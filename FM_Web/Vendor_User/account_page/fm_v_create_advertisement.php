<?php
session_start();
require_once("../../db_constant.php");

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//Check connection
if (mysqli_connect_error()) {
	echo "Failed to connect to MySQL:" .mysqli_connect_error();
	exit();
}

$name = $_FILES['picture']['name'];
$temp_name = $_FILES['picture']['tmp_name'];
$size = $_FILES['picture']['size'];
$type = $_FILES['picture']['type'];

if ($size <= 5000000) {
	move_uploaded_file($temp_name,'../../images/' . $name);
} else {
	echo 'The file is too large';
	echo 'The file is ' . $size . ' and needs to be less than 500KB';
}

$username = $_SESSION['username'];

//Assign variables
$title = $_POST['title'];
$descr = $_POST['descr'];
$expir = $_POST['expir'];
$aid = $_SESSION['uid'];

//MySQL Injection
$title = strip_tags($title);
$descr = strip_tags($descr);

$title = stripslashes($title);
$descr = stripslashes($descr);

$status = "Active";

$mysql = "select aid from User_Accounts where username = '$log' limit 1";
$result = $conn->query($mysql); //Run $mysql string after connection is specified
$row = mysqli_fetch_array($result); //Fetches result from table row once established connection
$id = $row['aid']; //Establishes the id number of the account from the vid number retrieved from the table

function advSubmit(){
	echo '<script type="text/javascript"> alert("Your advertisement has been posted."); location="fm_v_create_advertisement1.php";</script>';
}

//Todays date
$createdate = date("Y-m-d");

//Insert into the table
$sql = "INSERT INTO Advertisements (username, title, description, file, createdate, expirdate) VALUES ('$username','$title', '$descr', '$name', '$createdate', '$expir')";

if ($conn->query($sql) === TRUE) {
	advSubmit();
} else {
	echo '<script type="text/javascript"> alert("Something went wrong!")</script>';
}

mysqli_close($conn);

?>