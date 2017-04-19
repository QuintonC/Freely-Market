<?php
session_start();
require_once("../db_constant.php");

if (isset($_SESSION['loggedin']) and $_SESSION['loggedin'] == true) {
    $log = $_SESSION['username'];
} else {
    echo "Please log in first to see this page.";
}	
	
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}
	
#Get ID from url
$iid = $_GET['id'];


$sql = "Update Issue Set status = 'inactive' where iid = '$iid'";

if ($conn->query($sql) === TRUE) {
	header("Location: fm_admin_view_issues.php");
	exit;
} else {
	echo "Error: " . $esql . "<br>" . $conn->error;
}


?>