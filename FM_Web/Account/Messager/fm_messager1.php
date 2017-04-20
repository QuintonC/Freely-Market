<?php

session_start();
require_once("../../db_constant.php");

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
$reciever = $_GET['id'];

$mysql = "select * from Msg_Notifications where recipient = '$sender'";
$result = $conn->query($mysql);

$sql = "select distinct sender from Messages where reciever = '$sender'";
$content = $conn->query($sql);

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
<title>Freely Market | Messenger</title>
<link rel="stylesheet" type="text/css" href="../../_style.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
<script type="text/javascript">
    function blank()
    {
    var a=document.forms["messager"]["reciever"].value;
    var b=document.forms["messager"]["message"].value;
    if (a==null || a=="",b==null || b=="")
      {
      alert("Please Fill All Required Field");
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

<div class="landing">

    <!-- Block 1 -->
    <div class = "titleAlt">
        <div class="titleCenter">
            <img class="titleLogo" src="../../images/freelyMarketLogo.png"/>
        </div>

        <div class = "login">
            <div class="titleButton">
                <a href="../../listings/fm_listings.php">Listings</a>
            </div>

            <div class="titleButton">
                <a href = "../../transactions/fm_transactions.php">Transactions</a>
            </div>

            <div class="titleButton">
                <a href="../../account/fm_account.php" class = "active">My Account</a>
            </div>

            <div class="titleButton">
                <a href = "../../fm_homepage.html">Logout</a>
            </div>
        </div>
    </div>


    <!-- Block 2 -->
    <div class = "leftsidebar">
        <?php while ($row = mysqli_fetch_array($user)) { ?>
            <table>
                    <tr>
                        <td><img class="img-circle" src="../../images/<?php echo $row['picture'];?>"/></td>
                    </tr>
            </table>
        <?php } ?>
        
        <div class="username">
            Logged in as: <?php echo $log; ?>
        </div>
    

        <div class="brackets">
                
            <div class="menuLink">
                <a href="../../account/edit_account/fm_edit_account.php">Edit Account</a>
            </div>

            <div class="menuLink">
                <a href="../../account/edit_card/fm_edit_card.php">Payment Info</a>
            </div>

            <div class="menuLink">
                
                <a href="../../account/messager/fm_messager1.php">Messenger <?php if ($digit != 0) { echo "(" . $digit . ")";}?></a>
            </div>

            <div class="menuLink">
                <a href="../../account/notifications/fm_notifications.php">Notifications <?php if ($number != 0) { echo "(" . $number . ")";}?></a>
            </div>

            <div class="menuLink">
                <a href = "../../account/fm_my_listings.php">My Listings</a>
            </div>

            <div class="menuLink">
                <a href = "../../account/fm_my_offers.php">My Offers</a>
            </div>

            <div class="menuLink">
                <?php if ($check['typ'] == 2 ): ?>
                    <a href='../../admin/fm_admin_view_users.php'>View Users</a>
                <?php endif;?>
            </div>

            <div class="menuLink">
                <?php if ($check['typ'] == 2 ): ?>
                    <a href='../../admin/fm_admin_view_issues.php'>View Issues</a>
                <?php endif;?>
            </div>

            <div class="menuLink">
                <?php if ($check['typ'] == 1 ): ?>
                    <a href='../../vendor/account_page/fm_v_create_advertisement1.php'>Advertisements</a>
                <?php endif;?>
            </div>

            <div class="menuLink">
                <?php if ($check['typ'] == 2 ): ?>
                    <a href='../../admin/fm_admin_ad_requests.php'>Ad Requests</a>
                <?php endif;?>
            </div>

            <div class="menuLink">
                <?php if ($check['typ'] == 2 ): ?>
                    <a href='../../admin/fm_admin_vendor_requests.php'>Vendor Requests</a>
                <?php endif;?>
            </div>

            <div class="menuLink">
                <a href="../../account/report_issue/fm_issue_form.php">Report an Issue</a>
            </div>
        </div>
    </div>

<!-- Block 3 -->
<div class = "listingOuter">

    <div class = "listings">
    
        <?php while ($row = mysqli_fetch_array($result)) : ?>	
	
        <div class="item">

            <div class="listingTitle">
                <?php echo $row['message']; ?>
            </div>


            <div class="listingTitle">
                <?php echo $row['sender']; ?>
            </div>
		
            <div class="listingTitle">
                <?php echo $row['created']; ?>
            </div>
		
		<a class="infoButton" href = "fm_view_msg_notif.php?id=<?php echo $row['msgid']; ?>">View</a><br>
		<a class="infoButton" href = "fm_delete_msg_notif.php?id=<?php echo $row['msgid']; ?>">Delete</a>
        
        </div>

        <?php endwhile;?>

    </div>

    <form name = "messager" action = "fm_messager.php" method="get" onsubmit = "return blank()" >
    <p style="color:white !important;">To: <input type="text" id = "reciever" name ="reciever" value = "<?php echo $reciever; ?>" maxlength = "15"></p>
    
    <p style="color:white !important;">Message: </p>
    
    <textarea rows = "4" cols = "50" id = "message" name = "message"></textarea>
    
    <br />
    <br />
    
    <button type="submit" value="Login">Send Message</button>
    
    </form>
</div>

<!-- Block 4 -->
<div class = "rightsidebar">

<div class = "contacts">
<table>
<?php while ($set = mysqli_fetch_array($content)) { ?>
	<tr>
		<td><a href = "fm_conversation.php?contact=<?php echo $set['sender']; ?>"><?php echo $set['sender']; ?></a></td>
	</tr>
<?php }?>
</table>
</div>

</div>

    <div class = "footer">
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