<?php
//this is like a sample home page
	session_start();
	if (!$_SESSION["user_id"]) {
		header("Location: index.php");
	}
	require_once 'header.html';
?>
			<body>
				<header>
					<div>
						<p title="Just Aesthetics" class="btn_link">
							Home
						</p>
					</div>
					
					<div>
						Hello  <?php echo $_SESSION["user_id"]; ?>,  Welcome To Account Recovery Home
					</div>
					
					<div>
						<p title="Log-out">
							<a href="scripts/logout.php" class="btn_link">Log-out</a>
						</p>
					</div>
				</header>
				
				<main class="home_bg">
					<div class="main1">
						<div class="main2">
							<b>This is the Home page for <br>
							password reset script.</b>
						</div>
					</div>
					
					<div class="main3">
						
					</div>
				</main>
				
<?php
	require_once 'footer.html';
?>
	