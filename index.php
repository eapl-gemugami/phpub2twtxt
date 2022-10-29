<?php
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

// Copy the .config.sample file to .config and replace the content
// with your settings

// TODO: Give a warning if the file is not found
$config = parse_ini_file('.config');

$txt_file_path = $config['txt_file_path']; // File route
$public_txt_url = $config['public_txt_url'];
$pass = $config['master_password'];

$timezone = $config['timezone'];

session_start();

if (!isset($_SESSION['valid_session']))  {
	header("location: login.html");
}

if (isset($_POST['sub'])) {
	// We assume $_SESSION['valid_session'] set means we have a valid_access now
	$valid_access = true;

	if ($valid_access) {
		$new_post = filter_input(INPUT_POST, 'new_post');
		$new_post = str_replace("\n","\u{2028}", $new_post);
		$new_post = str_replace("\r","", $new_post);

		if ($new_post) {
			// Check if we have a point to insert the next Twt
			define('NEW_TWT_MARKER', "#~~~#\n");
			$contents = file_get_contents($txt_file_path);

			if (!date_default_timezone_set($timezone)) {
				date_default_timezone_set('UTC');
			}

			$twt = date('c') . "\t$new_post\n";

			if (strpos($contents, NEW_TWT_MARKER) !== false) {
				// Add the previous marker
				$twt = NEW_TWT_MARKER . $twt;
				$contents = str_replace(NEW_TWT_MARKER, $twt, $contents);
			} else {
				$contents .= $twt;
			}

			// TODO: Add error handling if write to the file fails
			// For example due to permissions problems
			$file_write_result = file_put_contents($txt_file_path, $contents);

			header('Refresh:0; url=?');
			exit;
		} else {
			echo "Oops something went wrong...\n\nCheck the error_log on the server";
			exit;
		}
	} else { header("location: ?retry"); }
} else { ?>
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
