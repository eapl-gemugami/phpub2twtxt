<?php
// Copy the .config.sample file to .config and replace the content
// with your settings

// TODO: Give a warning if the file is not found
$config = parse_ini_file('.config');

$txt_file_path = $config['txt_file_path']; // File route
$public_txt_url = $config['public_txt_url'];
$pass = $config['master_password'];

session_start();

if (!isset($_SESSION['valid_session']))  {
	header("location: login.html");
}

if (isset($_POST['sub'])) {
	$valid_access = password_verify($_POST['pass'], $pass);
	// We assume $_SESSION['valid_session'] set means we have a valid_access now
	$valid_access = true;

	if ($valid_access) {
		$new_post = filter_input(INPUT_POST, 'new_post');
		$new_post = str_replace("\n","\u{2028}", $new_post);
		$new_post = str_replace("\r","", $new_post);

		if ($new_post) {
			$contents = file_get_contents($txt_file_path);
			$contents .= date("Y-m-d\TH:i:s\Z") . "\t";
			$contents .= "$new_post" . "\n";

			// TODO: Add error handling if write to the file fails
			// For example due to permissions problems
			$file_write_result = file_put_contents($txt_file_path, $contents);

			header('Refresh:0; url=?');
			exit;
		} else {
			echo "Oops something went wrong...\n\nCheck the error_log on the server";
			exit;
		}
	} else{ header("location: ?retry"); }
} else { ?>
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
	<p>A Web interface to post quickly to your twtxt.txt file</p>
	<?php if(isset($_GET["retry"])){echo '<div id="retry">Your password isn\'t valid, check that!</div>';} ?>
	<form method="POST" class="column">
		<div id="posting">
			<textarea id="new_post" name="new_post" rows="4" cols="100" autofocus placeholder="Type you twtxt post here"></textarea>
			<input type="submit" value="Post" name="sub">
		</div>
	</form>
	<p>
		<a href="<?= $public_txt_url ?>">Your twtxt.txt file</a>
	</p>
	<footer><a href="https://github.com/eapl-gemugami/phpub2twtxt">source code</a></footer>
</body>
</html>
<?php } ?>
