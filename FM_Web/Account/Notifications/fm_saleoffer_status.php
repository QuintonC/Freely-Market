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
	
$tid = $_GET['id'];

$mysql = "SELECT t.seller, t.occured, b.item, b.price, b.descr, b.picture FROM B_Transactions AS t, Buy_Listing AS b WHERE t.bid = b.bid AND tid = '$tid'";
$content = $conn->query($mysql);

#$sql = "delete from Notifications where tid = '$tid'";
#$conn->query($sql);
	
?>

<html>

<head>

<title>Completed Sale Offer</title>
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

.title .search {
top: 15px;
left: 2%;
position: absolute;
}


.center {
position: absolute;
height: 400px;
left: 0%;
width: 100%;
top: 150px;
}


.center .form {
text-align: center;
position: relative;
top: 50px;
}


.footer {
margin: auto;
width: 100%;
background-color: #000000;
color: #FFFAF0;
position: absolute;
top: 450px;
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
	<title>Completed Sale Offer</title>
</head>

<body>


<!-- Block 1 -->
<div class = "title">

<div class = "navbar">

<ul>
<li><a href = "fm_listings.php">Listings</a></li>
<li><a href="fm_account.php">My Account</a></li>
<li><a href = "fm_transactions.php" class = "active">Transactions</a></li>
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
		<th>Seller</th>
		<th>Occured</th>
		<th>Item</th>
		<th>Price</th>
		<th>Description</th>
		<th>Picture</th>
		<th></th>
		<th></th>
	</tr>
	<?php while ($row = mysqli_fetch_array($content)) { ?>
	<tr>
		<td><?php echo $row['seller']; ?></td>
		<td><?php echo $row['occured']; ?></td>
		<td><?php echo $row['item']; ?></td> 
		<td><?php echo $row['price']; ?></td> 
		<td><?php echo $row['descr']; ?></td>
		<td><?php echo $row['picture']; ?></td>
		<td><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="5ND29PM888ZWL">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form></td>
		<td><a href = "fm_abandon_sale.php">Abandon Transaction</a></td>
	</tr>
	<?php } ?>
</table>




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