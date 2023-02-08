<?php
require_once 'login_db.php';//you can use your database connection here to test the script

if (isset($_POST["l_uname"]) && isset($_POST["l_pword"])) {
	$uname = htmlspecialchars($_POST["l_uname"]);
	$pword = htmlspecialchars($_POST["l_pword"]);
	$val = "inval";

	if (empty($uname) || empty($pword)) {
		echo $val;
		exit();
	}
	//you can add other checks here like preg_match and co.
	else {
		$query = "SELECT * FROM client_data WHERE username=?;";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $query)) {
			//you can choose what to do here
			echo $val;
			exit();
		}
		else {
			mysqli_stmt_bind_param($stmt, "s", $uname);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$num_rows = mysqli_num_rows($result);
			
			if ($num_rows > 0) {
				$row = mysqli_fetch_assoc($result);
				$pass_verify = password_verify($pword, $row["password"]);
				if ($pass_verify == false) {
					echo $val;
					exit();
				}
				else if ($pass_verify == true) {
					session_start();//you can secure your session better 
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
else {
	echo "inval";
	exit();
}