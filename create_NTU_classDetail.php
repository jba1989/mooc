<?php
header("Content-type: text/html; charset=utf-8");
@include("connect.php");
ini_set('memory_limit', '512M');
@include("spider.php");

//連線資料庫
$command = "SELECT * FROM `NTU_class` WHERE `classType` LIKE '法社管理'";
$allClassID = $db_link -> query($command); 


// //循序寫入個課程資料
foreach ($allClassID as $class)
{	
	$classID = $class['classID'];
	$search_ID = "SELECT * FROM `allclasses` WHERE `classID` LIKE '{$classID}'";  //欲比對ID是否重複
    $result_ID = $db_link -> query($search_ID);
    // print_r($result_ID);
	if (($result_ID -> num_rows) == 0 )	//若無此classID則寫入資料
    {	
		$classTitle   = parseClassTitle ($classID);
		$classVideo   = parseVideo ($classID);
		$count        = countClass ($classID);
		// $array[] = array($classID,$className,$classTeacher,$classTitle,$classVideo,);
		// var_dump($array);
		
		for ($i = 0; $i < $count ; $i++)
		{
			$inser_array = "INSERT `allclasses` (classID,classTitle,classVideo)
							VALUES (?,?,?)";
			$stmt = $db_link -> prepare($inser_array);	
			$stmt -> bind_param("sss", $classID, $classTitle[$i],$classVideo[$i]);
			$stmt -> execute();
			$stmt -> close();
		};
		
    };
    
    
};


$db_link -> close();
echo "執行完畢";

?>