<?php

	//連線資料庫
    @include_once("connect.php");
	session_start();
	$userID = $_POST["userID"]; 
    $search_ID = "SELECT * FROM `users` WHERE `userID` = '$userID' ";  //欲比對ID是否重複
    $result_ID = $db_link -> query($search_ID);
    if ($result_ID-> num_rows >0 )
    {
        $data = 'images/notok1.png';
        // $data = "此帳號已被使用";
    }
    else $data = 'images/ok1.png';
    	// $data = "此帳號可以使用";
        
    echo $data;
?>