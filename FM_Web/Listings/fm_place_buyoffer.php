<?php

session_start();
require_once("db_constant.php");


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}


$username = $_SESSION['username'];

#Get id from url
$bid = $_GET['id'];

#Create offer for sale
$mysql = "insert into Pending_Sale (username,bid) values ('$username','$bid')";
$conn->query($mysql);

$place = "You have recieved an offer";
$date = date("Y-m-d H:i:s");

$sql = "select psid from Pending_Sale where username = '$username' and bid = '$bid' limit 1";
$content = $conn->query($sql);
$row = mysqli_fetch_array($content);
$psid = $row['psid'];

$sql2 = "SELECT u.username FROM User_Accounts AS u, Buy_Listing AS b, Pending_Sale AS p WHERE p.bid = b.bid AND b.aid = u.aid AND psid = '$psid'";
$data = $conn->query($sql2);
$set = mysqli_fetch_array($data);
$seller = $set['username'];


#Create notification
$sql3 = "INSERT INTO Notifications(recipient,sender,types,created,psid) VALUES('$seller','$username','$place','$date','$psid')";

if ($conn->query($sql3) === TRUE) {
	header("Location: fm_account.php");
	exit;
} else {
	echo "Error: " . $sql3 . "<br>" . $conn->error;
}


?>