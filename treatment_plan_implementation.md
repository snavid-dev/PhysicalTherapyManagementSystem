# Treatment Plan Delete & Update

This plan outlines the steps to implement the missing Delete and Update functionalities for the treatment plan module.

## Proposed Changes

### Controller Layer
#### [MODIFY] `application/controllers/Admin.php`
- Add `delete_treatment()`: Receives a single ID, looks up the `recommendation_name` and `patient_id` from `turn_tooth_recommended`, and deletes all processes associated with that specific plan. Returns standard JSON success/error.
- Add `get_treatment_plan_for_edit()`: Fetches the details of a specific treatment plan to populate the edit modal.
- Add `update_recommended_process()`: Mirrors `insert_recommended_processes()`, but first deletes the existing plan (or updates it) and inserts the new selections.
- Add permission checks `check_permission_function()` to all new methods.

### Model Layer
#### [MODIFY] `application/models/Admin_model.php`
- Add `get_recommended_process_by_id($id)`: Helper to get a specific record (used to find the name of the plan to delete/edit).
- Add `delete_treatment_plan_by_name($patient_id, $name)`: Deletes all rows in `turn_tooth_recommended` for a given patient and plan name.
- Add `get_plan_details_by_name($patient_id, $name)`: Fetches all teeth and selected processes data for a specific plan to pre-fill the edit modal.

### Frontend Views
#### [MODIFY] `application/views/patients/components/main/TreatmentPlan/treatment_plan.php`
- Add the `Edit` button next to the `Delete` button, invoking `edit_treatment_plan(id)`.
- Update the `Delete` button handler to ensure the ID corresponds correctly to backend expectations.

#### [NEW] `application/views/patients/components/status/edit_recommended_process_modal.php`
- Duplicate the structure of `recommended_process_modal.php` but adjust IDs (e.g., `edit_processForm`, `edit_process_teeth`) for the update flow.

#### [MODIFY] `application/views/patients/components/status/status_js.php`
- Add `edit_treatment_plan(id)` function: makes an AJAX call to load data, populates `edit_recommended_process_modal.php`, and shows the modal.
- Add `get_edit_teeth_process()` logic to correctly render checkboxes for the edit modal, pre-checking previously selected processes.

#### [MODIFY] `application/views/patients/components/status/status.php`
- Include the new `edit_recommended_process_modal.php` inside the status view so it is available in the DOM.

### Language System
#### [MODIFY] `application/libraries/Language.php`
- Append the following keys for Farsi/English/Pashto:
  - `delete treatment plan` -> 'Treatment plan deleted successfully'
  - `update treatment plan` -> 'Treatment plan updated successfully'
  - `edit treatment plan` -> 'Edit Treatment Plan'

## Verification Plan

### Manual Verification
1. Navigate to a patient's profile.
2. Under Treatment Plan tab, click Delete on a plan.
3. Confirm modal appears, click 'Yes', observe Toastr success message and table refresh without reload.
4. Verify DB table `turn_tooth_recommended` has those rows deleted.
5. Click Edit on an existing plan.
6. Verify modal opens and previously selected teeth, processes, and 'other' texts are pre-filled.
7. Modify selections, submit, and observe Toastr success message and table refresh without reload.
8. Verify DB reflects the updated processes.
