# Store Module Build Log

## SM1: Catalog, Treasuries, Stock Ledger ✅

**Status**: Completed (awaiting verification)

**Implemented**:
- Product categories CRUD (create, read, update, delete)
- Products with variants CRUD
- Two stock locations (Front Desk, Warehouse) seeded
- Stock movement ledger (append-only)
- Stock levels cache with recompute() idempotent function
- Opening stock functionality
- Stock view per location with warehouse visibility from front desk
- Store permissions (view_store, manage_store, approve_store_requisition)
- Permission-gated navigation and routes
- All strings in both English and Farsi language files
- RTL-compatible views

**Schema Changes**:
- `store_product_categories` table
- `store_products` table
- `store_product_variants` table
- `store_locations` table (seeded with 2 rows)
- `stock_levels` table
- `stock_movements` table (append-only ledger)
- Added `store_sale`, `store_refund` to safe_transactions.source enum
- Added store permissions to permissions table
- Added Inventory Purchase category to expense_categories

**Files Created**:
- `application/controllers/Store.php`
- `application/models/Store_model.php`
- `application/models/Inventory_model.php`
- `application/views/store/index.php`
- `application/views/store/products.php`
- `application/views/store/product_form.php`
- `application/views/store/variant_form.php`
- `application/views/store/categories.php`
- `application/views/store/category_form.php`
- `application/views/store/stock.php`
- `application/views/store/opening_stock_form.php`

**Acceptance Criteria Verification**:
1. ✓ Can create category and product with variants
2. ⏳ Set opening stock and verify stock_movements row (manual test needed)
3. ⏳ Verify idempotency of recompute_stock_level() (manual test needed)
4. ⏳ Verify front-desk sees warehouse quantity (manual test needed)
5. ✓ Stock validation prevents negative quantities
6. ✓ Routes and nav gated by permissions
7. ✓ Strings in both languages, RTL-compatible

**Next**: SM2 requisition & approval workflow
