<?php
// reference the file containing the content class
require_once('content.class.php');
// reference the file containing header function
require_once('headers.php');
// retrieve the operation to be performed
$mode = $_POST['mode'];
// create a new Content instance
$content = new Content();
//if the operation is RetrieveContent
if($mode == 'GetContent') {
	// retrieve the action parameters from client request
	$id = $_POST['id'];
	$table = $_POST['table'];
	
	// check for valid id
	if($id != '') {
		sendHeaders();
		//retrieve the content from the server
		echo json_encode($content->getContent($id, $table));
	}
} else if ($mode == 'GetStaffSummary') {
	// retrieve the action parameters from client request
	$id = $_POST['id'];	
	
	if($id != '') {
		sendHeaders();
		//retrieve the content from the server
		echo json_encode($content->getStaffSummary($id));
	}
} else if ($mode == 'GetCourseSummary') {
	// retrieve the action parameters from client request
	$id = $_POST['id'];
	
	if($id != '') {
		sendHeaders();
		//retrieve the content from the server
		echo json_encode($content->getCourseSummary($id));
	}
} else if ($mode == 'GetStaffCourseDetail') {
	// retrieve the action parameters from client request
	$id = $_POST['id'];
	
	if($id != '') {
		sendHeaders();
		//retrieve the content from the server
		echo json_encode($content->getStaffCourseDetail($id));
	}
} else if ($mode == 'GetStaffProjectDetail') {
	// retrieve the action parameters from client request
	$id = $_POST['id'];
	
	if($id != '') {
		sendHeaders();
		//retrieve the content from the server
		echo json_encode($content->getStaffProjectDetail($id));
	}
} else if ($mode == 'GetCourseDetail') {
	// retrieve the action parameters from client request
	$id = $_POST['id'];
	
	if($id != '') {
		sendHeaders();
		//retrieve the content from the server
		echo json_encode($content->getCourseDetail($id));
	}
} else if ($mode == 'GetStaffDetail') {
	// retrieve the action parameters from client request
	$id = $_POST['id'];
	
	if($id != '') {
		sendHeaders();
		//retrieve the content from the server
		echo json_encode($content->getStaffDetail($id));
	}
} else if ($mode == 'GetCourseStaffDetail') {
	// retrieve the action parameters from client request
	$id = $_POST['id'];
	
	if($id != '') {
		sendHeaders();
		//retrieve the content from the server
		echo json_encode($content->getCourseStaffDetail($id));
	}
} else if ($mode == 'GetProjectList') {
	echo json_encode($content->getProjectList());
}

?>