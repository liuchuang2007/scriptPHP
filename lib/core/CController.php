<?php
/**
 * @description: all modules is herited.
 * @Author:liuchuang
 * @Date: 2013-03-07
 */
class CController {
    public $layout = 'default';
    
    /**
     *@description: render the content with layout.
     */
    protected function render($file, $data=array()) {
        if (is_array($data)) {
            foreach($data as $key => $value) {
                Application::$app->tpl->assign($key,$value);
            }
        }
        
        //website name
        Application::$app->tpl->assign('name', Application::$app->name);
        
        //render css
        if (!empty($this->css)) {
            Application::$app->tpl->assign('css',$this->css);
        }
        
        //render js
        if (!empty($this->js)) {
            Application::$app->tpl->assign('js',$this->js);
        }

        //include file.
        $folder = $this->getViewsFolder();
        $file = "$folder/$file" . '.html';
        ob_start();
        Application::$app->tpl->output($file);
        $content = ob_get_contents();
        ob_clean();

        //render the content in layout
        Application::$app->tpl->assign('content',$content);
        $layout = "layouts/$this->layout" . '.html';
        Application::$app->tpl->output($layout);
    }

    /**
     *@description: only render the content.
     */
    protected function renderPartial($file, $data=array()) {
        if (is_array($data)) {
            foreach($data as $key => $value) {
                Application::$app->tpl->assign($key,$value);
            }
        }

        $folder = $this->getViewsFolder();
        $file = "$folder/$file" . '.html';
        Application::$app->tpl->output($file);
    }

    /**
     *@description: add css file
     */
    protected function registerCssFile($file) {
        $this->css .= '<link rel="stylesheet" type="text/css" href="'.$file.'">';
    }

    /**
     *@description: add js file
     */
    protected function registerJsFile($file) {
        $this->js .= '<script src="'.$file.'"></script>';
    }

    /**
     *@description: if user request type is POST
     */
    protected function isPostRequest() {
        return $_SERVER[REQUEST_METHOD] == 'POST';
    }

    /**
     *@description: get the current module's view fold
     */
    private function getViewsFolder() {
        $className = get_class($this);
        return strtolower(str_replace('Controller', '', $className));
    }

    /**
     *@description: get remote client ip
     */
    protected function getClientIp() {
        if (isset($_SERVER)) {
            if (isset($_SERVER[HTTP_X_FORWARDED_FOR])) {
                $realip = $_SERVER[HTTP_X_FORWARDED_FOR];
            } elseif (isset($_SERVER[HTTP_CLIENT_IP])) {
                $realip = $_SERVER[HTTP_CLIENT_IP];
            } else {
                $realip = $_SERVER[REMOTE_ADDR];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realip = getenv( "HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }
}
