/**
 * Global Bootstrap tooltip initializer for CANIN project.
 * Apply to any element by adding: data-tooltip="1" title="..."
 * data-tooltip is used instead of Bootstrap's own data-bs-toggle="tooltip"
 * convention because many buttons already use data-bs-toggle for a modal,
 * dropdown, or collapse — Bootstrap only reads one data-bs-toggle value per
 * element, so stacking "tooltip" on top of "modal" silently breaks one of them.
 * Call window.CANINTooltips.initAll(container) after injecting new markup
 * (e.g. AJAX-rendered rows) so newly added tooltips get wired up too.
 */
(function ($) {
	'use strict';

	function initAll(context) {
		if (!window.bootstrap || !window.bootstrap.Tooltip) {
			return;
		}

		$(context || document).find('[data-tooltip]').each(function () {
			window.bootstrap.Tooltip.getOrCreateInstance(this, { trigger: 'hover focus' });
		});
	}

	$(document).ready(function () {
		initAll(document);
	});

	$(document).on('shown.bs.modal', function (event) {
		initAll(event.target);
	});

	window.CANINTooltips = {
		initAll: initAll
	};
})(jQuery);
