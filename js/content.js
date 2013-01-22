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
		var htmlReference = "";
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
		// insert the new HTML into the document
		$('#main')[0].innerHTML = htmlReference;
	});
}

// control the display of the content browser
function controlSidebar()
{
	$('#contentT div').hide();
	//$('#contentT div').removeClass('.expanded');
	$('#contentT p.cat').click(function() {
		$(this).next().toggle();
		$('#contentT div p.sub').next().hide();
		//$('#contentT div p.sub').next().removeClass('.expanded');
	});
	
	$('#contentT div div').hide();
	$('#contentT div p.sub').click(function() {
		$(this).next().toggle();
		$(this).find('>:first-child').toggleClass('rotate');//.attr('src','images/disclosure_down.png');
		//$(this).addClass('expanded');
	});
	
	$('#contentT div.title li').click(function(e) {
		getContent(e);
	});
}



