<script>
	// var counters = {};
	//
	// function singleColorize(div, cls1id, cls2id, cls3id = null, gLocatoinId) {
	// 	// Initialize the counter if it doesn't exist
	// 	if (!counters[cls1id]) counters[cls1id] = 0;
	//
	// 	// Cache DOM elements once
	// 	let clsEle1 = document.getElementById(cls1id);
	// 	let clsEle2 = document.getElementById(cls2id);
	// 	let clsEle3 = cls3id ? document.getElementById(cls3id) : null;
	//
	// 	// let input = document.getElementById(inputId);
	// 	// let g_loc = document.getElementById(gLocatoinId);
	//
	// 	// remember that Here  Increment the counter
	// 	counters[cls1id] += 1;
	//
	// 	let currentCount = counters[cls1id];
	//
	// 	// Reset classes based on counter !important
	// 	function resetClasses() {
	// 		let classesToRemove = ['echo1', 'echo2', 'echo3'];
	// 		[clsEle1, clsEle2, clsEle3].forEach(ele => {
	// 			if (ele) {
	// 				classesToRemove.forEach(cls => ele.classList.remove(cls));
	// 			}
	// 		});
	// 		counters[cls1id] = 0;
	// 	}
	//
	// 	// Apply the appropriate class
	// 	function applyClass(className) {
	// 		[clsEle1, clsEle2, clsEle3].forEach(ele => {
	// 			if (ele) ele.classList.toggle(className);
	// 		});
	// 	}
	//
	// 	if (currentCount === 1) {
	// 		applyClass('echo1');
	// 	} else if (currentCount === 2) {
	// 		applyClass('echo2');
	// 	} else if (currentCount === 3) {
	// 		applyClass('echo3');
	// 	} else {
	// 		resetClasses();
	// 	}
	//
	// 	// Here is the name and address of the tooth you will send these to server
	// 	// input.value = counters[cls1id];
	// 	// g_loc.value = div.id;
	//
	//
	// 	console.log('Current count:', counters[cls1id]);
	// 	console.log('Current div:', div.id);
	//
	// 	document.getElementById("sigleChoiseInput").value = `${div.id} : ${counters[cls1id]}`
	// }


</script>




<script>
	var counters = {};

	function colorize(teeth) {
		let locationCounterPairs = [];
		teeth.forEach(tooth => {
			const { divId, cls1id, cls2id, cls3id = null, inputId, locationId } = tooth;

			if (!counters[cls1id]) counters[cls1id] = 0;

			let currentCount = counters[cls1id]; // Store the current counter value
			let currentDiv = document.getElementById(divId); // Get the div element by ID

			let clsEle1 = document.getElementById(cls1id);
			let clsEle2 = document.getElementById(cls2id);
			let clsEle3 = cls3id ? document.getElementById(cls3id) : null;

			let input = document.getElementById(inputId);
			let g_loc = document.getElementById(locationId);

			currentCount++; // Increment the counter

			if (clsEle3 != null) {
				if (currentCount === 1) {
					clsEle1.classList.toggle("echo1");
					clsEle2.classList.toggle("echo1");
					clsEle3.classList.toggle("echo1");
				}
				else {
					// Reset the classes
					clsEle1.classList.remove("echo1", "echo2", "echo3");
					clsEle2.classList.remove("echo1", "echo2", "echo3");
					clsEle3.classList.remove("echo1", "echo2", "echo3");
					currentCount = 0;
				}
			} else {
				// When clsEle3 is null (only 2 elements)
				if (currentCount === 1) {
					clsEle1.classList.toggle("echo1");
					clsEle2.classList.toggle("echo1");
				}
				else {
					// Reset the classes
					clsEle1.classList.remove("echo1", "echo2", "echo3");
					clsEle2.classList.remove("echo1", "echo2", "echo3");
					currentCount = 0;
				}
			}

			counters[cls1id] = currentCount; // Update the counter

			// Update the input values for the current tooth If You dont have the input to put these shits comment the two line below
			// input.value = currentCount;
			// g_loc.value = divId;

			// these are fot just debuging dont delete it

			// console.log('Current count:', counters[cls1id]);
			// console.log('Current div:', divId);

			// add the values to the above array
			locationCounterPairs.push(`${divId}:${counters[cls1id]}`);

			if (counters[cls1id] === 0){
				// Erase the array in order to pervent sending wrong values into the server
				locationCounterPairs = [];
			}

		});
		document.getElementById('locationCounterInput').value = locationCounterPairs.join(', ');

	}



	const allJaws = [
		{ divId: 'g1', cls1id: 'g1cls1', cls2id: 'g1cls2', cls3id: null, inputId: 'g1CounterValue', locationId: 'g1Location' },
		{ divId: 'g2', cls1id: 'g2cls1', cls2id: 'g2cls2', cls3id: null, inputId: 'g2CounterValue', locationId: 'g2Location' },
		{ divId: 'g3', cls1id: 'g3cls1', cls2id: 'g3cls2', cls3id: null, inputId: 'g3CounterValue', locationId: 'g3Location' },
		{ divId: 'g4', cls1id: 'g4cls1', cls2id: 'g4cls2', cls3id: 'g4cls3', inputId: 'g4CounterValue', locationId: 'g4Location' },
		{ divId: 'g5', cls1id: 'g5cls1', cls2id: 'g5cls2', cls3id: 'g5cls3', inputId: 'g5CounterValue', locationId: 'g5Location' },
		{ divId: 'g6', cls1id: 'g6cls1', cls2id: 'g6cls2', cls3id: null, inputId: 'g6CounterValue', locationId: 'g6Location' },
		{ divId: 'g7', cls1id: 'g7cls1', cls2id: 'g7cls2', cls3id: null, inputId: 'g7CounterValue', locationId: 'g7Location' },
		{ divId: 'g8', cls1id: 'g8cls1', cls2id: 'g8cls2', cls3id: null, inputId: 'g8CounterValue', locationId: 'g8Location' },
		{ divId: 'g9', cls1id: 'g9cls1', cls2id: 'g9cls2', cls3id: null, inputId: 'g9CounterValue', locationId: 'g9Location' },
		{ divId: 'g10', cls1id: 'g10cls1', cls2id: 'g10cls2', cls3id: null, inputId: 'g10CounterValue', locationId: 'g10Location' },
		{ divId: 'g11', cls1id: 'g11cls1', cls2id: 'g11cls2', cls3id: null, inputId: 'g11CounterValue', locationId: 'g11Location' },
		{ divId: 'g12', cls1id: 'g12cls1', cls2id: 'g12cls2', cls3id: 'g12cls3', inputId: 'g12CounterValue', locationId: 'g12Location' },
		{ divId: 'g13', cls1id: 'g13cls1', cls2id: 'g13cls2', cls3id: 'g13cls3', inputId: 'g13CounterValue', locationId: 'g13Location' },
		{ divId: 'g14', cls1id: 'g14cls1', cls2id: 'g14cls2', cls3id: null, inputId: 'g14CounterValue', locationId: 'g14Location' },
		{ divId: 'g15', cls1id: 'g15cls1', cls2id: 'g15cls2', cls3id: null, inputId: 'g15CounterValue', locationId: 'g15Location' },
		{ divId: 'g16', cls1id: 'g16cls1', cls2id: 'g16cls2', cls3id: null, inputId: 'g16CounterValue', locationId: 'g16Location' },
		{ divId: 'g17', cls1id: 'g17cls1', cls2id: 'g17cls2', cls3id: null, inputId: 'g17CounterValue', locationId: 'g17Location' },
		{ divId: 'g18', cls1id: 'g18cls1', cls2id: 'g18cls2', cls3id: null, inputId: 'g18CounterValue', locationId: 'g18Location' },
		{ divId: 'g19', cls1id: 'g19cls1', cls2id: 'g19cls2', cls3id: null, inputId: 'g19CounterValue', locationId: 'g19Location' },
		{ divId: 'g20', cls1id: 'g20cls1', cls2id: 'g20cls2', cls3id: 'g20cls3', inputId: 'g20CounterValue', locationId: 'g20Location' },
		{ divId: 'g21', cls1id: 'g21cls1', cls2id: 'g21cls2', cls3id: 'g21cls3', inputId: 'g21CounterValue', locationId: 'g21Location' },
		{ divId: 'g22', cls1id: 'g22cls1', cls2id: 'g22cls2', cls3id: null, inputId: 'g22CounterValue', locationId: 'g22Location' },
		{ divId: 'g23', cls1id: 'g23cls1', cls2id: 'g23cls2', cls3id: null, inputId: 'g23CounterValue', locationId: 'g23Location' },
		{ divId: 'g24', cls1id: 'g24cls1', cls2id: 'g24cls2', cls3id: null, inputId: 'g24CounterValue', locationId: 'g24Location' },
		{ divId: 'g25', cls1id: 'g25cls1', cls2id: 'g25cls2', cls3id: null, inputId: 'g25CounterValue', locationId: 'g25Location' },
		{ divId: 'g26', cls1id: 'g26cls1', cls2id: 'g26cls2', cls3id: null, inputId: 'g26CounterValue', locationId: 'g26Location' },
		{ divId: 'g27', cls1id: 'g27cls1', cls2id: 'g27cls2', cls3id: null, inputId: 'g27CounterValue', locationId: 'g27Location' },
		{ divId: 'g28', cls1id: 'g28cls1', cls2id: 'g28cls2', cls3id: 'g28cls3', inputId: 'g28CounterValue', locationId: 'g28Location' },
		{ divId: 'g29', cls1id: 'g29cls1', cls2id: 'g29cls2', cls3id: 'g29cls3', inputId: 'g29CounterValue', locationId: 'g29Location' },
		{ divId: 'g30', cls1id: 'g30cls1', cls2id: 'g30cls2', cls3id: null, inputId: 'g30CounterValue', locationId: 'g30Location' },
		{ divId: 'g31', cls1id: 'g31cls1', cls2id: 'g31cls2', cls3id: null, inputId: 'g31CounterValue', locationId: 'g31Location' },
		{ divId: 'g32', cls1id: 'g32cls1', cls2id: 'g32cls2', cls3id: null, inputId: 'g32CounterValue', locationId: 'g32Location' }
	];

	const upperJaw = [
		{ divId: 'g1', cls1id: 'g1cls1', cls2id: 'g1cls2', cls3id: null, inputId: 'g1CounterValue', locationId: 'g1Location' },
		{ divId: 'g2', cls1id: 'g2cls1', cls2id: 'g2cls2', cls3id: null, inputId: 'g2CounterValue', locationId: 'g2Location' },
		{ divId: 'g3', cls1id: 'g3cls1', cls2id: 'g3cls2', cls3id: null, inputId: 'g3CounterValue', locationId: 'g3Location' },
		{ divId: 'g4', cls1id: 'g4cls1', cls2id: 'g4cls2', cls3id: 'g4cls3', inputId: 'g4CounterValue', locationId: 'g4Location' },
		{ divId: 'g5', cls1id: 'g5cls1', cls2id: 'g5cls2', cls3id: 'g5cls3', inputId: 'g5CounterValue', locationId: 'g5Location' },
		{ divId: 'g6', cls1id: 'g6cls1', cls2id: 'g6cls2', cls3id: null, inputId: 'g6CounterValue', locationId: 'g6Location' },
		{ divId: 'g7', cls1id: 'g7cls1', cls2id: 'g7cls2', cls3id: null, inputId: 'g7CounterValue', locationId: 'g7Location' },
		{ divId: 'g8', cls1id: 'g8cls1', cls2id: 'g8cls2', cls3id: null, inputId: 'g8CounterValue', locationId: 'g8Location' },
		{ divId: 'g9', cls1id: 'g9cls1', cls2id: 'g9cls2', cls3id: null, inputId: 'g9CounterValue', locationId: 'g9Location' },
		{ divId: 'g10', cls1id: 'g10cls1', cls2id: 'g10cls2', cls3id: null, inputId: 'g10CounterValue', locationId: 'g10Location' },
		{ divId: 'g11', cls1id: 'g11cls1', cls2id: 'g11cls2', cls3id: null, inputId: 'g11CounterValue', locationId: 'g11Location' },
		{ divId: 'g12', cls1id: 'g12cls1', cls2id: 'g12cls2', cls3id: 'g12cls3', inputId: 'g12CounterValue', locationId: 'g12Location' },
		{ divId: 'g13', cls1id: 'g13cls1', cls2id: 'g13cls2', cls3id: 'g13cls3', inputId: 'g13CounterValue', locationId: 'g13Location' },
		{ divId: 'g14', cls1id: 'g14cls1', cls2id: 'g14cls2', cls3id: null, inputId: 'g14CounterValue', locationId: 'g14Location' },
		{ divId: 'g15', cls1id: 'g15cls1', cls2id: 'g15cls2', cls3id: null, inputId: 'g15CounterValue', locationId: 'g15Location' },
		{ divId: 'g16', cls1id: 'g16cls1', cls2id: 'g16cls2', cls3id: null, inputId: 'g16CounterValue', locationId: 'g16Location' }
	]

	const downJaw = [
		{ divId: 'g17', cls1id: 'g17cls1', cls2id: 'g17cls2', cls3id: null, inputId: 'g17CounterValue', locationId: 'g17Location' },
		{ divId: 'g18', cls1id: 'g18cls1', cls2id: 'g18cls2', cls3id: null, inputId: 'g18CounterValue', locationId: 'g18Location' },
		{ divId: 'g19', cls1id: 'g19cls1', cls2id: 'g19cls2', cls3id: null, inputId: 'g19CounterValue', locationId: 'g19Location' },
		{ divId: 'g20', cls1id: 'g20cls1', cls2id: 'g20cls2', cls3id: 'g20cls3', inputId: 'g20CounterValue', locationId: 'g20Location' },
		{ divId: 'g21', cls1id: 'g21cls1', cls2id: 'g21cls2', cls3id: 'g21cls3', inputId: 'g21CounterValue', locationId: 'g21Location' },
		{ divId: 'g22', cls1id: 'g22cls1', cls2id: 'g22cls2', cls3id: null, inputId: 'g22CounterValue', locationId: 'g22Location' },
		{ divId: 'g23', cls1id: 'g23cls1', cls2id: 'g23cls2', cls3id: null, inputId: 'g23CounterValue', locationId: 'g23Location' },
		{ divId: 'g24', cls1id: 'g24cls1', cls2id: 'g24cls2', cls3id: null, inputId: 'g24CounterValue', locationId: 'g24Location' },
		{ divId: 'g25', cls1id: 'g25cls1', cls2id: 'g25cls2', cls3id: null, inputId: 'g25CounterValue', locationId: 'g25Location' },
		{ divId: 'g26', cls1id: 'g26cls1', cls2id: 'g26cls2', cls3id: null, inputId: 'g26CounterValue', locationId: 'g26Location' },
		{ divId: 'g27', cls1id: 'g27cls1', cls2id: 'g27cls2', cls3id: null, inputId: 'g27CounterValue', locationId: 'g27Location' },
		{ divId: 'g28', cls1id: 'g28cls1', cls2id: 'g28cls2', cls3id: 'g28cls3', inputId: 'g28CounterValue', locationId: 'g28Location' },
		{ divId: 'g29', cls1id: 'g29cls1', cls2id: 'g29cls2', cls3id: 'g29cls3', inputId: 'g29CounterValue', locationId: 'g29Location' },
		{ divId: 'g30', cls1id: 'g30cls1', cls2id: 'g30cls2', cls3id: null, inputId: 'g30CounterValue', locationId: 'g30Location' },
		{ divId: 'g31', cls1id: 'g31cls1', cls2id: 'g31cls2', cls3id: null, inputId: 'g31CounterValue', locationId: 'g31Location' },
		{ divId: 'g32', cls1id: 'g32cls1', cls2id: 'g32cls2', cls3id: null, inputId: 'g32CounterValue', locationId: 'g32Location' }
	]


	// Apply colorize to all the teeth
	// colorize(teeth);


</script>


<script>
	var counters = {};
	var outputString = ""; // Variable to hold the output string

	function singleColorize(div, cls1id, cls2id, cls3id = null, gLocationId) {
		// Initialize the counter if it doesn't exist
		if (!counters[cls1id]) counters[cls1id] = 0;

		// Cache DOM elements once
		let clsEle1 = document.getElementById(cls1id);
		let clsEle2 = document.getElementById(cls2id);
		let clsEle3 = cls3id ? document.getElementById(cls3id) : null;

		// Increment the counter
		counters[cls1id] += 1;

		let currentCount = counters[cls1id];
		let currentDiv = div.id;

		// Reset classes based on counter
		function resetClasses() {
			let classesToRemove = ['echo1', 'echo2', 'echo3'];
			[clsEle1, clsEle2, clsEle3].forEach(ele => {
				if (ele) {
					classesToRemove.forEach(cls => ele.classList.remove(cls));
				}
			});
			counters[cls1id] = 0;
		}

		// Apply the appropriate class
		function applyClass(className) {
			[clsEle1, clsEle2, clsEle3].forEach(ele => {
				if (ele) ele.classList.toggle(className);
			});
		}

		if (currentCount === 1) {
			applyClass('echo1');
		} else if (currentCount === 2) {
			applyClass('echo2');
		} else if (currentCount === 3) {
			applyClass('echo3');
		} else {
			resetClasses();
		}

		// Update the output string for all elements
		updateOutputString();

		console.log('Current count:', counters[cls1id]);
		console.log('Current div:', currentDiv);
	}

	function updateOutputString() {
		outputString = Object.keys(counters)
			.map(key => `${key} : ${counters[key]}`)
			.join(", ");

		let outputInput = document.getElementById('sigleChoiseInput'); // Make sure to have an input with this ID
		if (outputInput) {
			outputInput.value = outputString;
		}
	}
</script>
