<?php

session_start();
require_once("../../../db_constant.php");


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}
	
	
$renter = $_SESSION['username'];

#Get from url
$prid = $_GET['id'];

#Get rental listing id 
$mysql = "select rid from Pending_Rental where prid = '$prid'";
$result = $conn->query($mysql);
$row = mysqli_fetch_array($result);
$rid = $row['rid'];

#Get username that belongs to the listing
$sql = "select username from Pending_Rental where prid = '$prid'";
$content = $conn->query($sql);
$set = mysqli_fetch_array($content);
$borrower = $set['username'];

#Get bidder's email
$sql1 = "select email from User_Accounts where username = '$borrower'";
$product = $conn->query($sql1);
$grp = mysqli_fetch_array($product);
$email = $grp['email'];

#Delete listing from pending rental
$sql2 = "delete from Pending_Rental where rid = '$rid'";
$conn->query($sql2);

#Get Item Name
$sql3 = "select item from Rental_Listing where rid = '$rid'";
$info = $conn->query($sql3);
$tup = mysqli_fetch_array($info);
$item = $tup['item'];

$type = "rentreject";
$date = date("Y-m-d H:i:s");
$message = "Your offer for " . $item . " has been rejected!";
$email_msg = "Your offer for " . $item . " has been rejected by " . $renter . "!<br /><a href = 'http://cgi.soic.indiana.edu/~team12/register_login/fm_login.html'>Freely Market</a>";

#Create Notification
$sql4 = "insert into Notifications(message,recipient,sender,types,created,rid) values('$message','$borrower','$renter','$type','$date','$rid')";

#Email Confirmation Message
$to = $email;
$subject = 'Offer Status';

//Set content-type
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

//More headers
$headers .= 'From: freelycreativecapstone@gmail.com' . "\r\n";

if ($conn->query($sql4) === TRUE) {
	mail($to, $subject, $email_msg, $headers);
	header("Location: ../../../account/fm_account.php");
	exit;
} else {
	echo "Error: " . $sql4 . "<br>" . $conn->error;
}



?>