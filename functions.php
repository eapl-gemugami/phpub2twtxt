<?php
/**
 * The function searches for a key-value pair in a string and returns the value if found.
 *
 * @param keyToFind The key we want to find in the string.
 * @param string The string in which to search for the key-value pair.
 *
 * @return the value of the key that matches the given keyToFind in the given string. If a match is
 * found, the function returns the value of the key as a string after trimming any whitespace. If no
 * match is found, the function returns null.
 */
function getValue($keyToFind, $string) {
	if (!str_contains($string, $keyToFind)) {
		return null;
	}

	$pattern = '/\s*' . $keyToFind . '\s*=\s*([^#\n]+)/';
	$pattern = '/\s*' . $keyToFind . '\s*=\s*([^\s#]+)/';
	preg_match($pattern, $string, $matches);

	if (isset($matches[1])) {
		return trim($matches[1]);
	} else {
		return null;
	}
}

function getReplyHashFromTwt($twtString) {
	// Extract the text between parentheses using regular expressions
	$pattern = '/\(#([^\)]+)\)/'; // Matches "(#<text>)"
	preg_match($pattern, $twtString, $matches);

	if (isset($matches[1])) {
		$textBetweenParentheses = $matches[1];
		return $textBetweenParentheses;
	}
}

function getMentionsFromTwt($twtString) {
	$pattern = '/@<([^>]+)\s([^>]+)>/'; // Matches "@<nick url>"
	preg_match_all($pattern, $twtString, $matches, PREG_SET_ORDER);

	$result = array();

	foreach ($matches as $match) {
		$nick = $match[1];
		$url = $match[2];
		$result[] = array("nick" => $nick, "url" => $url);
	}

	return $result;
}

function replaceMentionsFromTwt($twtString) {
	$patterns = array(
		'/@<([^>]+)\s([^>]+)>/' => '@$1',
		'/https:\/\/[^\/]+/' => ''
	);

	$newString = preg_replace_callback(array_keys($patterns), function($matches) use ($patterns) {
		return $patterns[$matches[0]];
	}, $string);
}


function getTimeElapsedString($timestamp, $full = false) {
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

function getCachedFileContents($filePath, $cacheDuration = 15) {
	$cacheFile = __DIR__ . '/cache/' . md5($filePath);

	// Check if cache file exists and it's not expired
	if (file_exists($cacheFile) && time() - filemtime($cacheFile) < $cacheDuration) {
			return file_get_contents($cacheFile);
	}

	// File doesn't exist in cache or has expired, so fetch and cache it
	$contents = file_get_contents($filePath);
	file_put_contents($cacheFile, $contents);

	return $contents;
}

if (!function_exists('str_starts_with')) {
	function str_starts_with($haystack, $needle) {
		return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
	}
}
if (!function_exists('str_ends_with')) {
	function str_ends_with($haystack, $needle) {
		return $needle !== '' && substr($haystack, -strlen($needle)) === (string)$needle;
	}
}
if (!function_exists('str_contains')) {
	function str_contains($haystack, $needle) {
		return $needle !== '' && mb_strpos($haystack, $needle) !== false;
	}
}

/*
$string = '#        nick = me@eapl.mx';
$value = getValue('nick', $string);
echo $value; // Output: me@eapl.mx

$string = '# follow = https://twtxt.net/user/tkanos/twtxt.txt';
$value = getValue('follow', $string);
echo $value; // Output: me@eapl.mx
*/
