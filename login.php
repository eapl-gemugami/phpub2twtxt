<?php
require_once('base.php');

require_once('libs/TOTP.php');
$config = parse_ini_file('.config');

if (isset($_POST['totp'])) {
	$topt = trim($_POST['totp']);
	//echo intval($config['totp_digits']);

	$isCodeValid = verifyTOTP($config['totp_secret'], $topt, intval($config['totp_digits']));

	// TODO: Add checks to avoid Brute force attacks by IP or similar

	if ($isCodeValid) {
		$_SESSION['valid_session'] = true;
		header('Location: .');
	} else {
		$invalidCode = true;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>twtxt</title>
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h1>twtxt</h1>
	<p>A Web interface to publish quickly to your twtxt.txt file</p>
	<form method="POST" class="column">
		<div id="login">
			<?php if (isset($invalidCode)) { ?>
				<div>TOTP is invalid, try again!</div>
			<?php } ?>
			<label for="fname">TOTP:</label>
			<br>
  		<input type="text" id="totp" name="totp"><br>
			<input type="submit" value="Login" class="btn">
		</div>
	</form>
	</form>
	<hr>
	<footer><a href="https://github.com/eapl-gemugami/phpub2twtxt">source code</a></footer>
</body>
</html>