<?php
// reference the file containing the content class
require_once('page.class.php');
// retrieve the operation to be performed
$mode = $_POST['mode'];

// create a new Page instance
$page = new Page();

if ($mode == 'getPageTable') {
	// retrieve the action parameters from client request
	$table = $_POST['table'];
	
	// check for valid id
	if($table != '') {
		// clear out the output buffer
		if(ob_get_length()) ob_clean();
		// send headers so that browser doesn't cache content
		header('Expires: Mon, 26 July 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-Type: application/json');
		//retrieve the content from the server
		echo json_encode($page->getPageTable($table));
	}
} else if ($mode == 'userSearch') {
	$table = $_POST['table'];
	$word = $_POST['searchword'];
	
	// check for valid id
	if($table != '' && $word != '') {
		// clear out the output buffer
		if(ob_get_length()) ob_clean();
		// send headers so that browser doesn't cache content
		header('Expires: Mon, 26 July 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-Type: application/json');
		//retrieve the content from the server
		echo json_encode($page->searchPageTable($table, $word));
	}
}

?>