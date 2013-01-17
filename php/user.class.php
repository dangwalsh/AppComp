<?php
require_once('config.php');
require_once('error_handler.php');

class User
{
	private $mMysqli;
	private $username;
	private $password;
	private $staffid;
	private $usergroup;
	
	function __construct()
	{
		$this->mMysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	}

	public function __destruct()
	{
		$this->mMysqli->close();
	}
	
	public function authorize($name, $pass)
	{
		if($name != '' && $pass != '') {
			// query to find if the username and password are a match
			$query = "SELECT *
					  FROM login
					  WHERE username = '$name'
					  AND password = '$pass'";			
		}
		// execute the query
		$result = $this->mMysqli->query($query);
		
		if($result->num_rows) {
			
			while($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$this->username = $name;
				$this->password = $pass;
				$this->usergroup = $row['usergroup'];
				$this->staffid = $row['staff_id'];
			}
			
			$result->close();
			return true;
			
		} else {
			
			$result->close();
			return false;
		}
		
	}
	
	public function rec_login($id) {
		$name = $this->username;
		
		$query = "INSERT INTO sessions (session_id, username, date_created) VALUES ('" . $id . "', '" . $name . "', NOW())";
		
		// execute the query
		//$result = $this->mMysqli->query($query);
		/*
		if($this->mMysqli->affected_rows()) {
			$result->close();
			return true;
		} else {
			$result->close();
			return false;
		}
		*/
		//$result->close();
	}
	
	public function stats() {
		$reference = array();
		
		$reference['username'] = $this->username;
		$reference['usergroup'] = $this->usergroup;
		$reference['staff_id'] = $this->staffid;
		
		return $reference;
	}
}

?>