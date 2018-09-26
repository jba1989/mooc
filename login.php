<?php
header("Content-type: text/html; charset=utf-8");

@include_once("connect.php"); //匯入連線檔

// ----SESSION驗證----
session_start();


// ----登出----
if ($_GET["logout"]==1) 
{
    session_unset();
    header("Location:index.php");
};


// ----驗證userID----
if (isset($_POST["submit"]))
{
    if (!empty("userName") && !empty("userPassword"))
    {
        //讀取資料庫內相同ID的欄位
        $command = "SELECT * FROM `users` WHERE `userID` = '{$_POST["userID"]}'";  
        //執行sql指令
        $result_ID = $db_link -> query($command);
        if ($result_ID -> num_rows > 0 )	//比對帳號,有資料則繼續比對密碼
        {   
            $row_result = $result_ID -> fetch_assoc();
            if (password_verify ( "{$_POST['userPassword']}" , $row_result["userPassword"]))	//密碼已經過加密
            {
                $_SESSION['userID'] = $row_result['userID'];
                $_SESSION['userName'] = $row_result['userName'];
                $_SESSION['userEmail'] = $row_result['userEmail'];
                $_SESSION['manager'] = $row_result['manager'];
                
                if (!empty($_SESSION["classID"]))		//若是經由單一課程轉跳過來,則轉跳回該課程頁面
                {	
                	$classID = $_SESSION["classID"];
                	header("Location:singleClass.php?classID=$classID");
                }
                else { header("Location:index.php");};
                exit ();
            }
            else {echo "<script>alert('密碼錯誤!');</script>";}
        }
        else {echo "<script>alert('帳號錯誤!');</script>";}
    };
};


?>


<!DOCTYPE html>
<html>
    <head>
        <meta chatset="UTF-8">
        <title>開放式課程討論區-會員登入</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<style> 
		    
		    
		    .main {
		        width:500px;
		        height:400px;
		        margin:10% auto;
		     }
		    
		    .minor {
		        margin:0 auto;
		        
                font-family: "Montserrat", sans-serif;
		    }
		    
		    table {
		        width:99%;
		        vertical-align:center;
		        font-family: "Open Sans", Arial, Helvetica, sans-serif;
		    }
		    
		    tr {
		        margin:2em;
		    }
		    
		  .left {
		        text-align:center;
		        width:35%;
		        font-size:20px;
	            color: #25a2c3;
		    }
		    
		    .right {
		        width:65%;
		        padding-left:0;
		        
		    }
		    
		    input[type="password"],
		    input[type="text"]
		    {
	            background: rgba(144, 144, 144, 0.075);
	            height: 2.5em;
	            width:80%;
	            font-size:15px;
	            padding-left:0.8em;
	            text-decoration: none;
	            font-family: "Open Sans", Arial, Helvetica, sans-serif;
		        border-radius: 10px;
		        
		    }
		    
		    input[type="submit"],
		    input[type="button"]
		    {   
		        width:25%;
		        height:3em;
		        text-decoration: none;
		        border-radius: 5px;
		        border:none;
		        margin-top:7%;
		        margin-bottom:7%;
		        color:white;
		        font-weight:bold;
		    }
		    
		    
		    input[type="submit"]{
		        background-color: #25a2c3;
		        margin-left:12%;
		    }
		    input[type="submit"]:hover{
		        background-color: #2bb3d7;
		    }
		    
		    input[type="reset"]{
		        background-color: #f6755e;
		    }
		    input[type="reset"]:hover {
		        background-color: #f78a76;
		    }
		    
		    input[type="button"]:hover{
		        background-color: gray;
		    }
		
			input[type="button"] {
		        background-color: rgb(100,100,100);
		    }	
	
		    caption {
		        font-size:4em;
		        color: #25a2c3;
		        padding: 0 0 20px 0;
		    }
		</style>
    </head>
    <body>
    
        <div class=main>
        <form id="login" method="post" action="">
            <table>
                <caption>會員登入</caption>
                <th span="2"></th>
                <tr>
                    <td class=left><pre>帳   號:</pre></td>
                    <td class=right>
                        <input type="text" pattern= "[a-zA-z0-9_]*" required minlength="3" maxlength="20" name="userID" >
		            </td>
                </tr>
                <tr>
                    <td class=left><pre>密   碼:</pre></td>
                    <td class=right>
                        <input type="password" pattern= "[a-zA-z0-9]*" required minlength="5" maxlength="20" name="userPassword">
                    </td>
                </tr>
            </table>   
            
            <div>   
                <td><input type="submit" name="submit"/></td>
                <td><input type="button" name="resign" value="註冊" onclick="javascript:location.href='resign.php'"/></td>
                <td><input type="button" name="homepage" value="返回首頁" onclick="javascript:location.href='index.php'"/></td>
            </div> 
        </form>
        </div>
    </body>
</html>