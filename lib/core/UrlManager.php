<?php
/**
 * @description: url rewrite.
 * @Author:liuchuang
 * @Date: 2013-03-06
 */
class UrlManager {
    
    /**
     *@description: process the url rewirte rules.
    **/
    public function getReQuest() {
        $url = $_SERVER['REQUEST_URI'];
        $rules = Application::$app->urlrules;
        foreach($rules as $key => $rule) {
            if ($this->checkUrl($rules,$key)) {
                if (!empty($_REQUEST['_a'])) {
                    $rule = str_replace('<_a>', $_REQUEST['_a'], $rule);
                    unset($_REQUEST['_a']);
                }
                $main = explode('/', $rule);
                $_REQUEST['module'] = $main[0];
                $_REQUEST['action'] = $main[1];
                return true;
            }
        }
        return false;
    }

    /**
     *@description: analyze the url rewrite rules.
    **/
    private function checkUrl($url,$pattern) {
        //parameter check.
        $pattern = '/^'.str_replace('/', '\/', $pattern).'/';
        $newPatten = "/<(.*?)>/";
        if (preg_match_all($newPatten,$pattern,$result,PREG_PATTERN_ORDER)) {
            if (is_array($result[1])) {
                foreach($result[1] as $value) {
                    $tmp = explode(':',$value);
                    $pattern = str_replace('<'.$value.'>',"(?P<{$tmp[0]}>{$tmp[1]})", $pattern);
                }
            }
        }

        $url = $_SERVER['REQUEST_URI'];
        if (preg_match($pattern,$url,$result)) {
            foreach ($result as $key=>$item) {
                if (!is_numeric($key))$_REQUEST[$key] = $item;
            }
            return true;
        }
        return false;
    }

    /*
     * createUrl(array('module'=>'base','action'=>'index','id'=>123,'name'=>'george'))
     */
    public function createUrl($params) {
        if (empty($params['module']) || empty($params['action'])) return false;
        
        //if url rewrite is open
        if (Application::$app->urlrewrite) {
            $url =  '/' . $params['module'] . '/' . $params['action'] . '/';
            unset($params['module']);
            unset($params['action']);
            
            //just combine the params for easy.
            foreach($params as $key => $value) {
                $url = $url . "$value/";
            }

            return trim($url,'/') . Application::$app->rewrite_suffix;
        }
        else {
            //if url rewrite is closed
            $url = '/index.php?';
            foreach ($params as $key=>$value) {
                $url = $url . "$key=$value&";
            }

            return trim($url,'&');
        }
    }
}