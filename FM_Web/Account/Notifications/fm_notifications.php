<?php

session_start();
require_once("db_constant.php");

if (isset($_SESSION['loggedin']) and $_SESSION['loggedin'] == true) {
     $log = $_SESSION['username'];
} else {
    echo "Please log in first to see this page.";
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
	}
	
$sender = $_SESSION['username'];

$mysql = "select * from Notifications where recipient = '$sender'";
$result = $conn->query($mysql);


	
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

.center {
position: absolute;
height: 450px;
left: 15%;
width: 85%;
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

	<title>Notifications Page</title>
	
</head>

<body>

<!-- Block 1 -->
<div class = "title">

<div class = "search">
<img src = "logo.png" height = "100px" width = "200px" /><br />
<input type="text" name="search" placeholder="Search..">
</div>

<div class = "header">
<h1>Notifications</h1>
</div>

<div class = "navbar">

<ul>
<li><a href = "fm_listings.php">Listings</a></li>
<li><a href="fm_account.php" class = "active">My Account</a></li>
<li><a href = "fm_transactions.php">Transactions</a></li>
<li><a href = "fm_homepage.html">Logged In: <?php echo $log; ?></a></li>
</ul>

</div>


</div>

<!-- Block 2 -->
<div class = "leftsidebar">

<div class = "menu">
<ul>
<li><a href = "fm_edit_account.php">Edit Account</a></li>
<li><a href = "fm_edit_card.php">Edit Card Info</a></li>
<li><a href = "fm_messager1.php">Messager</a></li>
<li><a href = "fm_notifications.php">Notifications <div class = "num"><?php if ($number != 0) { echo $number;}?></div></a></li>
<li><a href = 'fm_admin_vendor_requests.php'>Vendor Requests</a></li>
<?php if ($adminCheck['admin'] == "y"): ?>
	<span><li><a href = "fm_messager1.php">Messager</a></li></span>
<?php endif;?>
</ul>
</div>

</div>

<!-- Block 3 -->
<div class = "center">

<table>
	<tr>
		<th>Message</th>
		<th>From</th>
		<th>Date/Time</th>
		<th>View</th>
		<th>Delete</th>
	</tr>
<?php while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><?php echo $row['message']; ?></td>
		<td><?php echo $row['sender']; ?></td>
		<td><?php echo $row['created']; ?></td>
		<td><a href = "fm_view_msg_notif.php?id=<?php echo $row['msgid'] . $row['types'];?>"><?php echo $row['msgid'];?></a></td>
		<td><a href = "fm_delete_msg_notif.php?id=<?php echo $row['msgid'] . $row['types'];?>"><?php echo $row['msgid'];?></a></td>
		<td><a href = "fm_view_sale_notif.php?id=<?php echo $row['bid'] . $row['types'];?>"><?php echo $row['bid'];?></a></td>
		<td><a href = "fm_delete_sale_notif.php?id=<?php echo $row['bid'] . $row['types'];?>"><?php echo $row['bid'];?></a></td>
		<td><a href = "fm_view_rent_notif.php?id=<?php echo $row['rid'] . $row['types'];?>"><?php echo $row['rid'];?></a></td>
		<td><a href = "fm_delete_rent_notif.php?id=<?php echo $row['rid'] . $row['types'];?>"><?php echo $row['rid'];?></a></td>
		<td><a href = "fm_saleoffer_status_accept.php?id=<?php echo $row['tid'] . $row['types'];?>"><?php echo $row['tid'];?></a></td>
		<td><a href = "fm_rentoffer_status_accept.php?id=<?php echo $row['tid'] . $row['types'];?>"><?php echo $row['tid'];?></a></td>
		<td><a href = "fm_saleoffer_status_reject.php?id=<?php echo $row['bid'] . $row['types'];?>"><?php echo $row['bid'];?></a></td>
		<td><a href = "fm_rentoffer_status_reject.php?id=<?php echo $row['rid'] . $row['types'];?>"><?php echo $row['rid'];?></a></td>
	</tr>
<?php } ?>
</table>

</div>

<!-- Block 4 -->
<div class = "rightsidebar">

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