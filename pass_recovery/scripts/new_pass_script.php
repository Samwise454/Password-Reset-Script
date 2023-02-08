<?php
require_once 'login_db.php';

if (isset($_POST["pass1"]) && isset($_POST["pass2"]) && isset($_POST["select"]) && isset($_POST["validate"])) {
	$password1 = htmlspecialchars($_POST["pass1"]);
	$password2 = htmlspecialchars($_POST["pass2"]);
	$selector = htmlspecialchars($_POST["select"]);
	$validator = htmlspecialchars($_POST["validate"]);
	$val = "inval";
	
	if (empty($password1) || empty($password2) || empty($selector) || empty($validator)) {
		echo $val;
		exit();
	}
	else if ($password1 !== $password2) {
		echo $val;
		exit();
	}
	//let's check the if selector and validator are hex values with ctype_xdigit()
	else if (ctype_xdigit($selector) == false && ctype_xdigit($validator) == false) {
		//if false, we do not grant access
		echo $val;
		exit();
	}
	//you can add other checks for authentication like preg_match etc.
	else {
		//get current date value which will be used to calculate the expiration value in the db
		$current_date = date("U");
		
		//now we query the db
		$query = "SELECT * FROM reset_data WHERE reset_selector=?;";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $query)) {
			//handle this the way you wish
			echo $val;
			exit();
		}
		else {
			mysqli_stmt_bind_param($stmt, "s", $selector);
			mysqli_stmt_execute($stmt);
			
			$result = mysqli_stmt_get_result($stmt);
			$num_rows = mysqli_num_rows($result);
			if ($num_rows > 0) {
				$row = mysqli_fetch_assoc($result);
				//now we convert the validator to binary
				$validator_bin = hex2bin($validator);
				$validator_check = password_verify($validator_bin, $row["reset_token"]);
				$expire = $row["reset_expire"];
				$email = $row["reset_email"];
				
				//let's verify validator and also subtract 30 mins from current time and compare with expire.
				if ($validator_check === false && ($current_date - 1800) > $expire) {
					echo $val;
					exit();
				}
				else if ($validator_check === true && ($current_date - 1800) < $expire) {
					//we query the client_data table with the email and update passwords
					
					$query = "SELECT * FROM client_data WHERE email=?;";
					$stmt = mysqli_stmt_init($conn);
					if (!mysqli_stmt_prepare($stmt, $query)) {
						//handle this the way you wish
						echo $val;
						exit();
					}
					else {
						mysqli_stmt_bind_param($stmt, "s", $email);
						mysqli_stmt_execute($stmt);
						$result = mysqli_stmt_get_result($stmt);
						$num_rows = mysqli_num_rows($result);
						if ($num_rows > 0) {
							$row = mysqli_fetch_assoc($result);
							session_start();
							
							$_SESSION["user_id"] = $row["username"];
							$username = $_SESSION["user_id"];
							
							//let's update the client_data table
							$query = "UPDATE client_data SET password=?, confirm_password=? WHERE username=?;";
							$stmt = mysqli_stmt_init($conn);
							if (!mysqli_stmt_prepare($stmt, $query)) {
								//handle this the way you wish
								echo $val;
								exit();
							}
							else {
								$new_hashed_pass1 = password_hash($password1, PASSWORD_DEFAULT);
								$new_hashed_pass2 = password_hash($password2, PASSWORD_DEFAULT);
								
								mysqli_stmt_bind_param($stmt, "sss", $new_hashed_pass1, $new_hashed_pass2, $username);
								mysqli_stmt_execute($stmt);
								
								//we delete the selector and validator from the reset_data table
								
								$query = "DELETE FROM reset_data WHERE reset_email=?;";
								$stmt = mysqli_stmt_init($conn);
								if (!mysqli_stmt_prepare($stmt, $query)) {
									//handle this the way you wish
									echo $val;
									exit();
								}
								else {
									mysqli_stmt_bind_param($stmt, "s", $email);
									mysqli_stmt_execute($stmt);
									
									//we now unset and destroy session, then send user to sign in page
									session_unset();
									session_destroy();
									
									echo "success";
								}
							}
						}
						else {
							echo $val;
							exit();
						}
					}
				}
			}
			else {//no selector, therefore request needs to be re-submitted
				echo $val;
				exit();
			}
		}
	}
	mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
else {
	echo "inval";
	exit();
}