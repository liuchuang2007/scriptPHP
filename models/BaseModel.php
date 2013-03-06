<?php
class BaseModel extends AgentDB {
    public function getReturnStat($type,$param='') {
        $manager = new Manager();
        $cate = new Category();
        $busi = new BusiModel();
        $depart = $manager->getUserDepartmentInfo($_SESSION['usercode']);
        $activity = new Activity();

        $total['total'] = 0;
        $total['return'] = 0;
        $total['cancel'] = 0;

        if ($type == 'rblist') {//branches specified day stat list of region.
            $time = $this->mysql_escape_string($param['time']);
            $rcode = $this->mysql_escape_string($param['rcode']);
            $result = array();
            
            //get store name.
            $rInfo = $manager->getUserDepartmentInfo($rcode,'dcode');
            if (empty($rInfo))die('-1');
            $total['name'] = $rInfo['name'];
            $total['date'] = date('Y/m/d',$time);

            //get branches
            $btype = BRANCH_TYPE;
            $branches = $this->query("select a.dcode,name from department a,manager b where a.dcode = b.dcode and pregion='$rcode' and type = $btype group by a.dcode",'DEFAULT','all');
            foreach ($branches as $item) {
                $sql = "select sum(total)as total,dtime,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from (select dcode from department where pbranch='{$item['dcode']}') as a,
                  busirecord as b  where a.dcode = b.storecode and status = 2 and dtime = '$time'";

                $bInfo = $this->query($sql,'DEFAULT','assoc');
                $row['bcode'] = $item['dcode'];
                $row['bname'] = $item['name'];
                $row['dtime'] = $time;
                $row['total'] = empty($bInfo['total']) ? 0 : $bInfo['total'];
                $row['return'] = $activity->calculate($bInfo['suppliermoney'], $bInfo['storemoney']);
               
                //get cancel data
                $sql = "select sum(total)as total from (select dcode from department where pbranch='{$item['dcode']}')as a,
                 busirecord b  where a.dcode = b.storecode and status = 3 and dtime = '$time'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                //stat total
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
                array_push($result,$row);
            }
        }
        if ($type == 'rlist') {//branches specified day stat list of region.
            $time = $this->mysql_escape_string($param);
            $result = array();
            $total['name'] = $depart['name'];
            $total['date'] = date('Y/m/d',$time);
       
            //get regions
            $btype = REGION_TYPE;
            $regions = $this->query("select a.dcode,name from department a,manager b where a.dcode = b.dcode and pbase='{$depart['dcode']}' and type = $btype group by a.dcode",'DEFAULT','all');
            foreach ($regions as $item) {
                $sql = "select sum(total)as total,dtime,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from (select dcode from department where pregion='{$item['dcode']}') as a,
                  busirecord as b  where a.dcode = b.storecode and status = 2 and dtime = '$time'";
                $bInfo = $this->query($sql,'DEFAULT','assoc');
                $row['rcode'] = $item['dcode'];
                $row['bname'] = $item['name'];
                $row['dtime'] = $time;
                $row['total'] = empty($bInfo['total']) ? 0 : $bInfo['total'];
                $row['return'] = $activity->calculate($bInfo['suppliermoney'], $bInfo['storemoney']);
               
                //get cancel data
                $sql = "select sum(total)as total from (select dcode from department where pregion='{$item['dcode']}')as a,
                 busirecord b  where a.dcode = b.storecode and status = 3 and dtime = '$time'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                //stat total
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
                array_push($result,$row);
            }
        }
        else if ($type == 'storelist') {
            $time = $this->mysql_escape_string($param['time']);
            $bcode = $this->mysql_escape_string($param['bcode']);
            $result = array();

             //get region and branch name.
            $bInfo = $manager->getUserDepartmentInfo($bcode,'dcode');
            if (empty($bInfo))die('-1');
            $rInfo = $manager->getUserDepartmentInfo($bInfo['pregion'],'dcode');
            
            $total['rname'] = $rInfo['name'];
            $total['bname'] = $bInfo['name'];
            $total['date'] = date('Y/m/d',$time);
            

            $total['dtime'] = $time;
            //get all stores
            $storetype = STORE_TYPE;
            $sql = "select a.name,a.dcode from department a, manager b where a.dcode = b.dcode and pbranch = '$bcode' and type = $storetype group by a.dcode";
            $stores = $this->query($sql,'DEFAULT','all');
            foreach ($stores as $item) {
                $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from busirecord  where storecode = '{$item['dcode']}' and status = 2 and dtime = '$time'";
                $row = $this->query($sql,'DEFAULT','assoc');
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['name'] = $item['name'];
                $row['dtime'] = $time;
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
        else if ($type == 'plist') {
            $dtime = $this->mysql_escape_string($param['time']);
            $storecode = $this->mysql_escape_string($param['storecode']);
            $result = array();

            //get store name.
            $sInfo = $manager->getUserDepartmentInfo($storecode,'dcode');
            if (empty($sInfo))die('-1');
            $total['name'] = $sInfo['name'];
            $total['date'] = date('Y/m/d',$dtime);

            //get all buycode.
            $sql = "select buycode,usercode from busirecord where storecode = '$storecode' and dtime = '$dtime' group by buycode";
            $codes = $this->query($sql,'DEFAULT','all');
            foreach ($codes as $key=>$item) {
                $sql = "select sum(total)as total,usercode,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from 
                  busirecord where buycode = '{$item['buycode']}' and status = 2";
                $buyInfo = $this->query($sql,'DEFAULT','assoc');
                $userInfo = $busi->getBaseInfo($item['usercode']);
                $row['buycode'] = $item['buycode'];
                $row['companyname'] = $userInfo['companyname'];
                $row['total'] = empty($buyInfo['total']) ? 0 : $buyInfo['total'];
                $row['return'] = $activity->calculate($buyInfo['suppliermoney'], $buyInfo['storemoney']);
               
                //get cancel data
                $sql = "select sum(total)as total from busirecord where buycode = '{$item['buycode']}' and status = 3";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                //stat total
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
                array_push($result,$row);
            }
        }
        else if ($type == 'lrlist') {//region list.
            //get time.
            $time = $activity->getTimeLine('lweek');
            $start = $time[0];
            $end = array_pop($time);

            $result = array();
            $total['name'] = $depart['name'];
            //get all regions
            $btype = REGION_TYPE;
            $regions = $this->query("select a.dcode,name from department a,manager b where a.dcode = b.dcode and pbase='{$depart['dcode']}' and type = $btype group by a.dcode",'DEFAULT','all');
            foreach ($regions as $item) {
                $sql = "select sum(total)as total,dtime,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from (select dcode from department where pregion='{$item['dcode']}') as a,
                  busirecord as b  where a.dcode = b.storecode and status = 2 and dtime >= '$start' and dtime <= '$end'";
                $bInfo = $this->query($sql,'DEFAULT','assoc');
                $row['rcode'] = $item['dcode'];
                $row['bname'] = $item['name'];
                $row['total'] = empty($bInfo['total']) ? 0 : $bInfo['total'];
                $row['return'] = $activity->calculate($bInfo['suppliermoney'], $bInfo['storemoney']);
               
                //get cancel data
                $sql = "select sum(total)as total from (select dcode from department where pregion='{$item['dcode']}')as a,
                 busirecord b  where a.dcode = b.storecode and status = 3 and dtime >= '$start' and dtime <= '$end'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                //stat total
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
                array_push($result,$row);
            }
        }
        else if ($type == 'lblist') {
            //get time.
            $time = $activity->getTimeLine('lweek');
            $start = $time[0];
            $rcode = $this->mysql_escape_string($param);
            $end = array_pop($time);

            //get region name.
            $bInfo = $manager->getUserDepartmentInfo($rcode,'dcode');
            if (empty($bInfo))die('-1');
            $total['name'] = $bInfo['name'];

            $result = array();
            //get branches
            $btype = BRANCH_TYPE;
            $branches = $this->query("select a.dcode,name from department a,manager b where a.dcode = b.dcode and pregion='{$rcode}' and type = $btype group by a.dcode",'DEFAULT','all');
            foreach ($branches as $item) {
                $sql = "select sum(total)as total,dtime,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from (select dcode from department where pbranch='{$item['dcode']}') as a,
                  busirecord as b  where a.dcode = b.storecode and status = 2 and dtime >= '$start' and dtime <= '$end'";
                $bInfo = $this->query($sql,'DEFAULT','assoc');
                $row['bcode'] = $item['dcode'];
                $row['bname'] = $item['name'];
                $row['total'] = empty($bInfo['total']) ? 0 : $bInfo['total'];
                $row['return'] = $activity->calculate($bInfo['suppliermoney'], $bInfo['storemoney']);
               
                //get cancel data
                $sql = "select sum(total)as total from (select dcode from department where pbranch='{$item['dcode']}')as a,
                 busirecord b  where a.dcode = b.storecode and status = 3 and dtime >= '$start' and dtime <= '$end'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                //stat total
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
                array_push($result,$row);
            }
        }
        else if ($type == 'lslist') {//last week store list.
            $bcode = $this->mysql_escape_string($param);
            $result = array();
             //get time.
            $time = $activity->getTimeLine('lweek');
            $start = $time[0];
            $end = array_pop($time);

            //get branch name.
            $bInfo = $manager->getUserDepartmentInfo($bcode,'dcode');
            $rInfo = $manager->getUserDepartmentInfo($bInfo['pregion'],'dcode');
            if (empty($bInfo))die('-1');
            $total['bname'] = $bInfo['name'];
            $total['rname'] = $rInfo['name'];

            //get all stores
            $storetype = STORE_TYPE;
            $sql = "select a.name,a.dcode from department a, manager b where a.dcode = b.dcode and pbranch = '$bcode' and type = $storetype group by a.dcode";
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
        else if ($type == 'lsdetails') {
            $storecode = $this->mysql_escape_string($param);
            $time = $activity->getTimeLine('lweek');
            
            $sInfo = $manager->getUserDepartmentInfo($storecode,'dcode');
            if (empty($sInfo))die('-1');
            
            $total['name'] = $sInfo['name'];
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
            $dtime = $this->mysql_escape_string($param['time']);
            $stype = $this->mysql_escape_string($param['stype']);
            $result = array();
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
                    $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where pbase='{$depart['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 2 and dtime = $item";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    $row['date'] = date('Y/m/d',$item);
                    $row['dtime'] = $item;
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    $sql = "select sum(total)as total from (select dcode from department where pbase='{$depart['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 3 and dtime = '$item'";
                    $cancel = $this->query($sql,'DEFAULT','assoc');
                    $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                    $total['cancel'] += $row['cancel'];
                    $total['total'] += $row['total'];
                    $total['return'] += $row['return'];
                    array_push($result,$row);
                }
            }
            else if ($stype == 'emonth') {
                $time = $activity->getTimeLine('permonth');
                foreach($time as $item) {
                    $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where 
                    pbase='{$depart['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 2 and dtime >= '{$item['start']}' and dtime <= '{$item['end']}'";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    $row['date'] = date('Y年m月',$item['start']);
                    $row['dtime'] = $item['start'];
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    $sql = "select sum(total)as total from (select dcode from department where pbase='{$depart['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 3 and dtime >= '{$item['start']}' and dtime <= '{$item['end']}'";
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
        $supplier = new Supplier();
        $cate = new Category();
        $depart = $manager->getUserDepartmentInfo($_SESSION['usercode']);
        $activity = new Activity();
        $stype = $this->mysql_escape_string($param['stype']);
        $key = $this->mysql_escape_string($param['key']);

        $total['total'] = 0;
        $total['return'] = 0;
        $total['opmoney'] = 0;

        if ($type == 'search') {
            $stype = $this->mysql_escape_string($param);
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
            //get all regions
            $regiontype = REGION_TYPE;
            $sql = "select a.name,a.dcode from department a, manager b where a.dcode = b.dcode and pbase = '{$depart['dcode']}' and type = $regiontype group by a.dcode";
            $regions = $this->query($sql,'DEFAULT','all');
            foreach ($regions as $item) {
                $sql = "select sum(total)as total,sum(opmoney)as opmoney,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where pregion='{$item['dcode']}') a,busirecord b where a.dcode = b.storecode and status = 2 $sub";
                $row = $this->query($sql,'DEFAULT','assoc');
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['opmoney'] = empty($row['opmoney']) ? 0 : $row['opmoney'];
                $row['name'] = $item['name'];
                $row['dcode'] = $item['dcode'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                array_push($result,$row);
                
                $total['total'] += $row['total'];
                $total['opmoney'] += $row['opmoney'];
                $total['return'] += $row['return'];
            }
        }
        else if ($type == 'branchlist') {
            $start = $this->mysql_escape_string($param['start']);
            $end = $this->mysql_escape_string($param['end']);
            $rcode = $this->mysql_escape_string($param['rcode']);
            if (empty($rcode))die('-1');
            if ($start && $end) {
                $sub = " and dtime >= '$start' and dtime <= '$end'";
            }
            else {
                $time1 = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time1);
                $sub = " and dtime <= '$lastday'";
            }
            $result = array();
            $total['start'] = $start;
            $total['end'] = $end;
            $total['bcode'] = $bcode;

            $branchtype = BRANCH_TYPE;
            $sql = "select a.name,a.dcode from department a, manager b where a.dcode = b.dcode and pregion = '$rcode' and type = $branchtype group by a.dcode";
            $branches = $this->query($sql,'DEFAULT','all');
            foreach ($branches as $item) {
                $sql = "select sum(total)as total,sum(opmoney)as opmoney,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where pbranch='{$item['dcode']}') a,busirecord b where a.dcode = b.storecode and status = 2 $sub";
                $row = $this->query($sql,'DEFAULT','assoc');
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['opmoney'] = empty($row['opmoney']) ? 0 : $row['opmoney'];
                $row['name'] = $item['name'];
                $row['dcode'] = $item['dcode'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                array_push($result,$row);
                
                $total['total'] += $row['total'];
                $total['opmoney'] += $row['opmoney'];
                $total['return'] += $row['return'];
            }
        }
        else if ($type == 'opclist') {
            $start = $this->mysql_escape_string($param['start']);
            $end = $this->mysql_escape_string($param['end']);
            $bcode = $this->mysql_escape_string($param['bcode']);
            if (empty($bcode))die('-1');
            if ($start && $end) {
                $sub = " and dtime >= '$start' and dtime <= '$end'";
            }
            else {
                $time1 = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time1);
                $sub = " and dtime <= '$lastday'";
            }
            $result = array();
            $total['start'] = $start;
            $total['end'] = $end;
            $total['bcode'] = $bcode;

            //get all cates
            $cates = $this->query("select * from category where type = 1",'DEFAULT','all');
            foreach($cates as $item) {
                $sql = "select sum(total)as total,cid,sum(opmoney)as opmoney,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from (select dcode from department where pbranch='$bcode') a, 
            busirecord b where a.dcode = b.storecode and b.status = 2 $sub and cid = '{$item['id']}'";

                $row = $this->query($sql,'DEFAULT','assoc');
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['opmoney'] = empty($row['opmoney']) ? 0 : $row['opmoney'];
                $row['name'] = $item['name'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                array_push($result,$row);
                
                $total['total'] += $row['total'];
                $total['opmoney'] += $row['opmoney'];
                $total['return'] += $row['return'];
            }
        }
        else if ($type == 'opblist') {
            $start = $this->mysql_escape_string($param['start']);
            $end = $this->mysql_escape_string($param['end']);
            $cid = $this->mysql_escape_string($param['cid']);
            $bcode = $this->mysql_escape_string($param['bcode']);
            if (empty($bcode))die('-1');
            if ($start && $end) {
                $sub = " and dtime >= '$start' and dtime <= '$end'";
            }
            else {
                $time1 = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time1);
                $sub = " and dtime <= '$lastday'";
            }

            //get all brands
            $total['start'] = $start;
            $total['end'] = $end;
            $total['cid'] = $cid;
            $total['bcode'] = $bcode;
            $result = array();
            $brands = $this->query("select * from category where pid = '$cid' and type = 2",'DEFAULT','all');
            foreach($brands as $item) {
                $sql = "select sum(total)as total,bid,sum(opmoney)as opmoney,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from (select dcode from department where pbranch='$bcode') a, 
            busirecord b where a.dcode = b.storecode and b.status = 2 $sub and cid = '$cid' and bid = '{$item['id']}'";

                $row = $this->query($sql,'DEFAULT','assoc');
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['opmoney'] = empty($row['opmoney']) ? 0 : $row['opmoney'];
                $row['name'] = $item['name'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                array_push($result,$row);
                
                $total['total'] += $row['total'];
                $total['opmoney'] += $row['opmoney'];
                $total['return'] += $row['return'];
            }
        }
        else if ($type == 'plist') {
            $start = $this->mysql_escape_string($param['start']);
            $end = $this->mysql_escape_string($param['end']);
            $cid = $this->mysql_escape_string($param['cid']);
            $bid = $this->mysql_escape_string($param['bid']);
            $bcode = $this->mysql_escape_string($param['bcode']);
            if (empty($bcode))die('-1');
            if ($start && $end) {
                $sub = " and dtime >= '$start' and dtime <= '$end'";
            }
            else {
                $time1 = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time1);
                $sub = " and dtime <= '$lastday'";
            }
            if ($cid) $sub .= " and cid = '$cid'";
            if ($bid) $sub .= " and bid = '$bid'";
            $sql = "select total,count,pname,opmoney,suppliermoney,storemoney from (select dcode from department where pbranch='$bcode') a, 
            busirecord b where a.dcode = b.storecode and b.status = 2 $sub";

            $result = $this->query($sql,'DEFAULT','all');
            foreach($result as $key=>$row) {
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);

                $total['total'] += $row['total'];
                $total['count'] += $row['count'];
                $total['return'] += $result[$key]['return'];
                $total['opmoney'] += $row['opmoney'];
            }
        }
        
        return array('data'=>$result,'all'=>$total);
    }

 public function getSupplierStat($type,$param='') {
        $manager = new Manager();
        $busi = new BusiModel();
        $supplier = new Supplier();
        $cate = new Category();
        $branch = new BranchModel();
        $depart = $manager->getUserDepartmentInfo($_SESSION['usercode']);
        $activity = new Activity();
        $acsetting = $this->query("select name from activity",'DEFAULT','assoc');

        $total['total'] = 0;
        $total['return'] = 0;
        $total['cancel'] = 0;
        if ($type == 'sdlist') {//suppliers list in specified day.
            $time = $this->mysql_escape_string($param['time']);
            $bcode = $this->mysql_escape_string($param['bcode']);
            $total['date'] = date('Y/m/d',$time);

            //get all suppliers
            $result = array();
            $sql = "select scode,name from supplier where pbranch = '$bcode'";
            $suppliers = $this->query($sql,'DEFAULT','all');
            foreach($suppliers as $item) {
                 $sql = "select sum(total)as total,scode,dtime,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from busirecord where scode = '{$item['scode']}' and dtime = '$time' and status = 2";
                 $row = $this->query($sql,'DEFAULT','assoc');
                 $row['total'] = empty($row['total']) ? 0 :  $row['total'];
                 $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                 $row['sname'] = $item['name'];
                 $row['scode'] = $item['scode'];
                 $cancel = $this->query("select sum(total)as total from busirecord where scode = '{$item['scode']}' and dtime = '$time' and status = 3",'DEFAULT','assoc');
                 $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                 array_push($result,$row);
                 $total['cancel'] += $row['cancel'];
                 $total['total'] += $row['total'];
                 $total['return'] += $row['return'];
            }
        }
        else if ($type == 'rlist') {//branches specified day stat list of region.
            $time = $this->mysql_escape_string($param);
            $result = array();
            //get branches
            $total['date'] = date('Y/m/d',$time);
            $total['name'] = $depart['name'];
            $btype = REGION_TYPE;
            $regions = $this->query("select a.dcode,name from department a,manager b where a.dcode = b.dcode and pbase='{$depart['dcode']}' and type = $btype group by a.dcode",'DEFAULT','all');
            foreach ($regions as $item) {
                $sql = "select sum(total)as total,dtime,a.scode,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from (select scode from supplier where pregion='{$item['dcode']}') as a,
                  busirecord as b  where a.scode = b.scode and status = 2 and dtime = '$time'";
                $bInfo = $this->query($sql,'DEFAULT','assoc');
                $row['rcode'] = $item['dcode'];
                $row['bname'] = $item['name'];
                $row['dtime'] = $time;
                $row['total'] = empty($bInfo['total']) ? 0 : $bInfo['total'];
                $row['return'] = $activity->calculate($bInfo['suppliermoney'], $bInfo['storemoney']);
               
                //get cancel data
                $sql = "select sum(total)as total from (select scode from supplier where pregion='{$item['dcode']}')as a,
                 busirecord b  where a.scode = b.scode and status = 3 and dtime = '$time'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                //stat total
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
                array_push($result,$row);
            }
        }
        else if ($type == 'rblist') {//branches specified day stat list of region.
            $time = $this->mysql_escape_string($param['time']);
            $rcode = $this->mysql_escape_string($param['rcode']);
            $result = array();
            //get branches
            $total['date'] = date('Y/m/d',$time);
            $rInfo = $manager->getUserDepartmentInfo($rcode,'dcode');
            if (empty($rInfo)) die('-1');
            $total['name'] = $rInfo['name'];
            $btype = BRANCH_TYPE;
            $branches = $this->query("select a.dcode,name from department a,manager b where a.dcode = b.dcode and pregion='$rcode' and type = $btype group by a.dcode",'DEFAULT','all');
            foreach ($branches as $item) {
                $sql = "select sum(total)as total,dtime,a.scode,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from (select scode from supplier where pbranch='{$item['dcode']}') as a,
                  busirecord as b  where a.scode = b.scode and status = 2 and dtime = '$time'";
                $bInfo = $this->query($sql,'DEFAULT','assoc');
                $row['bcode'] = $item['dcode'];
                $row['bname'] = $item['name'];
                $row['dtime'] = $time;
                $row['total'] = empty($bInfo['total']) ? 0 : $bInfo['total'];
                $row['return'] = $activity->calculate($bInfo['suppliermoney'], $bInfo['storemoney']);
               
                //get cancel data
                $sql = "select sum(total)as total from (select scode from supplier where pbranch='{$item['dcode']}')as a,
                 busirecord b  where a.scode = b.scode and status = 3 and dtime = '$time'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                //stat total
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
                array_push($result,$row);
            }
        }
         else if ($type == 'lrlist') {//regions last week stat list.
            $time = $activity->getTimeLine('lweek');
            $result = array();
            $dInfo = $manager->getUserDepartmentInfo($_SESSION['usercode']);
            $total['name'] = $dInfo['name'];

            //get all branches of current region.
            $btype = REGION_TYPE;
            $branches = $this->query("select a.dcode,name from department a,manager b where a.dcode = b.dcode and pbase='{$depart['dcode']}' and type = $btype group by a.dcode",'DEFAULT','all');
            foreach ($branches as $item) {
                $sql = "select sum(total)as total,a.scode,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from (select scode from supplier where pregion='{$item['dcode']}') as a,
                  busirecord as b  where a.scode = b.scode and status = 2 and dtime >= '{$time[0]}' and dtime <= '{$time[6]}'";
                $bInfo = $this->query($sql,'DEFAULT','assoc');
                $row['bcode'] = $item['dcode'];
                $row['bname'] = $item['name'];
                $row['total'] = $bInfo['total'];
                $row['return'] = $activity->calculate($bInfo['suppliermoney'], $bInfo['storemoney']);
               
                //get cancel data
                $sql = "select sum(total)as total from (select scode from supplier where pregion='{$item['dcode']}')as a,
                 busirecord b  where a.scode = b.scode and status = 3 and dtime >= '{$time[0]}' and dtime <= '{$time[6]}'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                //stat total
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
                array_push($result,$row);
            }
        }
        else if ($type == 'lblist') {//branches last week stat list.
            $time = $activity->getTimeLine('lweek');
            $result = array();
            $rcode = $this->mysql_escape_string($param);
            $rInfo = $manager->getUserDepartmentInfo($rcode,'dcode');
            if (empty($rInfo)) die('-1');
            $total['name'] = $rInfo['name'];

            //get all branches of current region.
            $btype = BRANCH_TYPE;
            $branches = $this->query("select a.dcode,name from department a,manager b where a.dcode = b.dcode and pregion='$rcode' and type = $btype group by a.dcode",'DEFAULT','all');
            foreach ($branches as $item) {
                $sql = "select sum(total)as total,a.scode,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from (select scode from supplier where pbranch='{$item['dcode']}') as a,
                  busirecord as b  where a.scode = b.scode and status = 2 and dtime > '{$time[0]}' and dtime <= '{$time[6]}'";
                $bInfo = $this->query($sql,'DEFAULT','assoc');
                $row['bcode'] = $item['dcode'];
                $row['bname'] = $item['name'];
                $row['total'] = $bInfo['total'];
                $row['return'] = $activity->calculate($bInfo['suppliermoney'], $bInfo['storemoney']);
               
                //get cancel data
                $sql = "select sum(total)as total from (select scode from supplier where pbranch='{$item['dcode']}')as a,
                 busirecord b  where a.scode = b.scode and status = 3 and dtime > '{$time[0]}' and dtime <= '{$time[6]}'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                //stat total
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
                array_push($result,$row);
            }
        }
        else if ($type == 'slblist') {//the supplier last week stat list of choosed branch.
            $bcode = $this->mysql_escape_string($param);
            $time = $activity->getTimeLine('lweek');
            $result = array();
            $branchInfo = $manager->getUserDepartmentInfo($bcode,'dcode');
            if (!$branchInfo['name'])die('-1');

            $total['bname'] = $branchInfo['name'];
            //get the suppliers of choosed branch.
            $suppliers = $this->query("select scode,name from supplier where pbranch = '$bcode'",'DEFAULT','all');
            foreach ($suppliers as $item) {
                $sql = "select sum(total)as total,scode,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from
                   busirecord where scode = '{$item['scode']}' and status = 2 and dtime > '{$time[0]}' and dtime <= '{$time[6]}'";
                $sInfo = $this->query($sql,'DEFAULT','assoc');
                $row['scode'] = $item['scode'];
                $row['sname'] = $item['name'];
                $row['total'] = empty($sInfo['total']) ? 0 : $sInfo['total'];
                $row['return'] = $activity->calculate($sInfo['suppliermoney'], $sInfo['storemoney']);
               
                //get cancel data
                $sql = "select sum(total)as total from 
                 busirecord where scode = '{$item['scode']}' and status = 3 and dtime > '{$time[0]}' and dtime <= '{$time[6]}'";
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
        else if ($type == 'sclist') {
            $scode = $this->mysql_escape_string($param['scode']);
            $dtime = $this->mysql_escape_string($param['time']);
            $sql = "select sum(total)as total,cid,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from busirecord where scode = '$scode' and status = 2 and dtime = '$dtime' group by cid";

            $result = $this->query($sql,'DEFAULT','all');
            $total['time'] = $dtime;
            $total['scode'] = $scode;
            $total['date'] = date('Y/m/d',$time);
            $sInfo = $supplier->getSupplierInfo($scode);
            $total['sname'] = $sInfo['name'];
            foreach($result as $key=>$row) {
                $cinfo = $cate->getBaseInfo($row['cid']);
                $result[$key]['cname'] = $cinfo['name'];
                $result[$key]['total'] = empty($row['total']) ? 0 : $row['total'];
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);

                $sql = "select sum(total)as total from busirecord where scode = '$scode' and status = 3 and cid = '{$row['cid']}' and dtime = '$dtime'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $result[$key]['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                $total['cancel'] += $result[$key]['cancel'];
                $total['total'] += $result[$key]['total'];
                $total['return'] += $result[$key]['return'];
            }
        }
        else if ($type == 'sblist') {//branch brand stat list in specified day
            $time = $this->mysql_escape_string($param['time']);
            $scode = $this->mysql_escape_string($param['scode']);
            $cid = $this->mysql_escape_string($param['cid']);
            
            //category
            $cInfo = $cate->getBaseInfo($cid);
            $total['cname'] = $cInfo['name'];
            
            $sql = "select sum(total)as total,cid,bid,scode,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from busirecord b where scode = '$scode' and b.status = 2 and dtime ='$time' and cid = '$cid' group by bid";
            $result = $this->query($sql,'DEFAULT','all');
            if($result) {
                 $total['time'] = $time;
                 $total['scode'] = $scode;
                 $total['cid'] = $cid;
            }
            foreach($result as $key=>$row) {
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                $sinfo = $supplier->getSupplierInfo($row['scode']);
                $cateInfo = $cate->getBaseInfo($row['bid']);
                $result[$key]['bname'] = $cateInfo['name'];

                $cancel = $this->query("select sum(total)as total from busirecord where scode = '{$row['scode']}' and status = 3 and dtime ='$time' and bid = '{$row['bid']}' and cid = '$cid'",'DEFAULT','assoc');
                $result[$key]['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];
                $total['cancel'] += $result[$key]['cancel'];
                $total['total'] += $row['total'];
                $total['return'] += $result[$key]['return'];
            }
        }
        else if ($type == 'plist') {//supplier brand details stat
            $time = $this->mysql_escape_string($param['time']);
            $scode = $this->mysql_escape_string($param['scode']);
            $cid = $this->mysql_escape_string($param['cid']);
            $bid = $this->mysql_escape_string($param['bid']);
            
            
            //category and brand.
            $cInfo = $cate->getBaseInfo($cid);
            $total['cname'] = $cInfo['name'];
            $cInfo = $cate->getBaseInfo($bid);
            $total['bname'] = $cInfo['name'];

            $sql = "select total,bid,count,pname,pcode,scode,suppliermoney,storemoney from busirecord b where scode = '$scode' and b.status = 2 and dtime ='$time' and bid = '$bid' and cid = '$cid'";
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
        else if ($type == 'search') {//supplier search
            $dtime = $this->mysql_escape_string($param['time']);
            $stype = $this->mysql_escape_string($param['stype']);
            $result = array();

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
                    $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select scode from supplier where pbase='{$depart['dcode']}') a, busirecord b where b.scode = a.scode and b.status = 2 and dtime = $item";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    $row['date'] = date('Y/m/d',$item);
                    $row['dtime'] = $item;
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    $sql = "select sum(total)as total from (select scode from supplier where pbase='{$depart['dcode']}') a, busirecord b where b.scode = a.scode and b.status = 3 and dtime = '$item'";
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
                    pbase='{$depart['dcode']}') a, busirecord b where b.scode = a.scode and b.status = 2 and dtime >= '{$item['start']}' and dtime <= '{$item['end']}'";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    $row['date'] = date('Y年m月',$item['start']);
                    $row['dtime'] = $item['start'];
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    $sql = "select sum(total)as total from (select scode from supplier where pbase='{$depart['dcode']}') a, busirecord b where b.scode = a.scode and b.status = 3 and dtime >= '{$item['start']}' and dtime <= '{$item['end']}'";
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

    public function getCancelStat($type,$param='') {
        $manager = new Manager();
        $cate = new Category();
        $busi = new BusiModel();
        $depart = $manager->getUserDepartmentInfo($_SESSION['usercode']);
        $activity = new Activity();

        $total['total'] = 0;
        $total['return'] = 0;
        $total['cancel'] = 0;
        if ($type == 'regionlist') {
            $time = $this->mysql_escape_string($param);
            $result = array();

            $total['dtime'] = $time;
            $total['date'] = date('Y/m/d',$time);
            $total['name'] = $depart['name'];
            //get all regions
            $regiontype = REGION_TYPE;
            $sql = "select a.name,a.dcode from department a, manager b where a.dcode = b.dcode and pbase = '{$depart['dcode']}' and type = $regiontype group by a.dcode";
            $regions = $this->query($sql,'DEFAULT','all');
            foreach ($regions as $item) {
                $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where pregion='{$item['dcode']}') a,busirecord b where a.dcode = b.storecode and status = 2 and dtime = '$time'";
                $row = $this->query($sql,'DEFAULT','assoc');
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['name'] = $item['name'];
                $row['dcode'] = $item['dcode'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                
                //get cancel
                $sql = "select sum(total)as total from (select dcode from department where pregion='{$item['dcode']}') a,busirecord b where a.dcode = b.storecode and status = 3 and dtime = '$time'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];
                array_push($result,$row);
                
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
            }
        }
        if ($type == 'branchlist') {
            $time = $this->mysql_escape_string($param['time']);
            $rcode = $this->mysql_escape_string($param['rcode']);
            $result = array();
            
            $total['dtime'] = $time;
            $total['date'] = date('Y/m/d',$time);
            $rInfo = $manager->getUserDepartmentInfo($rcode,'dcode');
            if (!$rInfo)die('-1');
            $total['name'] = $rInfo['name'];

            //get all branches
            $branchtype = BRANCH_TYPE;
            $sql = "select a.name,a.dcode from department a, manager b where a.dcode = b.dcode and pregion = '$rcode' and type = $branchtype group by a.dcode";
            $branches = $this->query($sql,'DEFAULT','all');
            foreach ($branches as $item) {
                $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where pbranch='{$item['dcode']}') a,busirecord b where a.dcode = b.storecode and status = 2 and dtime = '$time'";
                $row = $this->query($sql,'DEFAULT','assoc');
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['name'] = $item['name'];
                $row['dcode'] = $item['dcode'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                
                //get cancel
                $sql = "select sum(total)as total from (select dcode from department where pbranch='{$item['dcode']}') a,busirecord b where a.dcode = b.storecode and status = 3 and dtime = '$time'";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];
                array_push($result,$row);
                
                $total['total'] += $row['total'];
                $total['cancel'] += $row['cancel'];
                $total['return'] += $row['return'];
            }
        }
        else if ($type == 'storelist') {
            $time = $this->mysql_escape_string($param['time']);
            $bcode = $this->mysql_escape_string($param['bcode']);
            $result = array();

            $total['dtime'] = $time;
            $total['date'] = date('Y/m/d',$time);
            $bInfo = $manager->getUserDepartmentInfo($bcode,'dcode');
            $rInfo = $manager->getUserDepartmentInfo($bInfo['pregion'],'dcode');
            if (!$rInfo)die('-1');
            $total['rname'] = $rInfo['name'];
            $total['name'] = $bInfo['name'];

            //get all stores
            $storetype = STORE_TYPE;
            $sql = "select a.name,a.dcode from department a, manager b where a.dcode = b.dcode and pbranch = '$bcode' and type = $storetype group by a.dcode";
            $stores = $this->query($sql,'DEFAULT','all');
            foreach ($stores as $item) {
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
        else if ($type == 'plist') {
            $time = $this->mysql_escape_string($param['time']);
            $storecode = $this->mysql_escape_string($param['storecode']);

            $total['date'] = date('Y/m/d',$time);
            $storeInfo = $manager->getUserDepartmentInfo($storecode,'dcode');
            if (!$storeInfo)die('-1');
            $total['name'] = $storeInfo['name'];

            //get cancel
            $total['count'] = 0;
            $sql = "select total,suppliermoney,storemoney,buycode,usercode,count,billmoney,pname,billcode,canceltime from busirecord  where storecode = '$storecode' and status = 3 and dtime = '$time'";
            $result = $this->query($sql,'DEFAULT','all');
            foreach($result as $key=>$row) {
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                $userInfo = $busi->getBaseInfo($row['usercode']);
                $result[$key]['companyname'] = $userInfo['companyname'];
                $result[$key]['canceldate'] = date('Y/m/d',strtotime($row['canceltime']));
                $result[$key]['cancel'] = $row['total'];
                
                $total['count'] += $row['count'];
                $total['cancel'] += $row['total'];
                $total['return'] += $result[$key]['return'];
            }
        }
        else if ($type == 'search') {
            $dtime = $this->mysql_escape_string($param['time']);
            $stype = $this->mysql_escape_string($param['stype']);
            $result = array();
            if ($stype == 'lweek' || $stype == 'lmonth' || $stype == 'onemonth') {
                if ($stype == 'lweek') {
                    $time = $activity->getTimeLine('lweek');
                }
                else if ($stype == 'lmonth') {
                    $time = $activity->getTimeLine('lmonth');
                }
                else if ($stype == 'onemonth'){
                    
                    $time = $activity->getTimeLine('onemonth',$dtime);
                }
                foreach($time as $item) {
                    $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where pbase='{$depart['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 2 and dtime = $item";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    $row['date'] = date('Y/m/d',$item);
                    $row['dtime'] = $item;
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    $sql = "select sum(total)as total from (select dcode from department where pbase='{$depart['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 3 and dtime = '$item'";
                    $cancel = $this->query($sql,'DEFAULT','assoc');
                    $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                    $total['cancel'] += $row['cancel'];
                    $total['total'] += $row['total'];
                    $total['return'] += $row['return'];
                    array_push($result,$row);
                }
            }
            else if ($stype == 'emonth') {//each month stat.
                $time = $activity->getTimeLine('permonth');
                echo 'sssssss';
                foreach($time as $item) {
                    $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney,dtime from (select dcode from department where 
                    pbase='{$depart['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 2 and dtime >= '{$item['start']}' and dtime <= '{$item['end']}'";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    $row['date'] = date('Y年m月',$item['start']);
                    $row['dtime'] = $item['start'];
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    $sql = "select sum(total)as total from (select dcode from department where pbase='{$depart['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 3 and dtime >= '{$item['start']}' and dtime <= '{$item['end']}'";
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
}