<?php
// Check that we have a valid session, otherwise show the Login button
// After that redirect to index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>phpub2twtxt</title>
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h1>phpub2twtxt</h1>
	<p>A Web interface to publish quickly to your twtxt.txt file</p>
	<form method="POST" class="column">
		<div id="login">
			<input type="submit" value="Login" name="sub">
		</div>
	</form>
	<footer><a href="https://github.com/eapl-gemugami/phpub2twtxt">source code</a></footer>
</body>
</html>