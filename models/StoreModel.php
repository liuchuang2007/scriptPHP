<?php
class StoreModel extends AgentDB {
    public static function Model() {
         return new StoreModel();
    }

    public function checkBuycode($buycode) {
        $buycode = $this->mysql_escape_string($buycode);
        $today = strtotime(date('Y-m-d'));
        $info = $this->query("select usercode,endtime,status from applyrecord where buycode='$buycode'",'DEFAULT','assoc');
        if (!$info) return -1;
        else if ($info['endtime'] < $today && $info['status'] == 1) return -2;
        else if ($info['status'] == 2) return -3;

        $activity = $this->query("select name from activity",'DEFAULT','assoc');
        $user = $this->query("select companyname from busiuser where usercode='{$info['usercode']}'",'DEFAULT','assoc');
        return array('flag'=>1,'acname'=>$activity['name'],'company'=>$user['companyname']);
    }

    public function getProductBycode($pcode) {
        $pcode = $this->mysql_escape_string($pcode);
        $product = $this->query("select pname,price from product where pcode='$pcode' and status=1",'DEFAULT','assoc');
        return $product ? $product : -1;
    }

    public function saveBuyRecord($buycode,$record) {
        $buycode = $this->mysql_escape_string($buycode);
        $category = new Category();
        $product = new Product();
        $store = Manager::Model()->getUserDepartmentInfo($_SESSION['usercode']);
        $storecode = $store['dcode'];
        $exist = $this->query("select usercode from applyrecord where buycode='$buycode' and status=1",'DEFAULT','assoc');
        if (!$exist) return -1;
        $time = time(true);
        $date = strtotime(date('Y-m-d'));
        
        $sql = "insert into busirecord(citycode,buycode,storecode,pcode,cid,bid,scode,usercode,pname,price,count,total,billcode,billmoney,opmoney,suppliermoney,storemoney,status,dtime) values";
        $activity = new Activity();
        if (is_array($record)) {
            foreach ($record as $row) {
                $pInfo = $product->getProductById($row['pcode']);
                $cInfo = $category->getBaseInfo($pInfo['bid']);
                $cInfo = $category->getBaseInfo($cInfo['pid']);
                $data = $activity->getSingleRateSetting($pInfo['scode'],$cInfo['id']);
                if (!$data) return -2;
                $total = $row['count'] * $row['price'];
                $opmoney = ($total * $data['operationrate']) / 100;
                $suppliermoney = ($total * $data['supplierrate']) / 100;
                $storemoney = ($total * $data['storerate']) / 100;
                
                //获取门店的城市编码
                $cityInfo = $this->query("select * from departmentcity where dcode ='{$store['dcode']}'",'DEFAULT','assoc');

                $sql .= "('{$cityInfo['citycode']}','$buycode','$storecode','{$row['pcode']}','{$cInfo['id']}','{$pInfo['bid']}','{$pInfo['scode']}','{$exist['usercode']}','{$row['name']}'," .
                "'{$row['price']}','{$row['count']}','$total','{$row['bill']}',{$row['billmoney']},$opmoney,$suppliermoney,$storemoney,1,'$date'),";
            }
        }
        
        //update use status.
        $this->query("update applyrecord set status = 2,usetime=$time where buycode='$buycode'",'DEFAULT','assoc');

        return  $this->query(substr($sql,0,-1),'DEFAULT','assoc');
    }

    public function getConfirmRecords($buycode) {
        $buycode = $this->mysql_escape_string($buycode);
        $activity = new Activity();
        $data = $this->query("select * from busirecord where buycode = '$buycode'",'DEFAULT','all');
        if (!$data) return false;
        foreach($data as $key=>$value) {
            $data[$key]['return'] = $activity->calculate($value['suppliermoney'],$value['storemoney']);
            if ($value['status'] == 2) $data[$key]['status'] = true;
            else $data[$key]['status'] = false;
        }

        //get total
        $all =  $this->query("select id,sum(total)as total,sum(count)as count,sum(suppliermoney) as suppliermoney, sum(opmoney) as opmoney, sum(storemoney) as storemoney from busirecord where buycode = '$buycode' and status=2",'DEFAULT','assoc');
        $all['return'] = $activity->calculate($all['suppliermoney'],$all['storemoney']);
//var_dump();
        //company info.
        $buyInfo = $this->query("select * from applyrecord where buycode = '$buycode'",'DEFAULT','assoc');
        $user = BusiModel::Model()->getBaseInfo($buyInfo['usercode']);

        return array('res'=>$data,'info'=>array('company'=>$user['companyname'],'date'=>date('Y/m/d',$buyInfo['usetime'])),'all'=>$all);
    }

    /*
     * get activity data record list.
     */
    public function getDataList($type,$param='') {
        //get activity setting.
        $activity = new Activity();
        $busi = new BusiModel();
        $setting = $this->query("select name,pic from activity",'DEFAULT','assoc');
        $storeinfo = Manager::Model()->getUserDepartmentInfo($_SESSION['usercode']);

        $total = array();
        $total['return'] = 0;
        $total['total'] = 0;
        $total['cancel'] = 0;
        $total['count'] = 0;
        $total['acname'] = $setting['name'];
        if ($type == 'dlist') {//date
            $result =  $this->query("select sum(total)as total,sum(count)as count,sum(suppliermoney) as suppliermoney, sum(opmoney) as opmoney, sum(storemoney) as storemoney,dtime from busirecord where status = 2 and storecode='{$storeinfo['dcode']}' group by dtime order by dtime desc",'DEFAULT','all');
            //format data
            foreach($result as $key=>$row) {
                $result[$key]['return'] = $activity->calculate($row['suppliermoney'],$row['storemoney']);
                $result[$key]['cancel'] = $this->getCancelMoneyByAttr('dtime',$row['dtime'],$storeinfo['dcode']);
                $result[$key]['date'] = date('Y/m/d',strtotime($row['dtime']));
                $total['return'] += $result[$key]['return'];
                $total['total'] += $row['total'];
                $total['cancel'] += $result[$key]['cancel'];
            }

            return array('data'=>$result,'all'=>$total);//array('aname'=>$setting['name'],'mname'=>$_SESSION['name'],'total'=>$result['total'],'pay'=>$profit);
        }
        else if ($type == 'blist') {//get one day data group by buycode.
            $dtime = $this->mysql_escape_string($param);
            $sql = "select buycode,usercode,sum(total)as total,sum(count)as count,sum(suppliermoney) as suppliermoney, sum(opmoney) as opmoney, sum(storemoney) as storemoney,status from busirecord where dtime = '$dtime' and storecode = '{$storeinfo['dcode']}' group by buycode";
            $res = $this->query($sql,'DEFAULT','all');

            //format data
            $total['date'] = date('Y/m/d',strtotime($dtime));
            foreach ($res as $key=>$row) {
                $userinfo = $busi->getBaseInfo($row['usercode']);
                $res[$key]['return'] = $activity->calculate($row['suppliermoney'],$row['storemoney']);
                $res[$key]['cancel'] = $this->getCancelMoneyByAttr('dtime',$row['dtime'],$storeinfo['dcode']);
                $res[$key]['companyname'] = $userinfo['companyname'];
                
                $total['return'] += $res[$key]['return'];
                $total['total'] += $row['total'];
                $total['cancel'] += $res[$key]['cancel'];
            }

            return array('data'=>$res,'all'=>$total);
        }
        else if ($type == 'bdetails') {//buycode details.
            $buycode = $this->mysql_escape_string($param);
            $sql = "select buycode,usercode,total,pname,billmoney,billcode,count,suppliermoney, opmoney,storemoney,dtime from busirecord where buycode = '$buycode' and status = 2";
            $res = $this->query($sql,'DEFAULT','all');
            //format data
            if ($res && $res[0]) {
                $userinfo = $busi->getBaseInfo($res[0]['usercode']);
                $total['date'] = date('Y/m/d',strtotime($res[0]['dtime']));
                $total['dtime'] = $res[0]['dtime'];
                $total['companyname'] = $userinfo['companyname'];
            }
            else die('-1');

            $total['buycode'] = $buycode;
            foreach ($res as $key=>$row) {
                $userinfo = $busi->getBaseInfo($row['usercode']);
                $res[$key]['return'] = $activity->calculate($row['suppliermoney'],$row['storemoney']);
                $res[$key]['cancel'] = $this->getCancelMoneyByAttr('dtime',$row['dtime'],$storeinfo['dcode']);
                $res[$key]['companyname'] = $userinfo['companyname'];
                
                $total['return'] += $res[$key]['return'];
                $total['total'] += $row['total'];
                $total['cancel'] += $res[$key]['cancel'];
                $total['count'] += $row['count'];
            }
            return array('data'=>$res,'all'=>$total);
        }
    }

    public function getCancelDataList($type,$param='') {
        //get activity setting.
        $activity = new Activity();
        $busi = new BusiModel();
        $setting = $this->query("select name,pic from activity",'DEFAULT','assoc');
        $storeinfo = Manager::Model()->getUserDepartmentInfo($_SESSION['usercode']);

        $total = array();
        $total['return'] = 0;
        $total['total'] = 0;
        $total['cancel'] = 0;
        $total['count'] = 0;
        $total['acname'] = $setting['name'];
        if ($type == 'dlist') {//date
            //get the days which has canceled record.
            $days = $this->query("select dtime from busirecord where storecode='{$storeinfo['dcode']}' and status = 3 group by dtime order by dtime desc",'DEFAULT','all');
            if (empty($days) || empty($days[0]))return array('data'=>$days,'all'=>$total);
            //format data
            foreach($days as $key=>$row) {
                $dayInfo = $this->query("select sum(total)as total,sum(count)as count,sum(suppliermoney) as suppliermoney, sum(opmoney) as opmoney, sum(storemoney) as storemoney,dtime from busirecord where status = 2 and storecode='{$storeinfo['dcode']}' and dtime = '{$row['dtime']}'",'DEFAULT','assoc');
                $days[$key]['total'] = empty($dayInfo['total']) ? 0 : $dayInfo['total'];
                $days[$key]['return'] = $activity->calculate($dayInfo['suppliermoney'],$dayInfo['storemoney']);
                $days[$key]['cancel'] = $this->getCancelMoneyByAttr('dtime',$row['dtime'],$storeinfo['dcode']);
                $days[$key]['date'] = date('Y/m/d',strtotime($row['dtime']));

                $total['return'] += $result[$key]['return'];
                $total['total'] += $row['total'];
                $total['cancel'] += $result[$key]['cancel'];
            }
            return array('data'=>$days,'all'=>$total);//array('aname'=>$setting['name'],'mname'=>$_SESSION['name'],'total'=>$result['total'],'pay'=>$profit);
        }
        else if ($type == 'blist') {
            $dtime = $this->mysql_escape_string($param);
            $sql = "select * from busirecord where dtime = '$dtime' and storecode = '{$storeinfo['dcode']}'";
            $all = $this->query($sql,'DEFAULT','all');
            $result = array();
            foreach ($all as $key=>$row) {
                $record = array();
                if ($row['status'] == 2) {
                    $total['total'] += $row['total'];
                    $total['count'] += $row['count'];
                    $total['return'] += $activity->calculate($row['suppliermoney'],$row['storemoney']);
                }
                else if ($row['status'] == 3) {
                    $userinfo = $busi->getBaseInfo($row['usercode']);
                    $record['companyname'] = $userinfo['companyname'];
                    $record['buycode'] = $row['buycode'];
                    $record['pname'] = $row['pname'];
                    $record['count'] = $row['count'];
                    $record['total'] = $row['total'];
                    $record['return'] = $activity->calculate($row['suppliermoney'],$row['storemoney']);
                    $record['billcode'] = $row['billcode'];
                    $record['billmoney'] = $row['billmoney'];
                    $record['canceldate'] = date('Y/m/d',strtotime($row['canceltime']));;
                    $record['cancel'] = $row['total'];
                    $total['cancel'] += $row['total'];
                    array_push($result,$record);
                }
            }
            return array('data'=>$result,'all'=>$total);
        }
    }

    public function confirmBill($buycode) {
        $buycode = $this->mysql_escape_string($buycode);
        $exist = $this->query("select * from busirecord where buycode = '$buycode'",'DEFAULT','all');
        if (!$exist)return -1;
        $this->query("update busirecord set status = 2 where buycode = '$buycode'",'DEFAULT','assoc');
        return 1;
    }
    public function getStoreNameByCode($code) {
        $data = $this->query("select name from store where storecode='$code'",'DEFAULT','assoc');
        if ($data) return $data['name'];
    }

    public function getCancelMoneyByAttr($key,$value,$dcode) {
        $res = $this->query("select sum(total)as total from busirecord where status = 3 and storecode = '$dcode' and $key = '$value'",'DEFAULT','assoc');
        if ($res && $res['total']) return $res['total'];
        else return 0;
    }

    public function cancelBuyRecord($id) {
        return $this->query("update busirecord set status = 3, canceltime = now() where id=$id",'DEFAULT','assoc');
    }
}