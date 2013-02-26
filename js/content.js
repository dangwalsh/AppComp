/**
 * @author dwalsh
 */
// AJAX function that gets the content corresponding to the user click
function getContent(e)
{
	// get the id number of the content that was clicked on
	var number = e.target.id;
	// check for a valid id
	if(number != '') {
		// build the JSON data field
		var params = {
			mode: 'GetContent',
			id: number,
			table: pageTable // this needs to come from the page click in the nav bar!!!!
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
					displayContent(data);
				}
			}
		});		
	}
}

// function to display the AJAX return content on the page
function displayContent(data, textStatus)
{
	// loop through results	
	$.each(data.references, function(i, reference) {
		// compose HTML code that displays the content
		var htmlReference = "<div>";
		htmlReference += "<h1>" + reference.title + "</h1>";
		htmlReference += "<h3>" + reference.date_created + "</h3>";
		htmlReference += "<p>" + reference.content + "</p>";
		if(reference.file_path) {
			if(pageTable == 'videos') {
				// compose HTML code that displays a video player
				htmlReference += "<video width='640' height='480' preload controls>";
				htmlReference += "<source src=\'" + reference.file_path + ".mp4\' type=\'video/mp4; codecs=\"avc1.42E01E, mp4a.40.2\"\' />";
				htmlReference += "<source src=\'" + reference.file_path + ".ogv\' type=\'video/ogg; codecs=\"theora, vorbis\"\' />";
				htmlReference += "Your browser does not support HTML5 video.";
				htmlReference += "</video>";
			} else if (pageTable == 'courses') {
				
			} else if (pageTable == 'posts') {
				
			}
		}
		htmlReference += "</div>";
		// insert the new HTML into the document
		$('#main')[0].innerHTML = htmlReference;
	});
}


