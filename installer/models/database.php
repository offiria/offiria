<?php
class DBConnection 
{
	/**
	 *	Main database class
	 *	Works as a layer over PDO. Every static call is forwarded to the PDO object, except for those which are defined
	 *
	 **/
	protected static $DB;
	
	private function __construct()	{}
	private function __clone()		{}
	
	public static function connect($dbname='default', $host='localhost', $user='root', $password='') {
		try {
			// connects to the database
			self::$DB = new PDO("mysql:dbname=$dbname;host=$host" , $user , $password);
			// set the error reporting attribute
			self::$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e) {
			// echo $e->getMessage();
			return false;
		}
		return self::$DB;
	}
	
	public static function close_connection() {
		self::$DB = NULL;
	}
	
	public static function __callStatic($name, $arguments) {
		return forward_static_call_array(array(self::$DB, $name), $arguments);
	}
}

class DBManager 
{
	protected $_prefix;

	public function __construct($prefix = '') {
		$this->_prefix = $prefix;
	}

	public function createDatabase(& $db, $name) {
		// Build the create database query.
		$query = 'CREATE DATABASE `'.$name.'` CHARACTER SET `utf8`';
		try	{
			// Run the create database query.
			$stmt = $db->prepare($query);
			$stmt->execute();
		}
		catch (RuntimeException $e)	{
			// If an error occurred return false.
			return false;
		}
		return true;
	}

	public function dropDatabase(& $db, $name) {
		// Build the create database query.
		$query = 'DROP DATABASE `'.$name.'`';
		try	{
			// Run the create database query.
			$stmt = $db->prepare($query);
			$stmt->execute();
		}
		catch (RuntimeException $e)	{
			// echo $e->getMessage();
			// If an error occurred return false.
			return false;
		}
		return true;
	}

	/**
	 * Method to import a database schema from a file.
	 *
	 * @param	JDatabase	&$db	JDatabase object.
	 * @param	string		$schema	Path to the schema file.
	 *
	 * @return	boolean	True on success.
	 * @since	1.0
	 */
	public function populateDatabase(& $db, $schema)
	{
		// Initialise variables.
		$return = true;
		// Get the contents of the schema file.
		if (!($buffer = file_get_contents($schema))) {
			return false;
		}

		// Get an array of queries from the schema and process them.
		$queries = $this->_splitQueries($buffer);
		foreach ($queries as $query) {
			// Trim any whitespace.
			$query = trim($query);

			// If the query isn't empty and is not a comment, execute it.
			if (!empty($query) && ($query{0} != '#')) {
				try	{
					// Execute the query.
					$stmt = $db->prepare($query);
					$stmt->execute();
				}
				catch (RuntimeException $e)	{
					// echo $e->getMessage() . '<br />';
					// $return = false;
				}
			}
		}

		return $return;
	}

	/**
	 * Method to install all patches in a directory
	 */
	public function installPatches(& $db, $dir) {
		$patches = scandir($dir);
		foreach ($patches as $idx=>$file) {
			// remove all files begins with dots
			// we need more than directory pointer (since all files are passed to populateDatabase())
			if (!preg_match('~^\.~', $file)) {
				$this->populateDatabase($db, $dir.DS.$file);
			}
		}
		return true;
	}

	/**
	 * Installation of first Offiria admin user
	 */
	public function installAdmin(& $db, $username, $password, $email, $configs) {
		$salt = UserHelper::genRandomPassword(32);
		$crypt = UserHelper::getCryptedPassword($password, $salt);
		$password = $crypt . ':' . $salt;
		//$table = $configs->getValue('dbprefix') . 'users';
		$table = $this->_prefix . 'users';
		$date = date("Y-m-d H:i:s");
		$query = "INSERT INTO `$table` (id, name, username, email, password, sendEmail, registerDate, lastvisitDate, params) " . 
			"VALUES(42, '$username', '$username', '$email', '$password', 1, '$date', '$date', '')";
		try	{
			$stmt = $db->prepare($query);
			$stmt->execute();
		}
		catch (RuntimeException $e)	{
			return false;
		}
		

		// install anon user
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = '';
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, count($alphabet)-1);
	        $pass[$i] = $alphabet[$n];
	    }
	    $pass = $pass . ':' . $salt;
		$query = "INSERT INTO `$table` (id, name, username, email, password, sendEmail,  block, registerDate, lastvisitDate, params) " . 
			"VALUES(43, 'Anonymous', 'anon', 'anon@anon.com', '$pass', 1,  1, '$date', '$date', '')";
		try	{
			$stmt = $db->prepare($query);
			$stmt->execute();
		}
		catch (RuntimeException $e)	{
			return false;
		}

		return true;
	}

 	/**
	 * Method to split up queries from a schema file into an array.
	 *
	 * @param	string	$sql SQL schema.
	 *
	 * @return	array	Queries to perform.
	 * @since	1.0
	 * @access	protected
	 */
	function _splitQueries($sql)
	{
		// Initialise variables.
		$buffer		= array();
		$queries	= array();
		$in_string	= false;

		// Trim any whitespace.
		$sql = trim($sql);

		// Remove comment lines.
		$sql = preg_replace("/\n\#[^\n]*/", '', "\n".$sql);

		// Table prefix
		$sql = preg_replace("/%%PREFIX%%/", $this->_prefix, $sql);

		// Parse the schema file to break up queries.
		for ($i = 0; $i < strlen($sql) - 1; $i ++)
			{
				if ($sql[$i] == ";" && !$in_string) {
					$queries[] = substr($sql, 0, $i);
					$sql = substr($sql, $i +1);
					$i = 0;
				}

				if ($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {
					$in_string = false;
				}
				elseif (!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset ($buffer[0]) || $buffer[0] != "\\")) {
					$in_string = $sql[$i];
				}
				if (isset ($buffer[1])) {
					$buffer[0] = $buffer[1];
				}
				$buffer[1] = $sql[$i];
			}

		// If the is anything left over, add it to the queries.
		if (!empty($sql)) {
			$queries[] = $sql;
		}

		return $queries;
	}
}
