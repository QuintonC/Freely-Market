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
<head>
<link rel="stylesheet" type="text/css" href="../_style.css"><link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
<title>Freely Market | View Users</title>
</head>
<body>

<div class="landing" style="overflow-y: scroll;">

    <!-- Block 1 -->
    <div class = "titleAlt">
        <div class="titleCenter">
            <img class="titleLogo" src="../images/freelyMarketLogo.png"/>
        </div>

        <div class = "login">
            <div class="titleButton">
                <a href = "../listings/fm_listings.php">Listings</a>
            </div>

            <div class="titleButton">
                <a href = "../transactions/fm_transactions.php">Transactions</a>
            </div>

            <div class="titleButton">
                <a href="../account/fm_account.php" class = "active">My Account</a>
            </div>

            <div class="titleButton">
                <a href = "../fm_homepage.html">Logout</a>
            </div>
        </div>
    </div>

	<!-- Block 2 -->
    <div class = "leftsidebar">
        <?php while ($row = mysqli_fetch_array($user)) { ?>
            <table>
                    <tr>
                        <td><img class="img-circle" src="../images/<?php echo $row['picture'];?>"/></td>
                    </tr>
            </table>
        <?php } ?>
        
        <div class="username">
            Logged in as: <?php echo $log; ?>
        </div>
    

        <div class="brackets">
                
            <div class="menuLink">
                <a href="../account/edit_account/fm_edit_account.php">Edit Account</a>
            </div>

            <div class="menuLink">
                <a href="../account/edit_card/fm_edit_card.php">Payment Info</a>
            </div>

            <div class="menuLink">
                
                <a href="../account/messager/fm_messager1.php">Messenger <?php if ($digit != 0) { echo "(" . $digit . ")";}?></a>
            </div>

            <div class="menuLink">
                <a href="../account/notifications/fm_notifications.php">Notifications <?php if ($number != 0) { echo "(" . $number . ")";}?></a>
            </div>

            <div class="menuLink">
                <a href = "../account/fm_my_listings.php">My Listings</a>
            </div>

            <div class="menuLink">
                <a href = "../account/fm_my_offers.php">My Offers</a>
            </div>

            <div class="menuLink">
                <?php if ($check['typ'] == 2 ): ?>
                    <a href='../admin/fm_admin_view_users.php'>View Users</a>
                <?php endif;?>
            </div>

            <div class="menuLink">
                <?php if ($check['typ'] == 2 ): ?>
                    <a href='../admin/fm_admin_view_issues.php'>View Issues</a>
                <?php endif;?>
            </div>

            <div class="menuLink">
                <?php if ($check['typ'] == 1 ): ?>
                    <a href='../vendor/account_page/fm_v_create_advertisement1.php'>Advertisements</a>
                <?php endif;?>
            </div>

            <div class="menuLink">
                <?php if ($check['typ'] == 2 ): ?>
                    <a href='../admin/fm_admin_ad_requests.php'>Ad Requests</a>
                <?php endif;?>
            </div>

            <div class="menuLink">
                <?php if ($check['typ'] == 2 ): ?>
                    <a href='../admin/fm_admin_vendor_requests.php'>Vendor Requests</a>
                <?php endif;?>
            </div>

            <div class="menuLink">
                <a href="../account/report_issue/fm_issue_form.php">Report an Issue</a>
            </div>
        </div>
    </div>



<!--Block 3 -->
	<div class = "listingOuter">

		<?php while ($row = mysqli_fetch_array($result)) { ?>

		<div class="users">

			<div class="listingTitle">
				<?php echo $row['vendor']; ?>
			</div>

			<div class="listingTitle">
				<?php echo $row['fname']; ?>
			</div>

			<div class="listingTitle">
				<?php echo $row['lname']; ?>
			</div>

			<div class="listingTitle">
				<?php echo $row['street'] . ", " . $row['city'] . ", " . $row['state'] . " " . $row['zipcode']; ?>
			</div>

			<div class="listingTitle">
				<?php echo $row['email']; ?>
			</div>

			<div class="listingTitle">
				<?php echo $row['years']; ?>
			</div>

			<div class="listingTitle">
				<?php echo $row['whyjoin']; ?>
			</div>

			<div class="listingTitle">
				<?php echo $row['futureproj']; ?>
			</div>

			<div class="listingTitle">
				<br><a class="buttonAlt" href="fm_delete_vendor_request.php?id=<?php echo $row['appid']; ?>"><?php echo "Deny Request";?></a><br><br>
				
				<a class="buttonAlt" href = "fm_accept_vendor_request.php?id=<?php echo $row['appid']; ?>"><?php echo "Accept Request";?></a>
			</div>

		</div>

	<?php } ?>

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
</html>