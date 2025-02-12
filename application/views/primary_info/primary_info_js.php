<?php
$ci = get_instance();
?>


<!--dynamic row functions-->
<script>
	document.addEventListener("DOMContentLoaded", function () {
		const tableBody = document.querySelector("#percentageTable tbody");
		const addRowButton = document.getElementById("addRowButton");
		let rowCount = 0;
		let draggedRow = null;


		addRowButton.addEventListener("click", function () {
			rowCount++;


			const row = document.createElement("tr");
			row.setAttribute("draggable", "true");


			row.innerHTML = `
      <td>${rowCount}</td>
      <td><input type="text" class="form-control" name="name[]" placeholder="Enter name"></td>
      <td><input type="number" class="form-control" name="percentage[]" placeholder="Enter percentage"></td>
      <td><button class="btn btn-danger deleteRow">Delete</button></td>
    `;

			// drag and drop functionality
			addDragAndDropEvents(row);

			// Append the row to the table body
			tableBody.appendChild(row);

			// Add delete functionality to the delete button
			const deleteButton = row.querySelector(".deleteRow");
			deleteButton.addEventListener("click", function () {
				row.remove();
				updateRowNumbers();
			});
		});

		// this part is for updating the row orders numbers actually
		function updateRowNumbers() {
			rowCount = 0;
			Array.from(tableBody.children).forEach((row, index) => {
				rowCount = index + 1;
				row.querySelector("td:first-child").textContent = rowCount;
			});
		}


		// fucking drag and drop functions
		// Function to add drag-and-drop event listeners to a row
		function addDragAndDropEvents(row) {
			row.addEventListener("dragstart", function (e) {
				draggedRow = row;
				setTimeout(() => (row.style.display = "none"), 0); // rember this part: while drag and drop the selected row should be hidden
			});

			row.addEventListener("dragend", function (e) {
				setTimeout(() => (row.style.display = "table-row"), 0); // Here Show row again
				draggedRow = null;
			});

			row.addEventListener("dragover", function (e) {
				e.preventDefault(); // Allow dropping
				const draggedOverRow = e.target.closest("tr");
				if (draggedOverRow && draggedOverRow !== draggedRow) {
					const bounding = draggedOverRow.getBoundingClientRect();
					const offset = e.clientY - bounding.top;
					if (offset > bounding.height / 2) {
						draggedOverRow.after(draggedRow);
					} else {
						draggedOverRow.before(draggedRow);
					}
				}
			});

			row.addEventListener("drop", function (e) {
				e.preventDefault();
				updateRowNumbers(); // Update row numbers after reordering
			});
		}

		// Function to erase all rows
		function eraseAllRows() {
			tableBody.innerHTML = "";
			rowCount = 0;
		}
		window.eraseAllRows = eraseAllRows;

	});

	// Function to get data
	// function getAllDataAndSum() {
	// 	const tableBody = document.querySelector("#percentageTable tbody");
	// 	const rows = tableBody.querySelectorAll("tr");
	//
	// 	let data = [];
	// 	let sum = 0;
	//
	// 	rows.forEach((row, index) => {
	// 		const rowNumber = index + 1; // Row number (starting from 1)
	// 		const nameInput = row.querySelector('input[name="name[]"]');
	// 		const percentageInput = row.querySelector('input[name="percentage[]"]');
	//
	// 		const name = nameInput ? nameInput.value.trim() : "";
	// 		const percentage = percentageInput ? parseFloat(percentageInput.value) : 0;
	//
	// 		if (name) {
	// 			data.push([rowNumber, name, percentage]);
	// 		}
	//
	// 		if (!isNaN(percentage)) {
	// 			sum += percentage;
	// 		}
	// 	});
	//
	// 	console.log("Data:", data);
	// 	console.log("Sum of Percentages:", sum);
	//
	// 	return { data, sum };
	// }


	function getAllDataAndSum() {
		const tableBody = document.querySelector("#percentageTable tbody");
		const rows = tableBody.querySelectorAll("tr");

		let data = [];
		let sum = 0;

		rows.forEach((row, index) => {
			const rowNumber = index + 1; // Row number (starting from 1)
			const nameInput = row.querySelector('input[name="name[]"]');
			const percentageInput = row.querySelector('input[name="percentage[]"]');

			const name = nameInput ? nameInput.value.trim() : "";
			const percentage = percentageInput ? parseFloat(percentageInput.value) : 0;

			if (name) {
				data.push({ row: rowNumber, name: name, percentage: percentage });
			}

			if (!isNaN(percentage)) {
				sum += percentage;
			}
		});

		console.log("Data:", data);
		console.log("Sum of Percentages:", sum);

		return { data, sum };
	}


</script>
