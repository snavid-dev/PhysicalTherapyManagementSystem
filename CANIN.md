# Canin — Project Context File
> ⚠️ Read this file before doing anything in this project.

---

## 1. What Is Canin?
A full-featured **dental clinic management system** (نرم‌افزار مطب دندانپزشکی) for **شفاخانه تخصصی ثنا کوثر** in Herat, Afghanistan. It covers everything from patient registration and per-tooth clinical records to lab orders, prescriptions, financials, and multi-user access control.

---

## 2. Tech Stack
| Layer | Technology |
|---|---|
| Backend | PHP 7.4+, CodeIgniter 3 (MVC) |
| Database | MariaDB/MySQL — db name: `canin` |
| Frontend | Bootstrap 5, AJAX/XHR, DataTables, Select2, Toastr.js |
| Dates | Jalali/Shamsi throughout — stored as `varchar`, Jalali Date Picker on frontend |
| Languages | Farsi (fa), English (en), Pashto (pa) — cookie-based, RTL for fa/pa |
| CDN | `canin-cdn.cyborgtech.co` (online) / `localhost/canin-cdn` (local) |
| PWA | `manifest.json` + `service-worker.js` |

---

## 3. Key Files & Structure
```
canin/
├── application/
│   ├── controllers/
│   │   ├── Admin.php           ← Main controller (159 methods, ~8400 lines)
│   │   ├── Login.php           ← Authentication
│   │   └── Roles.php           ← Role management
│   ├── models/
│   │   └── Admin_model.php     ← Main data layer (69KB)
│   ├── views/
│   │   └── patients/components/ ← 51 component partials
│   ├── libraries/
│   │   ├── Dentist.php         ← Clinic config, teeth, departments, currency
│   │   ├── MyLibrary.php       ← Helpers, Jalali date conversion, hashing
│   │   ├── Language.php        ← Translation engine (82KB)
│   │   └── Auth.php            ← Permission checking
│   └── config/
│       ├── database.php        ← DB: canin @ localhost
│       └── routes.php          ← Default: Login controller
├── assets/                     ← Local CSS/JS overrides
├── patient_files/              ← Uploaded X-rays and patient files
├── user_images/                ← Staff profile photos
├── canin init.sql              ← Full DB schema + seed data
└── CANIN.md                    ← ⬅ This file
```

---

## 4. Current Modules
1. **Auth & RBAC** — Session login, 73 permissions, 8 categories, custom roles
2. **Patient Management** — Permanent + temp/walk-in patients, QR lookup, status (pending/accepted/blocked)
3. **Dental Clinical Records** — Per-tooth tracking across 3 departments:
   - Restorative (filling material, composite brand, bonding, etc.)
   - Endodontic/Root Canal (up to 5 canals, widths, obturation method, sealer)
   - Prosthodontics (crown type, shade, impression, cement, etc.)
4. **Appointments/Turns** — Time slots, doctor assignment, 3-call log, statuses
5. **Lab Orders** — Multi-stage: `first_try → second_try → receive → install → pay`
6. **Prescriptions** — Up to 10 medicines per prescription, templates, printable
7. **Patient Files** — X-ray uploads stored in `patient_files/`
8. **Financial Management** — CR/DR balance sheet, receipts, accounts
9. **Reports** — Receipts, income, expenses, balance — all Jalali date filtered
10. **Phonebook / Call Log** — Failed/missed call tracking per patient
11. **User Management** — Staff accounts, image cropping, role assignment
12. **Primary Info / Lookups** — Services, medicines, diagnoses, categories CRUD

---

## 5. Database — Key Tables
| Table | Purpose |
|---|---|
| `users` | Staff accounts with roles and working hours |
| `patient` / `temp_patient` | Permanent and walk-in patients |
| `tooth` | Individual tooth records per patient |
| `restorative` / `endo` / `prosthodontics` | Per-tooth treatment data |
| `turn` / `temp_turn` | Appointments |
| `labs` | Lab orders |
| `prescription` | Up to 10 drugs as flat columns (denormalized) |
| `balance_sheet` / `customers` | Financial ledger and accounts |
| `services` / `medicine` / `diagnose` | Lookup/reference data |
| `roles` / `permissions` / `role_permissions` | RBAC system |
| `waiting_room` | Exists in schema but unused in code |

---

## 6. Hard Constraints — Never Violate
- ❌ **No full page reloads** — all CRUD via AJAX returning JSON
- ❌ **No hardcoded UI strings** — all text must go through the `Language` library
- ✅ **Jalali dates only** — use `MyLibrary::getCurrentShamsiDate()`, store as `varchar`
- ✅ **RTL layout** must stay intact for `fa` and `pa` languages
- ✅ **Permission checks** (`check_permission_function` / `check_permission_page`) required on every new controller method
- ✅ **Follow existing patterns** in `Admin.php` when adding new methods
- ✅ **CDN assets** stay on `canin-cdn.cyborgtech.co` — do not change URLs

---

## 7. Known Issues (Do Not Worsen)
| Issue | Detail |
|---|---|
| Weak password hashing | Triple MD5 with static salt — do not change existing auth |
| No CSRF protection | CI3 CSRF middleware not enabled — do not rely on it |
| Hardcoded `dir="ltr"` | RTL CSS overrides it — leave as-is |
| SMS disabled | `MyLibrary::sendSms()` always returns `true` — stubbed out |
| Dead routes | `home/tasks`, `home/jobs`, `home/companies` → no controller exists |
| `waiting_room` unused | Table exists but no complete controller flow |
| Dev artifacts at root | `navid.php`, `phpstorm.php`, `demo5.php`, `new.json` — do not touch or delete |

---

## 8. Git Workflow — Mandatory for Every Task

Every task, no matter how small, must follow this Git workflow. No exceptions.

### Branch Naming Convention
```
<type>/<short-description>

Types:
  feat/     → adding a new feature
  fix/      → bug fix
  remove/   → removing a feature or code
  refactor/ → code restructure without behavior change
  db/       → database schema changes only
  style/    → UI/CSS only changes
  config/   → config file changes

Examples:
  feat/waiting-room-management
  fix/jalali-date-turn-display
  remove/phonebook-module
  db/add-patient-notes-column
```

### Step-by-Step Git Flow
```bash
# 1. Always branch off main (or develop if it exists)
git checkout main
git pull origin main
git checkout -b feat/your-feature-name

# 2. Make your changes

# 3. Stage only relevant files
git add <specific files>   # never use: git add .

# 4. Commit with structured message (see format below)
git commit -m "..."

# 5. Push branch
git push origin feat/your-feature-name
```

### Commit Message Format
Every commit must follow this structure exactly:
```
<type>(<scope>): <short summary>

What changed:
- <file path> → <what was changed>
- <file path> → <what was changed>

Why:
<reason this change was made>

Affected:
- DB tables: <table names or "none">
- Permissions: <new permission keys or "none">
- Language keys: <new keys added or "none">
```

**Real example:**
```
feat(turns): add doctor leave conflict warning on turn creation

What changed:
- application/controllers/Admin.php (line 1842) → added leave check before turn insert
- application/models/Admin_model.php (line 673) → added get_doctor_leaves_by_date() method
- application/views/turns.php (line 94) → added warning toast trigger in JS

Why:
Receptionist was booking turns on dates when the doctor was on leave,
causing scheduling conflicts with no visual warning.

Affected:
- DB tables: doctor_leave (read only)
- Permissions: none
- Language keys: turn_doctor_on_leave_warning (fa/en/pa)
```

---

## 9. Change Report — Required After Every Task

After completing any task, you MUST produce a change report in this format:

```
═══════════════════════════════════════════
 CANIN CHANGE REPORT
 Task: <task name>
 Branch: <branch name>
 Date: <Jalali date>
═══════════════════════════════════════════

📁 FILES CHANGED
───────────────────────────────────────────
File: application/controllers/Admin.php
  Line 1842 — ADDED: leave conflict check before turn insert
  Line 1850 — MODIFIED: return JSON now includes `leave_conflict` flag
  Reason: Prevent booking on doctor leave dates

File: application/models/Admin_model.php
  Line 673 — ADDED: get_doctor_leaves_by_date($doctor_id, $date)
  Reason: New query needed to check leave by date

File: application/views/turns.php
  Line 94 — ADDED: JS handler for leave_conflict flag → shows warning toast
  Reason: Visual feedback to receptionist

───────────────────────────────────────────
🗃️ DATABASE CHANGES
  Table: none
  (or): ALTER TABLE turn ADD COLUMN notes VARCHAR(255) NULL AFTER status;
  Reason: <why>

───────────────────────────────────────────
🔑 NEW PERMISSIONS
  none
  (or): turn_override_leave — allows admin to book over doctor leave

───────────────────────────────────────────
🌐 NEW LANGUAGE KEYS
  none
  (or): turn_doctor_on_leave_warning → "Doctor is on leave on this date"

───────────────────────────────────────────
⚠️ RISKS & SIDE EFFECTS
  - None
  (or): Existing turns on leave dates are NOT retroactively flagged

───────────────────────────────────────────
✅ GIT COMMANDS USED
  git checkout -b feat/waiting-room-management
  git add application/controllers/Admin.php
  git add application/models/Admin_model.php
  git add application/views/turns.php
  git commit -m "feat(turns): add doctor leave conflict warning on turn creation ..."
  git push origin feat/waiting-room-management
═══════════════════════════════════════════
```

---

## 10. How to Work on This Project

### ➕ Adding a Feature
1. Identify affected controller method(s), model method(s), and view partial(s)
2. Follow the AJAX + JSON response pattern used throughout `Admin.php`
3. Add a permission check at the top of the method
4. Add language keys for any new UI text
5. Use Jalali dates — never Gregorian

### ➖ Removing a Feature
1. List all affected files: controller method, model method, view partial, DB table/column
2. Warn if removal breaks dependent features
3. Prefer **soft-removal** (hide from UI) over hard deletion unless explicitly asked

### 🐛 Debugging
1. Trace: `Admin.php` → `Admin_model.php` → view component
2. Check Jalali date handling first (most common failure point)
3. Check AJAX response format — must return valid JSON
4. Check permission key spelling against `permissions` table

---

## 11. Prompt to Start Any Chat
Paste this at the start of every new conversation:

```
Read CANIN.md in the project root before starting.
It has the full project context, constraints, and patterns — do not ask what the project is.

For every task you must:
1. Create a Git branch using the naming convention in CANIN.md Section 8
2. Make changes following the constraints in Section 6
3. Commit with the structured message format in Section 8
4. Produce a full Change Report using the format in Section 9
```
