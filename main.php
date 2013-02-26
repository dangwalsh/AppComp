<?php
require_once ('php/user.class.php');

session_start();

date_default_timezone_set('America/New_York');

if (isset($_SESSION['user'])) {
	$user = unserialize($_SESSION['user']);
	$user->getUsername();
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
	<link rel="stylesheet" type="text/css" href="css/main.css" />
	<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="js/error_handler.js"></script>
	<script type="text/javascript" src="js/window.js"></script>
	<script type="text/javascript" src="js/page.js"></script>
	<script type="text/javascript" src="js/content.js"></script>
	<script type="text/javascript" src="js/statistics.js"></script>
	<script type="text/javascript" src="js/graph.js"></script>
	<script type="text/javascript" src="js/insert.js"></script>
	<script type="text/javascript" src="js/upload.js"></script>>

	<body onload="MM_preloadImages('images/appcomp.png','images/search.png','images/disclosure.png','images/disclosure_down.png','svg/grad_articles.svg','svg/grad_articles_sel.svg','svg/grad_courses.svg','svg/grad_courses_sel.svg','svg/grad_videos.svg','svg/grad_videos_sel.svg','svg/grad_nopage.svg')">
		<div>
			<header>			
				<table id="accountT">
					<tr>
						<td>
EOT;
echo "<strong>" . $user->getUsername() . "</strong></td><td>login: " . date('g:i a', $user->getLogtime()) . "</td><td id='userid' style='display:none;'>" . $user->getUserId() . "</td>";
echo <<<EOT
						</td>
						<td><a href="*">Account</a></td>
						<td><a href="http://development/index.php">Logout</a></td>
					</tr>
				</table>
				<img src="images/appcomp.png">
			</header>
			<nav>
				<table id="adminT">
					<tr>
						<td id="admin">Admin</td>
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
							<td><input id="search_field" type="text" /></td>
							<td><button id="search_btn"><img src="images/search.png"></button></td>
						</tr>
					</table>
				</div>
				<div id="contentT">
				
				</div>		
			</div>
			<div id="breadcrumbT">
				<table>
					<tr>
						<td></td>
						<td></td>
						<td></td>
					</tr>					
				</table>				
			</div>
			<div id="main">

			</div>
			<footer>
				<table id="footerT">
					<tr>
						<td><a href="*">Contact</a></td>
						<td><a href="http://enneadintranet/">Intranet</a></td>
						<td><a href="http://www.ennead.com">Ennead</a></td>
					</tr>					
				</table>
			</footer>
		</div>
	</body>
</html>

EOT;
?>