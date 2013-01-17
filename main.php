<?php
session_start();

if (isset($_SESSION['user'])) {
	$user = unserialize($_SESSION['user']);
} else {
	echo "You are not authorized!";
	exit();
}

echo <<<EOT

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Applied Computing</title>
	<meta name="description" content="" />
	<meta name="author" content="dwalsh" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0" />
	<link rel="stylesheet" type="text/css" href="css/main.css" />
	<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="js/error_handler.js"></script>
	<script type="text/javascript" src="js/window.js"></script>
	<script type="text/javascript" src="js/page.js"></script>
	<script type="text/javascript" src="js/content.js"></script>
</head>

	<body>
		<div>
			<header>			
				<table id="accountT">
					<tr>
						<td>User</td>
						<td>Login Time</td>
						<td>*Account</td>
						<td><a href="http://development/index.php">Logout</a></td>
					</tr>
				</table>
				<h1>Title</h1>
			</header>
			<nav>
				<table id="adminT">
					<tr>
						<td>Admin</td>
					</tr>
				</table>
				<table id="navigationT">
					<tr>
						<td id="posts">Posts</td>
						<td id="videos">Videos</td>
						<td id="courses">Courses</td>
					</tr>					
				</table>
			</nav>
			<div id="sidebar">
				<div id="search">
					<table id="searchT">
						<tr>
							<td>I1</td>
							<td>I2</td>
							<td>I3</td>
						</tr>
					</table>
				</div>
				<div id="contentT">
						<p class="cat">Cat1</p>
						<div>
							<p class="sub">Sub1</p>
							<div class="title">
								<p>Cont3A</p>
								<p>Cont3B</p>
								<p>Bot3C</p>
								<p>Bot3D</p>
							</div>
							<p class="sub">Sub2</p>
							<div class="title">
								<p>Bot3A</p>
								<p>Bot3B</p>
								<p>Bot3C</p>
								<p>Bot3D</p>
							</div>
						</div>
						<p class="cat">Cat2</p>
						<div>
							<p class="sub">Sub1</p>
							<div class="title">
								<p>Bot3C</p>
								<p>Bot3D</p>
							</div>
							<p class="sub">Sub2</p>
							<div class="title">
								<p>Bot3A</p>
								<p>Bot3B</p>
								<p>Bot3D</p>
							</div>
						</div>
						<p class="cat">Cat3</p>
						<div>
							<p class="sub">Sub1</p>
							<div class="title">
								<p>Bot3A</p>
								<p>Bot3B</p>
								<p>Bot3C</p>
								<p>Bot3D</p>
							</div>
						</div>			
				</div>
			</div>
			<div id="breadcrumbT">
				<table>
					<tr>
						<td>Level 1</td>
						<td>Level 2</td>
						<td>Level 3</td>
					</tr>					
				</table>				
			</div>
			<div id="main">
				This is the main content.
			</div>
			<footer>
				<table id="footerT">
					<tr>
						<td>Link 1</td>
						<td>Link 2</td>
						<td>Link 3</td>
					</tr>					
				</table>
			</footer>
		</div>
	</body>
</html>

EOT;
?>