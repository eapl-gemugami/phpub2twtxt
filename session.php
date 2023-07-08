<?php
//const COOKIE_LIFETIME = 7 * 24 * 60 * 60; // 7 days * 24 hours * 60 minutes * 60 seconds
const COOKIE_LIFETIME = 5 * 60; // 5 minutes * 60 seconds

session_start([
	'name' => 'twtxt',
	'use_strict_mode' => true,
	'cookie_httponly' => true,
	'cookie_secure' => true,
	'cookie_lifetime' => COOKIE_LIFETIME,
	'gc_maxlifetime' => COOKIE_LIFETIME,
	'sid_length' => 64,
	'sid_bits_per_character' => 6,
	'cookie_samesite' => 'Strict',
	'save_path' => '/var/lib/php/sessions/twtxt'
]);

// Check if the session is new or expired
if (!isset($_SESSION['last_activity'])
		|| (time() - $_SESSION['last_activity']) > COOKIE_LIFETIME) {
	// If the session is new or expired, refresh the session ID
	session_regenerate_id(true);

	// Update the session last activity timestamp
	$_SESSION['last_activity'] = time();
}

// TODO: Implement improvements to session
// https://www.php.net/manual/en/function.session-regenerate-id.php
// https://stackoverflow.com/questions/1236374/session-timeouts-in-php-best-practices

// Add CSRF protection like on
// https://hg.sr.ht/~m15o/mebo/browse/classes/Session.php?rev=tip