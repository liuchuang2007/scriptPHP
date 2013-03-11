<?php
/**
 *@description: MemRes 
 *@author: liuchuang
 *@Date:2013-03-03
 */
class MemRes  extends ResourceRoute {
	private static $mem;
	public function __construct() {
	}

	public function newMem($conntype='memcache') {

		$arrcon = $this->get_conn_source('memcache');

		if (null === self::$mem) {
			self::$mem = new Memcache;
			self::$mem ->connect($arrcon['host'], $arrcon['port']);
		}

		return self::$mem;
	}
}
