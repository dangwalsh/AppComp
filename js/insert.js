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