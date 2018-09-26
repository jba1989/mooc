<?php
header("Content-type: text/html; charset=utf-8");
@include_once("connect.php"); //匯入連線檔
session_start();

if (empty($_SESSION["userID"])) 
{
	header("Location:login.php");
}
else
{	
	// ----提取會員資料 ----
	$userID = $_SESSION["userID"];
	$search_ID = "SELECT * FROM `users` WHERE userID = '$userID'";  
    $result_ID = $db_link -> query($search_ID);
	$row_result = $result_ID -> fetch_assoc();
};

	// ----送出表單後進行判斷----
if (isset($_POST["submit"]))
{   
	$userID = $_SESSION["userID"];
    $level  = $_POST["level"];
    $major  = trim($_POST["major"]);
    
    // ----呼叫database----
    $query_insert = "UPDATE `users` SET `level`='$level',`major`='$major'
                     WHERE `userID` LIKE '$userID'";                   
	$db_link -> query($query_insert);
    header("Location:member.php");

};

?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        
        <script type="text/javascript">
            
        </script>
        <style tyle:text/css>
            .note {
                font-size:0.5em;
                color:gray;
                
            }

        
		    .main {
		        width:600px;
		        /*height:70%;*/
		        margin:10% auto;
		        border:solid gray;
		        border-radius: 25px;
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
		        width:25%;
		        font-size:20px;
	            color: #25a2c3;
	        }
		    
		    .center {
		        width:70%;
		        padding-left:0;
		        margin-right:5px;
		    }
		    
		    .right {
		        width:5%;
		        padding-left:0;
		    }
		    
		    td {
		    	vertical-align:middle;	
		    }
		        
		    p {
		    	padding-left:0.8em;
		    }
		    
		    input[type="password"],
		    input[type="text"],
		    input[type="email"],
		    select
		    {
	            background: rgba(144, 144, 144, 0.075);
	            height: 2.5em;
	            width:93%;
	            font-size:15px;
	            padding-left:0.8em;
	            text-decoration: none;
	            font-family: "Open Sans", Arial, Helvetica, sans-serif;
		        border-radius: 10px;
		    }
		    
		    
		    input[type="submit"],
		    input[type="reset"],
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
			input[type="file"]
			input[type="button"] {
		        background-color: rgb(100,100,100);
		    }	
		
		    caption {
		        font-size:4em;
		        color: #25a2c3;
		        padding: 20px 0;
		    }
		    
		    .mark_1,
		    .mark_2 
		    {
		        height:1em;
		        width:1em;
		        
		        /*float: right;*/
		        display:none;
		        /*visibility:hidden;*/
		        /*z-index:-1;*/
		    }
		    
		    
			ul, li {
				display:inline;
			}
		    
        </style>
    </head>
    <body>
        <div class=main>
			<form id="login" method="post" action="">
                <table>
                        <caption>修改會員資料</caption>
                        <tr>
                            <td class=left>帳　　號:</td>
                            <td class=center><p><?php echo $row_result['userID'] ?></p></td>
                            <td class=right><img class=mark_1 src="" /></td>
                            
                        </tr>
                        
                        <tr>
                            <td class=left>真實姓名:</td>
                            <td class=center><P><?php echo $row_result['userName'] ?></P></td>
                            <td class=mark></td>
                        </tr>
                        
                        <tr>
                            <td class=left>E-mail:</td>
                            <td class=center><p><?php echo $row_result['userEmail'] ?><p></td>
                            <td class=right><img class=mark_2 src="" /></td>
                        </tr>
                      
                        <tr>
                            <td class=left>科　　系:</td>
                            <td class=center><input type="text" name="major" value="<?php echo $row_result['major'] ?>" /></td>
                            <td class=right></td>
                        </tr>
                        <tr>
                            <td class=left>最高學歷:</td>
                            <td class=center>
                                <select name="level"/>
                                <option selected >請選擇</option>
                                <option >博士</option>
                                <option >碩士</option>
                                <option >大學</option>
                                <option >高中</option>
                                <option >國中</option>
                                <option >國小</option>
                                </select>
                            </td>
                            <td class=right></td>
                        </tr>
                </table>
                
                <input type="submit" name="submit"/>
                <input type="reset" name="reset"/>
                <input type="button" name="homepage" value="返回首頁" onclick="javascript:location.href='index.php'"/>
                
            </form>
        </div>
    </body>
</html>