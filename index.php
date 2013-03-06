<?php
session_start();
error_reporting(E_ALL);
include_once 'Application.php';
$config = include_once dirname(__FILE__) . '/config/main.php';

$app = new Application($config);

$app->run();