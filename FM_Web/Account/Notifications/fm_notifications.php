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
height: 500px;
left: 0%;
width: 15%;
background-color: #808080;
}

.center {
position: absolute;
height: 500px;
left: 15%;
width: 70%;
background-image: url("tree.jpg");
}

.center .list{
position: absolute;
top: 50px;
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
height: 500px;
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
top: 650px;
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

<div class = "list">
<table>
	<tr>
		<th>From</th>
		<th>Type</th>
		<th>Date/Time</th>
		<th>View</th>
		<th>Delete</th>
	</tr>
<?php while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><?php echo $row['sender']; ?></td>
		<td><?php echo $row['types']; ?></td>
		<td><?php echo $row['created']; ?></td>
		<td><a href = "fm_view_notif.php?id=<?php echo $row['msgid'];?>"><?php echo $row['msgid'];?></a></td>
		<td><a href = "fm_delete_notif.php?id=<?php echo $row['msgid'];?>"><?php echo $row['msgid'];?></a></td>
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