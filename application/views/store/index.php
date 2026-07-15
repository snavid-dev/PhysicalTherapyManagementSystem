<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5 store-hub">
	<div class="store-hub-hero card mb-4">
		<div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
			<div>
				<h1 class="h3 mb-1"><?= t('store_management') ?></h1>
				<p class="text-muted mb-0"><?= t('store_hub_subtitle') ?></p>
			</div>
			<?php if ($this->auth->has_permission('manage_store')): ?>
			<div class="d-flex gap-2 flex-wrap">
				<a href="<?= site_url('store/sell') ?>" class="btn btn-primary btn-lg"><?= t('sell_product') ?></a>
				<a href="<?= site_url('store/bulk_sell') ?>" class="btn btn-outline-primary btn-lg"><?= t('bulk_sell') ?></a>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if (isset($today_revenue)): ?>
	<div class="row g-3 mb-4">
		<div class="col-6 col-md-4">
			<div class="card h-100 store-hub-stat store-hub-stat--green">
				<div class="card-body d-flex justify-content-between align-items-center">
					<div>
						<div class="stat-label"><?= t('today_revenue') ?></div>
						<div class="stat-value"><?= number_format($today_revenue, 2) ?></div>
					</div>
					<div class="store-hub-stat__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
							<path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
						</svg>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 col-md-4">
			<div class="card h-100 store-hub-stat store-hub-stat--blue">
				<div class="card-body d-flex justify-content-between align-items-center">
					<div>
						<div class="stat-label"><?= t('today_sales_count') ?></div>
						<div class="stat-value"><?= (int) $today_sales_count ?></div>
					</div>
					<div class="store-hub-stat__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
							<path d="M3 3h2l2.4 12.2a2 2 0 0 0 2 1.8h8.2a2 2 0 0 0 2-1.6L21 8H6"></path>
							<circle cx="9" cy="21" r="1"></circle>
							<circle cx="18" cy="21" r="1"></circle>
						</svg>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card h-100 store-hub-stat <?= $open_debt_count > 0 ? 'store-hub-stat--amber' : '' ?>">
				<div class="card-body d-flex justify-content-between align-items-center">
					<div>
						<div class="stat-label"><?= t('open_debts') ?></div>
						<div class="stat-value"><?= (int) $open_debt_count ?></div>
					</div>
					<div class="store-hub-stat__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="9"></circle>
							<path d="M12 7v5l3 3"></path>
						</svg>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<div class="row g-3 mb-2">
		<div class="col-12">
			<div class="card h-100 store-hub-tile">
				<div class="card-body">
					<div class="store-hub-tile__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
							<rect x="3" y="4" width="18" height="14" rx="2"></rect>
							<path d="M3 9h18M8 4v5"></path>
						</svg>
					</div>
					<h5 class="card-title"><?= t('stock') ?></h5>
					<p class="card-text text-muted small"><?= t('view_manage_stock_levels') ?></p>
					<a href="<?= site_url('store/stock') ?>" class="btn btn-outline-primary"><?= t('go_to_stock') ?></a>
				</div>
			</div>
		</div>
	</div>

	<?php if ($this->auth->has_permission('manage_store')): ?>
	<h2 class="h6 text-muted text-uppercase mt-4 mb-3"><?= t('point_of_sale_section') ?></h2>
	<div class="row g-3 mb-2">
		<div class="col-md-6 col-lg-3">
			<div class="card h-100 store-hub-tile">
				<div class="card-body">
					<div class="store-hub-tile__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
							<rect x="2" y="7" width="20" height="14" rx="2"></rect>
							<path d="M16 3H8v4h8V3Z"></path>
						</svg>
					</div>
					<h5 class="card-title">
						<?= t('sale_batches') ?>
						<?php if (!empty($pending_sale_batches_count)): ?>
							<span class="badge bg-warning text-dark"><?= (int) $pending_sale_batches_count ?></span>
						<?php endif; ?>
					</h5>
					<p class="card-text text-muted small"><?= t('bulk_sell_hint') ?></p>
					<a href="<?= site_url('store/sale_batches') ?>" class="btn btn-outline-primary"><?= t('sale_batches') ?></a>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-lg-3">
			<div class="card h-100 store-hub-tile">
				<div class="card-body">
					<div class="store-hub-tile__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
							<path d="M3 3v18h18"></path>
							<path d="M7 15l4-6 3 4 5-8"></path>
						</svg>
					</div>
					<h5 class="card-title"><?= t('store_reports') ?></h5>
					<p class="card-text text-muted small"><?= t('store_reports_hint') ?></p>
					<a href="<?= site_url('store/reports') ?>" class="btn btn-outline-primary"><?= t('store_reports') ?></a>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-lg-3">
			<div class="card h-100 store-hub-tile">
				<div class="card-body">
					<div class="store-hub-tile__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
							<path d="M20 7h-9M14 17H5"></path>
							<circle cx="17" cy="17" r="3"></circle>
							<circle cx="7" cy="7" r="3"></circle>
						</svg>
					</div>
					<h5 class="card-title"><?= t('store_products') ?></h5>
					<p class="card-text text-muted small"><?= t('manage_products_and_variants') ?></p>
					<a href="<?= site_url('store/products') ?>" class="btn btn-outline-primary"><?= t('go_to_products') ?></a>
					<a href="<?= site_url('store/categories') ?>" class="btn btn-outline-secondary btn-sm ms-1"><?= t('manage_categories') ?></a>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-lg-3">
			<div class="card h-100 store-hub-tile">
				<div class="card-body">
					<div class="store-hub-tile__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
							<path d="M12 2v6M12 22v-6M4.9 4.9l4.2 4.2M14.9 14.9l4.2 4.2M2 12h6M22 12h-6M4.9 19.1l4.2-4.2M14.9 9.1l4.2-4.2"></path>
						</svg>
					</div>
					<h5 class="card-title"><?= t('set_opening_stock') ?></h5>
					<p class="card-text text-muted small"><?= t('stock_intake_hint') ?></p>
					<a href="<?= site_url('store/set_opening_stock') ?>" class="btn btn-outline-primary"><?= t('set_opening_stock') ?></a>
				</div>
			</div>
		</div>
	</div>

	<h2 class="h6 text-muted text-uppercase mt-4 mb-3"><?= t('inventory_section') ?></h2>
	<div class="row g-3">
		<div class="col-md-6 col-lg-4">
			<div class="card h-100 store-hub-tile">
				<div class="card-body">
					<div class="store-hub-tile__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
							<path d="M4 7l8-4 8 4-8 4-8-4Z"></path>
							<path d="M4 7v10l8 4 8-4V7"></path>
							<path d="M12 11v10"></path>
						</svg>
					</div>
					<h5 class="card-title">
						<?= t('requisitions') ?>
						<?php if (!empty($pending_requisitions_count)): ?>
							<span class="badge bg-warning text-dark"><?= (int) $pending_requisitions_count ?></span>
						<?php endif; ?>
					</h5>
					<p class="card-text text-muted small"><?= t('request_to_location') ?></p>
					<a href="<?= site_url('store/requisitions') ?>" class="btn btn-outline-primary"><?= t('requisitions') ?></a>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-lg-4">
			<div class="card h-100 store-hub-tile">
				<div class="card-body">
					<div class="store-hub-tile__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
							<path d="M21 8V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2"></path>
							<path d="M3 8l2 11a2 2 0 0 0 2 1.7h10A2 2 0 0 0 19 19l2-11"></path>
							<path d="M9 12h6"></path>
						</svg>
					</div>
					<h5 class="card-title"><?= t('stock_receipts') ?></h5>
					<p class="card-text text-muted small"><?= t('receive_stock') ?></p>
					<a href="<?= site_url('store/receive_stock') ?>" class="btn btn-outline-primary"><?= t('receive_stock') ?></a>
					<a href="<?= site_url('store/stock_receipts') ?>" class="btn btn-outline-secondary btn-sm ms-1"><?= t('stock_receipts') ?></a>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-lg-4">
			<div class="card h-100 store-hub-tile">
				<div class="card-body">
					<div class="store-hub-tile__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
							<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
							<path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"></path>
							<circle cx="9" cy="7" r="4"></circle>
							<path d="M21 21v-2a4 4 0 0 0-3-3.87"></path>
						</svg>
					</div>
					<h5 class="card-title"><?= t('suppliers') ?></h5>
					<p class="card-text text-muted small"><?= t('suppliers') ?></p>
					<a href="<?= site_url('store/suppliers') ?>" class="btn btn-outline-primary"><?= t('suppliers') ?></a>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
