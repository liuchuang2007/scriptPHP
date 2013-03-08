<?php
define('BASE_PATH',dirname(__FILE__).'/../');
include BASE_PATH.'../lib/templates/class.smarttemplate.cls';
return array (
    'template_dir' => 'views',
	'temp_dir' => 'temp/',
    'urlrewrite' => false,
	'rewrite_suffix' => '.html',
    'name' => 'scriptPHP Framework',
    'uploadImgPath' => dirname(__FILE__).'/../upload/images/',
    'urlrules' => include dirname(__FILE__).'/urlrules.php',
	
	//memcache config
	'memcache' => array('host'=>'127.0.0.1', 'port'=>11211)
);