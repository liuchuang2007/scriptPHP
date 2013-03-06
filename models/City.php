<?php
/*
 * @description: city information setting and query.
 * @Author:liuchuang
 * @Date: 2012-07-12
 */
class City extends AgentDB {
    public static $class_name = 'City';
    public static function Model() {
         return new City();
    }

    public function getCityNameBycode($citycode) {
        if (!is_numeric($citycode)) return '';
        $data = $this->query("select name from city where code = '$citycode'",'DEFAULT','assoc');
        return empty($data['name']) ? '' : $data['name'];
    }

    public function getCityInfo($citycode) {
        return $this->query("select * from city where code = '$citycode'",'DEFAULT','assoc');
    }
    public function getNamesByCodes($str) {
        $str = trim ($str,',');
        $str = $this->mysql_escape_string($str);
        if (empty($str)) return '';
        $data = $this->query("select name from city where code in ($str)",'DEFAULT','all');
        foreach($data as $row) {
           $cities .= $row['name'] . ',';
        }
        return empty($cities) ? '' : $cities;
    }

/*    public function getValidLeftCities() {
        $sql = 'select citycode from manager where type = 3';
        $cities = $this->query($sql,'DEFAULT','all');
        foreach ($cities as $item) {
            if ($item['citycode']) {
                $str .= trim($item['citycode'],',') . ',';
            }
        }
        $str = trim($str,',');
        if ($str) {
            $sql = "select citycode,cityname from city where citycode not in ($str)";
        }
        else {
            $sql = "select citycode,cityname from city";
        }

        return $this->query($sql,'DEFAULT','all');
    }*/
    public function getDepartmentCities($dcode) {
        $dcode = $this->mysql_escape_string($dcode);
        return $this->query("select * from departmentcity where dcode = '$dcode'",'DEFAULT','all');
    }

    public function getDepartmentProvince($dcode) {
        $dcode = $this->mysql_escape_string($dcode);
        return $this->query("select a.pcode,b.name from departmentcity a, city b where a.pcode = b.code and dcode = '$dcode' group by a.pcode",'DEFAULT','all');
    }

    public function getAllProvinces() {
        return $this->query('select * from city where pcode = 1 and status = 1','DEFAULT','all');
    }
}