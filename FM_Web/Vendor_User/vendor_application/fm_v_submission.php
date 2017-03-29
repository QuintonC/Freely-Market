<?php
//session_start();
//require_once("db_prim.php");

//$con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
//Check connection
//if (mysqli_connect_errno()) {
//	echo "Failed to connect to MySQL:" .mysqli_connect_error();
//	}

//$sql = "select * from Vendor_Application where vendor='$vendor'";
//$result = $con->query($sql);

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<title>Thank you!</title>
<style>
	body {
		margin: 0px;
	}
	
	
	.header {
		width: 100%;
		height: 135px;
		background-color:#4B4B4B;
		position: absolute;
	}	

	
	.header .title {
		position: absolute;
		left: 40%;
		bottom: -15px;
		font-style: italic;
		font-family: "Brush Script MT";
		font-size: 30px;
	}
	
	
	.header .image {
		padding-top: 20px;
		padding-left: 5px;
	}
	
	.information {
		margin: auto;
		text-align: left;
		padding-left: 10px;
		font-family: Constantia, "Lucida Bright", "DejaVu Serif", Georgia, "serif";
		width: 100%;
		height: 565px;
		background-color: #ffe6e6;
		position: absolute;
		top: 135px;
	}
	
	.footer {
		margin: auto;
		width: 100%;
		background-color: #4B4B4B;
		color: #FFFAF0;
		position: absolute;
		top: 700px;
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
		border-left: 1px solid #bbb;
	}
	
	
	.footer li a {
		display: block;
		color: white;
		text-align: center;
		padding: 14px 16px;
		text-decoration: none;
	}
</style>
</head>

<body>

<!-- Header -->
<div class="header">
	<div class="image">
		<img src="LogoMakr-layerExport.png" height = "90px" width = "175px"/>
	</div>

	<div class ="title">
		<h1>Freely Market</h1>
	</div>
</div>
<!-- End Header -->

<!-- Thank you form -->
<div class="information"/>
	<form action="fm_v_application.php" method="get">
	<h3>Thank you, <?php echo $_GET['vendor']; ?>for your application!</h3>
	<p>Return to <a href="fm_homepage.html"/>homepage.</p>
</div>
<!-- End Thank you form -->

<!-- Footer -->
<div class = "footer">
	<ul>
		<li><a href = "">Privacy Policy</a></li>
		<li><a href = "">About</a></li>
		<li><a href = "">Contact</a></li>
		<li style = "float:left"><a href = "">Social Links</a></li>
	</ul>
</div>
<!-- End Footer -->
</body>
</html>
