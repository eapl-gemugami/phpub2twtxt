<?php
# Shows the timeline for a user
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

const DEBUG_TIME_SECS = 300;
const PRODUCTION_TIME_SECS = 15;
$fileContent = getCachedFileContentsOrUpdate($url, DEBUG_TIME_SECS);
$fileContent = mb_convert_encoding($fileContent, 'UTF-8');

$fileLines = explode("\n", $fileContent);

$parsedTwtxtFiles = [];

$twtFollowingList = [];
foreach ($fileLines as $currentLine) {
	if (str_starts_with($currentLine, '#')) {
		if (!is_null(getDoubleParameter('follow', $currentLine))) {
			$follow = getDoubleParameter('follow', $currentLine);

			$followURL = $follow[1];
			$parsedTwtxtFile = getTwtsFromTwtxtString($followURL);
			$parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
			$twtFollowingList[] = $follow;
		}
	}
}

$twts = [];

//print_r(json_encode($twtFollowingList, JSON_PRETTY_PRINT));

foreach ($parsedTwtxtFiles as $currentTwtFile) {
	if (!is_null($currentTwtFile)) {
		$twts = array_merge($twts, $currentTwtFile->twts);
	}
}

krsort($twts, SORT_NUMERIC);
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
	<h2>Timeline for <a href="<?= $url ?>"><?= $url ?></a></h2>
	<h3>Following</h3>
<?php foreach ($twtFollowingList as $currentFollower) { ?>
	<p>
		<a href="?url=<?= $currentFollower[1] ?>"><?= $currentFollower[0] ?></a>
	</p>
<?php } ?>
	<hr>
<?php foreach ($twts as $twt) { ?>
	<p>
		<?php if($twt->replyToHash) { ?>
			<em>Reply to <a href="#"><?= $twt->replyToHash?></a></em><br>
		<?php } ?>
		<img src='<?= $twt->avatar ?>' class="rounded"> <strong><?= $twt->nick ?></strong>
		<a href='#<?= $twt->hash ?>'><span title="<?= $twt->fullDate ?>"><?= $twt->displayDate ?></span></a>

		<br>
		<?= $twt->content ?>
		<?php foreach ($twt->mentions as $mention) { ?>
			<br><?= $mention['nick'] ?>(<?= $mention['url'] ?>)
		<?php } ?>
	</p>
	<br>
<?php } ?>
	<footer><hr><a href="https://github.com/eapl-gemugami/phpub2twtxt">source code</a></footer>
</body>
</html>