<?php

session_start();
require_once("../../db_constant.php");

if (isset($_SESSION['loggedin']) and $_SESSION['loggedin'] == true) {
     $log = $_SESSION['username'];
} else {
    echo "Please log in first to see this page.";
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
	}
	
$sender = $_SESSION['username'];
$reciever = $_GET['id'];

$mysql = "select * from Msg_Notifications where recipient = '$sender'";
$result = $conn->query($mysql);

$sql = "select distinct sender from Messages where reciever = '$sender'";
$content = $conn->query($sql);

?>

<html>

<head>

<title>Message Page</title>

<script type="text/javascript">
    function blank()
    {
    var a=document.forms["messager"]["reciever"].value;
    var b=document.forms["messager"]["message"].value;
    if (a==null || a=="",b==null || b=="")
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

.leftsidebar {
position: absolute;
height: 800px;
left: 0%;
width: 15%;
background-color: #808080;
}

.leftsidebar .menu ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: 200px;
    background-color: #f1f1f1;
}
.leftsidebar .menu li a {
   display: block;
   color: #000;
   padding: 8px 16px;
   text-decoration: none;
   width: 200px;
   text-align: left;
}
.leftsidebar .menu li a:hover {
    background-color: #555;
    color: white;
}
.leftsidebar .menu li a:hover {
    background-color: #555;
    color: white;
}
.leftsidebar .menu .active {
    background-color: #4CAF50;
    color: white;
}
.num {
	color: red;
}

.center {
position: absolute;
height: 800px;
left: 15%;
width: 70%;
}


.center .notif {
position: absolute;
top: 100px;
left: 100px;
height: 300px;
width: 700px;
background-color: #FFFFFF;
border-style: solid;
border-width: 2px;
padding: 15px;
position: absolute;
overflow: scroll;
}

.center .notif table, th, td {
	margin-left: auto;
	margin-right: auto;
	border-bottom: 1px solid #ddd;
	padding: 15px;
    text-align: left;
}

.center .notif th {
    background-color: 	#00008B;
    color: white;
}

.center .notif tr:nth-child(even) {
	background-color: #f2f2f2;
}

.center .notif tr:hover {
	background-color: #f5f5f5;
}

.center .mess {
position: absolute;
top: 500px;
left: 100px;
height: 200px;
width: 700px;
background-color: #FFFFFF;
border-style: solid;
border-width: 2px;
padding: 15px;
position: absolute;
}


.rightsidebar {
position: absolute;
height: 800px;
left: 85%;
width: 15%;
background-color: #808080;
}

.rightsidebar .contacts {
	
}

.footer {
margin: auto;
width: 100%;
background-color: #000000;
color: #FFFAF0;
position: absolute;
top: 950px;
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

<div class = "search">
<img src = "../../images/logo.png" height = "100px" width = "200px" /><br />
<input type="text" name="search" placeholder="Search..">
</div>

<div class = "header">
<h1>Messages</h1>
</div>

<div class = "navbar">

<ul>
<li><a href = "../../listings/fm_listings.php">Listings</a></li>
<li><a href="../../account/fm_account.php" class = "active">My Account</a></li>
<li><a href = "../../transactions/fm_transactions.php">Transactions</a></li>
<li><a href = "../../fm_homepage.html">Logged In: <?php echo $log; ?></a></li>
</ul>

</div>


</div>

<!-- Block 2 -->
<div class = "leftsidebar">

<div class = "menu">
<ul>
<li><a href = "../edit_account/fm_edit_account.php">Edit Account</a></li>
<li><a href = "../edit_card/fm_edit_card.php">Edit Card Info</a></li>
<li><a href = "../messager/fm_messager1.php">Messager</a></li>
<li><a href = "../notifications/fm_notifications.php">Notifications <div class = "num"><?php if ($number != 0) { echo $number;}?></div></a></li>
<li><a href = 'fm_admin_vendor_requests.php'>Vendor Requests</a></li>
<?php if ($adminCheck['admin'] == "y"): ?>
	<span><li><a href = "fm_messager1.php">Messager</a></li></span>
<?php endif;?>
</ul>
</div>

</div>

<!-- Block 3 -->
<div class = "center">

<div class = "notif">
<table>
	<tr>
		<th>Message</th>
		<th>From</th>
		<th>Date/Time</th>
		<th>View</th>
		<th>Delete</th>
	</tr>
<?php while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><?php echo $row['message']; ?></td>
		<td><?php echo $row['sender']; ?></td>
		<td><?php echo $row['created']; ?></td>
		<td><a href = "fm_view_msg_notif.php?id=<?php echo $row['msgid']; ?>">View</a></td>
		<td><a href = "fm_delete_msg_notif.php?id=<?php echo $row['msgid']; ?>">Delete</a></td>
	</tr>
<?php }?>
</table>
</div>

<div class ="mess">
<form name = "messager" action = "fm_messager.php" method="get" onsubmit = "return blank()" >
<p>To: <input type="text" id = "reciever" name ="reciever" value = "<?php echo $reciever; ?>" maxlength = "15"></p>
<p>Message: </p>
<textarea rows = "4" cols = "50" id = "message" name = "message"></textarea>
<br />
<br />
<button type="submit" value="Login">Send Message</button>
</form>
</div>

</div>

<!-- Block 4 -->
<div class = "rightsidebar">

<div class = "contacts">
<table>
<?php while ($set = mysqli_fetch_array($content)) { ?>
	<tr>
		<td><a href = "fm_conversation.php?contact=<?php echo $set['sender']; ?>"><?php echo $set['sender']; ?></a></td>
	</tr>
<?php }?>
</table>
</div>

</div>

<!-- Block 5 -->
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