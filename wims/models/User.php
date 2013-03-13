<?php 
require_once 'model.php';
/*
 * User model
*/
class User extends Model implements Persistable{
	var $id;
	var $username;
	var $password;
	var $fisrtname;
	var $lastname;
	var $location;
	var $phone;
	var $lastLogin;
	var $loginAttempt;
	var $isLocked;
	var $properties = Array('id', 'username', 'password', 'email', 'firstname',
			'lastname', 'location', 'phone', 'lastLogin',
			'loginAttempt', 'isLocked');
	var $table = 'users';
	var $NULL_USER;
	const SALT = '#$@#$SADW$Q#EQqw21312';
	
	/*
	 * Constructor
	 */
	public function __construct($params) {
		$this::setPropertiesList($this->properties);
		//$this::createNull();
		foreach ($params as $key => $value) {
			if ($this::hasProperty($key))
				$this::setProperty($key, $value);
		}
	}
	
	/*
	 * Null 
	 */
	private function createNull() {
		$params = [
			'id'			=> 0,
			'username'		=> '', 
			'password'		=> '', 
			'email' 		=> '', 
			'firstname' 	=> '', 
			'lastname'		=> '', 
			'location' 		=> '',
			'phone' 		=> '',
			'lastLogin' 	=> '',
			'loginAttempt' 	=> 0,
			'isLocked' 		=> 0,
		];
		$this::setProperty('NULL_USER', new User($params));
	}

	/*
	 * Check whether a username exist or not
	*
	* @param string username to check
	* @return boolean true if username is in database; Otherwise, return false
	*/
	public function exist() {
		$query = "SELECT EXISTS (SELECT * FROM `$this->table` WHERE `username` = '$this->username') AS `existence`";
		$res =  mysql_query($query, $this::cursor());
		$row = mysql_fetch_row($res);		
		mysql_free_result($res);
		return $row[0] == 1;
	}

	/*
	 * Authenticate user 
	 */
	public function authenticate() {
		// only if user exists
		if ($this::exist()) {
			$query = "SELECT * FROM `$this->table` WHERE `username` = '$this->username'";
			$res = mysql_query($query, $this::cursor());
			$userData = mysql_fetch_assoc($res);
			mysql_free_result($res);
			if (strcmp($userData['password'], $this::generatePassword($this->password)) == 0)
				return $userData;
			return false;
		}
		return false;
	}
	private function getUserData($username) {
		if ($this::exist()) {
			$query = "SELECT * FROM `$this->table` WHERE `username` = '$this->username'";
			$res = mysql_query($query, $this::cursor());
			$userData = mysql_fetch_assoc($res);
			mysql_free_result($res);
			return $userData;
		}
		return false;
	}
	/*
	 * Register user
	 */
	public function register() {
		// only if user does not exist
		if (!$this::exist()) {
			$token = $this::generateToken();
			$password = $this::generatePassword($this->password);
			$query = "INSERT INTO `$this->table` (`username`,`password`,`email`,`token`) 
					VALUES ('$this->username', '$password', '$this->email','$token')";
			$res = mysql_query($query, $this->cursor());
			if ($res) {
				return array("username" => $this->username, "password" => $password, "email" => $this->email, "token" => $token);
			}
		}
		return false;
	}
	
	/*
	 * Generate password 
	 */
	private function generatePassword($rawPassword) {
		return md5($this::SALT.$rawPassword);
	}
	
	/*
	 * Generate token bases on random value
	 */
	private function generateToken() {
		$r1 = mt_rand(1, 10000);
		$r2 = mt_rand($r1, 2*$r1 + 9999);
		$current = time();
		return md5($r1.$r2.$current);
	}

	public function insert($params) {

	}
	public function delete($params) {

	}
	public function update($params) {

	}
	public function find($params) {

	}


}
?>