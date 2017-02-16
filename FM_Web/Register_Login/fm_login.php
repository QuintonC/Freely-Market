
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
$formpass = $_POST['password'];


//  Prevent MySQL injection 
$username = strip_tags($username);
$formpass = strip_tags($formpass);

$username = stripslashes($username);
$formpass = stripslashes($formpass);



//$username = mysql_real_escape_string($username);
//$password = mysql_real_escape_string($password);


#Creates a global session variable of the user account's id number
$mysql = "select aid from User_Accounts where username = '$username' limit 1";
$result = $conn->query($mysql);
$row = mysqli_fetch_array($result);
$id = $row['aid'];


#Selects the combination where the entered form password matches the stored hash
$sql = "select password from User_Accounts where username = '$username' limit 1";
$content = $conn->query($sql);
$set = mysqli_fetch_array($content);
$password = $set['password'];


if (password_verify($formpass,$password)) {
	$_SESSION['username'] = $username;
	$_SESSION['uid'] = $id;
	$_SESSION['loggedin'] = true;
	header("Location: fm_account.php");
	exit;
} else {
	echo "<p>Invalid username/password combination</p>";
}



?>