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
$prid = $_GET['id'];

$seller = $_SESSION['username'];

#Get username and id that belongs to the listing
$mysql = "select username, rid from Pending_Rental where prid = '$prid'";
$result = $conn->query($mysql);
$row = mysqli_fetch_array($result);
$renter = $row['username'];
$rid = $row['rid'];

#Select card id and user id 
$sql = "SELECT c.cid, r.aid, a.email FROM CardInfo AS c, Rental_Listing AS r, User_Accounts AS a WHERE r.rid = '$rid' AND r.aid = a.aid AND a.aid = c.aid";
$content = $conn->query($sql);
$set = mysqli_fetch_array($content);
$cid = $set['cid'];
$aid = $set['aid'];
$email = $set['email'];

$type = "rentaccept";
$date = date("Y-m-d H:i:s");
$payment = "pending";
$message = "Your offer for a rental listing has been accepted!";

#Insert into tranactions table to finalize
$sql2 = "insert into Rental_Transactions (borrower, renter, occured, rid, cid, aid, payment) values ('$renter','$seller','$date','$rid','$cid','$aid','$payment')";
$conn->query($sql2);

#Get Transactions ID
$sql3 = "select rtid from Rental_Transactions where rid = '$rid'";
$record = $conn->query($sql3);
$batch = mysqli_fetch_array($record);
$rtid = $batch['rtid'];

// #Create Notification
// $sql4 = "INSERT INTO Notifications(message,recipient,sender,types,created,rid) VALUES('$message','$renter','$seller','$type','$date','$rid')";
// $conn->query($sql4);

// $status = 'Complete';

// #Update Listing Status
// $sql5 = "UPDATE Rental_Listing SET status = '$status' WHERE rid = '$rid' AND status = 'Active'";
// $conn->query($sql5);

// #Delete listing from pending table
// $sql6 = "delete from Pending_Rental where rid = '$rid'";

// #Email Confirmation Message
// $to = $email;
// $subject = 'the subject';

// //Set content-type
// $headers = "MIME-Version: 1.0" . "\r\n";
// $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// //More headers
// $headers .= 'From: freelycreativecapstone@gmail.com' . "\r\n";

// if ($conn->query($sql6) === TRUE) {
	// mail($to, $subject, $message, $headers;
	// header("Location: ../../../account/fm_account.php");
	// exit;
// } else {
	// echo "Error: " . $sql6 . "<br>" . $conn->error;
// }


?>