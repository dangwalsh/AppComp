<?php
// reference the file containing the content class
require_once('insert.class.php');
// reference the file containing header function
require_once('headers.php');
// retrieve the operation to be performed
$mode = $_POST['mode'];
// create a new Content instance
$insert= new Insert();
//if the operation is RetrieveContent
if ($mode == 'GetProjectList') {
	sendHeaders();
	//retrieve the content from the server
	echo json_encode($insert->getProjectList());	
} else if ($mode == 'GetCourseList') {
	sendHeaders();
	//retrieve the content from the server
	echo json_encode($insert->getCourseList());	
} 
?>