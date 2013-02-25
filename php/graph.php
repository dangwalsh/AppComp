<?php
// reference the file containing the content class
require_once('graph.class.php');
// reference the file containing header function
require_once('headers.php');
// retrieve the operation to be performed
$mode = $_POST['mode'];
//if the operation is RetrieveContent
if($mode == 'GetCategoriesGraph') {
	// retrieve the action parameters from client request
	$id = $_POST['id'];	
	// check for valid id
	if($id != '') {
		sendHeaders();
		// create a new Graph instance
		$graph= new Graph('bar');
		//retrieve the content from the server
		echo json_encode($graph->getCategoriesGraph($id));
	}
}
?>