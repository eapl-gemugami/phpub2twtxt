<?php
// Clear the session and cookie
require_once('session.php');
session_unset();
session_destroy();

// Clear the remember me cookie
const COOKIE_NAME = 'remember_user';
if (isset($_COOKIE[COOKIE_NAME])) {
	unset($_COOKIE[COOKIE_NAME]);
	setcookie(COOKIE_NAME, '', time() - 3600);
}

header("Location: .");
exit;
