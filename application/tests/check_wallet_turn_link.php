<?php
/**
 * Guards against the wallet/safe ledger integrity bugs found while
 * investigating "why doesn't Safe count refunds": turn-deletion orphaning
 * patient_wallet_transactions.turn_id (and the phantom safe income that let
 * through), the backdated-payment mislink in Patients::record_payment(), and
 * safe/index.php's $source_labels map missing sources the app actually writes
 * (the reason refunds looked uncounted — they were, just invisible in the UI).
 * Run: php application/tests/check_wallet_turn_link.php
 */

define('BASEPATH', '');
define('ENVIRONMENT', 'production');
require dirname(__DIR__) . '/config/database.php';
$conn = $db['default'];

$mysqli = new mysqli($conn['hostname'], $conn['username'], $conn['password'], $conn['database']);

if ($mysqli->connect_errno) {
	fwrite(STDERR, "Cannot connect: {$mysqli->connect_error}\n");
	exit(1);
}

function check($label, $passed)
{
	echo ($passed ? "PASS" : "FAIL") . " - $label\n";
	return $passed;
}

$ok = TRUE;

$fk = $mysqli->query("
	SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
	WHERE CONSTRAINT_SCHEMA = DATABASE()
	AND TABLE_NAME = 'patient_wallet_transactions'
	AND CONSTRAINT_NAME = 'patient_wallet_transactions_turn_fk'
")->fetch_assoc();
$ok = check('turn_id has no ON DELETE SET NULL foreign key', $fk === NULL) && $ok;

$orphans = $mysqli->query("
	SELECT COUNT(*) AS n FROM patient_wallet_transactions
	WHERE turn_id IS NULL AND note REGEXP 'turn #[0-9]+'
")->fetch_assoc();
$ok = check('no wallet rows reference a turn # in note but have turn_id NULL', (int) $orphans['n'] === 0) && $ok;

// The orphaning above let Safe_model's legacy-manual-topup heuristic mistake a
// turn-linked top-up for a standalone one and re-log it into safe_transactions
// under reference_table='patient_wallet_transactions' — money already recorded
// once under reference_table='turns', so this is phantom duplicate income.
$phantom = $mysqli->query("
	SELECT COUNT(*) AS n
	FROM safe_transactions st
	JOIN patient_wallet_transactions wt ON wt.id = st.reference_id
	WHERE st.source = 'wallet_topup'
	AND st.reference_table = 'patient_wallet_transactions'
	AND wt.type = 'topup'
	AND wt.note REGEXP 'turn #[0-9]+'
")->fetch_assoc();
$ok = check('no phantom duplicate safe income for turn-linked top-ups', (int) $phantom['n'] === 0) && $ok;

// Second, related bug (Patients::record_payment): the overflow top-up's safe
// entry used to look up "the patient's latest wallet transaction" instead of
// the row it just inserted, so a backdated payment could tag the wrong (later,
// unrelated) transaction. A wallet_topup safe row must reference a topup-type
// wallet row, never a deduction/reversal.
$mislinked = $mysqli->query("
	SELECT st.id
	FROM safe_transactions st
	JOIN patient_wallet_transactions wt ON wt.id = st.reference_id
	WHERE st.source = 'wallet_topup'
	AND st.reference_table = 'patient_wallet_transactions'
	AND wt.type != 'topup'
")->fetch_all(MYSQLI_ASSOC);
$ok = check('every wallet_topup safe row references an actual topup', empty($mislinked)) && $ok;

if (!empty($mislinked)) {
	echo "  (safe_transactions ids referencing a non-topup row: " . implode(', ', array_column($mislinked, 'id')) . ")\n";
}

// safe/index.php's $source_labels map must cover every source value the app
// actually writes — a missing entry silently drops that source from the filter
// dropdown and mislabels it in the ledger table (the visible symptom of the
// bug this whole file guards against: refunds "not counted" because staff
// couldn't find or recognize them, even though the aggregate total was already
// correct).
$view_source = file_get_contents(dirname(__DIR__) . '/views/safe/index.php');
preg_match("/\\\$source_labels = array\\((.*?)\\);/s", $view_source, $m);
preg_match_all("/'([a-z_]+)' => t\\(/", $m[1] ?? '', $lm);
$labeled_sources = $lm[1] ?? array();

$used_sources = array_column($mysqli->query("SELECT DISTINCT source FROM safe_transactions")->fetch_all(MYSQLI_ASSOC), 'source');
$unlabeled = array_diff($used_sources, $labeled_sources);
$ok = check('every source used in safe_transactions has a label in the safe view', empty($unlabeled)) && $ok;

if (!empty($unlabeled)) {
	echo "  (unlabeled sources: " . implode(', ', $unlabeled) . ")\n";
}

// NOTE: expense_date/payment_date is a business/accounting date staff can
// backdate freely; safe_transactions.created_at must track when cash actually
// left the drawer, because balance math has to respect safe_adjustments resets
// (a physical cash-count override). An earlier version of this fix conflated
// the two and pointed created_at at expense_date, which silently let backdated
// entries get swallowed by an adjustment that happened before their new date —
// undercounting the real balance. Reverted; do not reintroduce that.
// Correct invariant: an expense/salary safe row's created_at must match its
// reference row's own created_at (the real insert moment), not its business date.
$mistimed = $mysqli->query("
	SELECT st.id FROM safe_transactions st
	JOIN expenses e ON e.id = st.reference_id
	WHERE st.source = 'expense' AND st.reference_table = 'expenses'
	AND st.created_at != e.created_at
	UNION ALL
	SELECT st.id FROM safe_transactions st
	JOIN staff_salary_payments sp ON sp.id = st.reference_id
	WHERE st.source = 'salary_payment' AND st.reference_table = 'staff_salary_payments'
	AND st.created_at != sp.created_at
")->fetch_all(MYSQLI_ASSOC);
$ok = check('every expense/salary safe row is timestamped to its real entry time, not a business date', empty($mistimed)) && $ok;

if (!empty($mistimed)) {
	echo "  (safe_transactions ids mistimed: " . implode(', ', array_column($mistimed, 'id')) . ")\n";
}

exit($ok ? 0 : 1);
