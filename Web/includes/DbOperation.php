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
}

?>