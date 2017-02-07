<?php

session_start();
require_once("db_constant.php");


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}

$seller = $_SESSION['username'];
	
#Get from url
$psid = $_GET['id'];

#Get sale listing id 
$mysql = "select bid from Pending_Sale where psid = '$psid'";
$result = $conn->query($mysql);
$row = mysqli_fetch_array($result);
$bid = $row['bid'];

#Get username that belongs to the listing
$sql = "select username from Pending_Sale where psid = '$psid'";
$content = $conn->query($sql);
$set = mysqli_fetch_array($content);
$buyer = $set['username'];

#Delete listing from pending sale
$sql1 = "delete from Pending_Sale where bid = '$bid'";
$conn->query($sql1);

$reject = "Your offer has been rejected";
$date = date("Y-m-d H:i:s");

#Create Notification
$sql2 = "insert into Notifications(recipient,sender,types,created,bid) values('$buyer','$seller','$reject','$date','$bid')";

if ($conn->query($sql2) === TRUE) {
	header("Location: fm_account.php");
	exit;
} else {
	echo "Error: " . $sql2 . "<br>" . $conn->error;
}

?>