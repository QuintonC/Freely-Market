<?php

class DbOperation {
    private $conn;

    //Constructor
    function __construct() {
        
        require_once dirname(__FILE__) . '/Config.php';
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        
        $db = new DbConnect();
        $this->conn = $db->connect();
    
    }

    //Function to check if user exists
    public function checkAvail($username) {
        $stmt = $this->conn->prepare("SELECT * FROM User_Accounts WHERE username='$username' LIMIT 1");
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $stmt->store_result();
            $checkResult = false;
            $stmt->bind_result($checkResult);
            $stmt->fetch();

            if ($stmt->num_rows == 1) {
                return true;
                exit;
            } else {
                return false;
                exit;
            }
            $stmt->close();
        }
    }

    //Function to create a new user
    public function createUser($username, $password, $first_name, $last_name, $email, $phone) {
        $stmt = $this->conn->prepare("INSERT INTO User_Accounts(username, password, first_name, last_name, email, phone) values(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $password, $first_name, $last_name, $email, $phone);
        $result = $stmt->execute();
        $stmt->close();
        
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    //Function for checking login credentials
    public function checkCredentials($username, $password) {
        $stmt = $this->conn->prepare("SELECT username, password FROM User_Accounts WHERE username='$username' AND password='$password'");
        $stmt->bind_param("ss", $username, $password);
        
        if ($stmt->execute()) {
            $stmt->store_result();
            $credCheck = false;
            $stmt->bind_result($credCheck);
            $stmt->fetch();

            if ($stmt->num_rows == 1) {
                return true;
                exit;
            } else {
                return false;
                exit;
            }
        }
        $stmt->close();
    }
}

?>