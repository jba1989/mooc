<?php
include_once("connect.php");
session_start();

// ----留言版功能----

    $text = filter_var(trim($_POST['message']), FILTER_SANITIZE_MAGIC_QUOTES);	//留言過濾quote
    $text = htmlspecialchars($_POST['message']);
    $query_insert = "INSERT `board` (`classID`,`userID`,`text`)
                     VALUES (?,?,?)";                   
    $stmt = $db_link -> prepare($query_insert); 
    $stmt -> bind_param("sss", $classID, $_SESSION['userID'], $text);       
    $stmt -> execute();
    $stmt -> close();

    
	 //while ($row = $each_page_board -> fetch_assoc())
  //  {
  //      echo '<div style="margin: 2em 0 0 0;"><code><b style="font-size:1.5em; padding-right:0.5em">'.
  //            $row["userID"].":<br>".$row["text"]."</code></div>";
  //  };
    
    


//換頁判斷
// if (isset($_GET['page'])){$now_page = $_GET['page'];}
// else {$now_page = 1; };	//預設顯示頁面

// $num_each_page = 5;    //每頁顯示筆數

// $classID = "101S101";
// //當頁開始記錄筆數
// $start_num = ($now_page-1) * $num_each_page;
// $commend_1 = "SELECT * FROM `board` WHERE `classID` LIKE '101S101' ORDER BY `cID` DESC";       //讀取留言,by cID排序
// // echo $commend_1;
// $total_text = $db_link -> query($commend_1);                            //所有留言
// // print_r($total_text);
// $total_num = $total_text ->num_rows;                                    //總筆數
// $total_page = ceil($total_num/$num_each_page);                          //總頁數
// $commend_2 = "SELECT * FROM `board` WHERE `classID` LIKE '101S101' ORDER BY `cID` DESC LIMIT {$start_num}, {$num_each_page}";    //設定每頁讀取筆數
// $each_page_board = $db_link -> query($commend_2); 
// // while ($row = $each_page_board -> fetch_assoc()) {var_dump($row);};
// // $row = $each_page_board -> fetch_assoc();
// // echo $row["cID"];
// // print_r($each_page_board);

?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<style>
			ul, li {
				display:inline;
			}
		</style>
    </head>
    <body>
        <section id="main" class="wrapper">
					<div class="inner">
									<form method="post" action="#">
										<div class="row uniform">
											<div class="12u$">
											<h3>留言板:</h3>
    											<?php while ($row = $each_page_board -> fetch_assoc()) { ?>      
    											<div style="margin: 0 0 2em 0;"><code><b style="font-size:1.5em; padding-right:0.5em">
    											    <?php echo $row["userID"].":"; ?></b><?php echo $row["text"]; ?>
                                        		</code></div>
                                    	    	<?php }; ?>
                                    	    	<div style="float:right;">
                                    	    	<ul style="list-style-type:none;">
											    <?php for($i = 1; $i < $total_page+1 ; $i++){ ?>
											    <li><a href="?page=<?php echo $i; ?>"/><?php echo $i; ?></li>
											    <?php }; ?>
											    </div>
											</ul>
											</div>
											
											
									        
									        <!--from-->
									        <form method="post" action="" style="width:100%;">   
											<div class="12u$">
												<textarea name="message" id="message" maxlength="300" placeholder="最大長度300字" rows="6"></textarea>
											</div>
											<div class="12u$">
												<ul class="actions">
													<li><input type="submit" value="Send Message" /></li>
													<li><input type="reset" value="Reset" class="alt" /></li>
												</ul>
											</div>
											</form> 
										</div>
									</form>
					</div>
				</section>
    </body>
</html>

                