<?php

session_start();
require_once("../../../db_constant.php");
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}
$username = $_SESSION['username'];

#Get from url
$rid = $_GET['id'];

?>

<html>

<head>
	<title>Rental Request Form</title>
	
<script type="text/javascript">
    function blank()
    {
    var a=document.forms["myForm"]["username"].value;
    var b=document.forms["myForm"]["password"].value;
	var c=document.forms["myForm"]["password_confirm"].value;
    var d=document.forms["myForm"]["fname"].value;
    var e=document.forms["myForm"]["lname"].value;
	var f=document.forms["myForm"]["email"].value;
	var g=document.forms["myForm"]["phone"].value;
	var h=document.forms["myForm"]["picture"].value;
    if (a==null || a=="",b==null || b=="",c==null || c=="",d==null || d=="",e==null || e=="",f==null || f=="",g==null || g=="",h==null || h=="")
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

<script language='javascript' type='text/javascript'>
    function check(input) {
        if (input.value != document.getElementById('password').value) {
            input.setCustomValidity('Password Must be Matching.');
        } else {
            // input is valid -- reset the error message
            input.setCustomValidity('');
        }
    }
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
height: 475px;
background-color: #ffe6e6;
background-image: url("../../../images/bw_rack.jpg");
}

.center .forms {
position: relative;
top: 75px;
left: 450px;
height: 200px;
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
<h3>Rental Request Form</h3>
<form name = "myForm" action="fm_place_rentoffer.php?id=<?php echo $rid; ?>" method="post" onsubmit = "return blank()">
	<input type="hidden" value="add" name="choice">
	<p>Reason: <input type="text" id = "reason" name ="reason"></p>
	<p>Duration: <input type="text" id = "duration" name ="duration"></p>
	<p>Destination: <input type="text" name="destination" id="destination"></p>
	<br />
	<button type="submit" name = "submit">Send Request</button>
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