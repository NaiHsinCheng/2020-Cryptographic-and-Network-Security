<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Result</title>
	<style type="text/css">
	body{
		text-align: center;
		line-height: 17pt;
		font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
		font-size: 15px;
	}
	h1{
		text-align: center;
		font-weight: bolder;
		font-size: 50px;
	}
	div{
		column-gap: 0px;
		width: 600px;
	}
	p{
		color: #555555;
	}
	</style>
</head>

<body class="result-body">
	<center>
		<?php
		define("SECRET_KEY", '9999999999999999');
		define("METHOD", "aes-128-cbc");
		session_start();

		function getflag(){
			echo '<p>Flag is ctf{123cbcchange}</p>';
		}

		function get_random_iv(){
			$random_iv='';
			for($i=0;$i<16;$i++){
				$random_iv.=chr(rand(1,255));
			}
			return $random_iv;
		}

		function login($info){
			$iv = get_random_iv();
			$plain = serialize($info);
			$cipher = openssl_encrypt($plain, METHOD, SECRET_KEY, OPENSSL_RAW_DATA, $iv);
			$_SESSION['username'] = $info['username'];
			
			setcookie("iv", base64_encode($iv));
			setcookie("cipher", base64_encode($cipher));
			//echo base64_encode($iv);
			//echo "<br>"; 
			//echo base64_encode($cipher);
		}

		function check_login(){
			if(isset($_COOKIE['cipher']) && isset($_COOKIE['iv'])){
				$cipher = base64_decode($_COOKIE['cipher']);
				$iv = base64_decode($_COOKIE["iv"]);
				
				if($plain = openssl_decrypt($cipher, METHOD, SECRET_KEY, OPENSSL_RAW_DATA, $iv)){
					//echo $plain;
					$info = @unserialize($plain) or die("<p>base64_decode('".base64_encode($plain)."') can't unserialize</p>");
					$_SESSION['username'] = $info['username'];
				}else{
					die("ERROR!");
				}
				
			}
		}

		function show_homepage(){
			if ($_SESSION["username"]==='admin'){
				echo '<p>Hello admin</p>';
				getflag();
			}else{
				echo '<p>hello '.$_SESSION['username'].'</p>';
				echo '<p>Only admin can see flag</p>';
			}
			echo '<p><a href="logout.php">Log out</a></p>';
		}

		if(isset($_POST['username']) && isset($_POST['password'])){
			$username = (string)$_POST['username'];
			$password = (string)$_POST['password'];
			if($username === 'admin'){
				echo '<p>admin are not allowed to login</p>';
				echo '<p><a href="login.html">Go back</a></p>';
				exit();
			}else{
				$info = array('username'=>$username,'password'=>$password);
				login($info);
			}
		}
		
		if(isset($_SESSION["username"])){
			show_homepage();
			check_login();
		}
		?>
	<center>
</body>
</html>