# STORE_TASKS.md — CANIN Physical Therapy Clinic — Store / Inventory Module

This file is the master task list for adding a **Store (Inventory) module** to the CANIN
Physical Therapy Clinic system. Read it together with `CANIN.md` (the project map) and
`TASKS.md` (the working protocol). This file extends both; it does not replace them.

The Store lets the clinic sell physiotherapy products (balls, belts, bands, needles,
tape, etc.), hold stock in two locations ("treasuries"), move stock from the main
warehouse to the front desk with manager approval, and report on stock, sales, and profit.

---

## 0. Execution Protocol (Read This First)

Follow `TASKS.md` section 0 exactly. The store-specific reminders:

1. Read `CANIN.md` fully before doing anything. Ignore legacy dental files.
2. **Autonomous mode — run to completion.** Work through the milestones **in order,
   SM1 → SM6, without pausing for human confirmation between them.** After finishing a
   milestone, self-verify it (below) and immediately begin the next. Do **not** stop for
   routine check-ins. Stop and report **only** when a real blocker occurs:
   - a milestone's Acceptance Criteria still fail after a genuine fix attempt, or
   - a `[verify]` schema name is ambiguous or conflicts with the real schema in a way that
     would change behavior, or
   - completing a task would require expanding scope beyond this spec, or
   - `php -l` fails and the cause isn't obvious.
   On a blocker: stop, explain it, and wait. Otherwise keep going until SM6 is done, then
   write one final summary.
3. For each milestone: implement all tasks; add every UI string to **both**
   `application/language/english/app_lang.php` and `application/language/farsi/app_lang.php`
   using `t('...')`; update `database/physical_therapy_clinic.sql`; run `php -l` on every
   changed file; verify every Acceptance Criterion in the running system; verify desktop +
   mobile + RTL (Persian); verify permission guards; append a short completion note to
   `STORE_BUILD_LOG.md` (create it if missing; use the report template in `TASKS.md`);
   commit the milestone (point 4); then **continue to the next milestone**.
4. **Git — autonomous, commit per milestone.** At the very start of the run, create one
   branch `store/full-build` with `git switch -c store/full-build`. After each milestone
   passes its Acceptance Criteria, commit **only that milestone's files** with a message
   like `SM1: catalog, treasuries, stock ledger`. This gives a reviewable, revertable
   history — the human reviews the branch at the end and merges. Never `push`, `merge`,
   `rebase`, `reset`, `restore`, or force-anything; commits stay local on this branch.
   *(To keep the original "human owns all commits" rule instead: skip every commit, leave
   the whole tree dirty, and the human commits after reviewing — you then get one large
   diff covering all milestones.)*
5. **All money movements touch `safe_transactions`.** No silent cash movement.
6. **All wallet-affecting writes call `Wallet_model::recalculate_for_patient()`** (built in
   `TASKS.md` M1). Reuse it — do not reimplement wallet math.
7. After each milestone, run `graphify update .` to keep the knowledge graph current.
8. **`[verify]` markers:** column and enum names of *existing* tables (`patients`,
   `payments`, `patient_wallet_transactions`, `patient_debts`, `safe_transactions`,
   `expenses`, `expense_categories`, `sections`, `staff`, `users`, `permissions`) were
   inferred from `CANIN.md`/`TASKS.md`. Before writing any query against them, confirm the
   real names with `graphify explain "<table>"` or the schema dump and fix mismatches. Do
   not invent columns silently — if a name is wrong, stop and correct it.
9. If anything here conflicts with `CANIN.md`, treat it as a blocker (point 2): stop and
   report the conflict rather than silently resolving it.

---

## 1. Decisions Locked For v1

| # | Decision | Choice |
|---|---|---|
| 1 | Product payment methods | cash, card, **wallet**, **prepayment** all allowed |
| 2 | Costing (for valuation & profit) | **last purchase cost** (variant `cost_price` overwritten on each receipt) |
| 3 | Consumables (needles/tape used *on* patients) | **retail sales only in v1**; clinical consumption deferred to SM7 |
| 4 | Selling location | **front desk only**; warehouse = stock + fulfillment |
| 5 | Restock accounting | **reuse Expenses** — a new "Inventory Purchase" expense category, which already writes a Safe outflow |

**Naming conventions (locked):** all new tables prefixed `store_` or `stock_`; permissions
`view_store`, `manage_store`, `approve_store_requisition`; new `safe_transactions` sources
`store_sale` and `store_refund`.

**Money-vs-stock rule (locked):**
- Cash/card **sale** → Safe **inflow**, source `store_sale`.
- Wallet/prepayment **sale** → `patient_wallet_transactions` debit + `recalculate_for_patient()`, **no** Safe row (no cash moved).
- Cash **refund** → Safe **outflow**, source `store_refund`. Wallet refund → wallet debit reversal + recalc, no Safe row.
- **Restock** → one `expenses` row (Inventory Purchase category) → Safe outflow via existing expense logic.
- **Transfer** between treasuries and **adjustments/counts** → stock only, **never** touch the Safe.
- COGS is captured **per sale line** at sale time (`unit_cost_at_sale`) so profit reports stay correct even after `cost_price` later changes.

---

## 2. New Permissions (do this in SM1, keep consistent thereafter)

Seed into the `permissions` table `[verify table/column names]`:
`view_store`, `manage_store`, `approve_store_requisition`. Add them to the Roles UI
(`application/views/roles/`), gate every store route in `Store.php`, and make the store nav
item in `application/views/layout/header.php` permission-aware (visible only with
`view_store`). Follow the existing `view_x` / `manage_x` pattern.

---

## Store Milestone 1 (SM1) — Foundation: Catalog, Treasuries, Stock Ledger

### Goal
Stand up the product catalog, the two stock locations, and a movement-based stock ledger
that is the single source of truth. No sales or transfers yet — just master data, opening
stock, and accurate stock viewing.

### Tasks

**1.1 — Schema.** Create the catalog, location, and ledger tables (Appendix A, tables
1–6) and seed the two locations. Update `database/physical_therapy_clinic.sql`.

**1.2 — Product categories mini-CRUD.** Manage `store_product_categories`
(name, active). Mirror the expense-category mini-CRUD pattern used in Preferences
(`Expense_category_model`) so it feels familiar. `manage_store` required.

**1.3 — Products + variants CRUD.** In `Store.php` / `Store_model.php`. A product has a
name, category, brand (optional), unit, active flag, optional image, and **one or more
variants**. Each variant has a `variant_label` (this is where "needle 0.25 × 40mm" lives),
optional SKU/barcode, `cost_price`, `sell_price`, and `reorder_level`. Every product must
have at least one variant (single-variant products get a default). `manage_store` required.

**1.4 — Opening stock (manager).** A "set opening stock" action that writes a
`stock_movements` row of type `adjustment` into a chosen location and refreshes
`stock_levels`. This seeds the system so SM2–SM3 can be tested. `manage_store` required.
(Full damage/loss/count adjustment UI is SM5 — SM1 only needs opening balances.)

**1.5 — Stock view.** A stock screen per location. The secretary (front desk) view must
show, per variant: their own on-hand quantity **and** the warehouse's available quantity
(read-only) — this is what tells them what to request in SM2. Use `.dt-table` DataTables if
the list is large. `view_store` required.

**1.6 — Ledger + cache.** `Inventory_model` owns `stock_movements` (append-only ledger)
and `stock_levels` (maintained cache). Provide `recompute_stock_level($variant_id,
$location_id)` that sums the ledger and rewrites the cache. Every stock write in every
milestone goes through `Inventory_model` so the ledger is never bypassed. Stock must never
go negative.

**1.7 — Permissions, nav, language.** Section 2 permissions; permission-aware nav entry;
all strings in both language files.

### Schema Changes
Appendix A tables 1–6; permission rows from Section 2. Update the SQL dump.

### Files Likely To Change
- `application/controllers/Store.php` (new)
- `application/models/Store_model.php` (new) — products, variants, categories
- `application/models/Inventory_model.php` (new) — locations, stock_levels, stock_movements
- `application/views/store/` (new) — `index.php` (products), product form, categories, `stock.php`
- `application/config/routes.php`
- `application/controllers/Roles.php` + `application/views/roles/` (register permissions)
- `application/views/layout/header.php` (nav)
- `application/language/english/app_lang.php`, `application/language/farsi/app_lang.php`
- `database/physical_therapy_clinic.sql`

### Acceptance Criteria
1. Create a category, then a product ("Acupuncture Needle") with two variants
   (`0.25 × 40mm`, `0.30 × 50mm`), each with cost, sell, and reorder values.
2. Set opening stock of 100 for one variant in the warehouse; the stock view shows 100 and
   a matching `stock_movements` row exists.
3. `stock_levels` for that variant/location equals the ledger sum (`recompute` is
   idempotent — running it twice gives the same number).
4. The front-desk stock view shows the warehouse's available quantity read-only alongside
   the front desk's own quantity.
5. Stock can never be driven negative from any SM1 action.
6. Routes are gated by `view_store` / `manage_store`; the nav item is hidden without
   `view_store`.
7. All new strings exist in both language files; Persian renders RTL; mobile layout works.

### Self-verify, log, commit `SM1: catalog, treasuries, stock ledger`, then proceed directly to SM2. Stop only on a blocker (§0.2).

---

## Store Milestone 2 (SM2) — Internal Requisition & Manager Approval

### Goal
Let the front desk request stock from the warehouse, have a manager approve/adjust/reject,
fulfill the transfer, and have the front desk confirm receipt — with discrepancy handling
and a full audit trail. This is the workflow the clinic asked for.

### Tasks

**2.1 — Schema.** `stock_requisitions` + `stock_requisition_items` (Appendix A, tables
7–8).

**2.2 — Create requisition (secretary).** From the front-desk stock view, select variants
and quantities to request from the warehouse. Show live warehouse availability while
choosing. Use the bulk-row pattern from `application/views/turns/bulk_form.php` (dynamic
rows, "+" to add, remove button, Select2 re-init on each new row). Status starts `pending`.
`manage_store` required.

**2.3 — Approve / adjust / reject (manager).** A pending-requisitions screen. The manager
can edit `qty_approved` per line, approve partially, approve as-is, or reject the whole
requisition with a reason. `approve_store_requisition` required.

**2.4 — Fulfill.** On approval, moving the approved quantities writes `transfer_out`
movements from the warehouse (via `Inventory_model`) and sets status `in_transit`. Block if
warehouse available < approved.

**2.5 — Receive (secretary).** The secretary confirms received quantities. Writes
`transfer_in` movements into the front desk. If `qty_received` ≠ `qty_approved`, record the
discrepancy on the line, flag it for the manager, and still complete with the real received
amount — never silently overwrite. Status → `received`.

**2.6 — Visibility.** Requisition list shows status to the secretary; a pending-approval
count badge appears on the manager's nav. (Simple count badge, not a full notification
system.)

### Schema Changes
Appendix A tables 7–8. Update the SQL dump.

### Files Likely To Change
- `application/controllers/Store.php`, `application/models/Store_model.php`,
  `application/models/Inventory_model.php`
- `application/views/store/` — `requisitions.php`, requisition form, approval view
- `application/views/layout/header.php` (pending badge)
- both language files, `database/physical_therapy_clinic.sql`

### Acceptance Criteria
1. Secretary submits a requisition for 20 of a variant the warehouse has; status `pending`.
2. Manager reduces it to 15 and approves; warehouse stock drops by 15 (`transfer_out`);
   status `in_transit`; front-desk stock unchanged yet.
3. Secretary confirms receipt of 14 (one damaged in transit); front desk rises by 14
   (`transfer_in`); the 15→14 discrepancy is recorded and flagged; status `received`.
4. Rejecting a requisition with a reason moves no stock and shows the reason to the secretary.
5. Approving more than the warehouse holds is blocked.
6. Ledger sums still equal `stock_levels` at both locations after the full cycle.
7. Every state change is timestamped with the acting user (audit trail).
8. Strings in both languages; RTL; mobile; pending badge respects `approve_store_requisition`.

### Self-verify, log, commit `SM2: requisition and approval`, then proceed directly to SM3. Stop only on a blocker (§0.2).

---

## Store Milestone 3 (SM3) — Sales / POS (Safe + Wallet + Prepayment)

### Goal
Sell products from the front desk to a patient or a walk-in, deduct stock, take payment via
cash/card/wallet/prepayment, route money correctly, and print a receipt.

### Tasks

**3.1 — Schema.** `store_sales` + `store_sale_items` (Appendix A, tables 9–10). Add
`store_sale` to the `safe_transactions` source enum/lookup `[verify]`.

**3.2 — Sale screen (cart).** Add variants to a cart (front desk stock only), quantities,
per-line and per-sale discount, optional tax. Optionally attach a patient (Select2 patient
search, same as Turns). Block any line exceeding front-desk available stock.

**3.3 — Payment routing.**
- **Cash / card:** write a `payments` row `[verify: patient-linked, no turn_id]` and a
  `safe_transactions` inflow, source `store_sale`.
- **Wallet / prepayment:** patient required. Debit via a `patient_wallet_transactions` row
  `[verify enum: add a store_purchase type or reuse an existing debit type]`, then call
  `Wallet_model::recalculate_for_patient()`. **No** Safe row.
- Reuse existing wallet/prepayment/debt logic from `TASKS.md` M1 — do not duplicate it.

**3.4 — Stock + COGS.** On completion, write `sale_out` movements from the front desk and
store `unit_cost_at_sale` on each line (copied from the variant's current `cost_price`) so
profit reporting is stable.

**3.5 — Receipt.** Printable receipt (Shamsi date via `to_shamsi()`), clinic header, line
items, totals, payment method. Reuse the print styling used elsewhere.

**3.6 — Patient history.** If a patient was attached, the sale appears in their profile
(`application/views/patients/show.php`) alongside turns and wallet activity.

### Schema Changes
Appendix A tables 9–10; add `store_sale` to `safe_transactions` source `[verify]`. Update SQL dump.

### Files Likely To Change
- `application/controllers/Store.php`, `application/models/Store_model.php`,
  `application/models/Inventory_model.php`
- `application/models/Safe_model.php`, `application/models/Wallet_model.php` (reuse; wire calls)
- `application/views/store/` — `sell.php`, receipt view
- `application/views/patients/show.php` (purchase history block)
- both language files, `database/physical_therapy_clinic.sql`

### Acceptance Criteria
1. Cash sale of 2 items deducts front-desk stock, writes a Safe inflow `store_sale`, and
   prints a correct Shamsi receipt.
2. Wallet sale to a patient with enough balance debits the wallet, writes **no** Safe row,
   and leaves the wallet correct after `recalculate_for_patient()` (idempotent).
3. Prepayment/wallet sale to a patient with insufficient balance is blocked or offered as
   debt per existing wallet rules `[verify current behavior]` — no negative stock, no orphan payment.
4. A sale line exceeding available front-desk stock is blocked.
5. `unit_cost_at_sale` is stored per line and does not change if the variant's `cost_price`
   is later updated.
6. An attached patient sees the purchase in their profile.
7. Ledger sums equal `stock_levels`; Safe `balance_after` stays correct.
8. Strings in both languages; RTL; mobile; `manage_store` gates selling.

### Self-verify, log, commit `SM3: sales / POS`, then proceed directly to SM4. Stop only on a blocker (§0.2).

---

## Store Milestone 4 (SM4) — Restock From Suppliers (via Expenses)

### Goal
Receive purchased stock into the warehouse, update last cost, and record the spend as an
expense so it flows into the Safe and expense reports — no separate money path.

### Tasks

**4.1 — Schema.** `store_suppliers`, `store_stock_receipts`, `store_stock_receipt_items`
(Appendix A, tables 11–13). Seed one **"Inventory Purchase"** row in `expense_categories`
`[verify table]`.

**4.2 — Suppliers mini-CRUD.** Simple name/contact/active list. `manage_store`.

**4.3 — Receive stock.** A form: optional supplier, then rows of variant + qty + unit cost
(bulk-row + Select2 pattern). On submit, inside one DB transaction:
- write `purchase_in` movements into the warehouse (via `Inventory_model`);
- update each variant's `cost_price` to the received unit cost (last-cost rule);
- create **one** `expenses` row (Inventory Purchase category) for the receipt total, which
  writes its Safe outflow through existing `Expense_model` logic;
- link the created `expense_id` back onto the receipt `[verify expenses.id]`.

`manage_store` required.

### Schema Changes
Appendix A tables 11–13; one `expense_categories` seed row. Update SQL dump.

### Files Likely To Change
- `application/controllers/Store.php`, `application/models/Store_model.php`,
  `application/models/Inventory_model.php`
- `application/models/Expense_model.php`, `application/models/Expense_category_model.php` (reuse)
- `application/views/store/` — `receive.php`, suppliers view
- both language files, `database/physical_therapy_clinic.sql`

### Acceptance Criteria
1. Receiving 50 of a variant at a new unit cost raises warehouse stock by 50 and updates the
   variant `cost_price` to that unit cost.
2. Exactly one expense row (Inventory Purchase) is created for the receipt total and the
   Safe shows a matching outflow with correct `balance_after`.
3. If any row is invalid, the whole receipt rolls back — no partial stock, no orphan expense.
4. The receipt stores the linked `expense_id`.
5. Ledger sums equal `stock_levels`.
6. Strings in both languages; RTL; mobile; `manage_store` gates receiving.

### Self-verify, log, commit `SM4: restock from suppliers`, then proceed directly to SM5. Stop only on a blocker (§0.2).

---

## Store Milestone 5 (SM5) — Returns, Adjustments, Stock Count

### Goal
Handle product returns (with refunds), stock corrections, and physical counts.

### Tasks

**5.1 — Schema.** `store_returns`, `store_return_items`, `store_adjustments`,
`store_stock_counts`, `store_stock_count_items` (Appendix A, tables 14–17b). Add
`store_refund` to `safe_transactions` source `[verify]`.

**5.2 — Returns.** From a completed sale, return selected lines/quantities. Write
`return_in` movements to the front desk. Refund by **cash** (Safe outflow `store_refund`) or
**wallet** (wallet credit reversal + `recalculate_for_patient()`, no Safe row). Update sale
status to `refunded` / `partially_refunded`. `manage_store`.

**5.3 — Adjustments.** Manager records a signed `qty_delta` with a reason
(`damage/loss/theft/expiry/found/correction`); writes an `adjustment` movement. No Safe row.
`manage_store` (or a dedicated approver).

**5.4 — Stock count.** Open a count for a location, enter counted quantities, see variance
vs system, and reconcile — reconciliation writes `adjustment` movements for each variance.
`manage_store`.

### Schema Changes
Appendix A tables 14–17b; add `store_refund` to `safe_transactions` source. Update SQL dump.

### Files Likely To Change
- `application/controllers/Store.php`, `application/models/Store_model.php`,
  `application/models/Inventory_model.php`, `application/models/Safe_model.php`,
  `application/models/Wallet_model.php`
- `application/views/store/` — `returns.php`, `adjustments.php`, `count.php`
- both language files, `database/physical_therapy_clinic.sql`

### Acceptance Criteria
1. Cash refund of one returned item raises front-desk stock and writes a Safe outflow
   `store_refund`; the sale becomes `partially_refunded`.
2. Wallet refund credits the wallet, writes no Safe row, and leaves the wallet correct.
3. An adjustment of −3 (damage) lowers stock by 3 with the reason recorded; the Safe is
   untouched.
4. A stock count showing system 100 / counted 96 reconciles to 96 via a −4 adjustment
   movement.
5. Ledger sums equal `stock_levels`; Safe `balance_after` correct throughout.
6. Strings in both languages; RTL; mobile.

### Self-verify, log, commit `SM5: returns, adjustments, stock count`, then proceed directly to SM6. Stop only on a blocker (§0.2).

---

## Store Milestone 6 (SM6) — Store Reports & Dashboard

### Goal
Read-only reporting on stock, sales, and profit, consistent with the existing Reports module.

### Tasks

**6.1 — Reports.** Add under `/reports` (or a store reports page) — keep it read-only,
date-range driven (Shamsi in, Gregorian query), responsive, printable + PDF like the other
reports:
- **Inventory valuation** — per location, at last cost and at retail; grand total.
- **Stock on hand / low stock** — variants at or below `reorder_level`, per location.
- **Stock movement ledger** — filterable by variant/location/type with running balance.
- **Sales** — by period, product, category, patient, and seller.
- **Profit** — revenue − COGS using `unit_cost_at_sale`, per product and overall.
- **Purchases** — by supplier/period (from receipts/expenses).
- **Shrinkage** — losses from adjustments.
- **Pending requisitions** — awaiting approval / in transit.

**6.2 — Dashboard tiles.** On the existing dashboard (respecting `view_store`): total
inventory value, today's & this month's store sales and profit, low-stock count, pending
requisitions.

**6.3 — Rollup.** Ensure store sales appear as an income stream alongside services in the
existing Reports/Safe totals without double-counting (store sales already hit the Safe in
SM3).

### Schema Changes
None (read-only), unless an index is needed for report performance.

### Files Likely To Change
- `application/controllers/Reports.php`, `application/models/Report_model.php`,
  `application/views/reports/`
- `application/controllers/Dashboard.php`, `application/models/Dashboard_model.php`,
  `application/views/dashboard/index.php`
- `application/models/Store_model.php`, `application/models/Inventory_model.php`
- both language files

### Acceptance Criteria
1. Inventory valuation equals Σ(on-hand × cost) per location and matches a hand check on a
   small dataset.
2. Profit for a period equals Σ(line revenue − line `unit_cost_at_sale`) and is unaffected
   by later `cost_price` changes.
3. Low-stock report lists exactly the variants at/below reorder level.
4. Store sales appear in the clinic income totals without double-counting.
5. Print and PDF outputs are clean on A4; dashboard tiles respect `view_store`.
6. Strings in both languages; RTL; mobile.

### Self-verify, log, commit `SM6: reports and dashboard`. This is the last milestone in scope — write the final summary and STOP. Do NOT start SM7 (deferred).

---

## Store Milestone 7 (SM7) — Deferred / Future

Out of scope for v1; capture here so it isn't lost:
- **Clinical consumables** — needles/tape consumed during a session write `consumption_out`
  from front-desk stock (a cost of service, not a sale), tied to Turns. Requires the
  `is_consumable` flag (reserved in Appendix A) and a consumption report.
- **Batch/lot + expiry tracking** for consumables.
- **PT product recommendations** by section/treatment on the patient profile.
- **Staff commission** on product sales (ties into Salaries).

---

## Appendix A — Proposed Schema (DDL sketch)

InnoDB / utf8mb4, money as `DECIMAL(12,2)` (AFN), IDs `INT UNSIGNED AUTO_INCREMENT`, dates
stored **Gregorian**. All FKs to existing tables are marked `[verify]` — confirm the real
column names before creating constraints.

```sql
-- 1
CREATE TABLE store_product_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL
);

-- 2
CREATE TABLE store_products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,          -- FK -> store_product_categories.id
  name VARCHAR(191) NOT NULL,
  brand VARCHAR(191) NULL,
  unit VARCHAR(50) NOT NULL DEFAULT 'piece',
  is_consumable TINYINT(1) NOT NULL DEFAULT 0, -- reserved for SM7
  image VARCHAR(255) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL
);

-- 3
CREATE TABLE store_product_variants (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id INT UNSIGNED NOT NULL,           -- FK -> store_products.id
  variant_label VARCHAR(191) NOT NULL,        -- e.g. "0.25 x 40mm", "Size L", "65cm"
  attributes JSON NULL,
  sku VARCHAR(100) NULL,
  barcode VARCHAR(100) NULL,
  cost_price DECIMAL(12,2) NOT NULL DEFAULT 0, -- last purchase cost
  sell_price DECIMAL(12,2) NOT NULL DEFAULT 0,
  reorder_level INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL
);

-- 4
CREATE TABLE store_locations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  type ENUM('front_desk','warehouse') NOT NULL,
  is_default_sales_location TINYINT(1) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1
);
-- seed: (1,'Front Desk','front_desk',1,1), (2,'Warehouse','warehouse',0,1)

-- 5
CREATE TABLE stock_levels (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  variant_id INT UNSIGNED NOT NULL,           -- FK -> store_product_variants.id
  location_id INT UNSIGNED NOT NULL,          -- FK -> store_locations.id
  qty_on_hand INT NOT NULL DEFAULT 0,
  qty_reserved INT NOT NULL DEFAULT 0,
  updated_at DATETIME NOT NULL,
  UNIQUE KEY uq_variant_location (variant_id, location_id)
);

-- 6  (append-only ledger; source of truth)
CREATE TABLE stock_movements (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  variant_id INT UNSIGNED NOT NULL,           -- FK -> store_product_variants.id
  location_id INT UNSIGNED NOT NULL,          -- FK -> store_locations.id
  type ENUM('purchase_in','transfer_out','transfer_in','sale_out','return_in','adjustment','consumption_out') NOT NULL,
  qty INT NOT NULL,                           -- signed: + in, - out
  unit_cost DECIMAL(12,2) NULL,
  reference_type VARCHAR(40) NULL,            -- 'requisition','sale','receipt','adjustment','return','count','opening'
  reference_id INT UNSIGNED NULL,
  note VARCHAR(255) NULL,
  created_by INT UNSIGNED NULL,               -- FK -> users.id [verify]
  created_at DATETIME NOT NULL
);

-- 7
CREATE TABLE stock_requisitions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  from_location_id INT UNSIGNED NOT NULL,     -- warehouse
  to_location_id INT UNSIGNED NOT NULL,       -- front desk
  requested_by INT UNSIGNED NOT NULL,         -- FK -> users.id [verify]
  status ENUM('pending','approved','rejected','in_transit','received','cancelled') NOT NULL DEFAULT 'pending',
  approved_by INT UNSIGNED NULL,              -- FK -> users.id [verify]
  reject_reason VARCHAR(255) NULL,
  note VARCHAR(255) NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL
);

-- 8
CREATE TABLE stock_requisition_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  requisition_id INT UNSIGNED NOT NULL,       -- FK -> stock_requisitions.id
  variant_id INT UNSIGNED NOT NULL,           -- FK -> store_product_variants.id
  qty_requested INT NOT NULL,
  qty_approved INT NULL,
  qty_received INT NULL
);

-- 9
CREATE TABLE store_sales (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id INT UNSIGNED NULL,               -- FK -> patients.id [verify]
  location_id INT UNSIGNED NOT NULL,          -- front desk
  sold_by INT UNSIGNED NOT NULL,              -- FK -> users.id [verify]
  subtotal DECIMAL(12,2) NOT NULL,
  discount DECIMAL(12,2) NOT NULL DEFAULT 0,
  tax DECIMAL(12,2) NOT NULL DEFAULT 0,
  total DECIMAL(12,2) NOT NULL,
  payment_method ENUM('cash','card','wallet','prepayment') NOT NULL,
  status ENUM('completed','refunded','partially_refunded') NOT NULL DEFAULT 'completed',
  payment_id INT UNSIGNED NULL,               -- FK -> payments.id [verify]
  created_at DATETIME NOT NULL
);

-- 10
CREATE TABLE store_sale_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sale_id INT UNSIGNED NOT NULL,              -- FK -> store_sales.id
  variant_id INT UNSIGNED NOT NULL,           -- FK -> store_product_variants.id
  qty INT NOT NULL,
  unit_price DECIMAL(12,2) NOT NULL,
  discount DECIMAL(12,2) NOT NULL DEFAULT 0,
  line_total DECIMAL(12,2) NOT NULL,
  unit_cost_at_sale DECIMAL(12,2) NOT NULL     -- COGS snapshot
);

-- 11
CREATE TABLE store_suppliers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  contact VARCHAR(191) NULL,
  note VARCHAR(255) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL
);

-- 12
CREATE TABLE store_stock_receipts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  supplier_id INT UNSIGNED NULL,              -- FK -> store_suppliers.id
  received_by INT UNSIGNED NOT NULL,          -- FK -> users.id [verify]
  expense_id INT UNSIGNED NULL,               -- FK -> expenses.id [verify]
  note VARCHAR(255) NULL,
  created_at DATETIME NOT NULL
);

-- 13
CREATE TABLE store_stock_receipt_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  receipt_id INT UNSIGNED NOT NULL,           -- FK -> store_stock_receipts.id
  variant_id INT UNSIGNED NOT NULL,           -- FK -> store_product_variants.id
  qty INT NOT NULL,
  unit_cost DECIMAL(12,2) NOT NULL
);

-- 14
CREATE TABLE store_returns (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sale_id INT UNSIGNED NOT NULL,              -- FK -> store_sales.id
  refund_method ENUM('cash','card','wallet') NOT NULL,
  refunded_by INT UNSIGNED NOT NULL,          -- FK -> users.id [verify]
  total_refund DECIMAL(12,2) NOT NULL,
  note VARCHAR(255) NULL,
  created_at DATETIME NOT NULL
);

-- 15
CREATE TABLE store_return_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  return_id INT UNSIGNED NOT NULL,            -- FK -> store_returns.id
  sale_item_id INT UNSIGNED NOT NULL,         -- FK -> store_sale_items.id
  qty INT NOT NULL
);

-- 16
CREATE TABLE store_adjustments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  location_id INT UNSIGNED NOT NULL,          -- FK -> store_locations.id
  variant_id INT UNSIGNED NOT NULL,           -- FK -> store_product_variants.id
  qty_delta INT NOT NULL,                     -- signed
  reason ENUM('damage','loss','theft','expiry','found','correction') NOT NULL,
  approved_by INT UNSIGNED NULL,              -- FK -> users.id [verify]
  note VARCHAR(255) NULL,
  created_at DATETIME NOT NULL
);

-- 17a
CREATE TABLE store_stock_counts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  location_id INT UNSIGNED NOT NULL,          -- FK -> store_locations.id
  status ENUM('open','reconciled') NOT NULL DEFAULT 'open',
  created_by INT UNSIGNED NOT NULL,           -- FK -> users.id [verify]
  created_at DATETIME NOT NULL
);

-- 17b
CREATE TABLE store_stock_count_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  count_id INT UNSIGNED NOT NULL,             -- FK -> store_stock_counts.id
  variant_id INT UNSIGNED NOT NULL,           -- FK -> store_product_variants.id
  counted_qty INT NOT NULL,
  system_qty INT NOT NULL,
  variance INT NOT NULL
);
```

**Existing tables to extend (verify first):**
- `safe_transactions.source` — add `store_sale` (SM3) and `store_refund` (SM5). `[verify: enum vs lookup table]`
- `patient_wallet_transactions.type` — add a `store_purchase` debit type, or reuse an existing debit type. `[verify]`
- `expense_categories` — seed one "Inventory Purchase" row (SM4). `[verify table/columns]`
- `permissions` — seed `view_store`, `manage_store`, `approve_store_requisition` (SM1). `[verify]`

---

## Appendix B — AI Prompt To Start (paste into Claude Code)

> Read `CANIN.md` and `STORE_TASKS.md` fully first. Build the new **Store (Inventory)
> module** for this CodeIgniter 3 Physical Therapy Clinic app.
>
> **Run autonomously in the order SM1 → SM6. Do NOT stop for my confirmation between
> milestones.** After each milestone, self-verify its Acceptance Criteria, run `php -l`,
> append a completion note to `STORE_BUILD_LOG.md`, commit that milestone, and immediately
> begin the next one. Keep going until SM6 is complete, then write one final summary.
> Stop and ask me **only** on a real blocker per §0.2 (acceptance criteria fail after a
> genuine fix attempt, an ambiguous/conflicting `[verify]` schema name, scope creep beyond
> this spec, or an unexplained `php -l` failure). Do not start SM7 — it is deferred.
>
> Start with `application/controllers/Store.php`, `application/models/Store_model.php`,
> `application/models/Inventory_model.php`, views under `application/views/store/`, the
> route in `application/config/routes.php`, both `app_lang.php` files, and
> `database/physical_therapy_clinic.sql`. Reuse `Safe_model`, `Wallet_model`,
> `Expense_model`, and `Auth`.
>
> Before writing any query, resolve every `[verify]` column/enum name against the real
> schema with `graphify explain` / `graphify query`. **Git:** create one branch
> `store/full-build`, commit per milestone with messages like `SM1: catalog, treasuries,
> stock ledger`; never push/merge/rebase/reset. Follow the language (both `app_lang.php`),
> Shamsi-date (`to_shamsi`/`to_gregorian`), Select2, DataTables, responsive, and RTL rules
> in `CANIN.md`. Run `graphify update .` at the end.

---

## Appendix C — Completion Report

Use the exact completion-report template at the bottom of `TASKS.md` for every store
milestone, appending each one to `STORE_BUILD_LOG.md` as the run proceeds (instead of
stopping to hand each report to the human). The final summary at the end of SM6 links to
that log.
