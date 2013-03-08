<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Login</title>
	<meta name="description" content="" />
	<meta name="author" content="dwalsh" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0" />
	<link rel="stylesheet" type="text/css" href="css/main.css" />
	<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="js/error_handler.js"></script>
	<script type="text/javascript" src="js/login.js"></script>
	<script type="text/javascript" src="js/user.js"></script>
</head>

<?php
require_once ('php/user.class.php');

session_start();

date_default_timezone_set('America/New_York');

if (isset($_SESSION['user'])) {
	// if logged in, log out
	$user = unserialize($_SESSION['user']);
	if($user->rec_logout(session_id())) {
		unset($user);
	}
	// display message in window
	echo <<<EOT
	<body>
		<div id="log" class="out">
			<h1>Logout</h1>
			<table>
				<tr><td>Your session has ended<td><tr>
			</table>
			<button id="reset">Login</button>
		</div>
	</body>
</html>
EOT;

} else {
	
	// if not logged in, log in
	echo <<<EOT
	<body>
		<div id="log" class="in">
			<h1>Login</h1>
			<table>
				<tr><td><input id="name" type="text" /><td><label>Username</label></td></td></tr>
				<tr><td><input id="pass" type="password" /><td><label>Password</label></td></td></tr>
			</table>
			<button id="submit">Submit</button>
			<span id="response"></span>
		</div>
	</body>
</html>
EOT;
}
?>