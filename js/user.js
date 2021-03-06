/**
 * @author dwalsh
 */

function getUser(name, pass)
{
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
				displayWelcome(data);
			}
		}
	});

}

function displayWelcome(data, textStatus)
{
	var htmlReference = "";

	if(data.error){
		htmlReference += "<p class='alert'>" + data.error + "</p>";
		if(data.error == "Authentication Failed") {
			$('#name').val("");
			$('#pass').val("");
		}	
		$('#response')[0].innerHTML = htmlReference;
	} else {
		window.location = "main.php";
	}
		
}

