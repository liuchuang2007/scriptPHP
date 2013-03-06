<?php
class User extends AgentDB {
    public static function Model() {
         return new Supplier();
    }

    public function getRandUsercode($type=1,$len=11) {
        $str = '0123456789';
        if ($type == 1) $prefix = 'B'; //business
        else if ($type == 2)$prefix = 'M';//manager
        else return false;
        for($i=0;$i<$len-1;$i++){
            $code = str_shuffle($str);
            $y = rand(0,9);
            $x .= $code[$y];
        }
        return $prefix . $x;
    }

    public static function checkLogin($currtype,$currmtype=0,$jump=true) {
        //If user not login,jump to the proper entrance.
        if (empty($_COOKIE['usercode']) || empty($_SESSION['usercode'])||$_COOKIE['usercode'] != $_SESSION['usercode']) {
            if ($jump) {
              if ($_SERVER[REQUEST_METHOD] == 'POST')die('-11');
              User::jumpToLogin($currtype);
            }
            return false;
        }
        //If authority limited,jump back to own first page.
        if ($currtype != $_SESSION['type'] || $currmtype != $_SESSION['mtype']) {
            //share method exception in branch
            if ($currmtype == 0 && $currtype == BRANCH_TYPE)return true;
           // $site = new SiteController();
           // $site->actionError();
            User::jumpToIndex($_SESSION['type'], $_SESSION['mtype']);
        }

        return true;
    }

    public function isLogin() {
        if (empty($_COOKIE['usercode']) || empty($_SESSION['usercode'])||$_COOKIE['usercode'] != $_SESSION['usercode']) {
            return false;
        }
        return true;
    }
    public static function jumpToLogin($type) {
        switch($type) {
        case CENTER_TYPE:
        case BASE_TYPE:
        case REGION_TYPE:
        case BRANCH_TYPE:
        case STORE_TYPE:
            echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'site','action'=>'login')).'";</script>';
        break;
        case BUSI_TYPE:
            echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'busi','action'=>'login')).'";</script>';
        break;
        }
    }
    public static function jumpToIndex($type,$mtype=0) {
        switch($type) {
        case CENTER_TYPE:
            echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'center','action'=>'index')).'";</script>';
        break;
        case BASE_TYPE:
            echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'base','action'=>'index')).'";</script>';
        break;
        case REGION_TYPE:
            echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'region','action'=>'index')).'";</script>';
        break;
        case BRANCH_TYPE:
            if ($mtype == OPERATION_ACCOUNT) {
                echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'branch','action'=>'index')).'";</script>';
            }
            else if ($mtype == PURCHASE_ACCOUNT) {
                echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'branch','action'=>'operation')).'";</script>';
            }
            else if ($mtype == FINANCE_ACCOUNT) {
                echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'branch','action'=>'index')).'";</script>';
            }
            else if ($mtype == CATEGORY_ACCOUNT) {
                echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'branch','action'=>'index')).'";</script>';
            }
            else if ($mtype == BRAND_ACCOUNT) {
                echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'branch','action'=>'index')).'";</script>';
            }
        break;
        case STORE_TYPE:
            if ($mtype == STORE_SERVICE_ACCOUNT) {
                echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'store','action'=>'index')).'";</script>';
            }
            else if ($mtype == STORE_BOSS_ACCOUNT) {
                echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'store','action'=>'stat')).'";</script>';
            }
            else if ($mtype == STORE_FINANCE_ACCOUNT) {
                 echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'store','action'=>'confirm')).'";</script>';
            }
        break;
        case BUSI_TYPE:
            echo '<script>window.location.href="'.Application::$app->urlManager->createUrl(Application::$app->baseUrl,array('module'=>'busi','action'=>'index')).'";</script>';
        break;
        }
    }

    public function login($type,$username,$password) {
        $username = $this->mysql_escape_string($username);
        $password = $this->mysql_escape_string($password);
        if ($type == 1) {//管理账户
            $sql = "select mcode, name, type, mtype, a.status as astatus,b.status as bstatus from manager a, department b where a.dcode = b.dcode and  logincode='$username'";
            $result = $this->query($sql, 'DEFAULT' ,'assoc');
            if (!$result) {
               return '-1';
            }
            if($result['astatus'] == 2 || $result['bstatus'] == 2) return '-3';
            $this->setUserLogin($result['mcode'],$result['name'],$result['type'],$result['mtype']);
        }
        else if ($type == 2){//会员
            $sql = "select loginname,status from busiuser where loginname='$username'";
            $result = $this->query($sql, 'DEFAULT' ,'assoc');
            if (!$result) {
               return '-1';
            }
            if (intval($result['status']) != 2) {
               return '-3';
            }

            $sql = "select usercode,companyname,status from busiuser where loginname='$username' and password='$password'";
            $result = $this->query($sql, 'DEFAULT' ,'assoc');
            if (!$result) {
               return '-2';
            }

            $this->setUserLogin($result['usercode'],$result['companyname'],BUSI_TYPE);
        }

        return '1';
    }

    private function setUserLogin($usercode,$name,$type,$mtype=0) {
        $_SESSION['usercode'] = $usercode;
        $_SESSION['name'] = $name;
        $_SESSION['type'] = $type;
        $_SESSION['mtype'] = $mtype;
        setcookie('usercode',$usercode,0,'/',COOKIE_DOMAIN);
    }

    public function lockAccount($mcode,$status,$type) {
        if (1 != $status && 2 != $status) return '-1';
        $mcode = $this->mysql_escape_string($mcode);
        if ($type == 'manager') {
            $sql = "select type from manager where mcode = '$mcode'";
        }
        else if ($type == 'store') {//store
            $sql = "select storecode from store where storecode = '$mcode'";
        }
        $exist = $this->query($sql,'DEFAULT','all');
        if ($exist) {
            $newstatus = ($status == 1) ? 2 : 1;
            if ($type == 'manager') {
                $sql = "update manager set status = $newstatus where mcode = '$mcode'";
            }
            else if ($type == 'store') {//store
                $sql = "update store set status = $newstatus where storecode = '$mcode'";
            }
            $this->query($sql,'DEFAULT','assoc');
            return '1';
        }
        else return '-1';
    }

    public function changeLogincode($mcode) {
        $mcode = $this->mysql_escape_string($mcode);
        $exist = $this->query("select type from manager where mcode = '$mcode'",'DEFAULT','all');
        if (!$exist) {
            return '-1';
        }
        $code = $this->newLoginCode(MANAGER_CODE,Application::$app->logincodelen);
        $this->query("update manager set logincode = '$code' where mcode = '$mcode'",'DEFAULT','assoc');
        return $code;
    }

    public function newLoginCode($type=1,$len = 15) {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $x = '';
        for($i=0;$i<$len;$i++){
            $code = str_shuffle($str);
            $y = rand(0,51);
            $x .= $code[$y];
        }
		if ($type == MANAGER_CODE) {
            $sql = "select mcode from manager where logincode='$x'";
        }
        else if ($type == BUSI_CODE) {
            $sql = "select usercode from busiuser where loginname='$x'";
        }
        $exist = $this->query($sql,'DEFAULT','all');
        if ($exist) $x = $this->newLoginCode($type,$len);
        return $x;
    }

    public function newUsercode($type=1,$len=11) {
        $str = '0123456987';
        $x = '';
        for($i=0;$i<$len;$i++){
            $code = str_shuffle($str);
            $y = rand(0,9);
            $x .= $code[$y];
        }
        
        switch($type) {
            case MANAGER_CODE:
                $sql = "select mcode from manager where mcode='$x'";
            break;
            case BUSI_CODE:
                $sql = "select usercode from busiuser where usercode='$x'";
            break;
            case SUPPLIER_CODE:
                $sql = "select scode from supplier where scode='$x'";
            break;
            case DEPARTMENT_CODE:
                $sql = "select dcode from department where dcode='$x'";
            break;
        }

        $exist = $this->query($sql,'DEFAULT','all');
        if ($exist) $x = $this->newUsercode($type,$len);
        return $x;
    }

    public function newPassword($len=6) {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $x = '';
        for($i=0;$i<$len;$i++){
            $code = str_shuffle($str);
            $y = rand(0,51);
            $x .= $code[$y];
        }
        return $x;
    }

    public static function getFileName($file,$len) {
        $strlen = strlen($file);
        if ($strlen <= $len)return $file;
        $start = $strlen - $len;
        return substr($file,$start);
    }


}