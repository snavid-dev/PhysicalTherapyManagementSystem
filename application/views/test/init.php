<?php $ci = get_instance(); ?>
<div class="row" style=" row-gap: 50px">
	<div class="col-sm-12 col-md-3">
		<div class="switch flex_switch">
			<input role="switch" type="checkbox" class="switch-input"
				   value="echo1"/>
			<label class="switch-input-label">echo1</label>
		</div>
	</div>

	<div class="col-sm-12 col-md-3">
		<div class="switch flex_switch">
			<input role="switch" type="checkbox" class="switch-input"
				   value="echo2"/>
			<label class="switch-input-label">echo2</label>
		</div>
	</div>

	<div class="col-sm-12 col-md-3">
		<div class="switch flex_switch">
			<input role="switch" type="checkbox" class="switch-input"
				   value="echo3"/>
			<label class="switch-input-label">echo3</label>
		</div>
	</div>

	<div class="col-sm-12 col-md-3">
		<div class="switch flex_switch">
			<input role="switch" type="checkbox" class="switch-input"
				   value="echo4"/>
			<label class="switch-input-label">echo4</label>
		</div>
	</div>

</div>
<div style="width: 100%; padding: 5px; margin-top: 90px">
	<button class="btn btn-success" id="getTogglesButton" onclick="getOnToggles()" style="float: right">
		Get toggles
	</button>
</div>

<script>
	// Function to get values of all checked toggles
	function getOnToggles() {
		// Select all checkbox inputs with the class 'switch-input'
		const toggles = document.querySelectorAll('.switch-input');
		// Filter and get the values of only the checked toggles
		const onToggles = Array.from(toggles)
			.filter(toggle => toggle.checked)
			.map(toggle => toggle.value);

		// Log the result in the console
		console.log('Checked Values:', onToggles);
	}

</script>
