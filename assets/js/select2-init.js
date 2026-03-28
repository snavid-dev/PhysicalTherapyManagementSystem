/**
 * Global Select2 initializer for CANIN project.
 *
 * Single select with search:
 *   Add class .s2-select to any <select>
 *
 * Multiple select with search:
 *   Add class .s2-select-multiple to any <select multiple>
 *
 * For dynamically added elements (bulk form rows):
 *   Call initSelect2(selector) manually after adding row.
 */

function bindSelect2NativeChange($element) {
	if ($element.data('select2-native-change-bound')) {
		return;
	}

	$element.data('select2-native-change-bound', true);
	$element.on('select2:select.select2Bridge select2:unselect.select2Bridge select2:clear.select2Bridge', function () {
		this.dispatchEvent(new Event('change', { bubbles: true }));
	});
}

function initSelect2(context) {
	var $context = $(context || document);
	var $singleSelects = $context.filter('.s2-select').add($context.find('.s2-select'));
	var $multiSelects = $context.filter('.s2-select-multiple').add($context.find('.s2-select-multiple'));

	$singleSelects.each(function () {
		var $dropdownParent;
		var options;

		if ($(this).data('select2')) return;
		$dropdownParent = $(this).closest('.modal');
		options = {
			theme: 'bootstrap-5',
			width: '100%',
			allowClear: true,
			placeholder: $(this).data('placeholder')
				|| $(this).find('option[value=""]').text()
				|| 'Select...',
			language: getSelect2Lang()
		};

		if ($dropdownParent.length) {
			options.dropdownParent = $dropdownParent;
		}

		$(this).select2(options);
		bindSelect2NativeChange($(this));
	});

	$multiSelects.each(function () {
		var $dropdownParent;
		var options;

		if ($(this).data('select2')) return;
		$dropdownParent = $(this).closest('.modal');
		options = {
			theme: 'bootstrap-5',
			width: '100%',
			allowClear: true,
			closeOnSelect: false,
			placeholder: $(this).data('placeholder') || 'Select...',
			language: getSelect2Lang()
		};

		if ($dropdownParent.length) {
			options.dropdownParent = $dropdownParent;
		}

		$(this).select2(options);
		bindSelect2NativeChange($(this));
	});
}

function getSelect2Lang() {
	if (typeof APP_LANG !== 'undefined' && APP_LANG === 'farsi') {
		return {
			noResults: function () { return 'موردی یافت نشد'; },
			searching: function () { return 'در حال جستجو...'; },
			removeAllItems: function () { return 'حذف همه'; },
			inputTooShort: function () { return 'تایپ کنید...'; }
		};
	}

	return {
		noResults: function () { return 'No results found'; },
		searching: function () { return 'Searching...'; },
		removeAllItems: function () { return 'Remove all'; },
		inputTooShort: function () { return 'Start typing...'; }
	};
}

$(document).ready(function () {
	initSelect2(document);
});
