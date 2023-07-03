<?php
require_once('base.php');
session_destroy();
header('Refresh: 0; URL = index.php');