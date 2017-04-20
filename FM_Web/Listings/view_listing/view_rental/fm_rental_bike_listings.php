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
	<title>Freely Market | Rental Listings</title>
	<link rel="stylesheet" type="text/css" href="../../../_style.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
</head>

<body>
<div class="landing" style="overflow-y: scroll;">

	<!-- Block 1 -->
	<div class = "titleAlt">
		<div class="titleCenter">
			<img class="titleLogo" src="../../../images/freelyMarketLogo.png"/>
		</div>

		<div class = "login">
			<div class="titleButton">
				<a href = "../../../listings/fm_listings.php">Listings</a>
			</div>

			<div class="titleButton">
				<a href = "../../../transactions/fm_transactions.php">Transactions</a>
			</div>

			<div class="titleButton">
				<a href="../../../account/fm_account.php" class = "active">My Account</a>
			</div>

			<div class="titleButton">
				<a href = "../../../fm_homepage.html">Logout</a>
			</div>
		</div>
		<div class="search">
			<form class="glow" name = "searchbar" action = "fm_rental_search_results.php?pagenum=1" method="post">
				<input type="text" name="search" placeholder="Search for a Listing...">

				<button class="buttonAlt" type="submit" value="search">Search</button>
			</form>
		</div>
	</div>

	<!-- Block 2 -->
	<div class = "leftsidebar">
		<?php while ($row = mysqli_fetch_array($user)) { ?>
			<table>
					<tr>
						<td><img class="img-circle" src="../../../images/<?php echo $row['picture'];?>"/></td>
					</tr>
			</table>
		<?php } ?>
		
		<div class="username">
			Logged in as: <?php echo $log; ?>
		</div>
	

		<div class="brackets">
				
			<div class="menuLink">
				<a href="../../../account/edit_account/fm_edit_account.php">Edit Account</a>
			</div>

			<div class="menuLink">
				<a href="../../../account/edit_card/fm_edit_card.php">Payment Info</a>
			</div>

			<div class="menuLink">
				
				<a href="../../../account/messager/fm_messager1.php">Messenger <?php if ($digit != 0) { echo "(" . $digit . ")";}?></a>
			</div>

			<div class="menuLink">
				<a href="../../../account/notifications/fm_notifications.php">Notifications <?php if ($number != 0) { echo "(" . $number . ")";}?></a>
			</div>

			<div class="menuLink">
				<a href = "../../../account/fm_my_listings.php">My Listings</a>
			</div>

			<div class="menuLink">
				<a href = "../../../account/fm_my_offers.php">My Offers</a>
			</div>

			<div class="menuLink">
				<?php if ($check['typ'] == 2 ): ?>
					<a href='../../../admin/fm_admin_view_users.php'>View Users</a>
				<?php endif;?>
			</div>

			<div class="menuLink">
				<?php if ($check['typ'] == 2 ): ?>
					<a href='../../../admin/fm_admin_view_issues.php'>View Issues</a>
				<?php endif;?>
			</div>

			<div class="menuLink">
				<?php if ($check['typ'] == 1 ): ?>
					<a href='../../../vendor/account_page/fm_v_create_advertisement1.php'>Advertisements</a>
				<?php endif;?>
			</div>

			<div class="menuLink">
				<?php if ($check['typ'] == 2 ): ?>
					<a href='../../../admin/fm_admin_ad_requests.php'>Ad Requests</a>
				<?php endif;?>
			</div>

			<div class="menuLink">
				<?php if ($check['typ'] == 2 ): ?>
					<a href='../../../admin/fm_admin_vendor_requests.php'>Vendor Requests</a>
				<?php endif;?>
			</div>

			<div class="menuLink">
				<a href="../../../account/report_issue/fm_issue_form.php">Report an Issue</a>
			</div>
		</div>
	</div>

<!-- Block 3 -->
	<div class="listingOuter">
	
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
	
		<div class="listings">
			
			<?php while ($row = mysqli_fetch_array($result)) : ?>
			
			<div class="item">
				
				<div class="listingTitle">
					<a href = "fm_viewrental.php?id=<?php echo $row['rid'];?>"><?php echo $row['item']; ?></a>
				</div>

				<div class="listingImage">
					<img src ="../../../images/<?php echo $row['picture']; ?>" class="listingImageConstraints">
				</div>

				<div class="listingPrice">
					<?php echo "$" . $row['price']; ?>
				</div>
				
				<a href = "fm_viewrental.php?id=<?php echo $row['rid'];?>">
					<div class="moreButton">
						More Info
					</div>
				</a>
			</div>
	
		<?php endwhile; ?>
		</div>

		<!-- <?php echo "Page " . $pagenum . " of " . $lastpage;?><br /> -->
	</div>

<!-- Block 4 -->
	<div class = "rightsidebar">

	<div class="">Vendor Advertisements</div>

		<table>
		<?php while ($ad = mysqli_fetch_array($data)) { ?>
			<tr>
				<td><img src = "../../../images/<?php echo $ad['file']; ?>" height = '250x' width = '230px' /></td>
			</tr>
			<?php } ?>
		</table>

	</div>

	<!-- Block 5 -->
	<div class = "footer" style="position: relative !important;">
		<a class="footerElement" href="">About</a>
		<a class="footerElement" href="">Contact</a>
		<a class="footerElement" href="">Privacy Policy</a>

		<div class = "copyright">
			(c) 2017 Freely Market
		</div>
	</div>
</div>
</body>
</body>
</html>