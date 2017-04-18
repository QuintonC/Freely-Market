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

#Call session variables
$username = $_SESSION['username'];
$aid = $_SESSION['uid'];

#Show Vendor Requests 
$sql = "SELECT * FROM Vendor_Application WHERE confirmed != 'confirmed' AND confirmed != 'denied'";
$result = $conn->query($sql);

$confirm = "Select confirmed FROM Vendor_Application";
$con = $conn ->query($confirm);
$array = mysqli_fetch_array($con);

#Check if user is admin
$adminsql = "select typ from User_Accounts where aid = '$aid'";
$adminCheck = $conn->query($adminsql);
$check = mysqli_fetch_array($adminCheck);


?>

<html>
<header>
<style>
body {
padding: 0px;
margin: 0px;
}

.active {
    background-color: 	#00008B;
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

.links {
	background-color: #333;
}

.links a:hover {
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

.num {
	color: red;
}
.center {
position: absolute;
height: 450px;
width: 99%;
text-align: center;
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



.navMenu {
	margin:0;
	padding:0;
}	
.navMenu ul {
	margin:0;
	padding:0;
	line-height:15px;
}	
	
.navMenu li {
	margin:0;
	padding:0;
	list-style:none;
	float:left;
	position:relative;
}

.navMenu > ul > li:hover {
	background-color: #f5f5f5;
}	
.navMenu > ul > li {
	text-align:center;
	text-decoration:none;
	display:block;
	color:#000;
	position:relative;
}	

ul.sub-menu {
	position:absolute;
	opacity:0;
}
ul.sub-menu li{
	
}
	
.navMenu ul ul {
	position:absolute;
	visibility:hidden;
}
	
.navClass li:hover .sub-menu{
	opacity:1;
}
</style>
</header>
<title>Vendor Requests</title>
<body>
<!-- Block 1 -->
<div class = "title">

<div class = "search">
<img src = "../images/logo.png" height = "100px" width = "200px" /><br />
<input type="text" name="search" placeholder="Search..">
</div>

<div class = "header">
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

<div class = "vendor_requests">
<center><h2>Vendor Requests</h2></center>
<table>
	<tr>
		<th>Application ID</th>
		<th>Vendor</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Street</th>
		<th>City</th>
		<th>State</th>
		<th>Zipcode</th>
		<th>Email</th>
		<th>Years In Business</th>
		<th>Why Did You Join</th>
		<th>Future Projects</th>
		<th>Deny Request</th>
		<th>Accept Request</th>
	</tr>
	<?php while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><?php echo $row['appid']; ?></td>
		<td><?php echo $row['vendor']; ?></td>
		<td><?php echo $row['fname']; ?></td>
		<td><?php echo $row['lname']; ?></td>
		<td><?php echo $row['street']; ?></td>
		<td><?php echo $row['city']; ?></td>
		<td><?php echo $row['state']; ?></td>
		<td><?php echo $row['zipcode']; ?></td>
		<td><?php echo $row['email']; ?></td>
		<td><?php echo $row['years']; ?></td>
		<td><?php echo $row['whyjoin']; ?></td>
		<td><?php echo $row['futureproj']; ?></td>
		
		<div class = "links">
		
		<td><a href = "fm_delete_vendor_request.php?id=<?php echo $row['appid']; ?>"><?php echo "Deny Request";?></a></td>
		<td><a href = "fm_accept_vendor_request.php?id=<?php echo $row['appid']; ?>"><?php echo "Accept Request";?></a></td>
		
		</div>
	</tr>
	<?php } ?>
</table>
</div>
</body>
</html>