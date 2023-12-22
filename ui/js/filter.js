
// Used on every list.htm file to filter "dvdata" table object
function searchTable() {
	var input, filter, table, tr, td, i, txtValue;
	// Columns to inspect
	var columns = [1,2,3,4,5,6,7,8,9,10,11,12];
	filter = document.getElementById("search").value.toUpperCase();
	table = document.getElementById("list");
	tr = table.getElementsByTagName("tr");
	td = [];
	for (i = 1; i < tr.length; i++) {
		for (var j of columns) {
		td[j] = tr[i].getElementsByTagName("td")[j];
		if (td[j].innerText != '') {
			if (td[j].innerText.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
				break;
			} else {
				tr[i].style.display = "none";
			}
		}
		}
	}
}

// JavaScript program to illustrate
// Table sort for both columns and
// both directions
function sortTable(n) {
	let table;
	table = document.getElementById("list");
	var rows, i, x, y, count = 0;
	var switching = true;
	//console.log(n);
	// Order is set as ascending
	var direction = "ascending";

	// Run loop until no switching is needed
	while (switching) {
		switching = false;
		var rows = table.rows;

		//Loop to go through all rows
		for (i = 1; i < (rows.length - 1); i++) {
			var Switch = false;

			// Fetch 2 elements that need to be compared
			x = rows[i].getElementsByTagName("td")[n];
			y = rows[i + 1].getElementsByTagName("td")[n];

			// Check the direction of order
			if (direction == "ascending") {

				// Check if 2 rows need to be switched
				if (x.innerHTML.toLowerCase() >
				y.innerHTML.toLowerCase()) {
						
					// If yes, mark Switch as needed
					// and break loop
					Switch = true;
					break;
				}
			} else if (direction == "descending") {
				// Check direction
				if (x.innerHTML.toLowerCase() <
					y.innerHTML.toLowerCase()) {
					// If yes, mark Switch as needed
					// and break loop
					Switch = true;
					break;
				}
			}
		}
		if (Switch) { 
			// Function to switch rows and mark
			// switch as completed
			rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
			switching = true;
			// Increase count for each switch
			count++;
		} else {
			// Run while loop again for descending order
			if (count == 0 && direction == "ascending") {
				direction = "descending";
				switching = true;
			}
		}
	}
}
