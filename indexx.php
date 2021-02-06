<?php
	session_start();
	require 'function.php';


	if(isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
		$id = $_COOKIE['id'];
		$key = $_COOKIE['key'];

		//ambil username berdasarkan id
		$result = mysqli_query($conn, "SELECT username FROM pm_user where uid = '$id'");

		$row = mysqli_fetch_assoc($result);

		//cek cookie dan username
		if( $key === hash('sha256', $row['username']) ) {
			$_SESSION['login'] = true;
		}
	}


	if(isset($_SESSION["login"])){
		header("location:program/");
		exit;
	}

	if( isset($_POST["login"]) ) {
		$username = $_POST["username"];

		$vpass		= htmlentities($_POST["password"], ENT_QUOTES);
		$password 	= md5("pmart"."$vpass");

		$result = mysqli_query($conn, "SELECT username, password, uid, level FROM pm_user where (username='$username' and status='a') or (uid='$username' and status='a')");
		
		//check user
		if( mysqli_num_rows($result) === 1 ) {
			// check password
			$row = mysqli_fetch_assoc($result);
			if( $password == $row["password"] ) {
				// set Session 
				$_SESSION["login"] 		= true;
				$_SESSION["uid"] 		= $row["uid"];
				$_SESSION["username"] 	= $row["username"];
				$_SESSION["level"] 		= $row["level"];

				//check remember me
				if(isset($_POST['remember'])) {
					//set cookie
					setcookie('uid', $row['uid'], time() + 86400);
					setcookie('key', hash('sha256', $row['username']), time() + 86400);
				}

				header("location:program/");
				exit;
			}
		}

		$error = true;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>YES V.5.0</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.png"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(images/bg-01.jpg);">
					<span class="login100-form-title-1">
						<img src='images/Logo YES.png' style='width:35%; padding:0px;'>
					</span>
				</div>

				<form action="" method="post" class="login100-form validate-form">
					<?php if (isset($error)) : ?>
						<p style='color:red; padding-bottom:20px;'><i>Username / Password Salah</i></p>
					<?php endif; ?>
					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">Username</span>
						<input class="input100" type="text" name="username" id="username" placeholder="Enter username" autocomplete="off">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
						<span class="label-input100">Password</span>
						<input class="input100" type="password" id="password" name="password" placeholder="Enter password">
						<span class="focus-input100"></span>
					</div>

					<div class="flex-sb-m w-full p-b-30">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>
						</div>

						<!-- <div>
							<a href="#" class="txt1">
								Forgot Password?
							</a>
						</div> -->
					</div>

					<div class="container-login100-form-btn">
						<button type="submit" name="login" class="login100-form-btn">
							Login
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>