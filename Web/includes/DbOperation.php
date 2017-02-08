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
                exit;
            } else {
                return false;
                exit;
            }
        }
        $stmt->close();
    }
    
    //Function for getting contacts
    public function getContacts($user) {
    	$stmt = $this->conn->prepare("SELECT sender, reciever FROM Messages WHERE sender ='$user' OR reciever ='$user'");
    	$stmt->bind_param("sss", $sender, $reciever)
    	$result = $stmt->execute();
    	
    	$contacts = array();
    	
    	$num=mysql_numrows($result);
    	
    	$i=0;
    	
    	while ($i < $num) {
    		$sender=mysql_result($result,$i,"sender");
			$reciever=mysql_result($result,$i,"reciever");
			if ($user == $sender) {
				$contacts[] = $reciever;
			} else {
				$contacts[] = $sender;
			}
			$i = $i + 1;
    	}
    	return $contacts;
    	$stmt->close();
    }
    
    
    //Function for getting messages
    public function getMessages($id1, $id2) {
    	$stmt = $this->conn->prepare("SELECT message FROM Messages WHERE (sender='$id1' OR sender='$id2') AND (reciever='$id1' OR reciever='$id2') ORDER BY recieved");
    	$stmt->bind_param("ssss", $id1, $id2)
    	$result = $stmt->execute();

		//$query="SELECT message FROM Messages WHERE (sender='$id1' OR sender='$id2') AND (reciever='$id1' OR reciever='$id2') ORDER BY recieved";
		//$result=mysql_query($query);

		$num=mysql_numrows($result);

		

		$i=0;
		$messages = array();
		
		while ($i < $num) {

		$sender=mysql_result($result,$i,"sender");
		$reciever=mysql_result($result,$i,"reciever");
		$message=mysql_result($result,$i,"message");

		$messages[]=array($sender, $reciever, $message);
		
		$i++;
		}
		return $messages;
		$stmt->close();
    }
    
    

    /* public function getUserID() {

    } */

    /*public function editUserInfo($password, $first_name, $last_name, $email, $phone) {
        $stmt = $this->conn->prepare("UPDATE User_Accounts SET password=$password, first_name=$first_name, last_name=$last_name, email=$email, phone=$phone WHERE username=$username ");
        $stmt->bind_param("sssss", $password, $first_name, $last_name, $email, $phone);
        
        if ($result) {
            return true;
        } else {
            return false;
        }       
    } */
}

?>