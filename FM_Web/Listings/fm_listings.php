<?php

session_start();
require_once("../db_constant.php");

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

#Call session variables
$aid = $_SESSION['uid'];	
$username = $_SESSION['username'];


#Check if user is restricted
$rsql = "select active from User_Accounts where aid = '$aid'";
$restrictedCheck = $conn->query($rsql);
$rcheck = mysqli_fetch_array($restrictedCheck);
?>

<html>

<head>
<link rel="stylesheet" type="text/css" href="../_style.css">
<title>Listings Page</title>
<!--<style>

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

.center {
position: absolute;
height: 450px;
left: 0%;
width: 100%;
background-image: url("../images/bw_rack.jpg");
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

</style>-->


</head>

<body>

<!-- Block 1 -->
<div class = "title">
	<div class = "search">
		<h3 align="center"><a href="../fm_homepage.html"><img src = "../images/logo.png" height = "90px" width = "160px" /></a></h3>
	</div>

	<div class = "header">
		<h1 align="center">Listings</h1>
	</div>

	<div class = "login">
		<ul>
			<li><a href = "fm_listings.php">Listings</a></li>
			<li><a href = "../transactions/fm_transactions.php">Transactions</a></li>
			<li><a href="../account/fm_account.php"  class = "active">My Account</a></li>
			<li><a href = "../fm_homepage.html">Logged In: <?php echo $log; ?></a></li>
		</ul>
	</div>
</div>
</div>


<!-- Block 2 -->
<div class = "forms">
<div class="listing">

<?php if ($rcheck['active'] == "0" ): ?>
		<p><a href = "fm_post_listing.php">Post Listings</a></p>
		<p><a href = "fm_view_listings.php">View Listings</a></p>
<?php endif;?>

<?php if ($rcheck['active'] == "2" ): ?>
	<ul>
		<li><a href = "restricted_warning.php">Post Listings</a></li>
		<li><a href = "restricted_warning.php">View Listings</a></li>
	</ul>
<?php endif;?>
</div>
</div>

<!-- Block 3 -->
<div class = "footer">
	<ul>
		<li><a href = "">Privacy Policy</a></li>
		<li><a href = "">About</a></li>
		<li><a href = "">Contact</a></li>
	</ul>
</div>

</body>
</html>