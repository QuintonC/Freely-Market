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
	
	$query3 = "SELECT token FROM User_Accounts WHERE username='$get_username' AND token='$get_token'";
	$da = $conn->query($query3);
	$numrows1 = $da->num_rows;
	
	if($numrows1 == 1)
	{
?>	
		<html>
		<head>
		<title>Reset Password</title>
		</head>
		<style>
		body {
			margin: 0px;
		}

		.header {
			width: 100%;
			height: 16%;
			background-color:#4B4B4B;
			position: absolute;
		}	


		.header .title {
			position: absolute;
			left: 41%;
			bottom: -15px;
			font-style: italic;
			font-family: "Brush Script MT";
			font-size: 30px;
		}

		.header .search {
			top: 14px;
			left: 2%;
			position: absolute;
		}

		.center {
			position: absolute;
			width: 100%;
			height:100%;
			top: 15%;
			background-color: #4FAB48;
			border: thin;
			border-bottom-color: white;
		}

		.center .reset {
			align-content: center;
			top: 25%;
			left: 35%;
			right: 35%;
			position: absolute;
			height: 200px;
			width: 30%;
			padding: 20px;
			border:solid;
			border-color: white;
			background-color: #4B4B4B;
			font-family: Constantia, "Lucida Bright", "DejaVu Serif", Georgia, "serif";

		}

		.password {
			align-content: center;
			display: block;
			color: white;
		}

		</style>

		<body>
		<!-- Header -->
		<div class="header">

			<div class="search">
				<img src="../images/logo.png" height = "90px" width = "175px"/>
			</div>

			<div class ="title">
				<h1>Freely Market</h1>
			</div>
		</div>
		<!-- End Header -->

		<!-- Center Page -->
		<div class = "center">
			<div class = "reset">
				<center>
					<form action='fm_password_reset.php?token=<?php echo $get_token?>' method='POST'>
						<p>Enter a new password<br><input type='password' name='newpass'><br>
						<p>Re-enter your password<br><input type='password' name='newpass1'><p>
						<input type='hidden' name='username' value='<? echo $get_username ?>'>
						<input type='submit' value='Update Password'>
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
	{
		echo "expired link";
	}
}
else
{?>
	<html>
	<head>
	<title>Recover Password</title>
	</head>
	<style>
		body {
			margin: 0px;
		}

		.header {
			width: 100%;
			height: 16%;
			background-color:#4B4B4B;
			position: absolute;
		}	


		.header .title {
			position: absolute;
			left: 41%;
			bottom: -15px;
			font-style: italic;
			font-family: "Brush Script MT";
			font-size: 30px;
		}

		.header .search {
			top: 14px;
			left: 2%;
			position: absolute;
		}

		.center {
			position: absolute;
			width: 100%;
			height:100%;
			top: 15%;
			background-color: #4FAB48;
			border: thin;
			border-bottom-color: white;
		}
	
		.center .recover {
			align-content: center;
			top: 25%;
			left: 35%;
			right: 35%;
			position: absolute;
			height: 200px;
			width: 30%;
			padding: 20px;
			border:solid;
			border-color: white;
			background-color: #4B4B4B;
			font-family: Constantia, "Lucida Bright", "DejaVu Serif", Georgia, "serif";

		}

		.login {
			align-content: center;
			display: block;
			color: white;
		}
	
	</style>

	<body>
	<!-- Header -->
	<div class="header">

		<div class="search">
			<img src="../images/logo.png" height = "90px" width = "175px"/>
		</div>

		<div class ="title">
			<center>
				<h1>Freely Market</h1>
			</center>
		</div>
	</div>
	<!-- End Header -->

	<!-- Center Page -->
	<div class = "center">
	  <div class = "recover">
			<center>
				<form action='fm_recover_password.php' method='POST'>
					<p>Enter your username<br><input type='text' name='username'><p>
					<p>Enter your email<br><input type='text' name='email'><p>
					<input type='submit' value='Submit' name='submit'>
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

			echo '<script type="text/javascript"> alert("Check your email!"); location="fm_recover_password.php";</script>';
		}
		else
		{
			echo '<script type="text/javascript"> alert("No account associated with that username or email."); location="fm_recover_password.php";</script>';
		}
	}
	}
?>