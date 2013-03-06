<?php
class CenterModel extends AgentDB {
    public function getReturnStat($type,$param='') {
        $activity = new Activity();

        $total['total'] = 0;
        $total['return'] = 0;
        $total['cancel'] = 0;

        if ($type == 'search') {
            $stype = $this->mysql_escape_string($param['stype']);
            $citycode = $this->mysql_escape_string($param['citycode']);
            $result = array();
            if ($stype == 'lweek') {
                $time = $activity->getTimeLine('lweek');
                $lastday = array_pop($time);
                $sub = " and dtime <= '$lastday' and dtime >= '{$time[0]}'";
            }
            else if ($stype == 'lmonth'){
                $time = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time);
                $sub = " and dtime <= '$lastday' and dtime >= '{$time[0]}'";
            }
            else {
                $time = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time);
                $sub = " and dtime <= '$lastday'";
            }
            if ($citycode) {
               $sub .= " and citycode = '$citycode'";
            }

            //get all base department.
            $type = BASE_TYPE;
            $base = $this->query("select a.dcode,name from department a left join manager b on a.dcode = b.dcode where type = '$type'",'DEFAULT','all');
            $total['links'] = Utils::Model()->getSepLinkString(count($base));
            $total['stype'] = $stype;
            $total['citycode'] = $citycode;
            $total['param'] = json_encode(array('stype'=>$stype,'citycode'=>$citycode));//save the param for separator.
            
            //get limited rows.
            $offset = ($param['page'] - 1) * Application::$app->perPage;
            $base = array_slice($base,$offset,Application::$app->perPage);
            foreach($base as $item) {
                $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from (select dcode from department where pbase='{$item['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 2 $sub";

                $row = $this->query($sql,'DEFAULT','assoc');
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['name'] = $item['name'];
                $row['dcode'] = $item['dcode'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                
                //get cancel
                $sql = "select sum(total)as total from (select dcode from department where pbase='{$item['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 3 $sub";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                $total['cancel'] += $row['cancel'];
                $total['total'] += $row['total'];
                $total['return'] += $row['return'];
                array_push($result,$row);
            }
        }
        else if ($type == 'csearch') {
            $stype = $this->mysql_escape_string($param['stype']);
            $citycode = $this->mysql_escape_string($param['citycode']);
            $dcode = $this->mysql_escape_string($param['dcode']);
            $cate = $this->mysql_escape_string($param['cate']);
            
            //validate dcode;
            $dInfo = Manager::Model()->getUserDepartmentInfo($dcode,'dcode');
            if (!$dInfo['name']) die('-1');

            //init time
            if ($stype == 'lweek') {
                $time = $activity->getTimeLine('lweek');
                $lastday = array_pop($time);
                $sub = " and dtime <= '$lastday' and dtime >= '{$time[0]}'";
            }
            else if ($stype == 'lmonth'){
                $time = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time);
                $sub = " and dtime <= '$lastday' and dtime >= '{$time[0]}'";
            }
            else {
                $time = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time);
                $sub = " and dtime <= '$lastday'";
            }
            if ($citycode) {
               $sub .= " and citycode = '$citycode'";
            }

            //save params
            $total['dcode'] = $dcode;
            $total['stype'] = $stype;
            $total['cate'] = $cate;
            $total['citycode'] = $citycode;

            //validate cate.
            if ($citycode) $citycondition = " and citycode = '$citycode'";
            $cInfo = Category::Model()->getBaseInfo($cate);
            
            $result = array();
            if (!empty($cInfo['type']) && 1 == $cInfo['type']) {
                $sql = "select sum(total)as total,cid,sum(suppliermoney)as smoney,sum(storemoney)as storemoney from `busirecord` a left join department c on a.storecode = c.dcode where c.pbase = '$dcode' and a.cid = '$cate' and a.status = 2 $citycondition $sub";
                $row = $this->query($sql,'DEFAULT','assoc');
                $row['name'] = $cInfo['name'];
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);

                //get cancel data.
                $sql = "select sum(total)as total,cid,sum(suppliermoney)as smoney,sum(storemoney)as storemoney from `busirecord` a left join department c on a.storecode = c.dcode where c.pbase = '$dcode' and a.cid = '$cate' and a.status = 3 $citycondition $sub";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                //totol.
                $total['cancel'] += $row['cancel'];
                $total['total'] += $row['total'];
                $total['return'] += $row['return'];
                array_push($result, $row);
            }
            else { //default get all category stat.
                //get all category record
                $cates = $this->query('select * from category where type = 1','DEFAULT','all');
                $total['links'] = Utils::Model()->getSepLinkString(count($cates));
                $total['param'] = json_encode(array('stype'=>$stype,'citycode'=>$citycode,'dcode'=>$dcode,'cate'=>$cate));//save the param for separator.

                foreach ($cates as $item) {
                    $sql = "select sum(total)as total,cid,sum(suppliermoney)as suppliermoney,sum(storemoney)as storemoney from busirecord a  left join department c on a.storecode = c.dcode where c.pbase = '$dcode' and a.status = 2 and cid = '{$item['id']}' $citycondition $sub";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    
                    $cate = $this->query("select * from category where id = '{$item['id']}'",'DEFAULT','assoc');
                    $row['name'] = $cate['name'];
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    
                    //get cancel data.
                    $sql = "select sum(total)as total,cid,sum(suppliermoney)as suppliermoney,sum(storemoney)as storemoney from busirecord a  left join department c on a.storecode = c.dcode where c.pbase = '$dcode' and a.status = 3 and cid = '{$item['id']}' $citycondition $sub";
                    $cancel = $this->query($sql,'DEFAULT','assoc');
                    $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];
                    
                    //totol.
                    $total['cancel'] += $row['cancel'];
                    $total['total'] += $row['total'];
                    $total['return'] += $row['return'];
                
                    array_push($result, $row);
                }
                $offset = ($param['page'] - 1) * Application::$app->perPage;
                $result = array_slice($result,$offset,Application::$app->perPage);
            }

        }
        else if ($type == 'bsearch') {
            $stype = $this->mysql_escape_string($param['stype']);
            $citycode = $this->mysql_escape_string($param['citycode']);
            $dcode = $this->mysql_escape_string($param['dcode']);
            $cate = $this->mysql_escape_string($param['cate']);
            $brand = $this->mysql_escape_string($param['brand']);
            
            //validate dcode;
            $dInfo = Manager::Model()->getUserDepartmentInfo($dcode,'dcode');
            if (!$dInfo['name']) die('-1');

            //init time
            if ($stype == 'lweek') {
                $time = $activity->getTimeLine('lweek');
                $lastday = array_pop($time);
                $sub = " and dtime <= '$lastday' and dtime >= '{$time[0]}'";
            }
            else if ($stype == 'lmonth'){
                $time = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time);
                $sub = " and dtime <= '$lastday' and dtime >= '{$time[0]}'";
            }
            else {
                $time = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time);
                $sub = " and dtime <= '$lastday'";
            }
            if ($citycode) {
               $sub .= " and citycode = '$citycode'";
            }

            //save params
            $total['dcode'] = $dcode;
            $total['stype'] = $stype;
            $total['cate'] = $cate;
            $total['brand'] = $brand;
            $total['citycode'] = $citycode;

            //city condition
            if ($citycode) $citycondition = " and citycode = '$citycode'";
            
            //validate cate.
            $cInfo = Category::Model()->getBaseInfo($cate);
            if (!$cInfo)die('-1');
            
            //validate brand.
            $bInfo = Category::Model()->getBaseInfo($brand);

            $result = array();
            if (!empty($bInfo['type']) && 2 == $bInfo['type']) {
                $sql = "select sum(total)as total,bid,sum(suppliermoney)as smoney,sum(storemoney)as storemoney from `busirecord` a left join department c on a.storecode = c.dcode where c.pbase = '$dcode' and a.cid = '$cate' and a.bid = '$brand' and a.status = 2 $citycondition $sub";
                $row = $this->query($sql,'DEFAULT','assoc');
                $row['name'] = $bInfo['name'];
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);

                //get cancel data.
                $sql = "select sum(total)as total,cid,sum(suppliermoney)as smoney,sum(storemoney)as storemoney from `busirecord` a left join department c on a.storecode = c.dcode where c.pbase = '$dcode' and a.cid = '$cate' and a.bid = '$brand' and a.status = 3 $citycondition $sub";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                //totol.
                $total['cancel'] += $row['cancel'];
                $total['total'] += $row['total'];
                $total['return'] += $row['return'];
                array_push($result, $row);
            }
            else { //default get all category stat.
                //get all category record
                $brands = $this->query('select * from category where type = 2','DEFAULT','all');
                $total['links'] = Utils::Model()->getSepLinkString(count($brands));
                $total['param'] = json_encode(array('stype'=>$stype,'citycode'=>$citycode,'dcode'=>$dcode,'cate'=>$cate,'brand'=>$brand));//save the param for separator.

                foreach ($brands as $item) {
                    $sql = "select sum(total)as total,cid,sum(suppliermoney)as suppliermoney,sum(storemoney)as storemoney from busirecord a  left join department c on a.storecode = c.dcode where c.pbase = '$dcode' and a.status = 2 and cid = '$cate' and a.bid = '{$item['id']}' $citycondition $sub";
                    $row = $this->query($sql,'DEFAULT','assoc');
                    
                    $brand = $this->query("select * from category where id = '{$item['id']}'",'DEFAULT','assoc');
                    $row['name'] = $brand['name'];
                    $row['total'] = empty($row['total']) ? 0 : $row['total'];
                    $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                    
                    //get cancel data.
                    $sql = "select sum(total)as total,cid,sum(suppliermoney)as suppliermoney,sum(storemoney)as storemoney from busirecord a  left join department c on a.storecode = c.dcode where c.pbase = '$dcode' and a.status = 3 and cid = '$cate' and a.bid = '{$item['id']}' $citycondition $sub";
                    $cancel = $this->query($sql,'DEFAULT','assoc');
                    $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];
                    
                    //totol.
                    $total['cancel'] += $row['cancel'];
                    $total['total'] += $row['total'];
                    $total['return'] += $row['return'];
                
                    array_push($result, $row);
                }
                $offset = ($param['page'] - 1) * Application::$app->perPage;
                $result = array_slice($result,$offset,Application::$app->perPage);
            }
        }
        return array('data'=>$result,'all'=>$total);
    }

    public function getOperationStat() {
        $activity = new Activity();

        $total['total'] = 0;
        $total['return'] = 0;
        $total['cancel'] = 0;

        if ($type == 'search') {
            $stype = $this->mysql_escape_string($param['stype']);
            $citycode = $this->mysql_escape_string($param['citycode']);
            $result = array();
            if ($stype == 'lweek') {
                $time = $activity->getTimeLine('lweek');
                $lastday = array_pop($time);
                $sub = " and dtime <= '$lastday' and dtime >= '{$time[0]}'";
            }
            else if ($stype == 'lmonth'){
                $time = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time);
                $sub = " and dtime <= '$lastday' and dtime >= '{$time[0]}'";
            }
            else {
                $time = $activity->getTimeLine('lmonth');
                $lastday = array_pop($time);
                $sub = " and dtime <= '$lastday'";
            }
            if ($citycode) {
               $sub .= " and citycode = '$citycode'";
            }

            //get all base department.
            $type = BASE_TYPE;
            $base = $this->query("select a.dcode,name from department a left join manager b on a.dcode = b.dcode where type = '$type'",'DEFAULT','all');
            $total['links'] = Utils::Model()->getSepLinkString(count($base));
            $total['stype'] = $stype;
            $total['citycode'] = $citycode;
            $total['param'] = json_encode(array('stype'=>$stype,'citycode'=>$citycode));//save the param for separator.
            
            //get limited rows.
            $offset = ($param['page'] - 1) * Application::$app->perPage;
            $base = array_slice($base,$offset,Application::$app->perPage);
            foreach($base as $item) {
                $sql = "select sum(total)as total,sum(suppliermoney) as suppliermoney,sum(storemoney) as storemoney from (select dcode from department where pbase='{$item['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 2 $sub";

                $row = $this->query($sql,'DEFAULT','assoc');
                $row['total'] = empty($row['total']) ? 0 : $row['total'];
                $row['name'] = $item['name'];
                $row['dcode'] = $item['dcode'];
                $row['return'] = $activity->calculate($row['suppliermoney'], $row['storemoney']);
                
                //get cancel
                $sql = "select sum(total)as total from (select dcode from department where pbase='{$item['dcode']}') a, busirecord b where b.storecode = a.dcode and b.status = 3 $sub";
                $cancel = $this->query($sql,'DEFAULT','assoc');
                $row['cancel'] = empty($cancel['total']) ? 0 : $cancel['total'];

                $total['cancel'] += $row['cancel'];
                $total['total'] += $row['total'];
                $total['return'] += $row['return'];
                array_push($result,$row);
            }
        }
    }
}