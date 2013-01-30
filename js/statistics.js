/**
 * @author dwalsh
 */

// AJAX function that gets the staff list
function getStaffSummary(e)
{
	// get the id number of the content that was clicked on
	var number = e.target.id;
	// check for a valid id
	if(number != '') {
		// build the JSON data field
		var params = {
		  	mode: 'GetStaffSummary',
			id: number,
		};
		// build the JSON AJAX statement
		$.ajax({
			url: 'php/content.php',
			data: $.param(params),
			type: 'POST',
			dataType: 'json',
			error: function(xhr, textStatus, errorThrown) {
				displayError(textStatus);
			},
			success: function(data, textStatus) {
				if (data.errno != null) {
					displayPHPError(data);
				} else {
					displayStaffSummary(data);
				}
			}
		});		
	}
}

// function to display the AJAX return content on the page
function displayStaffSummary(data, textStatus)
{
	var i = 0;	
	var htmlReference = "<div id='module'>";
	htmlReference += "<table>";
	htmlReference += "<thead>";
	htmlReference += "<tr><th>Name</th><th>Design</th><th>Documentation</th><th>Coordination</th><th>Total</th></tr>";
	htmlReference += "</thead><tbody>";
	// loop through results	
	$.each(data.references, function(i, reference) {
		// apply row banding
		if (i % 2 == 0) {
			var even = " class='even'";
		} else {
			var even = "";
		}
		// replace null values with tick marks
		if (reference.des_tot == null) reference.des_tot = "-";
		if (reference.doc_tot == null) reference.doc_tot = "-";
		if (reference.coo_tot == null) reference.coo_tot = "-";
		// compose HTML code that displays the content
		htmlReference += "<tr" + even + "><td class='left'><a href='*'>" + reference.last_name + ", " + reference.first_name + "</a></td><td>" + reference.des_tot + "</td><td>" + reference.doc_tot + "</td><td>" + reference.coo_tot + "</td><td>" + reference.total + "</td></tr>";				
		// increment counter
		++i;
	});
	htmlReference += "</tbody>";
	htmlReference += "</table>";
	htmlReference += "</div>";
	// insert the new HTML into the document
	$('#main')[0].innerHTML = htmlReference;
}

// AJAX function that gets the course list
function getCourseSummary(e)
{
	// get the id number of the content that was clicked on
	var number = e.target.id;
	// check for a valid id
	if(number != '') {
		// build the JSON data field
		var params = {
		  	mode: 'GetCourseSummary',
			id: number
		};
		// build the JSON AJAX statement
		$.ajax({
			url: 'php/content.php',
			data: $.param(params),
			type: 'POST',
			dataType: 'json',
			error: function(xhr, textStatus, errorThrown) {
				displayError(textStatus);
			},
			success: function(data, textStatus) {
				if (data.errno != null) {
					displayPHPError(data);
				} else {
					displayCourseSummary(data);
				}
			}
		});		
	}
}

// function to display the AJAX return content on the page
function displayCourseSummary(data, textStatus)
{
	var i = 0;	
	var htmlReference = "<div id='module'>";
	htmlReference += "<table>";
	htmlReference += "<thead>";
	htmlReference += "<tr><th>Number</th><th>Name</th><th>Attendees</th></tr>";
	htmlReference += "</thead><tbody>";
	// loop through results	
	$.each(data.references, function(i, reference) {
		// apply row banding
		if (i % 2 == 0) {
			var even = " class='even'";
		} else {
			var even = "";
		}
		// replace null values with tick marks
		if (reference.total == null) reference.total = "-";
		// compose HTML code that displays the content
		htmlReference += "<tr" + even + "><td class='left'><a href='*'>" + reference.id + "</a></td><td class='left'>" + reference.title + "</td><td>" + reference.total + "</td></tr>";			
		// increment counter
		++i;
	});
	htmlReference += "</tbody>";
	htmlReference += "</table>";
	htmlReference += "</div>";
	// insert the new HTML into the document
	$('#main')[0].innerHTML = htmlReference;
}