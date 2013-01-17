/**
 * @author dwalsh
 */

// when set to true, display detailed error message
var debugMode = true;

// function that displays an error message
function displayError(message)
{
	// display error message, with more technical details if debugMode is true
	alert ("error accessing the server! " +
				(debugMode ? message : ""));
}

// function that displays a PHP error message
function displayPHPError(error)
{
	displayError ("Error number :" + error.errno + "\r\n" +
				"Text :" + error.text + "\r\n" +
				"Location :" + error.location + "\r\n" +
				"Line :" + error.line + "\r\n");
}
