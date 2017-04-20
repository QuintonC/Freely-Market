<?php
session_start();
require_once("../db_constant.php");
#If logged in the username of the account will be displayed in the top right corner
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
#Call session variables
$username = $_SESSION['username'];
$aid = $_SESSION['uid'];

#Check if user is admin
$adminsql = "select typ from User_Accounts where aid = '$aid'";
$adminCheck = $conn->query($adminsql);
$check = mysqli_fetch_array($adminCheck);

#Get number of notifications
$sql3 = "select count(*) from Notifications where recipient = '$username'";
$num = $conn->query($sql3);
$set = mysqli_fetch_array($num);
$number = $set['count(*)'];

$sql4 = "select count(*) from Msg_Notifications where recipient = '$username'";
$dig = $conn->query($sql4);
$set = mysqli_fetch_array($dig);
$digit = $set['count(*)'];
?>

<html>

<head>

<style>
body {
padding: 0px;
margin: 0px;
}
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}
li {
    float: left;
	border-right: 1px solid #bbb;
}
li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}
li a:hover {
    background-color: 	#00008B;
}
.active {
    background-color: 	#00008B;
}
.title .navbar ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}
.title .navbar li {
    float: left;
	border-right: 1px solid #bbb;
}
.title .navbar li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}
.title .navbar li a:hover {
    background-color: 	#00008B;
}
.title .navbar .active {
    background-color: 	#00008B;
}
table, th, td {
	margin-left: auto;
	margin-right: auto;
	border-bottom: 1px solid #ddd;
	padding: 15px;
    text-align: left;
}
th {
    background-color: 	#00008B;
    color: white;
}
tr:nth-child(even) {
	background-color: #f2f2f2;
}
tr:hover {
	background-color: #f5f5f5;
}
.title {
margin: auto;
width: 100%;
height: 150px;
background-color: #ff4d4d;
}
.title .header {
top: 10px;
left: 42%;
position: absolute;
font-family: "Brush Script MT", cursive;
font-size: 24px;
}
.title .search {
top: 15px;
left: 2%;
position: absolute;
}
.title .navbar{
top: 15px;
right: 2%;
position: absolute;
font-family: Arial, Helvetica, sans-serif;
}
.leftsidebar {
position: absolute;
height: 450px;
left: 0%;
width: 15%;
background-color: #808080;
}
.leftsidebar .menu ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: 200px;
    background-color: #f1f1f1;
}
.leftsidebar .menu li a {
   display: block;
   color: #000;
   padding: 8px 16px;
   text-decoration: none;
   width: 200px;
   text-align: left;
}
.leftsidebar .menu li a:hover {
    background-color: #555;
    color: white;
}
.leftsidebar .menu li a:hover {
    background-color: #555;
    color: white;
}
.leftsidebar .menu .active {
    background-color: #4CAF50;
    color: white;
}

.num {
	color: red;
}

.dig {
	color: red;
}

.center {
position: absolute;
height: 450px;
left: 15%;
width: 85%;
text-align: center;
background-image: url("../images/shop.jpg");
}

.center ul {
	list-style-type: none;
    margin: 0;
    padding: 0;
	background-color: #333;
}

.center li {
	display: block;
    text-decoration: none;
	width: 100%;
    text-align: left;
	color: white;
}

.footer {
margin: auto;
width: 100%;
background-color: #000000;
color: #FFFAF0;
position: absolute;
top: 600px;
}
.footer ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}
.footer li {
    float: right;
	border-right: 1px solid #bbb;
}
.footer li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}
</style>

	<title>Account Page</title>
	
</head>

<body>

<!-- Block 1 -->
<div class = "title">

<div class = "search">
<img src = "../images/logo.png" height = "100px" width = "200px" /><br />
<input type="text" name="search" placeholder="Search..">
</div>

<div class = "header">
<h1>Account</h1>
</div>

<div class = "navbar">
<ul>
<li><a href = "../listings/fm_listings.php">Listings</a></li>
<li><a href="../account/fm_account.php"  class = "active">My Account</a></li>
<li><a href = "../transactions/fm_transactions.php">Transactions</a></li>
<li><a href = "../fm_homepage.html">Logged In: <?php echo $log; ?></a></li>
</ul>
</div>


</div>

<!-- Block 2 -->
<div class = "leftsidebar">

<div class = "menu">
<ul>
	<li><a href = "edit_account/fm_edit_account.php">Edit Account</a></li>
		<li><a href = "messager/fm_messager1.php">Messager <div class = "dig"><?php if ($digit != 0) { echo $digit;}?></div></a></li>
		<li><a href = "notifications/fm_notifications.php">Notifications <div class = "num"><?php if ($number != 0) { echo $number;}?></div></a></li>
		<li><a href = "report_issue/fm_issue_form.php">Report an Issue</a></li>
		<?php if ($check['typ'] == 2 ): ?>
			<span><li><a href = '../admin/fm_admin_vendor_requests.php'>Vendor Requests</a></li>
		<?php endif;?>
		<?php if ($check['typ'] == 2 ): ?>
			<span><li><a href = '../admin/fm_admin_view_users.php'>View Users</a></li>
		<?php endif;?>
		<?php if ($check['typ'] == 2 ): ?>
			<span><li><a href = '../admin/fm_admin_view_issues.php'>View Issues</a></li>
		<?php endif;?>
		<?php if ($check['typ'] == 1 ): ?>
			<span><li><a href = '../vendor/account_page/fm_v_create_advertisement1.php'>Advertisements</a></li>
		<?php endif;?>
		<?php if ($check['typ'] == 2 ): ?>
			<span><li><a href = '../admin/fm_admin_ad_requests.php'>Ad Requests</a></li>
		<?php endif;?>
</ul>
</div>


</div>

<!-- Block 3 -->
<div class = "center">

<ul>
<li><a href = "account_listings/fm_my_listed_bikes.php?pagenum=1">My Listed Bikes</a></li>
<li><a href = "account_listings/fm_my_listed_equipment.php?pagenum=1">My Listed Equipment</a></li>
<li><a href = "account_listings/fm_my_listed_rentals.php?pagenum=1">My Listed Rentals</a></li>
</ul>

</div>

<!-- Block 4 -->
<div class = "footer">

<ul>
<li><a href = "">Privacy Policy</a></li>
<li><a href = "">About</a></li>
<li><a href = "">Contact</a></li>
<li style = "float:left"><a href = "">Social Links</a></li>
</ul>

</div>

</body>
</html>