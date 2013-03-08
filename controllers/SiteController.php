<?php
/**
 * @description: website login, logout,upload,etc.
 * @Author:liuchuang
 * @Date: 2012-07-13
 */
class SiteController extends CController {
    public function actionIndex() {
        $this->render('index');
    }

	public function actionIntro() {
		echo 'ok';
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