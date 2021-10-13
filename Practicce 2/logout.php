<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Logout</title>
</head>
<body>
	<?php
		unset($_SESSION['iv']);
		unset($_SESSION['cipher']);
		session_destroy();
		header("Location: login.html"); 
	?>
</body>
</html>