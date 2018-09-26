<?php
header("Content-type: text/html; charset=utf-8");
@include("connect.php");
session_start();

?>


<!DOCTYPE HTML>

<html>
	<head>
		<title>開放式課程討論區</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body>

		

		<!-- Header/Menu -->
			<?php include("header.php"); ?>

		<!-- Banner -->
			<section id="banner">
				<div class="content">
					<h1>開放式課程討論區</h1>
					<p><br />一起增加學習效率吧!</p>
					<ul class="actions">
						<li><a href="#three" class="button scrolly">Get Started</a></li>
					</ul>
				</div>
			</section>

		<!-- Three -->
			<section id="three" class="wrapper">
				<div class="inner flex flex-3">
					<div class="flex-item box">
						<div class="image fit">
							<a href="NTU_class.php"><img src="images/NTU logo.jpg" alt="" /></a>
						</div>
						<div class="content">
							<h3><a href="NTU_class.php">台大課程</a></h3>
							<p>全新網頁,火熱開放中</p>
						</div>
					</div>
					<div class="flex-item box">
						<div class="image fit">
							<img src="images/NTHU logo.png" alt="" />
						</div>
						<div class="content">
							<h3>清大課程</h3>
							<p style="color:red">尚未開放,敬請期待</p>
						</div>
					</div>
					<div class="flex-item box">
						<div class="image fit">
							<img src="images/NCTU logo.jpg" alt="" />
						</div>
						<div class="content">
							<h3>交大課程</h3>
							<p style="color:red">尚未開放,敬請期待</p>
						</div>
					</div>
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