<?php

	//連線資料庫
    @include_once("connect.php");
    session_start();
	$userEmail = $_POST["userEmail"];
    $search_Email = "SELECT * FROM `users` WHERE `userEmail` = '$userEmail'";  //欲比對ID是否重複
    $result_Email = $db_link -> query($search_Email);
    if ($result_Email-> num_rows >0 )
    {
        $data = 'images/notok1.png';
        // $data = "此email已被使用";
    }
    else $data = 'images/ok1.png';
    	// $data = "此email可以使用";
        
    echo $data;
?>