<?php
require_once('session.php');
session_unset();
session_destroy();
session_write_close();
header('Location: .');
exit();
