<?php
session_start();
require_once("../../../db_constant.php");
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}
$username = $_SESSION['username'];
#Get id from url
$eid = $_GET['id'];

#Create offer for sale
$mysql = "insert into Pending_Equipment (username,eid) values ('$username','$eid')";
$conn->query($mysql);

$type = "equipmenteid";
$date = date("Y-m-d H:i:s");
$message = "You have recieved an offer for one of your equipment listings!";

$sql = "select peid from Pending_Equipment where username = '$username' and eid = '$eid' limit 1";
$content = $conn->query($sql);
$row = mysqli_fetch_array($content);
$peid = $row['peid'];

$sql2 = "SELECT u.username FROM User_Accounts AS u, Equipment_Listing AS e, Pending_Equipment AS p WHERE p.eid = e.eid AND e.aid = u.aid AND peid = '$peid'";
$data = $conn->query($sql2);
$set = mysqli_fetch_array($data);
$seller = $set['username'];

#Create notification
$sql3 = "INSERT INTO Notifications(message,recipient,sender,types,created,eid) VALUES('$message','$seller','$username','$type','$date','$eid')";
if ($conn->query($sql3) === TRUE) {
	header("Location: ../../../account/fm_account.php");
	exit;
} else {
	echo "Error: " . $sql3 . "<br>" . $conn->error;
}
?>