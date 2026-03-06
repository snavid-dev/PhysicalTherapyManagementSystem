═══════════════════════════════════════════
 CANIN CHANGE REPORT
 Task: Audit & Fix Edit Tooth/Teeth Flow
 Branch: codex/fix/edit-tooth-services-and-audit
 Date: 1404/12/15
═══════════════════════════════════════════

📁 FILES CHANGED
───────────────────────────────────────────
File: application/controllers/Admin.php
  Line 3356 — ADDED: permission check for `single_tooth`
  Line 7702 — ADDED: `tooth_id` validation + stable form-error init
  Line 7758 — ADDED: remove disabled department base records on update
  Line 7791 — MODIFIED: use `count(...) > 0` upsert path
  Line 7911 — ADDED: prosthodontic color re-insert on update
  Reason: Fix broken edit persistence and mirror insert behavior.

File: application/models/Admin_model.php
  Line 1761 — MODIFIED: delete basic-info/services by all department ids per tooth
  Line 1833 — ADDED: `delete_endo_by_tooth()`
  Line 1839 — ADDED: `delete_restorative_by_tooth()`
  Line 1845 — ADDED: `delete_prosthodontics_by_tooth()`
  Line 1851 — ADDED: `get_department_ids_by_tooth()` helper
  Reason: Ensure complete cleanup before re-insert in update flow.

File: application/views/patients/components/main/modals/adult/update/endo/endo.php
  Line 108 — MODIFIED: added canal length input ids for prefill
  Line 292 — MODIFIED: fixed obturation select id (`instypeObturation_update`)
  Line 383 — MODIFIED: added details textarea id (`details_update`)
  Line 398 — MODIFIED: unique update image id
  Reason: Fix edit modal prefill/field mapping.

File: application/views/patients/components/main/modals/adult/update/pro/pro.php
  Line 162 — MODIFIED: fixed color `multiple_value` selector targets
  Line 116 — MODIFIED: removed debug border style
  Line 321 — MODIFIED: unique update image id
  Reason: Fix color submission and clean edit UI artifact.

File: application/views/patients/components/main/modals/adult/update/restorative/restorative.php
  Line 211 — MODIFIED: unique restorative update image id
  Reason: Correct edit modal image update target.

File: application/views/patients/components/main/modals/adult/update/teethmodal_update-js.php
  Line 39 — MODIFIED: clear multi-selects safely
  Line 51 — ADDED: sync hidden `imgAddress` fields during prefill
  Line 59 — MODIFIED: normalize diagnose id comparison
  Line 77 — MODIFIED: correct image target ids
  Line 140 — ADDED: sync prosthodontic color hidden input in edit
  Line 198 — MODIFIED: robust multi-select prefill (`val(...).trigger('change')`)
  Reason: Ensure complete AJAX payload + stable prefill behavior.

File: application/views/patients/components/main/modals/adult/update/teethmodal_update.php
  Line 115 — MODIFIED: removed non-existent `total_price_form` from submit list
  Reason: Prevent invalid form aggregation references.

File: application/views/patients/js.php
  Line 148 — MODIFIED: pass `patient_id` to `updateTeeth(...)` in dynamic table
  Reason: Keep edit invocation consistent.

File: docs/fix-edit-tooth-audit.md
  Line 1 — ADDED: full audit/fix documentation.
  Reason: Required deliverable.

───────────────────────────────────────────
🗃️ DATABASE CHANGES
  Table: none

───────────────────────────────────────────
🔑 NEW PERMISSIONS
  none

───────────────────────────────────────────
🌐 NEW LANGUAGE KEYS
  none

───────────────────────────────────────────
⚠️ RISKS & SIDE EFFECTS
  - Deselecting a department during edit now removes that department’s stored records for the tooth (intentional, mirrors insert behavior).

───────────────────────────────────────────
✅ GIT COMMANDS USED
  git checkout main
  git pull origin main
  git checkout -b codex/fix/edit-tooth-services-and-audit
  git add <specific files>
  git commit -m "fix(teeth): audit and repair edit-tooth update flow"
  git push origin codex/fix/edit-tooth-services-and-audit
═══════════════════════════════════════════
