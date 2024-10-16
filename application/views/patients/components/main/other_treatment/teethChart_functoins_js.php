<script>
	var counters = {};

	function colorize(div, cls1id, cls2id, cls3id = null, inputId, gLocatoinId) {
		if (!counters[cls1id]) counters[cls1id] = 0;

		let currentCount = counters[cls1id]; // Store the fucking current counter value in a variable
		let currentDiv = div; // Store the div element in a local variable

		let clsEle1 = document.getElementById(cls1id);
		let clsEle2 = document.getElementById(cls2id);
		let clsEle3 = document.getElementById(cls3id);

		let input = document.getElementById(inputId);
		let g_loc = document.getElementById(gLocatoinId);

		currentCount++; // Increment the local counter variable------remember this is important

		if (clsEle3 != null) {
			if (currentCount === 1) {
				console.log("this is 1");
				clsEle1.classList.toggle("echo1");
				clsEle2.classList.toggle("echo1");
				clsEle3.classList.toggle("echo1");
			} else if (currentCount === 2) {
				console.log("this is 2");
				clsEle1.classList.toggle("echo2");
				clsEle2.classList.toggle("echo2");
				clsEle3.classList.toggle("echo2");
			} else {
				console.log("it will reset");
				clsEle1.classList.remove("echo2");
				clsEle2.classList.remove("echo2");
				clsEle3.classList.remove("echo2");

				clsEle1.classList.remove("echo1");
				clsEle2.classList.remove("echo1");
				clsEle3.classList.remove("echo1");
				currentCount = 0;
			}
		}

		if (clsEle3 == null) {
			if (currentCount === 1) {
				console.log("this is 1");
				clsEle1.classList.toggle("echo1");
				clsEle2.classList.toggle("echo1");
			} else if (currentCount === 2) {
				console.log("this is 2");
				clsEle1.classList.toggle("echo2");
				clsEle2.classList.toggle("echo2");
			} else {
				console.log("it will reset");
				clsEle1.classList.remove("echo2");
				clsEle2.classList.remove("echo2");

				clsEle1.classList.remove("echo1");
				clsEle2.classList.remove("echo1");
				currentCount = 0;
			}
		}

		counters[cls1id] = currentCount; // Update from here

		console.log('Current div:', currentDiv.id); // you can use div.id to extract Id
		console.log('Current count:', counters[cls1id]);
		input.value = counters[cls1id];
		g_loc.value = currentDiv.id;
	}

</script>
