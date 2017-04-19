<?php
session_start();
require_once("../db_constant.php");

if (isset($_SESSION['loggedin']) and $_SESSION['loggedin'] == true) {
    $log = $_SESSION['username'];
} else {
    echo "Please log in first to see this page.";
}	
	
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}
	
#Get ID from url
$appid = $_GET['id'];
	
	
$esql = "select email from Vendor_Application where appid = '$appid'";
$email = $conn->query($esql);
$fetch = mysqli_fetch_array($email);

$csql = "select code from Vendor_Application where appid= '$appid'";
$array = $conn->query($csql);
$fetch1 = mysqli_fetch_array($array);


$to = $fetch['email'];
$subject = "Vendor Application Accepted";
$msg = '
<html>
<head>
<title>Freely Market Password</title>
</head>
<body>
<p>Follow this <a href="https://cgi.soic.indiana.edu/~team12/vendor/register_login/fm_v_account_number.html"> link</a> to input your code: '.$fetch1['code']. '</p></br>
<br/>
<p><i>Freely Market Team</i></p>
';

//Set content-type
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

//More headers
$headers .= 'From: freelycreativecapstone@gmail.com' . "\r\n"; 

$sql = "Update Vendor_Application Set confirmed = 'confirmed' where appid = '$appid'";

if (!empty($fetch1) and !empty($fetch) and $conn->query($sql) === TRUE) {
	header("Location: fm_admin_vendor_requests.php");
	mail($to, $subject, $msg, $headers); 
	exit;
} else {
	echo "Error: " . $esql . "<br>" . $conn->error;
}


?>