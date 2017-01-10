
<?php

#Initiates session variables
session_start();

#References data base connection variables
require_once("db_constant.php");
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}

#Gets form entries
$username = $_POST['username'];
$password = $_POST['password'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$picture = $_POST['picture'];

#Query to create user account
$sql = "INSERT INTO User_Accounts (username,password,first_name,last_name,email,phone,picture) VALUES ('$username','$password','$fname','$lname','$email','$phone','$picture')";

if (!$conn->query($sql) === TRUE) {
	echo "Error: " . $sql . "<br>" . $conn->error;
	exit;
}

?>


<html>
<head>
	<title>Card Info</title>
	
<script type="text/javascript">
<!-- ensures froms are not blank -->
    function blank()
    {
    var a=document.forms["myForm"]["name"].value;
    var b=document.forms["myForm"]["number"].value;
    var c=document.forms["myForm"]["expr"].value;
    var d=document.forms["myForm"]["cvv"].value;
    if (a==null || a=="",b==null || b=="",c==null || c=="",d==null || d=="")
      {
      alert("Please Fill All Required Field");
      return false;
      }
    }

	function maxLength(char) {    
    if (!('maxLength' in char)) {
        var max = char.attributes.maxLength.value;
        char.onkeypress = function () {
            if (this.value.length >= max) return false;
        };
    }
}

maxLength(document.getElementById("text"));
</script>
	
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
height: 480px;
background-color: #ffe6e6;
background-image: url("tree.jpg");
}

.center .forms {
position: relative;
top: 75px;
left: 400px;
height: 250px;
width: 500px;
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
top: 605px;
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
<img src = "logo.png" height = "100px" width = "200px" />
</div>

<div class = "header">
<h1>Freely Market</h1>
</div>

</div>

<!-- Block 2 -->
<div class = "center">

<div class = "forms">

<form name = "myForm" action="fm_add_card.php" method="post" onsubmit = "return blank()">
	<input type="hidden" value="add" name="choice">
	<p>Card Name: <input type="text" id = "name" name ="name" maxlength = "30"></p>
	<p>Card Number: <input type="text" id = "number" name ="number"></p>
	<p>Expiration Date: <input type="text" id = "expr" name="expr" maxlength = "30"></p>
	<p>CVV: <input type="text" id ="cvv" name="cvv" maxlength = "30"></p>
	<button type="submit" name = "submit">Add Card</button>
	</form>

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

<?php

$mysql = "select aid from User_Accounts where username = '$username'";
$result = $conn->query($mysql);
$row = mysqli_fetch_array($result);
$id = $row['aid'];
$_SESSION['aid'] = $id;

?>





