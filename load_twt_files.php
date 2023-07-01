<?php
# Gets the followers from an URL and then gets all the Followers twtxt.txt files
# Intended to be run in the background
declare(strict_types=1);
require_once('functions.php');
require_once('hash.php');

ini_set('max_execution_time', '300');

ob_start();

$config = parse_ini_file('.config');
$url = $config['public_txt_url'];

if (!empty($_GET['url'])) {
	$url = $_GET['url'];
}

if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
	die('Not a valid URL');
}

echo "Loading URL: $url\n<br>\n<br>";
ob_flush();

const DEBUG_TIME_SECS = 300;
const PRODUCTION_TIME_SECS = 15;
$fileContent = getCachedFileContentsOrUpdate($url, DEBUG_TIME_SECS);
$fileContent = mb_convert_encoding($fileContent, 'UTF-8');

$fileLines = explode("\n", $fileContent);

$twtFollowingList = [];
foreach ($fileLines as $currentLine) {
	if (str_starts_with($currentLine, '#')) {
		if (!is_null(getDoubleParameter('follow', $currentLine))) {
			$twtFollowingList[] = getDoubleParameter('follow', $currentLine);
		}
	}
}

# Load all the files
# Save a flag to know it's loading files
foreach ($twtFollowingList as $following) {
	echo "Updating: $following[1]\n<br>";
	ob_flush();
	updateCachedFile($following[1]);
}
echo 'Finished';
ob_flush();

header('Location: .');
