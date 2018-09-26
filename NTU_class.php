<?php
header("Content-type: text/html; charset=utf-8");

// ----連線資料庫----
@include_once("connect.php");
@include_once("spider.php");

session_start();
// sumLikeCount();		//統計各課程按讚數

// ----讀取課程資料----
if (isset($_GET["classtype"]))
{
	switch ($_GET["classtype"])
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
}
else {$command = "SELECT * FROM `NTU_class` ORDER BY `classID`";};
$result = $db_link -> query($command);


?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>開放式課程討論區-台大課程總覽</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="subpage">

		<!-- Header/Menu -->
			<?php include("header.php"); ?>
			
		<!-- Three -->
				<section id="three" class="wrapper">
					<div class="inner bg-orange flex flex-3">
						<div class="flex-item box">
							<div class="image fit">
								<a href="NTU_class.php?classtype=熱門點閱"><img src="images/type1.png" alt="" /></a>
							</div>
							<div class="content">
								<h3><a href="NTU_class.php?classtype=熱門點閱">熱門點閱</a></h3>
								<p style="color:red;">最新功能!!!</p>
							</div>
						</div>
						<div class="flex-item box">
							<div class="image fit">
								<a href="NTU_class.php?classtype=理工電資"><img src="images/type3.png" alt="" /></a>
							</div>
							<div class="content">
								<h3><a href="NTU_class.php?classtype=理工電資">理工電資</a></h3>
								<p></p>
							</div>
						</div>
						<div class="flex-item box">
							<div class="image fit">
								<a href="NTU_class.php?classtype=法社管理"><img src="images/type2.png" alt="" /></a>
							</div>
							<div class="content">
								<h3><a href="NTU_class.php?classtype=法社管理">法社管理</a></h3>
								<p></p>
							</div>
						</div>
					</div>
				</section>
				
				
				<section id="main" class="wrapper">
				<!-- 課程表 -->
					<div class="inner">
						
							<h3>課程表</h3>
									<div class="table-wrapper">
										<table id="classList">
											<thead>
												<tr>
													<th style="text-align:center">課程ID:</th>
													<th>課程名稱:</th>
													<th style="text-align:center">讚數:</th>
													<th>開課教授:</th>
													<th>課程分類:</th>
  												</tr>
											</thead>
											<tbody>
												
												<?php while($row_result = $result -> fetch_assoc()) { ?>
													<tr>
													<td style="text-align:center"><?php echo $row_result['classID']; ?></td>
													<td><a href="singleClass.php?classID=<?php echo $row_result['classID']; ?>"/><?php echo $row_result['className']; ?></a></td>
													<td style="text-align:center;"><?php echo $row_result['likeCount']; ?></td>
													<td><?php echo $row_result['classTeacher']; ?></td>
													<td><?php echo $row_result['classType']; ?></td>
													</tr>
												<?php }; ?>
											</tbody>
										</table>
									</div>		
						
					</div>
				</section>
				
				<!-- Footer -->
			<!--<?php @include_once("header.php"); ?>-->

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>