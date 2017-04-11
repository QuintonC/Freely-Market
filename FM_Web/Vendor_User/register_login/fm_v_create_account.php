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

//Assign variables
$username = $_POST['username'];
$password = $_POST['password'];
$fname = $_POST['first_name'];
$lname = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

//MySQL Injection
$username = strip_tags($username);
$password = strip_tags($password);
$fname = strip_tags($fname);
$lname = strip_tags($lname);
$email = strip_tags($email);
$phone = strip_tags($phone);

$username = stripslashes($username);
$password = stripslashes($password);
$fname = stripslashes($fname);
$lname = stripslashes($lname);
$email = stripslashes($email);
$phone = stripslashes($phone);

$date = date("Y-m-d H:i:s");

$encpw = password_hash($password, PASSWORD_BCRYPT);

$type = 1;
$active = 0;

function userName(){
	echo '<script type="text/javascript"> alert("Username already exists."); location="fm_v_create_account.html";</script>';
}

$sql1 = "select * from User_Accounts where username = '$username' limit 1";
$content = $conn->query($sql1);
$count = mysqli_num_rows($content);

$sql2 = "INSERT INTO User_Accounts(username, password, first_name, last_name, email, phone, typ, picture, active, created) VALUES ('$username', '$encpw', '$fname', '$lname', '$email', '$phone', '$type', '$name', '$active', '$date')";

if (!$count > 0) {
	$conn->query($sql2);
	header("Location: ../../account/fm_account.php");
} else {
	userName();
}

$mysql = "select aid from User_Accounts where username = '$username'";
$result = $conn->query($mysql);
$row = mysqli_fetch_array($result);
$id = $row['aid'];
$_SESSION['aid'] = $id;

?>