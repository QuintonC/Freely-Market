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

$sql = "SELECT count(*) FROM Rental_Listing WHERE owner != '$username' AND status = 'Active'";
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

#Show Sales Listed
$mysql = "SELECT r.item, r.price, r.descr, r.duration, r.picture, r.owner, r.rid FROM Rental_Listing AS r, User_Accounts AS a WHERE a.aid = r.aid AND a.username != '$username' AND status = 'Active' LIMIT $limit OFFSET $offset";
$result = $conn->query($mysql);

#Select Advertisements
$sql1 = "select * from Advertisements WHERE confirmed = 'confirmed' limit 3";
$data = $conn->query($sql1);

?>

<html>

<head>

<title>Listings Page</title>
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


.leftsidebar {
position: absolute;
height: 1100px;
left: 0%;
background-color: #808080;
}


.center {
position: absolute;
height: 1100px;
left: 0%;
width: 66%;
text-align: center;
}

.center table, th, td {
	margin-left: auto;
	margin-right: auto;
	border-bottom: 1px solid #ddd;
	padding-top: 15px;
	padding-bottom: 15px;
	padding-left: 50px;
	padding-right: 50px;
    text-align: left;
}

.center th {
    background-color: 	#00008B;
    color: white;
}

.center tr:nth-child(even) {
	background-color: #f2f2f2;
}

.center tr:hover {
	background-color: #f5f5f5;
}

.rightsidebar {
position: absolute;
height: 1100px;
left: 75%;
width: 25%;
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
<form name = "searchbar" action = "fm_rental_search_results.php?pagenum=1" method="post">
<input type="text" name="search" placeholder="Search for a Listing...">
<button type="submit" value="search">Search</button>
</form>
</div>

<div class = "header">
<h1>Listings</h1>
</div>

<div class = "navbar">

<ul>
<li><a href = "../../../listings/fm_listings.php" class = "active">Listings</a></li>
<li><a href="../../../account/fm_account.php">My Account</a></li>
<li><a href = "../../../transactions/fm_transactions.php">Transactions</a></li>
<li><a href = "../../../fm_homepage.html">Logged In: <?php echo $log; ?></a></li>
</ul>
</div>


</div>

<!-- Block 2 -->
<div class = "leftsidebar">

</div>

<!-- Block 3 -->
<div class = "center">

<center><h2>Bike Rentals</h2></center>
<?php echo "Page " . $pagenum . " of " . $lastpage;?><br />
<?php if ($pagenum == 1 and $lastpage != 1) { ?>
<a href="fm_rental_bike_listings.php?pagenum=<?php echo $nextpage; ?>">NEXT</a>
<a href="fm_rental_bike_listings.php?pagenum=<?php echo $lastpage; ?>">LAST</a>
<?php } elseif ($pagenum == $lastpage and $pagenum != 1) { ?>
<a href="fm_rental_bike_listings.php?pagenum=1">FIRST</a>
<a href="fm_rental_bike_listings.php?pagenum=<?php echo $prevpage; ?>">PREV</a>
<?php } elseif ($pagenum != 1 and $lastpage != 1) { ?>
<a href="fm_rental_bike_listings.php?pagenum=1">FIRST</a>
<a href="fm_rental_bike_listings.php?pagenum=<?php echo $prevpage; ?>">PREV</a>
<a href="fm_rental_bike_listings.php?pagenum=<?php echo $nextpage; ?>">NEXT</a>
<a href="fm_rental_bike_listings.php?pagenum=<?php echo $lastpage; ?>">LAST</a>
<?php } ?>
<table>
	<tr>
		<th>Item</th>
		<th><a href="fm_rental_listings_sort.php?pagenum=1">Price</a></th>
		<th>Description</th>
		<th>Picture</th>
		<th>Id</th>
	</tr>
	<?php while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><a href = "fm_viewrental.php?id=<?php echo $row['rid'];?>"><?php echo $row['item']; ?></a></td>
		<td><?php echo $row['price']; ?></td>
		<td><?php echo $row['descr']; ?></td> 
		<td><a href = "fm_viewrental.php?id=<?php echo $row['rid'];?>"><img src ="../../../images/<?php echo $row['picture']; ?>" height = '75px' width = '75px' /></a></td>
		<td><a href = "fm_view_user_rental_listings.php?id=<?php echo $row['owner'];?>&pagenum=1"><?php echo $row['owner']; ?></a></td>
	</tr>
	<?php } ?>
</table>


</div>

<!-- Block 4 -->
<div class = "rightsidebar">

<table>
<?php while ($ad = mysqli_fetch_array($data)) { ?>
	<tr>
		<td><img src = "../../../images/<?php echo $ad['file']; ?>" height = '250x' width = '230px' /></td>
	</tr>
	<?php } ?>
</table>

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