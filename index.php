<?php
#
use app\core\Route;

#
include_once __DIR__ . '/init.php';
(new Route($_REQUEST['request'] ?? ''))->engine();