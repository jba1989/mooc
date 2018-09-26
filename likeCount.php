<?php

	//連線資料庫
    @include_once("connect.php");
    session_start();
    
     if ($_POST["like"] == true) {		//接收ajax傳送的資料
	    $classID = $_POST["classID"];
	    $userID = $_SESSION["userID"];
	    
	    //----判斷是否有此課程欄位,若無則新增欄位----
	    $command_class = "SELECT `$classID` FROM `users`";
		$favor = $db_link -> query($command_class);
		if ($favor == false)
		{
			$add_column = "ALTER TABLE `users` ADD `$classID` BOOLEAN";
			$db_link -> query($add_column);
		};
		
		//----寫入userID like $classID的紀錄----
		$add_like = "UPDATE `users` SET `$classID` = 1 WHERE `userID` LIKE '$userID'";
		$db_link -> query($add_like);
		
		//----重新由資料庫統計有like的user數目----
		$count_like = "SELECT `$classID` FROM `users` WHERE `$classID` = 1 ";
		$like_count = $db_link -> query($count_like);
		$like_number = $like_count -> num_rows ;
		
		//----回傳數目----
		echo $like_number;
     };
?>