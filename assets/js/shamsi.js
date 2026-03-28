(function (window, document, $) {
	'use strict';

	function normalizeDigits(value) {
		return String(value === null || value === undefined ? '' : value).replace(/[۰-۹٠-٩]/g, function (char) {
			var map = {
				'۰': '0',
				'۱': '1',
				'۲': '2',
				'۳': '3',
				'۴': '4',
				'۵': '5',
				'۶': '6',
				'۷': '7',
				'۸': '8',
				'۹': '9',
				'٠': '0',
				'١': '1',
				'٢': '2',
				'٣': '3',
				'٤': '4',
				'٥': '5',
				'٦': '6',
				'٧': '7',
				'٨': '8',
				'٩': '9'
			};

			return map[char] || char;
		});
	}

	function parseGregorianDate(value) {
		var normalized = normalizeDigits(value).trim();
		var match = normalized.match(/^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2})(?::(\d{2}))?)?$/);

		if (!match) {
			return null;
		}

		return new Date(
			parseInt(match[1], 10),
			parseInt(match[2], 10) - 1,
			parseInt(match[3], 10),
			parseInt(match[4] || '0', 10),
			parseInt(match[5] || '0', 10),
			parseInt(match[6] || '0', 10)
		);
	}

	function formatPersianDate(value, format) {
		if (typeof window.persianDate === 'undefined') {
			return normalizeDigits(value);
		}

		var parsed = parseGregorianDate(value);

		if (!parsed || isNaN(parsed.getTime())) {
			return normalizeDigits(value);
		}

		try {
			return normalizeDigits(new window.persianDate(parsed).format(format || 'YYYY/MM/DD'));
		} catch (error) {
			return normalizeDigits(value);
		}
	}

	/**
	 * Jalali Datepicker global init for CANIN project.
	 * Apply to any input with class .shamsi-date
	 * Format: YYYY/MM/DD
	 * Always Western digits.
	 */
	function initJalaliDatepicker(selector) {
		if (!$) {
			return;
		}

		$(selector).each(function () {
			var $input = $(this);

			if ($input.hasClass('jalali-init')) {
				return;
			}

			$input.addClass('jalali-init');

			if ($.fn.datepicker) {
				$input.datepicker({
					format: 'yyyy/mm/dd',
					autoclose: true,
					calendarType: 'jalali',
					language: 'fa-ir-la',
					todayHighlight: true,
					clearBtn: true
				});
				return;
			}

			if ($.fn.persianDatepicker) {
				$input.persianDatepicker({
					format: 'YYYY/MM/DD',
					autoClose: true,
					initialValue: false,
					persianDigit: false,
					calendar: {
						persian: { locale: 'en' }
					},
					navigator: {
						enabled: true,
						scroll: { enabled: false },
						title: { enabled: true }
					},
					toolbox: {
						enabled: true,
						todayButton: { enabled: true },
						submitButton: { enabled: true }
					}
				});
			}
		});
	}

	function initShamsiMonthpicker(selector) {
		if (!$ || !$.fn || typeof $.fn.persianDatepicker !== 'function') {
			return;
		}

		$(selector).each(function () {
			var $input = $(this);

			if ($input.attr('data-shamsi-month-initialized') === '1') {
				return;
			}

			$input.attr('data-shamsi-month-initialized', '1');
			$input.persianDatepicker({
				format: 'YYYY/MM',
				autoClose: true,
				initialValue: false,
				persianDigit: false,
				calendar: {
					persian: {
						locale: 'en'
					}
				},
				dayPicker: {
					enabled: false
				},
				monthPicker: {
					enabled: true
				},
				yearPicker: {
					enabled: true
				}
			});
		});
	}

	window.initJalaliDatepicker = initJalaliDatepicker;
	window.initShamsiMonthpicker = initShamsiMonthpicker;
	window.formatShamsiDate = formatPersianDate;

	if (!$) {
		return;
	}

	$(document).ready(function () {
		initJalaliDatepicker('.shamsi-date');
		initShamsiMonthpicker('.shamsi-month');
	});

	$(document).on('focus', '.shamsi-date:not(.jalali-init)', function () {
		initJalaliDatepicker(this);
	});

	$(document).on('focus', '.shamsi-month', function () {
		initShamsiMonthpicker(this);
	});
})(window, document, window.jQuery);
