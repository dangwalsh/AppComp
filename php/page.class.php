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
			// close the database connection as soon as possible
			$result->close();
		}
		//return the JSON response
		return $response;	
		//return $reference;
	}	
	
	public function searchPageTable($table, $word)
	{
		// escape the variable data
		$table = $this->mMysqli->real_escape_string($table);
		$word = $this->mMysqli->real_escape_string($word);
		// compose the SQL query that retrieves the data
		if ($table != '' && $word != '') {
			// retrieve all content of corresponding type
			$query1 = "SELECT category, subcategory, id, title
					   FROM $table
					   WHERE MATCH (title, content)
					   AGAINST ('" . $word . "')
					   ORDER BY category, subcategory, id";
		}
		// execute the query
		$result1 = $this->mMysqli->query($query1);
		
		if ($result1->num_rows) {
			
			$query2 = "SELECT category, subcategory, id, title
					   FROM $table
					   WHERE MATCH (title, content)
					   AGAINST ('" . $word . "')
					   ORDER BY category, subcategory, id";
			$result2 = $this->mMysqli->query($query2);
			 
		} else {
			
			$query3 = "SELECT category, subcategory, id, title
					   FROM $table
					   WHERE (title LIKE '%" . $word . "%'
					   OR content LIKE '%" . $word . "%')
					   ORDER BY category, subcategory, id";
			$result2 = $this->mMysqli->query($query3);
			 
		}
		
		// build the JSON response
		$response = array();
		$response['references'] = array();
		$reference = array();
		// see if there are any results
		if ($result2->num_rows) {
			// loop through all the fetched content
			while ($row = $result2->fetch_array(MYSQLI_ASSOC)) {
				$reference = array();								
				$reference['category'] = $row['category'];
				$reference['subcategory'] = $row['subcategory'];
				$reference['id'] = $row['id'];
				$reference['title'] = $row['title'];
				array_push($response['references'], $reference);	
			}
			// close the database connection as soon as possible
			$result1->close();
			$result2->close();
		}
		//return the JSON response
		return $response;
	}	

}
?>