<!-- Header -->
			<header id="header">
				<nav class="left">
					<a href="#menu"><span>Menu</span></a>
				</nav>
				<a href="index.php" class="logo">Welcome! <?php echo $_SESSION["userID"]; ?></a>
				<nav class="right">
					<a href="<?php echo ( empty($_SESSION["userID"])?"login.php":"login.php?logout=1" ); ?>" class="button alt">
						<?php echo ( empty($_SESSION["userID"])?"login":"log out" ); ?>
					</a>
				
				</nav>
			</header>

<!-- Menu -->
			<nav id="menu">
				<ul class="links">
					<li><a href="index.php">首頁</a></li>
					<li><a href="member.php">會員資料</a></li>
					<li><a href="NTU_class.php">台大課程總覽</a></li>
					<li style="text-decoration: line-through; color:gray;">交大課程總覽</li>
					<li style="text-decoration: line-through; margin-top:1em; color:gray;">清大課程總覽</li>

				</ul>
				<ul class="actions vertical">
					<li><a href="#" class="button fit"></a></li>
				</ul>
			</nav>
			