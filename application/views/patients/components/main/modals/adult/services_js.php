<?php
$ci = get_instance();
?>

<script>

	// TODO: the fucking services start
	// Update service_price_resto to accept a callback function
	function service_price_resto(serviceSelectId = '#services_restorative', hiddenInputId = '#services_input_restorative', priceInputId = '#price_tooth_restorative', callback = null) {
		const select = $(serviceSelectId).val();
		let text = select.join();
		$(hiddenInputId).val(text);

		$.ajax({
			url: "<?= base_url('admin/check_service_price') ?>",
			type: 'POST',
			data: {
				service: select
			},
			success: function (response) {
				let result = JSON.parse(response);
				$(priceInputId).val(result[0]);
				// Call the callback function with the result value
				if (typeof callback === 'function') {
					callback(result[0]);
				}
			}
		});
	}

	// Update service_price to accept a callback function
	function service_price(serviceSelectId = '#services', hiddenInputId = '#services_input', priceInputId = '#price_tooth', callback) {
		const select = $(serviceSelectId).val();
		let text = select.join();
		$(hiddenInputId).val(text);

		$.ajax({
			url: "<?= base_url('admin/check_service_price') ?>",
			type: 'POST',
			data: {
				service: select
			},
			success: function (response) {
				let result = JSON.parse(response);
				$(priceInputId).val(result[0]);
				// Call the callback function with the result value
				if (typeof callback === 'function') {
					callback(result[0]);
				}
			}
		});
	}


	function service_price_pro(serviceSelectId = '#services_pro', hiddenInputId = '#services_input_pro', priceInputId = '#price_tooth_pro', callback) {
		const select = $(serviceSelectId).val();
		let text = select.join();
		$(hiddenInputId).val(text);

		$.ajax({
			url: "<?= base_url('admin/check_service_price') ?>",
			type: 'POST',
			data: {
				service: select
			},
			success: function (response) {
				let result = JSON.parse(response);
				$(priceInputId).val(result[0]);
				// Call the callback function with the result value
				if (typeof callback === 'function') {
					callback(result[0]);
				}
			}
		});
	}

	// Function to calculate the sum of values from service_price_resto and service_price
	function calculate_sum() {
		let priceResto = 0;
		let priceService = 0;
		let priceProsthodontics = 0;

		let is_endo = ($('#checkbox_endo').is(':checked')) ? true : false;
		let is_resto = ($('#checkbox_resto').is(':checked')) ? true : false;
		let is_prosthodontics = ($('#checkbox_prosthodontics').is(':checked')) ? true : false;

		// Callback function for service_price_resto
		function handlePriceResto(price) {
			if (is_resto) {
				priceResto = price;
				handleResults();
			}
		}

		function handlePriceProsthodontics(price) {
			if (is_prosthodontics) {
				priceProsthodontics = price;
				handleResults();
			}
		}


		// Callback function for service_price
		function handlePriceService(price) {
			if (is_endo) {
				priceService = price;
				handleResults();
			}
		}

		// Function to handle the results and update the inputs accordingly
		function handleResults() {
			// Calculate the sum
			const sum = priceResto + priceService + priceProsthodontics;

			// Determine which inputs to update based on the values of priceResto and priceService
			if (priceResto === 0 && priceService !== 0) {
				// If priceResto is zero, update only the "priceTag_resto" input
				$('#priceTag_endo').val(sum);
				$('#priceTag_resto').val(sum);
				$('#priceTag_pro').val(sum);
			} else if (priceService === 0 && priceResto !== 0) {
				// If priceService is zero, update only the "priceTag_endo" input
				$('#priceTag_resto').val(sum);
				$('#priceTag_endo').val(sum);
				$('#priceTag_pro').val(sum);

			} else {
				// If both priceResto and priceService have non-zero values, update both inputs
				$('#priceTag_resto').val(sum);
				$('#priceTag_endo').val(sum);
				$('#priceTag_pro').val(sum);
			}
		}

		// Add event listeners to handle changes in price_tooth_restorative and price_tooth inputs
		document.getElementById('price_tooth_restorative').addEventListener('change', () => {
			priceResto = parseFloat(document.getElementById('price_tooth_restorative').value) || 0;
			handleResults();
		});

		document.getElementById('price_tooth_pro').addEventListener('change', () => {
			priceProsthodontics = parseFloat(document.getElementById('price_tooth_pro').value) || 0;
			handleResults();
		});

		document.getElementById('price_tooth').addEventListener('change', () => {
			priceService = parseFloat(document.getElementById('price_tooth').value) || 0;
			handleResults();
		});

		// Call the functions with the appropriate callback functions
		service_price_resto('#services_restorative', '#services_input_restorative', '#price_tooth_restorative', handlePriceResto);
		service_price('#services', '#services_input', '#price_tooth', handlePriceService);
		service_price_pro('#services_pro', '#services_input_pro', '#price_tooth_pro', handlePriceProsthodontics);
	}

	// TODO: the fucking services end
</script>

<script>
function calculate_sum_update() {
	const isRestoChecked = $('#checkbox_update_resto').is(':checked');
	const isEndoChecked = $('#checkbox_update_endo').is(':checked');
	const isProChecked = $('#checkbox_update_prosthodontics').is(':checked');

	const restoPrice = parseInt($('#price_tooth_restorative_update').val()) || 0;
	const endoPrice = parseInt($('#price_tooth_endo_update').val()) || 0;
	const proPrice = parseInt($('#price_tooth_pro_update').val()) || 0;

	// Include only if the corresponding checkbox is checked
	const priceRestoUpdate = isRestoChecked ? restoPrice : 0;
	const priceService = isEndoChecked ? endoPrice : 0;
	const priceProsthodontics = isProChecked ? proPrice : 0;

	const sum = priceRestoUpdate + priceService + priceProsthodontics;

	console.log({
		priceRestoUpdate,
		priceService,
		priceProsthodontics,
		sum
	});

	// Update all total fields
	$('#priceTag_resto_update').val(sum);
	$('#priceTag_endo_update').val(sum);
	$('#priceTag_pro_update').val(sum);
	$('#total_price_update').val(sum);
}


// ✅ Helper function to safely get numeric values from input fields
	function getNumericValue(selector) {
		return parseFloat($(selector).val()) || 0;
	}

	function insert_endo_price_update(price) {
		calculate_sum_update(null, price);
	}

	function insert_resto_price_update(price) {
		calculate_sum_update(price);
	}

	function insert_pro_price_update(price) {
		calculate_sum_update(null, null, price);
	}

document.addEventListener("DOMContentLoaded", function () {
	const inputSelectors = [
		"#price_tooth_restorative_update",
		"#price_tooth_endo_update",
		"#price_tooth_pro_update"
	];

	const checkboxSelectors = [
		"#checkbox_update_resto",
		"#checkbox_update_endo",
		"#checkbox_update_prosthodontics"
	];

	// Trigger on input/change in price fields
	inputSelectors.forEach(selector => {
		const el = document.querySelector(selector);
		if (el) {
			el.addEventListener("input", calculate_sum_update);
			el.addEventListener("change", calculate_sum_update);
		}
	});

	// Trigger on checkbox toggle
	checkboxSelectors.forEach(selector => {
		const el = document.querySelector(selector);
		if (el) {
			el.addEventListener("change", calculate_sum_update);
		}
	});

	// Initial load
	calculate_sum_update();
});




	// TODO: the fucking services end
</script>
