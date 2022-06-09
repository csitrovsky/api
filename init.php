<?php

#
use app\Autoload;

#
const INC_ROOT = __DIR__;

#
error_reporting(E_ALL & ~E_NOTICE);

#
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

#
session_cache_limiter(false);
session_start();

#
if (file_exists(($autoload = INC_ROOT . '/vendor/autoload.php'))) {
    include_once $autoload;
}

#
include_once INC_ROOT . '/app/Autoload.php';
return (new Autoload())->load();