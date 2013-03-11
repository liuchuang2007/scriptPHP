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
				if (!empty(Application::$app->mysql)) {
					$res = Application::$app->mysql;
				}
			break;
			
			case 'memcache':
				if (!empty(Application::$app->memcache)) {
					$res = Application::$app->memcache;
				}
			break;
			
			case 'mongodb':
				if (!empty(Application::$app->mongodb)) {
					$res = Application::$app->mongodb;
				}
			break;

			default:
				if (!empty(Application::$app->db)) {
					$res = Application::$app->db;
				}
			break;
		}

		return $res;
	}
}

?>