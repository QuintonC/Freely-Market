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

#Check if user is admin
$adminsql = "select typ from User_Accounts where aid = '$aid'";
$adminCheck = $conn->query($adminsql);
$check = mysqli_fetch_array($adminCheck);

//Select User Photo
$sql2 = "select * from User_Accounts WHERE username ='$username'";
$user = $conn->query($sql2);

#Get number of notifications
$sql3 = "select count(*) from Notifications where recipient = '$username'";
$num = $conn->query($sql3);
$set = mysqli_fetch_array($num);
$number = $set['count(*)'];

$sql4 = "select count(*) from Msg_Notifications where recipient = '$username'";
$dig = $conn->query($sql4);
$set = mysqli_fetch_array($dig);
$digit = $set['count(*)'];
?>

<html>

<head>
<link rel="stylesheet" type="text/css" href="../_style.css">
<title>Account Page</title>
	
</head>

<body>

<!-- Block 1 -->
<div class = "title">
	<div class = "search">
		<h3 align="center"><a href="../fm_homepage.html"><img src = "../images/logo.png" height = "90px" width = "160px" /></a></h3>
	</div>

	<div class = "header">
		<h1 align="center">Account</h1>
	</div>

	<div class = "login">
		<ul>
			<li><a href = "../listings/fm_listings.php">Listings</a></li>
			<li><a href = "../transactions/fm_transactions.php">Transactions</a></li>
			<li><a href="fm_account.php"  class = "active">My Account</a></li>
			<li><a href = "../fm_homepage.html">Logged In: <?php echo $log; ?></a></li>
		</ul>
	</div>
</div>

<!-- Block 2 -->
<div class = "leftsidebar">
	<?php while ($row = mysqli_fetch_array($user)) { ?>
		<table align="center">
				<tr>
					<td align="center"><img class="img-circle" src="../images/<?php echo $row['picture'];?>"/></td>
				</tr>
		</table>
	<?php } ?>
	<font color="white"><h2 align="center"> <?php echo $log; ?></h2></font>
<div class = "menu">
	<ul>
		<li><a href = "edit_account/fm_edit_account.php">Edit Account</a></li>
		<li><a href = "edit_card/fm_edit_card.php">Edit Card Info</a></li>
		<li><a href = "messager/fm_messager1.php">Messager <div class = "dig"><?php if ($digit != 0) { echo $digit;}?></div></a></li>
		<li><a href = "notifications/fm_notifications.php">Notifications <div class = "num"><?php if ($number != 0) { echo $number;}?></div></a></li>
		<li><a href = "report_issue/fm_issue_form.php">Report an Issue</a></li>
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
			<span><li><a href = '../vendor/account_page/fm_v_create_advertisement1.php'>Advertisements</a></li>
		<?php endif;?>
	</ul>
</div>


</div>

<!-- Block 3 -->
<div class = "account">
	<div class="my_listings">
		<a href = "fm_my_listings.php"><img class="img-circle1" /></a><br/>My Listings
	</div>
	<div class="my_offers">
		<a href = "fm_my_offers.php"><img class="img-circle1" src="../images/dollar-sign-black.jpg" height="100px" width="150px"/></a><br/>My Offers
	</div>
</div>

<!-- Block 4 -->
<div class = "footer">
	<ul>
		<li><a href = "">Privacy Policy</a></li>
		<li><a href = "">About</a></li>
		<li><a href = "">Contact</a></li>
	</ul>
</div>

</body>
</html>