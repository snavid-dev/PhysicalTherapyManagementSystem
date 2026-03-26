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

	function basePickerOptions(format) {
		return {
			format: format,
			autoClose: true,
			initialValue: false,
			observer: true,
			autoCloseDelay: 150,
			calendar: {
				persian: {
					locale: 'en'
				}
			}
		};
	}

	function applyPicker($elements, options, marker) {
		if (!$ || !$.fn || typeof $.fn.persianDatepicker !== 'function') {
			return;
		}

		$elements.each(function () {
			var $input = $(this);

			if ($input.attr(marker) === '1') {
				return;
			}

			$input.attr(marker, '1');
			$input.persianDatepicker(options);
		});
	}

	function initShamsiDatepicker(selector) {
		if (!$) {
			return;
		}

		applyPicker($(selector), basePickerOptions('YYYY/MM/DD'), 'data-shamsi-initialized');
	}

	function initShamsiMonthpicker(selector) {
		if (!$) {
			return;
		}

		var options = basePickerOptions('YYYY/MM');
		options.dayPicker = {
			enabled: false
		};
		options.monthPicker = {
			enabled: true
		};
		options.yearPicker = {
			enabled: true
		};

		applyPicker($(selector), options, 'data-shamsi-month-initialized');
	}

	window.initShamsiDatepicker = initShamsiDatepicker;
	window.initShamsiMonthpicker = initShamsiMonthpicker;
	window.formatShamsiDate = formatPersianDate;

	if (!$) {
		return;
	}

	$(function () {
		initShamsiDatepicker('.shamsi-date');
		initShamsiMonthpicker('.shamsi-month');
	});

	$(document).on('focus', '.shamsi-date', function () {
		initShamsiDatepicker(this);
	});

	$(document).on('focus', '.shamsi-month', function () {
		initShamsiMonthpicker(this);
	});
})(window, document, window.jQuery);
