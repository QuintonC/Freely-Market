<?php
//session_start();
require_once("../db_constant.php");

$conn=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
//Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL:" .mysqli_connect_error();
	}

if($_GET['token'])
{
	$get_username = $_GET['username'];
	$get_token = $_GET['token'];
	
	//  Prevent MySQL injection 
	$get_username = strip_tags($get_username);
	$get_token = strip_tags($get_token);

	$get_username = stripslashes($get_username);
	$get_token = stripslashes($get_token);
	
	$query3 = "SELECT token FROM User_Accounts WHERE username='$get_username' AND token='$get_token'";
	$da = $conn->query($query3);
	$numrows1 = $da->num_rows;
	
	if($numrows1 == 1) {
?>	
		<html>
		<head>
		<link rel="stylesheet" type="text/css" href="../_style.css">
		<title>Freely Market | Recover Password</title>
		<link rel="stylesheet" type="text/css" href="../_style.css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
		</head>
		<body>
		
		<div class="landing">
			<!-- Block 1 -->
			<div class="titleAlt">
				<div class="titleCenter">
					<img class="titleLogo" src="../images/freelyMarketLogo.png"/>
				</div>

				<div class="login">
					<div class="titleButton">
						<a href = "../fm_homepage.html">Home</a>
					</div>

					<div class="titleButton">
						<a href="fm_login.html">Login</a>
					</div>
				</div>
			</div>

			<!-- Block 2 -->
			<div class="forms">
				<div class="user_pass">
					<form class="glow" action='fm_password_reset.php?token=<?php echo $get_token?>' method='POST'>
						<input type='text' class="password" name='newpass' placeholder="Enter a new password" autocomplete="off">
						<input type='text' class="password" name='newpass1' placeholder="Re-enter your password" autocomplete="off">
						<input type='hidden' name='username' value='<? echo $get_username ?>'>
						<input class='buttonAlt' type='submit' value='Update Password'>
					</form>
				</div>
			</div>
		
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
<?php	
	} else { ?>
		<html>
		<head>
		<link rel="stylesheet" type="text/css" href="../_style.css">
		<title>Freely Market | Recover Password</title>
		<link rel="stylesheet" type="text/css" href="../_style.css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
		</head>
		<body>
		
		<div class="landing">

		<!-- Block 1 -->
			<div class="titleAlt">
				<div class="titleCenter">
					<img class="titleLogo" src="../images/freelyMarketLogo.png"/>
				</div>

				<div class="login">
					<div class="titleButton">
						<a href = "../fm_homepage.html">Home</a>
					</div>

					<div class="titleButton">
						<a href="fm_login.html">Login</a>
					</div>
				</div>
			</div>

			<!-- Center Page -->
			<div class="forms">
				<div class="fourohfour">
					Something went wrong. Perhaps the link you've clicked has expired?
				</div>
			</div>

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
<?php
	}
}
else
{
?>
	<html>
	<head>
	<link rel="stylesheet" type="text/css" href="../_style.css">
	<title>Freely Market | Recover Password</title>
	<link rel="stylesheet" type="text/css" href="../_style.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
	</head>
	<body>
	
	<div class="landing">

		<!-- Block 1 -->
		<div class="titleAlt">
			<div class="titleCenter">
				<img class="titleLogo" src="../images/freelyMarketLogo.png"/>
			</div>

			<div class="login">
				<div class="titleButton">
					<a href = "../fm_homepage.html">Home</a>
				</div>

				<div class="titleButton">
					<a href="fm_login.html">Login</a>
				</div>
			</div>
		</div>

		<!-- Block 2 -->
		<div class="forms">
		  <div class="user_pass">
				<form class="glow" action='fm_recover_password.php' method='POST'>
					<input type='text' name='username' placeholder="Enter your username" autocomplete="off">
					<input type='text' name='email' placeholder="Enter your email" autocomplete="off">
					<input class='buttonAlt' type='submit' value='Submit' name='submit'>
				</form>
			</div>
		</div>

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
	
<?php
		
	if(isset($_POST['submit'])) {
		
		$username = $_POST['username'];
		$email = $_POST['email'];
		
		//  Prevent MySQL injection 
		$username = strip_tags($username);
		$email = strip_tags($email);

		$username = stripslashes($username);
		$email = stripslashes($email);
		
		$query1 = "SELECT aid FROM User_Accounts WHERE username='$username' AND email='$email'";
		$runquery = $conn->query($query1);
		$numrow = $runquery->num_rows;
		
		if($numrow!=0) {
			$token = rand(10000,1000000);

			$to = $email;
			$subject = "Password reset";
			$body = "This is an automated email. Click link http://cgi.soic.indiana.edu/~team12/register_login/fm_recover_password.php?token=$token&username=$username";

			$query2 = "UPDATE User_Accounts SET token='$token' WHERE username='$username'";
			$conn->query($query2);

			mail($to, $subject, $body);

			echo '<script type="text/javascript"> alert("Check your email!"); location="../fm_homepage.html";</script>';
		}
		else
		{
			echo '<script type="text/javascript"> alert("No account associated with that username or email."); location="fm_recover_password.php";</script>';
		}
	}
	}
?>