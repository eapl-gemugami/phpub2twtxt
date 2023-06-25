<?php
declare(strict_types=1);
date_default_timezone_set('UTC');

require_once('functions.php');
require_once('hash.php');

if (!empty($_GET['url'])) {
	$url = $_GET['url'];
} else {
	die('Not a valid URL');
}

if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
	die('Not a valid URL');
}

$fileContent = file_get_contents($url);
$fileContent = mb_convert_encoding($fileContent, 'UTF-8');

// \u2028 is \xE2 \x80 \xA8 in UTF-8
// Check here: https://www.mclean.net.nz/ucf/
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
		if (!is_null(getValue('url', $currentLine))) {
			$currentURL = getValue('url', $currentLine);

			if (empty($twtMainUrl)) {
				$twtMainUrl = $currentURL;
			}
			$twtUrls[] = $currentURL;
		}
		if (!is_null(getValue('nick', $currentLine))) {
			$twtNick = getValue('nick', $currentLine);
		}
		if (!is_null(getValue('avatar', $currentLine))) {
			$twtAvatar = getValue('avatar', $currentLine);
		}
		if (!is_null(getValue('lang', $currentLine))) {
			$twtLang = getValue('lang', $currentLine);
		}
		if (!is_null(getValue('description', $currentLine))) {
			$twtDescription = getValue('description', $currentLine);
		}
		if (!is_null(getValue('follow', $currentLine))) {
			$twtFollowingList[] = getValue('follow', $currentLine);

			// Fix that the follow has a nick\nurl structure
			# follow = @birdsite.slashdev.space https://birdsite.slashdev.space/users/mirkosertic
		}
	}

	if (!str_starts_with($currentLine, '#')) {
		$explodedLine = explode("\t", $currentLine);
		if (count($explodedLine) >= 2) {
			$dateStr = $explodedLine[0];
			$twtContent = $explodedLine[1];

			$twtContent = str_replace("\xE2\x80\xA8", "\n<br>", $twtContent);

			if (($timestamp = strtotime($dateStr)) === false) {
				//echo "The string ($dateStr) is incorrect";
				continue;
			} else {
				$displayDate = getTimeElapsedString($timestamp);
			}

			$twts[$timestamp] = [
				'originalTwtStr' => $currentLine,
				'fullDate' => date('j F Y h:i', $timestamp),
				'displayDate' => $displayDate,
				'content' => htmlentities($twtContent),
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
	<h2><?= $url ?></h2>
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
	<br>
		<img src='<?= $twtAvatar ?>' class="rounded"> <strong><?= $twtNick ?></strong>
		<a href='#<?= getHashFromTwt($twt['originalTwtStr'], $twtMainUrl) ?>'><span title="<?= $twt['fullDate'] ?>"><?php echo $twt['displayDate'] ?></span></a><br>
		<?= $twt['content'] ?>
	</p>
<?php } ?>
	<footer><hr><a href="https://github.com/eapl-gemugami/phpub2twtxt">source code</a></footer>
</body>
</html>