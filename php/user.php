<?php
// reference the file containing the user class
require_once('user.class.php');
// reference the file containing the user class
require_once('headers.php');
// retrieve the operation to be performed
$mode = $_POST['mode'];

$user = new User();

if ($mode == 'getUser') {
	
	$name = $_POST['name'];
	$pass = $_POST['pass'];

	if ($user->auth($name, $pass)) {
		session_start();
		$user->login(session_id());
		$_SESSION['user'] = serialize($user);
	}	
	sendHeaders();		
	echo json_encode($user->stats());			
} else if ($mode == 'QueryGroup') {
	$staff_id = $_POST['id'];
	sendHeaders();
	echo json_encode($user->queryGroup($staff_id));
}

?>