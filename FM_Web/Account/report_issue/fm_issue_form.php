<?php
session_start();
require_once("../../db_constant.php");
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

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="../../_style.css">
<meta charset="utf-8">
<link rel="shortcut icon" href="../../images/favicon.ico" type="image/x-icon" />

<script type="text/javascript">

<!-- Ensures forms are not blank -->
    function blank()
    {
    var a=document.forms["complaint"]["description"].value;
    
    if (a==null || a=="",)
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

<title>Report Issue Form</title>
</head>
<!--<style>
	body {
		margin: 0px;
	}
	
	.header {
		width: 100%;
		height: 20%;
		padding-left: 1px;
		background-color:#4B4B4B;
		position: absolute;
	}	

	
	.header .title {
		position: absolute;
		left: 40%;
		top: 0.5%;
		font-style: italic;
		font-family: "Brush Script MT";
		font-size: 30px;
	}
	
	
	.header .image {
		padding-top: 2%;
		padding-left: 0.5%;
	}
	
	
	.information {
		text-align: left;
		padding-top: 5px;
		padding-left: 1px;
		font-family: Constantia, "Lucida Bright", "DejaVu Serif", Georgia, "serif";
		width: 100%;
		height: 100%;
		top: 20%;
		background-color: #ffe6e6;
		position: absolute;
	}
	
	.button {
		background-color: #4B4B4B;
		border: none;
		border-radius: 3px;
		color: white;
		padding: 5px 15px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 12px;
		margin: 4px 2px;
		cursor: pointer;
	}
	
	
	footer {
		margin: auto;
		top: 95%;
		bottom: 0%;
		width: 100%;
		background-color: #4B4B4B;
		color: #FFFAF0;
		position: absolute;
	}
	

	.footer ul {
		list-style-type: none;
		margin: 0px;
		padding: 0px;
		overflow: hidden;
		background-color: #333;
	}
	
	
	.footer li {
		float: right;
		border-right: 1px solid #bbb;
		border-left: 1px solid #bbb;
	}
	
	
	.footer li a {
		display: block;
		color: white;
		text-align: center;
		padding: 14px 16px;
		text-decoration: none;
	}
	
</style>-->

<body>
<!-- Header -->
<div class="title">
	<div class="search">
		<h3 align="center"><a href="../fm_account.php"><img src="../../images/logo.png" height = "90px" width = "160px"/></a></h3><br />
	</div>

	<div class ="header">
		<h1 align="center">Freely Market</h1>
	</div>
</div>
<!-- End Header -->

<!-- Form -->
<div class="application">
	<form name="issue" action="fm_issued.php" method="post" onsubmit="return blank()">
		<fieldset>
			<input type="hidden" value="add" name="choice">
			<legend> Report an Issue</legend>
				Describe the situation below: <br><textarea cols="200" rows="10" id="description" name="description" maxlength="400"></textarea>
				<input type="submit" class="button" value="Submit">
			</fieldset>
		</form>
</div>
<!-- End Form -->

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