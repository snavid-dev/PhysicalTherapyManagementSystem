/**
 * Global DataTables initializer for CANIN project.
 * Apply to any table by adding class: .dt-table
 * Customize per table using data attributes:
 *   data-order-col="0"       - default sort column index
 *   data-order-dir="desc"    - default sort direction
 *   data-no-export="true"    - hide export buttons
 *   data-no-sort-cols="4,5"  - comma-separated column indexes to disable sorting on
 */
(function ($) {
	'use strict';

	var appLang = window.APP_LANG || 'english';
	var labels = window.DT_I18N || {};
	var registry = {};
	var isRTL = $('html').attr('dir') === 'rtl' || $('body').hasClass('rtl');

	if (isRTL) {
		// DataTables layout is adjusted through shared CSS.
	}

	function parseBoolean(value) {
		return value === true || value === 'true';
	}

	function parseOrderColumn($table) {
		var orderCol = parseInt($table.data('order-col'), 10);
		return Number.isNaN(orderCol) ? 0 : orderCol;
	}

	function parseNoSortColumns($table) {
		var rawValue = $table.data('no-sort-cols');

		if (!rawValue && rawValue !== 0) {
			return [];
		}

		return String(rawValue)
			.split(',')
			.map(function (value) {
				return parseInt($.trim(value), 10);
			})
			.filter(function (value) {
				return !Number.isNaN(value);
			});
	}

	function buildLanguage() {
		var isFarsi = appLang === 'farsi';

		return {
			search: '',
			searchPlaceholder: isFarsi ? 'جستجو...' : ((labels.search || 'Search') + '...'),
			lengthMenu: (labels.show || (isFarsi ? 'نمایش' : 'Show')) + ' _MENU_ ' + (labels.entries || (isFarsi ? 'ردیف' : 'entries')),
			info: isFarsi ? 'نمایش _START_ تا _END_ از _TOTAL_ ردیف' : 'Showing _START_ to _END_ of _TOTAL_ entries',
			infoEmpty: isFarsi ? 'موردی یافت نشد' : 'No entries found',
			infoFiltered: isFarsi ? '(فیلتر از _MAX_ کل)' : '(filtered from _MAX_ total)',
			paginate: {
				first: '«',
				last: '»',
				next: '›',
				previous: '‹'
			},
			emptyTable: labels.noData || (isFarsi ? 'داده‌ای موجود نیست' : 'No data available'),
			zeroRecords: isFarsi ? 'موردی یافت نشد' : 'No matching records found'
		};
	}

	function buildButtons(noExport) {
		if (noExport) {
			return [];
		}

		return [
			{
				extend: 'excelHtml5',
				text: labels.exportExcel || 'Excel',
				className: 'btn btn-sm btn-success',
				exportOptions: {
					columns: ':not(.no-export)'
				}
			},
			{
				extend: 'pdfHtml5',
				text: labels.exportPdf || 'PDF',
				className: 'btn btn-sm btn-danger',
				exportOptions: {
					columns: ':not(.no-export)'
				}
			},
			{
				extend: 'print',
				text: labels.print || 'Print',
				className: 'btn btn-sm btn-secondary',
				exportOptions: {
					columns: ':not(.no-export)'
				}
			}
		];
	}

	function initTable(table) {
		var $table = $(table);
		var noExport = parseBoolean($table.data('no-export'));
		var noSortCols = parseNoSortColumns($table);
		var columnDefs = [];
		var api;
		var domLayout;
		var tableKey;

		if (!$table.length) {
			return null;
		}

		if ($.fn.DataTable.isDataTable($table[0])) {
			return $table.DataTable();
		}

		if (noSortCols.length > 0) {
			columnDefs.push({
				orderable: false,
				targets: noSortCols
			});
		}

		domLayout = noExport
			? '<"row mb-3"<"col-sm-12 text-end"f>>'
				+ '<"row"<"col-sm-12"tr>>'
				+ '<"row mt-3"<"col-sm-4"l><"col-sm-4 text-center"i><"col-sm-4"p>>'
			: '<"row mb-3"<"col-sm-6"B><"col-sm-6 text-end"f>>'
				+ '<"row"<"col-sm-12"tr>>'
				+ '<"row mt-3"<"col-sm-4"l><"col-sm-4 text-center"i><"col-sm-4"p>>';

		api = $table.DataTable({
			processing: false,
			serverSide: false,
			order: [[parseOrderColumn($table), $table.data('order-dir') || 'desc']],
			pageLength: 25,
			lengthMenu: [10, 25, 50, 100],
			columnDefs: columnDefs,
			dom: domLayout,
			buttons: buildButtons(noExport),
			language: buildLanguage(),
			responsive: false,
			scrollX: true
		});

		tableKey = $table.attr('id') || $table.data('dt-key');
		if (tableKey) {
			registry[tableKey] = api;
		}

		return api;
	}

	function initAll(context) {
		$(context || document).find('.dt-table').each(function () {
			initTable(this);
		});
	}

	function refreshTable(tableSelector) {
		var $table = $(tableSelector);
		var rows;
		var api;

		if (!$table.length) {
			return null;
		}

		if (!$.fn.DataTable.isDataTable($table[0])) {
			return initTable($table[0]);
		}

		rows = $table.find('tbody tr').toArray();
		api = $table.DataTable();
		api.clear();

		if (rows.length > 0) {
			api.rows.add(rows);
		}

		api.draw(false);
		api.columns.adjust();

		return api;
	}

	function adjustVisibleTables() {
		$('.dt-table').each(function () {
			if ($.fn.DataTable.isDataTable(this)) {
				$(this).DataTable().columns.adjust();
			}
		});
	}

	$(document).ready(function () {
		initAll(document);
	});

	$(document).on('shown.bs.collapse shown.bs.modal shown.bs.tab', function () {
		adjustVisibleTables();
	});

	window.CANINDataTables = {
		adjustVisibleTables: adjustVisibleTables,
		initAll: initAll,
		initTable: initTable,
		refreshTable: refreshTable,
		registry: registry
	};
})(jQuery);
