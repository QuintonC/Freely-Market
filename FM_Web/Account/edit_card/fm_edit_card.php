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

$aid = $_SESSION['uid'];

#Displays current card information
$sql = "select * from CardInfo where aid = '$aid' limit 1";
$result = $conn->query($sql);
$set = mysqli_fetch_array($result);
$card_name = $set['card_name'];
$card_number = $set['card_number'];
$expr = $set['expr']; 
$cvv = $set['cvv'];

?>


<html>
<head>
	<link rel="stylesheet" type="text/css" href="../../_style.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
	<title>Freely Market | Update Card Information</title>
	
<script type="text/javascript">
    function blank()
    {
    var a=document.forms["myForm"]["fname"].value;
    var b=document.forms["myForm"]["lname"].value;
	var c=document.forms["myForm"]["email"].value;
	var d=document.forms["myForm"]["phone"].value;
    if (a==null || a=="",b==null || b=="",c==null || c=="",d==null || d=="")
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
		<div class = "edit_card">
			<form class="glow" name = "myForm" action="fm_update_card.php" method="post" onsubmit = "return blank()">
				<input type="hidden" value="add" name="choice">
				<p>Card Name: <input type="text" id = "cname" name="cname" maxlength = "30" value = "<?php echo $card_name; ?>"></p>
				<p>Card Number: <input type="text" id ="cnum" name="cnum" maxlength = "30" value = "<?php echo $card_number; ?>"></p>
				<p>Expiration Date: <input type="text" id = "expr" name="expr" maxlength = "30" value = "<?php echo $expr; ?>"></p>
				<p>Security: <input type="text" id = "cvv" name="cvv" value = "<?php echo $cvv; ?>"></p>
				<br />
				<button class="buttonAlt" type="submit" name = "submit">Update Card</button>
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