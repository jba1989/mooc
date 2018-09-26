<?php

//連線資料庫
@include_once("connect.php");
session_start();    //SESSION連線

// ----送出表單後進行判斷----
if (isset($_POST["submit"]))
{   
    $userID         = strip_tags(trim($_POST["userID"]));
    $userName       = strip_tags(trim($_POST["userName"]));
    $userPassword   = strip_tags(password_hash(trim($_POST["userPassword"]),PASSWORD_DEFAULT));
    $userEmail      = strip_tags(trim($_POST["userEmail"]));
    $level          = $_POST["level"];
    $major          = strip_tags(trim($_POST["major"]));
    
    // ----呼叫database----
    $search_ID = "SELECT `userID` FROM `users` WHERE userID = '$userID'";  //欲比對ID是否重複
    $result_ID = $db_link -> query($search_ID);
   
    $search_Email = "SELECT `userEmail` FROM `users` WHERE userEmail = '$userEmail'";  //欲比對Email是否重複
    $result_Email = $db_link -> query($search_Email);
    
    if ($result_ID-> num_rows >0 )
    {
        echo "<script>alert('此帳號已被註冊過')</script>";
    }
    else 
    {
        if ($result_Email -> num_rows > 0)
        {
            echo "<script>alert'此Email已被註冊過')</script>";
        }
        else
        {
        $query_insert = "INSERT users (`userID`,`userPassword`,`userName`,`userEmail`,`level`,`major`)
                         VALUES (?,?,?,?,?,?)";                   
        
        $stmt = $db_link -> prepare($query_insert); 
        $stmt -> bind_param("ssssss",$userID,$userPassword,$userName,$userEmail,$level,$major);       
        $stmt -> execute();
        $stmt -> close();
        $db_link -> close();
        $_SESSION['userID'] = $userID;
        $_SESSION['userName'] = $userName;
        $_SESSION['userEmail'] = $userEmail;
        if (!empty($_SESSION["classID"]))   //若是經由單一課程轉跳過來,則轉跳回該課程頁面
                {	
                	$classID = $_SESSION["classID"];
                	header("Location:singleClass.php?classID=$classID");
                }
                else { header("Location:index.php");};
                exit ();
        }
    }
}    
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>開放式課程討論區-註冊會員</title>
        <script type="text/javascript" src="assets/js//jquery.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#userID').blur(function()
                {
                    
                    if ($('#userID').val() !=="")
                    {   
                        $.ajax({
                            type:'POST',
                            url :'resignID_check.php',
                            data: {userID:$('#userID').val()},
                            success:function(data)
                                {   
                                    
                                    $('.mark_1').attr('src',data);
                                    $('.mark_1').show();
                                },
                            error:function(){alert("error");}
                        });
                    };
                });
                
                $('#userEmail').blur(function()
                {
                    if ($('#userEmail').val() !=="")
                    {   
                        $.ajax({
                            type:'POST',
                            url :'resignEmail_check.php',
                            data: {userEmail:$('#userEmail').val()},
                            success:function(data)
                                {   
                                    $('.mark_2').attr('src',data);
                                    $('.mark_2').show();
                                },
                            error:function(){alert("error");}
                        });
                    };
                });
            });
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
		    
        </style>
    </head>
    <body>
        <div class=main>
            <form id="login" method="post" action="">
                <table>
                        <caption>註冊會員</caption>
                        
                        <tr>
                            <td class=left>帳　　號:</td>
                            <td class=center><input type="text" ID="userID" pattern= "[a-zA-z0-9_]*" required minlength="3" maxlength="30" name="userID" placeholder="3~30個英文或數字"/></td>
                            <td class=right><img class=mark_1 src="" /></td>
                            
                        </tr>
                        
                        <tr>
                            <td class=left>密　　碼:</td>
                            <td class=center><input type="password" pattern= "[a-zA-z0-9]*" required minlength="5" maxlength="30" name="userPassword" placeholder="5~30個英文或數字"></td>
                            <td class=right></td>
                        </tr>
                        
                        <tr>
                            <td class=left>真實姓名:</td>
                            <td class=center><input type="text" required maxlength="30" name="userName"></td>
                            <td class=mark></td>
                        </tr>
                        
                        <tr>
                            <td class=left>E-mail:</td>
                            <td class=center><input type="email" required id=userEmail maxlength="100" name="userEmail"></td>
                            <td class=right><img class=mark_2 src="" /></td>
                        </tr>
                       
                        <tr>
                            <td class=left>科　　系:</td>
                            <td class=center><input type="text" name="major"/></td>
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