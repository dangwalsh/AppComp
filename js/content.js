/**
 * @author dwalsh
 */
// AJAX function that gets the content corresponding to the user click
function getContent(e, uid)
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
					displayContent(data, textStatus, uid);
				}
			}
		});		
	}
}

// function to display the AJAX return content on the page
function displayContent(data, textStatus, uid)
{
	// loop through results	
	$.each(data.references, function(i, reference) {
		// compose HTML code that displays the content
		var htmlReference = "<div>";
		htmlReference += "<h1>" + reference.title + "</h1>";
		htmlReference += "<div id='tabs'></div>";	
		
		htmlReference += "<h5>submitted: " + reference.date_created + "</h5>";
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
		// add the edit tab for authorized users
		queryGroup(uid, reference.id);
	});
}

function queryGroup(uid, id)
{
	// build the JSON data field
	var params = {
		mode: 'QueryGroup',
		id: uid
	};
	// build the JSON AJAX statement
	$.ajax({
		url: 'php/user.php',
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
				displayTabs(data, id);
			}
		},
	});
}

function displayTabs(data, id)
{
	if (data == 'admin' || data == 'author') {
		$('#main #tabs')[0].innerHTML = "<ul class='primary'><li class='active' id='" + id + "'>View</li><li class='inactive' id='" + id + "'>Edit</li></ul>";
	}
}

function editContent(e)
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
					displayEditContent(data);
				}
			}
		});		
	}	
}

// function to display the AJAX return content on the page
function displayEditContent(data, textStatus)
{
	// loop through results
	$.each(data.references, function(i, reference) {
		var htmlReference = "<div id='createForm'>";
		htmlReference += "<input type='text' id='title' value='" + reference.title + "'/>";
		htmlReference += "<div id='tabs'><ul class='primary'><li class='inactive' id='" + reference.id + "'>View</li><li class='active' id='" + reference.id + "'>Edit</li></ul></div>";
		htmlReference += "<p><h5 id='type'><span>" + pageTable + "</span> >> <select id='category'><option value='Revit'>Revit</option><option value='Rhino'>Rhino</option><option value='AutoCAD'>AutoCAD</option></select> >> ";
		htmlReference += "<select id='subcategory'><option value='Tip'>Tip</option><option value='Reference'>Reference</option></select></h5></p>";
		htmlReference += "<textarea id='content'>" + reference.content + "</textarea>";
		htmlReference += "<form enctype='multipart/form-data'><input name='file' type='file' style='margin: 10px 0 10px 0;'/><input type='hidden' id='contentId' value='" + reference.id + "'/>";
		htmlReference += "<div id='bar'><table><tr><td style='width: 80px;'><input type='button' value='Update' id='submit' class='update' style='width: 60px;'/></td><td><progress></progress></td></tr></table></div>";
		htmlReference += "</form></div>";
		// insert the new HTML into the document
		$('#main')[0].innerHTML = htmlReference;
	});
}


