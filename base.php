<?php
session_start([
	'name' => 'twtxt',
	'use_strict_mode' => true,
	'cookie_httponly' => true,
	'cookie_secure' => true,
	'cookie_lifetime' => 604800, // 7 days
	'gc_maxlifetime' => 604800, // 7 days
	'sid_length' => 64,
	'sid_bits_per_character' => 6,
	'cookie_samesite' => 'Strict',
]);

// TODO: Implement improvements to session
// https://www.php.net/manual/en/function.session-regenerate-id.php
// https://stackoverflow.com/questions/1236374/session-timeouts-in-php-best-practices

// Add csrf protection like on
// https://hg.sr.ht/~m15o/mebo/browse/classes/Session.php?rev=tip