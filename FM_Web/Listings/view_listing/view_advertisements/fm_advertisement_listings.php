<?php
session_start();
require_once("../../../db_constant.php");

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

//Select Advertisements
$sql1 = "select * from Advertisements";
$data = $conn->query($sql1);
?>


<html>
<head>
<title>Advertisements</title>
</head>

<body>
<style>
body{
	padding: 0px;
	margin: 0px;
}	
	
a:link {
    color: green;
}
a:visited {
    color: white;
}
a:hover {
    color: #13A61B;
}
	
.title {
	margin: auto;
	width: 100%;
	height: 15%;
	background-color: #13A61B;
	position: absolute;
}
.title .header {
	width: 100%;
	position: absolute;
	top: 1%;
	font-family: "Brush Script MT", cursive;
	font-size: 24px;
}
.title .logo {
	top: 0.5%;
	left: 2%;
	position: absolute;
}

.center {
	top: 15%;
	width: 100%;
	color: white;
	background-color: #333;
	border-style: solid;
	border-color: white;
	border-width: 2px;
	padding: 10px;
	padding-bottom: 5px;
	position: absolute;
	font-family: Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, "sans-serif";
}
	
	.center table, th, td {
	padding-top: 15px;
	padding-bottom: 15px;
	padding-left: 50px;
	padding-right: 50px;
    text-align: center;
	width: 100%;
}
	
.center th {
    background-color:#13A61B;
    color: black;
	width: 100%;
}

.center tr{
	background-color: #333;
}

.center tr:hover {
	background-color: black;
}

	
input[type=text] {
	width: 100%;
	padding: 10px 10px;
	box-sizing:border-box;
	border-color: white;
	border-radius: 3px;
}
	
input[type=password] {
	width: 100%;
	padding: 12px 10px;
	box-sizing: border-box;
	border-color: white;
	border-radius: 3px;
}
	
.photo {
	border: medium;
	border-color: black;
	border-radius: 5px;
	color: white;
	padding: 5px 15px;
	margin: 4px 2px;
}
</style>

<!-- Block 1 -->
<div class = "title">
<div class = "header">
	<h1 align="center">Freely Market</h1>
</div>
	<div class = "logo">
		<a href="../../../fm_homepage.html"><img src="../../../images/logo.png" height = "100px" width = "200px"/></a>
	</div>
</div>


<!-- Block 2 -->
<div class = "center">
	<table>
			<col width="25%">
			<col width="25%">
			<col width="25%">
			<col width="25%">
			<tr>
				<th>Details</th>
				<th>Title</th>
				<th>Advertisement</th>
				<th>Vendor</th>
			</tr>
			<?php while ($row = mysqli_fetch_array($data)) { ?>
			<tr>
				<td><font color="white"><?php echo $row['description'];?><br>Offer valid until <?php echo $row['expirdate'];?></font></td>
				<td><font color="white"><?php echo $row['title'];?>
				<div class="photo">
					<td><img src = "../../../images/<?php echo $row['file'];?>" height = '200x' width = '300px' /></td>
				</div>
				<td><font color="white"><?php echo $row['username'];?></font></td>
			</tr>
		<?php } ?>
	</table>
</div>

</body>
</html>