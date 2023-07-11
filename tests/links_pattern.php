<?php
$string = 'This is an URL: https://ea.com gemini://example.com https://youtu.be/ysaNUatLMn0 https://eapl.me/twtxt.txt Friends... OpenBSD..';

// Regular expression pattern to match URLs
//$pattern = '/(?<!\S)(\b(https?|ftp|gemini|spartan|gopher):\/\/\S+|\S+\.\S+\.\S+)(?!\S)/';
//$pattern = '/(?<!\S)((?:https?|ftp|gemini|spartan|gopher):\/\/\S+|(?:\S+\.)+\S+)(?!\S)/';
$pattern = '/(?<!\S)(\b(https?|ftp|gemini):\/\/\S+|\b(?!:\/\/)\w+(?:\.\w+)+(?:\/\S+)?)(?!\S)/';

// Replace URLs with clickable links
$replacement = '<a href="$1">$1</a>';
$result = preg_replace($pattern, $replacement, $string);

// Output the result
echo $result;