<?php

#Initiates session variables
session_start();

#References data base connection variables
require_once("../db_constant.php");
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

function invalidPass() {
	echo '<script type="text/javascript"> alert("Invalid username or password."); location="fm_login.html";</script>';
}
#Check if user is banned
$asql = "select active from User_Accounts where username = '$username'";
$activeCheck = $conn->query($asql);
$acheck = mysqli_fetch_array($activeCheck);

if ($acheck['active'] == 1) {
	header("Location: banned_warning.html");
} else {
	if (password_verify($formpass,$password)) {
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		$_SESSION['uid'] = $id;
		$_SESSION['loggedin'] = true;
		$_SESSION['typ'] = $typ;
		header("Location: ../account/fm_account.php");
		exit;
	} else {
		invalidPass();
	}
}

?>