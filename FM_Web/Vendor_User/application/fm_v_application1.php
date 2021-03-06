<?php
require_once("../../db_constant.php");
$con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL:" .mysqli_connect_error();
	}
?>

<html>
<head>
<meta charset="utf-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

<script type="text/javascript">

<!-- Ensures forms are not blank -->
    function blank()
    {
    var a=document.forms["app"]["vendor"].value;
    var b=document.forms["app"]["fname"].value;
    var c=document.forms["app"]["lname"].value;
    var d=document.forms["app"]["email"].value;
		var e=document.forms["app"]["email"].value;
		var f=document.forms["app"]["street"].value;
		var g=document.forms["app"]["city"].value;
		var h=document.forms["app"]["state"].value;
			var i=document.forms["app"]["zip"].value;
			var j=document.forms["app"]["whyjoin"].value;
			var k=document.forms["app"]["futureproj"].value;
    if (a==null || a=="",b==null || b=="",c==null || c=="",d==null || d=="",e==null || e=="",f==null || f=="",g==null || g=="",h==null || h=="",i==null || i=="",j==null || j=="",k==null || k=="")
      {
      alert("Please complete all required fields");
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

<title>Application Form</title>
</head>
<style>
	body {
		margin: 0px;
	}
	
	.header {
		width: 100%;
		height: 20%;
		padding-left: 1px;
		background-color:#4B4B4B;
		position: absolute;
	}	

	
	.header .title {
		position: absolute;
		left: 40%;
		top: 0.5%;
		font-style: italic;
		font-family: "Brush Script MT";
		font-size: 30px;
	}
	
	
	.header .image {
		padding-top: 2%;
		padding-left: 0.5%;
	}
	
	
	.information {
		text-align: left;
		padding-top: 5px;
		padding-left: 1px;
		font-family: Constantia, "Lucida Bright", "DejaVu Serif", Georgia, "serif";
		width: 100%;
		top: 20%;
		background-color: #ffe6e6;
		position: absolute;
	}
	
	
	footer {
		margin: auto;
		top: 95%;
		bottom: 0%;
		width: 100%;
		background-color: #4B4B4B;
		color: #FFFAF0;
		position: absolute;
	}
	

	.footer ul {
		list-style-type: none;
		margin: 0px;
		padding: 0px;
		overflow: hidden;
		background-color: #333;
	}
	
	
	.footer li {
		float: right;
		border-right: 1px solid #bbb;
		border-left: 1px solid #bbb;
	}
	
	
	.footer li a {
		display: block;
		color: white;
		text-align: center;
		padding: 14px 16px;
		text-decoration: none;
	}
	
</style>

<body>
<!-- Header -->
<div class="header">
	<div class="image">
		<img src="../../images/LogoMakr-layerExport.png" height = "90px" width = "175px"/>
	</div>

	<div class ="title">
		<h1>Freely Market</h1>
	</div>
</div>
<!-- End Header -->

<!-- Form -->
<div class="information">
	<form name="app" action="fm_v_application.php" method="post" onsubmit="return blank()">
		<fieldset>
			<input type="hidden" value="add" name="choice">
			<legend> Vendor Application </legend>
				Vendor Name: <input type="text" id="vendor" name="vendor" maxlength="30"><br>
				<br>
				First: <input type="text" id="fname" name = "fname" maxlength="30"> Middle: <input type="text" name = "mname" placeholder="(optional)"> Last: <input type="text" id="lname" name = "lname" maxlength="30"><br>
				Email: <input type="text" id="email" name = "email"><br>
				<br>
				Are you the owner?:
					<input type="radio" name="owner" value="Yes">Yes
					<input type="radio" name="owner" value="No">No<br>
				<br>
				If not, may we have their name and email?<br>
				<br>First: <input type="text" id="ofirst" name="ofirst"> 
					Middle: <input type="text" id="omiddle" name ="omiddle"> 
					Last: <input type="text" id="olast" name="olast"><br>
				Email: <input type="text" id="oemail" name="oemail"><br>
				<br>
				<?php
				if ($owner == "1" and $ofirst='' and $omiddle='' and $oemail='') {
					?> <script type="text/javascript">
								function blank()
								{
								var a=document.forms["app"]["ofirst"].value;
								var b=document.forms["app"]["olast"].value;
								var c=document.forms["app"]["oemail"].value;
								if (a==null || a=="",b==null || b=="",c==null || c=="")
								  {
								  alert("Please complete all required fields");
								  return false;
								  }
								}

								function maxLength(char) {    
								if (!("maxLength" in char)) {
									var max = char.attributes.maxLength.value;
									char.onkeypress = function () {
										if (this.value.length >= max) return false;
									};
								}
							}

							maxLength(document.getElementById("text"));
							</script>
				<?php }; ?>
				Location: 
					<input type="text" id="street" name = "street" placeholder="Street" maxlength="30"> 
					<input type="text" id="city" name = "city" placeholder="City" maxlength="30"> 
					<input type="text" id="state" name = "state" placeholder="State" maxlength="30"> 
					<input type="text" id="zip" name = "zip" placeholder="Zipcode" maxlength="30"><br>
				<br>
				Years in Business: 
						<input type="radio" name="years" value="1">1
						<input type="radio" name="years" value="2">2
						<input type="radio" name="years" value="3">3
						<input type="radio" name="years" value="4">4+<br>
				<br>
				Why do you want to join Freely Market? <br><textarea cols="80" rows="5" id="whyjoin" name="whyjoin" maxlength="300"></textarea>
				<br>
				What is the future projection of the company? <br><textarea cols="80" rows="5" id="futureproj" name="futureproj" maxlength="300"></textarea>
				<br>
				<input type="submit" value="Submit">
			</fieldset>
		</form>
</div>
<!-- End Form -->

<!-- Footer -->
<div class = "footer">
	<ul>
		<li><a href = "">Privacy Policy</a></li>
		<li><a href = "">About</a></li>
		<li><a href = "">Contact</a></li>
		<li style = "float:left"><a href = "">Social Links</a></li>
	</ul>
</div>
<!-- End Footer -->
</body>
</html>