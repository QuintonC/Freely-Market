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

#Get from url
$rid = $_GET['id'];

$reason = $_POST['reason'];
$duration = $_POST['duration'];
$destination = $_POST['destination'];

#Create offer for rental
$mysql = "insert into Pending_Rental (username,reason,duration,destination,rid) values ('$username','$reason','$duration','$destination','$rid')";
$conn->query($mysql);

$type = "rentalbid";
$date = date("Y-m-d H:i:s");

$sql = "select prid from Pending_Rental where username = '$username' and rid = '$rid' limit 1";
$content = $conn->query($sql);
$row = mysqli_fetch_array($content);
$prid = $row['prid'];

#Get Item Name
$sql1 = "select item from Rental_Listing where rid = '$rid'";
$cont = $conn->query($sql1);
$dat = mysqli_fetch_array($cont);
$item = $dat['item'];

$message = "You have recieved an offer for " . $item . "!";
$email_msg = "You have recieved an offer for " . $item . " from " . $username . "!<br /><a href = 'http://cgi.soic.indiana.edu/~team12/register_login/fm_login.html'>Freely Market</a>";

$sql2 = "SELECT u.username, u.email FROM User_Accounts AS u, Rental_Listing AS r, Pending_Rental AS p WHERE p.rid = r.rid AND r.aid = u.aid AND prid = '$prid'";
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
$sql3 = "INSERT INTO Notifications(message,recipient,sender,types,created,rid) VALUES('$message','$seller','$username','$type','$date','$rid')";

if ($conn->query($sql3) === TRUE) {
	mail($to, $subject, $email_msg, $headers);
	header("Location: ../../../account/fm_account.php");
	exit;
} else {
	echo "Error: " . $sql3 . "<br>" . $conn->error;
}
?>