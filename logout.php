<?php
session_start();
session_destroy();
header('Refresh: 0; URL = index.php');