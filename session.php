<?php
const COOKIE_LIFETIME = 7 * 24 * 60 * 60; // 7 days * 24 hours * 60 minutes * 60 seconds
const GARBAGE_COLLECTOR_LIFETIME = 10 * 24 * 60 * 60; // 10 days * 24 hours * 60 minutes * 60 seconds
const TIME_TO_REFRESH_SESSION = 1 * 60 * 60; // 1 hour * 60 minutes * 60 seconds

session_start([
	'name' => 'twtxt',
	'use_strict_mode' => true,
	'cookie_httponly' => true,
	'cookie_secure' => true,
	'cookie_lifetime' => COOKIE_LIFETIME,
	'gc_maxlifetime' => GARBAGE_COLLECTOR_LIFETIME,
	'sid_length' => 64,
	'sid_bits_per_character' => 6,
	//'cookie_samesite' => 'Strict', // Not compatible with PHP lower than 7.3
	// TODO: Move this to config
	'save_path' => '/var/lib/php/sessions/twtxt'
]);

$validSession = $_SESSION['valid_session'] ?? null;

// Check if the session is new or expired
if (!isset($_SESSION['last_activity'])
		|| (time() - $_SESSION['last_activity']) > TIME_TO_REFRESH_SESSION) {
	// If the session is new or expired, refresh the session ID
	// TODO: Implement improvements to regenerate
	// https://www.php.net/manual/en/function.session-regenerate-id.php
	// https://stackoverflow.com/questions/1236374/session-timeouts-in-php-best-practices
	session_regenerate_id(true);

	// Update the session last activity timestamp
	$_SESSION['last_activity'] = time();
	$_SESSION['valid_session'] = $validSession;
}

// Cookie samesite (For PHP <7.3)
// https://stackoverflow.com/questions/39750906/php-setcookie-samesite-strict

// Add CSRF protection like on
// https://hg.sr.ht/~m15o/mebo/browse/classes/Session.php?rev=tip