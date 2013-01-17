<?php
// reference the file containing the content class
require_once('content.class.php');
// retrieve the operation to be performed
$mode = $_POST['mode'];

// create a new Content instance
$content = new Content();

//if the operation is RetrieveContent
if($mode == 'GetContent')
{
	// retrieve the action parameters from client request
	$id = $_POST['id'];
	$table = $_POST['table'];
	
	// check for valid id
	if($id != '') {
		// clear out the output buffer
		if(ob_get_length()) ob_clean();
		// send headers so that browser doesn't cache content
		header('Expires: Mon, 26 July 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-Type: application/json');
		//retrieve the content from the server
		echo json_encode($content->getContent($id, $table));
	}
}

?>