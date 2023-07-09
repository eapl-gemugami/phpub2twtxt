<?php
require_once('session.php');

require_once('libs/TOTP.php');
$config = parse_ini_file('.config');

if (isset($_POST['totp'])) {
	$topt = $_POST['totp'];

	$isCodeValid = verifyTOTP($config['totp_secret'], $topt, intval($config['totp_digits']));

	// TODO: Add checks to avoid Brute force attacks by IP or similar

	if ($isCodeValid) {
		// Based on: https://shiflett.org/articles/session-fixation
		session_regenerate_id();
		$_SESSION['valid_session'] = true;
		header('Location: .');
		exit();
	} else {
		$invalidCode = true;
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
			<?php if (isset($invalidCode)) { ?>
				<div class="alert">Password is invalid, try again!</div><br>
			<?php } ?>
			<label for="fname">One Time Password (TOTP)</label>
			<br>
  		<input type="text" id="totp" name="totp" class="input" autocomplete="off"><br>
			<input type="submit" value="Login" class="btn">
		</div>
	</form>
	</form>
	<hr>
</body>
</html>