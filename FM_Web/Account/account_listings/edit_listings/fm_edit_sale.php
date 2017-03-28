<?php

session_start();
require_once("../../../db_constant.php");

if (isset($_SESSION['loggedin']) and $_SESSION['loggedin'] == true) {
    $log = $_SESSION['username'];
} else {
    echo "Please log in first to see this page.";
}

#Gathers Buy_Listing ID from the URL
$bid = $_GET['id'];

?>

<html>

<head>

<title>Edit Sale Page</title>

<script type="text/javascript">
    function blank()
    {
    var a=document.forms["myForm"]["item"].value;
    var b=document.forms["myForm"]["price"].value;
	var c=document.forms["myForm"]["descr"].value;
	var d=document.forms["myForm"]["picture"].value;
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

body {
padding: 0px;
margin: 0px;
}

ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

li {
    float: left;
	border-right: 1px solid #bbb;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover {
    background-color: 	#00008B;
}

.active {
    background-color: 	#00008B;
}

.title {
margin: auto;
width: 100%;
height: 150px;
background-color: #ff4d4d;
}


.title .header {
top: 10px;
left: 42%;
position: absolute;
font-family: "Brush Script MT", cursive;
font-size: 24px;
}

.title .search {
top: 15px;
left: 2%;
position: absolute;
}

.title .navbar{
top: 15px;
right: 2%;
position: absolute;
font-family: Arial, Helvetica, sans-serif;
}

.center {
position: absolute;
height: 500px;
width: 100%;
background-image: url("../../../images/tree.jpg");
}

.center .forms {
position: relative;
top: 90px;
left: 400px;
height: 200px;
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
top: 650px;
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

	<title>Edit Sale Page</title>
	
</head>

<body>

<!-- Block 1 -->
<div class = "title">

<div class = "search">
<img src = "../../../images/logo.png" height = "100px" width = "200px" /><br />
<input type="text" name="search" placeholder="Search..">
</div>

<div class = "header">
<h1>Edit Sale</h1>
</div>

<div class = "navbar">
<ul>
<li><a href = "../../../listings/fm_listings.php">Listings</a></li>
<li><a href="../../../account/fm_account.php" class = "active">My Account</a></li>
<li><a href = "../../../transactions/fm_transactions.php">Transactions</a></li>
<li><a href = "../../../fm_homepage.html">Logged In: <?php echo $log; ?></a></li>
</ul>
</div>


</div>

<!-- Block 2 -->
<div class = "center">

<div class = "forms">

<form name = "myForm" action="fm_update_sale.php?id=<?php echo $bid ?>;" method="post" enctype="multipart/form-data" onsubmit = "return blank()">
	<input type="hidden" value="add" name="choice">
	<p>Item Name: <input type="text" id = "item" name ="item" maxlength = "15"></p>
	<p>Price: <input type="text" id = "price" name ="price"></p>
	<p>Description: <input type="text" id = "descr" name="descr" maxlength = "30"></p>
	<input type="file" id = "picture" name="picture" accept="image/gif, image/jpeg, image/png">
	<br />
	<br />
	<button type="submit" name = "submit">Update Sale</button>
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