/**
 * @author dwalsh
 */

var pageTable;
// AJAX function that gets the page corresponding to the user click
function getPageTable(e)
{
	// get the id number of the content that was clicked on
	pageTable = e.target.id;
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
				htmlReference += "</div></div>";
				i = 0;
			}

			htmlReference += "<p class='cat'>" + reference.category + "</p>";
			htmlReference += "<div>";
			cat = reference.category;
		}

		if(reference.subcategory != sub) {
			
			if(i != 0){
				htmlReference += "</div>";
			}

			htmlReference += "<p class='sub'>" + reference.subcategory + "</p>";
			htmlReference += "<div class='title'>";				
			sub = reference.subcategory;
		}

		htmlReference += "<p id='" + reference.id + "'>" + reference.title + "</p>";		
		++i;
	});
	//htmlReference += "</tfoot>";
	// insert the new HTML into the document
	$('#contentT')[0].innerHTML = htmlReference;
	// add display control to new table
	controlSidebar();
}
