<?php
declare(strict_types=1);
session_name('twtxt');
session_start([
	'name' => 'twtxt',
	'cookie_secure' => true,
	'cookie_lifetime' => 604800, // 7 days
]);
