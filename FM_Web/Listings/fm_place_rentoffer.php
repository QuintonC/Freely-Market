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

#Get from url
$rid = $_GET['id'];

#Create offer for rental
$mysql = "insert into Pending_Rental (username,rid) values ('$username','$rid')";
$conn->query($mysql);

$place = "You have recieved an offer";
$date = date("Y-m-d H:i:s");

$sql = "select prid from Pending_Rental where username = '$username' and rid = '$rid' limit 1";
$content = $conn->query($sql);
$row = mysqli_fetch_array($content);
$prid = $row['prid'];

$sql2 = "SELECT u.username FROM User_Accounts AS u, Rental_Listing AS r, Pending_Rental AS p WHERE p.rid = r.rid AND r.aid = u.aid AND prid = '$prid'";
$data = $conn->query($sql2);
$set = mysqli_fetch_array($data);
$seller = $set['username'];


#Create notification
$sql3 = "INSERT INTO Notifications(recipient,sender,types,created,prid) VALUES('$seller','$username','$place','$date','$prid')";

if ($conn->query($sql3) === TRUE) {
	header("Location: fm_account.php");
	exit;
} else {
	echo "Error: " . $sql3 . "<br>" . $conn->error;
}


?>