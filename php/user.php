<?php
// reference the file containing the user class
require_once('user.class.php');
// retrieve the operation to be performed
$mode = $_POST['mode'];

$user = new User();

if ($mode == 'getUser') {
	
	$name = $_POST['name'];
	$pass = $_POST['pass'];

	 if ($user->authorize($name, $pass)) {
	 	session_start();
		$user->rec_login(session_id());
		$_SESSION['user'] = serialize($user);
		
		if(ob_get_length()) ob_clean();
		// send headers so that browser doesn't cache content
		header('Expires: Mon, 26 July 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-Type: application/json');
		
		echo json_encode($user->stats());			
	 }
	 
}

?>