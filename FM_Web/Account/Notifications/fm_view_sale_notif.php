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
		exit();
	}

#Get id from url
$bid = $_GET['id'];
#Diaplay the Listing
$mysql = "select * from Buy_Listing where bid = '$bid'";
$result = $conn->query($mysql);

#Display accounts of offers made for listing
$sql1 = "select psid, username from Pending_Sale where bid = '$bid'";
$records = $conn->query($sql1);

#Delete Notification
$sql2 = "delete from Notifications where bid = '$bid'";
$conn->query($sql2);

?>

<html>

<head>

<style>

body {
padding: 0px;
margin: 0px;
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
height: 800px;
left: 0%;
width: 15%;
background-color: #808080;
}

.center {
position: absolute;
height: 800px;
left: 15%;
width: 70%;
background-image: url("tree.jpg");
}

.center .listing {
position: absolute;
top: 50px;
left: 200px;
height: 200px;
width: 500px;
background-color: #FFFFFF;
border-style: solid;
border-width: 2px;
padding: 15px;
position: absolute;
overflow: scroll;
}

.center .offers {
position: absolute;
top: 350px;
left: 200px;
height: 300px;
width: 500px;
background-color: #FFFFFF;
border-style: solid;
border-width: 2px;
padding: 15px;
position: absolute;
overflow: scroll;
}

.rightsidebar {
position: absolute;
height: 800px;
left: 85%;
width: 15%;
background-color: #808080;
}

.footer {
margin: auto;
width: 100%;
background-color: #000000;
color: #FFFAF0;
position: absolute;
top: 950px;
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

	<title>View Offers Page</title>
	
</head>

<body>

<!-- Block 1 -->
<div class = "title">

<div class = "search">
<img src = "logo.png" height = "100px" width = "200px" /><br />
<input type="text" name="search" placeholder="Search..">
</div>

<div class = "header">
<h1>View Offers</h1>
</div>

<div class = "navbar">

<ul>
<li><a href = "fm_listings.php">Listings</a></li>
<li><a href="fm_account.php"  class = "active">My Account</a></li>
<li><a href = "fm_transactions.php">Transactions</a></li>
<li><a href = 'fm_homepage.html'>Logged In: <?php echo $log; ?></a></li>
</ul>

</div>


</div>

<!-- Block 2 -->
<div class = "leftsidebar">



</div>

<!-- Block 3 -->
<div class = "center">

<div class = "listing">
<center><h3>Listing</h3></center>
<table>
	<tr>
		<th>Item</th>
		<th>Price</th>
		<th>Description</th>
		<th>Picture</th>
	</tr>
	<?php while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><?php echo $row['item']; ?></td>
		<td><?php echo $row['price']; ?></td>
		<td><?php echo $row['descr']; ?></td> 
		<td><?php echo $row['picture']; ?></td> 
	</tr>
	<?php } ?>
</table>
</div>

<div class = "offers">
<center><h3>Offers</h3></center>
<table>
	<?php while ($set = mysqli_fetch_array($records)) { ?>
	<tr>
		<td><?php echo $set['psid']; ?></td>
		<td><?php echo $set['username']; ?></td>
		<td><a href = "fm_accept_buyoffers.php?id=<?php echo $set['psid']; ?>">Accept</a></td>
		<td><a href = "fm_reject_buyoffers.php?id=<?php echo $set['psid']; ?>">Reject</a></td>
	</tr>
	<?php } ?>
</table>
</div>

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