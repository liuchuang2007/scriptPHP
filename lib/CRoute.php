<?php
/**
* DB资源类
* 作者: zwj
* 最后更新日期:2012-02-20
*/
interface IDBClass
{
	function get_conn_source();

}
class RoutRule{

	protected function get_conn_source($type,$opr="W",$citycode='')
	{
		switch($type)
		{
			case "mysql"://获取mysql配置
					$arr = array(
						'M'=>array(
								'host'=>'172.168.1.19',
								'user'=>'george',
								'pass'=>'123',
								'db'=>'zizhu3'
							),
						'S'=>array(
							'0'=>array(
								'host'=>'172.168.1.19',
								'user'=>'george',
								'pass'=>'123',
								'db'=>'zizhu3'
							)
						)
						/*'M'=>array(
							'host'=>'172.168.1.70:4001',//
							'user'=>'yougou_mg', //
							'pass'=>'aEAdFBJx6E2XjHUx',//
							'db'=>'yougou'//
						),
						'S'=>array(
							'0'=>array(
								'host'=>'172.168.1.70:4001',
								'user'=>'yougou_mg',
								'pass'=>'aEAdFBJx6E2XjHUx',
								'db'=>'yougou'
							)
						)*/
					);
			break;
			default:
				$arr = array(
					/*'M'=>array(
						'host'=>'172.168.1.70:4001',//
						'user'=>'yougou_mg', //
						'pass'=>'aEAdFBJx6E2XjHUx',//
						'db'=>'yougou'//
					),
					'S'=>array(
						'0'=>array(
							'host'=>'172.168.1.70:4001',
							'user'=>'yougou_mg',
							'pass'=>'aEAdFBJx6E2XjHUx',
							'db'=>'yougou'
						)
					)*/
					'M'=>array(
								'host'=>'172.168.1.19',
								'user'=>'george',
								'pass'=>'123',
								'db'=>'zizhu3'
							),
					'S'=>array(
							'0'=>array(
								'host'=>'172.168.1.19',
								'user'=>'george',
								'pass'=>'123',
								'db'=>'zizhu3'
							)
					)
			);
			break;
		}

		if($opr == "W")
		{
			$res = $arr['M'];
		}
		else
		{
			//随机取一个从机地址
			is_array($arr['S']) && $res = $arr['S'][array_rand($arr['S'])];
		}
		return $res;
	}

	
//	/**
//    * memcache 配置
//    *
//    * @return  int
//    * @access  public
//    */
//	protected function get_memcached_conf()
//	{	
//		$arrconf = array(
//               'servers' => array('172.168.1.222:11211'),
//               'debug'   => false,
//               'compress_threshold' => 10240,
//               'persistant' => false);
//		return $arrconf;
//	}
//	/**
//    * 获取memcached的缓存时间
//    *
//    * @return  void
//    * @access  public
//    */
//	protected function get_memcached_exp()
//	{
//		$exp = 86400;
//		return $exp;
//	}
//
//	/**
//    * mongo 配置
//    *
//    * @return  void
//    * @access  public
//    */
//	protected function get_mongo_conf()
//	{
//		$mongoconf = array(
//			'host' => '172.168.1.222:27017',
//			'db' => 'ipservice'
//		);
//		return $mongoconf;
//	
//	}
}

?>