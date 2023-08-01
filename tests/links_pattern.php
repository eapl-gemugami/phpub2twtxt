<?php
$string = 'This is an URL: https://ea.com gemini://example.com https://youtu.be/ysaNUatLMn0 https://eapl.me/twtxt.txt Friends... OpenBSD.. https://ea.com<br> eapl.me 8.2';

/*
// Broken links:
<a href="eapl.me">eapl.me</a>
<a href="8.2">8.2</a>
*/

// Regular expression pattern to match URLs
//$pattern = '/(?<!\S)(\b(https?|ftp|gemini|spartan|gopher):\/\/\S+|\S+\.\S+\.\S+)(?!\S)/';
//$pattern = '/(?<!\S)((?:https?|ftp|gemini|spartan|gopher):\/\/\S+|(?:\S+\.)+\S+)(?!\S)/';
//$pattern = '/(?<!\S)((?:https?|ftp|gemini|spartan|gopher):\/\/\S+|(?:\S+\.)+\S+(?:\/\S*)?)(?![^\n\S])/';
$pattern = '/(?<!\S)(\b(https?|ftp|gemini|spartan|gopher):\/\/\S+|\b(?!:\/\/)\w+(?:\.\w+)+(?:\/\S+)?)(?!\S)/';

// Replace URLs with clickable links
$replacement = '<a href="$1">$1</a>';
$result = preg_replace($pattern, $replacement, $string);

// Output the result
echo $result;