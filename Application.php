<?php
class Application {
    public static $app;
    public function __construct($config) {
        Application::$app = $this;
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
        $this->tpl = new SmartTemplate();
        $this->tpl->template_dir = $this->template_dir;
        $this->urlManager = new UrlManager();
    }

    public function run() {
        //If rewrite is open.
		$module = empty($_REQUEST['module']) ? '' : trim($_REQUEST['module']);
        $action = empty($_REQUEST['action']) ? '' : trim($_REQUEST['action']);
        if ($this->urlrewrite) {
            if (!$this->urlManager->getReQuest() && $_SERVER[REQUEST_URI] != '/') {
				$site = new SiteController();
                $site->actionError();
            }
        }

		//default action.
        if (empty($module) && empty($action)) {
            $module = 'site';
			$action = 'index';
            $_REQUEST['action'] = $action;
        }
		else if (empty($module)) {
		    $module = 'site';
		}
                		
        $class_name = ucfirst($module) . 'Controller';
        if (class_exists($class_name)) {
            $class = new $class_name();
            $method = 'action' . ucfirst($action);
            if (method_exists($class, $method)) {
                $class->$method();
            }
            else {//if method not find, redirect to error
                $site = new SiteController();
                $site->actionError();
            }
        }
        else { //if class not find, redirect to error 
				echo '----'.$class_name;die();
            $site = new SiteController();
            $site->actionError();
        }
    }

    public static function autoload($class) {
        //require_once dirname(__FILE__) . '/lib/class.agent_db.php';
        $base = dirname(__FILE__);
        $include_file = $base.'/include/'.$class.'.php';
        $controller_file = $base.'/controllers/'.$class.'.php';
        $lib_file = $base.'/lib/'.$class.'.php';
        $model_file = $base.'/models/'.$class.'.php';
        if (file_exists($include_file)) require_once $include_file;
        else if (file_exists($controller_file)) require_once $controller_file;
        else if (file_exists($lib_file)) require_once $lib_file;
        else if (file_exists($model_file)) {
            require_once $model_file;
        }
    }

    public static function addLog($type= 'SYS', $msg='') {
        $fp = fopen(Application::$app->import_log,'a+');
        $str = '[' . date('Y-m-d H:i:s') . '][' . $type . ']' . $msg . "<br />\n";
        fputs($fp, $str);
        fclose($fp);
        return $str;
    }
}
spl_autoload_register(array('Application','autoload'));