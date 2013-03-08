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
			$query = "SELECT s.staff_id, s.last_name, s.first_name, fu.fun, ds.des, dc.doc, co.coo, bi.bim, na.nav, el.ele, t.tot 
					   FROM staff s
							LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS tot
                     			FROM staff_courses
                    			GROUP BY staff_id) AS t ON s.staff_id = t.staff_id
                    		LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS fun
                     			FROM staff_courses WHERE course_id LIKE '%REV%'
                    			GROUP BY staff_id) AS fu ON s.staff_id = fu.staff_id
							LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS des
                     			FROM staff_courses WHERE course_id LIKE '%DES%'
                    			GROUP BY staff_id) AS ds ON s.staff_id = ds.staff_id
							LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS doc
                     			FROM staff_courses WHERE course_id LIKE '%DOC%'
                    			GROUP BY staff_id) AS dc ON s.staff_id = dc.staff_id
							LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS coo
                     			FROM staff_courses WHERE course_id LIKE '%COO%'
                    			GROUP BY staff_id) AS co ON s.staff_id = co.staff_id
                    		LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS bim
                     			FROM staff_courses WHERE course_id LIKE '%BIM%'
                    			GROUP BY staff_id) AS bi ON s.staff_id = bi.staff_id
                    		LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS nav
                     			FROM staff_courses WHERE course_id LIKE '%NAV%'
                    			GROUP BY staff_id) AS na ON s.staff_id = na.staff_id
                    		LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS ele
                     			FROM staff_courses WHERE course_id LIKE '%REL%'
                    			GROUP BY staff_id) AS el ON s.staff_id = el.staff_id";					  
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
				$reference['staff_id'] = $row['staff_id'];
				$reference['last_name'] = $row['last_name'];
				$reference['first_name'] = $row['first_name'];
				$reference['fun_tot'] = $row['fun'];
				$reference['des_tot'] = $row['des'];
				$reference['doc_tot'] = $row['doc'];
				$reference['coo_tot'] = $row['coo'];
				$reference['bim_tot'] = $row['bim'];
				$reference['nav_tot'] = $row['nav'];
				$reference['ele_tot'] = $row['ele'];
				$reference['total'] = $row['tot'];
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

	public function getStaffCourseDetail($id)
	{
		$id = $this->mMysqli->real_escape_string($id);
		
		if($id != '') {
			// this query needs to use the $table variable once it is functional
			$query = "SELECT c.id, c.title, sc.date 
						FROM courses c
							JOIN (SELECT course_id, date
                     			FROM staff_courses 
                     			WHERE staff_id = $id) 
                     			AS sc ON c.id = sc.course_id";					  
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
				$reference['date'] = $row['date'];
				array_push($response['references'], $reference);
			}
			// close the database connection as soon as possible
			$result->close();
		}		
		//return the JSON response
		return $response;
	}

	public function getStaffProjectDetail($id)
	{
		$id = $this->mMysqli->real_escape_string($id);
		
		if($id != '') {
			// this query needs to use the $table variable once it is functional
			$query = "SELECT p.id, p.title, sp.date 
						FROM projects p
							JOIN (SELECT proj_id, date
                     			FROM staff_projects 
                     			WHERE staff_id = $id) 
                     			AS sp ON p.id = sp.proj_id";					  
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
				$reference['date'] = $row['date'];
				array_push($response['references'], $reference);
			}
			// close the database connection as soon as possible
			$result->close();
		}		
		//return the JSON response
		return $response;
	}


	public function getStaffDetail($id)
	{
		$id = $this->mMysqli->real_escape_string($id);
		
		if($id != '') {
			// this query needs to use the $table variable once it is functional
           $query = "SELECT *
						FROM staff
						WHERE staff_id = $id";	                    						  
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
				$reference['id'] = $row['staff_id'];
				$reference['last_name'] = $row['last_name'];
				$reference['first_name'] = $row['first_name'];
				$reference['role'] = $row['role'];
				array_push($response['references'], $reference);
			}
			// close the database connection as soon as possible
			$result->close();
		}		
		//return the JSON response
		return $response;
	}

	public function getCourseDetail($id)
	{
		$id = $this->mMysqli->real_escape_string($id);
		
		if($id != '') {
			// this query needs to use the $table variable once it is functional
           $query = "SELECT c.id, c.title, c.date_created, c.content
						FROM courses c 
						WHERE c.id='$id'";	                    						  
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
				$reference['date'] = $row['date_created'];
				$reference['content'] = $row['content'];
				array_push($response['references'], $reference);
			}
			// close the database connection as soon as possible
			$result->close();
		}		
		//return the JSON response
		return $response;
	}

	public function getCourseStaffDetail($id)
	{
		$id = $this->mMysqli->real_escape_string($id);
		
		if($id != '') {
			// this query needs to use the $table variable once it is functional
           $query = "SELECT s.staff_id, s.last_name, s.first_name, sc.date 
						FROM staff s
							JOIN (SELECT staff_id, date
                     			FROM staff_courses 
                     			WHERE course_id = '$id') 
                     			AS sc ON s.staff_id = sc.staff_id";                    						  
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
				$reference['id'] = $row['staff_id'];
				$reference['last_name'] = $row['last_name'];
				$reference['first_name'] = $row['first_name'];
				$reference['date'] = $row['date'];
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