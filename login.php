<?php
require_once('session.php');

require_once('libs/TOTP.php');
$config = parse_ini_file('.config');
$secretKey = $config['totp_secret'];

if ($config['debug_mode']) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

if (isset($_SESSION['valid_session']))  {
	header('Location: .');
	exit();
}

$invalidCode = $_GET['invalid'] ?? false;

if (isset($_POST['totp'])) {
	$topt = $_POST['totp'];

	$isCodeValid = verifyTOTP($config['totp_secret'], $topt, intval($config['totp_digits']));
	// TODO: Add checks to avoid Brute force attacks by IP or similar

	if ($isCodeValid) {
		// Based on: https://shiflett.org/articles/session-fixation
		saveLoginSuccess($secretKey);
		header('Location: .');
		exit();
	} else {
		header('Location: login.php?invalid=1');
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>twtxt - Login</title>
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h1><a href=".">twtxt</a></h1>
	<form method="POST" class="column">
		<div id="login">
<?php if ($invalidCode) { ?>
			<div class="alert">Password is invalid, try again!</div><br>
<?php } ?>
			<label for="fname">One Time Password (TOTP)</label>
			<br>
  		<input type="text" id="totp" name="totp" class="input" autocomplete="off" required>
			<br>
			<input type="submit" value="Login" class="btn">
		</div>
	</form>
	<hr>
</body>
</html>