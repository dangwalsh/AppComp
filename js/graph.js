/**
 * @author dwalsh
 */
// aggregate and sort functions to build data tables
function getCategoriesGraph(n, g)
{	
	// build the JSON data field
	var params = {
	  	mode: 'GetCategoriesGraph',
		id: n,
		graph: g
	};
	// build the JSON AJAX statement
	$.ajax({
		url: 'php/graph.php',
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
				displayCategoriesGraph(data);
				selectOption(g);
			}
		}
	});		
}

// function to display the AJAX return content on the page
function displayCategoriesGraph(data, textStatus)
{
	var htmlReference = data;
	// insert the new HTML into the document
	$('#main #progress')[0].innerHTML = htmlReference;
}	

function selectOption(g)
{
	$('#progress p select option').each(function() { this.selected = (this.value == g); });
}
