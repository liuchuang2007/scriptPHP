<?php
/**
 * @description: website default module etc.
 * @Author:liuchuang
 * @Date: 2013-03-07
 */
class SiteController extends CController {
    public function actionIndex() {
		$mem = new MemRes();
		$mem_con = $mem->newMem();
		$mem_con->set('ddddd','dddddddddddd');
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