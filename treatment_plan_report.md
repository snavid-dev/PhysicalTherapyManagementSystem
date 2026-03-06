# CANIN Codebase Analysis: Treatment Plan / درمان

This report outlines the current implementation state of the "treatment plan" (طرح درمان) features in the CANIN codebase based on the existing controllers, models, views, and database schema.

## 1. Existing Related Code

**Controllers (`Admin.php`)**
*   `insert_recommended_processes()` (Line 5665): Handles inserting new recommended treatments for a patient's tooth.
*   `get_recommended_by_turn()` (Line 5579): Fetches recommendations that have been tied to a specific appointment (turn).
*   `delete_treatment()`: Deletes an added treatment.
*   `get_doctor_for_plan()` (Line 7769): Identifies the assigned doctor for a specific grouped treatment plan.

**Models (`Admin_model.php`)**
*   Handles DB operations for `turn_tooth_recommended` and `turn_tooth_done`.
*   `get_unassigned_tooth_recommendations()` (Line 893): Fetches recommended processes for a patient where `turn_id IS NULL` (meaning they haven't been scheduled yet).
*   `get_department_services_with_processes()` (Line 703): Fetches available treatments for selection based on the department (Endo, Restorative, Prosthodontics).

**Views**
*   **`patients/components/status/status.php` & `status_js.php`**: The UI hub for recording recommendations. Contains the `recommended_processes` modal. JavaScript functions like `list_teeth_recommended()` and `list_treatment_plan()` manage the interactions.
*   **`patients/components/main/TreatmentPlan/`**: 
    *   `treatment_plan.php`: The main table view displaying the treatment plan.
    *   `insert_modal.php`: Modal containing the treatment plan selection dropdown.
*   **`patients/components/main/Turns/insert_modal.php`**: The "New Turn" (appointment) modal includes a field (`name="plan"`) that allows a receptionist to link a scheduled turn specifically to a pre-defined treatment plan.

## 2. Database

*   **`tooth`** (Exists in `canin init.sql`): The main table storing individual patient teeth.
*   **`turn_tooth_recommended`**: The core table for the "plan". It maps a `tooth_id` to a `process_id` (a specific treatment). 
    *   *Key Behavior:* It contains a `turn_id` column. If `turn_id` is `NULL`, the recommendation is a "floating" plan. If `turn_id` is populated, it means the treatment has been scheduled in an appointment.
*   **`turn_tooth_done`**: Tracks procedures that have been fully executed.
*   > [!WARNING]
    > **Schema Desync:** Both `turn_tooth_recommended` and `turn_tooth_done` tables are actively queried and updated in `Admin_model.php` but **do NOT exist** in the `canin init.sql` schema dump.

## 3. Current Flow

1.  **Doctor Recommends:** A doctor opens a patient's Status page and clicks "Recommended Processes" (`status.php`). They select a tooth, a department, and the required processes.
2.  **Plan Saved:** These selections are saved to `turn_tooth_recommended` with a `NULL` `turn_id` via `admin/insert_recommended_processes`.
3.  **Receptionist Books:** When booking a new appointment (Turn), the `Turns/insert_modal.php` dynamically groups these floating recommendations into a "Treatment Plan" dropdown option.
4.  **Plan Scheduled:** If the receptionist selects that plan, the backend updates the `turn_tooth_recommended` records, setting their `turn_id` to that new appointment's ID (effectively converting the raw plan into scheduled tasks).

## 4. Gaps & Missing Pieces

*   **Missing Database Tables in SQL Dump:** The most critical gap is that `turn_tooth_recommended` and `turn_tooth_done` are missing from `canin init.sql`. A fresh install would currently crash when encountering these DB queries.
*   **Lack of Independent Entity:** "Treatment Plan" currently acts merely as a pending list of recommendations waiting to be attached to a Turn. There is no independent `treatment_plan` table defining overall multi-visit phases, total estimated costs, priorities, or start/end goals. Plans are generated purely on-the-fly by grouping unassigned recommendations.
*   **Financials / Estimation:** There doesn't appear to be a mechanism to generate an upfront cost estimate or print a fully priced, phased treatment plan document for the patient before they commit to booking turns.

## 5. Related Permissions

Currently, explicit RBAC permissions for accessing the treatment plan are not seeded in the database dump (`canin init.sql`). The UI controls are heavily bundled with the `Turns` and `Patients` features. Any dedicated permissions like `treatment_plan_view` or `treatment_plan_insert` would need to be checked in the live `permissions` table, but no such hardcoded strings were found deeply isolated from general patient permissions.

## 6. Related Language Keys

The system relies on Language engine mappings for UI text. The UI directly queries these exact keys via `$ci->lang()`:
*   `'treatment plan'` 
*   `'recommended processes'`
*   `'tooth type'`
*   `'Tooth Position and Name'`
*   `'tooth name'`
*   `'tooth location'`
