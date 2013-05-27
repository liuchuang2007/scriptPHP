<?php
/**
 * @description: website default module etc.
 * @Author:liuchuang
 * @Date: 2013-03-07
 */
class User extends MysqlRes{
	public $_table = 'user';
    public function getItemByKey($key) {
		$key = $this->escape_string($key);
		return $this->queryBySql('SELECT * FROM ' . $this->table() . " WHERE save_key = '$key'",'assoc');
    }
}