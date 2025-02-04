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

<!--update functions-->
<script>

	// Function to calculate the sum of values from service_price_resto and service_price
	function calculate_sum_update() {
		let priceRestoUpdate = 0;
		let priceService = 0;
		let priceProsthodontics = 0;

		let is_endo = ($('#checkbox_update_endo').is(':checked')) ? true : false;
		let is_resto = ($('#checkbox_update_resto_update').is(':checked')) ? true : false;
		let is_prosthodontics = ($('#checkbox_update_prosthodontics_update').is(':checked')) ? true : false;

		// Callback function for service_price_resto
		function handlePriceRestoUpdate(price) {
			if (is_resto) {
				priceRestoUpdate = price;
				handleResults_update();
			}
		}

		function handlePriceProsthodonticsUpdate(price) {
			if (is_prosthodontics) {
				priceProsthodontics = price;
				handleResults_update();
			}
		}


		// Callback function for service_price
		function handlePriceServiceUpdate(price) {
			if (is_endo) {
				priceService = price;
				handleResults_update();
			}
		}

		// Function to handle the results and update the inputs accordingly
		function handleResults_update() {
			// Calculate the sum
			console.log(priceRestoUpdate);
			console.log(priceService);
			console.log(priceProsthodontics);
			const sum = priceRestoUpdate + priceService + priceProsthodontics;

			// Determine which inputs to update based on the values of priceRestoUpdate and priceService
			if (priceRestoUpdate === 0 && priceService !== 0) {
				// If priceRestoUpdate is zero, update only the "priceTag_resto" input
				$('#priceTag_endo_update').val(sum);
				$('#priceTag_resto_update').val(sum);
				$('#priceTag_pro_update').val(sum);
			} else if (priceService === 0 && priceRestoUpdate !== 0) {
				// If priceService is zero, update only the "priceTag_endo" input
				$('#priceTag_resto_update').val(sum);
				$('#priceTag_endo_update').val(sum);
				$('#priceTag_pro_update').val(sum);

			} else {
				// If both priceRestoUpdate and priceService have non-zero values, update both inputs
				$('#priceTag_resto_update').val(sum);
				$('#priceTag_endo_update').val(sum);
				$('#priceTag_pro_update').val(sum);
			}
		}

		// Add event listeners to handle changes in price_tooth_restorative and price_tooth inputs
		document.getElementById('price_tooth_restorative_update').addEventListener('change', () => {
			priceRestoUpdate = parseFloat(document.getElementById('price_tooth_restorative_update').value) || 0;
			handleResults_update();
		});

		document.getElementById('price_tooth_pro_update').addEventListener('change', () => {
			priceProsthodontics = parseFloat(document.getElementById('price_tooth_pro_update').value) || 0;
			handleResults_update();
		});

		document.getElementById('price_tooth_endo_update').addEventListener('change', () => {
			priceService = parseFloat(document.getElementById('price_tooth_endo_update').value) || 0;
			handleResults_update();
		});

		// Call the functions with the appropriate callback functions
		service_price_resto('#services_restorative_update', '#services_input_restorative_update', '#price_tooth_restorative_update', handlePriceRestoUpdate);
		service_price('#services_endo_update', '#services_input_endo_update', '#price_tooth_endo_update', handlePriceServiceUpdate);
		service_price_pro('#services_pro', '#services_input_pro', '#price_tooth_pro', handlePriceProsthodonticsUpdate);
	}

	// TODO: the fucking services end
</script>
