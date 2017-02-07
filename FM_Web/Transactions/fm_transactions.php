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


$username = $_SESSION['username'];

$mysql = "SELECT t.buyer, t.occured, b.item, b.price FROM B_Transactions AS t, Buy_Listing AS b WHERE b.bid = t.bid AND t.seller = '$username'";
$result = $conn->query($mysql);

$sql = "SELECT t.borrower, t.occured, r.item, r.price, r.duration FROM R_Transactions AS t, Rental_Listing AS r WHERE r.rid = t.rid AND t.renter = '$username'";
$content = $conn->query($sql);

$sql1 = "SELECT t.seller, t.occured, b.item, b.price FROM B_Transactions AS t, Buy_Listing AS b WHERE b.bid = t.bid AND t.buyer = '$username'";
$data = $conn->query($sql1);

$sql2 = "SELECT t.renter, t.occured, r.item, r.price, r.duration FROM R_Transactions AS t, Rental_Listing AS r WHERE r.rid = t.rid AND t.borrower = '$username'";
$record = $conn->query($sql2);

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


.leftsidebar {
position: absolute;
height: 1200px;
left: 0%;
width: 15%;
background-color: #808080;
}

.center {
position: absolute;
height: 1200px;
left: 15%;
width: 85%;
text-align: center;
background-image: url("rack.jpg");
}

.center .sales {
position: absolute;
top: 50px;
left: 200px;
height: 200px;
width: 700px;
background-color: #FFFFFF;
border-style: solid;
border-width: 2px;
padding: 15px;
position: absolute;
overflow: scroll;
}

.center .rentals{
position: absolute;
top: 320px;
left: 200px;
height: 200px;
width: 700px;
background-color: #FFFFFF;
border-style: solid;
border-width: 2px;
padding: 15px;
position: absolute;
overflow: scroll;
}

.center .buys {
position: absolute;
top: 600px;
left: 200px;
height: 235px;
width: 700px;
background-color: #FFFFFF;
border-style: solid;
border-width: 2px;
padding: 15px;
position: absolute;
overflow: scroll;
}

.center .rents {
position: absolute;
top: 900px;
left: 200px;
height: 235px;
width: 700px;
background-color: #FFFFFF;
border-style: solid;
border-width: 2px;
padding: 15px;
position: absolute;
overflow: scroll;
}

.footer {
margin: auto;
width: 100%;
background-color: #000000;
color: #FFFAF0;
position: absolute;
top: 1350px;
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

	<title>Transactions Page</title>
	
</head>

<body>

<!-- Block 1 -->
<div class = "title">

<div class = "search">
<img src = "logo.png" height = "100px" width = "200px" /><br />
<input type="text" name="search" placeholder="Search..">
</div>

<div class = "header">
<h1>Transactions</h1>
</div>

<div class = "navbar">

<ul>
<li><a href = "fm_listings.php">Listings</a></li>
<li><a href="fm_account.php">My Account</a></li>
<li><a href = "fm_transactions.php" class = "active">Transactions</a></li>
<li><a href = 'fm_homepage.html'>Logged In: <?php echo $log; ?></a></li>
</ul>

</div>


</div>

<!-- Block 2 -->
<div class = "leftsidebar">


</div>

<!-- Block 3 -->
<div class = "center">

<div class = "sales">
<center><h3>Sales History</h3></center>
<table>
	<tr>
		<th>Buyer</th>
		<th>Date</th>
		<th>Item</th>
		<th>Price</th>
	</tr>
	<?php while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><?php echo $row['buyer']; ?></td>
		<td><?php echo $row['occured']; ?></td>
		<td><?php echo $row['item']; ?></td> 
		<td><?php echo $row['price']; ?></td> 
	</tr>
	<?php } ?>
</table>
</div>

<div class = "rentals">
<center><h3>Rental History</h3></center>
<table>
	<tr>
		<th>Borrower</th>
		<th>Date</th>
		<th>Item</th>
		<th>Price</th>
		<th>Duration</th>
	</tr>
	<?php while ($set = mysqli_fetch_array($content)) { ?>
	<tr>
		<td><?php echo $set['borrower']; ?></td>
		<td><?php echo $set['occured']; ?></td>
		<td><?php echo $set['item']; ?></td>
		<td><?php echo $set['price']; ?></td>
		<td><?php echo $set['duration']; ?></td> 
	</tr>
	<?php } ?>
</table>
</div>

<div class = "buys">

<center><h3>Successful Purchases</h3></center>
<table>
	<tr>
		<th>Seller</th>
		<th>Date</th>
		<th>Item</th>
		<th>Price</th>
	</tr>
	<?php while ($tup = mysqli_fetch_array($data)) { ?>
	<tr>
		<td><?php echo $tup['seller']; ?></td>
		<td><?php echo $tup['occured']; ?></td>
		<td><?php echo $tup['item']; ?></td> 
		<td><?php echo $tup['price']; ?></td> 
	</tr>
	<?php } ?>
</table>

</div>

<div class = "rents">

<center><h3>Succesful Rentals</h3></center>
<table>
	<tr>
		<th>Renter</th>
		<th>Date</th>
		<th>Item</th>
		<th>Price</th>
		<th>Duration</th>
	</tr>
	<?php while ($area = mysqli_fetch_array($record)) { ?>
	<tr>
		<td><?php echo $area['renter']; ?></td>
		<td><?php echo $area['occured']; ?></td>
		<td><?php echo $area['item']; ?></td>
		<td><?php echo $area['price']; ?></td> 
		<td><?php echo $area['duration']; ?></td> 
	</tr>
	<?php } ?>
</table>


</div>

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