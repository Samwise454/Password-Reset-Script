<?php
/*This is the sytax for the client data table, but you can create table as you wish.
		CREATE TABLE client_data (
			id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			username VARCHAR(250) NOT NULL,
			password VARCHAR(250) NOT NULL,
			confirm_password VARCHAR(250) NOT NULL,
			email VARCHAR(250) NOT NULL,
			tel VARCHAR(15) NOT NULL,
			date_created TIMESTAMP
		);*/
		
require_once 'login_db.php';//you can use your database connection here to test the script


if (isset($_POST["s_uname"]) && isset($_POST["s_pword"]) && isset($_POST["s_repword"]) && isset($_POST["email"]) && isset($_POST["tel"])) {
	$username = htmlspecialchars($_POST["s_uname"]);
	$password = htmlspecialchars($_POST["s_pword"]);
	$re_password = htmlspecialchars($_POST["s_repword"]);
	$email = htmlspecialchars($_POST["email"]);
	$tel = htmlspecialchars($_POST["tel"]);
	$val = "inval";
	
	if (empty($username) || empty($password) || empty($re_password) || empty($email) || empty($tel)) {
		echo $val;
		exit();
	}
	else if ($password !== $re_password) {
		echo $val;
		exit();
	}
	else if (mb_strlen($password) < 8) {
		echo $val;
		exit();
	}
	//you can add other checks, preg_match etc and you can use ajax or any other tech.
	//lets move on to querying the database procedurally.
	else {
		$query = "SELECT * FROM client_data WHERE username=?;";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $query)) {
			//You can choose what to do here
			echo $val;
			exit();
		}
		else {
			mysqli_stmt_bind_param($stmt, "s", $username);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$num_rows = mysqli_num_rows($result);
			if ($num_rows > 0) {
				//you can decide what to do here
				echo $val;
				exit();
			}
			else {
				//let's insert value into the table
				$query = "INSERT INTO client_data (username, password, confirm_password, email, tel) VALUES (?,?,?,?,?);";
				$stmt = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($stmt, $query)) {
					//You can choose what to do here
					echo $val;
					exit();
				}
				else {
					$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
					$hashedPasswordRepeat = password_hash($re_password, PASSWORD_DEFAULT);
					
					mysqli_stmt_bind_param($stmt, "sssss", $username, $hashedPassword, $hashedPasswordRepeat, $email, $tel);
					mysqli_stmt_execute($stmt);
					
					session_start();
					//let's query to get the data from client_data table to start session
					
					$query = "SELECT * FROM client_data WHERE username=?;";
					$stmt = mysqli_stmt_init($conn);
					if (!mysqli_stmt_prepare($stmt, $query)) {
						//you can choose what to do here
						echo $val;
						exit();
					}
					else {
						mysqli_stmt_bind_param($stmt, "s", $username);
						mysqli_stmt_execute($stmt);
						$result = mysqli_stmt_get_result($stmt);
						$num_rows = mysqli_num_rows($result);
						if ($num_rows > 0) {
							$row = mysqli_fetch_assoc($result);
							$pass_verify = password_verify($password, $row["password"]);
							if ($pass_verify === false) {
								echo $val;
								exit();
							}
							else if ($pass_verify === true) {
								//you can secure your session better 
								$_SESSION["user_id"] = $row["username"];
								$_SESSION["email"] = $row["email"];
								$_SESSION["tel"] = $row["tel"];
							}
						}
						else {
							echo $val;
						}
					}
				}
			}
		}
	}
	    mysqli_stmt_close($stmt);
		mysqli_close($conn2);
}
else {
	echo "inval";
	exit();
}