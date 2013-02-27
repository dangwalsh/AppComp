/**
 * @author Daniel Walsh
 */
// AJAX function that gets the project list of the staff member
function addEntry(t, n, s)
{
	// build the JSON data field
	var params = {
	  	mode: 'AddEntry',
	  	table: t,
		id: n,
		staff: s
	};
	// build the JSON AJAX statement
	$.ajax({
		url: 'php/insert.php',
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
				if (t == 'projectT') {
					getStaffProjectDetail(s);
				} else {
					getStaffCourseDetail(s);
					getCategoriesGraph(s);
				}
			}
		}
	});
}

// function to display the AJAX return content on the page
function buildForm(e, t)
{
	var htmlReference = "<div id='createForm'><h2 id='type'>" + t + "</h2>";
	htmlReference += "<table><tr><td>Category:</td><td>Subcategory:</td></tr><tr><td><select id='category'><option value='Revit'>Revit</option><option value='Rhino'>Rhino</option><option value='AutoCAD'>AutoCAD</option></select></td>";
	htmlReference += "<td><select id='subcategory'><option value='Tip'>Tip</option><option value='Reference'>Reference</option></select></td></tr></table>";
	htmlReference += "<p>Title:</p><input type='text' id='title'/>";
	htmlReference += "<p>Content:</p><textarea id='content'></textarea>";
	htmlReference += "<form enctype='multipart/form-data'><input name='file' type='file' style='margin: 10px 0 10px 0;'/>";
	htmlReference += "<div id='bar'><table><tr><td style='width: 80px;'><input type='button' value='Upload' id='btn' class='create' style='width: 60px;'/></td><td><progress></progress></td></tr></table></div>";
	htmlReference += "</form></div>";
	// insert the new HTML into the document
	$('#main')[0].innerHTML = htmlReference;
}

function submitContent(uid, type, cat, sub, title, cont)
{
	// build the JSON data field
	var params = {
		mode: 'SubmitContent',
		id: uid,
		type: type,
		category: cat,
		subcategory: sub,
		title: title,
		content: cont
	};
	// build the JSON AJAX statement
	$.ajax({
		url: 'php/insert.php',
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
				alert(data);
			}
		}
	});	
}