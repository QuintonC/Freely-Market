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

#Call session variables
$username = $_SESSION['username'];
$aid = $_SESSION['uid'];

#Show Vendor Requests 
$sql = "SELECT * FROM Vendor_Application WHERE confirmed != 'confirmed' AND confirmed != 'denied'";
$result = $conn->query($sql);

$confirm = "Select confirmed FROM Vendor_Application";
$con = $conn ->query($confirm);
$array = mysqli_fetch_array($con);

#Check if user is admin
$adminsql = "select typ from User_Accounts where aid = '$aid'";
$adminCheck = $conn->query($adminsql);
$check = mysqli_fetch_array($adminCheck);


?>

<html>
<header>
<link rel="stylesheet" type="text/css" href="../_style.css">
<style>

</style>
</header>
<title>Vendor Requests</title>
<body>
<!-- Block 1 -->
<div class = "title">

<div class = "search">
<img src = "../images/logo.png" height = "100px" width = "200px" /><br />
<input type="text" name="search" placeholder="Search..">
</div>

<div class = "header">
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

<!-- Block 2 -->
<div class = "leftsidebar">

<div class = "menu">
<ul>
<li><a href = "../account/edit_account/fm_edit_account.php">Edit Account</a></li>
<li><a href = "../account/edit_card/fm_edit_card.php">Edit Card Info</a></li>
<li><a href = "../account/messager/fm_messager1.php">Messager <div class = "dig"><?php if ($digit != 0) { echo $digit;}?></div></a></li>
<li><a href = "../account/notifications/fm_notifications.php">Notifications <div class = "num"><?php if ($number != 0) { echo $number;}?></div></a></li>
<li><a href = "../account/report_issue/fm_issue_form.php">Report an Issue</a></li>
<?php if ($check['typ'] == 2 ): ?>
	<span><li><a href = '../admin/fm_admin_vendor_requests.php'>Vendor Requests</a></li>
<?php endif;?>
<?php if ($check['typ'] == 2 ): ?>
	<span><li><a href = '../admin/fm_admin_view_users.php'>View Users</a></li>
<?php endif;?>
<?php if ($check['typ'] == 2 ): ?>
	<span><li><a href = '../admin/fm_admin_view_issues.php'>View Issues</a></li>
<?php endif;?>
<?php if ($check['typ'] == 1 ): ?>
	<span><li><a href = '../vendor/account_page/fm_v_create_advertisement1.php'>View Users</a></li>
<?php endif;?>
<?php if ($check['typ'] == 2 ): ?>
	<span><li><a href = '../admin/fm_admin_ad_requests.php'>Ad Requests</a></li>
<?php endif;?>
</ul>
</div>



<!--Block 3 -->
<div class = "vendor_requests">
<center><h2>Vendor Requests</h2></center>
<table>
	<tr>
		<th>Application ID</th>
		<th>Vendor</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Street</th>
		<th>City</th>
		<th>State</th>
		<th>Zipcode</th>
		<th>Email</th>
		<th>Years In Business</th>
		<th>Why Did You Join</th>
		<th>Future Projects</th>
		<th>Deny Request</th>
		<th>Accept Request</th>
	</tr>
	<?php while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><?php echo $row['appid']; ?></td>
		<td><?php echo $row['vendor']; ?></td>
		<td><?php echo $row['fname']; ?></td>
		<td><?php echo $row['lname']; ?></td>
		<td><?php echo $row['street']; ?></td>
		<td><?php echo $row['city']; ?></td>
		<td><?php echo $row['state']; ?></td>
		<td><?php echo $row['zipcode']; ?></td>
		<td><?php echo $row['email']; ?></td>
		<td><?php echo $row['years']; ?></td>
		<td><?php echo $row['whyjoin']; ?></td>
		<td><?php echo $row['futureproj']; ?></td>
		
		<div class = "links">
		
		<td><a href = "fm_delete_vendor_request.php?id=<?php echo $row['appid']; ?>"><?php echo "Deny Request";?></a></td>
		<td><a href = "fm_accept_vendor_request.php?id=<?php echo $row['appid']; ?>"><?php echo "Accept Request";?></a></td>
		
		</div>
	</tr>
	<?php } ?>
</table>
</div>
</body>
</html>