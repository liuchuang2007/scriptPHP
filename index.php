<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once 'App.php';
$config = include_once dirname(__FILE__) . '/web/config/main.php';

$app = new App($config);
$app->run();