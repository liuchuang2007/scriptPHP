<?php
class UrlManager {
    public function getReQuest() {
        $url = $_SERVER['REQUEST_URI'];
		echo $url;
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

    private function checkUrl($url,$pattern) {
		//action check.
		/*$access_actions = array();
		$actionExist = false;
		if (preg_match('/\/.*\/(\(.*\))/',$pattern,$matchaction)) {
			
			if (preg_match_all('/[|(](\w+)/',$pattern,$result)) {
				foreach ($result[1] as $item) {
					array_push($access_actions,$item);
				}
			}
			$actionExist = true;
		}*/

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
     * createUrl('http://www.zizhu.cn',array('module'=>'base','action'=>'index','id'=>123,'name'=>'george'))
     */
    public function createUrl($mainurl,$params) {
        if (empty($params['module']) || empty($params['action'])) return false;
        $mainurl = trim($mainurl,'/');
        if (Application::$app->urlrewrite) {
            $url = $mainurl . '/' . $params['module'] . '/' . $params['action'] . '-';
            unset($params['module']);
            unset($params['action']);
            foreach($params as $key => $value) {
                $url = $url . "$value-";
            }
            return trim($url,'-') . '.html';
        }
        else {
            $url = $mainurl.'?'.'module='.$params['module'].'&action='.$params['action'].'&';
            unset($params['module']);
            unset($params['action']);
            if ($params && is_array($params)) {
                foreach ($params as $key=>$value) {
                    $url = $url . "$key=$value&";
                }
            }
            return trim($url,'&');
        }
    }
}