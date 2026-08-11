(function (window, document) {
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
	 * Jalali Datepicker (github.com/majidh1/jalaliDatePicker) global init for CANIN project.
	 * Applies to any input with class .shamsi-date (full date) or .shamsi-month (month only).
	 * Format: YYYY/MM/DD (or YYYY/MM for month inputs). Always Western digits.
	 */
	var watchStarted = false;

	function stripToMonth(input) {
		var normalized = normalizeDigits(input.value);
		var match = normalized.match(/^(\d{4}\/\d{2})/);

		if (match && normalized !== match[1]) {
			input.value = match[1];
		}
	}

	// Bootstrap modals trap focus inside themselves (util/focustrap.js) and yank
	// it back whenever something outside the modal is focused. The picker popup
	// is appended to <body>, outside the modal's DOM, so without this any click
	// on the popup gets immediately undone. Disabling per-modal focus trapping
	// (data-bs-focus="false", read by Bootstrap when the modal instance is first
	// created) is the documented escape hatch for third-party popups like this.
	function disableFocusTrapForDateModals() {
		document.querySelectorAll('.modal').forEach(function (modal) {
			if (modal.querySelector('.shamsi-date, .shamsi-month')) {
				modal.setAttribute('data-bs-focus', 'false');
			}
		});
	}

	function startWatch() {
		if (watchStarted || typeof window.jalaliDatepicker === 'undefined') {
			return;
		}

		watchStarted = true;

		disableFocusTrapForDateModals();

		window.jalaliDatepicker.startWatch({
			selector: '.shamsi-date, .shamsi-month',
			persianDigits: false,
			hideAfterChange: true,
			zIndex: 1075, // above Bootstrap's .modal (1055) and .modal-backdrop (1050)
			months: ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت']
		});

		document.addEventListener('jdp:change', function (event) {
			if (event.target && event.target.classList && event.target.classList.contains('shamsi-month')) {
				stripToMonth(event.target);
			}
		});
	}

	// Kept for backward compatibility with call sites that used to init dynamically
	// added inputs manually; the delegated watcher above already covers them.
	function initJalaliDatepicker() {
		startWatch();
	}

	function initShamsiMonthpicker() {
		startWatch();
	}

	window.initJalaliDatepicker = initJalaliDatepicker;
	window.initShamsiMonthpicker = initShamsiMonthpicker;
	window.formatShamsiDate = formatPersianDate;

	document.addEventListener('DOMContentLoaded', startWatch);
})(window, document);
