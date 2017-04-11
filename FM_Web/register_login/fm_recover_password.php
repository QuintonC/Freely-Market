<?php
session_start();
require_once("../db_constant.php");

$con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL:" .mysqli_connect_error();
	}

$username = $_POST['username'];
$email = $_POST['email'];

$username = strip_tags($username);
$email = strip_tags($email);

$username = stripslashes($username);
$email = stripslashes($email);

$to = "$email";
$subject = "Password Recovery";
$msg = "
<html>
<head>
<title>Freely Market Password</title>
</head>
<body>
<p>Follow this <a href='https://cgi.soic.indiana.edu/~team12/fm_password_reset.html'> link</a> to reset your password.</p></br>
<br/>
<p><i>Freely Market Team</i></p>
";

//Set content-type
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

//More headers
$headers .= 'From: freelycreativecapstone@gmail.com' . "\r\n";

$mysql = "select * from User_Accounts where username = '$username' AND email = '$email'";
$result = $con->query($mysql); //Run $mysql string after connection is specified
$row_cnt = $result->num_rows;

function recoverPass(){
	echo '<script type="text/javascript"> alert("No account associated with that username or email."); location="fm_recover_password.html";</script>';
}

$account_exist = false;

if (!$row_cnt == 1) {
	recoverPass();
} else {
	echo '<script type="text/javascript"> alert("We have sent you an email address with a link to recover your password."); window.location="fm_login.html";</script>';
	mail($to, $subject, $msg, $headers);
	exit;
}

mysqli_close($con);

?>