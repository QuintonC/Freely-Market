<?php

session_start();
require_once("../../db_constant.php");

if (isset($_SESSION['loggedin']) and $_SESSION['loggedin'] == true) {
     $log = $_SESSION['username'];
} else {
    echo "Please log in first to see this page.";
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
	}


$sender = $_SESSION['username'];
#Get from entries
$reciever = $_GET['reciever'];
$message = $_GET['message'];
#Today's date
$date = date("Y-m-d H:i:s");

$type = "You have recieved a message";

$sql = "select count(*) from User_Accounts where username = '$reciever'";
$num = $conn->query($sql);
$set = mysqli_fetch_array($num);
$number = $set['count(*)'];

#Creates Message
$mysql = "INSERT INTO Messages(sender,reciever,message,recieved) VALUES('$sender','$reciever','$message','$date')";

if ($number != 0) {
	$conn->query($mysql);
} 

#Gets message ids
$sql2 = "select msgid from Messages where sender = '$sender' and reciever = '$reciever' order by recieved desc limit 1";
$data = $conn->query($sql2);
$tup = mysqli_fetch_array($data);
$msgid = $tup['msgid'];

$message = 'You have recieved a message from ' . $sender . '!';

#Create Notification
$sql3 = "INSERT INTO Msg_Notifications(message,recipient,sender,created,msgid) VALUES('$message','$reciever','$sender','$date','$msgid')";

if ($number != 0) {
	$conn->query($sql3);
	header("Location: fm_conversation.php?contact=" . $reciever);
	exit;
} else {
	echo "User does not exist!";
	exit;
}


?>

