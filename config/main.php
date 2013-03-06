<?php
define('BASE_PATH',dirname(__FILE__).'/../');
include BASE_PATH.'/include/class.smarttemplate.cls';
return array (
    'template_dir' => 'views',
	'temp_dir' => 'temp/',
    'urlrewrite' => false,
    'title' => 'scriptPHP Framework',
    'uploadImgPath' => dirname(__FILE__).'/../upload/images/',
    'urlrules'=> include dirname(__FILE__).'/urlrules.php',
    'sysmenu'=> require dirname(__FILE__) . '/menuconfig.php',
);