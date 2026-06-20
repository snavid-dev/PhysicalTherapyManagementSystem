# TASKS.md — CANIN Physical Therapy Clinic Refactor Plan

This file is the master task list for the next round of changes on the CANIN clinic system. It is meant to be read together with `CANIN.md`. `CANIN.md` describes the project. This file describes the work.

---

## 0. Execution Protocol (Read This First)

Any AI agent working on this file must follow these rules without exception.

1. Read `CANIN.md` fully before doing anything. This file extends `CANIN.md`; it does not replace it.
2. Work on **exactly one milestone at a time**, in the order written. Do not start the next milestone until the human explicitly approves with `continue to M[N]` or `approved, proceed`.
3. For each milestone:
   - Implement every task under the milestone.
   - Add every UI string to **both** `application/language/english/app_lang.php` and `application/language/farsi/app_lang.php`.
   - Update `database/physical_therapy_clinic.sql` if schema changes.
   - Run `php -l` on every changed file.
   - Manually verify every entry in the **Acceptance Criteria** section of the milestone.
   - Verify desktop **and** mobile responsiveness, and RTL correctness in Persian.
   - Verify permission guards still gate the right routes.
   - Write a short completion report (template at bottom of this file) and **STOP**.
4. Do not touch legacy dental files unless explicitly instructed. Refer to `CANIN.md` section 28.
5. If anything in this file conflicts with `CANIN.md`, ask before deciding. Do not silently resolve conflicts.
6. If a task cannot be completed cleanly without expanding scope, stop and report instead of expanding scope.
7. **Git rules — strict. The human owns all commits.**
   - At the start of each milestone, create a new branch from the current main/development branch named `milestone/m[N]-<short-slug>` (for example `milestone/m1-wallet-integrity`, `milestone/m2-edit-turn-payment`, `milestone/m3-daily-income-reports`). Confirm the branch is created and checked out before making any code changes.
   - **Never run `git commit`, `git add`, `git push`, `git stash`, `git merge`, `git rebase`, `git reset`, `git restore`, `git checkout -- <file>`, or any other command that writes to the index, the staging area, or the commit history.**
   - Do not amend, squash, or rewrite any existing commits.
   - Leave the working tree dirty at the end of the milestone. The human reviews with `git diff`, stages what they want, and commits themselves.
   - If a write-level git command is run by accident, stop immediately and report it in the completion report under a section titled "⚠️ Git accidents". Do not attempt to undo it.
   - The only git commands that may be run are read-only: `git status`, `git diff`, `git log`, `git branch`, `git show`, plus the two branch-management commands `git switch -c <new-branch>` (to create the milestone branch) and `git switch <branch>` (only to confirm you are on the right branch). These two are the only writes to git state that are allowed, and only at the very start of a milestone.

---

## Milestone 1 — Patient Wallet, Debt, and Refund Integrity

### Goal

Make the patient financial flow correct and complete so that:

- Patients can pay debts without booking a turn.
- Patients with both wallet credit and open debt are automatically reconciled.
- Refunds are tracked through the safe and shown in reports.
- Wallet balances stay correct after **any** change to past turns, payments, or refunds.

This milestone is the foundation for M2, M3, and M5. Do not skip or compress it.

### Real-World Bugs This Milestone Must Fix

**Bug A — Phantom debtors (Poorya, 5/15)**
A patient finishes all their sessions, ends up with 100 AFN of debt from past turns, and later prepays 100 AFN. The 100 AFN sits in their wallet and the 100 AFN debt also stays open. Net real position: zero. The debtors report still lists them as a debtor.

**Bug B — Wallet inflation on past-turn edit (Marziya scenario)**
- Patient prepaid 600 AFN. Per-session fee = 180.
- Used 2 sessions → wallet should be 240.
- Secretary edits both turns' fees from 180 → 170 to match the physical card.
- Expected wallet after edit: 260. Actual wallet after edit: 440.
- Per Poorya's note: this class of bug appears whenever past turns or payments are edited — the wallet inflates above the true value.

### Tasks

**1.1 — Wallet recalculation engine**

Create a single authoritative function that recomputes a patient's wallet balance from scratch by replaying all their transactions in chronological order. Live location: `application/models/Wallet_model.php`.

- Public API: `recalculate_for_patient($patient_id)` returns the new balance and also persists it.
- It must consider, in chronological order: turn fees, cash payments tied to turns, wallet top-ups, standalone debt payments (1.3), refunds (1.4), and debt creations/clearances.
- It must be deterministic and idempotent — calling it twice in a row produces the same result.
- It must run inside a DB transaction.
- It must be called automatically after every write that affects financial state. Add the call to:
  - `Turn_model` create / update / delete
  - `Wallet_model` top-up
  - The new standalone payment endpoint (1.3)
  - The new refund endpoint (1.4)
  - `Patient_model` debt write paths

**1.2 — Auto-reconcile debt against wallet credit**

After `recalculate_for_patient()` runs, if the patient has any open `patient_debts` rows AND a positive wallet balance, automatically apply wallet credit to oldest debt first until either wallet is zero or all debts are settled.

- Each auto-applied settlement must create a `patient_wallet_transactions` row of type `auto_debt_settlement` and update the corresponding `patient_debts` row to settled.
- Do not write a `safe_transactions` row for auto-reconcile — no real cash moved.
- Patient profile must show these auto-settlements clearly in the wallet activity list.

**1.3 — Standalone debt payment from patient profile**

Per Q1: a button on the patient profile page (not a separate page).

- Add a "ثبت پرداخت / Record Payment" button on `application/views/patients/show.php`.
- The button opens a modal with: amount, payment date (Shamsi), note.
- On submit:
  - Write a row in `payments` linked to the patient (no turn id).
  - Write a `safe_transactions` row, source = `patient_debt_payment`.
  - Call `recalculate_for_patient()` which will auto-clear debts via 1.2.
- The patient profile wallet/debt section must reflect the payment immediately.
- Permission required: `manage_turns` (same as existing payment workflows).

**1.4 — Refund flow from patient profile**

Per Q6: full refund flow.

- Add a "مستردی / Refund" button on `application/views/patients/show.php`, only enabled when wallet balance > 0.
- The button opens a modal with: amount (max = current wallet balance), refund date (Shamsi), note.
- On submit:
  - Write a row in `patient_wallet_transactions` type = `refund`, negative amount.
  - Write a `safe_transactions` row, type = outflow, source = `patient_refund`.
  - Call `recalculate_for_patient()`.
- Add `patient_refund` to the safe source enum / lookup if one exists.
- Permission required: `manage_turns`.

### Schema Changes

- If `patient_wallet_transactions.type` is an ENUM, add `auto_debt_settlement` and `refund`.
- If `safe_transactions.source` is an ENUM, add `patient_debt_payment` and `patient_refund`.
- Update `database/physical_therapy_clinic.sql` accordingly.

### Files Likely To Change

- `application/models/Wallet_model.php`
- `application/models/Debt_model.php`
- `application/models/Patient_model.php`
- `application/models/Turn_model.php`
- `application/models/Safe_model.php`
- `application/controllers/Patients.php`
- `application/views/patients/show.php`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`
- `database/physical_therapy_clinic.sql`

### Acceptance Criteria

The milestone is not complete until **every** item below is verified by reproducing it in the running system.

1. **Bug A reproduction passes**: a patient with 100 AFN debt and 100 AFN wallet, after triggering any wallet write (or by running recalculation manually), ends up with 0 wallet, 0 open debt, and an `auto_debt_settlement` transaction visible on their profile. They no longer appear on the debtors list.
2. **Bug B reproduction passes**: replay the Marziya scenario — 600 prepay, 2 turns at 180, then edit both turns to 170. Wallet ends at 260. Not 440.
3. The "Record Payment" button on the patient profile records a debt payment without requiring a turn. The safe ledger shows the inflow with source `patient_debt_payment`.
4. The "Refund" button on the patient profile decreases the wallet and writes a safe outflow with source `patient_refund`.
5. Refund button is disabled when wallet balance ≤ 0.
6. Calling `recalculate_for_patient()` twice in a row produces identical state (idempotency).
7. All new strings exist in both language files. Persian UI renders correctly in RTL. Mobile layout of the new modals is usable.

### STOP. Write completion report. Wait for `continue to M2`.

---

## Milestone 2 — Editable Turn Payment

### Goal

Allow editing the payment amount on an already-created turn, with proper safe and wallet reconciliation, gated behind a manager-only permission.

This milestone is light because M1 already gave us the wallet recalculation engine. M2 mostly unlocks fields and adds a permission.

### Tasks

**2.1 — New permission `edit_processed_payments`**

Per Q5: a new permission.

- Add `edit_processed_payments` to the permissions list (DB + role form + `Auth.php`).
- Seed it onto the manager role by default.
- Add a description string in both language files.
- Update `database/physical_therapy_clinic.sql`.

**2.2 — Unlock payment fields on the turn edit form**

- In `application/views/turns/form.php`, payment fields (`payment_type`, `fee`, `wallet_used`, `cash_collected`, `wallet_topup_amount`) are currently read-only in edit mode. Make them editable **only if** the current user has `edit_processed_payments`.
- For users without the permission, keep current read-only behavior.
- Show a small note next to the unlocked fields: "ویرایش این مقادیر کیف پول و دخل را دوباره محاسبه می‌کند / Editing these recalculates the wallet and safe."

**2.3 — Safe entry adjustment on edit**

Per Q2: adjust the original safe entry in place.

- In `application/controllers/Turns.php` update flow:
  - If the payment-affecting fields changed, locate the original `safe_transactions` row(s) tied to that turn (by `source = 'turn_payment'` and `reference_id = turn_id`) and update them in place.
  - Recompute `balance_after` for that row and every subsequent row chronologically. This is the only sane way to keep the running balance consistent.
  - Run the whole thing inside a DB transaction.
- Then call `Wallet_model::recalculate_for_patient()` which will resolve debt/wallet shifts automatically (M1 handles the rest).

### Acceptance Criteria

1. A user without `edit_processed_payments` sees payment fields locked, exactly as before M2.
2. A manager (with the permission) can edit `fee`, `cash_collected`, `wallet_used`, `wallet_topup_amount`, and `payment_type` on an existing turn.
3. Marziya scenario from M1 Bug B can now be reproduced through the UI: edit two turns 180→170, wallet ends at 260.
4. The original `safe_transactions` row for that turn is updated, not duplicated.
5. The safe ledger running balance (`balance_after`) is consistent on every row after the edit, not just the edited one.
6. Permission appears in the role-edit screen in both languages.

### STOP. Write completion report. Wait for `continue to M3`.

---

## Milestone 3 — Daily Income & Refund Reporting

### Goal

Make the daily register / daily income report correctly reflect everything that touched the safe today, including the new flows introduced in M1.

### Tasks

**3.1 — Daily income report shows standalone debt payments**

- The existing daily register (`/reports/daily-register`) reads from turns or safe; verify which.
- Standalone debt payments (M1 task 1.3) must appear as their own line item or as part of an income total.
- Add a "Debt Payments (no turn)" subtotal alongside the existing turn-payment totals.

**3.2 — Daily income report shows refunds as a separate negative line**

- Refunds (M1 task 1.4) must appear as a separate line/subtotal labeled "مستردی‌ها / Refunds", displayed as a negative value or in a clearly distinct visual style.
- Net daily income line = turn payments + debt payments − refunds − expenses (cash outflow) − salary payments.

**3.3 — Print version of daily register includes the new lines**

- The print version of `/reports/daily-register/print` must include the new lines in the same structure.

### Files Likely To Change

- `application/controllers/Reports.php`
- `application/models/Report_model.php`
- `application/views/reports/` (daily register view and print view)
- Both language files

### Acceptance Criteria

1. On a day where standalone debt payments occurred, the daily register shows them in their own line and they roll up into the day's income.
2. On a day where refunds occurred, the daily register shows them in a separate clearly negative line.
3. Net daily income math reconciles with the safe `balance_after` change for the day.
4. Print view matches screen view.

### STOP. Write completion report. Wait for `continue to M4`.

---

## Milestone 4 — Patient Creation UX

### Goal

Two submit buttons on the patient create form: one that saves and returns to the list (current behavior), one that saves and opens the new patient's profile.

### Tasks

- On `application/views/patients/form.php`, in **create mode only**, render two submit buttons:
  - "ذخیره / Save" — current behavior, returns to patient list.
  - "ذخیره و باز کردن پروفایل / Save & Open Profile" — saves then redirects to the new patient's profile.
- In `application/controllers/Patients.php`, distinguish the two via a `submit_action` hidden field or button name.
- Edit mode is unchanged.

### Acceptance Criteria

1. Both buttons appear in create mode and behave as described.
2. Validation errors on either button return to the create form with input preserved.
3. Edit mode shows only the current Save button.
4. Mobile layout: buttons stack or wrap, both remain reachable without horizontal scroll.

### STOP. Write completion report. Wait for `continue to M5`.

---

## Milestone 5 — Reports Enhancements

### Goal

Three additions to the Reports module:

- Doctor (therapist) filter on patient/turn reports.
- Debtors list with printable + PDF output.
- New-patients-by-date-range report.

### Tasks

**5.1 — Doctor filter on patient reports**

- On `application/views/reports/index.php` (or the relevant report views), add a doctor/therapist filter alongside the existing department and date-range filters.
- The dropdown is populated from active staff records (or users with a therapist role — match whatever the existing department filter joins on).
- Filter applies to the visible report blocks (turns in range, patient counts).
- Persist the selection on the URL so the filter is shareable / refresh-safe.

**5.2 — Debtors list with print + PDF**

Per Q3: both formats.

- New report page: `/reports/debtors` (or under `/reports`, behind `view_reports`).
- Lists every patient with `wallet balance < 0` OR open `patient_debts` rows after M1's reconciliation.
- Columns: patient name, father name, phone, last turn date, total debt amount.
- "Print" button → print-friendly HTML view at `/reports/debtors/print`.
- "PDF" button → server-side PDF generation. Use TCPDF or dompdf, whichever is already a dependency of this CI3 project. If neither is, ask before adding one.
- The print/PDF must be physically usable by the secretary: clear header with clinic name and date, large readable rows.

**5.3 — New patients report**

- A new report block (or its own page under `/reports`) listing new patients within a date range.
- Inputs: start date and end date (Shamsi). Default: last 30 days.
- Output: count of new patients in the range + a list (table) of those patients.
- Reuse the existing patient creation date field. Confirm which field that is before writing the query — likely `patients.created_at`.

### Acceptance Criteria

1. Doctor filter narrows turn and patient results to the selected therapist. Combined with date range and department, filters compose correctly.
2. Debtors list shows exactly the patients with real outstanding balance (post-M1). Bug A patient must NOT appear.
3. Print view of debtors list is readable on standard A4 with no JS chrome.
4. PDF download produces a file with the same data.
5. New patients report counts and lists patients within the date range correctly. Patients created on the boundary days are included.
6. All new strings in both language files. RTL correct. Mobile readable.

### STOP. Write completion report. Wait for `continue to M6`.

---

## Milestone 6 — Leaves and Salary

### Goal

Per Q4 answer B: diagnose and fix the salary-vs-leaves calculation in one pass, with a clean UX.

### Tasks

**6.1 — Diagnose**

Before writing fixes, verify by reading code and live data:

- The join path between `staff.user_id` and `doctor_leaves.doctor_id` (CANIN.md flags this as fragile).
- How `Salary_model` currently subtracts leave days from monthly salary.
- Whether leave **status** is honored (only `approved` should deduct).
- Whether overlapping leave periods are double-counted.
- Whether partial-month leaves are handled correctly.

Write the diagnosis as 3–6 short bullet points at the top of the completion report, then proceed to fix.

**6.2 — Fix salary calculation**

- All salary deductions must come exclusively from `doctor_leaves` rows where `status = 'approved'` and the leave date falls within the salary calculation period.
- Overlapping leave periods must be deduplicated by date.
- Daily rate = monthly salary ÷ working days in that month (confirm whether the system uses calendar days or working days; if not specified, use calendar days and note this in the report).
- The salary breakdown UI on `staff/profile.php` and `salaries/pay.php` must show: base salary, leave days, deduction, net.

**6.3 — UX cleanup**

- On the leaves form, make status (`approved` / `pending` / `rejected`) a clear, accessible control.
- On the staff salary profile, show a "Leave impact" line that itemizes the leave days deducted from the current period's salary.

### Acceptance Criteria

1. Two leave rows for the same date for the same staff member only deduct one day.
2. Pending and rejected leaves never affect salary.
3. The salary breakdown UI lines up with what the user can verify by hand from the leave list.
4. The diagnosis section of the completion report is concrete and references file/line evidence.

### STOP. Write completion report. Wait for `continue to M7`.

---

## Milestone 7 — Expenses Enhancements

### Goal

Add editing of existing expenses and a bulk-entry form for multiple expenses on the same date.

### Tasks

**7.1 — Edit existing expenses**

- Add an "Edit" action to the expense list (`application/views/expenses/index.php`).
- The expense form (`application/views/expenses/form.php`) must support edit mode.
- **Hard rule (per CANIN.md):** expenses tied to salary payments (`expenses` rows linked from `staff_salary_payments`) must remain non-editable from this UI. Show a clear disabled state with a tooltip explaining why.
- If the amount changes, the corresponding `safe_transactions` outflow row must be updated in place (same pattern as M2 task 2.3) and downstream `balance_after` recomputed.

**7.2 — Bulk expenses page**

- New route: `/expenses/bulk` (or a button on the expense list).
- Form layout based on the existing turns bulk pattern (`application/views/turns/bulk_form.php`).
- Single date input at the top (Shamsi).
- A dynamic table of rows. Each row: category, description, amount, optional staff member.
- "+" button adds rows. Each row has a remove button. There must always be at least one row.
- Select2 must be initialized on each newly added row (CANIN.md Select2 rules).
- On submit, all rows are saved within one DB transaction. Each row writes its own `expenses` row and its own `safe_transactions` row.
- Validation: every row needs a category and amount > 0. Empty rows are skipped.

### Acceptance Criteria

1. A non-salary expense can be edited, including changing the amount, and the safe stays consistent.
2. A salary-linked expense shows as non-editable and the form refuses to save it.
3. Bulk page can submit 10 expense rows in one go, sharing one date.
4. After bulk submit, the expenses list shows all 10 rows and the safe ledger shows 10 outflows.
5. If one row fails validation, the whole transaction rolls back and the user sees an error.
6. Mobile usability of the bulk table is acceptable (rows wrap or scroll cleanly).

### STOP. Final completion report.

---

## Completion Report Template

After each milestone, write a report using this exact structure:

```
## Milestone [N] — [name] — COMPLETED

### Branch
- Created: milestone/m[N]-<slug>
- Confirmed checked out: yes

### Files changed (NOT committed — left in working tree for human review)
- path/to/file1.php — [one-line reason]
- path/to/file2.php — [one-line reason]

### Schema changes
- [none, or list of ALTER / new tables]

### Language keys added
- en: [count] — examples: t('record_payment'), t('refund')...
- fa: [count] — same keys

### Acceptance criteria verification
1. ✅ / ❌ — [criterion 1 short name] — [how verified]
2. ✅ / ❌ — ...

### Deviations from spec
- [none, or list with justification]

### Known issues / TODOs
- [none, or list]

### ⚠️ Git accidents
- [none — only fill in if a write-level git command ran by accident]

### Ready for human review. Working tree dirty, no commits made. Awaiting "continue to M[N+1]".
```

---

## Appendix — Quick Reference

- `CANIN.md` is the project map. Read it first.
- All dates in DB are Gregorian. UI is Shamsi. Use `to_shamsi()` and `to_gregorian()`.
- All new UI strings go in **both** language files.
- All money flows must touch `safe_transactions`. No silent cash movement.
- All wallet-affecting writes must call `Wallet_model::recalculate_for_patient()` (built in M1).
- Permissions to be aware of: `manage_turns`, `manage_expenses`, `manage_salaries`, `view_reports`, `view_safe`, `manage_safe`, and the new `edit_processed_payments` (M2).
- **You never commit. The human commits.** One branch per milestone. Read-only git commands only, except for the two branch-creation commands at the start of each milestone.