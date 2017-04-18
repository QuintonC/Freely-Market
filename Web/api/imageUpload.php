<?php

$name = 'asset';
$extension = '.jpg';
$imagePathToReturn = "";
$imagePath = $_POST['filename'];

if (move_uploaded_file($_FILES['file']['tmp_name'], "../images/" . $_FILES["file"]["name"])) {
    echo "File uploaded: ". $_FILES["file"]["name"];	
} else {
	echo "something went wrong";
	echo $_FILES["file"]["name"];
	//echo $imagePath;
}


// if(file_exists('../images/$imagePath')) {
// 	$basename = $name . $t . $extension;
// 	if (move_uploaded_file($_FILES['file']['tmp_name'], "../images/" . $basename)) {
// 		$imagePathToReturn = $basename; 
// 	} else {
// 		echo "something went wrong";
// 		die();
// 	}
// } else {
// 	if (move_uploaded_file($_FILES['file']['tmp_name'], "../images/asset.jpg")) {
// 		$imagePathToReturn = $_FILES['file']['name'];
// 	} else {
// 		echo "something went wrong";
// 		die();
// 	}
// }

// echo json_encode($imagePathToReturn);

?>