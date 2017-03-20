<?php
session_start();
require_once("../../db_constant.php");
#If logged in the username of the account will be displayed in the top right corner
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

#Check if user is admin
$adminsql = "select admin from User_Accounts where aid = '$aid' AND status = 'Active'";
$adminCheck = $conn->query($adminsql);

#Get number of notifications
$sql3 = "select count(*) from Notifications where recipient = '$username'";
$num = $conn->query($sql3);
$set = mysqli_fetch_array($num);
$number = $set['count(*)'];

$pagenum = $_GET['pagenum'];

$sql = "SELECT count(*) FROM Buy_Listing WHERE aid = '$aid' AND status = 'Active'";
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
$mysql = "select * from Buy_Listing where aid = '$aid' AND status = 'Active' LIMIT $limit OFFSET $offset";
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
height: 650px;
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
height: 650px;
left: 15%;
width: 85%;
text-align: center;
}

.footer {
margin: auto;
width: 100%;
background-color: #000000;
color: #FFFAF0;
position: absolute;
top: 800px;
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

	<title>Account Page</title>
	
</head>

<body>

<!-- Block 1 -->
<div class = "title">

<div class = "search">
<img src = "../../images/logo.png" height = "100px" width = "200px" /><br />
<input type="text" name="search" placeholder="Search..">
</div>

<div class = "header">
<h1>Account</h1>
</div>

<div class = "navbar">
<ul>
<li><a href = "../../listings/fm_listings.php">Listings</a></li>
<li><a href="../fm_account.php"  class = "active">My Account</a></li>
<li><a href = "../../transactions/fm_transactions.php">Transactions</a></li>
<li><a href = "../../fm_homepage.html">Logged In: <?php echo $log; ?></a></li>
</ul>
</div>


</div>

<!-- Block 2 -->
<div class = "leftsidebar">

<div class = "menu">
<ul>
<li><a href = "../edit_account/fm_edit_account.php">Edit Account</a></li>
<li><a href = "../edit_card/fm_edit_card.php">Edit Card Info</a></li>
<li><a href = "../messager/fm_messager1.php">Messager</a></li>
<li><a href = "../notifications/fm_notifications.php">Notifications <div class = "num"><?php if ($number != 0) { echo $number;}?></div></a></li>
<li><a href = 'fm_admin_vendor_requests.php'>Vendor Requests</a></li>
<?php if ($adminCheck['admin'] == "y"): ?>
	<span><li><a href = "fm_messager1.php">Messager</a></li></span>
<?php endif;?>
</ul>
</div>


</div>

<!-- Block 3 -->
<div class = "center">

<center><h3>Sales</h3></center>

<?php echo "Page " . $pagenum . "of " . $lastpage;?><br />
<?php if ($pagenum == 1 and $lastpage != 1) { ?>
<a href="fm_listed_sales.php?pagenum=<?php echo $nextpage; ?>">NEXT</a>
<a href="fm_listed_sales.php?pagenum=<?php echo $lastpage; ?>">LAST</a>
<?php } elseif ($pagenum == $lastpage and $pagenum != 1) { ?>
<a href="fm_listed_sales.php?pagenum=1">FIRST</a>
<a href="fm_listed_sales.php?pagenum=<?php echo $prevpage; ?>">PREV</a>
<?php } elseif ($pagenum != 1 and $lastpage != 1) { ?>
<a href="fm_listed_sales.php?pagenum=1">FIRST</a>
<a href="fm_listed_sales.php?pagenum=<?php echo $prevpage; ?>">PREV</a>
<a href="fm_listed_sales.php?pagenum=<?php echo $nextpage; ?>">NEXT</a>
<a href="fm_listed_sales.php?pagenum=<?php echo $lastpage; ?>">LAST</a>
<?php } ?>

<table>
	<tr>
		<th>Item</th>
		<th>Price</th>
		<th>Description</th>
		<th>Picture</th>
		<th>Edit</th>
		<th>Delete</th>
		<th>View</th>
	</tr>
	<?php while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><?php echo $row['item']; ?></td>
		<td><?php echo $row['price']; ?></td>
		<td><?php echo $row['descr']; ?></td> 
		<td><img src ="../../images/<?php echo $row['picture']; ?>" height = '75px' width = '75px' /></td> 
		<td><a href = "edit_listings/fm_edit_sale.php?id=<?php echo $row['bid']; ?>"><?php echo $row['bid'];?></a></td>
		<td><a href = "edit_listings/fm_delete_sale.php?id=<?php echo $row['bid']; ?>"><?php echo $row['bid'];?></a></td>
		<td><a href = "account_accept_reject/fm_view_buyoffers.php?id=<?php echo $row['bid']; ?>"><?php echo $row['bid'];?></a></td>
	</tr>
	<?php } ?>
</table>

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