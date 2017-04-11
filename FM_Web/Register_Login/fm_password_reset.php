<?php
session_start();
require_once("../db_constant.php");

$con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL:" .mysqli_connect_error();
	}

$username = $_POST['username'];
$new_pass = $_POST['new_pass'];
$confirm_pass = $_POST['confirm_pass'];

if ($new_pass != $confirm_pass){
	echo '<script type="text/javascript"> alert("Passwords do not match."); window.location="fm_password_reset.html";</script>';
}

$encpw = password_hash($new_pass, PASSWORD_BCRYPT);

$sql = "UPDATE User_Accounts SET password='$encpw' WHERE username = '$username'";

$result = $con->query($sql);
	if ($result === TRUE) {
		echo '<script type="text/javascript"> alert("Password changed."); window.location="fm_login.html";</script>';			
	} else {
			echo "<p>Passwords do not match</p>";
			exit;
	} 
?>