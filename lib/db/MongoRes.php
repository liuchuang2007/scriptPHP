<?php
/**
 *@description: MongoRes 
 *@author: liuchuang
 *@Date:2013-03-03
 */
class MongoRes  extends ResourceRoute {
	public static $_newmon;
	public function __construct() {
		$this->newMongo();
	}

	protected function newMongo($conntype='mongodb') {

			$arrcon = $this->get_conn_source($conntype);

			if (null === self::$_newmon) {
				self::$_newmon = new Mongo($arrcon['host']);
			}

			return self::$_newmon->$arrcon['db'];
	}

	/**
	 * @Describe : mongo query
	 * @Parm : $collection collection name
	 * @Parm : $where array('key'=>condition array)
	 * @Parm : $fields array()
	 * @Parm : $limit array(start, perpage)
	 * @Return : data
	 */
	public function mongoQuery($collection, $where, $fields=null, $total = null, $sort=null, $limit=null) {
		try{
			$count = 0;
			$data = array();
			$collection = self::newMongo()->selectCollection($collection);
			if ($collection == null) {
				return $data;
			}

			if (!empty($fields)) {
				$cursor = $collection->find($where, $fields);
			}
			else {
				$cursor = $collection->find($where);
			}

			if (!empty($total)) {
				$total = $cursor ->count();
			}

			if (!empty($sort)) {
				$cursor->sort($sort);
			}

			if (!empty($limit)) {
				if (isset($limit[0]) && $limit[0] > 0) {
					$cursor->skip($limit[0]);
				}
				if (isset($limit[1]) && $limit[1] > 0) {
					$cursor->limit($limit[1]);
				}
			}

			while ($cursor->hasNext()) {
				$item = $cursor->getNext();
				unset($item['_id']);
				$data[$count++] = $item;
			}

			return array('ret' => $data, 'total'=>$total);
		}
		catch(MongoCursorException $e) {

			exit( "errcode:" . $e->getCode() . " errmsg:" . $e->getMessage());
		}
		
	}

	/**
	 * @Describe : mongo update
	 * @Parm : $collection collection name
	 * @Parm : $where array('key'=>condition array)
	 * @Parm : $updata array('key'=>'new data')
	 * @Return : true
	 */
	public function mongoUpdate($collection, $updata, $where, $affectall=false) {
		try{

			$collection = self::newMongo()->selectCollection($collection);

			if ($collection == null) {
				return false;
			}
			$data = array('$set'=>$updata);
		
			return $collection->update($where,$data,array('multiple' => $affectall,'safe' => true));

		}catch(MongoCursorException $e){
		
			exit( "errcode:" . $e->getCode() . " errmsg:" . $e->getMessage());
		
		}
	}

	/**
	 * @Describe : mongoInsert
	 * @Parm : $collection collection name
	 * @Parm : $where array('key'=>condition array)
	 * @Parm : $updata array('key'=>'new data')
	 * @Return : true
	 */
	public function mongoInsert($collection, $data) {
		try{
			$collection = self::newMongo()->selectCollection($collection);
			if ($collection != null) {
				$ret = $collection->batchInsert($data,array("safe" => true));

				return ($ret['ok'] == 1);
			}
			return false;
		}catch(MongoCursorException $e){
			exit( "errcode:" . $e->getCode() . " errmsg:" . $e->getMessage());
		}

	}
	/**
	 * @Describe : mongo delete
	 * @Parm : $collection collection name
	 * @Parm : $where array('key'=>condition array)
	 * @Parm : $updata array('key'=>'new data')
	 * @Return : true
	 */
	public function mongoDelete($collection, $where) {

		$collection = self::newMongo($db)->selectCollection($collection);

		$options = array("justOne"=>false,"safe"=>true);

		if ($collection != null) {
			try{
				$ret = $collection->remove($where, $options);

				return ($ret['ok'] == 1);
			}catch(MongoCursorException $e){
				exit( "errcode:" . $e->getCode() . " errmsg:" . $e->getMessage());
			}
		}
		return false;
	}
}