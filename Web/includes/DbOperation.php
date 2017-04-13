<?php

session_start();

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
        $stmt = $this->conn->prepare("INSERT INTO User_Accounts(username, password, first_name, last_name, email, phone, typ) values(?, ?, ?, ?, ?, ?, 0)");
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
    public function checkCredentials($username, $authpass) {
        $stmt = $this->conn->prepare("SELECT username, password FROM User_Accounts WHERE username='$username'");
        $stmt->bind_param("ss", $username, $password);
        
        if ($stmt->execute()) {
            $stmt->store_result();
            $credCheck = false;
            $stmt->bind_result($username, $password);
            $stmt->fetch();

            if (password_verify($authpass, $password)) {
                return true;
            } else {
                return false;
            }
        }
        $stmt->close();
    }

    public function editCreds($username, $password, $first_name, $last_name, $email, $phone) {

        $stmt = $this->conn->prepare("UPDATE User_Accounts SET password='$password', first_name='$first_name', last_name='$last_name', email='$email', phone='$phone' WHERE username='$username");
        $stmt->bind_param("sssss", $password, $first_name, $last_name, $email, $phone);
        $result = $stmt->execute();
        $stmt->close();
        
        if ($result) {
            return true;
        } else {
            return false;
        }      
    }

    public function buyListings() {
        $stmt = $this->conn->prepare("SELECT item, price, picture, descr, owner FROM Buy_Listing WHERE status='active'");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function rentalListings() {
        $stmt = $this->conn->prepare("SELECT item, price, picture, descr, owner FROM Rental_Listing WHERE status='active'");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function equipmentListings() {
        $stmt = $this->conn->prepare("SELECT item, price, picture, descr, owner FROM Equipment_Listing WHERE status='active'");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
    
    
    //Function for getting contacts
    public function getContacts($user) {
    	$stmt = $this->conn->prepare("SELECT sender, reciever FROM Messages WHERE sender ='$user' OR reciever ='$user';");
    	$stmt->execute();
    	$result = $stmt->get_result();
		return $result;
    	$stmt->close();
    }
    
    
    //Function for getting messages
    public function getMessages($id1, $id2) {
    	$stmt = $this->conn->prepare("SELECT sender, message FROM Messages WHERE (sender='$id1' OR sender='$id2') AND (reciever='$id1' OR reciever='$id2') ORDER BY recieved;");
    	
    	
    	$stmt->execute();
    	$result = $stmt->get_result();
		return $result;
    	$stmt->close();
    }
    
    //Function to create a new message
    public function createMessage($sender, $reciever, $message) {
    	#Today's date
		$date = date("Y-m-d H:i:s");
        $stmt = $this->conn->prepare("INSERT INTO Messages(sender,reciever,message,recieved) VALUES('$sender','$reciever','$message','$date')");
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    //Function to see if a user exists
    public function findUser($username) {
    	$stmt = $this->conn->prepare("SELECT username FROM User_Accounts WHERE username='$username';");
    	$stmt->execute();
        $stmt->store_result();
        $doesExist = false;
        $stmt->bind_result($doesExist);
        $stmt->fetch();

        if ($stmt->num_rows == 1) {
            $doesExist = true;
            return $doesExist;
            exit();
        } else {
            return $doesExist;
            exit();
        }
        $stmt->close();
    }

    // ONLY USE THIS FOR THE LISTING CREATION FUNCTION
    public function getAID($owner) { 
        $stmt = $this->conn->prepare("SELECT aid from User_Accounts WHERE username='$owner'");
        $stmt->bind_param("aid");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $row = $result->fetch_row();
        return $row[0];
    }

    public function createListing($type, $item, $price, $descr, $aid, $owner, $picture) {
        $stmt = $this->conn->prepare("INSERT INTO $type(item, price, descr, aid, owner, typ, status, picture) VALUES('$item', '$price', '$descr', '$aid', '$owner', 'User', 'Active', '$picture');");

        $stmt->bind_param("sssssss", $type, $item, $price, $descr, $aid, $owner, $picture);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
}

?>