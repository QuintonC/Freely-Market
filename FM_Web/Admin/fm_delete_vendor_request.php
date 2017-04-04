<?php
session_start();
require_once("db_constant.php");

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



$to = $fetch['email'];
$subject = "Vendor Application Denied";
$msg = '
<html>
<head>
<title>Freely Market Password</title>
</head>
<body>
<p>We are sorry but you have been denied a vendor account.  Please email freelycreativecapstone@gmail.com with any questions</p></br>
<br/>
<p><i>Freely Market Team</i></p>
';

//Set content-type
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

//More headers
$headers .= 'From: freelycreativecapstone@gmail.com' . "\r\n";
	
	

#Delete Sale Listing
$sql = "Update Vendor_Application Set confirmed = 'denied' where appid = '$appid'";

if (!empty($fetch) and $conn->query($sql) === TRUE) {
	header("Location: fm_admin_vendor_requests.php");
	mail($to, $subject, $msg, $headers); 
	exit;
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}

?>