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
	
	if($numrows1 == 1)
	{
?>	
		<html>
		<head>
		<link rel="stylesheet" type="text/css" href="../_style.css">
		<title>Reset Password</title>
		</head>
		<body>
		<!-- Header -->
		<!-- Block 1 -->
		<div class = "title">
			<div class = "search">
				<h3 align="center"><a href="../fm_homepage.html"><img src = "../images/logo.png" height = "90px" width = "160px" /></a></h3><br/>
			</div>

			<div class = "header">
				<h1 align="center">Freely Market</h1>
			</div>
		</div>
		<!-- End Header -->

		<!-- Center Page -->
		<div class = "forms">
			<div class = "user_pass">
				<center>
					<form action='fm_password_reset.php?token=<?php echo $get_token?>' method='POST'>
						<p>Enter a new password<br><input type='password' name='newpass'><br>
						<p>Re-enter your password<br><input type='password' name='newpass1'><p>
						<input type='hidden' name='username' value='<? echo $get_username ?>'>
						<input class='button' type='submit' value='Update Password'>
					</form>
				</center>		
			</div>
		</div>
		<!-- End Center Page -->
		</body>
		</html>
<?php	
	}
	else
	{?>
		<html>
		<head>
		<link rel="stylesheet" type="text/css" href="../_style.css">
		<title>Reset Password</title>
		</head>
		<body>
		<!-- Header -->
		<!-- Block 1 -->
		<div class = "title">
			<div class = "search">
				<h3 align="center"><a href="../fm_homepage.html"><img src = "../images/logo.png" height = "90px" width = "160px" /></a></h3><br/>
			</div>

			<div class = "header">
				<h1 align="center">Freely Market</h1>
			</div>
		</div>
		<!-- End Header -->

		<!-- Center Page -->
		<div class = "forms">
			<div class="error">
			<br>
			<br>
			<br>
				<font color="white"><h1 align="center">404 Page not found</h1></font>
			</div>
		</div>
		<!-- End Center Page -->
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
	<title>Recover Password</title>
	</head>
	<body>
	<!-- Block 1 -->
	<div class = "title">
		<div class = "search">
			<h3 align="center"><a href="../fm_homepage.html"><img src = "../images/logo.png" height = "90px" width = "160px" /></a></h3><br/>
		</div>

		<div class = "header">
			<h1 align="center">Freely Market</h1>
		</div>
	</div>
	<!-- End Header -->

	<!-- Center Page -->
	<div class = "forms">
	  <div class = "user_pass">
			<center>
				<form action='fm_recover_password.php' method='POST'>
					<p>Enter your username<br><input type='text' name='username'><p>
					<p>Enter your email<br><input type='text' name='email'><p>
					<input class='button' type='submit' value='Submit' name='submit'>
				</form>
			</center>		
		</div>
	</div>
	<!-- End Center Page -->
	</body>
	</html>
	
<?php
		
	if(isset($_POST['submit']))
	{
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
		
		if($numrow!=0)
		{
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