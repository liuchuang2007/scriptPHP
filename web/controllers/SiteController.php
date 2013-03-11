<?php
/**
 * @description: website default module etc.
 * @Author:liuchuang
 * @Date: 2013-03-07
 */
class SiteController extends CController {
    public function actionIndex() {
		//use memcache
		//$mem = new MemRes();
		//$mem_con = $mem->newMem();
		//$mem_con->set('ddddd','----------dddddddddddd');
		//echo $mem_con->get('ddddd');
		
		//use mysql
		//$mysql = new MysqlRes();
		//$users = $mysql->queryBySql('SELECT * FROM ecs_users','all');
		//var_dump($users);
		
		//use mongo
		//$connection = new MongoRes();
		//$res = $connection->mongoUpdate('test',array('sex'=>'male','age'=>14,'grade'=>'12','school'=>'hanbin'),array('sex'=>'female'));
		//$res = $connection->mongoQuery('test',array('sex'=>'male'));
		//var_dump($res);
        $this->render('index');
    }

	public function actionIntro() {
		$this->render('about');
	}

    /**
     * @Describe :  route error process
    */
    public function actionError() {
        $action = $_REQUEST['action'];
        $module = $_REQUEST['module'];
        if ($_SERVER['REQUEST_METHOD']== 'POST') {
            die('-10');//path error
        }
        else $this->renderPartial('error',array('url'=>$_SERVER[REQUEST_URI]));
        exit();
    }
}