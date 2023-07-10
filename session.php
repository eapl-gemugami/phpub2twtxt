<?php
$config = parse_ini_file('.config');
$secret_key = $config['totp_secret'];

const COOKIE_NAME = 'remember_user';

session_start([
	'name' => 'twtxt_session',
	'use_strict_mode' => true,
	'cookie_httponly' => true,
	'cookie_secure' => true,
	'sid_length' => 64,
	'sid_bits_per_character' => 6,
	//'cookie_samesite' => 'Strict', // Not compatible with PHP lower than 7.3
]);

function encrypt(string $data, string $key, string $method): string {
	$ivSize = openssl_cipher_iv_length($method);
	$iv = openssl_random_pseudo_bytes($ivSize);
	$encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
	$encrypted = strtoupper(implode(null, unpack('H*', $encrypted)));

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
	$cookie_value = generateCookieValue('admin', $secretKey);
	$cookie_expiry = time() + (30 * 24 * 60 * 60); // 30 days
	setcookie(COOKIE_NAME, $cookie_value, $cookie_expiry);
}

function generateCookieValue($username, $secretKey) {
	$key = bin2hex($secretKey);

	$encrypted = encrypt($username, $key, 'aes-256-ecb');
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
	setcookie(COOKIE_NAME, $encoded_cookie_value, $cookie_expiry);

	$decrypted = decrypt($encoded_cookie_value, $key, 'aes-256-ecb');
	return $decrypted;
}