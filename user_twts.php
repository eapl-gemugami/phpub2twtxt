<?php
declare(strict_types=1);
date_default_timezone_set('UTC');

require_once('functions.php');
require_once('hash.php');

$config = parse_ini_file('.config');
$url = $config['public_txt_url'];

if (!empty($_GET['url'])) {
	$url = $_GET['url'];
}

if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
	die('Not a valid URL');
}

# TODO: Process the Warning
# Warning: file_get_contents(https://eapl.mx/twtxt.net):
# failed to open stream: HTTP request failed! HTTP/1.1 404 Not Found in

const DEBUG_TIME_SECS = 300;
const PRODUCTION_TIME_SECS = 15;
$fileContent = getCachedFileContents($url, DEBUG_TIME_SECS);
if (is_null($fileContent)) {
	echo "File doesn't exist";
	die();
}
$fileContent = mb_convert_encoding($fileContent, 'UTF-8');

$fileLines = explode("\n", $fileContent);

$twts = [];

$twtMainUrl = $url; // The first one
$twtUrls = [];
$twtNick = '';
$twtAvatar = '';
$twtLinks = [];
$twtLang = '';
$twtDescription = '';
$twtFollowingList = [];

foreach ($fileLines as $currentLine) {
	// Remove empty lines and comments
	if (empty($currentLine)) {
		continue;
	}

	if (str_starts_with($currentLine, '#')) {
		if (!is_null(getSingleParameter('url', $currentLine))) {
			$currentURL = getSingleParameter('url', $currentLine);

			if (empty($twtMainUrl)) {
				$twtMainUrl = $currentURL;
			}
			$twtUrls[] = $currentURL;
		}
		if (!is_null(getSingleParameter('nick', $currentLine))) {
			$twtNick = getSingleParameter('nick', $currentLine);
		}
		if (!is_null(getSingleParameter('avatar', $currentLine))) {
			$twtAvatar = getSingleParameter('avatar', $currentLine);
		}
		if (!is_null(getSingleParameter('lang', $currentLine))) {
			$twtLang = getSingleParameter('lang', $currentLine);
		}
		if (!is_null(getSingleParameter('description', $currentLine))) {
			$twtDescription = getSingleParameter('description', $currentLine);
		}
		if (!is_null(getSingleParameter('follow', $currentLine))) {
			$twtFollowingList[] = getSingleParameter('follow', $currentLine);

			// Fix that the follow has a nick\nurl structure
			# follow = @birdsite.slashdev.space https://birdsite.slashdev.space/users/mirkosertic
		}
	}

	if (!str_starts_with($currentLine, '#')) {
		$explodedLine = explode("\t", $currentLine);
		if (count($explodedLine) >= 2) {
			$dateStr = $explodedLine[0];
			$twtContent = $explodedLine[1];

			// Convert HTML problematic characters
			$twtContent = htmlentities($twtContent);

			// Replace the Line separator character (U+2028)
			// \u2028 is \xE2 \x80 \xA8 in UTF-8
			// Check here: https://www.mclean.net.nz/ucf/
			$twtContent = str_replace("\xE2\x80\xA8", "<br>\n", $twtContent);

			// Get and remote the hash
			$hash = getReplyHashFromTwt($twtContent);
			if ($hash) {
				$twtContent = str_replace("(#$hash)", '', $twtContent);
			}

			// Get mentions
			$mentions = getMentionsFromTwt($twtContent);

			if (($timestamp = strtotime($dateStr)) === false) {
				//echo "The string ($dateStr) is incorrect";
				continue;
			} else {
				$displayDate = getTimeElapsedString($timestamp);
			}

			$twts[$timestamp] = [
				'originalTwtStr' => $currentLine,
				'hash' => getHashFromTwt($currentLine, $twtMainUrl),
				'fullDate' => date('j F Y h:i', $timestamp),
				'displayDate' => $displayDate,
				'content' => $twtContent,
				'replyToHash' => $hash,
				'mentions' => $mentions,
			];

			// TODO: Interpret the content as markdown
		}
	}
}

/*
echo "Main URL: $twtMainUrl";
//echo "URLS: $twtUrls";
echo "Nick: $twtNick";
echo "Avatar URL: $twtAvatar";
//echo "Links: $twtLinks";
echo "Lang Code: $twtLang";
echo "Description $twtDescription";
*/

krsort($twts, SORT_NUMERIC);

// Get followers and print a list!
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
	<h2>twts for <a href="<?= $url ?>"><?= $twtNick ?></a></h2>
<!--
	<h3>Following</h3>
<?php foreach ($twtFollowingList as $currentFollower) { ?>
	<p>
		<a href="read.php?url=<?= $currentFollower ?>"><?= $currentFollower ?></a>
	</p>
<?php } ?>
-->
<?php foreach ($twts as $twt) { ?>
	<p>
		<?php if($twt['replyToHash']) { ?>
			<em>Reply to <a href="#"><?= $twt['replyToHash']?></a></em><br>
		<?php } ?>
		<img src='<?= $twtAvatar ?>' class="rounded"> <strong><?= $twtNick ?></strong>
		<a href='#<?= $twt['hash'] ?>'><span title="<?= $twt['fullDate'] ?>"><?= $twt['displayDate'] ?></span></a>

		<br>
		<?= $twt['content'] ?>
		<?php foreach ($twt['mentions'] as $mention) { ?>
			<br><?= $mention['nick'] ?>(<?= $mention['url'] ?>)
		<?php } ?>
	</p>
	<br>
<?php } ?>
	<footer><hr><a href="https://github.com/eapl-gemugami/phpub2twtxt">source code</a></footer>
</body>
</html>