<?php
/*
 * @Description: Manager operation---Service record query,account setting,user review, etc.
 * @Author:liuchuang
 * @Date: 2012-07-12
 */
class Manager extends AgentDB {

    public static function Model() {
        return new Manager();
    }

    /*
     * @description:  get base type user list.
     */
    public function getSubAccountList($dcode,$type) {
        if ($type == BASE_TYPE ) {
            $data = $this->query("select mcode,name,logincode,mtype,b.status from manager a,department b where a.dcode = b.dcode and type = 2",'DEFAULT','all');
            foreach ($data as $key=>$row) {
                $data[$key]['status'] = (1 == $row['status']) ? true : false;
            }
        }
        else if ($type == REGION_TYPE) {
            $data = $this->query("select mcode,name,logincode,mtype,b.status from manager a,department b where a.dcode = b.dcode and pbase = '$dcode' and type = 3",'DEFAULT','all');
            foreach ($data as $key=>$row) {
                $data[$key]['status'] = (1 == $row['status']) ? true : false;
            }
        }
        else if ($type == BRANCH_TYPE) {
            $data = $this->query("select a.dcode,a.name from department a, manager b where a.dcode = b.dcode and pregion = '$dcode' and type = 4 group by a.dcode",'DEFAULT','all');
            foreach ($data as $key=>$row) {
                $condition['dcode'] = $row['dcode'];
                $condition['type'] = BRANCH_TYPE;
                $condition['mtype'] = OPERATION_ACCOUNT;
                $info = $this->getUserInfo($condition);
                $data[$key]['oplogincode'] = $info['logincode'];
                $data[$key]['opmcode'] = $info['mcode'];
                
                $condition['mtype'] = PURCHASE_ACCOUNT;
                $info = $this->getUserInfo($condition);
                $data[$key]['plogincode'] = $info['logincode'];
                $data[$key]['pmcode'] = $info['mcode'];
                
                $condition['mtype'] = FINANCE_ACCOUNT;
                $info = $this->getUserInfo($condition);
                $data[$key]['flogincode'] = $info['logincode'];
                $data[$key]['fmcode'] = $info['mcode'];
            }
        }
        else if ($type == STORE_TYPE) {
            $data = $this->query("select a.dcode,cityname,name from department a, departmentcity b where a.dcode = b.dcode and a.pbranch = '$dcode' group by a.dcode",'DEFAULT','all');

            foreach ($data as $key=>$row) {
                $condition['dcode'] = $row['dcode'];
                $condition['type'] = STORE_TYPE;
                $condition['mtype'] = STORE_SERVICE_ACCOUNT;
                $info = $this->getUserInfo($condition);
                $data[$key]['slogincode'] = $info['logincode'];
                $data[$key]['codes'] = $info['mcode'];
                
                $condition['mtype'] = STORE_FINANCE_ACCOUNT;
                $info = $this->getUserInfo($condition);
                $data[$key]['flogincode'] = $info['logincode'];
                $data[$key]['codef'] = $info['mcode'];
               
                $condition['mtype'] = STORE_BOSS_ACCOUNT;
                $info = $this->getUserInfo($condition);
                $data[$key]['blogincode'] = $info['logincode'];
                $data[$key]['codeb'] = $info['mcode'];
            }
        }

        return $data;
    }

    /*
     * @description:  new base user.
     * $param: $name username.
     */
    public function newAccount($name,$city,$type,$usercode) {
        $user = new User();
        $usercode = $this->mysql_escape_string($usercode);
        $department = $this->getUserDepartmentInfo($usercode);
        if (!$department)die('-3');

        //create department
        $dInfo['dcode'] = $user->newUsercode(DEPARTMENT_CODE,Application::$app->usercodelen);
        $dInfo['name'] = $this->mysql_escape_string($name);
        $dInfo['status'] = 1;
        $dInfo['citycode'] = '';
        $dInfo['ctime'] = time(true);
        

        $newuser['ctime'] = time(true);
        $newuser['status'] = 1;
        $newuser['logincode'] = $user->newLoginCode(MANAGER_CODE,Application::$app->logincodelen);
        $newuser['dcode'] = $dInfo['dcode'];
        $newuser['mcode'] = $user->newUsercode(MANAGER_CODE,Application::$app->usercodelen);
        if ($type == BASE_TYPE || $type == REGION_TYPE) {//base or region account.
            //create department
            if ($type == REGION_TYPE) {
                $dInfo['pbase'] = $department['dcode'];
            }
            $this->insert('department', $dInfo);

            $newuser['type'] = ($type == BASE_TYPE) ? BASE_TYPE : REGION_TYPE;
            $this->insert('manager', $newuser);
        }
        else if ($type == BRANCH_TYPE) {//branch account.
            //create department
            $dInfo['pbase'] = $department['pbase'];
            $dInfo['pregion'] = $department['dcode'];
            $this->insert('department', $dInfo);

            // new first operation account.
            $newuser['type'] = BRANCH_TYPE;
            $newuser['mtype'] = OPERATION_ACCOUNT;
            $this->insert('manager', $newuser);

            //new purchase account
            $newuser['mtype'] = PURCHASE_ACCOUNT;
            $newuser['logincode'] = $user->newLoginCode(MANAGER_CODE,Application::$app->logincodelen);
            $newuser['mcode'] = $user->newUsercode(MANAGER_CODE,Application::$app->usercodelen);
            $this->insert('manager', $newuser);

            //new finance account
            $newuser['mtype'] = FINANCE_ACCOUNT;
            $newuser['logincode'] = $user->newLoginCode(MANAGER_CODE,Application::$app->logincodelen);
            $newuser['mcode'] = $user->newUsercode(MANAGER_CODE,Application::$app->usercodelen);
            $this->insert('manager', $newuser);


        }
        else if ($type == STORE_TYPE) {//store account
            //create department
            $dInfo['pbranch'] = $department['dcode'];
            $dInfo['pregion'] = $department['pregion'];
            $dInfo['pbase'] = $department['pbase'];
            $this->insert('department', $dInfo);

            // new boss account.
            $newuser['type'] = STORE_TYPE;
            $newuser['mtype'] = STORE_BOSS_ACCOUNT;
            $this->insert('manager', $newuser);

            //new finance account
            $newuser['mtype'] = STORE_FINANCE_ACCOUNT;
            $newuser['logincode'] = $user->newLoginCode(MANAGER_CODE,Application::$app->logincodelen);
            $newuser['mcode'] = $user->newUsercode(MANAGER_CODE,Application::$app->usercodelen);
            $this->insert('manager', $newuser);
            
            //new service account
            $newuser['mtype'] = STORE_SERVICE_ACCOUNT;
            $newuser['logincode'] = $user->newLoginCode(MANAGER_CODE,Application::$app->logincodelen);
            $newuser['mcode'] = $user->newUsercode(MANAGER_CODE,Application::$app->usercodelen);
            $this->insert('manager', $newuser);


        }

        // add city
        $cityinfo['dcode'] = $dInfo['dcode'];
        foreach ($city as $item) {
            $cityinfo['citycode'] = $item;
            $info = City::Model()->getCityInfo($item);
            if (!$info)die('-2');
            $cityinfo['pcode'] = $info['pcode'];
            $cityinfo['cityname'] = $info['name'];
            $this->insert('departmentcity', $cityinfo);
        }

        return true;
    }

    public function getUserInfo($condition) {
        if (!is_array($condition))return '-1';
        foreach($condition as $key=>$value) {
            $sql .=' and ' .$key .'='."'$value'";
        }
        
        return $this->query("select * from manager where 1=1 $sql",'DEFAULT','assoc');
    }

    public function getUserDepartmentInfo($code,$type='usercode') {
        if ($type == 'usercode') {
            return $this->query("select b.* from manager a,department b where a.dcode = b.dcode and mcode = '$code'",'DEFAULT','assoc');
        }
        else if ($type == 'dcode') {
            return $this->query("select * from department where dcode = '$code'",'DEFAULT','assoc');
        }
    }

    public function getAllOpenedCity() {
        return $this->query("select * from departmentcity group by citycode",'DEFAULT','all');
    }
}