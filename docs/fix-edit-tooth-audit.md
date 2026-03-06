# Fix Report: Edit Tooth Audit

Date: 1404/12/15
Branch: fix/edit-tooth-services-and-audit (implemented as `codex/fix/edit-tooth-services-and-audit`)

## Problems Found

| # | Problem | File | Line | Severity |
|---|---|---|---|---|
| 1 | `single_tooth` endpoint had no permission check | application/controllers/Admin.php | 3354 | High |
| 2 | `update_tooth` had no strict `tooth_id` validation | application/controllers/Admin.php | 7708 | High |
| 3 | Update flow deleted join rows but did not remove disabled department records, leaving stale department data | application/controllers/Admin.php | 7754 | High |
| 4 | Department upsert logic used `count(...) == 1`; duplicates could trigger unintended re-insert path | application/controllers/Admin.php | 7791, 7834, 7879 | High |
| 5 | Service re-insert loops could insert empty `services_id` values | application/controllers/Admin.php | 7801, 7844, 7891 | Medium |
| 6 | Prosthodontic `color` basic-info values were not re-inserted in update flow | application/controllers/Admin.php | 7911 | High |
| 7 | Prosthodontic color prefill in `single_tooth` overwrote previous values and kept only one color | application/controllers/Admin.php | 3516 | Medium |
| 8 | Model delete helpers removed related rows for only one department row (first id), not all tooth-linked rows | application/models/Admin_model.php | 1761-1855 | High |
| 9 | Update modal prefill missed key fields due id mismatches (`typeObturation`, canal lengths, endo details) | update/endo/endo.php + update JS | 108, 292, 383 | Medium |
| 10 | Update modal image selectors targeted non-existent/duplicate ids; hidden `imgAddress` not synchronized on edit | update JS + update views | 51, 77, 398 | Medium |
| 11 | Prosthodontic color hidden field used wrong selectors in edit mode (`#pro_color`, `#pro_colors`) | update/pro/pro.php | 162 | High |
| 12 | Update submit call referenced a non-existent form id (`total_price_form`) | update/teethmodal_update.php | 115 | Low |
| 13 | Dynamic teeth table edit action did not pass `patient_id` consistently | application/views/patients/js.php | 148 | Low |

## Fixes Applied

### Fix 1: Hardened controller validation and permissions for edit flow

- File: `application/controllers/Admin.php`
- Line(s): 3354-3360, 7699-7713
- What changed: Added `check_permission_function('Update Teeth')` to `single_tooth`; added strict `tooth_id` validation rule in `update_tooth` and initialized response structure.
- Why: Enforces edit authorization and prevents invalid update requests.
- Risk: Low.

### Fix 2: Corrected update department lifecycle to mirror insert behavior

- File: `application/controllers/Admin.php`
- Line(s): 7754-7765
- What changed: When a department checkbox is off, related department base row is deleted (`endo`, `restorative`, `prosthodontics`) after relation cleanup.
- Why: Insert flow creates department rows only when selected; update now mirrors this and avoids stale services/fields.
- Risk: Medium (intentional cleanup of deselected department data).

### Fix 3: Stabilized department upsert paths and service inserts

- File: `application/controllers/Admin.php`
- Line(s): 7791-7898
- What changed: Changed `count(...) == 1` checks to `count(...) > 0`; used insert return ids directly; skipped empty service ids in all three departments.
- Why: Prevents duplicate-row reinsert behavior and invalid junction rows.
- Risk: Low.

### Fix 4: Restored prosthodontic color persistence on update

- File: `application/controllers/Admin.php`
- Line(s): 7911-7923
- What changed: Added color re-insert logic (`color` split + insert into `prosthodontics_has_basic_information_teeth`) in update flow.
- Why: Color values were being dropped on every edit.
- Risk: Low.

### Fix 5: Corrected `single_tooth` serialization for service arrays and prosthodontic colors

- File: `application/controllers/Admin.php`
- Line(s): 3387, 3428, 3469, 3484, 3516
- What changed: Filtered empty service entries; changed prosthodontic color handling to accumulate all color ids instead of overwriting.
- Why: Enables correct prefill and prevents data loss in edit modal.
- Risk: Low.

### Fix 6: Made model cleanup methods delete by all department ids per tooth

- File: `application/models/Admin_model.php`
- Line(s): 1761-1855
- What changed: Reworked delete helpers to use all related department ids (`where_in`) and added explicit department delete-by-tooth methods.
- Why: Ensures old service/basic-info rows are fully cleared before re-insert, including duplicate-row edge cases.
- Risk: Medium.

### Fix 7: Fixed edit modal field/id mismatches and payload synchronization

- File: `application/views/patients/components/main/modals/adult/update/endo/endo.php`
- Line(s): 108, 148, 188, 228, 269, 292, 383, 398
- What changed: Added missing ids for canal lengths/details; corrected `typeObturation` id to update variant; updated endo image id.
- Why: Prefill/update JS was targeting non-existent ids.
- Risk: Low.

### Fix 8: Fixed prosthodontic update selectors and removed debug style artifact

- File: `application/views/patients/components/main/modals/adult/update/pro/pro.php`
- Line(s): 116, 162, 321
- What changed: Corrected color `multiple_value` selectors to update ids; removed yellow debug border; updated pro image id.
- Why: Color was not being submitted reliably; cleaned edit UI artifact.
- Risk: Low.

### Fix 9: Corrected restorative update image id

- File: `application/views/patients/components/main/modals/adult/update/restorative/restorative.php`
- Line(s): 211
- What changed: Updated restorative modal image id to update-specific selector.
- Why: Needed for consistent edit image prefill.
- Risk: Low.

### Fix 10: Removed invalid form id from update submit aggregation

- File: `application/views/patients/components/main/modals/adult/update/teethmodal_update.php`
- Line(s): 115
- What changed: Removed `total_price_form` from `submitWithoutDatatableMulti` source list.
- Why: Prevents payload collection from referencing non-existent form ids.
- Risk: Low.

### Fix 11: Reworked edit modal JS prefill and multi-select behavior

- File: `application/views/patients/components/main/modals/adult/update/teethmodal_update-js.php`
- Line(s): 39-202
- What changed: Cleared multi-selects safely; normalized diagnose matching; synchronized hidden `imgAddress`; fixed image targets; corrected color hidden sync; simplified robust multi-select prefill.
- Why: Ensures service and department values are reliably prefilled and posted.
- Risk: Medium (JS behavior touched in central edit flow).

### Fix 12: Aligned dynamic list edit button call signature

- File: `application/views/patients/js.php`
- Line(s): 148
- What changed: Passed `patient_id` to `updateTeeth(...)` in dynamic teeth table rendering.
- Why: Keeps edit invocation consistent with static table usage.
- Risk: Low.

## Language Keys Added

| Key | fa | en | pa |
|---|---|---|---|
| none | - | - | - |

## DB Changes

none

## Files Changed

| File | Type of change |
|---|---|
| application/controllers/Admin.php | Modified |
| application/models/Admin_model.php | Modified |
| application/views/patients/components/main/modals/adult/update/endo/endo.php | Modified |
| application/views/patients/components/main/modals/adult/update/pro/pro.php | Modified |
| application/views/patients/components/main/modals/adult/update/restorative/restorative.php | Modified |
| application/views/patients/components/main/modals/adult/update/teethmodal_update.php | Modified |
| application/views/patients/components/main/modals/adult/update/teethmodal_update-js.php | Modified |
| application/views/patients/js.php | Modified |
