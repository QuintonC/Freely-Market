<?php

session_start();
require_once("../../../db_constant.php");

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
	
$username = $_SESSION['username'];


$pagenum = $_GET['pagenum'];

$sql = "SELECT count(*) FROM B_Transactions AS t, Buy_Listing AS b WHERE b.bid = t.bid AND t.seller = '$username'";
$content = $conn->query($sql);
$val = mysqli_fetch_array($content);
$total = $val['count(*)'];

$limit = 8;

$lastpage = ceil($total / $limit);
if ($lastpage == 0) {
	$lastpage = 1;
}
$nextpage = $pagenum + 1;
$prevpage = $pagenum - 1;
$offset = ($pagenum - 1)  * $limit;


$mysql = "SELECT t.buyer, t.occured, b.item, b.price FROM B_Transactions AS t, Buy_Listing AS b WHERE b.bid = t.bid AND t.seller = '$username' LIMIT $limit OFFSET $offset";
$result = $conn->query($mysql);

?>

<html>

<head>

<title>Past Sales Page</title>
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
	padding-top: 15px;
	padding-bottom: 15px;
	padding-left: 50px;
	padding-right: 50px;
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
height: 1100px;
left: 0%;
width: 15%;
background-color: #808080;
}

.leftsidebar ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: 200px;
    background-color: #f1f1f1;
}


.leftsidebar li a {
   display: block;
    color: #000;
    padding: 8px 16px;
    text-decoration: none;
	width: 200px;
    text-align: left;
}

.leftsidebar li a:hover {
    background-color: #555;
    color: white;
}

.leftsidebar .active {
    background-color: #4CAF50;
    color: white;
}

.center {
position: absolute;
height: 1100px;
left: 15%;
width: 70%;
text-align: center;
}


.rightsidebar {
position: absolute;
height: 1100px;
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
top: 1250px;
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


</head>

<body>

<!-- Block 1 -->
<div class = "title">

<div class = "search">
<img src = "../../../images/logo.png" height = "100px" width = "200px" /><br />
<input type="text" name="search" placeholder="Search..">
</div>

<div class = "header">
<h1>Transactions</h1>
</div>

<div class = "navbar">

<ul>
<li><a href = "../../../listings/fm_listings.php">Listings</a></li>
<li><a href="../../../account/fm_account.php">My Account</a></li>
<li><a href = "../../../transactions/fm_transactions.php" class = "active">Transactions</a></li>
<li><a href = "../../../fm_homepage.html">Logged In: <?php echo $log; ?></a></li>
</ul>
</div>


</div>

<!-- Block 2 -->
<div class = "leftsidebar">

</div>

<!-- Block 3 -->
<div class = "center">

<center><h2>Sold Bikes</h2></center>
<?php echo "Page " . $pagenum . "of " . $lastpage;?><br />
<?php if ($pagenum == 1 and $lastpage != 1) { ?>
<a href="fm_past_sales_bikes.php?pagenum=<?php echo $nextpage; ?>">NEXT</a>
<a href="fm_past_sales_bikes.php?pagenum=<?php echo $lastpage; ?>">LAST</a>
<?php } elseif ($pagenum == $lastpage and $pagenum != 1) { ?>
<a href="fm_past_sales_bikes.php?pagenum=1">FIRST</a>
<a href="fm_past_sales_bikes.php?pagenum=<?php echo $prevpage; ?>">PREV</a>
<?php } elseif ($pagenum != 1 and $lastpage != 1) { ?>
<a href="fm_past_sales_bikes.php?pagenum=1">FIRST</a>
<a href="fm_past_sales_bikes.php?pagenum=<?php echo $prevpage; ?>">PREV</a>
<a href="fm_past_sales_bikes.php?pagenum=<?php echo $nextpage; ?>">NEXT</a>
<a href="fm_past_sales_bikes.php?pagenum=<?php echo $lastpage; ?>">LAST</a>
<?php } ?>

<table>
	<tr>
		<th>Sold To</th>
		<th>Occured</th>
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

<!-- Block 4 -->
<div class = "rightsidebar">

</div>

<!-- Block 5 -->
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