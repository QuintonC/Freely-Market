<?php

$link = mysqli_connect('db.soic.indiana.edu', 'caps16_team12', 'my+sql=caps16_team12', 'caps16_team12');

if (!$link) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die("Connection failed: " . $link->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];
$first_name = $_POST['fname'];
$last_name = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];

$encpw = md5($password);

$stmt = "INSERT INTO User_Accounts(username, password, first_name, last_name, email, phone) VALUES ($username, $encpw, $first_name, $last_name, $email, $phone)";


if (mysqli_query($link, $stmt)) {
	$response['error'] = false;
	$response['message'] = 'User created successfully';
} else {
    $response['error']=true;
    $response['message']='Could not create user';
    echo "Error: " . $stmt . mysqli_error($link);
}

echo json_encode($response);

?>