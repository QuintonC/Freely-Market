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




.navMenu {
	margin:0;
	padding:0;
}	
.navMenu ul {
	margin:0;
	padding:0;
	line-height:15px;
	background-color: red;
	color:blue;
}	
	


.navMenu > ul > li:hover {
	background-color: #f5f5f5;
}	
.navMenu > ul > li {
	list-style-type:none;
	text-align:center;
	text-decoration:none;
	display:inline-block;
	color: blue;
	position:relative;
}	

ul.sub-menu {
	position:absolute;
	backg
	opacity:0;
}
ul.sub-menu li{
	
}
	
.navMenu ul ul {
	position:absolute;
	visibility:hidden;
}
	
.navClass li:hover .sub-menu{
	opacity:1;
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
<li><a href = "../listings/fm_listings.php">Listings</a></li>
<li><a href="../account/fm_account.php"  class = "active">My Account</a></li>
<li><a href = "../transactions/fm_transactions.php">Transactions</a></li>
<li><a href = "../fm_homepage.html">Logged In: <?php echo $log; ?></a></li>
</ul>
</div>


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
		<th>Action</th>

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
		
		
		<td>
		<div id = "wrapper">
		<div id = "navMenu">
			<ul>
				<li><a href = "#">Action</a>
					<ul class "sub-menu">
						<li><a href ="fm_unrestrict_user.php?id=<?php echo $row['aid']; ?>">Regulate User</a></li>
						<li><a href ="fm_restrict_user.php?id=<?php echo $row['aid']; ?>">Restrict User</a></li>
						<li><a href ="fm_ban_user.php?id=<?php echo $row['aid']; ?>">Ban User</a></li>
					</ul>
				</li>
			</ul>
		</div>
		</div>
		</td>
		
		
			
	</tr>
	<?php } ?>
</table>
</div>
</body>
</html>