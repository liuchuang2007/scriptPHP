<?php

require_once('class.db.php');

require_once('class.routing.php');

//require_once('class.memcached-client.php');
/**
* DB代理类
* ver: 1.0.1
* 作者: zwj
* 最后更新日期: 2012-02-20
*/
class AgentDB extends RoutRule{
	public $mydb;
	private static $_newmem = null;
	private static $_newmon = null;
	private $flag_spl_rw;//是否启动读写分离过滤
	function __construct($_spl_rw=true){
		$this->flag_spl_rw = $_spl_rw;
	}

	function __destruct(){
		unset($mydb);
	}

	//$returntype:''=>返回游标集;'row'=>返回一行;'all'=>返回数组;'assoc'=>未知;
	protected function query($sql = '',$conntype='DEFAULT',$returntype='',$citycode=''){
//		array('tab'=>
//			  'where' =>
//			  'field' => 
//			  'sort' =>
//			  'limit' => 
//			);
		$opr = "W";
		if($this->flag_spl_rw)
		{
			//根据sql语句判断是指向主还是从
			preg_match("/^(\s*)select/i", $sql) && $opr = "R";
		}

		
		$arrcon = $this->get_conn_source($conntype,$opr,$citycode='');

		$this->mydb = new MyDb($arrcon['host'],$arrcon['user'],$arrcon['pass'],$arrcon['db']);
		
		$res = $this->mydb->query($sql,$returntype);

		//$this->data_unset();
		return $res;
	}


	protected function get_one($sql,$conntype='DEFAULT')
	{
		//判断是否select语句，不是则返回错误
		if(!preg_match ("/^(\s*)select/i", $sql)){
			return "SQL ERROR!";
		}
		$opr = "W";
		$this->flag_spl_rw ==true && $opr = "R";

		$arrcon = $this->get_conn_source($conntype,$opr);
		//return $arrcon;
		$this->mydb = new MyDb($arrcon['host'],$arrcon['user'],$arrcon['pass'],$arrcon['db']);

		$res = $this->mydb->query($sql,'row');
		
		$this->data_unset();

		return $res;
	}

	function data_unset()
	{
		unset($this->mydb);
	}
	
	protected function insert($tab,$arr,$type='single',$getlastid=false,$conntype='DEFAULT',$showsql=false){
		$opr = "W";
		$arrcon = $this->get_conn_source($conntype,$opr);
		$this->mydb = new MyDb($arrcon['host'],$arrcon['user'],$arrcon['pass'],$arrcon['db']);

		$res = $this->mydb->insert($tab,$arr,$type,$showsql);
		//只有执行成功才执行返回最后id的操作
		if($res)
		{
			$getlastid && $res = $this->mydb->last_insert_id();
		}
		$this->data_unset();
		return $res;
	}
	
	protected function update($tab,$id,$arr,$conntype='DEFAULT',$showsql=false){
		$opr = "W";

		$arrcon = $this->get_conn_source($conntype,$opr);

		$this->mydb = new MyDb($arrcon['host'],$arrcon['user'],$arrcon['pass'],$arrcon['db']);

		$res = $this->mydb->update($tab,$id,$arr,$showsql);

		$this->data_unset();
		return $res;
	}
	protected function newMem($conntype='memcache') {

			$arrcon = $this->get_conn_source($conntype,'R');

			if(null === self::$_newmem){
				self::$_newmem = new memcache;
				self::$_newmem -> connect($arrcon['host'],$arrcon['port']);
			}

			return self::$_newmem;
	}

	protected function newMongo($conntype='mongomc') {

			$arrcon = $this->get_conn_source($conntype,'R');

			if(null === self::$_newmon){
				self::$_newmon = new Mongo($arrcon['host']);
			}

			return self::$_newmon->$arrcon['db'];
	}
	/**
	 * mongoQuery
	 * 
	 * @Describe : mongo查询
	 * @Parm : $tab 表
	 * @Parm : $where array('字段'=>条件数组)
	 * @Parm : $fields array('字段',...)
	 * @Parm : $limit array(开始,条数)
	 * @Return : data
	 * @Author : zwj 
	 * @DataTime : 2012-03-07
	 */
	protected function mongoQuery($db='mongomc',$tab,$where,$fields=null,$sort=null,$limit=null,$total = null) {
			try{
			$count = 0;
			$data = array();
			$collection = self::newMongo($db)->selectCollection($tab);
			if($collection == null){
				return $data;
			}
			if(!empty($fields)){
				$cursor = $collection->find($where,$fields);
			}else{
				$cursor = $collection->find($where);
			}
			if(!empty($total)){
				$total = $cursor -> count();
			}
			if(!empty($sort)){
				$cursor->sort($sort);
			}
			if(!empty($limit)){
				if(isset($limit[0]) && $limit[0] > 0){
					$cursor->skip($limit[0]);
				}
				if(isset($limit[1]) && $limit[1] > 0){
					$cursor->limit($limit[1]);
				}
			}
			while($cursor->hasNext()){
				$item = $cursor->getNext();
				unset($item['_id']);
				$data[$count++] = $item;
			}
			return array('ret' => $data, 'total'=>$total);
		}
		catch(MongoCursorException $e){
			return "errcode:" . $e->getCode() . " errmsg:" . $e->getMessage();
		}
		
	}
	/**
	 * mongoUpdate
	 * 
	 * @Describe : mongo更新
	 * @Parm : $tab 表
	 * @Parm : $where array('字段'=>条件数组)
	 * @Parm : $updata array('字段'=>'新数据')
	 * @Return : true
	 * @Author : zwj 
	 * @DataTime : 2012-03-07
	 */
	protected function mongoUpdate($db='mongomc',$tab,$updata,$where,$affectall=false) {
		try{

			$collection = self::newMongo($db)->selectCollection($tab);

			if($collection == null){
				return false;
			}
			$data = array('$set'=>$updata);
		
			return $collection->update($where,$data,array('multiple' => $affectall,'safe' => true));

		}catch(MongoCursorException $e){
		
			return "errcode:" . $e->getCode() . " errmsg:" . $e->getMessage();
		
		}
	}

	/**
	 * mongoAdd
	 * 
	 * @Describe : mongo累加
	 * @Parm : $tab 表
	 * @Parm : $where array('字段'=>条件数组)
	 * @Parm : $updata array('字段'=>'新数据')
	 * @Return : true
	 * @Author : zwj 
	 * @DataTime : 2012-03-07
	 */
	protected function mongoAdd($db='mongomc',$tab,$updata,$where) {
		try{
//			self::newMongo()->createCollection($tab);
			$collection = self::newMongo($db)->selectCollection($tab);
			if($collection == null){
				return false;
			}
			$data = array('$inc'=>$updata);
		
			return $collection->update($where,$data,array('multiple' => $affectall,'safe' => true));

		}catch(MongoCursorException $e){
		
			return "errcode:" . $e->getCode() . " errmsg:" . $e->getMessage();
		
		}
	}
	/**
	 * mongoInsert
	 * 
	 * @Describe : mongo写入
	 * @Parm : $tab 表
	 * @Parm : $data array('字段'=>'数据',.....)
	 * @Return : true
	 * @Author : zwj 
	 * @DataTime : 2012-03-07
	 */
	protected function mongoInsert($db='mongomc',$tab,$data) {

		try{
			$collection = self::newMongo($db)->selectCollection($tab);
			if($collection != null){
				$ret = $collection->batchInsert($data,array("safe" => true));
				return ($ret['ok'] == 1);
			}
			return false;
		}catch(MongoCursorException $e){
			return "errcode:" . $e->getCode() . " errmsg:" . $e->getMessage();
		}

	}
	/**
	 * mongoDelete
	 * 
	 * @Describe : mongo删除
	 * @Parm : $tab 表
	 * @Parm : $data array('字段'=>'数据',.....)
	 * @Return : true
	 * @Author : zwj 
	 * @DataTime : 2012-03-07
	 */
	protected function mongoDelete($db='mongomc',$tab,$where) {

		$collection = self::newMongo($db)->selectCollection($tab);

		$options = array("justOne"=>false,"safe"=>true);

		if($collection != null){
			try{
				$ret = $collection->remove($where,$options);
				return ($ret['ok'] == 1);
			}catch(MongoCursorException $e){
				return "errcode:" . $e->getCode() . " errmsg:" . $e->getMessage();
			}
		}
		return false;
	}

	protected function mysql_escape_string($str) {
		$opr = "W";
		$arrcon = $this->get_conn_source("DEFAULT",$opr);
		$this->mydb = new MyDb($arrcon['host'],$arrcon['user'],$arrcon['pass'],$arrcon['db']);
		return $this->mydb->escape_string($str);
	}
}

?>