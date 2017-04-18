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
	<title>Update Card Information</title>
	
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
<body>

<!--<style>

ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

li {
    float: left;
	border-right: 1px solid #bbb;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover {
    background-color: 	#00008B;
}

.active {
    background-color: 	#00008B;
}
	
.title {
margin: auto;
width: 100%;
height: 150px;
background-color: #ff4d4d;
}


.title .search {
top: 15px;
left: 2%;
position: absolute;
}

.title .navbar{
top: 15px;
right: 2%;
position: absolute;
font-family: Arial, Helvetica, sans-serif;
}

.title .header {
top: 10px;
left: 42%;
position: absolute;
font-family: "Brush Script MT", cursive;
font-size: 24px;
}

.center{
width: 100%;
height: 700;
background-color: #ffe6e6;
background-image: url("../../images/bw_rack.jpg");
position: absolute;
}


.center .tables {
position: relative;
top: 90px;
left: 400px;
height: 75px;
width: 500px;
background-color: #FFFFFF;
border-style: solid;
border-width: 2px;
padding: 15px;	
}

.center .forms {
position: relative;
top: 100px;
left: 400px;
height: 200px;
width: 500px;
background-color: #FFFFFF;
border-style: solid;
border-width: 2px;
padding: 15px;
}

.footer {
margin: auto;
width: 100%;
background-color: #000000;
color: #FFFAF0;
position: absolute;
top: 810px;
}

.footer ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

.footer li {
    float: right;
	border-right: 1px solid #bbb;
}

.footer li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}
	
</style>-->

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
	<div class = "edit_card">
		<form name = "myForm" action="fm_update_card.php" method="post" onsubmit = "return blank()">
			<input type="hidden" value="add" name="choice">
			<p>Card Name: <input type="text" id = "cname" name="cname" maxlength = "30" value = "<?php echo $card_name; ?>"></p>
			<p>Card Number: <input type="text" id ="cnum" name="cnum" maxlength = "30" value = "<?php echo $card_number; ?>"></p>
			<p>Expiration Date: <input type="text" id = "expr" name="expr" maxlength = "30" value = "<?php echo $expr; ?>"></p>
			<p>Security: <input type="text" id = "cvv" name="cvv" value = "<?php echo $cvv; ?>"></p>
			<br />
			<button class="button" type="submit" name = "submit">Update Card</button>
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