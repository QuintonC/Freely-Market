<?php

session_start();
require_once("db_constant.php");


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

#Delete listing from pending rental
$sql1 = "delete from Pending_Rental where rid = '$rid'";
$conn->query($sql1);

$reject = "Your offer has been rejected";
$date = date("Y-m-d H:i:s");

#Create Notification
$sql2 = "insert into Notifications(recipient,sender,types,created,rid) values('$borrower','$renter','$reject','$date','$rid')";

if ($conn->query($sql2) === TRUE) {
	header("Location: fm_account.php");
	exit;
} else {
	echo "Error: " . $sql2 . "<br>" . $conn->error;
}



?>