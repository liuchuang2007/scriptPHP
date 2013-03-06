<?php
/*
 * @description: activity info setting and query.
 * @Author:liuchuang
 * @Date: 2012-07-12
 */
class Supplier extends AgentDB {
    public static function Model() {
         return new Supplier();
    }

    public function addNewSupplier($name,$brand,$dcode) {
        $name = $this->mysql_escape_string($name);
        $user = new User();
        $depart = Manager::Model()->getUserDepartmentInfo($dcode,'dcode');
        $scode = $user->newUsercode(SUPPLIER_CODE,Application::$app->usercodelen);

        $sql = "insert into suppliercate(scode,bid,name,status) values ";
        foreach ($brand as $item) {
            if (!is_numeric($item))die('-1');
            $sql .= "('$scode',$item,'$name',1),";
        }
        $this->query(trim($sql,','),'DEFAULT','assoc');

        //supplierrate = 0. only storerate is effective.
        $rate = $this->query("select operationrate,storerate from ratesetting",'DEFAULT','assoc');
        if (!$rate) {
            $rate = Activity::Model()->getActivityInfo();
        }
        $sql = "insert into ratesetting(scode,cid,supplierrate,operationrate,storerate,ctime) values ";
        foreach ($brand as $item) {
            $cid = $this->query("select pid from category where id = '$item'",'DEFAULT','assoc');
            $sql .= "('$scode','{$cid['pid']}',0,'{$rate['operationrate']}','{$rate['storerate']}',unix_timestamp()),";
        }
        
        $this->query(trim($sql,','),'DEFAULT','assoc');
        $this->query("insert into supplier(scode,pbranch,pregion,pbase,name,status,ctime) values('$scode','$dcode','{$depart['pregion']}','{$depart['pbase']}','$name',1,unix_timestamp())",'DEFAULT','assoc');
        return 1;
    }

    public function getAllSupplier($type,$dcode) {
        if ($type == BRANCH_TYPE) {
            return $this->query("select * from supplier where pbranch = '$dcode'",'DEFAULT','all');
        }
    }

    public function getSupplierInfo($scode) {
        return $this->query("select * from supplier where scode = '$scode'",'DEFAULT','assoc');
    }
}