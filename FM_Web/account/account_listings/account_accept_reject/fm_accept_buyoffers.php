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
$psid = $_GET['id'];

$seller = $_SESSION['username'];

#Get username and id that belongs to the listing
$mysql = "select username, bid from Pending_Sale where psid = '$psid'";
$result = $conn->query($mysql);
$row = mysqli_fetch_array($result);
$buyer = $row['username'];
$bid = $row['bid'];

#Get Buyer's email
$nsql = "select email from User_Accounts where username = '$buyer'";
$product = $conn->query($nsql);
$grp = mysqli_fetch_array($product);
$email = $grp['email'];

#Select card id and user id 
$sql = "SELECT c.cid, b.aid, FROM CardInfo AS c, Buy_Listing AS b, User_Accounts AS a WHERE b.bid = '$bid' AND b.aid = a.aid AND a.aid = c.aid";
$content = $conn->query($sql);
$set = mysqli_fetch_array($content);
$cid = $set['cid'];
$aid = $set['aid'];

#Get Item Name
$sql1 = "select item from Buy_Listing where bid = '$bid'";
$info = $conn->query($sql1);
$tup = mysqli_fetch_array($info);
$item = $tup['item'];


$type = "buyaccept";
$date = date("Y-m-d H:i:s");
$payment = "pending";
$message = "Your offer for " . $item . " has been accepted!";
$email_msg = "Your offer for " . $item . " has been accepted by " . $seller . "! <br /><a href = 'http://cgi.soic.indiana.edu/~team12/register_login/fm_login.html'>Freely Market</a>";

#Insert into transactions table to finalize
$sql2 = "insert into Bike_Transactions (buyer, seller, occured, bid, cid, aid, payment) values ('$buyer','$seller','$date','$bid','$cid','$aid','$payment')";
$conn->query($sql2);

#Get Transactions ID
$sql3 = "select btid from Bike_Transactions where bid = '$bid'";
$record = $conn->query($sql3);
$batch = mysqli_fetch_array($record);
$btid = $batch['btid'];


#Create Notification
$sql4 = "INSERT INTO Notifications(message,recipient,sender,types,created,btid) VALUES('$message','$buyer','$seller','$type','$date','$btid')";
$conn->query($sql4);

$status = 'Complete';

#Update Listing Status
$sql5 = "UPDATE Buy_Listing SET status = '$status' WHERE bid = '$bid' AND status = 'Active'";
$conn->query($sql5);

#Delete listing from pending table
$sql6 = "delete from Pending_Sale where bid = '$bid'";

#Email Confirmation Message
$to = $email;
$subject = 'Offer Status';

//Set content-type
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

//More headers
$headers .= 'From: freelycreativecapstone@gmail.com' . "\r\n";

if ($conn->query($sql6) === TRUE) {
	mail($to, $subject, $email_msg, $headers);
	header("Location: ../../../account/fm_account.php");
	exit;
} else {
	echo "Error: " . $sql6 . "<br>" . $conn->error;
}


?>