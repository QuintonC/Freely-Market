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

#Get id from url
$rid = $_GET['id'];		

#Display Account information of the account who posted the listing
$mysql = "SELECT u.username, u.first_name, u.last_name, u.email, u.phone FROM User_Accounts AS u, Rental_Listing AS r WHERE u.aid = r.aid AND r.rid = '$rid' limit 1";
$result = $conn->query($mysql);

#Display the Rental Listing
$sql = "select * from Rental_Listing where rid = '$rid' limit 1";
$content = $conn->query($sql);

#Select Owner's Name to pass to messager
$sql1 = "select owner from Rental_Listing where rid = '$rid'";
$record = $conn->query($sql1);
$rec = mysqli_fetch_array($record);
$reciever = $rec['owner'];

?>

<html>

<head>

<title>View Rental Page</title>
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
    background-color:	#00008B;
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
height: 360px;
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
<li><a href = "../../../listings/fm_listings.php" class = "active">Listings</a></li>
<li><a href="../../../account/fm_account.php">My Account</a></li>
<li><a href = "../../../transactions/fm_transactions.php">Transactions</a></li>
<li><a href = "../../../fm_homepage.html">Logged In: <?php echo $log; ?></a></li>
</ul>

</div>


<div class = "search">
<img src = "../../../images/logo.png" height = "100px" width = "200px" /><br />
<form name = "searchbar" action = "fm_rental_search_results.php?pagenum=1" method="post">
<input type="text" name="search" placeholder="Search for a Listing...">
<button type="submit" value="search">Search</button>
</form>
</div>

<div class = "header">
<h1>Rental</h1>
</div>


<!-- Block 2 -->
<div class = "center">

<div class = "form">
<table>
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
		<th>Duration</th>
		<th>Description</th>
		<th>Picture</th>
		<th>Make Offer</th>
		<th>Send Message</th>
	</tr>
	<?php while ($set = mysqli_fetch_array($content)) { ?>
	<tr>
		<td><?php echo $set['item']; ?></td>
		<td><?php echo $set['price']; ?></td>
		<td><?php echo $set['duration']; ?></td>
		<td><?php echo $set['descr']; ?></td> 
		<td><img src ="../../../images/<?php echo $set['picture']; ?>" height = '75px' width = '75px' /></td>
		<td><a href = "fm_rent_request.php?id=<?php echo $set['rid']; ?>">Request to Rent</a></td>
		<td><a href = "../../../account/messager/fm_messager1.php?id=<?php echo $reciever; ?>">Message User</a> </td>
	</tr>
	<?php } ?>
</table>

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