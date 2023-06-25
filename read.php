<?php
function time_elapsed_string($timestamp, $full = false) {
	$now = new DateTime;
	$ago = new DateTime;
	$ago->setTimestamp($timestamp);

	$diff = $now->diff($ago);

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}

	if (!$full) $string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) . ' ago' : 'just now';
}

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

foreach ($fileLines as $currentLine) {
	// Remove empty lines and comments
	if (!str_starts_with($currentLine, '#') && !empty($currentLine)) {
		$explodedLine = explode("\t", $currentLine);
		if (count($explodedLine) >= 2) {
			$dateStr = $explodedLine[0];
			$twtContent = $explodedLine[1];

			$twtContent = str_replace("\xE2\x80\xA8", "\n<br>", $twtContent);

			if (($timestamp = strtotime($dateStr)) === false) {
				//echo "The string ($dateStr) is incorrect";
				continue;
			} else {
				$displayDate = time_elapsed_string($timestamp);
			}

			$twts[$timestamp] = [
				'fullDate' => date('j F Y h:i', $timestamp),
				'displayDate' => $displayDate,
				'content' => $twtContent,
			];
		}
	}
}

krsort($twts, SORT_NUMERIC);

// Get followers

// twtxt Multiline extension
// https://dev.twtxt.net/doc/multilineextension.html

// twtxt Hash extension
// https://dev.twtxt.net/doc/twthashextension.html

//echo sodium_crypto_generichash('Message');
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
	<h1>twtxt <?php echo $url ?></h1>
	<p></p>
<?php foreach ($twts as $twt) { ?>
	<p>
		<?php echo $twt['content'] ?><br>
		<span title="<?php echo $twt['fullDate'] ?>"><?php echo $twt['displayDate'] ?></span>
	</p>
<?php }  ?>

	<form method="POST" class="column">
		<div id="login">
		</div>
	</form>
	<footer><a href="https://github.com/eapl-gemugami/phpub2twtxt">source code</a></footer>
</body>
</html>