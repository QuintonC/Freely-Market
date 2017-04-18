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
		exit();
	}

$username = $_SESSION['username'];
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
	<title>Update Account</title>
	
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

<!-- Block 2 -->

<div class = "forms">
	<div class = "edit_account">
		<form name = "myForm" action="fm_update_account.php" method="post" enctype="multipart/form-data" onsubmit = "return blank()">
			<input type="hidden" value="add" name="choice">
			<p>First Name: <input type="text" id = "fname" name="fname" maxlength = "30" value = "<?php echo $first_name; ?>"></p>
			<p>Last Name: <input type="text" id ="lname" name="lname" maxlength = "30" value = "<?php echo $last_name; ?>"></p>
			<p>Email: <input type="text" id = "email" name="email" maxlength = "30" value = "<?php echo $email; ?>"></p>
			<p>Phone: <input type="text" id = "phone" name="phone" pattern ='[0-9]{10}' value = "<?php echo $phone; ?>"></p>
			<p>Picture: <input class="button" type="file" id = "picture" name="picture" accept="image/gif, image/jpeg, image/png"></p>
			<center><img src ="../../images/<?php echo $picture; ?>" height = '170px' width = '220px' /></center>
			<br />
			<button class="button" type="submit" name = "submit">Update Account</button>
		</form>
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