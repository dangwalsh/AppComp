<?php
// reference the file containing the insert class
require_once('insert.class.php');
// reference the file containing the user class
require_once('user.class.php');
// reference the file containing header function
require_once('headers.php');
// retrieve the operation to be performed
$mode = $_POST['mode'];
// create a new Content instance
$insert= new Insert();
//if the operation is RetrieveContent
if ($mode == 'GetProjectList') {
	// call header function
	sendHeaders();
	//retrieve the content from the server
	echo json_encode($insert->getProjectList());	
} else if ($mode == 'GetCourseList') {
	sendHeaders();
	//retrieve the content from the server
	echo json_encode($insert->getCourseList());	
} else if ($mode == 'GetCorresponding') {
	// retrieve JSON parameters
	$table = $_POST['table'];
	$selector = $_POST['selector'];
	$value = $_POST['value'];
	// call header function	
	sendHeaders();
	// retrieve the content from the server
	echo json_encode($insert->getCorresponding($table, $selector, $value));	
} else if ($mode == 'AddEntry') {
	// retrieve JSON parameters
	$table = $_POST['table'];
	$id = $_POST['id'];
	$staff = $_POST['staff'];
	// call header function	
	sendHeaders();
	// retrieve the content from the server
	echo json_encode($insert->addEntry($table, $id, $staff));		
} else if ($mode == 'DeleteEntry') {
	// retrieve JSON parameters
	$table = $_POST['table'];
	$id = $_POST['id'];
	$staff = $_POST['staff'];
	// call header function	
	sendHeaders();
	// retrieve the content from the server
	echo json_encode($insert->deleteEntry($table, $id, $staff));		
} else if ($mode == 'SubmitContent') {
	// retrieve JSON parameters
	$staff_id = $_POST['id'];
	$type = $_POST['type'];
	$category = $_POST['category'];
	$subcategory = $_POST['subcategory'];
	$title = mysql_real_escape_string(trim($_POST['title']));
	$content = mysql_real_escape_string(trim($_POST['content']));
	$cid = $_POST['cid'];
	// call header function	
	sendHeaders();
	if ($cid == null) {
		// retrieve the content from the server
		echo json_encode($insert->submitContent($staff_id, $type, $category, $subcategory, $title, $content));
	} else {
		// retrieve the content from the server
		echo json_encode($insert->updateContent($staff_id, $type, $category, $subcategory, $title, $content, $cid));
	}

}
?>