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
#Call session variables
$username = $_SESSION['username'];
$aid = $_SESSION['uid'];

#Show User Info 
$sql = "SELECT * FROM Issue WHERE status = 'active'";
$result = $conn->query($sql);

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
<title>Issues</title>
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
<div class = "center">
<center><h2>Issues</h2></center>
<table>
	<tr>
		<th>Issue ID</th>
		<th>User ID</th>
		<th>Description</th>
		<th>Action</th>
	</tr>
	<?php while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><?php echo $row['iid']; ?></td>
		<td><?php echo $row['aid']; ?></td>
		<td><?php echo $row['description']; ?></td>
		<td><li><a href ="fm_clear_issue.php?id=<?php echo $row['iid']; ?>">Clear Issue</a></li></td>	
	</tr>
	<?php } ?>
</table>
</div>
</body>
</html>