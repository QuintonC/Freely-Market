<?php

session_start();
require_once("db_constant.php");


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}


$cname = $_POST['cname'];
$cnum = $_POST['cnum'];
$expr = $_POST['expr'];
$cvv = $_POST['cvv'];
$aid = $_SESSION['uid'];

#Prevent MySQL Injection
$name = strip_tags($name);
$number = strip_tags($number);
$expr = strip_tags($expr);
$cvv = strip_tags($cvv);

$name = stripslashes($name);
$number = stripslashes($number);
$expr = stripslashes($expr);
$cvv = stripslashes($cvv);

$sql = "update CardInfo set card_name = '$cname', card_number = '$cnum', expr = '$expr', cvv = '$cvv' where aid = '$aid'";

if ($conn->query($sql) === TRUE) {
	echo "New record updated successfully";
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}

header("Location: fm_account.php");
exit;
?>