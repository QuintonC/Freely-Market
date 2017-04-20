<?php

session_start();
require_once("../../db_constant.php");

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//Check connection
if (mysqli_connect_error()) {
	echo "Failed to connect to MySQL:" .mysqli_connect_error();
	exit();
}

if (isset($_SESSION['loggedin']) and $_SESSION['loggedin'] == true) {
	$log = $_SESSION['username'];
} else {
	echo "Please log in to see this page.";
}

$username = $_SESSION['username'];

//Select Advertisements
$sql1 = "select * from Advertisements WHERE username='$username' LIMIT 2";
$data = $conn->query($sql1);

//Select User Photo
$sql2 = "select * from User_Accounts WHERE username ='$username'";
$user = $conn->query($sql2);

#Get number of notifications
$sql3 = "select count(*) from Notifications where recipient = '$username'";
$num = $conn->query($sql3);
$set = mysqli_fetch_array($num);
$number = $set['count(*)'];

$sql4 = "select count(*) from Msg_Notifications where recipient = '$username'";
$dig = $conn->query($sql4);
$set = mysqli_fetch_array($dig);
$digit = $set['count(*)'];

//Current Date
$currentdate = date("Y-m-d");

//Advertisement expiration date
$sql5 = "SELECT expirdate FROM Advertisements where expirdate='$currentdate'";
$expirdate = $conn->query($sql5);
$row_arr = mysqli_fetch_array($expirdate);

$sql6 = "DELETE FROM Advertisements WHERE expirdate = '$currentdate'";
$delete = $conn->query($sql6);

if($row_arr == $currentdate){
	$delete;
}
?>

<!doctype html>
<html>
<head>

<meta charset="utf-8">
<link rel="shortcut icon" href="../../images/favicon.ico" type="image/x-icon" />
<title>Create Advertisement</title>

<script type="text/javascript">

<!-- Ensures forms are not blank -->
    function blank()
    {
    var a=document.forms["advertisement"]["title"].value;
	var b=document.forms["advertisement"]["picture"].value;
	var c=document.forms["advertisement"]["descr"].value;
	var d=document.forms["advertisement"]["expir"].value;
    if (a==null || a=="",b==null || b=="",c==null || c=="",d==null || d=="")
      {
      alert("Please complete all required fields");
      return false;
      }
    }

	function maxLength(char) {    
    if (!('maxLength' in char)) {
        var max = char.attributes.maxLength.value;
        char.onkeypress = function () {
            if (this.value.length >= max) return false;
        };
    }
}

maxLength(document.getElementById("text"));
</script>
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
    background-color: black;
}

a:link {
    color: green;
}

a:visited {
    color: white;
}

a:hover {
    color: #13A61B;
}


.active {
    background-color: #4CAF50;
}

.title {
	margin: auto;
	width: 100%;
	height: 15%;
	background-color: #13A61B;
	position: absolute;
}


.title .header {
	width: 100%;
	position: absolute;
	height: 15%;
	font-family: "Brush Script MT", cursive;
	font-size: 24px;
}

.title .search {
	left: 1%;
	width: 10%;
	height: 15%;
	position: absolute;
}


.title .login{
	font-family: Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, "sans-serif";
	border-radius: 5px;
	position: inherit;
	top: 15%;
	right: 1%;
	font-size: 14px;
	border-style: solid;
	border-color: white;
}

.title .navbar{
	left: 85%;
	position: relative;
	font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, "sans-serif";
}

.leftsidebar {
	font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, "sans-serif";
	top: 15%;
	width: 15%;
	height: 100%;
	position: absolute;
	border-top: solid;
	border-top-color: white;
	background-color: #333;
	padding-top: 8px;
}
	
.leftsidebar .menu ul {
	font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, "sans-serif";
	list-style-type: none;
	background-color: #13A61B;
	margin: 0px;
	padding: 0px;
}
	
.leftsidebar .menu li {
	float: none;
	border: 1px solid #bbb;
	border:solid;
	border-color: #333;
	border-radius: 1px;
}
	
.leftsidebar .menu li a {
	display: block;
	color: white;
	font-size: 14px;
	text-align: center;
	padding: 14px 16px;
	text-decoration: none;
}
	
.leftsidebar .img-circle {
	border-radius: 50%;
	border-style: solid;
	border-color: white;
	width: 150px;
	height: 130px;
}

.leftsidebar .menu li a:hover {
    background-color: black;
    color: white;
}
.leftsidebar .menu .active {
    background-color: #4CAF50;
    color: white;
}

.rightsidebar {
	font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, "sans-serif";
	width: 15%;
	height: 100%;
	right: 0%;
	position: absolute;
	border-top: thick;
	border-top-color: white;
	background-color: #333;
	top: 15%;
	color: white;
}
	
.rightsidebar ul {
	margin: 0px;
	padding: 2px;
}
	
.rightsidebar li {
	float: none;
	border: 1px solid #bbb;
}
	
.rightsidebar li a {
	display: block;
	color: white;
	font-size: 14px;
	text-align: center;
	padding: 14px 16px;
	text-decoration: none;
}

.center1 {
	margin: auto;
	text-align: left;
	padding: 3px;
	left: 15%;
	font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, "sans-serif";
	width: 70%;
	height: 100%;
	background-color: #ffe6e6;
	position: absolute;
	top: 15%;
	border-top: solid;
	border-color: white;
}
	
.center1 ul {
	list-style-type: none;
	margin: 0px;
	padding: 0px;
}
	
.center1 li {
	float: none;
	border: 1px solid #bbb;
}
	
.center1 li a {
	display: block;
	color: white;
	font-size: 14px;
	text-align:left;
	padding: 14px 16px;
	text-decoration: none;
}


.footer {
	font-family: Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, "sans-serif";
	margin: auto;
	width: 100%;
	height: 25%;
	border-top: solid;
	border-color: white;
	background-color: #000000;
	color: #FFFAF0;
	position: absolute;
	top: 105%;
}

.footer ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #000000;
}

.footer li {
    width: 33%;
	text-align: justify;
	border: none !important;
}

.footer li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}
	
</style>
</head>

<body

<!-- Block 1 -->
<div class = "title">
	<div class = "search">
		<h3 align="center"><a href="../../fm_homepage.html"><img src = "../../images/logo.png" height = "90px" width = "160px" /></a></h3>
	</div>

	<div class = "header">
		<h1 align="center">Freely Market</h1>
	</div>

	<div class = "login">
		<ul>
			<li><a href = "../../listings/fm_listings.php">Listings</a></li>
			<li><a href = "../../transactions/fm_transactions.php">Transactions</a></li>
			<li><a href="../fm_account.php"  class = "active">My Account</a></li>
			<li><a href = "../../fm_homepage.html">Logged In: <?php echo $log; ?></a></li>
		</ul>
	</div>
</div>

<!-- Left Sidebar -->
<div class="leftsidebar"> 
	<?php while ($row = mysqli_fetch_array($user)) { ?>
	<table align="center">
			<tr>
				<td align="center"><img class="img-circle" src="../../images/<?php echo $row['picture'];?>"/></td>
			</tr>
	</table>
	<?php } ?>
	<font color="white"><h2 align="center"> <?php echo $log; ?></h2></font>
	<div class = "menu">
	<ul>
		<li><a href = "../../account/edit_account/fm_edit_account.php">Edit Account</a></li>
		<li><a href = "../../account/edit_card/fm_edit_card.php">Edit Card Info</a></li>
		<li><a href = "../../account/messager/fm_messager1.php">Messager <div class = "dig"><?php if ($digit != 0) { echo $digit;}?></div></a></li>
		<li><a href = "../../account/notifications/fm_notifications.php">Notifications <div class = "num"><?php if ($number != 0) { echo $number;}?></div></a></li>
		<li><a href = "../../account/report_issue/fm_issue_form.php">Report an Issue</a></li>
		<?php if ($check['typ'] == 2 ): ?>
			<span><li><a href = '../admin/fm_admin_vendor_requests.php'>Vendor Requests</a></li>
		<?php endif;?>
		<?php if ($check['typ'] == 2 ): ?>
			<span><li><a href = '../admin/fm_admin_view_users.php'>View Users</a></li>
		<?php endif;?>
		<?php if ($check['typ'] == 2 ): ?>
			<span><li><a href = '../admin/fm_admin_view_issues.php'>View Issues</a></li>
		<?php endif;?>
		<li><a href = 'fm_v_create_advertisement1.php'>Advertisements</a></li>
	</ul>
</div>
</div>
<!-- End Left Sidebar -->

<!-- Right Sidebar -->
<div class="rightsidebar">
	<h3 align="center">Current</h3>
	<?php while ($row = mysqli_fetch_array($data)) { ?>
		<table align="center">
			<tr>
				<td><center><?php echo $row['title'];?></center><br><img src = "../../images/<?php echo $row['file'];?>" height = '150x' width = '150px' /><br><center>Expires: <?php echo $row['expirdate'];?></center></td><hr>
			</tr>
		</table>
	<?php } ?>
</div>
<!-- End Right Sidebar -->

<!-- Center Page -->
<div class="center1">
	<h2 align="center">Advertisements</h2>
	<form name="advertisement" action="fm_v_create_advertisement.php" method="post" enctype="multipart/form-data" onsubmit = "return blank()">
		<p>Title <br><br> <input type="text" id="title" name="title"></p> 
		<p>Upload file <br>
			<br>
			<input type="file" class="button" id = "picture" name="picture" accept="image/gif, image/jpeg, image/png">
		</p>
		<p>Description <br>
			<textarea cols="120" rows="6" id="descr" name="descr" maxlength="300"></textarea>
		</p>
		<p>Expiration Date <br><br> <input type="date" id="expir" name="expir"></p>
		<br>
		<button type="submit" class="button" name="Submit" style= "float:right">Post Advertisement</button>
	</form>
</div>
<!-- End Center Page -->

<!-- Footer -->
<div class = "footer">
	<ul>
		<li><a href = "">Privacy Policy</a></li>
		<li><a href = "">About</a></li>
		<li><a href = "">Contact</a></li>
	</ul>
</div>
<!-- End Footer -->
</body>
</html>

</html>
