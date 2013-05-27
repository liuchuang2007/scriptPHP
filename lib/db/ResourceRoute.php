<?php
/**
 *@description: DBRoute 
 *@author: liuchuang
 *@Date:2013-03-03
 */
class ResourceRoute {
	/**
	 *@description: get the connection resource
	 *@param: $type mysql, memcached, mongodb
	 */
	protected function get_conn_source($type) {
		switch($type) {
			case 'mysql':
				if (!empty(App::$app->mysql)) {
					$res = App::$app->mysql;
				}
			break;
			
			case 'memcache':
				if (!empty(App::$app->memcache)) {
					$res = App::$app->memcache;
				}
			break;
			
			case 'mongodb':
				if (!empty(App::$app->mongodb)) {
					$res = App::$app->mongodb;
				}
			break;

			default:
				if (!empty(App::$app->db)) {
					$res = App::$app->db;
				}
			break;
		}

		return $res;
	}
}

?>