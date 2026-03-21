# CANIN Project Story And AI Agent Guide

This file is the main project handoff document for future chats and AI agents.

Use it when you want:

- a full story of the project
- a quick understanding of the active system
- a map of every current module
- exact steps for changing any module safely

This repository started as a dental clinic system, but the active application has now been reshaped into a simpler **Physical Therapy Clinic Management System**.

The current live direction is no longer dental treatment planning, teeth charts, lab workflows, or prescriptions. The active product direction is now:

- patient management
- patient profile
- section or department management
- staff management and salary profiles
- users and login access
- roles and permissions
- reference doctors
- turns or appointments
- payments
- reports
- doctor or therapist leaves

The old dental files still exist in the repository in places, but they should be treated as legacy unless the current routes explicitly use them.

---

## 1. Project Story

### Original Story

The repository was originally a large CodeIgniter 3 clinic product built around dental workflows:

- patient intake
- tooth-by-tooth records
- dental departments
- prescriptions
- lab orders
- financial tracking
- user access control

That older version was centered around one very large controller and many dental-specific partial views.

### Current Story

The project is now being repositioned as a **simple, maintainable, responsive physical therapy clinic application**.

The current refactor focuses on:

- smaller modules
- clearer routes
- simpler CRUD flows
- dynamic section setup instead of hardcoded staff sections
- role-based access
- responsive UI
- bilingual support for Persian and English
- reusable structure for future AI-driven changes

### What This Means For Future Changes

If you are editing this project in a future chat, assume this:

1. The physical therapy version is the active product.
2. The route-based module set is the source of truth.
3. Legacy dental files are not the default place to build new features.
4. New work should extend the simplified structure, not the old dental architecture.

---

## 2. Tech Stack

| Layer | Details |
|---|---|
| Backend | PHP 7.4+, CodeIgniter 3 |
| Pattern | MVC |
| Database | MySQL / MariaDB |
| Frontend | Bootstrap 5 + custom CSS |
| Languages | Persian (`farsi`) and English (`english`) |
| RTL Support | Enabled for Persian |
| Auth | Session-based login |
| RBAC | Roles + permissions |
| Main stylesheet | `assets/css/app.css` |
| Main DB schema reference | `database/physical_therapy_clinic.sql` |

---

## 3. Active Application Structure

### Core bootstrap and shared system

- `application/config/routes.php`
- `application/core/MY_Controller.php`
- `application/libraries/Auth.php`
- `application/helpers/app_helper.php`
- `application/views/layout/header.php`
- `application/views/layout/footer.php`
- `assets/css/app.css`

### Controllers currently driving the active app

- `application/controllers/Login.php`
- `application/controllers/Preferences.php`
- `application/controllers/Dashboard.php`
- `application/controllers/Patients.php`
- `application/controllers/Reference_doctors.php`
- `application/controllers/Sections.php`
- `application/controllers/Staff.php`
- `application/controllers/Users.php`
- `application/controllers/Roles.php`
- `application/controllers/Turns.php`
- `application/controllers/Payments.php`
- `application/controllers/Reports.php`
- `application/controllers/Leaves.php`

### Models currently used by the active app

- `application/models/Login_model.php`
- `application/models/Dashboard_model.php`
- `application/models/Patient_model.php`
- `application/models/Reference_doctor_model.php`
- `application/models/Section_model.php`
- `application/models/Staff_model.php`
- `application/models/User_model.php`
- `application/models/Role_model.php`
- `application/models/Turn_model.php`
- `application/models/Payment_model.php`
- `application/models/Report_model.php`
- `application/models/Leave_model.php`

### Active views

- `application/views/login.php`
- `application/views/dashboard/`
- `application/views/patients/`
- `application/views/reference_doctors/`
- `application/views/sections/`
- `application/views/staff/`
- `application/views/users/`
- `application/views/roles/`
- `application/views/turns/`
- `application/views/payments/`
- `application/views/reports/`
- `application/views/leaves/`

### Language files

- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`
- `application/language/farsi/form_validation_lang.php`

---

## 4. First 10 Minutes For A New AI Agent

If you are a new AI agent entering this project, do this first and in this order:

1. Read this `CANIN.md` file fully.
2. Confirm the active product is the physical therapy clinic version, not the legacy dental version.
3. Read `application/config/routes.php` to identify the active module route.
4. Read `application/core/MY_Controller.php` to understand auth, rendering, locale, and theme bootstrapping.
5. Read the relevant controller for the requested module.
6. Read the related model.
7. Read the related views.
8. Read the related language keys in:
   - `application/language/english/app_lang.php`
   - `application/language/farsi/app_lang.php`
9. Check whether the change affects permissions.
10. Check whether the change affects responsiveness in `assets/css/app.css`.

### Do not assume

Do not assume:

- the old dental code is still active
- old `Admin.php` is the primary place for new work
- a legacy file should be edited just because it contains similar logic

### Trust order

When sources conflict, trust them in this order:

1. Active routes
2. Active controller
3. Active model
4. Active view
5. This `CANIN.md`
6. Legacy dental files only if still referenced

---

## 5. Active Routes

These are the main active routes:

- `/login`
- `/logout`
- `/dashboard`
- `/patients`
- `/reference_doctors`
- `/sections`
- `/staff`
- `/users`
- `/roles`
- `/turns`
- `/payments`
- `/reports`
- `/leaves`
- `/preferences/language/{locale}`
- `/preferences/theme/{theme}`

If a file is not connected to these active routes, treat it as legacy unless proven otherwise.

---

## 6. Module Dependency Map

This is the practical dependency map of the active system.

### Login dependencies

- `Login` depends on `Login_model`
- `Login` depends on `Auth`
- `Login` depends on `app_lang`
- `Login` depends on `login.php`

### Dashboard dependencies

- `Dashboard` depends on `Dashboard_model`
- `Dashboard` depends on `layout/header.php`
- `Dashboard` depends on `dashboard/index.php`

### Patients dependencies

- `Patients` depends on `Patient_model`
- `Patients` depends on `reference_doctors` for optional referral linkage
- patient profile depends on turns from `Patient_model::turn_history()`
- patient profile depends on payments from `Patient_model::payment_history()`
- patient UI depends on `patients/index.php`, `patients/form.php`, `patients/show.php`

### Reference Doctors dependencies

- `Reference_doctors` depends on `Reference_doctor_model`
- `Reference_doctors` depends on `Patient_model` data through `patients.referred_by`
- reference doctor UI depends on `reference_doctors/index.php`, `reference_doctors/form.php`, `reference_doctors/profile.php`

### Users dependencies

- `Users` depends on `User_model`
- `Users` depends on `Role_model`
- `Users` depends on role records existing in the database

### Roles dependencies

- `Roles` depends on `Role_model`
- `Roles` depends on `Auth`
- `Roles` affects navigation visibility
- `Roles` affects every permission-gated module

### Turns dependencies

- `Turns` depends on `Turn_model`
- `Turns` depends on `Patient_model`
- `Turns` depends on `User_model`
- bulk turns depend on `turns/bulk_form.php`
- patient profile depends on turn history
- reports depend on turn records

### Payments dependencies

- `Payments` depends on `Payment_model`
- `Payments` depends on `Patient_model`
- patient profile depends on payment history
- dashboard depends on payment totals
- reports depend on payment records

### Reports dependencies

- `Reports` depends on `Report_model`
- `Report_model` depends on turns, payments, leaves, and patients data

### Leaves dependencies

- `Leaves` depends on `Leave_model`
- `Leaves` depends on `User_model`
- reports depend on leave records
- future scheduling rules may depend on leaves if turn blocking is introduced

### Staff dependencies

- `Staff` depends on `Staff_model`
- `Staff` depends on `User_model`
- `Staff` depends on `Section_model`
- salary profile depends on `doctor_leaves`
- salary profile depends on `turns`
- salary profile depends on `users`
- staff section assignment depends on `sections`
- staff multi-section assignment depends on `staff_sections`

### Sections dependencies

- `Sections` depends on `Section_model`
- `Sections` affects Staff section assignment
- future turn pricing can depend on `sections.default_fee`

### Preferences dependencies

- `Preferences` depends on session state
- locale depends on language files
- theme depends on `assets/css/app.css`
- both login and layout depend on preferences

---

## 7. Module To Database Table Map

This section maps each active module to the main tables it reads or writes.

### Login

- reads: `users`
- reads: `roles`

### Dashboard

- reads: `patients`
- reads: `users`
- reads: `turns`
- reads: `payments`

### Patients

- writes: `patients`
- reads: `reference_doctors`
- reads: `turns`
- reads: `payments`

### Reference Doctors

- writes: `reference_doctors`
- reads: `patients` via `referred_by`

### Users

- writes: `users`
- reads: `roles`

### Staff

- writes: `staff`
- reads: `staff_types`
- reads: `sections`
- writes: `staff_sections`
- reads: `doctor_leaves`
- reads: `turns`
- reads: `users`

### Sections

- writes: `sections`
- reads: `staff`
- reads: `staff_types`
- reads: `staff_sections`

### Roles

- writes: `roles`
- writes: `permissions`
- writes: `role_permissions`
- indirectly affects: `users`

### Turns

- writes: `turns`
- reads: `patients`
- reads: `users`

### Payments

- writes: `payments`
- reads: `patients`

### Reports

- reads: `turns`
- reads: `payments`
- reads: `doctor_leaves`
- reads: `patients`
- reads: `users`

### Leaves

- writes: `doctor_leaves`
- reads: `users`

### Preferences

- no dedicated DB table currently
- session-driven only

---

## 8. Authentication, Roles, And Permissions

### Authentication flow

The login flow is handled by:

- `application/controllers/Login.php`
- `application/models/Login_model.php`
- `application/libraries/Auth.php`

The session stores the signed-in user and role information.

### Base controller flow

Shared logic is handled in:

- `application/core/MY_Controller.php`

Important rules:

- public pages should use `Base_Controller`
- protected pages should use `Authenticated_Controller`
- protected pages should call `require_permission(...)` where needed

### Current permission keys

- `manage_patients`
- `manage_reference_doctors`
- `manage_sections`
- `manage_staff`
- `manage_users`
- `manage_roles`
- `manage_turns`
- `manage_payments`
- `view_reports`
- `manage_leaves`

### Navigation rule

The top navigation in `application/views/layout/header.php` should only show modules that the current role can access.

---

## 9. UI Story

The current UI direction is:

- simple
- clinic-focused
- fast to understand
- responsive on desktop and mobile
- readable in Persian and English

### Typography

- Persian UI should use **Wazir**
- English UI should use **Inter**

### Responsive rule

Every new screen must remain usable on:

- desktop
- tablet
- mobile

This includes:

- wrapped header actions
- scrollable tables on smaller screens
- stacked or wrapped action buttons
- cards and forms with smaller mobile spacing

---

## 10. Module Overview

Below is the module-by-module story of the active system.

---

## 11. Module: Login

### Purpose

This module allows staff to sign in and enter the clinic system.

### Main files

- `application/controllers/Login.php`
- `application/models/Login_model.php`
- `application/views/login.php`
- `application/libraries/Auth.php`
- `application/core/MY_Controller.php`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`

### What it currently does

- shows the login screen
- validates username and password
- checks the user record
- verifies the password hash
- creates the authenticated session
- redirects to dashboard after successful login
- logs the user out

### If you want to change this module

Pass these steps:

1. Decide if the change is UI-only, logic-only, or both.
2. Update `Login.php` if the validation or redirect flow changes.
3. Update `Login_model.php` if the user lookup query changes.
4. Update `Auth.php` if session structure changes.
5. Update `login.php` if the login form or messages change.
6. Update language keys in both language files.
7. Verify Persian and English rendering.
8. Verify login, failed login, and logout.

### Common safe changes

- add remember-me behavior
- improve error messages
- add last-login tracking
- change redirect destination after login

### Common risky changes

- changing password hashing rules
- changing session key names
- changing role lookup structure

### AI prompt example for this module

> Read `CANIN.md` first. Work only on the Login module. Inspect `application/controllers/Login.php`, `application/models/Login_model.php`, `application/libraries/Auth.php`, and `application/views/login.php`. Keep the current session-based auth flow intact, preserve Persian and English support, and keep the login page responsive.

---

## 12. Module: Dashboard

### Purpose

This module gives the clinic a quick overview of the current state of the system.

### Main files

- `application/controllers/Dashboard.php`
- `application/models/Dashboard_model.php`
- `application/views/dashboard/index.php`
- `application/views/layout/header.php`
- `assets/css/app.css`

### What it currently does

- shows patient count
- shows user count
- shows today turns
- shows payments this month
- lists today’s turns
- lists recent payments

### If you want to change this module

Pass these steps:

1. Decide what new metric or widget is needed.
2. Add or update data queries in `Dashboard_model.php`.
3. Keep the data lightweight and fast.
4. Update `Dashboard.php` to pass the new data.
5. Update `dashboard/index.php` to render it.
6. Update translations if new labels are added.
7. Check mobile layout because dashboard cards can break on small screens.

### Common safe changes

- add a new KPI card
- add a “recent patients” block
- add a “pending leaves” block

### Common risky changes

- adding very heavy report-style queries
- turning dashboard into a large operational module

### AI prompt example for this module

> Read `CANIN.md` first. Work only on the Dashboard module. Inspect `application/controllers/Dashboard.php`, `application/models/Dashboard_model.php`, and `application/views/dashboard/index.php`. Add the requested dashboard change without breaking mobile responsiveness or slowing the page with heavy queries.

---

## 13. Module: Patients

### Purpose

This module manages patient records and patient profiles.

### Main files

- `application/controllers/Patients.php`
- `application/models/Patient_model.php`
- `application/views/patients/index.php`
- `application/views/patients/form.php`
- `application/views/patients/show.php`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`
- `database/physical_therapy_clinic.sql`

### What it currently does

- lists patients
- creates a patient
- edits a patient
- deletes a patient
- shows patient profile
- shows patient turn history
- shows patient payment history

### Current data fields

Core patient fields currently include:

- first name
- last name
- gender
- date of birth
- phone
- email
- address
- medical notes

### If you want to change this module

Pass these steps:

1. Decide whether the change affects list view, form, profile, or all.
2. Update validation in `Patients.php`.
3. Update payload handling in `patient_payload()`.
4. Update `Patient_model.php` if stored fields or joins change.
5. Update the correct views:
   - `index.php` for listing
   - `form.php` for create and edit
   - `show.php` for profile display
6. Update language files for new labels or messages.
7. If the schema changes, update `database/physical_therapy_clinic.sql`.
8. Verify CRUD and the profile page.
9. Verify mobile form layout and table layout.

### Common safe changes

- add emergency contact
- add occupation
- add injury or diagnosis summary
- add insurance fields

### Common risky changes

- changing the primary patient identity fields
- deleting fields used by turn or payment history

### AI prompt example for this module

> Read `CANIN.md` first. Work only on the Patients module. Inspect `application/controllers/Patients.php`, `application/models/Patient_model.php`, `application/views/patients/index.php`, `application/views/patients/form.php`, and `application/views/patients/show.php`. Keep patient CRUD simple, preserve patient profile history, and update both language files if you add visible text.

---

## 14. Module: Users

### Purpose

This module manages login accounts for clinic staff.

### Main files

- `application/controllers/Users.php`
- `application/models/User_model.php`
- `application/views/users/index.php`
- `application/views/users/form.php`
- `application/models/Role_model.php`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`

### What it currently does

- lists users
- creates users
- edits users
- deletes users
- assigns role
- stores active or inactive account status

### Current user fields

- first name
- last name
- username
- email
- phone
- role
- active status
- password

### If you want to change this module

Pass these steps:

1. Decide if the change affects account identity, role assignment, or access behavior.
2. Update validation in `Users.php`.
3. Update `user_payload()`.
4. Update `User_model.php` queries if new fields or joins are needed.
5. Update `users/index.php` if the list changes.
6. Update `users/form.php` if create/edit fields change.
7. Update language files.
8. Verify create, edit, delete, and inactive user behavior.
9. Verify you cannot break current signed-in user protection.

### Common safe changes

- add staff title
- add department
- add profile photo later
- add phone formatting rules

### Common risky changes

- changing `username` uniqueness behavior
- changing password behavior
- allowing deletion of currently signed-in user

### AI prompt example for this module

> Read `CANIN.md` first. Work only on the Users module. Inspect `application/controllers/Users.php`, `application/models/User_model.php`, `application/models/Role_model.php`, `application/views/users/index.php`, and `application/views/users/form.php`. Preserve role assignment, password behavior, and the protection against deleting the current signed-in user.

---

## 15. Module: Roles And Permissions

### Purpose

This module controls what each user role is allowed to do.

### Main files

- `application/controllers/Roles.php`
- `application/models/Role_model.php`
- `application/views/roles/index.php`
- `application/views/roles/form.php`
- `application/libraries/Auth.php`
- `application/views/layout/header.php`
- `database/physical_therapy_clinic.sql`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`

### What it currently does

- lists roles
- creates roles
- edits roles
- deletes roles when safe
- assigns permissions to roles
- protects modules through `require_permission(...)`
- hides menu items if the role lacks permission

### If you want to change this module

Pass these steps:

1. Decide whether you are changing role structure, permission structure, or both.
2. Update `Role_model.php` if permission syncing changes.
3. Update `Roles.php` if validation or behavior changes.
4. Update `roles/form.php` if the permission UI changes.
5. Update `Auth.php` only if permission resolution logic changes.
6. Update `header.php` if navigation access rules change.
7. If permissions are added or removed, update `database/physical_therapy_clinic.sql`.
8. Verify at least two different roles against the menu and protected routes.

### Common safe changes

- add a new permission key for a new module
- create a new role
- rename role labels

### Common risky changes

- changing permission keys without updating controller guards
- deleting roles that are assigned to users
- bypassing permission-aware navigation

### AI prompt example for this module

> Read `CANIN.md` first. Work only on the Roles and Permissions module. Inspect `application/controllers/Roles.php`, `application/models/Role_model.php`, `application/libraries/Auth.php`, `application/views/roles/`, and `application/views/layout/header.php`. Keep permission keys consistent with controller guards and keep navigation permission-aware.

---

## 16. Module: Turns

### Purpose

This module manages clinic appointments or turns.

### Main files

- `application/controllers/Turns.php`
- `application/models/Turn_model.php`
- `application/views/turns/index.php`
- `application/views/turns/form.php`
- `application/views/turns/bulk_form.php`
- `application/models/Patient_model.php`
- `application/models/User_model.php`
- `database/physical_therapy_clinic.sql`

### What it currently does

- lists turns
- creates one turn
- edits one turn
- deletes one turn
- supports bulk turn creation
- links turns to patient and therapist
- stores turn date, time, status, and notes

### Current turn statuses

- scheduled
- completed
- cancelled

### If you want to change this module

Pass these steps:

1. Decide if the change is for single turns, bulk turns, or both.
2. Update validation in `Turns.php`.
3. Update `turn_payload()`.
4. Update bulk row creation logic if bulk behavior changes.
5. Update `Turn_model.php` if the query or schema changes.
6. Update these views as needed:
   - `index.php`
   - `form.php`
   - `bulk_form.php`
7. Update language files for any new status or labels.
8. If schema changes, update `database/physical_therapy_clinic.sql`.
9. Verify CRUD plus bulk create.
10. Verify responsive behavior because the bulk form is sensitive on mobile.

### Common safe changes

- add duration
- add room number
- add appointment type
- add reminder flags

### Common risky changes

- changing date or time field names
- breaking the bulk turn flow
- adding heavy business logic only in the view

### AI prompt example for this module

> Read `CANIN.md` first. Work only on the Turns module. Inspect `application/controllers/Turns.php`, `application/models/Turn_model.php`, `application/views/turns/index.php`, `application/views/turns/form.php`, and `application/views/turns/bulk_form.php`. Preserve both single-turn and bulk-turn flows and verify the UI still works on mobile.

---

## 17. Module: Payments

### Purpose

This module records money received from patients.

### Main files

- `application/controllers/Payments.php`
- `application/models/Payment_model.php`
- `application/views/payments/index.php`
- `application/views/payments/form.php`
- `application/models/Patient_model.php`
- `application/models/Report_model.php`
- `database/physical_therapy_clinic.sql`

### What it currently does

- lists payments
- creates payments
- edits payments
- deletes payments
- shows total received
- links payments to patients

### Current payment fields

- patient
- payment date
- amount
- payment method
- reference number
- notes

### If you want to change this module

Pass these steps:

1. Decide whether the change is financial data, workflow, or reporting-related.
2. Update validation in `Payments.php`.
3. Update `payment_payload()`.
4. Update `Payment_model.php` if query behavior changes.
5. Update payment list and form views.
6. Update language keys.
7. If schema changes, update `database/physical_therapy_clinic.sql`.
8. Verify create, edit, delete, and totals.
9. Verify reports still read payment data correctly.

### Common safe changes

- add payment source
- add cashier name
- add invoice number
- add payment status later if required

### Common risky changes

- changing amount precision carelessly
- changing patient-payment relationship
- splitting one payment into unsupported sub-record logic without schema planning

### AI prompt example for this module

> Read `CANIN.md` first. Work only on the Payments module. Inspect `application/controllers/Payments.php`, `application/models/Payment_model.php`, `application/views/payments/index.php`, and `application/views/payments/form.php`. Preserve patient linkage and reporting compatibility, and update the schema reference if payment fields change.

---

## 18. Module: Reports

### Purpose

This module gives date-filtered operational reporting.

### Main files

- `application/controllers/Reports.php`
- `application/models/Report_model.php`
- `application/views/reports/index.php`
- `application/models/Turn_model.php`
- `application/models/Payment_model.php`
- `application/models/Leave_model.php`
- `application/models/Patient_model.php`

### What it currently does

- filters by date range
- shows summary cards
- shows turns in range
- shows payments in range
- shows leaves in range
- shows new patient count

### If you want to change this module

Pass these steps:

1. Decide whether the change is a summary metric or a detail block.
2. Add or update queries in `Report_model.php`.
3. Keep the date filters consistent across all report queries.
4. Update `Reports.php` if new filter inputs are needed.
5. Update `reports/index.php` for the UI.
6. Update language files.
7. Verify the date range inputs.
8. Verify mobile readability of report blocks.

### Common safe changes

- add therapist filter
- add payment method breakdown
- add completed vs cancelled turn counts

### Common risky changes

- mixing incompatible date fields
- turning reports into write operations
- adding slow queries without indexes or constraints

### AI prompt example for this module

> Read `CANIN.md` first. Work only on the Reports module. Inspect `application/controllers/Reports.php`, `application/models/Report_model.php`, and `application/views/reports/index.php`. Keep the module read-only, preserve date filtering consistency, and make sure the report remains readable on mobile.

---

## 19. Module: Leaves

### Purpose

This module tracks therapist or doctor leave periods.

### Main files

- `application/controllers/Leaves.php`
- `application/models/Leave_model.php`
- `application/views/leaves/index.php`
- `application/views/leaves/form.php`
- `application/models/User_model.php`
- `application/models/Turn_model.php`
- `database/physical_therapy_clinic.sql`

### What it currently does

- lists leave records
- creates leave records
- edits leave records
- deletes leave records
- links leave records to therapists
- stores leave status and reason

### Current leave statuses

- approved
- pending
- rejected

### If you want to change this module

Pass these steps:

1. Decide whether the change affects leave data only or should also affect turn scheduling.
2. Update validation in `Leaves.php`.
3. Update `leave_payload()`.
4. Update `Leave_model.php` if the query behavior changes.
5. Update the list and form views.
6. Update language files for new statuses or messages.
7. If schema changes, update `database/physical_therapy_clinic.sql`.
8. Verify create, edit, delete, and status display.
9. If leave should block turns in future, also update the turn module.

### Common safe changes

- add leave type
- add approval notes
- add longer description

### Common risky changes

- changing therapist linkage
- adding schedule-blocking behavior without updating turns

### AI prompt example for this module

> Read `CANIN.md` first. Work only on the Leaves module. Inspect `application/controllers/Leaves.php`, `application/models/Leave_model.php`, `application/views/leaves/index.php`, and `application/views/leaves/form.php`. Keep leave CRUD simple, and if leave should affect scheduling, update the Turns module deliberately instead of hiding logic in the view.

---

## 20. Module: Reference Doctors

### Purpose

This module manages referral-source doctors and tracks the patients each doctor has referred into the clinic.

### Main files

- `application/controllers/Reference_doctors.php`
- `application/models/Reference_doctor_model.php`
- `application/views/reference_doctors/index.php`
- `application/views/reference_doctors/form.php`
- `application/views/reference_doctors/profile.php`
- `application/views/layout/header.php`
- `application/views/roles/form.php`
- `application/models/Patient_model.php`
- `application/controllers/Patients.php`
- `database/physical_therapy_clinic.sql`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`

### What it currently does

- lists reference doctors
- creates reference doctor records
- edits reference doctor records
- soft-deactivates reference doctor records
- shows a profile page with doctor details and referral totals
- counts referred patients within a selected date range through AJAX
- shows all referred patients on the profile page
- supplies active doctors to the patient create and edit form

### Current data fields

- first name
- last name
- specialty
- phone
- clinic name
- address
- notes
- status

### If you want to change this module

Pass these steps:

1. Decide whether the change affects CRUD data, patient linkage, profile reporting, or all three.
2. Update validation in `Reference_doctors.php`.
3. Update `doctor_payload()` if stored fields change.
4. Update `Reference_doctor_model.php` if totals, joins, or date-range queries change.
5. Update the correct views:
   - `index.php`
   - `form.php`
   - `profile.php`
6. Update `Patients.php` and `Patient_model.php` if the patient referral contract changes.
7. Update both language files for new labels or messages.
8. If schema changes, update `database/physical_therapy_clinic.sql`.
9. Verify the permission gate, navigation visibility, patient form dropdown, and AJAX date-range count modal.

### Common safe changes

- add extra contact fields
- improve doctor profile presentation
- add more referral analytics
- add search or filters to the list

### Common risky changes

- changing the `patients.referred_by` relationship
- filtering patient referral counts by the wrong date field
- exposing inactive doctors in the patient form dropdown

### AI prompt example for this module

> Read `CANIN.md` first. Work only on the Reference Doctors module. Inspect `application/controllers/Reference_doctors.php`, `application/models/Reference_doctor_model.php`, `application/controllers/Patients.php`, `application/models/Patient_model.php`, and the views under `application/views/reference_doctors/`. Preserve `manage_reference_doctors`, keep patient referral optional, and keep the profile page bilingual and responsive.

---

## 21. Module: Staff

### Purpose

This module manages clinic staff records, linked login accounts, multi-section assignment, and on-demand salary calculations for a selected date range.

### Main files

- `application/controllers/Staff.php`
- `application/models/Staff_model.php`
- `application/models/Section_model.php`
- `application/views/staff/index.php`
- `application/views/staff/form.php`
- `application/views/staff/profile.php`
- `application/models/User_model.php`
- `application/models/Leave_model.php`
- `application/models/Turn_model.php`
- `database/physical_therapy_clinic.sql`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`

### What it currently does

- lists staff with type, assigned sections, and status
- creates staff records with optional linked user accounts
- auto-creates an inactive linked user when no existing user is selected
- edits staff records
- deactivates staff records without hard deletion
- re-activates inactive staff records
- shows a staff profile page
- assigns one or more sections to staff members who require sections
- counts completed turns from the previous calendar month when a linked user exists
- calculates salary impact from approved leave days on demand for a chosen date range

### If you want to change this module

Pass these steps:

1. Decide whether the change affects CRUD fields, section assignment, salary logic, or linked-user behavior.
2. Update validation in `Staff.php`.
3. Update `staff_payload()` if stored fields change.
4. Update `Staff_model.php` if joins, section syncing, or salary-related queries change.
5. Update the correct views:
   - `index.php`
   - `form.php`
   - `profile.php`
6. Update both language files for any new labels or messages.
7. If schema changes, update `database/physical_therapy_clinic.sql`.
8. Verify the permission gate, navigation visibility, CRUD flow, and salary calculator.
9. Verify the form and profile remain usable on mobile and in RTL.

### Common safe changes

- add more staff types
- add extra non-auth profile fields
- adjust salary profile presentation
- add richer staff profile metrics
- adjust section assignment rules by staff type

### Common risky changes

- changing the join path between staff and users
- changing how `staff_sections` syncs with `staff.section_id`
- changing how leave days are counted from `doctor_leaves`
- changing turn linkage without checking the salary profile queries

### AI prompt example for this module

> Read `CANIN.md` first. Work only on the Staff module. Inspect `application/controllers/Staff.php`, `application/models/Staff_model.php`, `application/models/Section_model.php`, and the views under `application/views/staff/`. Preserve the `manage_staff` permission gate, keep `doctor_leaves` read-only, preserve multi-section staff assignment through `staff_sections`, and keep the salary profile calculation aligned with the live schema.

---

## 22. Module: Sections

### Purpose

This module manages clinic sections or departments and stores each section's default fee for future turn pricing.

### Main files

- `application/controllers/Sections.php`
- `application/models/Section_model.php`
- `application/views/sections/index.php`
- `application/views/sections/form.php`
- `application/views/sections/show.php`
- `application/views/layout/header.php`
- `application/models/Role_model.php`
- `database/physical_therapy_clinic.sql`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`

### What it currently does

- lists all sections with default fee
- creates sections
- edits sections
- deletes sections
- shows a section detail page with section summary
- shows a simple staff distribution chart by staff type for that section
- shows the staff members assigned to that section
- provides dynamic section records for the Staff module

### If you want to change this module

Pass these steps:

1. Decide whether the change affects section data only or also staff assignment rules.
2. Update validation in `Sections.php`.
3. Update `Section_model.php` if delete behavior, chart queries, or section reads change.
4. Update the correct views:
   - `index.php`
   - `form.php`
   - `show.php`
5. Update both language files for any new labels or messages.
6. If schema changes, update `database/physical_therapy_clinic.sql`.
7. Verify the permission gate, navigation visibility, CRUD flow, and section chart page.
8. Verify mobile layout and RTL rendering.
9. If default fee should affect appointment pricing, also update the Turns module deliberately.

### Common safe changes

- add more section metadata
- change section list presentation
- improve the chart UI

### Common risky changes

- deleting sections without checking staff reassignment impact
- changing default fee behavior without updating turns
- breaking `staff_sections` synchronization with Staff

### AI prompt example for this module

> Read `CANIN.md` first. Work only on the Sections module. Inspect `application/controllers/Sections.php`, `application/models/Section_model.php`, and the views under `application/views/sections/`. Preserve the `manage_sections` permission gate, keep section records dynamic, keep `staff_sections` compatible with Staff, and do not silently couple fee changes into Turns unless the task explicitly requires it.

---

## 23. Module: Preferences

### Purpose

This module stores simple UI preferences.

### Main files

- `application/controllers/Preferences.php`
- `application/helpers/app_helper.php`
- `application/views/layout/header.php`
- `application/views/login.php`
- `assets/css/app.css`
- `application/core/MY_Controller.php`

### What it currently does

- switches language
- switches theme
- redirects back after change

### If you want to change this module

Pass these steps:

1. Decide if the change is session-only or should become persistent later.
2. Update `Preferences.php`.
3. Update helper usage if new preference helpers are needed.
4. Update header or login controls if the UI changes.
5. Update CSS if a new theme option is added.
6. Verify both login and authenticated pages.

### Common safe changes

- add compact layout option
- add extra language later

### Common risky changes

- breaking redirect-back behavior
- changing locale names without updating loaders

### AI prompt example for this module

> Read `CANIN.md` first. Work only on the Preferences module. Inspect `application/controllers/Preferences.php`, `application/helpers/app_helper.php`, `application/views/layout/header.php`, `application/views/login.php`, and `assets/css/app.css`. Preserve theme switching, language switching, Wazir for Persian, Inter for English, and responsive layout behavior.

---

## 24. Database Story

The active simplified schema reference is:

- `database/physical_therapy_clinic.sql`

This file defines the simplified physical therapy structure for:

- roles
- permissions
- users
- sections
- staff types
- staff
- staff sections
- patients
- reference doctors
- turns
- payments
- doctor leaves

If the database needs to evolve, update that file and then reflect the change in the related:

- controller validation
- model queries
- views
- language strings

---

## 25. Legacy Code Policy

This repository still contains old dental-era code.

Examples include:

- older `Admin.php`-driven flows
- dental patient component trees
- teeth and treatment-plan views
- prescription-related dental screens
- lab-specific dental flows

### Rule

Do not build new physical therapy functionality on top of the legacy dental structure unless there is a very strong reason and the active route actually still depends on it.

Prefer the simplified module structure.

### Do not touch list unless the task explicitly requires it

These files and areas should be treated as legacy and should not be the default place for new work:

- `application/controllers/Admin.php`
- `application/models/Admin_model.php`
- `application/views/header.php`
- `application/views/footer.php`
- `application/views/turns.php`
- `application/views/turns-old` if later added
- `application/views/patients/components/`
- `application/views/prints/`
- `application/views/phonebook.php`
- `application/views/labs.php`
- `application/views/accounts.php`
- `application/views/receipts.php`
- `application/views/primary_info.php`
- `application/views/primary_info/`
- `application/views/users-orginal.php`
- `application/views/users/components/`
- `application/views/users/user_role.php`
- `application/views/users/user_role_edit.php`
- `application/views/users/leave_requests.php`
- `application/views/demo5.php`
- `application/views/switcher.php`
- `application/libraries/Dentist.php` for new product logic
- dental SQL dumps such as `canin init.sql`
- migration notes and dental audit docs unless the task is historical cleanup

### Legacy subdomains of code that are not part of the active app

- tooth workflows
- restorative workflows
- endo workflows
- prosthodontics workflows
- treatment plans
- prescriptions from the dental flow
- dental lab order flow
- old multi-part patient dental component tree

### Before touching a legacy file

Pass these checks:

1. Confirm the active route still depends on it.
2. Confirm there is no active simplified replacement already in place.
3. Confirm the requested feature cannot be implemented cleanly in the new module structure.
4. Document clearly why you had to touch the legacy code.

---

## 26. Global Change Process For Any Module

If you want to change any module, use this exact sequence:

1. Find the active route in `application/config/routes.php`.
2. Find the matching controller action.
3. Find the model methods that support it.
4. Find the view files that render it.
5. Search for related references with `rg`.
6. Change validation first if input is changing.
7. Change the model if storage or query logic is changing.
8. Change the view only after the backend contract is clear.
9. Update both language files if you add labels or messages.
10. Update `database/physical_therapy_clinic.sql` if schema changes are required.
11. Check permission rules if access behavior changes.
12. Check `application/views/layout/header.php` if navigation visibility changes.
13. Run syntax checks with `php -l`.
14. Verify desktop and mobile.

---

## 27. Module-Specific Change Matrix

| Module | Start Here | Then Check | Then Update |
|---|---|---|---|
| Login | `Login.php` | `Login_model.php`, `Auth.php` | `login.php`, language files |
| Dashboard | `Dashboard.php` | `Dashboard_model.php` | `dashboard/index.php`, language files |
| Patients | `Patients.php` | `Patient_model.php` | `patients/index.php`, `patients/form.php`, `patients/show.php` |
| Reference Doctors | `Reference_doctors.php` | `Reference_doctor_model.php`, `Patient_model.php` | `reference_doctors/index.php`, `reference_doctors/form.php`, `reference_doctors/profile.php`, `patients/form.php`, `patients/show.php`, language files |
| Sections | `Sections.php` | `Section_model.php`, `Staff_model.php` | `sections/index.php`, `sections/form.php`, `sections/show.php`, language files |
| Staff | `Staff.php` | `Staff_model.php`, `User_model.php` | `staff/index.php`, `staff/form.php`, `staff/profile.php`, language files |
| Users | `Users.php` | `User_model.php`, `Role_model.php` | `users/index.php`, `users/form.php` |
| Roles | `Roles.php` | `Role_model.php`, `Auth.php` | `roles/index.php`, `roles/form.php`, `header.php` |
| Turns | `Turns.php` | `Turn_model.php`, `Patient_model.php`, `User_model.php` | `turns/index.php`, `turns/form.php`, `turns/bulk_form.php` |
| Payments | `Payments.php` | `Payment_model.php`, `Patient_model.php` | `payments/index.php`, `payments/form.php` |
| Reports | `Reports.php` | `Report_model.php` | `reports/index.php` |
| Leaves | `Leaves.php` | `Leave_model.php`, `User_model.php` | `leaves/index.php`, `leaves/form.php` |
| Preferences | `Preferences.php` | `app_helper.php` | `header.php`, `login.php`, `app.css` |

---

## 28. Exact File Paths By Module

This section is intentionally repetitive. It is here so future chats can jump directly into the correct files without re-discovering the structure.

### Login exact paths

- `application/controllers/Login.php`
- `application/models/Login_model.php`
- `application/libraries/Auth.php`
- `application/core/MY_Controller.php`
- `application/views/login.php`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`

### Dashboard exact paths

- `application/controllers/Dashboard.php`
- `application/models/Dashboard_model.php`
- `application/views/dashboard/index.php`
- `application/views/layout/header.php`
- `assets/css/app.css`

### Patients exact paths

- `application/controllers/Patients.php`
- `application/models/Patient_model.php`
- `application/views/patients/index.php`
- `application/views/patients/form.php`
- `application/views/patients/show.php`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`
- `database/physical_therapy_clinic.sql`

### Reference Doctors exact paths

- `application/controllers/Reference_doctors.php`
- `application/models/Reference_doctor_model.php`
- `application/views/reference_doctors/index.php`
- `application/views/reference_doctors/form.php`
- `application/views/reference_doctors/profile.php`
- `application/controllers/Patients.php`
- `application/models/Patient_model.php`
- `application/views/patients/form.php`
- `application/views/patients/show.php`
- `application/views/layout/header.php`
- `application/views/roles/form.php`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`
- `database/physical_therapy_clinic.sql`

### Sections exact paths

- `application/controllers/Sections.php`
- `application/models/Section_model.php`
- `application/models/Staff_model.php`
- `application/views/sections/index.php`
- `application/views/sections/form.php`
- `application/views/sections/show.php`
- `application/views/layout/header.php`
- `application/models/Role_model.php`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`
- `database/physical_therapy_clinic.sql`

### Staff exact paths

- `application/controllers/Staff.php`
- `application/models/Staff_model.php`
- `application/models/Section_model.php`
- `application/models/User_model.php`
- `application/views/staff/index.php`
- `application/views/staff/form.php`
- `application/views/staff/profile.php`
- `application/views/layout/header.php`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`
- `database/physical_therapy_clinic.sql`

### Users exact paths

- `application/controllers/Users.php`
- `application/models/User_model.php`
- `application/models/Role_model.php`
- `application/views/users/index.php`
- `application/views/users/form.php`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`

### Roles exact paths

- `application/controllers/Roles.php`
- `application/models/Role_model.php`
- `application/libraries/Auth.php`
- `application/views/roles/index.php`
- `application/views/roles/form.php`
- `application/views/layout/header.php`
- `database/physical_therapy_clinic.sql`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`

### Turns exact paths

- `application/controllers/Turns.php`
- `application/models/Turn_model.php`
- `application/models/Patient_model.php`
- `application/models/User_model.php`
- `application/views/turns/index.php`
- `application/views/turns/form.php`
- `application/views/turns/bulk_form.php`
- `database/physical_therapy_clinic.sql`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`

### Payments exact paths

- `application/controllers/Payments.php`
- `application/models/Payment_model.php`
- `application/models/Patient_model.php`
- `application/views/payments/index.php`
- `application/views/payments/form.php`
- `application/models/Report_model.php`
- `database/physical_therapy_clinic.sql`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`

### Reports exact paths

- `application/controllers/Reports.php`
- `application/models/Report_model.php`
- `application/views/reports/index.php`
- `application/models/Turn_model.php`
- `application/models/Payment_model.php`
- `application/models/Leave_model.php`
- `application/models/Patient_model.php`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`

### Leaves exact paths

- `application/controllers/Leaves.php`
- `application/models/Leave_model.php`
- `application/models/User_model.php`
- `application/views/leaves/index.php`
- `application/views/leaves/form.php`
- `application/models/Turn_model.php`
- `database/physical_therapy_clinic.sql`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`

### Preferences exact paths

- `application/controllers/Preferences.php`
- `application/helpers/app_helper.php`
- `application/core/MY_Controller.php`
- `application/views/layout/header.php`
- `application/views/login.php`
- `assets/css/app.css`

---

## 29. AI Prompt Library

These prompts are designed to be pasted into future chats.

### Full-project prompt

> Read `CANIN.md` first. This repository is a simplified Physical Therapy Clinic Management System built on CodeIgniter 3. Ignore legacy dental code unless an active route still depends on it. Work only on the active modules: login, dashboard, patients, reference doctors, sections, staff, users, roles, turns, payments, reports, leaves, and preferences. Preserve Wazir for Persian, Inter for English, and keep the whole app responsive.

### Login prompt

> Read `CANIN.md` first. Update only the Login module. Start with `application/controllers/Login.php`, `application/models/Login_model.php`, `application/libraries/Auth.php`, and `application/views/login.php`. Preserve session-based authentication, bilingual support, and responsive UI.

### Dashboard prompt

> Read `CANIN.md` first. Update only the Dashboard module. Start with `application/controllers/Dashboard.php`, `application/models/Dashboard_model.php`, and `application/views/dashboard/index.php`. Keep queries lightweight and keep the dashboard responsive.

### Patients prompt

> Read `CANIN.md` first. Update only the Patients module. Start with `application/controllers/Patients.php`, `application/models/Patient_model.php`, and the views under `application/views/patients/`. Preserve patient CRUD, patient profile history, and bilingual responsive UI.

### Reference Doctors prompt

> Read `CANIN.md` first. Update only the Reference Doctors module. Start with `application/controllers/Reference_doctors.php`, `application/models/Reference_doctor_model.php`, `application/controllers/Patients.php`, `application/models/Patient_model.php`, and the views under `application/views/reference_doctors/`. Preserve `manage_reference_doctors`, keep `patients.referred_by` optional, and keep the date-range modal and patient profile integration responsive and bilingual.

### Sections prompt

> Read `CANIN.md` first. Update only the Sections module. Start with `application/controllers/Sections.php`, `application/models/Section_model.php`, and the views under `application/views/sections/`. Preserve `manage_sections`, keep sections dynamic, keep `staff_sections` compatible with Staff, and only connect default fees into Turns when the task explicitly asks for it.

### Staff prompt

> Read `CANIN.md` first. Update only the Staff module. Start with `application/controllers/Staff.php`, `application/models/Staff_model.php`, `application/models/Section_model.php`, and the views under `application/views/staff/`. Preserve `manage_staff`, keep multi-section staff assignment aligned with `staff_sections`, keep the salary profile aligned with `doctor_leaves` and `turns`, and keep the whole flow bilingual and responsive.

### Users prompt

> Read `CANIN.md` first. Update only the Users module. Start with `application/controllers/Users.php`, `application/models/User_model.php`, `application/models/Role_model.php`, and the views under `application/views/users/`. Preserve role assignment, password behavior, and current-user deletion protection.

### Roles prompt

> Read `CANIN.md` first. Update only the Roles and Permissions module. Start with `application/controllers/Roles.php`, `application/models/Role_model.php`, `application/libraries/Auth.php`, `application/views/roles/`, and `application/views/layout/header.php`. Keep permission keys consistent and keep the navigation permission-aware.

### Turns prompt

> Read `CANIN.md` first. Update only the Turns module. Start with `application/controllers/Turns.php`, `application/models/Turn_model.php`, and the views under `application/views/turns/`. Preserve both single and bulk turn flows and verify mobile behavior.

### Payments prompt

> Read `CANIN.md` first. Update only the Payments module. Start with `application/controllers/Payments.php`, `application/models/Payment_model.php`, and the views under `application/views/payments/`. Preserve patient linkage, reporting compatibility, and responsive forms and tables.

### Reports prompt

> Read `CANIN.md` first. Update only the Reports module. Start with `application/controllers/Reports.php`, `application/models/Report_model.php`, and `application/views/reports/index.php`. Keep it read-only, date-consistent, and responsive.

### Leaves prompt

> Read `CANIN.md` first. Update only the Leaves module. Start with `application/controllers/Leaves.php`, `application/models/Leave_model.php`, and the views under `application/views/leaves/`. Keep leave CRUD simple, and if leave should affect scheduling, update the Turns module deliberately.

### Preferences prompt

> Read `CANIN.md` first. Update only the Preferences module. Start with `application/controllers/Preferences.php`, `application/helpers/app_helper.php`, `application/views/layout/header.php`, `application/views/login.php`, and `assets/css/app.css`. Preserve theme switching, language switching, Wazir for Persian, Inter for English, and responsive behavior.

---

## 30. Language And Content Rules

When adding UI text:

1. Add it to `application/language/english/app_lang.php`
2. Add it to `application/language/farsi/app_lang.php`
3. Use `t('...')` in views and controllers where appropriate

Do not leave new visible UI strings untranslated if the rest of the module is localized.

---

## 31. Responsive Rules

Every module change must be checked for:

- mobile table overflow
- wrapped action buttons
- readable form spacing
- header controls wrapping correctly
- RTL alignment still looking correct in Persian

Primary CSS file:

- `assets/css/app.css`

If a module needs special responsive styling, prefer adding small, focused additions there instead of creating a scattered style system.

---

## 32. Validation Checklist Before Finishing Any Change

Before saying a task is complete, verify:

1. Route still resolves correctly.
2. PHP syntax is clean for every changed file.
3. Permission checks still make sense.
4. Navigation visibility still makes sense.
5. Language keys are present.
6. Persian still renders correctly.
7. Mobile layout still works.
8. The change does not accidentally revive legacy dental behavior.

---

## 33. Best Prompt To Reuse In Other Chats

If you want to continue this project in another chat, you can paste something like this:

> Read `CANIN.md` first. This repository is now a simplified Physical Therapy Clinic Management System built on CodeIgniter 3. Ignore legacy dental code unless an active route still depends on it. Work only on the active modules: login, dashboard, patients, reference doctors, sections, staff, users, roles, turns, payments, reports, leaves, and preferences. Preserve Wazir for Persian, Inter for English, and keep everything responsive.

---

## 34. Final Rule

When in doubt:

- trust the active routes
- trust the simplified module structure
- keep the app simple
- keep it responsive
- keep Persian and English support intact
- do not rebuild on top of legacy dental complexity unless absolutely necessary
