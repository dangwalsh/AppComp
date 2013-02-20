/**
 * @author dwalsh
 */

// aggregate and sort functions to build data tables
function buildDashboardPage(e)
{
	//clear content from the main window
	$('#main')[0].innerHTML = "";
	// get the id number of the content that was clicked on
	var number = e.target.id;	
	// check for a valid id and which type content to display
	if(number != '') {
		// this needs to change to current activity
		getStaffSummary(number);	
		// this needs to change to current activity
		getCourseSummary(number);					
	}
}

// aggregate and sort functions to build data tables
function buildSummaryPage(e)
{
	//clear content from the main window
	$('#main')[0].innerHTML = "";
	// get the id number of the content that was clicked on
	var number = e.target.id;
	//var table = t.prop('id');	
	
	// check for a valid id and which type content to display
	if(number == 'staff') {
		// breakdown of course-types the staff member has attended
		getStaffSummary(number);	
							
	} else if (number == 'courses') {
		// list of courses and attendance
		getCourseSummary(number);				
	}
}

// aggregate and sort functions to build data tables
function buildDetailPage(e, t)
{
	//clear content from the main window
	$('#main')[0].innerHTML = "";
	// get the id number of the content that was clicked on
	var number = e.target.id;
	var table = t.prop('id');	
	// check for a valid id and which type content to display
	if(number != '' && table == 'staffT') {
		// staff data detailed
		getStaffDetail(number);
		// chart the staff members progress
		getCategoriesGraph(number);
		// list of courses the staff member has attended
		getStaffCourseDetail(number);	
		// list of projects the staff member has worked on
		getStaffProjectDetail(number);	
					
	} else if (number != '' && table == 'courseT') {
		// course data detailed
		getCourseDetail(number);	
		// list of staff members that have attended course
		getCourseStaffDetail(number);				
	}
}

// AJAX function that gets the staff list
function getStaffSummary(number)
{
	// build the JSON data field
	var params = {
	  	mode: 'GetStaffSummary',
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
				displayStaffSummary(data);
			}
		}
	});		
}

// AJAX function that gets the course list
function getCourseSummary(number)
{
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

// AJAX function that gets the course list of the staff member
function getStaffCourseDetail(number)
{
	// build the JSON data field
	var params = {
	  	mode: 'GetStaffCourseDetail',
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
				displayStaffCourseDetail(data);
			}
		}
	});	
}

// AJAX function that gets the project list of the staff member
function getStaffProjectDetail(number)
{
	// build the JSON data field
	var params = {
	  	mode: 'GetStaffProjectDetail',
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
				displayStaffProjectDetail(data);
			}
		}
	});	
}

// AJAX function that gets the course information
function getStaffDetail(number)
{
	// build the JSON data field
	var params = {
	  	mode: 'GetStaffDetail',
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
				displayStaffDetail(data);
			}
		}
	});		
}


// AJAX function that gets the course information
function getCourseDetail(number)
{
	// build the JSON data field
	var params = {
	  	mode: 'GetCourseDetail',
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
				displayCourseDetail(data);
			}
		}
	});		
}

// AJAX function that gets the course attendance list
function getCourseStaffDetail(number)
{
	// build the JSON data field
	var params = {
	  	mode: 'GetCourseStaffDetail',
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
				displayCourseStaffDetail(data);
			}
		}
	});		
}

// function to display the AJAX return content on the page
function displayStaffSummary(data, textStatus)
{
	var i = 0;	
	var htmlReference = "<div id='module'>";
	htmlReference += "<h2>Staff Summary</h2>";
	htmlReference += "<table id='staffT'>";
	htmlReference += "<thead>";
	htmlReference += "<tr><th>Name</th><th>Fundamentals</th><th>Design</th><th>Documentation</th><th>Coordination</th><th>BIM Coord</th><th>Navisworks</th><th>Elective</th><th>Total</th></tr>";
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
		if (reference.fun_tot == null) reference.fun_tot = "-";
		if (reference.des_tot == null) reference.des_tot = "-";
		if (reference.doc_tot == null) reference.doc_tot = "-";
		if (reference.coo_tot == null) reference.coo_tot = "-";
		if (reference.bim_tot == null) reference.bim_tot = "-";
		if (reference.nav_tot == null) reference.nav_tot = "-";
		if (reference.ele_tot == null) reference.ele_tot = "-";
		if (reference.total == null) reference.total = "-";
		// compose HTML code that displays the content
		htmlReference += "<tr" + even + "><td><a id='" + reference.staff_id + "'>" + reference.last_name + ", " + reference.first_name + "</a></td><td class='center'>" + reference.fun_tot + "</td><td class='center'>" + reference.des_tot + "</td><td class='center'>" + reference.doc_tot + "</td><td class='center'>" + reference.coo_tot + "</td><td class='center'>" + reference.bim_tot + "</td><td class='center'>" + reference.nav_tot + "</td><td class='center'>" + reference.ele_tot + "</td><td class='center'>" + reference.total + "</td></tr>";				
		// increment counter
		++i;
	});
	htmlReference += "</tbody>";
	htmlReference += "</table>";
	htmlReference += "</div>";
	// insert the new HTML into the document
	$('#main')[0].innerHTML += htmlReference;
}

// function to display the AJAX return content on the page
function displayCourseSummary(data, textStatus)
{
	var i = 0;	
	var htmlReference = "<div id='module'>";
	htmlReference += "<h2>Course Summary</h2>";
	htmlReference += "<table id='courseT'>";
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
		htmlReference += "<tr" + even + "><td><a id='" + reference.id + "'>" + reference.id + "</a></td><td>" + reference.title + "</td><td>" + reference.total + "</td></tr>";			
		// increment counter
		++i;
	});
	htmlReference += "</tbody>";
	htmlReference += "</table>";
	htmlReference += "</div>";
	// insert the new HTML into the document
	$('#main')[0].innerHTML += htmlReference;
}

// function to display the AJAX return content on the page
function displayStaffCourseDetail(data, textStatus)
{
	var i = 0;	
	var htmlReference = "<div>";
	htmlReference += "<div id='bar'>";
	htmlReference += "<h2>Courses</h2>";
	htmlReference += "<table id='courseT'>";
	htmlReference += "<thead>";
	htmlReference += "<tr><th>Number</th><th>Name</th><th>Date</th></tr>";
	htmlReference += "</thead><tbody>";
	// loop through results	
	$.each(data.references, function(i, reference) {
		// apply row banding
		if (i % 2 == 0) {
			var even = " class='even'";
		} else {
			var even = "";
		}
		// compose HTML code that displays the content
		htmlReference += "<tr" + even + "><td><a id='" + reference.id + "'>" + reference.id + "</a></td><td>" + reference.title + "</td><td>" + reference.date + "</td></tr>";			
		// increment counter
		++i;
	});
	htmlReference += "</tbody>";
	htmlReference += "</table>";
	htmlReference += "</div>";
	htmlReference += "</div>";
	// insert the new HTML into the document
	$('#main')[0].innerHTML += htmlReference;
}

// function to display the AJAX return content on the page
function displayStaffProjectDetail(data, textStatus)
{
	var i = 0;	
	var htmlReference = "<div>";
	htmlReference += "<div id='bar'>";
	htmlReference += "<h2>Projects</h2>";
	htmlReference += "<table id='courseT'>";
	htmlReference += "<thead>";
	htmlReference += "<tr><th>Number</th><th>Name</th><th>Date</th></tr>";
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
		htmlReference += "<tr" + even + "><td><a id='" + reference.id + "'>" + reference.id + "</a></td><td>" + reference.title + "</td><td>" + reference.date + "</td></tr>";			
		// increment counter
		++i;
	});
	htmlReference += "</tbody>";
	htmlReference += "</table>";
	htmlReference += "</div>";
	htmlReference += "</div>";
	// insert the new HTML into the document
	$('#main')[0].innerHTML += htmlReference;
}

// function to display the AJAX return content on the page
function displayStaffDetail(data, textStatus)
{
	var i = 0;
	var htmlReference = "<div>";
	$.each(data.references, function(i, reference) {
		htmlReference += "<h1>" + reference.first_name + " " + reference.last_name + "</h1>";
		htmlReference += "<h3>" + reference.role + "</h3>";			
	});
	htmlReference += "</div>";	
	// insert the new HTML into the document
	$('#main')[0].innerHTML += htmlReference;
}

// function to display the AJAX return content on the page
function displayCourseDetail(data, textStatus)
{
	var i = 0;
	var htmlReference = "<div>";
	$.each(data.references, function(i, reference) {
		htmlReference += "<h1>" + reference.title + "</h1>";
		htmlReference += "<h3>" + reference.id + "</h3>";		
		htmlReference += "<p class='dateline'>" + reference.date + "</p>";
		htmlReference += "<p>" + reference.content + "</p>";	
	});
	htmlReference += "</div>";	
	// insert the new HTML into the document
	$('#main')[0].innerHTML += htmlReference;
}

// function to display the AJAX return content on the page
function displayCourseStaffDetail(data, textStatus)
{
	var i = 0;
	var htmlReference = "<div>";
	htmlReference += "<div id='bar'>";
	htmlReference += "<h2>Attendees</h2>";
	htmlReference += "<table id='staffT' style='width: 100%;'>";
	htmlReference += "<thead>";
	htmlReference += "<tr><th>Name</th><th>Date</th></tr>";
	htmlReference += "</thead><tbody>";
	// loop through results	
	$.each(data.references, function(i, reference) {
		// apply row banding
		if (i % 2 == 0) {
			var even = " class='even'";
		} else {
			var even = "";
		}
		// compose HTML code that displays the content
		htmlReference += "<tr" + even + "><td><a id='" + reference.id + "'>" + reference.last_name + ", " + reference.first_name + "</a></td><td>" + reference.date + "</td></tr>";			
		// increment counter
		++i;
	});
	htmlReference += "</tbody>";
	htmlReference += "</table>";
	htmlReference += "</div>";
	htmlReference += "</div>";
	// insert the new HTML into the document
	$('#main')[0].innerHTML += htmlReference;
}

