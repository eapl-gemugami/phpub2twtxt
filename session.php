<?php
$config = parse_ini_file('.config');
$secret_key = $config['totp_secret'];

const COOKIE_NAME = 'remember_user';
const ENCRYPTION_METHOD = 'aes-256-cbc';

/*
if (version_compare(phpversion(), '7.3.0', '<')) {
	# Add this check for PHP 7.3
}
*/

session_start([
	'name' => 'twtxt_session',
	'use_strict_mode' => true,
	'cookie_httponly' => true,
	//'cookie_secure' => true,
	'sid_length' => 64,
	'sid_bits_per_character' => 6,
	'save_path' => $config['session_path'],
	'cookie_samesite' => 'Strict', // Not compatible with PHP lower than 7.3
]);

function encrypt(string $data, string $key, string $method): string {
	$ivSize = openssl_cipher_iv_length($method);
	$iv = openssl_random_pseudo_bytes($ivSize);
	$encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
	# PHP 8.2 - Deprecated: implode():
	# Passing null to parameter #1 ($separator) of type array|string is deprecated
	//$encrypted = strtoupper(implode(null, unpack('H*', $encrypted)));
	$encrypted = strtoupper(implode(unpack('H*', $encrypted)));

	return $encrypted;
}

function decrypt(string $data, string $key, string $method): string {
	$data = pack('H*', $data);
	$ivSize = openssl_cipher_iv_length($method);
	$iv = openssl_random_pseudo_bytes($ivSize);
	$decrypted = openssl_decrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);

	return trim($decrypted);
}

function saveLoginSuccess($secretKey) {
	// Set a cookie to remember the user
	$_SESSION['valid_session'] = true;

	// Set a cookie value to remember the user
	$encoded_cookie_value = generateCookieValue('admin', $secretKey);
	$cookie_expiry = time() + (30 * 24 * 60 * 60); // 30 days

	setcookie(COOKIE_NAME, $encoded_cookie_value, [
    'expires' => $cookie_expiry,
    //'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict',
	]);
}

function generateCookieValue($username, $secretKey) {
	$key = bin2hex($secretKey);

	$encrypted = encrypt($username, $key, ENCRYPTION_METHOD);
	return $encrypted;
}

function decodeCookie($secretKey) {
	// Retrieve the encoded cookie name
	if (!isset($_COOKIE[COOKIE_NAME])) {
		return false;
	}

	$encoded_cookie_value = $_COOKIE[COOKIE_NAME];
	$key = bin2hex($secretKey);

	// Extend expiry by 30 days
	$cookie_expiry = time() + (30 * 24 * 60 * 60);
	setcookie(COOKIE_NAME, $encoded_cookie_value, [
    'expires' => $cookie_expiry,
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict',
	]);

	$decrypted = decrypt($encoded_cookie_value, $key, ENCRYPTION_METHOD);
	return $decrypted;
}