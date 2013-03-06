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

    /**
     * @Describe :  路径出错处理
    */
    public function actionError() {
        $action = $_REQUEST['action'];
        $module = $_REQUEST['module'];
        if ($_SERVER['REQUEST_METHOD']== 'POST') {
            die('-10');//路径错误
        }
        else $this->renderPartial('error',array('url'=>$_SERVER[REQUEST_URI]));
        exit();
    }

    /**
     * @Describe : 跳转到returnback页面
     */
    public function actionGoback() {
        if (!empty($_SESSION['returnUrl'])) {
            header("location: {$_SESSION['returnUrl']}");
        }
    }

    /**
     * @Describe : 记录returnback页面
     */
    public function actionSaveUrl() {
        $url = empty($_REQUEST['url']) ? '' : trim($_REQUEST['url']);
        if (!empty($url)) {
            $_SESSION['returnUrl'] = $url;
        }
    }
}