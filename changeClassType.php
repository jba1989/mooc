<?php
header("Content-type: text/html; charset=utf-8");

// ----連線資料庫----
@include_once("connect.php");

session_start();
// sumLikeCount();		//統計各課程按讚數

// ----讀取課程資料----
$_POST["classType"] = "理工電資";
switch ($_POST["classType"])
	{
		case ("理工電資"):
			$command = "SELECT * FROM `NTU_class` WHERE `classType` LIKE '理工電資' ORDER BY `classID` DESC";
			break;
		case ("法社管理"):
			$command = "SELECT * FROM `NTU_class` WHERE `classType` LIKE '法社管理' ORDER BY `classID` DESC";
			break;
		case ("熱門點閱"):
			$command = "SELECT * FROM `NTU_class` ORDER BY `likeCount` DESC LIMIT 0,6";
			break;
	};

$result = $db_link -> query($command);

while($row_result = $result -> fetch_assoc()) 
	{ 
		echo ("
		
		<tbody id=classType>
		<tr>
		<div id='test1'>test</div>
		<td style='text-align:center'>".$row_result['classID']."</td>
		<td><a href='singleClass.php?classID=". $row_result['classID']."/>". $row_result['className']."</a></td>
		<td style='text-align:center;'>". $row_result['likeCount'] ."</td>
		<td>". $row_result['classTeacher'] ."</td>
		<td>". $row_result['classType'] ."</td>
		</tr>
		</tbody>");
	};

?>


