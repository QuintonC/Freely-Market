<?php

session_start();
require_once("../../db_constant.php");

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//Check connection
if (mysqli_connect_error()) {
	echo "Failed to connect to MySQL:" .mysqli_connect_error();
	exit();
}

if (isset($_SESSION['loggedin']) and $_SESSION['loggedin'] == true) {
	$log = $_SESSION['username'];
} else {
	echo "Please log in to see this page.";
}

$username = $_SESSION['username'];

//Select Advertisements
$sql1 = "select * from Advertisements WHERE username='$username' LIMIT 2";
$data = $conn->query($sql1);

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

//Current Date
$currentdate = date("Y-m-d");

//Advertisement expiration date
$sql5 = "SELECT expirdate FROM Advertisements where expirdate='$currentdate'";
$expirdate = $conn->query($sql5);
$row_arr = mysqli_fetch_array($expirdate);

$sql6 = "DELETE FROM Advertisements WHERE expirdate = '$currentdate'";
$delete = $conn->query($sql6);

if($row_arr == $currentdate){
	$delete;
}
?>

<!doctype html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../../_style.css">
<meta charset="utf-8">
<link rel="shortcut icon" href="../../images/favicon.ico" type="image/x-icon" />
<title>Create Advertisement</title>

<script type="text/javascript">

<!-- Ensures forms are not blank -->
    function blank()
    {
    var a=document.forms["advertisement"]["title"].value;
	var b=document.forms["advertisement"]["picture"].value;
	var c=document.forms["advertisement"]["descr"].value;
	var d=document.forms["advertisement"]["expir"].value;
    if (a==null || a=="",b==null || b=="",c==null || c=="",d==null || d=="")
      {
      alert("Please complete all required fields");
      return false;
      }
    }

	function maxLength(char) {    
    if (!('maxLength' in char)) {
        var max = char.attributes.maxLength.value;
        char.onkeypress = function () {
            if (this.value.length >= max) return false;
        };
    }
}

maxLength(document.getElementById("text"));
</script>
</head>

<body

<!-- Block 1 -->
<div class = "title">
	<div class = "search">
		<h3 align="center"><a href="../../fm_homepage.html"><img src = "../../images/logo.png" height = "90px" width = "160px" /></a></h3>
	</div>

	<div class = "header">
		<h1 align="center">Freely Market</h1>
	</div>

	<div class = "login">
		<ul>
			<li><a href = "../../listings/fm_listings.php">Listings</a></li>
			<li><a href = "../../transactions/fm_transactions.php">Transactions</a></li>
			<li><a href="../fm_account.php"  class = "active">My Account</a></li>
			<li><a href = "../../fm_homepage.html">Logged In: <?php echo $log; ?></a></li>
		</ul>
	</div>
</div>

<!-- Left Sidebar -->
<div class="leftsidebar"> 
	<?php while ($row = mysqli_fetch_array($user)) { ?>
	<table align="center">
			<tr>
				<td align="center"><img class="img-circle" src="../../images/<?php echo $row['picture'];?>"/></td>
			</tr>
	</table>
	<?php } ?>
	<font color="white"><h2 align="center"> <?php echo $log; ?></h2></font>
	<div class = "menu">
	<ul>
		<li><a href = "../../account/edit_account/fm_edit_account.php">Edit Account</a></li>
		<li><a href = "../../account/edit_card/fm_edit_card.php">Edit Card Info</a></li>
		<li><a href = "../../account/messager/fm_messager1.php">Messager <div class = "dig"><?php if ($digit != 0) { echo $digit;}?></div></a></li>
		<li><a href = "../../account/notifications/fm_notifications.php">Notifications <div class = "num"><?php if ($number != 0) { echo $number;}?></div></a></li>
		<li><a href = "../../account/report_issue/fm_issue_form.php">Report an Issue</a></li>
		<?php if ($check['typ'] == 2 ): ?>
			<span><li><a href = '../admin/fm_admin_vendor_requests.php'>Vendor Requests</a></li>
		<?php endif;?>
		<?php if ($check['typ'] == 2 ): ?>
			<span><li><a href = '../admin/fm_admin_view_users.php'>View Users</a></li>
		<?php endif;?>
		<?php if ($check['typ'] == 2 ): ?>
			<span><li><a href = '../admin/fm_admin_view_issues.php'>View Issues</a></li>
		<?php endif;?>
		<li><a href = 'fm_v_create_advertisement1.php'>Advertisements</a></li>
	</ul>
</div>
</div>
<!-- End Left Sidebar -->

<!-- Right Sidebar -->
<div class="rightsidebar">
	<h3 align="center">Current</h3>
	<?php while ($row = mysqli_fetch_array($data)) { ?>
		<table align="center">
			<tr>
				<td><center><?php echo $row['title'];?></center><br><img src = "../../images/<?php echo $row['file'];?>" height = '150x' width = '150px' /><br><center>Expires: <?php echo $row['expirdate'];?></center></td><hr>
			</tr>
		</table>
	<?php } ?>
</div>
<!-- End Right Sidebar -->

<!-- Center Page -->
<div class="center1">
	<h2 align="center">Advertisements</h2>
	<form name="advertisement" action="fm_v_create_advertisement.php" method="post" enctype="multipart/form-data" onsubmit = "return blank()">
		<p>Title <br><br> <input type="text" id="title" name="title"></p> 
		<p>Upload file <br>
			<br>
			<input type="file" class="button" id = "picture" name="picture" accept="image/gif, image/jpeg, image/png">
		</p>
		<p>Description <br>
			<textarea cols="120" rows="6" id="descr" name="descr" maxlength="300"></textarea>
		</p>
		<p>Expiration Date <br><br> <input type="date" id="expir" name="expir"></p>
		<br>
		<button type="submit" class="button" name="Submit" style= "float:right">Post Advertisement</button>
	</form>
</div>
<!-- End Center Page -->

<!-- Footer -->
<div class = "footer">
	<ul>
		<li><a href = "">Privacy Policy</a></li>
		<li><a href = "">About</a></li>
		<li><a href = "">Contact</a></li>
	</ul>
</div>
<!-- End Footer -->
</body>
</html>

</html>
