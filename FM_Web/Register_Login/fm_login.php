
<?php

#Initiates session variables
session_start();

#References data base connection variables
require_once("db_constant.php");
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}

// Get Forms
$username = $_POST['user'];
$password = md5($_POST['password']);

//  Prevent MySQL injection 
$username = strip_tags($username);
$password = strip_tags($password);

$username = stripslashes($username);
$password = stripslashes($password);

//$username = mysql_real_escape_string($username);
//$password = mysql_real_escape_string($password);

#Creates a global session variable of the user account's id number
$mysql = "select aid from User_Accounts where username = '$username' limit 1";
$result = $conn->query($mysql);
$row = mysqli_fetch_array($result);
$id = $row['aid'];


#Selects the combination where the username and password match in the database
$sql = "select * from User_Accounts where username like '$username' and password like '$password' limit 1";

$result = $conn->query($sql);
	if (!$result->num_rows == 1) {
		echo "<p>Invalid username/password combination</p>";
	} else {
		#Creates session variables for username, password, and id if login is successful
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		$_SESSION['uid'] = $id;
		$_SESSION['loggedin'] = true;
		header("Location: fm_account.php");
		exit;
	}



?>