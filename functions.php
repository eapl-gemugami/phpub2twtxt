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

/*
$string = '#        nick = me@eapl.mx';
$value = getValue('nick', $string);
echo $value; // Output: me@eapl.mx

$string = '# follow = https://twtxt.net/user/tkanos/twtxt.txt';
$value = getValue('follow', $string);
echo $value; // Output: me@eapl.mx
*/