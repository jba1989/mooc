<?php
header("Content-type: text/html; charset=utf-8");

//連線資料庫
@include_once("connect.php");
@include_once("spider.php");
session_start();

// ----讀取classID,並由資料庫撈資料----
if ($_GET['classID']=="") 
	{
	header("Location:NTU_class.php");
	}
else
{	
	
	if (empty($_SESSION["userID"])) 
	{
		$_SESSION["classID"] = $_GET['classID'];
		header("Location:login.php");
	}
	else
	{	
		$classID = $_GET['classID'];
		$search_class = "SELECT * FROM `allclasses` WHERE `classID` LIKE '{$classID}'";
		$result_class = $db_link -> query($search_class); 
		// ----讀取課程資料----
		$command = "SELECT `className`,`classTeacher` FROM `NTU_class` WHERE `classID` LIKE '{$classID}'";
		$result = $db_link -> query($command);
		$row_result = $result -> fetch_assoc();
	};
};
	
	
// ----判斷按讚的圖片----
$userID = $_SESSION["userID"];
$likeImage_command = "SELECT `userID` FROM `users` WHERE `$classID` = 1 AND `userID` LIKE '$userID'";
$resultImage_command = $db_link -> query($likeImage_command);
$like_ByUserID = $resultImage_command -> num_rows;
if ($like_ByUserID == 0) {$image = "images/like2.png";}
else {$image = "images/like3.png";};
	
	
// ----計算按讚數----
$count_like = "SELECT `$classID` FROM `users` WHERE `$classID` = 1 ";
$like_count = $db_link -> query($count_like);
if ($like_count == false) {$like_number = 0;}
else {$like_number = $like_count -> num_rows;};
	
	
	
// ----留言版功能----
if (isset($_POST["submit"]) && !empty($_POST['message']))
{
    $text = filter_var(trim($_POST['message']), FILTER_SANITIZE_MAGIC_QUOTES);	//留言過濾quote
    $text = htmlspecialchars($_POST['message']);
    $query_insert = "INSERT `board` (`classID`,`userID`,`text`)
                     VALUES (?,?,?)";                   
    $stmt = $db_link -> prepare($query_insert); 
    $stmt -> bind_param("sss", $classID, $_SESSION['userID'], $text);       
    $stmt -> execute();
    $stmt -> close();
};

// ----留言板換頁判斷----
if (isset($_GET['page'])){$now_page = $_GET['page'];}
else {$now_page = 1; };	//預設顯示頁面


// ----讀取留言板資料----
$num_each_page = 5;    //每頁顯示筆數
$start_num = ($now_page-1) * $num_each_page;	//當頁開始記錄筆數
$command_1 = "SELECT * FROM `board` WHERE `classID` LIKE '{$classID}'
			  ORDER BY `cID` DESC";       //讀取留言,by cID排序

$total_text = $db_link -> query($command_1);                            //所有留言
// print_r($total_text);
$total_num = $total_text ->num_rows;                                    //總筆數
$total_page = ceil($total_num/$num_each_page);                          //總頁數
$command_2 = "SELECT * FROM `board` WHERE `classID` LIKE '{$classID}' 
			  ORDER BY `cID` DESC LIMIT {$start_num}, {$num_each_page}";
$each_page_board = $db_link -> query($command_2); 



?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>開放式課程討論區-<?php echo $row_result['className']; ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<script type="text/javascript" src="assets/js/jquery.js"></script>
		<style>
			#like,#dislike {
				width:3em;
			}
			
			ul, li {
				display:inline;
			}
			
			.toTop {
				width:5em; 
				position:fixed;
				bottom: 0; 
				right: 0;
			}
			
			.toBoard {
				width:5em; 
				position:fixed;
				bottom: 0; 
				left: 0;
			}
		</style>
		<script type="text/javascript">
			 $(document).ready(function () {
			 		
					$('#like').click(function()
	                	{	
	                        $.ajax(
	                        {
	                            type:'POST',
	                            url :'likeCount.php',
	                            data: {like:true,
	                            	   classID:'<?php echo $classID; ?>'},
	                            success:function(data)
	                                {   
	                                  $("#like").attr("src","images/like3.png");
	                                  $(".like").html(data);
	                                },
	                            error:function(){alert("error");}
	                        });
	                    });
			 });
		</script>
	</head>
	<body class="subpage">

		 <!--Header/Menu -->
			<?php @include_once("header.php"); ?>
		<!--Two -->	
			<section id="two" class="wrapper style1 special">
				<div class="inner">
					<h2><?php echo $row_result['className']; ?></h2>
					<cite class="author"><?php echo $row_result['classTeacher']; ?></cite>
					<figure>
						
					    <blockquote>
					    	<?php echo parseClassDescription ($classID) ?>
					    </blockquote>
					    <footer>
					    	<div>
					    			<img id=like src="<?php echo $image; ?>" style="cursor: pointer;"></a>
					    			<p class=like><?php echo $like_number; ?></p>
					    	</div>
					    </footer>
					</figure>
				</div>
			</section>
		 
				
				
				<section id="main" class="wrapper">
				 <!--課程表 -->
					<div class="inner">
						<div class="table-wrapper">
										<table id="classList" style="width:90%">
											<caption><h3>課程表</h3></caption>
											<tbody>
												
												<!--由資料庫撈取的data-->
												<?php foreach ($result_class as $class){ ?>
													<tr>
													<td style="width=100%; padding-left:10%; padding-right:10%; white-space:nowrap"><a href="<?php echo $class['classVideo']; ?>" target=blank ><?php echo $class['classTitle']; ?></a></td>
													</tr>
												<?php }; ?>
												
											</tbody>
										</table>
									</div>		
						
					</div>
				</section>
					<div class="inner" id="board">
						
							<div class="12u$">
								<h3 >留言板:</h3>
    							<?php while ($row = $each_page_board -> fetch_assoc()) { ?>      
    							<div style="margin: 2em 0 0 0;"><code><b style="font-size:1.5em; padding-right:0.5em">
    							    <?php echo $row["userID"].":"; ?></b><?php echo $row["text"]; ?>
                                	</code></div>
	                               	<?php }; ?>
	                               	
	                               	
	            <!--頁碼-->
	                               	<div>
		                                <div style="float:right; margin:1em;">
		                                <ul style="list-style-type:none;">
									    <?php for($i = 1; $i < $total_page+1 ; $i++){ ?>
									    <li><a href="singleClass.php?classID=<?php echo $classID.'&page='.$i; ?>" ><?php echo $i; ?></a></li>
									    <?php }; ?>
								    	</ul>
								    </div>	
								</div>
							</div>
				<!--留言板-->
						<form method="post" action="#">
							<div class="12u$">
								<textarea name="message" id="message" maxlength="300" placeholder="最大長度300字" rows="6" ></textarea>
							</div>
							
							<div class="12u$">
								
								<ul class="actions">
									<li><input type="submit" name="submit" value="Send Message" /></li>
									<li><input type="reset" name="reset" value="Reset" class="alt" /></li>
								</ul>
								
							</div>
						</form>
						
					</div>
				</section>
				
				 <!--Footer -->
				 <a href="#board"><img class="toBoard" src="images/toBoard.png"/></a>
				 <a href="#two"><img class="toTop" src="images/toTOP.png"/></a>
				<?php @include_once("footer.php"); ?>
				
		 <!--Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>