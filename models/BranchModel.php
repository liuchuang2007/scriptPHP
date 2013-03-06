<?php
class BranchModel extends AgentDB {
    public static function Model() {
        return new BranchModel();
    }

    public function branchReturnStat($type,$param='') {
        $manager = new Manager();
        $busi = new BusiModel();
        $depart = $manager->getUserDepartmentInfo($_SESSION['usercode']);
        $activity = new Activity();
        $acsetting = $this->query("select name from activity",'DEFAULT','assoc');

        $total['total'] = 0;
        $total['return'] = 0;
        $total['cancel'] = 0;
        if($type == 'storelist') {
            $time = $this->mysql_escape_string($param['time']);
            $key = $this->mysql_escape_string($param['key']);
            $result = array();
            if (!empty($key)) $searchKey = " and name like '%$key%'";
            
            //get all stores
            $total['time'] = $time;
            $total['key'] = $key;
            $storetype = STORE_TYPE;
            $stores = $this->query("select a.dcode,a.name from department a, manager b where a.dcode = b.dcode and type = '$storetype' and pbranch='{$depart['dcode']}' $searchKey group by a.dcode",'DEFAULT','all');
            foreach($stores as $key=>$item) {
                $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from busirecord  where storecode = '{$item['dcode']}' and status = 2 and dtime = '$time'";
                $row = $this->query($sql,'DEFAULT','assoc');
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['name'] = $item['name'];
                $row['dcode'] = $item['dcode'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                
                //get cancel
                $sql = "select sum(total)as total from busirecord  where storecode = '{$item['dcode']}' and status = 3 and dtime = '$time'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];
                array_push($result,$row);
                
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
            }
        }
        else if ($type == 'bclist') {//buycodelist
            $dtime = $this->mysql_escape_string($param['dtime']);
            $dcode = $this->mysql_escape_string($param['dcode']);
            $sql = "select storecode,buycode,usercode,sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from busirecord where dtime = '$dtime' and storecode = '$dcode' and status = 2 group by buycode";

            $result = $this->query($sql,'DEFAULT','all');
            foreach($result as $key=>$row) {
                $userInfo = $busi->getBaseInfo($row['usercode']);
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                $result[$key]['date'] = date('Y/m/d',$row['dtime']);
                $result[$key]['cancel'] += $this->getCancelMoneyByAttr('dtime',$dtime,$depart['dcode']);
                $result[$key]['companyname'] = $userInfo['companyname'];

                $total['cancel'] += $result[$key]['cancel'];
                $total['total'] += $row['total'];
                $total['return'] += $result[$key]['return'];
            }
        }
        else if ($type == 'plist') {
            $buycode = $this->mysql_escape_string($param);
            $applyInfo = $this->query("select usercode,usetime from applyrecord where status = 2 and buycode = '$buycode'",'DEFAULT','assoc');
            if (empty($applyInfo) || empty($applyInfo['usercode']))die('-1');
            $sql = "select * from busirecord where buycode = '$buycode' and status = 2";

            $result = $this->query($sql,'DEFAULT','all');
            $total['acname'] = $acsetting['name'];
            $total['buycode'] = $buycode;
            $userInfo = $busi->getBaseInfo($applyInfo['usercode']);
            $total['companyname'] = $userInfo['companyname'];
            $total['date'] = date('Y/m/d',$applyInfo['usetime']);
            foreach($result as $key=>$row) {
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);

                $total['total'] += $row['total'];
                $total['count'] += $row['count'];
                $total['return'] += $result[$key]['return'];
            }
        }
        else if ($type == 'lslist') {//last week store list.
            $key = $this->mysql_escape_string($param);
            $result = array();
            if (!empty($key)) $searchKey = " and name like '%$key%'";

             //get time.
            $time = $activity->getTimeLine('lweek');
            $start = $time[0];
            $end = array_pop($time);

            $total['name'] = $depart['name'];

            //get all stores
            $storetype = STORE_TYPE;
            $sql = "select a.name,a.dcode from department a, manager b where a.dcode = b.dcode and pbranch = '{$depart['dcode']}' and type = $storetype $searchKey group by a.dcode";
            $stores = $this->query($sql,'DEFAULT','all');
            foreach ($stores as $item) {
                $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from busirecord  where 
                storecode = '{$item['dcode']}' and status = 2 and dtime >= '$start' and dtime <= '$end'";

                $row = $this->query($sql,'DEFAULT','assoc');
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['name'] = $item['name'];
                $row['dcode'] = $item['dcode'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                
                //get cancel
                $sql = "select sum(total)as total from busirecord  where storecode = '{$item['dcode']}' and status = 3 and dtime >= '$start' and dtime <= '$end'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];
                array_push($result,$row);
                
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
            }
        }
        else if ($type == 'lsdetails') {//last week store details
            $storecode = $this->mysql_escape_string($param);
            $time = $activity->getTimeLine('lweek');
            $storeInfo = $manager->getUserDepartmentInfo($storecode,'dcode');
            
            if (!$storeInfo)die('-1');
            $total['name'] = $storeInfo['name'];

            $result = array();
            foreach($time as $item) {
                    $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from 
                     busirecord  where storecode = '$storecode' and status = 2 and dtime = '$item'";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    $row['date'] = date('Y/m/d',$item);
                    $row['dtime'] = $item;
                    $row['dcode'] = $storecode;
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    $sql = "select sum(total)as total from busirecord where storecode = '$storecode' and status = 3 and dtime = '$item'";
                    $cancel = $this->query($sql,'DEFAULT','assoc');
                    $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                    $total['cancel'] += $row['cancel'];
                    $total['total'] += $row['total'];
                    $total['return'] += $row['return'];
                    array_push($result,$row);
            }
        }
        else if ($type == 'search') {
            $key = $this->mysql_escape_string($param['key']);
            $stype = $this->mysql_escape_string($param['stype']);
            $dtime = $this->mysql_escape_string($param['time']);
            $result = array();
            $total['key'] = $key;

            if (!empty($key)) $searchKey = " and name like '%$key%'";
            if ($stype == 'lweek' || $stype == 'lmonth' || $stype == 'onemonth') {
                if ($stype == 'lweek') {
                    $time = $activity->getTimeLine('lweek');
                }
                else if ($stype == 'lmonth'){
                    $time = $activity->getTimeLine('lmonth');
                }
                else if ($stype == 'onemonth'){
                    
                    $time = $activity->getTimeLine('onemonth',$dtime);
                }
                
                foreach($time as $item) {
                    $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where pbranch='{$depart['dcode']}' $searchKey ) a, busirecord b where b.storecode = a.dcode and b.status = 2 and dtime = $item";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    $row['date'] = date('Y/m/d',$item);
                    $row['dtime'] = $item;
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    $sql = "select sum(total)as total from (select dcode from department where pbranch='{$depart['dcode']}' $searchKey) a, busirecord b where b.storecode = a.dcode and b.status = 3 and dtime = '$item'";
                    $cancel = $this->query($sql,'DEFAULT','assoc');
                    $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                    $total['cancel'] += $row['cancel'];
                    $total['total'] += $row['total'];
                    $total['return'] += $row['return'];
                    array_push($result,$row);
                }
            }
            else if ($stype == 'emonth') {//-------------
                $time = $activity->getTimeLine('permonth');
                foreach($time as $item) {
                    $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where 
                    pbranch='{$depart['dcode']}' $searchKey) a, busirecord b where b.storecode = a.dcode and b.status = 2 and dtime >= '{$item['start']}' and dtime <= '{$item['end']}'";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    $row['date'] = date('Y年m月',$item['start']);
                    $row['dtime'] = $item['start'];
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    $sql = "select sum(total)as total from (select dcode from department where pbranch='{$depart['dcode']}' $searchKey) a, busirecord b where b.storecode = a.dcode and b.status = 3 and dtime >= '{$item['start']}' and dtime <= '{$item['end']}'";
                    $cancel = $this->query($sql,'DEFAULT','assoc');
                    $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                    $total['cancel'] += $row['cancel'];
                    $total['total'] += $row['total'];
                    $total['return'] += $row['return'];
                    array_push($result,$row);
                }
            }
        }

        return array('data'=>$result,'all'=>$total);
    }

    public function getOperationStat($type,$param='') {
        $manager = new Manager();
        $busi = new BusiModel();
        $category = new Category();
        $depart = $manager->getUserDepartmentInfo($_SESSION['usercode']);
        $activity = new Activity();
        $acsetting = $this->query("select name from activity",'DEFAULT','assoc');

        $total['total'] = 0;
        $total['return'] = 0;
        $total['cancel'] = 0;
        $total['opmoney'] = 0;
        if ($type == 'clist') {//category
            $sql = "select cid,bid,sum(total)as total,sum(opmoney)as opmoney,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where pbranch='{$depart['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 2 group by cid";
            $result = $this->query($sql,'DEFAULT','all');
            foreach($result as $key=>$row) {
                $cInfo = $category->getBaseInfo($row['cid']);
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                $result[$key]['cname'] = $cInfo['name'];

                $total['opmoney'] += $row['opmoney'];
                $total['total'] += $row['total'];
                $total['count'] += $row['count'];
                $total['return'] += $result[$key]['return'];
            }
        }
        else if ($type == 'opblist') {
            $cid = $this->mysql_escape_string($param['cid']);
            $start = $this->mysql_escape_string($param['start']);
            $end = $this->mysql_escape_string($param['end']);
            if ($start && $end) {
                $sub = " and dtime >= '$start' and dtime <= '$end'";
            }
            else {
                $time1 = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time1);
                $sub = " and dtime <= '$lastday'";
            }

            
            $sql = "select bid,sum(total)as total,sum(opmoney)as opmoney,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where pbranch='{$depart['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 2 and cid = '$cid' $sub group by bid";
            $result = $this->query($sql,'DEFAULT','all');
            foreach($result as $key=>$row) {
                $cInfo = $category->getBaseInfo($row['bid']);
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                $result[$key]['bname'] = $cInfo['name'];

                $total['total'] += $row['total'];
                $total['count'] += $row['count'];
                $total['opmoney'] += $row['opmoney'];
                $total['return'] += $result[$key]['return'];
            }
        }
        else if ($type == 'bdetails') {
            $bid = $this->mysql_escape_string($param);
            $sql = "select cid,bid,pname,sum(count)as count,sum(total)as total,sum(opmoney)as opmoney,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where pbranch='{$depart['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 2 and bid = $bid group by pcode";
            $result = $this->query($sql,'DEFAULT','all');

            foreach($result as $key=>$row) {
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);

                $total['total'] += $row['total'];
                $total['count'] += $row['count'];
                $total['opmoney'] += $row['opmoney'];
                $total['return'] += $result[$key]['return'];
            }
        }
        if ($type == 'search') {
            $stype = $this->mysql_escape_string($param['stype']);
            $key = $this->mysql_escape_string($param['key']);
            if ($stype == 'lweek') {
                $time = $activity->getTimeLine('lweek');
            }
            else if ($stype == 'lmonth') {
                $time = $activity->getTimeLine('lmonth');
            }
            else if ($stype == 'emonth') {
                $time1 = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time1);
                $sub = " and dtime <= '$lastday'";
            }
            if ($time) {
                $start = $time[0];
                $len = count($time);
                $end = $time[$len-1];
                $total['start'] = $start;
                $total['end'] = $end;
                $sub = " and dtime >= '$start' and dtime <= '$end'";
            }

            $result = array();
            $total['key'] = $key;
            if (!empty($key)) $searchKey = " and name like '%$key%'";
            //get all categories
            $sql = "select name,id from category where type = 1 $searchKey";
            $cates = $this->query($sql,'DEFAULT','all');
            foreach ($cates as $item) {
                $sql = "select sum(total)as total,sum(opmoney)as opmoney,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where pbranch='{$depart['dcode']}') a,busirecord b where a.dcode = b.storecode and cid = '{$item['id']}' and status = 2 $sub";
                $row = $this->query($sql,'DEFAULT','assoc');
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['opmoney'] = empty($row['opmoney']) ? 0 : $row['opmoney'];
                $row['name'] = $item['name'];
                $row['cid'] = $item['id'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                array_push($result,$row);
                
                $total['total'] += $row['total'];
                $total['opmoney'] += $row['opmoney'];
                $total['return'] += $row['return'];
            }
        }

        return array('data'=>$result,'all'=>$total);
    }

    public function getCancelStat($type,$param='') {
        $manager = new Manager();
        $busi = new BusiModel();
        $depart = $manager->getUserDepartmentInfo($_SESSION['usercode']);
        $activity = new Activity();
        $acsetting = $this->query("select name from activity",'DEFAULT','assoc');

        $total['total'] = 0;
        $total['return'] = 0;
        $total['cancel'] = 0;
        $i = 0;

        if($type == 'storelist') {
            $dtime = $this->mysql_escape_string($param['time']);
            $key = $this->mysql_escape_string($param['key']);
            if (!empty($key)) $searchKey = " and name like '%$key%'";
            $sql = "select storecode,sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where pbranch='{$depart['dcode']}' $searchKey) a, busirecord b where b.storecode = a.dcode and dtime = '$dtime' and b.status = 2 group by storecode";
            $result = $this->query($sql,'DEFAULT','all');
            $total['date'] = date('Y/m/d',$dtime);
            foreach($result as $key=>$row) {
                $storeInfo = $manager->getUserDepartmentInfo($row['storecode'],'dcode');
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                
                $result[$key]['cancel'] += $this->getCancelMoneyByAttr('dtime',$dtime,$depart['dcode']);
                $result[$key]['storename'] = $storeInfo['name'];

                $total['cancel'] += $result[$key]['cancel'];
                $total['total'] += $row['total'];
                $total['return'] += $result[$key]['return'];
                if ($result[$key]['cancel'] == 0) {
                    array_splice($result, $i-1,1);
                }
            }
        }
        else if ($type == 'plist') {
            $dtime = $this->mysql_escape_string($param['dtime']);
            $dcode = $this->mysql_escape_string($param['dcode']);
            $sql = "select storecode,billmoney,canceltime,billcode,pname,count,buycode,usercode,total,suppliermoney,storemoney from busirecord where dtime = '$dtime' and storecode = '$dcode' and status = 3";

            $total['date'] = date('Y/m/d',$dtime);
            $sInfo = $manager->getUserDepartmentInfo($dcode,'dcode');
            if (!$depart) die('-1');
            $total['name'] = $sInfo['name'];
            $result = $this->query($sql,'DEFAULT','all');
            foreach($result as $key=>$row) {
                $userInfo = $busi->getBaseInfo($row['usercode']);
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                $result[$key]['canceldate'] = date('Y/m/d',strtotime($row['canceltime']));
                $result[$key]['cancel'] += $this->getCancelMoneyByAttr('dtime',$dtime,$depart['dcode']);
                $result[$key]['companyname'] = $userInfo['companyname'];

                $total['cancel'] += $result[$key]['cancel'];
                $total['total'] += $row['total'];
                $total['count'] += $row['count'];
                $total['return'] += $result[$key]['return'];
            }
        }
        else if ($type == 'search') {
            $key = $this->mysql_escape_string($param['key']);
            $stype = $this->mysql_escape_string($param['stype']);
            $dtime = $this->mysql_escape_string($param['time']);
            $result = array();
            $total['key'] = $key;

            if (!empty($key)) $searchKey = " and name like '%$key%'";
            if ($stype == 'lweek' || $stype == 'lmonth' || $stype == 'onemonth') {
                if ($stype == 'lweek') {
                    $time = $activity->getTimeLine('lweek');
                }
                else if ($stype == 'lmonth'){
                    $time = $activity->getTimeLine('lmonth');
                }
                else if ($stype == 'onemonth'){
                    
                    $time = $activity->getTimeLine('onemonth',$dtime);
                }
                
                foreach($time as $item) {
                    $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where pbranch='{$depart['dcode']}' $searchKey ) a, busirecord b where b.storecode = a.dcode and b.status = 2 and dtime = $item";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    $row['date'] = date('Y/m/d',$item);
                    $row['dtime'] = $item;
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    $sql = "select sum(total)as total from (select dcode from department where pbranch='{$depart['dcode']}' $searchKey) a, busirecord b where b.storecode = a.dcode and b.status = 3 and dtime = '$item'";
                    $cancel = $this->query($sql,'DEFAULT','assoc');
                    $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                    $total['cancel'] += $row['cancel'];
                    $total['total'] += $row['total'];
                    $total['return'] += $row['return'];
                    array_push($result,$row);
                }
            }
            else if ($stype == 'emonth') {//-------------
                $time = $activity->getTimeLine('permonth');
                foreach($time as $item) {
                    $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where 
                    pbranch='{$depart['dcode']}' $searchKey) a, busirecord b where b.storecode = a.dcode and b.status = 2 and dtime >= '{$item['start']}' and dtime <= '{$item['end']}'";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    $row['date'] = date('Y年m月',$item['start']);
                    $row['dtime'] = $item['start'];
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    $sql = "select sum(total)as total from (select dcode from department where pbranch='{$depart['dcode']}' $searchKey) a, busirecord b where b.storecode = a.dcode and b.status = 3 and dtime >= '{$item['start']}' and dtime <= '{$item['end']}'";
                    $cancel = $this->query($sql,'DEFAULT','assoc');
                    $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                    $total['cancel'] += $row['cancel'];
                    $total['total'] += $row['total'];
                    $total['return'] += $row['return'];
                    array_push($result,$row);
                }
            }
        }
        return array('data'=>$result,'all'=>$total);
    }

    public function getStoreAccountList() {
        //get controled cities.
        $usercode = $_SESSION['usercode'];
        $citycode = $this->query("select citycode from manager where mcode = '$usercode'",'DEFAULT','assoc');
        $citycodes = trim($citycode['citycode'],',');
       
        $sql = "select storecode,name,citycode,status from store where citycode in ($citycodes)";
        $data = $this->query($sql,'DEFAULT','all');
        $city = new City();
        foreach ($data as $key=>$row) {
            $data[$key]['service'] = $this->getStoreAccount($row['storecode'],1,'logincode');
            $data[$key]['query'] = $this->getStoreAccount($row['storecode'],2,'logincode');
            $data[$key]['cityname'] = $city->getCityNameBycode($row['citycode']);
            $data[$key]['status'] = ($row['status'] == 1) ? true : false;
        }

        return $data;
    }

    public function getStoreAccount($mcode, $mtype,$field) {
        $sql = "select logincode from storemanager where storecode = '$mcode' and mtype = '$mtype'";
        $result = $this->query($sql,'DEFAULT','assoc');
        return empty($result) ? '' : $result['logincode'];
    }

    public function profitSearch($type) {
        //get sub stores.
        $usercode = $_SESSION['usercode'];
        $codes = $this->query("select citycode from manager where mcode = '$usercode'",'DEFAULT','assoc');
        $citycodes = trim($codes['citycode'],',');
        //get activity setting.
        $setting = Activity::Model()->getActivityInfo();
        if ($type == 'alist') {
            $sql = "select sum(total) as total from busirecord a,store b where a.storecode = b.storecode and citycode in ($citycodes)";
            $data = $this->query($sql,'DEFAULT','assoc');
            if (!$data['total']) $data['total'] = 0;
            $data['name'] = $setting['name'];
            $data['pay'] = $data['total'] * ($setting['supplierrate'] + $setting['storerate']) / 100;
            $data['profit'] = $data['total'] * $setting['profitrate'] / 100;
            return $data;
        }
        else if ($type == 'clist') {//city
            //get all.
            $sql = "select sum(total) as total from busirecord a,store b where a.storecode = b.storecode and citycode in ($citycodes)";
            $all = $this->query($sql,'DEFAULT','assoc');
            if (!$all['total']) $all['total'] = 0;
            $all['name'] = $setting['name'];
            $all['pay'] = $all['total'] * ($setting['supplierrate'] + $setting['storerate']) / 100;
            $all['profit'] = $all['total'] * $setting['profitrate'] / 100;

            //get list
            $sql = "select citycode,sum(total) as total from busirecord a, store b where a.storecode = b.storecode and citycode in ($citycodes) group by citycode";
            $data = $this->query($sql,'DEFAULT','all');
            $city = new City();
            foreach($data as $key=>$row) {
                $data[$key]['pay'] = $row['total'] * ($setting['supplierrate'] + $setting['storerate']) / 100;
                $data[$key]['cityname'] = $city->getCityNameBycode($row['citycode']);
                $data[$key]['profit'] = $row['total'] * $setting['profitrate'] / 100;
            }

            return array('data'=>$data,'all'=>$all);
        }
    }

    public function getSupplierStat($type,$param='') {
        $manager = new Manager();
        $busi = new BusiModel();
        $supplier = new Supplier();
        $cate = new Category();
        $depart = $manager->getUserDepartmentInfo($_SESSION['usercode']);
        $activity = new Activity();
        $acsetting = $this->query("select name from activity",'DEFAULT','assoc');

        $total['total'] = 0;
        $total['return'] = 0;
        $total['cancel'] = 0;

        if ($type == 'sllist') {//the supplier last week stat 
            $key = $this->mysql_escape_string($param);
            $time = $activity->getTimeLine('lweek');
            $result = array();
            if (!empty($key)) $searchKey = " and name like '%$key%'";

            $total['name'] = $depart['name'];
            //get the suppliers of choosed branch.
            $suppliers = $this->query("select scode,name from supplier where pbranch = '{$depart['dcode']}' $searchKey",'DEFAULT','all');
            foreach ($suppliers as $item) {
                $sql = "select sum(total)as total,scode,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from
                   busirecord where scode = '{$item['scode']}' and status = 2 and dtime >= '{$time[0]}' and dtime <= '{$time[6]}'";
                $sInfo = $this->query($sql,'DEFAULT','assoc');
                $row['scode'] = $item['scode'];
                $row['sname'] = $item['name'];
                $row['total'] = empty($sInfo['total']) ? 0 : $sInfo['total'];
                $row['return'] = $activity->calculate($sInfo['suppliermoney'], $sInfo['storemoney']);
               
                //get cancel data
                $sql = "select sum(total)as total from 
                 busirecord where scode = '{$item['scode']}' and status = 3 and dtime >= '{$time[0]}' and dtime <= '{$time[6]}'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

               //stat total
               $total['total'] += $row['total'];
               $total['cancel'] += $row['cancel'];
               $total['return'] += $row['return'];
               array_push($result,$row);
            }
        }
        else if ($type == 'sldlist') {
            $scode = $this->mysql_escape_string($param);
            $time = $activity->getTimeLine('lweek');
            $result = array();
            
            $sInfo = $supplier->getSupplierInfo($scode);
            if (empty($sInfo))die('-1');
            $total['scode'] = $scode;
            $total['sname'] = $sInfo['name'];
            foreach($time as $item) {
                $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from busirecord where scode = '$scode' and status = 2 and dtime = $item";
                $row = $this->query($sql,'DEFAULT','assoc');
                $row['date'] = date('Y/m/d',$item);
                $row['dtime'] = $item;
                $row['scode'] = $scode;
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                $sql = "select sum(total)as total from busirecord where scode = '$scode' and status = 3 and dtime = '$item'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                $total['cancel'] += $row['cancel'];
                $total['total'] += $row['total'];
                $total['return'] += $row['return'];
                array_push($result,$row);
            }
        }
        else if ($type == 'sclist') {//supplier category list
            $time = $this->mysql_escape_string($param['time']);
            $scode = $this->mysql_escape_string($param['scode']);
            $sql = "select sum(total)as total,cid,scode,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from busirecord b where scode = '$scode' and b.status = 2 and dtime ='$time' group by cid";

            $sInfo = $supplier->getSupplierInfo($scode);
            if (empty($sInfo))die('-1');
            $total['name'] = $sInfo['name'];
            $total['date'] = date('Y/m/d',$time);
            $result = $this->query($sql,'DEFAULT','all');
            if($result) {
                 $total['time'] = $time;
                 $total['scode'] = $scode;
            }
            foreach($result as $key=>$row) {
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                $sinfo = $supplier->getSupplierInfo($row['scode']);
                $cateInfo = $cate->getBaseInfo($row['cid']);
                $result[$key]['cname'] = $cateInfo['name'];

                $cancel = $this->query("select sum(total)as total from busirecord where scode = '{$row['scode']}' and status = 3 and dtime ='$time' and cid = {$row['cid']}",'DEFAULT','assoc');
                $result[$key]['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];
                
                $total['cancel'] += $result[$key]['cancel'];
                $total['total'] += $row['total'];
                $total['return'] += $result[$key]['return'];
            }
        }
        else if ($type == 'sblist') {//supplier brand details stat
            $time = $this->mysql_escape_string($param['time']);
            $scode = $this->mysql_escape_string($param['scode']);
            $cid = $this->mysql_escape_string($param['cid']);
            $sql = "select sum(total)as total,cid,bid,scode,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from busirecord b where scode = '$scode' and b.status = 2 and dtime ='$time' and cid = '$cid' group by bid";

            $result = $this->query($sql,'DEFAULT','all');
            if($result) {
                 $total['time'] = $time;
                 $total['scode'] = $scode;
            }

            $cInfo = $cate->getBaseInfo($cid);
            if (!$cInfo)die('-1');
            $total['name'] = $cInfo['name'];
            foreach($result as $key=>$row) {
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                $sinfo = $supplier->getSupplierInfo($row['scode']);
                $cateInfo = $cate->getBaseInfo($row['bid']);
                $result[$key]['bname'] = $cateInfo['name'];

                $cancel = $this->query("select sum(total)as total from busirecord where scode = '{$row['scode']}' and status = 3 and dtime ='$time' and bid = {$row['bid']}",'DEFAULT','assoc');
                $result[$key]['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];
                
                $total['cancel'] += $result[$key]['cancel'];
                $total['total'] += $row['total'];
                $total['return'] += $result[$key]['return'];
            }
        }
        else if ($type == 'plist') {//supplier brand details stat
            $time = $this->mysql_escape_string($param['time']);
            $scode = $this->mysql_escape_string($param['scode']);
            $bid = $this->mysql_escape_string($param['bid']);
            $sql = "select total,bid,count,pname,pcode,scode,suppliermoney,storemoney from busirecord b where scode = '$scode' and b.status = 2 and dtime ='$time' and bid = '$bid'";

            $bInfo = $cate->getBaseInfo($bid);
            $cInfo = $cate->getBaseInfo($bInfo['pid']);
            if (!$cInfo)die('-1');
            $total['cname'] = $cInfo['name'];
            $total['bname'] = $bInfo['name'];
            $result = $this->query($sql,'DEFAULT','all');
            if($result) {
                 $total['time'] = $time;
                 $total['scode'] = $scode;
            }
            foreach($result as $key=>$row) {
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);

                $cancel = $this->query("select sum(total)as total from busirecord where pcode = '{$row['pcode']}' and status = 3 and dtime ='$time' and bid = {$row['bid']}",'DEFAULT','assoc');
                $result[$key]['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];
                
                $total['cancel'] += $result[$key]['cancel'];
                $total['count'] += $row['count'];
                $total['total'] += $row['total'];
                $total['return'] += $result[$key]['return'];
            }
        }
        else if ($type == 'sdlist') {//date stat
            $time = $this->mysql_escape_string($param);
            $sql = "select sum(total)as total,a.scode,dtime,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from (select scode from supplier where pbranch='{$depart['dcode']}') a, busirecord b where b.scode = a.scode and b.status = 2 and dtime ='$time' group by scode";
            $result = $this->query($sql,'DEFAULT','all');
            foreach($result as $key=>$row) {
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                $sinfo = $supplier->getSupplierInfo($row['scode']);
                $result[$key]['sname'] = $sinfo['name'];

                $cancel = $this->query("select sum(total)as total from (select scode from supplier where pbranch='{$depart['dcode']}') a, busirecord b where b.scode = a.scode and a.scode = '{$row['scode']}' and b.status = 3 and dtime ='$time'",'DEFAULT','assoc');
                $result[$key]['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];
                
                $total['cancel'] += $result[$key]['cancel'];
                $total['total'] += $row['total'];
                $total['return'] += $result[$key]['return'];
            }
        }
        else if ($type == 'search') {//supplier search
            $dtime = $this->mysql_escape_string($param['time']);
            $key = $this->mysql_escape_string($param['key']);
            $stype = $this->mysql_escape_string($param['stype']);
            $result = array();

            if (!empty($key)) $searchKey = " and name like '%$key%'";
            $total['key'] = $key;
            if ($stype == 'lweek' || $stype == 'lmonth' || $stype == 'onemonth') {
                if ($stype == 'lweek') {
                    $time = $activity->getTimeLine('lweek');
                }
                else if ($stype == 'lmonth'){
                    $time = $activity->getTimeLine('lmonth');
                }
                else if ($stype == 'onemonth'){
                    
                    $time = $activity->getTimeLine('onemonth',$dtime);
                }
                foreach($time as $item) {
                    $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select scode from supplier where pbranch='{$depart['dcode']}' $searchKey) a, busirecord b where b.scode = a.scode and b.status = 2 and dtime = $item";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    $row['date'] = date('Y/m/d',$item);
                    $row['dtime'] = $item;
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    $sql = "select sum(total)as total from (select scode from supplier where pbranch='{$depart['dcode']}' $searchKey) a, busirecord b where b.scode = a.scode and b.status = 3 and dtime = '$item'";
                    $cancel = $this->query($sql,'DEFAULT','assoc');
                    $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                    $total['cancel'] += $row['cancel'];
                    $total['total'] += $row['total'];
                    $total['return'] += $row['return'];
                    array_push($result,$row);
                }
            }
            else if ($stype == 'emonth') {//-------------
                $time = $activity->getTimeLine('permonth');
                foreach($time as $item) {
                    $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select scode from supplier where 
                    pbranch='{$depart['dcode']}' $searchKey) a, busirecord b where b.scode = a.scode and b.status = 2 and dtime >= '{$item['start']}' and dtime <= '{$item['end']}'";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    $row['date'] = date('Y年m月',$item['start']);
                    $row['dtime'] = $item['start'];
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    $sql = "select sum(total)as total from (select scode from supplier where pbranch='{$depart['dcode']}' $searchKey) a, busirecord b where b.scode = a.scode and b.status = 3 and dtime >= '{$item['start']}' and dtime <= '{$item['end']}'";
                    $cancel = $this->query($sql,'DEFAULT','assoc');
                    $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                    $total['cancel'] += $row['cancel'];
                    $total['total'] += $row['total'];
                    $total['return'] += $row['return'];
                    array_push($result,$row);
                }
            }
        }

        return array('data'=>$result,'all'=>$total);
    }

    //only used for finance account query.
    public function getAllReturnStat($type,$param){
        $manager = new Manager();
        $activity = new Activity();
        $depart = $manager->getUserDepartmentInfo($_SESSION['usercode']);
        $setting = $activity->getActivityInfo();
        $total['total'] = 0;
        $total['return'] = 0;
        $total['count'] = 0;
        $total['cancel'] = 0;
        if ($type == 'search') {
            $stype = $this->mysql_escape_string($param['stype']);
            $key = $this->mysql_escape_string($param['key']);
            if ($stype == 'lweek') {
                $time = $activity->getTimeLine('lweek');
            }
            else if ($stype == 'lmonth') {
                $time = $activity->getTimeLine('lmonth');
            }
            else if ($stype == 'emonth') {
                $time1 = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time1);
                $end = $lastday;
                $sub = " and dtime <= '$lastday'";
            }
            if ($time) {
                $start = $time[0];
                $len = count($time);
                $end = $time[$len-1];
                $total['start'] = $start;
                $total['end'] = $end;
                $sub = " and dtime >= '$start' and dtime <= '$end'";
            }

            $result = array();
            $total['key'] = $key;
            $total['start'] = $start;
            $total['end'] = $end;
            if (!empty($key)) $searchKey = " and name like '%$key%'";
            //get all stores
            $storetype = STORE_TYPE;
            $sql = "select c.dcode,c.name,citycode,cityname from (select a.dcode,a.name from department a,manager b where a.dcode = b.dcode and pbranch = '{$depart['dcode']}' and type = '$storetype' group by a.dcode) c,departmentcity d where c.dcode = d.dcode $searchKey";
            $stores = $this->query($sql,'DEFAULT','all');

            foreach ($stores as $item) {
                $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from busirecord where storecode = '{$item['dcode']}' and status = 2 $sub";
                $row = $this->query($sql,'DEFAULT','assoc');
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['name'] = $item['name'];
                $row['cityname'] = $item['cityname'];
                $row['dcode'] = $item['dcode'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                
                //get cancel.
                $sql = $sql = "select sum(total)as total from busirecord where storecode = '{$item['dcode']}' and status = 3 $sub";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];
                
                array_push($result,$row);
                
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
            }
        }
        else if ($type == 'plist') {
            $start = $this->mysql_escape_string($param['start']);
            $dcode = $this->mysql_escape_string($param['dcode']);
            $end = $this->mysql_escape_string($param['end']);
            $op = $this->mysql_escape_string($param['op']);
            
            $storeInfo = $manager->getUserDepartmentInfo($dcode,'dcode');
            if (!$storeInfo)die('-1');
            
            $total['name'] = $storeInfo['name'];
            $total['aname'] = $setting['name'];
            if ($start) $sub = " and dtime >= '$start'";
            if ($end) $sub .= " and dtime <= '$end'";
            //get all confirmed products.
            if ($op = 'cancel') {// get canceled product list.
                $sql = "select pname,count,total,suppliermoney,storemoney,billcode,price,canceltime,confirmtime from busirecord where storecode = '$dcode' and status = 3 $sub";
            }
            else {// get confirmed product list.
                $sql = "select pname,count,total,suppliermoney,storemoney,billcode,price,canceltime,confirmtime from busirecord where storecode = '$dcode' and status = 2 $sub";
            }
            $result = $this->query($sql,'DEFAULT','all');

            foreach ($result as $key=>$item) {
                $result[$key]['return'] = $activity->calculate($item['suppliermoney'], $item['storemoney']);
                $result[$key]['confirmtime'] = date('Y/m/d',$item['confirmtime']);
                $result[$key]['canceltime'] = date('Y/m/d',$item['canceltime']);
                $total['total'] += $item['total'];
                $total['return'] += $result[$key]['return'];
                $total['count'] += $item['count'];
            }
        }
        return array('data'=>$result,'all'=>$total);
    }
    public function newDepartAccount($name,$brands,$dcode) {
        $name = $this->mysql_escape_string($name);
        $user = new User();
        $category = new Category();
        $mcode = $user->newUsercode(MANAGER_CODE,Application::$app->usercodelen);

        $sql = "insert into managercate(dcode,mcode,bid,bname,status) values ";
        foreach ($brands as $item) {
            if (!is_numeric($item))die('-1');
            $info = $category->getBaseInfo($item);
            $sql .= "('$dcode','$mcode',$item,'{$info['name']}',1),";
        }
        $this->query(trim($sql,','),'DEFAULT','assoc');

        $newuser['ctime'] = time(true);
        $newuser['logincode'] = $user->newLoginCode(MANAGER_CODE,Application::$app->logincodelen);
        $newuser['dcode'] = $dcode;
        $newuser['mcode'] = $mcode;
        $newuser['status'] = 1;
        $newuser['mname'] = $name;
        $newuser['type'] = BRANCH_TYPE;
        $newuser['mtype'] = DEPARTMENT_ACCOUNT;
        $this->insert('manager', $newuser);
        return 1;
    }

    public function getBranchCity($usercode) {
        $usercode = $this->mysql_escape_string($usercode);
        $sql = "select citycode from manager a, departmentcity b where a.dcode = b.dcode and a.mcode = '$usercode'";
        return $this->query($sql,'DEFAULT','assoc');
    }

    public function getCancelMoneyByAttr($key,$value,$dcode) {
        $sql = "select sum(total)as total from (select dcode from department where pbranch='$dcode') a, busirecord b where b.storecode = a.dcode and b.status = 3 and $key = '$value'";
        $res = $this->query($sql,'DEFAULT','assoc');
        if ($res && $res['total']) return $res['total'];
        else return 0;
    }

    public function getSubAccounts($type,$code) {
        if ($type == DEPARTMENT_ACCOUNT) {
            return $this->query("select * from manager where mtype = $type and dcode = '$code'",'DEFAULT','all');
        }
        else if ($type == BRAND_ACCOUNT) {
            return $this->query("select * from manager where mtype = $type and pcode = '$code'",'DEFAULT','all');
        }
    }
}