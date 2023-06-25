<?php
function getValue($key, $string) {
	$pattern = '/\s*' . $key . '\s*=\s*([^#\n]+)/';
	preg_match($pattern, $string, $matches);

	if (isset($matches[1])) {
		return trim($matches[1]);
	} else {
		return null;
	}
}

$string = '#        nick = me@eapl.mx';
$value = getValue('nick', $string);
echo $value; // Output: me@eapl.mx

$string = '# follow = https://twtxt.net/user/tkanos/twtxt.txt';
$value = getValue('follow', $string);
echo $value; // Output: me@eapl.mx