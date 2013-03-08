/**
 * @author dwalsh
 */
$(document).ready( function() {	
	if ($('#log')) {
		// erase errors when user starts typing
		$('#name').click(function() {
			$('#response')[0].innerHTML = "";
			$('#name').removeClass('missing');
		});
		
		$('#pass').click(function() {
			$('#response')[0].innerHTML = "";
			$('#pass').removeClass('missing');
		});
		
		// allow user to log back in
		$('#reset').click(function() {
			window.location = "index.php";
		});
		
		// verify username and password and create 
		//user object or display error
		$('#submit').click(function() {
			
			$('#name').removeClass('missing');
			$('#pass').removeClass('missing');
			var name = $('#name').val();
			var pass = $('#pass').val();
			
			if (name == "") {
				$('#name').addClass('missing');
			}
			if (pass == "") {
				$('#pass').addClass('missing');
			}
			
			getUser(name, pass);
		});
	} 
});