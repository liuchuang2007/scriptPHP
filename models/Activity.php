<?php
/*
 * @description: activity info setting and query.
 * @Author:liuchuang
 * @Date: 2012-07-12
 */
class Activity extends AgentDB {
    public static function Model() {
         return new Activity();
    }

    public function getActivityInfo() {
        $sql = "select * from activity";
        return $this->query($sql,'DEFAULT','assoc');
    }

    public function updateActivityInfo($fields) {
        return $this->update('activity', '', $fields,'DEFAULT');
    }

    public function getSingleRateSetting($scode, $cid) {
        $scode = $this->mysql_escape_string($scode);
        $cid = $this->mysql_escape_string($cid);
        $sql = "select supplierrate,storerate,operationrate from ratesetting where scode = '$scode' and cid = '$cid'";
        return $this->query($sql,'DEFAULT','assoc');
    }

    public function getProductReturnRate($pcode) {
        $pcode = $this->mysql_escape_string($pcode);
        $res = $this->query("select scode,bid from product where pcode='$pcode'",'DEFAULT','assoc');
        if (!$res)return false;
        $sql = "select scode,cid,supplierrate,operationrate,storerate from ratesetting where scode='{$res['scode']}' and cid = {$res['cid']}";
        return $this->query($sql,'DEFAULT','assoc');
    }

    //deprecated.
    public function calculateMoney($data) {
       if (!is_array($data))return false;
       foreach ($data as $key=>$row) {
           $data[$key]['money'] = $row['suppliermoney'] + $row['storemoney'];
       }
       return $data;
    }

    public function calculate($suppliermoney,$storemoney) {
        return $suppliermoney + $storemoney;
    }

    public function getACApplySetting() {
        return $this->query('select * from applysetting','DEFAULT','all');
    }
    public function updateApplysetting($applysetting) {
        foreach ($applysetting as $key=>$value) {
            $key = $this->mysql_escape_string($key);
            $value = trim($value);
            if (!is_numeric($value))return 0;
            $this->query("update applysetting set distance = '$value' where name = '$key'",'DEFAULT','assoc');
        }
        return 1;
    }
    public function getTimeLine($type,$time='') {
         $result = array();
         if ($type == 'lweek') {
             $start = mktime(0,0,0, date('m'), date('d')-date('N')-6,date('Y'));
             $end  = mktime(23,59,59, date('m'), date('d')-date('N'),date('Y'));
             while($start <= $end) {
                 array_push($result,$start);
                 $start += 24 * 3600;
             }
         }
         else if ($type == 'lmonth') {
             $year =  date('Y');
             $mon = date('m');
             $end =  mktime(23,59,59, $mon, -1, $year);
             $start = mktime(0,0,0, $mon-1, 1, $year);
             while($start <= $end) {
                 array_push($result,$start);
                 $start += 24 * 3600;
             }
         }
         else if ($type == 'permonth') {
             $setting = $this->getActivityInfo();
             $starttime = $setting['starttime'];
             $month = date('m',$starttime);
             $year = date('Y',$starttime);
             $currtime = mktime(0,0,0, date('m'),1,date('Y'));
             $i = 0;
             while ($starttime < $currtime) {
                 $starttime = mktime(0,0,0, $month + $i,1,$year);
                 $endtime = mktime(23,59,59, $month + $i + 1,0,$year);
                 //$row['start'] = date('Y-m-d',$starttime);
                 //$row['end'] = date('Y-m-d',$endtime);
                 $row['start'] = $starttime;
                 $row['end'] = $endtime;
                 array_push($result,$row);
                 $i++;
             }
             //array_pop($result);
         }
         else if ($type == 'onemonth') {
             $year = date('Y',$time);
             $mon = date('m',$time);
             $start =  mktime(0,0,0, $mon, 1, $year);
             $end = mktime(23,59,59, $mon+1, 0, $year);
             while($start <= $end) {
                 array_push($result,$start);
                 $start += 24 * 3600;
             }
         }
         return $result;
    }

    public function closeOnSpecificDay($dates) {
        $sql = "insert into closedate(date,status) values ";
        foreach ($dates as $item) {
            $sql .= "('$item',1),";
        }
        $sql = substr($sql,0,-1);
        return $this->query($sql,'DEFAULT','assoc');
    }
    
    public function getAllIndustry() {
        return $this->query('select * from industry','DEFAULT','all');
    }
}