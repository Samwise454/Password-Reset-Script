<?php
require_once 'header.php';

//user not supposed to access this page with active session
if (isset($_SESSION["user_id"])) {
	session_start();
	session_unset();
	session_destroy();
	header("Location: home.php");
	exit();
}

//if selector and validator not set, send user to index
if (!isset($_GET["selector"]) && !isset($_GET["validator"])) {
	header("Location: index.php?val=unaccess");//unauthorized access
}
else {
	//we are escaping with htmlspecialchars because we will query the database with the selector and validator
	$selector = htmlspecialchars($_GET["selector"]);
	$validator = htmlspecialchars($_GET["validator"]);
}
echo '<body>
				<header>
					<div>
						<p title="Just Aesthetics" class="btn_link">
							Home
						</p>
					</div>
				</header>
				
				<main>
					<div class="main1">
						<div class="main2">
							This is where we input our  <br>
							new password.
						</div>
					</div>
					
					<div class="main3">
						<div class="main4" id="recover_pass">
							<p>Input New Password:</p>
							<div class="note_tab">
								<p class="e_note" id="e_note5"></p>
							</div>
							Password: <input type="password" class="uid" id="new_pass1"><br>
							Re-Password: <input type="password" class="uid" id="new_pass2"><br>
							<button class="l_btn" id="new_pass_btn" title="Click to Login">Submit</button>
							<button class="l_btn" id="go_back">Back</button>
							
							<!--We are passing the selector and validator via hidden inputs so we can query the db with them.-->
							
							<input type="hidden" id="selector" value='.$selector.'>
							<input type="hidden" id="validator" value='.$validator.'>
						</div>
					</div>
				</main>';
require_once 'footer.html';
	
?>