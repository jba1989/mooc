<?php
header("Content-type: text/html; charset=utf-8");
ini_set('memory_limit', '256M');
include("connect.php");		//連接資料庫
include("spider.php");		//爬蟲function


// for ($j=1; $j<5; $j++) {

	$j = 2;
    $url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/home/show-category/$j";
    
    
    switch ($j)		//課程分類
    {
    case 1:
        $classType = "文史哲藝";
        break;
    case 2:
        $classType = "法社管理";
        break;
    case 3:
        $classType = "理工電資";
        break;
    case 4:
        $classType = "生農醫衛";
        break;
    case 5:
        $classType = "百家學堂";
        break;    
    };
    
	echo "目前執行:$classType<br>" ; 
	    
	$sSource = parse_MyURL($url);
	$count = 6 ; //substr_count($sSource, '<div class="coursetitle">');    //爬$count個課程回來, 後面是全部爬回來
	for ($i = 0; $i < $count; $i++)
	{
		$classID[$i] = StrFind(	$sSource, 'px/', '.jpg', $i+1, false);
		$searchID = "SELECT `classID` FROM `NTU_calss` WHERE `classID` LIKE '{$classID[$i]}'";
		$result_ID = $db_link -> query($searchID);
	    
	    // 若課程重複則不寫入資料庫
	    if ($result_ID -> num_rows == 0 )
	    {   
	        $className = parseSingleClassName ($classID[$i]);
	        $classTeacher = parseClassTeacher ($classID[$i]);
	        // echo "$classID[$i]<br>$className<br>$classTeacher<br>";
	        
	        
	    	$command = "INSERT `NTU_class` (`classID`,`className`,`classTeacher`,`school`,`classType`)
	    	            VALUES (?, ?, ?, 'NTU', ?)";
	    	$stmt = $db_link -> prepare($command); 
	        $stmt -> bind_param("ssss", $classID[$i], $className, $classTeacher, $classType);       
	        $stmt -> execute();
	        $stmt -> close();
	    };
	};

// };
echo "執行完畢<hr>";



?>