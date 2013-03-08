<?php
session_start();
error_reporting(E_ALL);
include_once 'Application.php';
$config = include_once dirname(__FILE__) . '/web/config/main.php';
//$core_dirs = glob(dirname(__FILE__).'/*',GLOB_ONLYDIR);var_dump($core_dirs);die;
$app = new Application($config);
$app->run();