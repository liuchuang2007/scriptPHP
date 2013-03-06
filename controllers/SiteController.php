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

    /**
     * @describe: all manger account login access.
     */
    public function actionLogin() {
        $user = new User();
        if ($user->isLogin())User::jumpToIndex($_SESSION['type'],$_SESSION['mtype']);
        if (empty($_POST['username']) || empty($_POST['authcode'])) {
            $this->render('login');
        }
        else {
            if (strtolower($_POST['authcode']) != $_SESSION['authcode']) die('-4');
            $username = trim($_POST['username']);
            echo $user->login(1,$username,$password);
        }
    }

    public function actionLogout() {
        setcookie('usercode','',time(),'/',COOKIE_DOMAIN);
    }

    /**
     * @describe： lock specified account by usercode.
     * @param: $mcode. manager unique identity.
     */
    public function actionLockAccount() {
        $mcode = empty($_REQUEST['mcode']) ? '' : trim($_REQUEST['mcode']);
        $status = empty($_REQUEST['status']) ? '' : trim($_REQUEST['status']);
        $type = empty($_REQUEST['type']) ? '' : trim($_REQUEST['type']);
        $user = new User();
        echo $user->lockAccount($mcode,$status,$type);
    }

    /**
     * @describe: generate a new logincode of the manager.
     */
    public function actionNewLogincode() {
        $mcode = empty($_REQUEST['mcode']) ? '' : trim($_REQUEST['mcode']);
        $user = new User();
        echo $user->changeLogincode($mcode);
    }

    /**
     * @describe: process the picture upload of this whole website.
     */
    
    public function actionUpload() {
        $do = empty($_REQUEST['do']) ? '' : trim($_REQUEST['do']);
        if ($do) {//hold the param.
            $param = '?do='.$do;
        }
        if (empty($_FILES)) {
            $this->renderPartial('upload',array('param'=>$param));
        }
        else {
            $busi = new BusiModel();
            $name = $_FILES['uploadFile']['name'];
            $filename = pathinfo($name,PATHINFO_FILENAME);
            $ext = pathinfo($name,PATHINFO_EXTENSION);
            $name = md5(time().$name).'.'.$ext;
            $showname = substr($name,-15);
            $url = str_replace(trim(BASE_URL,'/'), '', Application::$app->imgBaseUrl . $name);

            move_uploaded_file($_FILES['uploadFile']['tmp_name'], Application::$app->uploadImgPath.$name);
            if ($do == 'license') {
                $js = "<script> var obj = parent.document.getElementById('liName');obj.innerHTML=\"$showname<a href='$url' id='licensePic'  target='_blank'>查看</a>\";</script>";
            }
            else if ($do == 'org') {
                $js = "<script> var obj = parent.document.getElementById('orgName');obj.innerHTML=\"$showname<a href='$url' id='orgPic' target='_blank'>查看</a>\";</script>";
            }
            else if ($do == 'updateli') {
                $data['licensepic'] = $url;
                $busi->saveProfileChange($data);
                $js = '<script>parent.window.location.href="/busi/profile.html";</script>';
            }
            else if ($do == 'updateorg') {
                $data['orgpic'] = $url;
                $busi->saveProfileChange($data);
                $js = '<script>parent.window.location.href="/busi/profile.html";</script>';
            }
            else if ($do == 'activityPic') {//activity setting of center operation.
                $js = "<script>var obj = parent.document.getElementById('pic');var show = parent.document.getElementById('showPic');obj.href='$url';show.innerHTML='$showname';</script>";
            }
            $this->renderPartial('upload',array('param'=>$param));
            echo $js;
        }
    }
}