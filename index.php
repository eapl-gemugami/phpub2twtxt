<?php
# Shows the timeline for a user
declare(strict_types=1);

# Parameters
#
# url(string): Gets
# Default: public_txt_url in .config
#
# timeline_url(string) = Gets the timeline for that specificed URL (twtxt)
# Default: public_txt_url in .config
#
# page(int):
# Default: Page 1 of N
# If page is higher than N, shows nothing
#
# hash(string) =
#
#

require_once('session.php');
require_once('functions.php');
require_once('hash.php');

const TWTS_PER_PAGE = 50;

$config = parse_ini_file('.config');
$url = $config['public_txt_url'];

date_default_timezone_set('UTC');

if (!empty($_GET['url'])) {
	$url = $_GET['url'];
}

if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
	die('Not a valid URL');
}

const DEBUG_TIME_SECS = 300;
const PRODUCTION_TIME_SECS = 15;
$fileContent = getCachedFileContentsOrUpdate($url, PRODUCTION_TIME_SECS);
$fileContent = mb_convert_encoding($fileContent, 'UTF-8');

$fileLines = explode("\n", $fileContent);

$parsedTwtxtFiles = [];

$twtFollowingList = [];
foreach ($fileLines as $currentLine) {
	if (str_starts_with($currentLine, '#')) {
		if (!is_null(getDoubleParameter('follow', $currentLine))) {
			$follow = getDoubleParameter('follow', $currentLine);
			$twtFollowingList[] = $follow;

			// Read the parsed files if in Cache
			$followURL = $follow[1];
			$parsedTwtxtFile = getTwtsFromTwtxtString($followURL);
			if (!is_null($parsedTwtxtFile)) {
				$parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
			}
		}
	}
}

$twts = [];

# Combine all the followers twts
foreach ($parsedTwtxtFiles as $currentTwtFile) {
	if (!is_null($currentTwtFile)) {
		$twts += $currentTwtFile->twts;
	}
}

if (!empty($_GET['hash'])) {
	$hash = $_GET['hash'];

	$twts = array_filter($twts, function($twt) use ($hash) {
		return $twt->hash === $hash || $twt->replyToHash === $hash;
	});

}

krsort($twts, SORT_NUMERIC);

if (!empty($_GET['hash'])) {
	$twts = array_reverse($twts, true);
}

$page = 1;
if (!empty($_GET['page'])) {
	$page = intval($_GET['page']);
}

$startingTwt = (($page - 1) * TWTS_PER_PAGE);
$twts = array_slice($twts, $startingTwt, TWTS_PER_PAGE);
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
	<h1><a href=".">twtxt</a></h1>
	<h2>Timeline for <a href="<?= $url ?>"><?= $url ?></a></h2>
	<h3><a href="load_twt_files.php?url=<?= $url ?>">Reload timeline</a></h3>
	<h3><a href="new_twt.php">Write a new twt</a></h3>
	<details><summary>Following: <?php echo count($twtFollowingList); ?></summary>
    <?php foreach ($twtFollowingList as $currentFollower) { ?>
	<p>
		<a href="?url=<?= $currentFollower[1] ?>"><?= $currentFollower[0] ?></a> <?= $currentFollower[1] ?>
	</p>
<?php } ?>
	</details>
	<hr>
<?php foreach ($twts as $twt) { ?>
	<p>
		<img src='<?= $twt->avatar ?>' class="rounded" onerror="this.onerror=null;this.src='imgs/image_not_found.png';">
		<strong><span title="<?= $twt->mainURL ?>"><?= $twt->nick ?></span></strong>
		<a href='?hash=<?= $twt->hash ?>'><span title="<?= $twt->fullDate ?> "><?= $twt->displayDate ?></span></a>
		<br>
<?php if($twt->replyToHash) { ?>
		<em>Reply to thread <a href="?hash=<?= $twt->replyToHash?>"><?= $twt->replyToHash?></a></em><br>
<?php } ?>
		<?= $twt->content ?>
		<?php foreach ($twt->mentions as $mention) { ?>
			<br><?= $mention['nick'] ?>(<?= $mention['url'] ?>)
		<?php } ?>
		<br>
		<a href="new_twt.php?hash=<?= $twt->hash ?>">Reply</a>
	</p>
	<br>
<?php } ?>
	<div><a href="?page=<?= $page + 1 ?>">Next</a></div>
	<footer><hr><a href="https://github.com/eapl-gemugami/phpub2twtxt">source code</a></footer>
</body>
</html>