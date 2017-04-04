<?php

session_start();
require_once("../db_constant.php");

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
	

#Show Vendor Requests 
$sql = "SELECT * FROM Vendor_Application WHERE confirmed != 'confirmed' AND confirmed != 'denied'";
$result = $conn->query($sql);

$confirm = "Select confirmed FROM Vendor_Application";
$con = $conn ->query($confirm);
$array = mysqli_fetch_array($con);


?>


<div class = "vendor_requests">
<center><h2>Vendor Requests</h2></center>
<table>
	<tr>
		<th>Application ID</th>
		<th>Vendor</th>
		<th>Name</th>
		<th>Email</th>
		<th>Owner</th>
		<th>Owner Name</th>
		<th>Owner Email</th>
		<th>Street</th>
		<th>City</th>
		<th>State</th>
		<th>Zipcode</th>
		<th>Years In Business</th>
		<th>Why Did You Join</th>
		<th>Future Projects</th>
		<th>Deny Request</th>
		<th>Accept Request</th>
	</tr>
	<?php while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><?php echo $row['appid']; ?></td>
		<td><?php echo $row['vendor']; ?></td>
		<td><?php echo $row['fname']; ?></td>
		
		
		<td><?php echo $row['email']; ?></td>
		<td><?php echo $row['owner']; ?></td>
		<td><?php echo $row['ofname']; ?></td>
		
		
		<td><?php echo $row['oemail']; ?></td>
		<td><?php echo $row['street']; ?></td>
		<td><?php echo $row['city']; ?></td>
		<td><?php echo $row['state']; ?></td>
		<td><?php echo $row['zip']; ?></td>
		<td><?php echo $row['years']; ?></td>
		<td><?php echo $row['whyjoin']; ?></td>
		<td><?php echo $row['futureproj']; ?></td>
		<td><a href = "fm_delete_vendor_request.php?id=<?php echo $row['appid']; ?>"><?php echo "Deny Request";?></a></td>
		<td><a href = "fm_accept_vendor_request.php?id=<?php echo $row['appid']; ?>"><?php echo "Accept Request";?></a></td>
		
	</tr>
	<?php } ?>
</table>
</div>