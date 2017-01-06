<?php 


	require("connect.php");
	require("dao.php");

	$username = htmlentities($_POST["username"]);
	$password = htmlentities($_POST["password"]);

	$returnValue = array();

	if(empty($username) || empty($password)) {
		$returnValue["status"] = "Error";
		$returnValue["message"] = "Either your username or password was incorrect.";
		echo json_encode($returnValue);
		return;
	}

	$dao = new dao();
	$dao->openConnection();
	$userDetails = $dao->getUserDetails($username);

	if(!empty($userDetails)) {
		$returnValue["status"] = "Error";
		$returnValue["message"] = "Username already exists";
		echo json_encode($returnValue);
		return;
	}

	$secure_password = md5($password); //lock that stuff up, fam

	$result = $dao->registerUser($username,$secure_password);

	if($result) {
		$returnValue["status"] = "Success";
		$returnValue["message"] = "You have successfully registered for Freely Market";
		echo json_encode($returnValue);
		return;
	}

	$dao->closeConnection();

?>