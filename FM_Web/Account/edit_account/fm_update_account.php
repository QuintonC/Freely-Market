<?php

session_start();
require_once("../../db_constant.php");


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}


$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$picture = $_POST['picture'];
$aid = $_SESSION['uid'];



$sql = "update User_Accounts set first_name = '$fname', last_name = '$lname', email = '$email', phone = '$phone', picture = '$picture' where aid = '$aid'";

if ($conn->query($sql) === TRUE) {
	echo "New record updated successfully";
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}

header("Location: ../fm_account.php");
exit;
?>