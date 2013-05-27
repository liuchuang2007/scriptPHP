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
        extract($data);

        //render css
        if (!empty($this->css)) {
            $css = $this->css;
        }
        
        //render js
        if (!empty($this->js)) {
            $js = $this->js;
        }
        
        //include file.
        $folder = $this->getViewsFolder();
        $file = "$folder/$file" . '.html';
        ob_start();
        require $file;
        $content = ob_get_contents();
        ob_end_clean();

        //render the content in layout
        require $folder."/../layouts/$this->layout" . '.html';
    }

    /**
     *@description: only render the content.
     */
    protected function renderPartial($file, $data=array()) {
        extract($data);

        $folder = $this->getViewsFolder();
        require "$folder/$file" . '.html';
    }
    
    /**
     *@description: only render the content.
     */
    protected function output($file, $data=array()) {
        extract($data);

        $folder = $this->getViewsFolder();
        ob_start();
        require "$folder/$file" . '.html';;
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
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
        return BASE_PATH . 'views/'. strtolower(str_replace('Controller', '', $className));die;
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
