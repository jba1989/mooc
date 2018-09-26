<?php 
header("Content-type: text/html; charset=utf-8");
@include_once("connect.php");
session_start();


if ($_FILES["fileUpload"]["error"]==0)
{   
    // $filename = fopen("$FILES['fileUpload']['name']","rb");
    // echo "$filename";
    
    if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"],"userImages/".$_FILES["fileUpload"]["name"]))
    {
        $userImage = $_FILES["fileUpload"]["name"];
        $userID = $_SESSION['userID'];
        $add_image = "UPDATE `users` SET `userImage` = '{$userImage}' WHERE `userID` LIKE '$userID'";
		$db_link -> query($add_image);
        header ("Location:member.php");
        exit();
    };
    
};

?>