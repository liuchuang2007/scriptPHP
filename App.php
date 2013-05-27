<?php
/**
 *@description: application entrance 
 *@author: liuchuang
 *@Date:2013-03-03
 */
class App {
    public static $app;
    public function __construct($config) {
        App::$app = $this;
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }

        $this->urlManager = new UrlManager();
		
		//load shopconfig
		/*$shop = new Shop();
		$conf = $shop->getShopConfig();
		foreach ($conf as $item) {
			$this->{$item['save_key']} = $item['save_value'];
		}*/
    }

    public function run() {
        if ($this->urlrewrite) {
            //if rewrite rule is not exist and path is not web default path, redirect to error page.
            if (!$this->urlManager->getReQuest()) {
                if ($_SERVER['REQUEST_URI'] != '/') {
                    $site = new SiteController();
                    $site->actionError();
                    exit;
                }
                else {
                    $_REQUEST['module'] = 'site';
                    $_REQUEST['action'] = 'index';
                }
            }
        }
        else if (empty($_REQUEST['module']) && empty($_REQUEST['module'])) {
            $_REQUEST['module'] = 'site';
            $_REQUEST['action'] = 'index';
        }

        //If rewrite is open.
        $module = empty($_REQUEST['module']) ? '' : trim($_REQUEST['module']);
        $action = empty($_REQUEST['action']) ? '' : trim($_REQUEST['action']);

        //if module or action is not exist, then redirect to error page.
        if (empty($module) || empty($action)) {
            $site = new SiteController();
            $site->actionError();
            exit;
        }

        //pick related controllers.
        $class_name = ucfirst($module) . 'Controller';
        if (class_exists($class_name)) {
            $class = new $class_name();
            
            //pick related method.
            $method = 'action' . ucfirst($action);
            if (method_exists($class, $method)) {
                $class->$method();
            }
            else {
                //if method not find, redirect to error
                $site = new SiteController();
                $site->actionError();
            }
        }
        else {
            //if class not find, redirect to error 
            $site = new SiteController();
            $site->actionError();
        }
    }

    public static function autoload($class) {
        //common classes. the class name is the same with class file name
        $core_dirs = glob(dirname(__FILE__).'/lib/*',GLOB_ONLYDIR);
        array($core_dirs, BASE_PATH.'/models');
        foreach($core_dirs as $dir) {
            $file = $dir . '/' . $class . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
        
        //include controller file
        $cfile = BASE_PATH.'controllers/'.$class.'.php';
        if (file_exists($cfile)) {
            require_once $cfile;
        }
        
        //include controller file
        $cfile = BASE_PATH.'models/'.$class.'.php';
        if (file_exists($cfile)) {
            require_once $cfile;
        }
    }

    public static function addLog($type= 'SYS', $msg='') {
        $fp = fopen(App::$app->import_log,'a+');
        $str = '[' . date('Y-m-d H:i:s') . '][' . $type . ']' . $msg . "<br />\n";
        fputs($fp, $str);
        fclose($fp);
        return $str;
    }
}
spl_autoload_register(array('App','autoload'));