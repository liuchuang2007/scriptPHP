<?php
define('COOKIE_DOMAIN','.zizhu2.cn');
define('CENTER_TYPE',1);
define('BASE_TYPE',2);
define('REGION_TYPE',3);
define('BRANCH_TYPE',4);
define('STORE_TYPE',5);
define('BUSI_TYPE',6);


//define branch account mtype.
define('OPERATION_ACCOUNT',1);
define('PURCHASE_ACCOUNT',2);
define('FINANCE_ACCOUNT',3);
define('DEPARTMENT_ACCOUNT',4);
define('BRAND_ACCOUNT',5);

//define store account mtype.
define('STORE_SERVICE_ACCOUNT',1);
define('STORE_FINANCE_ACCOUNT',2);
define('STORE_BOSS_ACCOUNT',3);


//定义帐户类型
define('MANAGER_CODE',1);
define('BUSI_CODE',2);
define('SUPPLIER_CODE',3);
define('DEPARTMENT_CODE',4);

define('BASE_URL','http://www.zizhu2.cn/');
define('BASE_PATH',dirname(__FILE__).'/../');
include BASE_PATH.'/include/class.smarttemplate.cls';
return array (
    
    'perPage' => 100,
    'logincodelen' =>10, //18
    'usercodelen' => 11,
    'passwordlen' => 6,
    'buycodelen' => 13,
    'perPage' => 1,
    'itemToShow' => 10,
    'template_dir' => 'views',
    'urlrewrite' => false,
    'title' => '小企业赞助',
    'uploadImgPath' => dirname(__FILE__).'/../upload/images/',
    'imgBaseUrl' => BASE_URL.'upload/images/',
    'urlrules'=> include dirname(__FILE__).'/urlrules.php',
    'sysmenu'=> require dirname(__FILE__) . '/menuconfig.php',
    'capitallevel'=>array(
       array('level'=>1,'text'=>'30万元以下'),
       array('level'=>2,'text'=>'100万元以下'),
       array('level'=>3,'text'=>'1000万元以下'),
       array('level'=>3,'text'=>'1000万元以上'),
    ),
    'company_type'=>array(
       array('level'=>1,'text'=>'事业单位'),
       array('level'=>2,'text'=>'生产企业'),
       array('level'=>3,'text'=>'贸易批发'),
       array('level'=>4,'text'=>'商业零售'),
       array('level'=>5,'text'=>'商业服务'),
    ),
);