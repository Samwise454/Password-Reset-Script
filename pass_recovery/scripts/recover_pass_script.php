<?php
/*This is the sytax for the reset table, but you can create table as you wish.
		CREATE TABLE reset_data (
			id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			reset_email VARCHAR(250) NOT NULL,
			reset_selector VARCHAR(250) NOT NULL,
			reset_token VARCHAR(250) NOT NULL,
			reset_expire VARCHAR(250) NOT NULL,
			date_created TIMESTAMP
		);*/
		
		
require_once 'login_db.php';
if (isset($_POST["re_mail"])) {
	$email = htmlspecialchars($_POST["re_mail"]);
	$val = "inval";
	
	if (empty($email)) {
		echo $val;
		exit();
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo $val;
		exit();
	}
	else {
		/*Now we want to create 2 tokens which will be sent to the client's email and this token will be used to confirm whether it is the right client requesting for the password. The 2 tokens will help control timing attacks from hackers. The random bytes fucntion will be used to generate the tokens in bytes then bin2hex function will be used to convert the bytes to hexadecimal value. When the tokens have been used, we use hex2bin to convert it back to binary for readablity.*/
		
		
		//variables 
		
		$selector = bin2hex(random_bytes(32));//hexadecimal value(1st)
		
		$token = random_bytes(32);//(2nd) to be used for authentication
		
		$url = "http://localhost/pass_recovery/recover_pass.php?selector=$selector&validator=".bin2hex($token);//this is the link we will be sending to the client's email.
		
		$expire = date("U") + 1800;//this will be used to make the token expire in 30 minutes.
		
		//remember we have collected the client's email as $email earlier.
		
		//let's now delete any existing token and selector in the table linked to the supplied email address.
		
		$query = "DELETE FROM reset_data WHERE reset_email=?;";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $query)) {
			//handle this as you deem fit
			echo $val;
			exit();
		}
		else {
			mysqli_stmt_bind_param($stmt, "s", $email);
			mysqli_stmt_execute($stmt);
		}
		
		//Now we insert fresh token and selector inside the database
		$query = "INSERT INTO reset_data (reset_email, reset_selector, reset_token, reset_expire) VALUES (?,?,?,?);";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $query)) {
			//handle this as you deem fit
			echo $val;
			exit();
		}
		else {
			//we hash the token for increased security
			$hash_token = password_hash($token, PASSWORD_DEFAULT);
			mysqli_stmt_bind_param($stmt, "ssss", $email, $selector, $hash_token, $expire);
			mysqli_stmt_execute($stmt);
			
			/*now we send the mail to the client's email address.
			You can do this with the php mail() function or phpmailer
			whichever one you use set smtp and others accordingly and also style the html as you wish*/
			
			$to = $email;
			$subject = "Password Reset";
			$message = '<p>Hello, you requested a password reset from this account, if you did not initiate this request, please ignore this mail.</p>
			<p>Here is your password reset link: <a href="'.$url.'">'.$url.'</a></p>';
			$headers = "From: support@pass_reset\r\n";
			$headers .= "Reply-To: support@pass_reset\r\n";
			$headers .= "Content-type: text/html\r\n";
			
			//we will comment out the mail() function for now until we get to a live server
			//mail($to, $subject, $message, $headers);
			
			$combo = $selector."(*)".bin2hex($token);//this is created solely for the ajax call, a combination of selector and token, which will be split in javascript file and then added to the url, because we are on localhost.
			echo $combo;//we are echoing because we are using localhost
			
			/*If you are on a live server and email services configured, you use the header below, note we are sending the client back to the index page because he/she is expected to get the pass reset url in the email address provided.
			header("Location: http://localhost/pass_recovery/index.php?val=passreset");*/
			exit();
		}
	}
	mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
else {
	echo "inval";
	exit();
}