<?php
class ImportModel extends AgentDB {
	private $posturl = 'http://goods.yougou.com:8080/commodity/dashoubi?key=6f4aeaf9bcaad5727bd35499b861e9d5&tid=1336635329&commodityNo=';
	public function __construct() {
	}

	private function postUrl($url) {
		$ch = curl_init();
		if (!$ch) return false;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLINFO_CONTENT_TYPE, 'utf-8');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		if ($result !== false) {
			return json_decode($result);
		}
		else {
			return false;
		}
		curl_close($ch);
	}

	private function checkIds($ids) {
		$ids = trim($ids, ',');
		$items = explode(',',$ids);
		foreach($items as $key=>$item) {
			$items[$key] = trim($item);//strlen($items[$key])!=8 || 
			if (!is_numeric($items[$key])) return false;
		}
		return $items;
	}

	public function importData($idstr) {
		$idresult = $this->checkIds($idstr);
		if (!$idresult) return false;
		$url = $this->posturl.implode(',',$idresult);
		//echo implode(',',$idresult);
		$result = $this->postUrl($url);
		return $this->proccessResult($result, $idresult);
	}

	private function proccessResult($result,$idresult) {
		if (!$result) return false;
		foreach ($result as $key=>$row) {
			$arr = array();
			$exist = $this->query("select id from yg_product where pcode=".trim($row->commodityNo),'DEFAULT','row');
			$arr['name'] = $row->commodityName;
			$arr['pageurl'] = $row->commodityPageUrl;
			$arr['pic'] = $row->pictureUrl;
			$arr['marketprice'] = $row->markPrice;
			$arr['sellprice'] = $row->sellPrice;
			$arr['stock'] = $row->stock;
			$arr['sellstatus'] = $row->sellStatus;
			$type = $this->checkType($row->commodityName);
			$arr['category'] = $type['category'];
			$arr['brand'] = $type['brand'];
			$arr['last_update_time'] = (int)microtime(true);
			if (!$exist) {
				$arr['type'] = 2; //默认都是优惠50
				$arr['pcode'] = trim($row->commodityNo);
				$this->insert('yg_product', $arr,'DEFAULT');
				$arr = array();
			}
			else {
				$where = 'pcode=' . trim($row->commodityNo);
				$this->update('yg_product', $where, $arr,'DEFAULT');
			}
		}
		return true;
	}

	/**
	 * checkType
	 * @Describe : 产品划分到四大类中去
	 * @category : 
	 * @param :  str 品牌名称
	 * @Return : int 分类id,1 运动  2户外  3 男鞋  4女鞋 5童鞋  0 都不是
	 */
	public function checkType($str) {
		$type = array();

		//先判断是否是运动类品牌下的商品
		$yd = $this->getBrandsByCategoryId(1);
		foreach ($yd as $item) {
			if (strstr($str,$item['name']))return array('category'=>1,'brand'=>$item['bid']);
		}

		//判断是否是户外
		$yd = $this->getBrandsByCategoryId(2);
		foreach ($yd as $item) {
			if (strstr($str,$item['name']))return array('category'=>2,'brand'=>$item['bid']);
		}

		
		//再按关键字划分
		if (strstr($str,'男') && strstr($str,'鞋')) {
			$yd = $this->getBrandsByCategoryId(3);
			foreach ($yd as $item) {
				if (strstr($str,$item['name']))return array('category'=>3,'brand'=>$item['bid']);
			}
		}
		else if (strstr($str,'女') && strstr($str,'鞋')) {
			$yd = $this->getBrandsByCategoryId(4);
			foreach ($yd as $item) {
				if (strstr($str,$item['name']))return array('category'=>4,'brand'=>$item['bid']);
			}
		}
		else if (strstr($str,'童') && strstr($str,'鞋')) {
			$yd = $this->getBrandsByCategoryId(5);
			foreach ($yd as $item) {
				if (strstr($str,$item['name']))return array('category'=>5,'brand'=>$item['bid']);
			}
		}

		return array('category'=>0,'brand'=>0);
		return 0;
	}
	/**
	 * getBrandsByCategoryId
	 * 
	 * @Describe : 获取分类下品牌鞋列表.
	 * @id : 分类,1 运动  2户外  3 男鞋  4女鞋 0 所有
	 * @Return : array 返回结果   false参数错误
	 */
	function getBrandsByCategoryId($id) {
		$allbrands = require Application::$app->brandsPath;
		$result = array();
		if (is_numeric($id)) {
			foreach($allbrands as $item) {
				if ($item['category'] == $id) {
					array_push($result,$item);
				}
			}
			return $result;
		}
		return false;
	}
}