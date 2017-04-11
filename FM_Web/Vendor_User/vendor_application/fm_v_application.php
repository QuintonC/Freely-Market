<?php
session_start();
require_once("../../db_constant.php");

$con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL:" .mysqli_connect_error();
	}

//Assign variables
$vendor = $_POST['vendor'];
$first = $_POST['fname'];
$middle = $_POST['mname'];
$last = $_POST['lname'];
$email = $_POST['email'];
$street = $_POST['street'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$years = $_POST['years'];
$whyjoin = $_POST['whyjoin'];
$futureproj = $_POST['futureproj'];

//MySQL Injection
$vendor = strip_tags($vendor);
$first = strip_tags($first);
$middle = strip_tags($middle);
$last = strip_tags($last);
$email = strip_tags($email);
$street = strip_tags($street);
$city = strip_tags($city);
$state = strip_tags($state);
$zip = strip_tags($zip);
$years = strip_tags($years);
$whyjoin = strip_tags($whyjoin);
$futureproj = strip_tags($futureproj);

$vendor = stripslashes($vendor);
$first = stripslashes($first);
$middle = stripslashes($middle);
$last = stripslashes($last);
$email = stripslashes($email);
$street = stripslashes($street);
$city = stripslashes($city);
$state = stripslashes($state);
$zip = stripslashes($zip);
$years = stripslashes($years);
$whyjoin = stripslashes($whyjoin);
$futureproj = stripslashes($futureproj);

$to = "$email";
$subject = "Application Comfirmation";
$msg = "
<html>
<head>
<title>Application Confirmation</title>
</head>
<body>
<p>Dear, $first</p>
<p>Thank you for applying to Freely Market!</p>
<p>We will be getting back to you shortly.</p></br>
<br/>
<p><i>Freely Market Team</i></p>
";

//Set content-type
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

//More headers
$headers .= 'From: freelycreativecapstone@gmail.com' . "\r\n";

$random = mt_rand(100000,999999);


//Insert into the table
$sql = "INSERT INTO Vendor_Application (vendor, fname, mname, lname, email, street, city, state, zip, years, whyjoin, futureproj, code) 
VALUES ('$vendor', '$first', '$middle', '$last', '$email', '$street', '$city', '$state', '$zip', '$years', '$whyjoin', '$futureproj', '$random')";

if ($con->query($sql) === TRUE) {
	header("Location: fm_v_submission.html");
	mail($to, $subject, $msg, $headers);
	exit;
} else {
	echo "Error: " . $sql . "<br>" . $con->error;
}

mysqli_close($con);

?>