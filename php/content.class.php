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

	public function getStaffSummary($id)
	{
		$id = $this->mMysqli->real_escape_string($id);
		
		if($id != '') {
			// this query needs to use the $table variable once it is functional
			$query = "SELECT s.last_name, s.first_name, t.tot, ds.des, dc.doc, co.coo
					   FROM staff s
							JOIN (SELECT staff_id, COUNT(staff_id) AS tot
                     			FROM staff_courses
                    			GROUP BY staff_id) AS t ON s.staff_id = t.staff_id
							LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS des
                     			FROM staff_courses WHERE course_id LIKE '%DES%'
                    			GROUP BY staff_id) AS ds ON s.staff_id = ds.staff_id
							LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS doc
                     			FROM staff_courses WHERE course_id LIKE '%DOC%'
                    			GROUP BY staff_id) AS dc ON s.staff_id = dc.staff_id
							LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS coo
                     			FROM staff_courses WHERE course_id LIKE '%COO%'
                    			GROUP BY staff_id) AS co ON s.staff_id = co.staff_id";					  
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
				$reference['total'] = $row['tot'];
				$reference['des_tot'] = $row['des'];
				$reference['doc_tot'] = $row['doc'];
				$reference['coo_tot'] = $row['coo'];
				array_push($response['references'], $reference);
			}
			// close the database connection as soon as possible
			$result->close();
		}		
		//return the JSON response
		return $response;	
	}

	public function getCourseSummary($id)
	{
		$id = $this->mMysqli->real_escape_string($id);
		
		if($id != '') {
			// this query needs to use the $table variable once it is functional
			$query = "SELECT c.id, c.title, t.total 
						FROM courses c
							LEFT JOIN (SELECT course_id, COUNT(course_id) AS total
                     			FROM staff_courses
                    			GROUP BY course_id) AS t ON c.id = t.course_id";					  
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
				$reference['title'] = $row['title'];
				$reference['total'] = $row['total'];
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