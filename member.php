<?php
@include_once("connect.php"); //匯入連線檔
session_start();

if (empty($_SESSION["userID"])) 
{
	header("Location:login.php");
}
else
{	
	$userID = $_SESSION["userID"];
	$search_ID = "SELECT * FROM `users` WHERE userID = '$userID'";  //欲比對ID是否重複
    $result_ID = $db_link -> query($search_ID);
	$row_result = $result_ID -> fetch_assoc();

};

?>




<!DOCTYPE HTML>
<html>
	<head>
		<title>Member</title></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<style>
			
			ul, li {
				display:inline;
				text-align:center;
			}
		</style>
	</head>
	<body class="subpage">

		<!-- Header/Menu -->
			<?php include("header.php"); ?>

		<!-- Main -->
			<section id="main" class="wrapper">
				
				<div class="inner">
					<header class="align-center">
						<h1>會員資料</h1>
					</header>
						
							<div style="position:relative; left:40%;">
								<img src="userImages/user_blue.png" alt="會員頭像" style="width:20%;"/>
							</div>
							<form action="imageRevise.php" method="post" enctype="multipart/form-data">
								<ul style="margin-left:28%">
									<li><input type="file" name="fileUpload" /></li>
									<li><input type="submit" style="background-color:none; line-height:0; width:5em; height:1.8em; margin:0; padding:0; text-align:center;" value="更換頭像" /></li>
								</ul>						
							</form>
						
						<table style="padding-bottom:1em;">
							<tr>
								<td style="text-align:center">會員ID :</td>
								<td><?php echo $row_result['userID']?></td>
							</tr>
							<tr>
								<td style="text-align:center">會員名稱:</td>
								<td><?php echo $row_result['userName']?></td>
							</tr>
							<tr>
								<td style="text-align:center">Email:</td>
								<td><?php echo $row_result['userEmail']?></td>
							</tr>
							<tr>
								<td style="text-align:center">主　修:</td>
								<td><?php echo $row_result['major']?></td>
							</tr>
							<tr>
								<td style="text-align:center">學　歷:</td>
								<td><?php echo $row_result['level']?></td>
							</tr>
						</table>
						
						<div ><a href="memberRevise.php" class="button icon fa-download" style="width:33%; margin:3em 33%;" />修改資料</a></div>
						
				</div>
			</section>

		<!-- Footer -->
			<?php @include_once("footer.php"); ?>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>