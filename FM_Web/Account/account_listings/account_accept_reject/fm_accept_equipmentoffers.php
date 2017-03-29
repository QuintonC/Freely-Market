<?php

session_start();
require_once("../../../db_constant.php");


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}

#Get from url
$peid = $_GET['id'];

$seller = $_SESSION['username'];

#Get username and id that belongs to the listing
$mysql = "select username, eid from Pending_Equipment where peid = '$peid'";
$result = $conn->query($mysql);
$row = mysqli_fetch_array($result);
$buyer = $row['username'];
$eid = $row['eid'];

#Select card id and user id 
$sql = "SELECT c.cid, e.aid FROM CardInfo AS c, Equipment_Listing AS e, User_Accounts AS a WHERE e.eid = '$eid' AND e.aid = a.aid AND a.aid = c.aid";
$content = $conn->query($sql);
$set = mysqli_fetch_array($content);
$cid = $set['cid'];
$aid = $set['aid'];


$type = "equipment accept";
$date = date("Y-m-d H:i:s");
$payment = "pending";
$message = "Your offer for an equipment listing has been accepted!";

#Insert into transactions table to finalize
$sql2 = "insert into Equipment_Transactions (buyer, seller, occured, eid, cid, aid, payment) values ('$buyer','$seller','$date','$eid','$cid','$aid','$payment')";
$conn->query($sql2);

#Get Transactions ID
$sql3 = "select etid from Equipment_Transactions where eid = '$eid'";
$record = $conn->query($sql3);
$batch = mysqli_fetch_array($record);
$etid = $batch['etid'];


#Create Notification
$sql4 = "INSERT INTO Notifications(message,recipient,sender,types,created,eid) VALUES('$message','$buyer','$seller','$type','$date','$eid')";
$conn->query($sql4);

$status = 'Complete';

#Update Listing Status
$sql5 = "UPDATE Equipment_Listing SET status = '$status' WHERE eid = '$eid' AND status = 'Active'";
$conn->query($sql5);

#Delete listing from pending table
$sql6 = "delete from Pending_Equipment where eid = '$eid'";

if ($conn->query($sql6) === TRUE) {
	header("Location: ../../../account/fm_account.php");
	exit;
} else {
	echo "Error: " . $sql6 . "<br>" . $conn->error;
}


?>