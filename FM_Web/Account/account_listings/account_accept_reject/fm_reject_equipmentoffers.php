<?php

session_start();
require_once("../../../db_constant.php");


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}

$seller = $_SESSION['username'];
	
#Get from url
$peid = $_GET['id'];

#Get sale listing id 
$mysql = "select bid from Pending_Equipment where peid = '$peid'";
$result = $conn->query($mysql);
$row = mysqli_fetch_array($result);
$bid = $row['bid'];

#Get username that belongs to the listing
$sql = "select username from Pending_Equipment where peid = '$peid'";
$content = $conn->query($sql);
$set = mysqli_fetch_array($content);
$buyer = $set['username'];

#Get bidder's email
$sql1 = "select email from User_Accounts where username = '$buyer'";
$product = $conn->query($sql1);
$grp = mysqli_fetch_array($product);
$email = $grp['email'];

#Delete listing from pending sale
$sql2 = "delete from Pending_Equipment where eid = '$eid'";
$conn->query($sql2);

#Get Item Name
$sql3 = "select item from Equipment_Listing where eid = '$eid'";
$info = $conn->query($sql3);
$tup = mysqli_fetch_array($info);
$item = $tup['item'];

$type = "equipment reject";
$date = date("Y-m-d H:i:s");
$message = "Your offer for " . $item . " has been rejected!";
$email_msg = "Your offer for " . $item . " has been rejected by " . $seller . "!<br /><a href = 'http://cgi.soic.indiana.edu/~team12/register_login/fm_login.html'>Freely Market</a>";

#Create Notification
$sql4 = "insert into Notifications(message,recipient,sender,types,created,eid) values('$message','$buyer','$seller','$type','$date','$eid')";

#Email Confirmation Message
$to = $email;
$subject = 'Offer Status';

//Set content-type
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

//More headers
$headers .= 'From: freelycreativecapstone@gmail.com' . "\r\n";


if ($conn->query($sql4) === TRUE) {
	mail($to, $subject, $message, $headers);
	header("Location: ../../../account/fm_account.php");
	exit;
} else {
	echo "Error: " . $sql4 . "<br>" . $conn->error;
}

?>