<?php

session_start();
require_once("../db_constant.php");

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
	
#Show User Info 
$sql = "SELECT * FROM User_Accounts";
$result = $conn->query($sql);

#Show User addresses
$mysql = "SELECT * FROM User_Addresses as u, User_Accounts WHERE ";
$address = $conn->query($mysql);

?>
<html>
<header>
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
text-align: center;
background-image: url("shop.jpg");
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
</header>
<body>

<!-- Block 1 -->
<div class = "title">

<div class = "search">
<img src = "logo.png" height = "100px" width = "200px" /><br />
<input type="text" name="search" placeholder="Search..">
</div>

<div class = "header">
<h1>Account</h1>
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

<div class = "menu">
<ul>
<li><a href = "fm_edit_account.php">Edit Account</a></li>
<li><a href = "fm_edit_card.php">Edit Card Info</a></li>
<li><a href = "fm_messager1.php">Messager</a></li>
<li><a href = "fm_notifications.php">Notifications <div class = "num"><?php if ($number != 0) { echo $number;}?></div></a></li>
<?php if ($check['typ'] == 2 ): ?>
	<span><li><a href = 'fm_admin_vendor_requests.php'>Vendor Requests</a></li>
<?php endif;?>
<?php if ($check['typ'] == 2 ): ?>
	<span><li><a href = 'fm_admin_view_users.php'>View Users</a></li>
<?php endif;?>
</ul>
</div>


</div>
<div class = "center">
<center><h2>Users</h2></center>
<table>
	<tr>
		<th>User ID</th>
		<th>User</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Email</th>
		<th>Phone</th>
		<th>Picture</th> 
		<th>Type</th>
		<th>Restrict User</th>
		<th>Unrestrict User</th>
		<th>Ban User</th>
		<th>Reactivate User</th>
	</tr>
	<?php while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><?php echo $row['aid']; ?></td>
		<td><?php echo $row['username']; ?></td>
		<td><?php echo $row['first_name']; ?></td>
		<td><?php echo $row['last_name']; ?></td>
		<td><?php echo $row['email']; ?></td>
		<td><?php echo $row['phone']; ?></td>
		<td><?php echo $row['picture']; ?></td>
		<td><?php echo $row['typ']; ?></td>
		<td><a href = "fm_restrict_user.php?id=<?php echo $row['aid']; ?>"><?php echo "Restrict User";?></a></td>
		<td><a href = "fm_unrestrict_user.php?id=<?php echo $row['aid']; ?>"><?php echo "Unrestrict User";?></a></td>
		<td><a href = "fm_ban_user.php?id=<?php echo $row['aid']; ?>"><?php echo "Ban User";?></a></td>
		<td><a href = "fm_reactivate_user.php?id=<?php echo $row['aid']; ?>"><?php echo "Reactivate User";?></a></td>
			
	</tr>
	<?php } ?>
</table>
</div>
</body>
</html>