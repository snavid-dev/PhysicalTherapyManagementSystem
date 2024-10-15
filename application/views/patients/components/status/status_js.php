<script>
	// TODO the actions for patients single page
	function actions() {
		let actionValues = $("#selectaction").val();
		console.log(actionValues);
		if (actionValues == 1) {
			$(`#extralargemodal`).modal("toggle");
			$("#selectaction").val("").trigger("change");
		}
		if (actionValues == 2) {
			payment_modal_clicked();
			$(`#paymentModal`).modal("toggle");
			$("#selectaction").val("").trigger("change");
		}
		if (actionValues == 3) {
			request();
			$(`#laboratoryInsertModal`).modal("toggle");
			$("#selectaction").val("").trigger("change");
		}
		if (actionValues == 4) {
			$(`#insertPrescription`).modal("toggle");
			$("#selectaction").val("").trigger("change");
		}
		if (actionValues == 5) {
			$(`#filesModal`).modal("toggle");
			$("#selectaction").val("").trigger("change");
		}
	}

	// TODO: toogleView function please note that this function placed at the cdn/assets/js/teeth.js

	function toogleView() {
		let toggleSelect = $("#selectToggleView").val();
		let upperTeeth = document.getElementById("upperTeethAdult");
		let downTeeth = document.getElementById("downTeethAdult");
		let teethBackground = document.getElementById("teethBackground");
		let vl = document.querySelector("div.vl");
		let v2 = document.querySelector("div.v2");
		let vSpace1 = document.getElementById("vspace1");
		let vSpace2 = document.getElementById("vspace2");
		let vSpace3 = document.getElementById("vspace3");
		let vSpace4 = document.getElementById("vspace4");

		if (toggleSelect == "simple") {
			upperTeeth.classList.remove("upperTeethXray");
			downTeeth.classList.remove("downTeethXray");
			teethBackground.classList.remove("teethContainer" && "containerAdult");

			upperTeeth.classList.toggle("upperTeethSimple");
			downTeeth.classList.toggle("downTeethSimple");

			vl.style.display = "block";
			v2.style.display = "block";
			vSpace1.style.display = "block";
			vSpace2.style.display = "block";
			vSpace3.style.display = "block";
			vSpace4.style.display = "block";
		} else {
			upperTeeth.classList.remove("upperTeethSimple");
			downTeeth.classList.remove("downTeethSimple");

			upperTeeth.classList.toggle("upperTeethXray");
			downTeeth.classList.toggle("downTeethXray");
			teethBackground.classList.toggle("teethContainer" && "containerAdult");
			vl.style.display = "none";
			v2.style.display = "none";
			vSpace1.style.display = "none";
			vSpace2.style.display = "none";
			vSpace3.style.display = "none";
			vSpace4.style.display = "none";
		}
	}
</script>
