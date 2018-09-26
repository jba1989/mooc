

<?php

##################################
##		台大開放式課程爬蟲		##
##################################

header("content-type: text/html; charset=utf-8");

// ----統計各課程按讚數----
function sumLikeCount() 
{   
    global $db_link;
    $command_1 = "SELECT `classID` FROM `NTU_class`";
    $result_1 = $db_link -> query($command_1);
    $row_result_1 = $result_1 -> fetch_assoc();
    $num_result_1 = $result_1 -> num_rows;
    
    // ----統計user按讚的數量並記錄於NTU_class中----
    while ($row_result_1 = $result_1 -> fetch_assoc())
    {   
        $classID = $row_result_1['classID'];
        
        $command_2 = "SELECT `$classID` FROM `users` WHERE `$classID` = 1";
        $result_2 = $db_link -> query($command_2);
        $num_result_2 = $result_2 -> num_rows;
        
        $command_3 = "UPDATE `NTU_class` SET `likeCount` = {$num_result_2} WHERE `classID` LIKE '$classID' ";
        $result_3 = $db_link -> query($command_3);
    };
}

// ---- 爬回所有課程網址 ----
function allClassLink() {
$url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/";
$sSource = parse_MyURL($url);
// echo $sSource;
$sBeginWith = "<option>選擇課程名稱 Course Title</option>";
$sEndWith = "</select>";
$parseString = StrFind($sSource, $sBeginWith, $sEndWith, $iTh = 1 , FALSE);
// echo $parseSelect ;
$sBeginWith = '<option value="';
$sEndWith = '">';
$result = parse_MyURL_Cycle ($parseString,$sBeginWith,$sEndWith);
return $result;
}


// ---- 爬回所有課程清單 ----
function allClassName() 
{
	$url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/";
	$sSource = parse_MyURL($url);
	$parseString = StrFind($sSource, "<option>選擇課程名稱 Course Title</option>", "</select>", $iTh = 1 , FALSE);
	$result  = parse_MyURL_Cycle ($parseString,'">','<');
	return $result;
}

// ---- 爬回所有課程ID ----
function allClassID() 
{
	$url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/";
	$sSource = parse_MyURL($url);
	$parseString = StrFind($sSource, "<option>選擇課程名稱 Course Title</option>", "</select>", $iTh = 1 , FALSE);
	$result  = parse_MyURL_Cycle ($parseString,'/cou/','">');
	return $result;
}


// ---- 抓取單一課程標題 ----
function parseClassTitle ($classID)
{	
	$url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/index.php/ocw/cou/$classID/1";
	//echo $url;
	$sSource = parse_MyURL($url);
	$sBeginWith = 'align="texttop" />';
	$sEndWith = '</div>';
	$count = countClass ($classID);
	for ($i = 0; $i < $count; $i++)
	{
		$result[] = trim(StrFind($sSource, $sBeginWith, $sEndWith, ($i+1) , FALSE));
	};
	return $result;
}


// ---- 抓取單一課程大綱目標 ----
function parseClassDescription ($classID)
{	
	$url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/ocw/cou_intro/$classID";
	//echo $url;
	$sSource = parse_MyURL($url);
	$sBeginWith = 'og:description" content="';
	$sEndWith = '" />';
	$result = trim(StrFind($sSource, $sBeginWith, $sEndWith, 1, FALSE));
	return $result;
}

// ---- 抓取單一課程老師 ----
function parseClassTeacher ($classID)
{	
	$url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/index.php/ocw/cou/$classID";
	$sSource = parse_MyURL($url);
	$result = trim(StrFind($sSource, '<meta name="description" content="', '" />', 1 , FALSE));
	return $result;
}

// ---- 抓取單一課程名稱 ----
function parseSingleClassName ($classID)
{	
	$url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/index.php/ocw/cou/$classID";
	$sSource = parse_MyURL($url);
	$result = trim(StrFind($sSource, '<title>', '- 臺大開放式課程', 1 , FALSE));
	return $result;
}


// ---- 抓取單一課程上課次數 ----
function countClass ($classID)
{
	$url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/index.php/ocw/cou/";
	$urlID = "$url"."$classID";
	$sSource = parse_MyURL($urlID);
	$sBeginWith = 'align="texttop" />';
	$count = substr_count($sSource, $sBeginWith);
	return $count;
	
}

// ---- 抓取課程影片連結 ----
function parseVideo ($classID)
{	
	$count = countClass ($classID);
	// echo $count;		//測試用
	$url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/index.php/ocw/cou/$classID/";
	for ($i = 1; $i < ($count+1); $i++)
	{
		$urlID = "$url"."$i";
		$sSource = parse_MyURL($urlID);
		$parse_1 = StrFind($sSource, '<div class="video">', '</iframe>', 1, FALSE);
		$result[] = StrFind($parse_1, "src='", "'>", 1, FALSE);
	};
	
	return $result;
}

// ----循序爬蟲 ----
function parse_MyURL_Cycle ($sSource,$sBeginWith,$sEndWith)
{	

	$count = substr_count($sSource, $sBeginWith);
	for ($i = 0; $i < $count; $i++)
	{
		$result[] = trim(StrFind($sSource, $sBeginWith, $sEndWith, ($i+1), FALSE));
	};
	return $result;
}


// ---- 頗析字串 ----
function StrFind($sSource, $sBeginWith, $sEndWith, $iTh = 1 , $bIncludeBeginEnd = TRUE) { 
	//(資料來源,開始字串,結束字串,第X次出現的字串,是否包含開始結數字元)
	$result = "";   //先將結果設為空字串
	$iStartPosition = - 1;   //配合後續$iStartPosition+1可往下查下一次出現的位置
	
	// <---搜尋指定之$sBeginWith位置--->
	for($i = 1; $i <= $iTh; $i ++) {
		$iStartPosition = strpos ( $sSource, $sBeginWith, $iStartPosition + 1 );
	}
	$istartPoint = $iStartPosition;  //做開始位置的備存
	
	
	// <---搜尋指定之$sBeginWith位置--->
	if ($iStartPosition < 0)
		return $result;    //若無找到$sSource中有$sBeginWith回傳空字串
	
	// <---搜尋指定之$iEndPosition位置--->
	$iEndPosition = strpos ( $sSource, $sEndWith, $iStartPosition + strlen($sBeginWith) );
	if ($iEndPosition < 0)
		return $result;
		
	// <---判斷是否有多重table--->
		$icount = -1;    //計算幾次的計數器,因會先計算一次故從-1開始
		do	
		{
			$istartPoint = strpos ( $sSource, $sBeginWith, $istartPoint + 1 );
			$icount ++;
		} while ( ($istartPoint < $iEndPosition) && ($istartPoint > 0) );
		//echo "icount=".$icount."<br />" ;    除錯用
		
	// <---重新尋找正確的$iEndPosition--->
		for ($j = 0 ; $j < $icount ; $j++)
		{
			$iEndPosition =  strpos ( $sSource, $sEndWith, $iEndPosition + 1 );
		}
	
	// <---重組字串並判斷是否連接"$sBeginWith"/"$sEndWith"字串--->
	if ($bIncludeBeginEnd) {
		$result = $sBeginWith . substr ($sSource, $iStartPosition + strlen ( $sBeginWith ), $iEndPosition - $iStartPosition - strlen ( $sBeginWith ) ) . $sEndWith;
	} 
	else
		$result = substr ( $sSource, $iStartPosition + strlen ( $sBeginWith ), $iEndPosition - $iStartPosition - strlen ( $sBeginWith ) );
	return $result;
}

// ----爬回網頁----
function parse_MyURL($url)
{
	// 1. 初始設定
	$ch = curl_init();
	
	// 2. 設定 / 調整參數
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	
	// 3. 執行，取回 response 結果
	$output = curl_exec($ch);
	$info = curl_getinfo($ch);
	
	//echo htmlspecialchars($output);
	//return htmlspecialchars($output);		//轉換tag符號, 轉給StrFind會不好解析
	return $output;
	
	// 4. 關閉與釋放資源
	curl_close($ch);
}


?>

