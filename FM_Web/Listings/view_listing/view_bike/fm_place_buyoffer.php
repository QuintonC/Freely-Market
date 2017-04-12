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
$bid = $_GET['id'];

#Create offer for sale
$mysql = "insert into Pending_Sale (username,bid) values ('$username','$bid')";
$conn->query($mysql);

$type = "buybid";
$date = date("Y-m-d H:i:s");

$sql = "select psid from Pending_Sale where username = '$username' and bid = '$bid' limit 1";
$content = $conn->query($sql);
$row = mysqli_fetch_array($content);
$psid = $row['psid'];

#Get Item Name
$sql1 = "select item from Buy_Listing where bid = '$bid'";
$cont = $conn->query($sql1);
$dat = mysqli_fetch_array($cont);
$item = $dat['item'];

$message = "You have recieved an offer for " . $item . "!";
$email_msg = "You have recieved an offer for " . $item . " from " . $username . "!<br /><a href = 'http://cgi.soic.indiana.edu/~team12/register_login/fm_login.html'>Freely Market</a>";

$sql2 = "SELECT u.username, u.email FROM User_Accounts AS u, Buy_Listing AS b, Pending_Sale AS p WHERE p.bid = b.bid AND b.aid = u.aid AND psid = '$psid'";
$data = $conn->query($sql2);
$set = mysqli_fetch_array($data);
$seller = $set['username'];
$email = $set['email'];

#Email Confirmation Message
$to = $email;
$subject = 'Offer Recieved';

//Set content-type
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

//More headers
$headers .= 'From: freelycreativecapstone@gmail.com' . "\r\n";


#Create notification
$sql3 = "INSERT INTO Notifications(message,recipient,sender,types,created,bid) VALUES('$message','$seller','$username','$type','$date','$bid')";
if ($conn->query($sql3) === TRUE) {
	mail($to, $subject, $email_msg, $headers);
	header("Location: ../../../account/fm_account.php");
	exit;
} else {
	echo "Error: " . $sql3 . "<br>" . $conn->error;
}
?>