<?php
	class MySQLDao {
		var $dbhost = null;
		var $dbuser = null;
		var $dbpass = null;
		var $connect = null;
		var $dbname = null;
		var $result = null;

		function __construct() {
			$this->dbhost = connect::$dbhost;
			$this->dbuser = connect::$dbuser;
			$this->dbpass = connect::$dbpass;
			$this->dbname = connect::$dbname;
		}

		public function openConnection() {
			$this->connect = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
			if (mysqli_connect_errno())
			echo new Exception("Could not establish connection with database");
		}

		public function getConnection() {
			return $this->connect;
		}

		public function closeConnection() {
			if ($this->connect != null)
			$this->connect->close();
		}

		public function getUserDetails($username) {
			$returnValue = array();
			$sql = "select * from users where username='" . $username . "'";

			$result = $this->conn->query($sql);
			if ($result != null && (mysqli_num_rows($result) >= 1)) {
				$row = $result->fetch_array(MYSQLI_ASSOC);
			
				if (!empty($row)) {
					$returnValue = $row;
				}
			}
			return $returnValue;
		}

		public function getUserDetailsWithPassword($username, $userpassword) {
			$returnValue = array();
			$sql = "select id,user_email from users where username='" . $username . "' and password='" .$userpassword . "'";

			$result = $this->conn->query($sql);
			if ($result != null && (mysqli_num_rows($result) >= 1)) {
				$row = $result->fetch_array(MYSQLI_ASSOC);
				if (!empty($row)) {
					$returnValue = $row;
				}
			}
			return $returnValue;
		}

		public function registerUser($username, $password) {
			$sql = "insert into User_Accounts set username=?, password=?";
			$statement = $this->conn->prepare($sql);

			if (!$statement)
			throw new Exception($statement->error);

			$statement->bind_param("ss", $username, $password);
			$returnValue = $statement->execute();

			return $returnValue;
		}

	}
?>