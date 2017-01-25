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

#Display Account information of the account who posted the listing
$mysql = "SELECT u.username, u.first_name, u.last_name, u.email, u.phone FROM User_Accounts AS u, Buy_Listing AS b WHERE u.aid = b.aid AND b.bid = '$bid' limit 1";
$result = $conn->query($mysql);
#Display the Sale Listing
$sql = "select * from Buy_Listing where bid = '$bid' limit 1";
$content = $conn->query($sql);

?>

<html>

<head>

<title>View Sale Page</title>
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

.title .navbar{
top: 15px;
right: 2%;
position: absolute;
font-family: Arial, Helvetica, sans-serif;
}

.title .header {
top: 10px;
left: 42%;
position: absolute;
font-family: "Brush Script MT", cursive;
font-size: 24px;
}


.center {
position: absolute;
height: 370px;
left: 15%;
width: 70%;
top: 150px;
}


.center .form {
text-align: center;
}


.footer {
margin: auto;
width: 100%;
background-color: #000000;
color: #FFFAF0;
position: absolute;
top: 610px;
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
<head>
	<title>View Sale Page</title>
</head>

<body>


<!-- Block 1 -->
<div class = "title">

<div class = "navbar">

<ul>
<li><a href = "fm_listings.php" class = "active">Listings</a></li>
<li><a href="fm_account.php">My Account</a></li>
<li><a href = "fm_transactions.php">Transactions</a></li>
<li><a href = 'fm_homepage.html'>Logged In: <?php echo $log; ?></a></li>
</ul>

</div>


<div class = "search">
<img src = "logo.png" height = "100px" width = "200px" /><br />
<input type="text" name="search" placeholder="Search..">
</div>

<div class = "header">
<h1>Sale</h1>
</div>


<!-- Block 2 -->
<div class = "center">

<div class = "form"
><table>
	<tr>
		<th>Username</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Email</th>
		<th>Phone</th>
	</tr>
	<?php while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><?php echo $row['username']; ?></td>
		<td><?php echo $row['first_name']; ?></td>
		<td><?php echo $row['last_name']; ?></td> 
		<td><?php echo $row['email']; ?></td> 
		<td><?php echo $row['phone']; ?></td>
	</tr>
	<?php } ?>
</table>

<table>
	<tr>
		<th>Item</th>
		<th>Price</th>
		<th>Description</th>
		<th>Picture</th>
		<th>Make Offer</th>
	</tr>
	<?php while ($set = mysqli_fetch_array($content)) { ?>
	<tr>
		<td><?php echo $set['item']; ?></td>
		<td><?php echo $set['price']; ?></td>
		<td><?php echo $set['descr']; ?></td> 
		<td><?php echo $set['picture']; ?></td>
		<td><a href = "fm_place_buyoffer.php?id=<?php echo $set['bid']; ?>">Place Offer</a></td>
	</tr>
	<?php } ?>
</table>

<a href = "fm_messager.php">Message User</a>

</div>
</div>

<!-- Block 3 -->
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