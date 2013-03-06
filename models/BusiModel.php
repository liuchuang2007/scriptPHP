<?php
class BusiModel extends AgentDB {
    public static function Model() {
        return new BusiModel();
    }
    public function getBuycode($usercode) {
        //get apply record.
        $today = strtotime(date('Y-m-d'));
        $usercode = $this->mysql_escape_string($usercode);
        $record = $this->query("select buycode,ctime,endtime from applyrecord where usercode = '$usercode' order by ctime desc",'DEFAULT','all');
        foreach($record as $key=>$row) {
            $record[$key]['ctime'] = date('Y/m/d',$row['ctime']);
            if ($row['status'] == 1 && $today > $row['endtime']) {
                $record[$key]['status'] = '已过期';
            }
            else if ($row['status'] == 1 && $today <= $row['endtime']) {
                $record[$key]['status'] = '未使用';
            }
            else if ($row['status'] == 2){
                $record[$key]['status'] = '已使用';
            }
        }

        $latest = $this->query("select buycode,ctime,endtime from applyrecord where usercode = '$usercode' order by ctime desc limit 1",'DEFAULT','assoc');
        if (!$latest || $today > $latest['endtime']) {//have not ever applied yet.
             $leftdays = 0;
        }
        else {
            $oneday = 3600 * 24;
            $leftdays = floor(($latest['endtime'] - $today) / $oneday);
        }
        return array('res'=>$record,'leftdays'=>$leftdays);
    }

    public function applyBuycode($usercode,$ip) {
        $today = strtotime(date('Y-m-d'));
        $usercode = $this->mysql_escape_string($usercode);
        $record = $this->query("select buycode,ctime,endtime from applyrecord where usercode = '$usercode' order by ctime desc limit 1",'DEFAULT','assoc');
        if (intval($today) <= $record['endtime']) die('-1');

        $starttime = strtotime(date('Y-m-d'));
        $setting = $this->query("select distance from busiuser a, applysetting b where a.capitallevel = b.id and usercode = '$usercode'",'DEFAULT','assoc');
        $endtime = $starttime + $setting['distance'] * 24 * 3600 - 1;
        $buycode = $this->newBuycode(Application::$app->buycodelen);

        $this->query("insert into applyrecord(buycode,usercode,starttime,endtime,status,ip,ctime,usetime) values('$buycode','$usercode','$starttime','$endtime',1,'$ip',unix_timestamp(),0)",'DEFAULT','assoc');
        exit('1');
    }

    public function newBuycode($len = 13){
        $activecode = array();
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $x = '';
        for($i=0;$i<$len;$i++){
            $code = str_shuffle($str);
            $y = rand(0,35);
            $x .= $code[$y];
        }
        $exist = $this->query("select buycode from applyrecord where buycode='$x'",'DEFAULT','assoc');
        if ($exist)$this->newBuycode(13);
        return $x;
    }

    public function getBuyHistory($type,$param='') {
        //get activity setting.
        $activity = new Activity();
        $usercode = $_SESSION['usercode'];
        $manager = new Manager();
        $setting = $activity->getActivityInfo();

        $total['total'] = 0;
        $total['return'] = 0;
        $total['count'] = 0;
        if ($type == 'search') {
            $start = strtotime($this->mysql_escape_string($param['start']));
            $end = strtotime($this->mysql_escape_string($param['end']));
            //get all records.
            $sql = "select storecode,buycode,sum(total) as total,sum(suppliermoney) as suppliermoney,
            sum(storemoney) as storemoney from busirecord where usercode='$usercode'
             and dtime >= '$start' and dtime <= '$end' group by buycode";
            $result = $this->query($sql,'DEFAULT','all');

            $total['count'] = count($result);
            foreach ($result as $key=>$row) {
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                
                //get applytime and usetime
                $time = $this->query("select ctime,usetime from applyrecord where buycode = '{$row['buycode']}'",'DEFAULT','assoc');
                $result[$key]['ctime'] = date('Y/m/d',$time['ctime']);
                $result[$key]['usetime'] = date('Y/m/d',$time['usetime']);
                
                //get storeInfo.
                $storeInfo = $manager->getUserDepartmentInfo($row['storecode'],'dcode');
                $result[$key]['storename'] = $storeInfo['name'];
                
                $total['total'] += $row['total'];
                $total['return'] += $result[$key]['return'];
            }

            
        }
        else if ($type == 'plist') {
            $buycode = $this->mysql_escape_string($param);
            //get buy record.
            $total['buycode'] = $buycode;
            $total['cname'] = $setting['name'];

            $sql = "select pname,count,storecode,suppliermoney,storemoney,dtime,total,billmoney,billcode from busirecord where buycode = '$buycode'";
            $result = $this->query($sql,'DEFAULT','all');
            if ($result[0]) {
                $sInfo = $manager->getUserDepartmentInfo($result[0]['storecode'],'dcode');
                $total['storename'] = $sInfo['name'];
                $total['date'] = date('Y/m/d',$result[0]['dtime']);
            }
            else die('-1');
            foreach ($result as $key=>$row) {
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                $total['total'] += $row['total'];
                $total['count'] += $row['count'];
                $total['return'] +=  $result[$key]['return'];
            }
        }
        return array('data'=>$result,'all'=>$total);
    }

    public function newBusiUser($data) {
        $user = new User();
        $data['usercode'] = $user->newUsercode(2,Application::$app->usercodelen);
        $data['password'] = $user->newPassword(Application::$app->passwordlen);
        $data['loginname'] = $user->newLoginCode(2,Application::$app->logincodelen);
        $data['ctime'] = time(true);
        $data['last_update_time'] = time(true);
        $this->insert('busiuser',$data);
    }

    public function getBaseInfo($usercode) {
        $usercode = $this->mysql_escape_string($usercode);
        $sql = "select * from busiuser where usercode='$usercode'";
        $data = $this->query($sql,'DEFAULT','assoc');

        $data['capitaltext'] = $this->getCapitalText($data['capitallevel']);
        $data['licenseShow'] = User::getFileName($data['licensepic'], 15);
        $data['orgShow'] = User::getFileName($data['orgpic'], 15);
        return $data;
    }

    public function getCapitalText($id) {
        $config = Application::$app->capitallevel;
        foreach($config as $item) {
            if ($item['level'] == $id)return $item['text'];
        }
    }

    public function saveProfileChange($data) {
        $usercode = $_SESSION['usercode'];
        $where = "usercode = '$usercode'";
        $this->update('busiuser',$where,$data);
    }

    public function checkPassword($password) {
        $password = $this->mysql_escape_string($password);
        $usercode = $_SESSION['usercode'];
        $user = $this->query("select password from busiuser where usercode='$usercode'",'DEFAULT','assoc');
        return $password == $user['usercode'];
    }

    public function userExist($usercode) {
        $exist = $this->query("select * from busiuser where usercode='$usercode'",'DEFAULT','assoc');
        return $exist ? $exist : false;
    }

    public function updateUserInfo($usercode,$fields) {
        $where = " usercode = '$usercode'";
        return $this->update('busiuser', $where, $fields);
    }
    /*
     * @description: Get business member list.
     * @param: int 1 to review,2 passed,3 rejected.
     * @return: Array or error -1
     */
    public function getBusiUserList($type = 'review') {
        if ($type == 'review') {
            $sql = "select * from busiuser where status in (1,4) order by last_update_time";
            $data = $this->query($sql,'DEFAULT','all');
            foreach ($data as $key=>$row) {
                $data[$key]['time'] = date('Y/m/d',$row['last_update_time']);
                if ($row['status'] == 1) {
                    $data[$key]['status'] = '新增';
                }
                else if ($row['status'] == 4) {
                    $data[$key]['status'] = '变更';
                }
            }
        }
        
        return $data;
    }
    
    /**
     * @description get the user stat list by specified conditions.
     * @param array $condition search condition
     * @param int $page
     */
    public function getUserStatList($condition,$page) {
        $activity = new Activity();
        //name,indus,type,status.
        $sub = " industry = '{$condition['indus']}' and type = '{$condition['type']}'";
        if ($condition['name'])$sub = " and companyname like '%{$condition['name']}%' ";

        //添加分页参数
        $total['param'] = json_encode(array('type'=>$condition['type'],'name'=>$condition['name'],'status'=>$condition['status'],'indus'=>$condition['indus']));

        //获取要搜索的公司属性和所属行业
        $company_type = Application::$app->company_type;
        foreach ($company_type as $value) {
            if ($value['level'] == $condition['type'])$condition['type'] = $value['text'];
        }
        $indus = Activity::Model()->getAllIndustry();
        foreach ($indus as $value) {
            if ($value['id'] == $condition['indus'])$condition['indus'] = $value['name'];
        }

        $sql = "select companyname,usercode,last_login_time,locked from busiuser where " .$sub . " group by usercode";
        $users = $this->query($sql,'DEFAULT','all');

        $result = array();
        foreach ($users as $user) {
            $record = array();
            $record['companyname'] = $user['companyname'];
            $record['usercode'] = $user['usercode'];
            $record['last_login'] = date('Y/m/d',$user['last_login_time']);
            $record['type'] = $condition['type'];
            $record['indus'] = $condition['indus'];
            $record['locked'] = $condition['locked'];

            //获得申请代码的次数
            $tmp = $this->query("select count(buycode) as count from applyrecord where usercode = '{$user['usercode']}'",'DEFAULT','assoc');
            $record['apply_count'] = $tmp['count'];

            //获得使用代码的次数
            $tmp = $this->query("select count(buycode) as count from applyrecord where usercode = '{$user['usercode']}' and status = 2",'DEFAULT','assoc');
            $record['use_count'] = $tmp['count'];

            if ($condition['status'] == 0 && $record['use_count'] == 0) {//未采购
                $record['total'] = 0;
                $record['return'] = 0;
                array_push($result,$record);
            }
            else if ($condition['status'] == 1 && $record['use_count'] > 0){//已采购
                $sql = "select sum(total) as total, sum(storemoney) as storemoney, sum(suppliermoney) as suppliermoney from busirecord where usercode = '{$user['usercode']}' and status = 2";
                $tmp = $this->query($sql,'DEFAUTL','assoc');
                $record['total'] = $tmp['total'];
                $record['return'] = $activity->calculate($tmp['suppliermoney'], $tmp['storemoney']);
                array_push($result,$record);
            }
            else continue;
        }
        
        //处理分页
        $total['links'] = Utils::Model()->getSepLinkString(count($result));
        $offset = ($page - 1) * Application::$app->perPage;
        $result = array_slice($result,$offset,Application::$app->perPage);
        return array('data'=>$result,'all'=>$total);
    }
    
    public function lockUserAccount($usercode) {
    	echo "select usercode,locked from busiuser where usercode='$usercode'";
        $exist = $this->query("select usercode,locked from busiuser where usercode='$usercode'",'DEFAULT','assoc');
        if (!$exist) return -1;
        $locked = ($exist['locked'] == 0) ? 1 : 0;
        return $this->query("update busiuser set locked = '$locked' where usercode = '$usercode'",'DEFAULT','assoc');
    }
}
