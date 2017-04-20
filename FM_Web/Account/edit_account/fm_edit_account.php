<?php

# Source
# 1.Javascript validation for empty input field. (n.d.). Retrieved April 19, 2017, from http://stackoverflow.com/questions/3937513/javascript-validation-for-empty-input-field

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


$password = $_SESSION['password'];

#Shows current information
$sql = "select * from User_Accounts where username = '$username' and password = '$password' limit 1";
$result = $conn->query($sql);
$set = mysqli_fetch_array($result);
$first_name = $set['first_name'];
$last_name = $set['last_name'];
$email = $set['email']; 
$phone = $set['phone'];
$picture = $set['picture'];


?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="../../_style.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
	<title>Freely Market | Edit Account</title>
	
<script type="text/javascript">
    function blank()
    {
    var a=document.forms["myForm"]["fname"].value;
    var b=document.forms["myForm"]["lname"].value;
	var c=document.forms["myForm"]["email"].value;
	var d=document.forms["myForm"]["phone"].value;
	var e=document.forms["myForm"]["picture"].value;
    if (a==null || a=="",b==null || b=="",c==null || c=="",d==null || d=="",e==null || e=="")
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
<body>

<div class="landing">

    <!-- Block 1 -->
    <div class = "titleAlt">
        <div class="titleCenter">
            <img class="titleLogo" src="../../images/freelyMarketLogo.png"/>
        </div>

        <div class = "login">
            <div class="titleButton">
                <a href = "../../listings/fm_listings.php">Listings</a>
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

	<div class = "forms">
		<div class = "edit_account">
			<form class="glow" name = "myForm" action="fm_update_account.php" method="post" enctype="multipart/form-data" onsubmit = "return blank()">
				<input type="hidden" value="add" name="choice">
				<p>First Name: <input type="text" id = "fname" name="fname" maxlength = "30" value = "<?php echo $first_name; ?>"></p>
				<p>Last Name: <input type="text" id ="lname" name="lname" maxlength = "30" value = "<?php echo $last_name; ?>"></p>
				<p>Email: <input type="text" id = "email" name="email" maxlength = "30" value = "<?php echo $email; ?>"></p>
				<p>Phone: <input type="text" id = "phone" name="phone" pattern ='[0-9]{10}' value = "<?php echo $phone; ?>"></p>
				<p>Picture: <input class="button" type="file" id = "picture" name="picture" accept="image/gif, image/jpeg, image/png"></p>
				<center><img src ="../../images/<?php echo $picture; ?>" height = '170px' width = '220px' /></center>
				<br />
				<button class="buttonAlt" type="submit" name = "submit">Update Account</button>
			</form>
		</div>
	</div>

	<!-- Block 3 -->
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