/**
 * @author dwalsh
 */
// aggregate and sort functions to build data tables
function getCategoriesGraph(number)
{	
	// build the JSON data field
	var params = {
	  	mode: 'GetCategoriesGraph',
		id: number
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
			}
		}
	});		
}

// function to display the AJAX return content on the page
function displayCategoriesGraph(data, textStatus)
{
	var i = 0;
	//alert(data);
	var htmlReference = data;
	// insert the new HTML into the document
	$('#main')[0].innerHTML += htmlReference;
}	