/**
 * @author dwalsh
 */

function getUser(name, pass)
{
	if(name != '' && pass != '') {
		
		var params = {
			mode: 'getUser',
			name: name,
			pass: pass
		};
		
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
					displayMessage(data);
				}
			}
		});
	}
}

function displayMessage(data, textStatus)
{
	var htmlReference = "";
	htmlReference += "<p>Welcome <strong>" + data.username + "</strong></p>";
	htmlReference += "<p>Your ID is: " + data.staff_id + "</p>";
	htmlReference += "<p>Your GROUP is: " + data.usergroup + "</p>";
		
	$('div')[0].innerHTML = htmlReference;	
	
	window.location = "main.php";
	
}
