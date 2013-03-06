<?php
class CController {
    public $layout = 'default';
    protected function render($file, $data=array()) {
        if (is_array($data)) {
            foreach($data as $key => $value) {
                Application::$app->tpl->assign($key,$value);
            }
        }
        Application::$app->tpl->assign('title', Application::$app->title .'-'.$this->title);

        if ($this->css) {
             Application::$app->tpl->assign('css',$this->css);
        }
        if ($this->js) {
             Application::$app->tpl->assign('js',$this->js);
        }
        //include file.
        $folder = $this->getViewsFolder();
        $file = "$folder/$file" . '.html';
        ob_start();
        Application::$app->tpl->output($file);
        $content = ob_get_contents();
        ob_clean();
        Application::$app->tpl->assign('content',$content);
        $layout = "layouts/$this->layout" . '.html';
        Application::$app->tpl->output($layout);
    }

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
    protected function file_import($file) {
        $folder = $this->getViewsFolder();
        include Application::$app->basePath."/views/$folder/$file.html";
    }

    protected function getMenu($type,$mtype=0) {
        $subItems = array();
        $menulist = array();

        foreach (Application::$app->sysmenu as $item) {
            if ($type == $item['type'] && !$mtype) {
                 $menulist = $item['items'];
                 break;
            }
            else if ($type == $item['type'] && $mtype) {
                foreach ($item['items'] as $item) {
                    if ($mtype == $item['mtype']) {
                        $menulist = $item['items'];
                        break;
                    }
                }
            }
        }

        foreach ($menulist as $key=>$item) {
            if ($item['action'] == $_REQUEST['action'])$menulist[$key]['flag'] = 1;
        }

        return $menulist;
    }

    protected function registerCssFile($file) {
        $this->css .= '<link rel="stylesheet" type="text/css" href="'.$file.'">';
    }

    protected function registerJsFile($file) {
        $this->js .= '<script src="'.$file.'"></script>';
    }

    protected function isPostRequest() {
        return $_SERVER[REQUEST_METHOD] == 'POST';
    }

    private function getViewsFolder() {
        $className = get_class($this);
        return strtolower(str_replace('Controller', '', $className));
    }

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
