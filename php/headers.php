<?php
function sendHeaders()
{
	// clear out the output buffer
	if(ob_get_length()) ob_clean();
	// send headers so that browser doesn't cache content
	header('Expires: Mon, 26 July 1997 05:00:00 GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
	header('Cache-Control: no-cache, must-revalidate');
	header('Pragma: no-cache');
	header('Content-Type: application/json');
}
?>