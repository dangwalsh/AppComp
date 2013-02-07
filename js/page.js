/**
 * @author dwalsh
 */

var pageTable;
// AJAX function that gets the page corresponding to the user click
function getPageTable(e)
{
	// get the id number of the content that was clicked on
	pageTable = e.target.id;
	// change the color of the navigation bar
	setNavColor();
	// check for a valid id
	if(pageTable != '') {
		// build the JSON data field
		var params = {
			mode: 'getPageTable',
			table: pageTable // this needs to come from the page click in the nav bar!!!!
		};
		// build the JSON AJAX statement
		$.ajax({
			url: 'php/page.php',
			data: $.param(params),
			type: 'post',
			dataType: 'json',
			error: function(xhr, textStatus, errorThrown) {
				displayError(textStatus);
			},
			success: function(data, textStatus) {
				if (data.errno != null) {
					displayPHPError(data);
				} else {
					displayPageTable(data);
				}
			}
		});		
	}
}

// function to display the AJAX return content on the page
function displayPageTable(data, textStatus)
{
	var htmlReference = "";
	var i = 0;
	var cat;
	var sub;
	
	$.each(data.references, function(i, reference) {
		// compose HTML code that displays the content
		if(reference.category != cat){

			if(i != 0){
				htmlReference += "</ul></div></div>";
				i = 0;
			}

			htmlReference += "<p class='cat'>" + reference.category + "</p>";
			htmlReference += "<div>";
			cat = reference.category;
		}

		if(reference.subcategory != sub) {
			
			if(i != 0){
				htmlReference += "</ul></div>";
			}

			htmlReference += "<p class='sub'><img src='images/disclosure.png'>" + reference.subcategory + "</p>";
			htmlReference += "<div class='title'>";	
			htmlReference += "<ul>";			
			sub = reference.subcategory;
		}

		htmlReference += "<li id='" + reference.id + "'>" + reference.title + "</li>";	
		++i;
	});

	// insert the new HTML into the document
	$('#contentT')[0].innerHTML = htmlReference;
	// add display control to new table
	controlSidebar();
}

// AJAX function that gets the content corresponding to the user search
function searchPageTable(word)
{
	// check for a valid table name and search word
	if(pageTable != '' && word != '') {
		// build the JSON data field
		var params = {
			mode: 'searchPageTable',
			table: pageTable, // this needs to come from the page click in the nav bar!!!!
			searchword: word
		};
		// build the JSON AJAX statement
		$.ajax({
			url: 'php/page.php',
			data: $.param(params),
			type: 'post',
			dataType: 'json',
			error: function(xhr, textStatus, errorThrown) {
				displayError(textStatus);
			},
			success: function(data, textStatus) {
				if (data.errno != null) {
					displayPHPError(data);
				} else {
					displayPageTable(data);
				}
			}
		});		
	}
}

function setNavColor()
{	
	var nbar = $('nav');
	var ntable = $('#navigationT');
	var ntd = $('#navigationT td');
	var atable = $('#adminT');
	var atd = $('#adminT td');
	
	nbar.removeClass();
	ntable.removeClass();
	ntd.removeClass();
	atable.removeClass();
	atd.removeClass();
	
	if(pageTable == 'posts') {
		nbar.addClass('articles');
		ntable.addClass('articles_table');
		ntd.addClass('articles_td');
		atable.addClass('articles_table');
		atd.addClass('articles_td');
		$('#posts').addClass('articles_sel');
	} else if (pageTable == 'videos') {
		nbar.addClass('videos');
		ntable.addClass('videos_table');
		ntd.addClass('videos_td');
		atable.addClass('videos_table');
		atd.addClass('videos_td');
		$('#videos').addClass('videos_sel');
	} else if (pageTable == 'courses') {
		nbar.addClass('courses');
		ntable.addClass('courses_table');
		ntd.addClass('courses_td');
		atable.addClass('courses_table');
		atd.addClass('courses_td');
		$('#courses').addClass('courses_sel');
	} else if (pageTable == 'admin') {
		nbar.addClass('nopage');
		ntable.addClass('nopage_table');
		ntd.addClass('nopage_td');
		atable.addClass('nopage_table');
		atd.addClass('nopage_td');
		$('#admin').addClass('admin_sel');
	} else {
		nbar.addClass('nopage');
		ntable.addClass('nopage_table');
		ntd.addClass('nopage_td');
		atable.addClass('nopage_table');
		atd.addClass('nopage_td');
	}
}
