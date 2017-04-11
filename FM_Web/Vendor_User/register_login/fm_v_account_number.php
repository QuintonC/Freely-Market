<?php
session_start();
require_once("../../db_constant.php");

$con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL:" .mysqli_connect_error();
	}

$code = $_POST['code'];

//Prevent MySQL Injection
$code = strip_tags($code);

$code = stripslashes($code);

function accountNum(){
	echo '<script type="text/javascript"> alert("Invalid code."); location="fm_v_account_number.html";</script>';
}

$mysql = "select * from Vendor_Application where code = '$code' limit 1";
$result = $con->query($mysql); //Run $mysql string after connection is specified
$row = mysqli_fetch_array($result); //Fetches result from table row once established connection

$sql = "UPDATE Vendor_Application set code = 0 where code = '$code'";

$row_cnt = $result->num_rows;
if ($row_cnt > 0) {
	$result1 = $con->query($sql);
	header("Location: fm_v_create_account.html");	
} else {
	accountNum();
} 
?>