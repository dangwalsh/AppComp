<?php
require_once('config.php');
require_once('error_handler.php');

class Insert
{
	private $mMysqli;
	// constructor takes no arguments
	function __construct()
	{
		$this->mMysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	}
	// destructor closes connection when destroyed
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

	public function getCorresponding($table, $selector, $value)
	{
		$t = "";
		$s = "";
		// determine whether the request is for a project or course, name or number
		switch ($table) {
			case "courseT":				
				switch ($selector) {
					case "course_num":
						$s = "id";
						break;
					case "course_name":
						$s = "title";
						break;
				}
				$t = "courses";
				break;
			case "projectT":
				switch ($selector) {
					case "proj_num":
						$s = "id";
						break;
					case "proj_name":
						$s = "title";
						break;
				}
				$t = "projects";
				break;
		}		
		// build the query
       	$query = "SELECT * 
       			  FROM $t
       			  WHERE $s = '$value'";	                    						  
		// execute the query
		$result = $this->mMysqli->query($query);
		
		// build the JSON response
		$response = array();

		// see if there are any results
		if($result->num_rows) {
			// loop through all the fetched content
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$response['id'] = $row['id'];
				$response['title'] = $row['title'];
			}
			// close the database connection as soon as possible
			$result->close();
		}		
		//return the JSON response
		return $response;
	}

	public function addEntry($table, $id, $staff)
	{
		$t = "";
		$col = "";
		// set the type of query
		if ($table == 'courseT') {
			$t = 'staff_courses';
			$col = 'course_id';
		} else {
			$t = 'staff_projects';
			$col = 'proj_id';
		}
		
		// this query needs to use the $table variable once it is functional
       	$query = "INSERT INTO staff_courses 
       			(staff_id, $col, date) 
       			VALUES ($staff, '$id', NOW())";	                    						  
		// execute the query
		$result = $this->mMysqli->query($query);
		// close the database connection	
		//$result->close();
		
		return 'Successfully inserted ' . $id . ' into database.';	
	}
	
	public function submitContent($staff_id, $type, $category, $subcategory, $title, $content)
	{
		// this query needs to use the $table variable once it is functional
       	$query = "INSERT INTO $type 
       			  (staff_id, category, subcategory, title, content, date_created) 
       			  VALUES ($staff_id, '$category', '$subcategory', '$title', '$content', NOW())";	                 						  
		// execute the query
		$result = $this->mMysqli->query($query);
		// close the database connection	
		//$result->close();
		
		return 'Successfully inserted record into database.';	
	}
	
}
?>