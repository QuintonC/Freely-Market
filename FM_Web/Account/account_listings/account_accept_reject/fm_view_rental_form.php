<?php

session_start();
require_once("../../../db_constant.php");
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}
	
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}
	
$username = $_SESSION['username'];

#Get from url
$prid = $_GET['id'];

$mysql = "select * from Pending_Rental where prid = '$prid'";
$content = $conn->query($mysql);
$set = mysqli_fetch_array($content);
$username = $set['username'];
$reason = $set['reason'];
$duration = $set['duration'];
$destination = $set['destination'];

?>

<html>

<head>
	<title>Rental Request Form</title>

	
<style>
	
.title {
margin: auto;
width: 100%;
height: 125px;
background-color: #ff4d4d;
}

.title .header {
left: 42%;
position: absolute;
font-family: "Brush Script MT", cursive;
font-size: 24px;
}

.title .logo {
top: 25px;
left: 2%;
position: absolute;
}


.center{
width: 100%;
height: 475px;
background-color: #ffe6e6;
background-image: url("../../../images/bw_rack.jpg");
}

.center .forms {
position: relative;
top: 75px;
left: 450px;
height: 250px;
width: 400px;
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
top: 600px;
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
	
</style>
</head>

<body>

<!-- Block 1 -->
<div class = "title">

<div class = "logo">
<img src = "../../../images/logo.png" height = "100px" width = "200px" />
</div>

<div class = "header">
<h1>Freely Market</h1>
</div>

</div>

<!-- Block 2 -->

<div class = "center">
<div class = "forms">
<center><h3>Rental Request Form</h3></center>

<p><b>Username:  </b><?php echo $username; ?></p>
<p><b>Reason:  </b><?php echo $reason; ?></p>
<p><b>Duration: </b><?php echo $duration; ?></p>
<p><b>Destination:  </b><?php echo $destination; ?></p>
<center><a href = "fm_accept_rentoffers.php?id=<?php echo $set['prid']; ?>">Accept</a>
<a href = "fm_reject_rentoffers.php?id=<?php echo $set['prid']; ?>">Reject</a></center>

</div>
</div>

<!-- Block 3 -->
<div class = "footer">

<ul>
<li><a href = "">Privacy Policy</a></li>
<li><a href = "">About</a></li>
<li><a href = "">Contact</a></li>
<li style = "float:left"><a href = "">Social Links</a></li>
</ul>

</div>

</body>
</html>