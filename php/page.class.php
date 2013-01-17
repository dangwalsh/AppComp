<?php
require_once('config.php');
require_once('error_handler.php');

// Page class that contains server-side page functionality
class Page
{
	private $mMysqli;
	
	function __construct()
	{
		$this->mMysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	}
	
	public function __destruct()
	{
		$this->mMysqli->close();
	}
	
	public function getPageTable($table)
	{
		// escape the variable data
		$table = $this->mMysqli->real_escape_string($table);
		// compose the SQL query that retrieves the data
		if ($table != '') {
			// retrieve all content of corresponding type
			$query = "SELECT category, subcategory, id, title
					  FROM $table
					  ORDER BY category, subcategory, id";
		} else {
			// handle the error
		}
		// execute the query
		$result = $this->mMysqli->query($query);
		
		// build the JSON response
		$response = array();
		$response['references'] = array();
		$reference = array();
		// see if there are any results
		if ($result->num_rows) {
			// loop through all the fetched content
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$reference = array();								
				$reference['category'] = $row['category'];
				$reference['subcategory'] = $row['subcategory'];
				$reference['id'] = $row['id'];
				$reference['title'] = $row['title'];
				array_push($response['references'], $reference);	
			}
			//array_push($response['references'], $reference);
			//$response['references']['reference'] = $refer
			// close the database connection as soon as possible
			$result->close();
		}
		//return the JSON response
		return $response;	
		//return $reference;
	}	
}
?>