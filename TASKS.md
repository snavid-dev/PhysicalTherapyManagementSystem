# CANIN — Active Task Roadmap

This file is the **single source of truth** for the current iteration of work on the Physical Therapy Clinic Management System.

It is intended to be read by Claude Code (or any future AI agent) **alongside** `CANIN.md`. `CANIN.md` describes the architecture, modules, and rules. **This file describes what to build next.**

---

## How An AI Agent Must Use This File

1. **Always read `CANIN.md` first.** It contains the architecture, module dependencies, naming, RBAC, language, responsiveness, and legacy-code policy.
2. **Then read this file.** Pick up at the first milestone whose status is `IN PROGRESS` or `NOT STARTED`.
3. **Work on one milestone at a time.** Do not start a later milestone until the user has explicitly approved the previous one.
4. **After finishing a milestone:**
   - Update its status in this file to `READY FOR REVIEW`.
   - Summarize what was changed (files touched, schema changes, permissions added, language keys added) in the milestone's `Implementation Log` section.
   - **Stop. Do not proceed to the next milestone.** Wait for the user's explicit approval (a message like "approved, continue to M2").
5. **If a milestone is rejected or has changes requested**, treat the user's follow-up as scoped to that milestone only. Do not silently touch later milestones.
6. **Follow the global rules from `CANIN.md`:**
   - Update both `english/app_lang.php` and `farsi/app_lang.php` for any new visible string
   - Update `database/physical_therapy_clinic.sql` for any schema change
   - Permission keys must be consistent across controller guards, `roles/form.php`, and `header.php`
   - All new tables/screens must be responsive and bilingual (Wazir for Persian, Inter for English)
   - Do not build on legacy dental files unless an active route requires it
   - All dates: store Gregorian, display Shamsi with Western digits

---

## Milestone Status Legend

- `NOT STARTED` — not yet touched
- `IN PROGRESS` — agent is currently working on this
- `READY FOR REVIEW` — agent has completed, awaiting user approval
- `APPROVED` — user has signed off; safe to reference but do not re-edit

---

## Milestone Order

| # | Milestone | Status |
|---|---|---|
| M1 | Patient financial flow — debt payment, refunds, auto-reconcile, daily income | NOT STARTED |
| M2 | Editable turn payment with wallet/safe reconciliation | NOT STARTED |
| M3 | Patient creation — dual submit buttons | NOT STARTED |
| M4 | Reports — doctor filter, debtors list, new patients report | NOT STARTED |
| M5 | Leaves ↔ salary calculation audit and fix | NOT STARTED |
| M6 | Expenses — edit existing + bulk add | NOT STARTED |

---

# M1 — Patient Financial Flow

**Status:** NOT STARTED

**Goal:** Make the wallet, debt, refund, and daily-income flows behave the way the clinic actually operates. Today a patient can only pay during a turn, refunds aren't tracked in the safe, and the wallet doesn't auto-reconcile a prior debt when the patient later puts money in.

## Scope

This milestone covers four interconnected behaviors. They must be implemented together because they touch the same models (`Patient_model`, `Wallet_model`, `Debt_model`, `Safe_model`).

### M1.1 — Standalone debt/credit payment (no turn required)

- A patient must be able to pay money into the clinic **without** an associated turn — for example, to clear an existing debt, or to top up the wallet in advance.
- **Entry point:** a clearly-labeled button on the **patient profile page** (`application/views/patients/show.php`). Examples of acceptable labels: "Receive payment" / "دریافت پرداخت".
- The button opens a modal or small page with: amount, payment date (default today, Shamsi datepicker), optional note. No turn fields, no doctor, no section.
- On submit:
  - If the patient has a non-zero debt, apply the payment to the debt first (oldest first if there are multiple debt rows). Mark debts as `paid` when fully covered.
  - Any remainder goes into `patient_wallet` as a top-up via `patient_wallet_transactions`.
  - Write **one** `safe_transactions` row with `type=in`, `source=patient_payment`, reference to the patient.
  - All three writes (debt update, wallet top-up, safe row) must be in a single DB transaction.

### M1.2 — Patient refund flow (NEW — Issue #12)

- A "Refund" / "مستردی" button on the patient profile, visible only when wallet balance > 0.
- Form: amount (cannot exceed current wallet balance), date, note.
- On submit:
  - Deduct from `patient_wallet` via a new `patient_wallet_transactions` row (`type=refund`).
  - Write **one** `safe_transactions` row with `type=out`, `source=patient_refund`, reference to the patient.
  - All writes in a single DB transaction.
- The refund must appear in the safe ledger as a separate, labeled outflow — **not** mixed into expenses, and **not** silently subtracted.
- Add a dedicated **"Refunds"** summary line to the Safe page summary cards (today, month) and to any daily-income totals.

### M1.3 — Auto-reconcile wallet vs debt

- **Current bug (per user):** when a patient has debt (negative-balance situation) and later pays, the system doesn't always clear the debt — the secretary has to manually fix it.
- **Required behavior:** any inflow to a patient — whether from M1.1 standalone payment, a turn payment with `top_up > 0`, or any wallet credit — must:
  1. First check `patient_debts` for unpaid rows for that patient.
  2. Apply the inflow to the oldest unpaid debt(s) until either the inflow is exhausted or all debts are cleared.
  3. Send any remainder to the wallet.
- The patient profile must always display the **correct net** in this priority: outstanding debt first, then wallet balance. There must never be a state where a patient simultaneously has both an unpaid debt and a positive wallet balance.

### M1.4 — Daily income report includes standalone payments and refunds

- Today the daily register/income report shows turn-driven payments.
- It must also include:
  - **Standalone patient payments from M1.1** as an income line, with patient name and note visible.
  - **Patient refunds from M1.2** as a separate negative line (or in a dedicated "Refunds" subtotal — implementer's choice, but it must be visually distinct from regular income).
- The "Total income for the day" figure shown to the manager must equal: cash from turns + standalone payments − refunds. This must match the safe ledger summary for the same day.

## Acceptance Criteria (must all pass before status → READY FOR REVIEW)

- [ ] Patient profile page has "Receive payment" button. Submitting it with a debt-bearing patient clears the debt first, sends remainder to wallet, and writes exactly one safe row.
- [ ] Patient profile page has "Refund" button. It's hidden when wallet balance is zero. Submitting it deducts from wallet and writes exactly one safe-out row labeled as a refund.
- [ ] **Regression test (must pass):** Patient with 200 AFN debt. Manager records standalone payment of 500 AFN. Result: debt cleared, wallet balance = 300, exactly one safe-in row of 500, dashboard shows no remaining debt on this patient.
- [ ] **Regression test (must pass):** Patient with 0 debt and 600 wallet. Refund of 250. Result: wallet = 350, exactly one safe-out row of 250 with source `patient_refund`, daily report shows 250 in the Refunds line.
- [ ] Daily register report numerically matches the safe ledger for the same day, across at least one day that mixes turn payments, standalone payments, and refunds.
- [ ] All new buttons, modals, and labels exist in both `english/app_lang.php` and `farsi/app_lang.php`.
- [ ] No patient anywhere in the database is left in the impossible state of (unpaid debt AND positive wallet). If migration is needed for existing data, add a one-time reconciliation pass.
- [ ] All new screens work on mobile and in RTL.

## Files Expected To Be Touched

- `application/controllers/Patients.php` — new actions for standalone payment & refund
- `application/models/Patient_model.php`
- `application/models/Wallet_model.php`
- `application/models/Debt_model.php`
- `application/models/Safe_model.php` — new source labels `patient_payment`, `patient_refund`
- `application/views/patients/show.php` — new buttons + modals
- `application/controllers/Reports.php` + `application/models/Report_model.php` — daily income query
- `application/views/reports/index.php` — refund line
- `application/controllers/Safe.php` + `application/views/safe/index.php` — Refunds summary card
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`
- `database/physical_therapy_clinic.sql` — if any new column or source enum value is needed

## Implementation Log

_(Agent fills in after completing — list of files changed, migrations, gotchas)_

---

# M2 — Editable Turn Payment

**Status:** NOT STARTED

**Goal:** Allow authorized users to edit a turn's payment amount after the turn has been created, with full wallet and safe reconciliation. This fixes the locked-field complaint and the Marziya-Gholam-Haidar wallet bug.

## Scope

### M2.1 — Unlock the payment fields on existing turns

- On the turn edit form (`application/views/turns/form.php`), the fields `fee`, `cash_collected`, `wallet_used`, and `top_up` must be editable when the current user has the new permission `edit_processed_payments`.
- For users without that permission, the fields stay read-only exactly as today.

### M2.2 — Reconcile wallet and safe on edit

When a turn payment is edited, the system must:

1. Compute the **delta** between the old payment breakdown and the new one (per field: fee, cash, wallet_used, top_up).
2. **Reverse** the old wallet and debt effects, then **apply** the new ones, inside a single DB transaction.
3. **Adjust the original safe entry in place** (per user decision Q2). Find the `safe_transactions` row(s) originally written by this turn, update their amounts, and recompute `balance_after` snapshots for that row and all later rows in the ledger.
4. If the change would make any historical `balance_after` go negative, abort the transaction and show the user a clear error.

### M2.3 — Marziya regression test

The Marziya case must be the explicit regression scenario for this milestone:

- Patient: prepaid 600 AFN, no debt.
- Turn A created: fee 180, wallet_used 180. Wallet → 420.
- Turn B created: fee 180, wallet_used 180. Wall
