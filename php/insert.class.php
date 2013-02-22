<?php
require_once('config.php');
require_once('error_handler.php');

class Insert
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
	
	public function getProjectList()
	{
		// this query needs to use the $table variable once it is functional
       $query = "SELECT * FROM projects";	                    						  

		// execute the query
		$result = $this->mMysqli->query($query);
		
		// build the JSON response
		$response = array();
		$response['references'] = array();
		// see if there are any results
		if($result->num_rows) {
			// loop through all the fetched content
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$reference = array();
				$reference['id'] = $row['id'];
				$reference['title'] = $row['title'];
				$reference['date'] = $row['date'];
				array_push($response['references'], $reference);
			}
			// close the database connection as soon as possible
			$result->close();
		}		
		//return the JSON response
		return $response;
	}
	
	public function getCourseList()
	{
		// this query needs to use the $table variable once it is functional
       $query = "SELECT * FROM courses";	                    						  

		// execute the query
		$result = $this->mMysqli->query($query);
		
		// build the JSON response
		$response = array();
		$response['references'] = array();
		// see if there are any results
		if($result->num_rows) {
			// loop through all the fetched content
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$reference = array();
				$reference['id'] = $row['id'];
				$reference['title'] = $row['title'];
				$reference['date'] = $row['date_created'];
				array_push($response['references'], $reference);
			}
			// close the database connection as soon as possible
			$result->close();
		}		
		//return the JSON response
		return $response;
	}
}
?>