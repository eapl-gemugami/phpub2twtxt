<?php
// TODO: Give a warning if the file is not found
$config = parse_ini_file('.config');

if ($config['debug_mode']) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$txt_file_path = $config['txt_file_path']; // File route
$public_txt_url = $config['public_txt_url'];
#$pass = $config['master_password'];

$timezone = $config['timezone'];

require_once('base.php');

if (!isset($_SESSION['valid_session']))  {
	header('Location: login.php');
}

$textareaValue = '';
if (isset($_GET['hash'])) {
	$hash = $_GET['hash'];
	$textareaValue = "(#$hash) ";
}

if (isset($_POST['submit'])) {
	$valid_access = true;

	if ($valid_access) {
		$new_post = filter_input(INPUT_POST, 'new_post');
		// Replace new lines for Line separator character (U+2028)
		$new_post = str_replace("\n", "\u{2028}", $new_post);
		$new_post = str_replace("\r", '', $new_post);

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

			//header('Refresh:0; url=?');
			header('Refresh:0; url=.');
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
			<textarea class="textinput" id="new_post" name="new_post"
				rows="4" cols="100" autofocus
				placeholder="Type you twtxt post here"><?= $textareaValue ?></textarea>
			<br>
			<input class="btn" type="submit" value="Post" name="submit">
		</div>
	</form>
	<p>
		<a href="<?= $public_txt_url ?>">Your twtxt.txt file</a>
	</p>
	<footer><a href="https://github.com/eapl-gemugami/phpub2twtxt">source code</a></footer>
</body>
</html>
<?php } ?>
