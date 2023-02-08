
			<body>
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
							This is just a page, <br>
							created for password reset script.
						</div>
					</div>
					
					<div class="main3">
						<div class="main4" id="sign_in">
							<p>Sign in:</p>
							
							<?php
								if (isset($_GET["id"])) {
									echo '<div class="note_tab">
											<p class="e_note" id="e_note1_1">Password reset successful, login with the new password.</p>
										</div>';
								}
								else {
									echo '<div class="note_tab">
											<p class="e_note" id="e_note1"></p>
										</div>';
								}
							?>
							
							Username: <input type="text" name="u_name" class="uid" id="uname" placeholder="e.g: Smith"><br>
							Password: <input type="password" name="p_word" class="uid" id="pword_signin"><br>
							<button class="l_btn" id="login_btn" title="Click to Login">Login</button>
							<button class="l_btn" id="f_pword" title="Forgotten Password?">Forgot Password</button>
							<button class="l_btn" id="switch_signup" title="Click to Sign-up">Sign-up</button>
						</div>

						<div class="main4" id="sign_up">
							<p>Sign up:</p><br>
							<div class="note_tab">
								<p class="e_note" id="e_note2"></p>
							</div>
							Username: <input type="text" name="u_name_signup" class="uid" id="uname_signup" placeholder="e.g: Smith"><br>
							Password: <input type="password" name="p_word_signup" class="uid" id="pword_signup"><br>
							Re-Password: <input type="password" name="re_pword" class="uid" id="re_pword"><br>
							E-mail: <input type="email" name="email" class="uid" id="email" placeholder="e.g: sample@mail.com"><br>
							Tel: <input type="tel" name="tel" class="uid" id="tel"><br>
							<button class="l_btn" id="signup_btn" title="Click to Sign-up">Sign-up</button>
							<button class="l_btn" id="switch_login" title="Click to Login">Login</button>
						</div>
						
						<div class="main4" id="forg_pword">
							<p>Let's recover your password:</p><br>
							<div class="note_tab">
								<p class="e_note" id="e_note3"></p>
							</div>
							Username: <input type="email" name="re_email" class="uid" id="re_email" placeholder="e.g: sample@mail.com"><br>
							<button class="l_btn" id="repword_btn">Recover</button>
							<button class="l_btn" id="back_login" title="Click to Login">Login</button>
							<button class="l_btn" id="back_signup" title="Click to Sign-up">Sign-up</button>
						</div>
					</div>
				</main>';