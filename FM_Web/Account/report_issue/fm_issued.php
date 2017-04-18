
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

//Assign variables
$description = $_POST['description'];


//MySQL Injection
$description = strip_tags($description);


$vendor = stripslashes($descrption);


function appSubmit(){
	echo '<script type="text/javascript"> alert("Thanks for your feedback! We will get right on solving the issue."); location="../fm_account.php";</script>';
}

//Insert into the table
$sql = "INSERT INTO Issue (description, aid) 
VALUES ('$description', '$aid')";

if ($conn->query($sql) === TRUE) {
	appSubmit();
	//header("Location: fm_issue_submission.html");
	exit;
} else {
	echo "Error: " . $sql . "<br>" . $con->error;
}

mysqli_close($con);

?>