<?php
require_once('config.php');
require_once('error_handler.php');

class Content
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
	
	public function getContent($id, $table)
	{
		$id = $this->mMysqli->real_escape_string($id);
		$table = $this->mMysqli->real_escape_string($table);
		
		if($id != '') {
			
			// this query needs to use the $table variable once it is functional
			$query = "SELECT *
			 		  FROM $table 
			 		  WHERE id='$id'";
		} else {
			// handle the error
		}
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
				$reference['staff_id'] = $row['staff_id'];
				$reference['title'] = $row['title'];
				$reference['content'] = $row['content'];
				$reference['date_created'] = $row['date_created'];
				$reference['file_path'] = $row['file_path'];
				array_push($response['references'], $reference);
			}
			// close the database connection as soon as possible
			$result->close();
		}
		
		//return the JSON response
		return $response;
		
	}

	public function getStaffSummary($id, $table)
	{
		$id = $this->mMysqli->real_escape_string($id);
		$table = $this->mMysqli->real_escape_string($table);
		
		if($id != '') {
			
			// this query needs to use the $table variable once it is functional
			$query = "SELECT last_name, first_name, COUNT(last_name) as count
					  FROM staff";
		}
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
				$reference['last_name'] = $row['last_name'];
				$reference['first_name'] = $row['first_name'];
				$reference['count'] = $row['count'];
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