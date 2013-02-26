<?php
require_once('config.php');
require_once('error_handler.php');

class User
{
	private $uMysqli;
	private $username;
	private $password;
	private $staffid;
	private $usergroup;
	private $errlog;
	private $logtime;
	
	public function getUserId() 
	{
		return $this->staffid;
	}
	
	public function getUsername() 
	{
		return $this->username;
	}
	
	public function getLogtime()
	{
		return $this->logtime;
	}
	
	function __construct()
	{
		$this->uMysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	}

	public function __destruct()
	{
		//$this->uMysqli->close();
	}
	
	public function auth($name, $pass) {
		$n = $this->uMysqli->real_escape_string($name);
		$p = $this->uMysqli->real_escape_string($pass);
		if($n !='' && $p != '') {
			$query = "SELECT * 
				  	  FROM login 
				  	  WHERE username LIKE '$n' 
				  	  AND password LIKE '$p'";
			$result = $this->uMysqli->query($query);			
			if($result->num_rows){
				while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
					$this->username = $row['username'];
					$this->password = $row['password'];
					$this->usergroup = $row['usergroup'];
					$this->staffid = $row['staff_id'];
					$this->logtime = time();
				}
				return TRUE;
			}
			$this->errlog = "Authentication Failed";
			return FALSE;
		}
		$this->errlog = "Provide Missing Information";
		return FALSE;
	}

	public function login($id) {
		$n = $this->username;
		$query = "INSERT INTO sessions 
				  (session_id, username, date_created) 
				  VALUES ('$id', '$n', NOW())";
		$result = $this->uMysqli->query($query);

		if($this->uMysqli->affected_rows) {
			return TRUE;
		}
		return FALSE;
	}
	
	public function rec_logout($id) {
		$dbc = mysql_connect('localhost', 'web', 'W7x99XuQZXF46meD');
		mysql_select_db('appcomp', $dbc);
		$query = "UPDATE sessions
				  SET date_destroyed = NOW()
				  WHERE session_id = '$id'";
		$result = mysql_query($query, $dbc);
		
		if(mysql_affected_rows($dbc) > 0) {
			$_SESSION = array();
			session_destroy();
			setcookie('PHPSESSID', '', time()-3600, '/', '', 0, 0);
			mysql_close($dbc);
			return TRUE;
		}
		mysql_close($dbc);
		return FALSE;
	}
	
	public function stats() {
		$reference = array();
		$reference['username'] = $this->username;
		$reference['usergroup'] = $this->usergroup;
		$reference['staff_id'] = $this->staffid;
		$reference['error'] = $this->errlog;		
		return $reference;
	}
}

?>