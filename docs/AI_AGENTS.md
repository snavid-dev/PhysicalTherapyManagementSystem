# AI Agent Guide

This document is the working guide for AI agents that add, change, or remove functionality in this project.

## Project Purpose

This application is a simple Physical Therapy Clinic Management System built on CodeIgniter 3.

The active product direction is:

- Patient management
- Patient profile
- User login and user CRUD
- Roles and permissions
- Turn management
- Payments
- Reports
- Doctor or therapist leaves

Anything from the older dental system should be treated as legacy unless it is explicitly still used by the current routes and views.

## Active Application Structure

The app currently uses the new simplified module set.

Important files and folders:

- `application/config/routes.php`
- `application/core/MY_Controller.php`
- `application/controllers/`
- `application/models/`
- `application/views/layout/`
- `application/views/dashboard/`
- `application/views/patients/`
- `application/views/users/`
- `application/views/roles/`
- `application/views/turns/`
- `application/views/payments/`
- `application/views/reports/`
- `application/views/leaves/`
- `application/language/english/app_lang.php`
- `application/language/farsi/app_lang.php`
- `application/language/farsi/form_validation_lang.php`
- `assets/css/app.css`
- `database/physical_therapy_clinic.sql`

## Architectural Rules

When changing this project, follow these rules:

1. Keep the app simple.
2. Prefer extending the current module set instead of reviving old dental-specific code.
3. Use the new controllers, models, and views, not the legacy dental pages.
4. Keep business logic in models and controllers, not in views.
5. Keep routes explicit in `application/config/routes.php`.
6. Keep language strings in the app language files instead of hardcoding UI text where practical.
7. Preserve RTL support for Persian and LTR support for English.
8. Preserve full responsiveness on mobile, tablet, and desktop.

## Authentication and Permissions

Authentication is handled through:

- `application/libraries/Auth.php`
- `application/core/MY_Controller.php`

Base conventions:

- Public pages should extend `Base_Controller`.
- Protected pages should extend `Authenticated_Controller`.
- Use `$this->require_permission('permission_name')` in protected modules.
- Navigation should only show modules the current user can access.

Current permission keys:

- `manage_patients`
- `manage_users`
- `manage_roles`
- `manage_turns`
- `manage_payments`
- `view_reports`
- `manage_leaves`

If a new module is added:

1. Add a permission record.
2. Add role-permission assignments.
3. Gate the controller with `require_permission(...)`.
4. Gate the navigation item in `application/views/layout/header.php`.

## Database Rules

The simplified schema is documented in:

- `database/physical_therapy_clinic.sql`

If schema changes are needed:

1. Update `database/physical_therapy_clinic.sql`.
2. Update the affected models.
3. Update validation rules in controllers.
4. Update the corresponding views and reports.

Do not introduce new database tables unless they are necessary for the clinic workflow.

## UI and Styling Rules

Shared layout:

- `application/views/layout/header.php`
- `application/views/layout/footer.php`

Shared styling:

- `assets/css/app.css`

UI requirements:

- Persian UI must use Wazir font.
- English UI should remain clean and readable.
- All pages must stay responsive.
- Tables must remain usable on mobile with horizontal scrolling where needed.
- Action buttons must stack or wrap cleanly on small screens.
- Do not reintroduce heavy dental-era dashboard styling or unused assets into the active UI.

## Adding a New Feature

When adding a feature:

1. Decide whether it belongs in an existing module first.
2. Add or update the route.
3. Create or update the controller action.
4. Add validation rules.
5. Add or update model methods.
6. Add or update views.
7. Add language strings in both English and Farsi.
8. Update permissions if access control is needed.
9. Verify mobile responsiveness.
10. Run syntax checks on every changed PHP file.

## Removing a Feature

When removing a feature:

1. Remove the route.
2. Remove links to it from the layout.
3. Remove or archive the controller/model/view code.
4. Remove permission checks and permission records if they are no longer needed.
5. Remove language strings that are no longer referenced.
6. Confirm no active route or view still depends on the removed code.

Do not remove old files blindly if they may still be referenced indirectly. Search first.

## Validation and Testing

Minimum validation after edits:

1. Run `php -l` on every changed PHP file.
2. Load the relevant route locally if possible.
3. Check both Persian and English rendering if the UI changed.
4. Check mobile behavior if layout or forms changed.

Recommended checks:

- Login flow
- Dashboard page
- Patient CRUD
- User CRUD
- Role CRUD
- Turn CRUD
- Payment CRUD
- Reports page
- Leave CRUD

## Legacy Code Policy

This repository still contains older dental-specific files. Treat them as legacy.

Do not build new functionality on top of:

- old dental patient components
- tooth, treatment-plan, prescription, and lab workflows
- old `Admin`-driven dental UI

If legacy files are not referenced by the active routes, prefer leaving them untouched or removing them in a dedicated cleanup step.

## Safe Change Strategy

Before making broad changes:

1. Inspect the active route.
2. Inspect the controller.
3. Inspect the model.
4. Inspect the view.
5. Search for references with `rg`.

After changes:

1. Run syntax checks.
2. Verify no new missing language keys.
3. Verify permission-aware navigation still works.
4. Verify responsive layout still works.

## Output Expectations for Future Agents

When handing off or documenting changes, always state:

- what was changed
- which files were touched
- whether database changes are required
- whether language files were updated
- whether responsive behavior was considered
- what was verified and what was not verified
