<?php

session_start();
require_once("db_constant.php");


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}

#Get from url
$prid = $_GET['id'];

$seller = $_SESSION['username'];

#Get username and id that belongs to the listing
$mysql = "select username, rid from Pending_Rental where prid = '$prid'";
$result = $conn->query($mysql);
$row = mysqli_fetch_array($result);
$renter = $row['username'];
$rid = $row['rid'];

#Select card id and user id 
$sql = "SELECT c.cid, r.aid FROM CardInfo AS c, Rental_Listing AS r, User_Accounts AS a WHERE r.rid = '$rid' AND r.aid = a.aid AND a.aid = c.aid";
$content = $conn->query($sql);
$set = mysqli_fetch_array($content);
$cid = $set['cid'];
$aid = $set['aid'];

#Delete listing from pending table
$sql1 = "delete from Pending_Rental where rid = '$rid'";
$conn->query($sql1);

#Insert into trsnactions table to finalize
$sql2 = "insert into R_Transactions (renter, seller, rid, cid, aid) values ('$renter','$seller','$rid','$cid','$aid')";

if ($conn->query($sql2) === TRUE) {
	header("Location: fm_account.php");
	exit;
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}


?>