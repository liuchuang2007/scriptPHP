<?php
/**
 *@description: MysqlRes
 *@author: liuchuang
 *@Date:2013-03-03
 */
class MysqlRes  extends ResourceRoute {
	private $linkID;
	public function MysqlRes() {
		$this->connect();
	}

	public function __destruct() {
		if ($this->linkID) { 
			mysqli_close($this->linkID);
		}
	}

	public function table($name='') {
		if (empty($name)) {
			return App::$app->mysql['prefix'].$this->_table;
		}
		else {
			return App::$app->mysql['prefix'] . $name;
		}
	}

	private function connect() {
		$arrcon = $this->get_conn_source('mysql');
		if (count(explode(':', $arrcon['host'])) > 1) {
			list($arrcon['host'], $arrcon['port']) = explode(':', $arrcon['host']);
			!$arrcon['port'] && $arrcon['port'] = 3306;
		}
		else {
			$arrcon['port'] = 3306;
		}

		$this->linkID = mysqli_connect($arrcon['host'], $arrcon['user'], $arrcon['password'], $arrcon['db'], $arrcon['port']) or $this->db_error('DB Connect Error!');
		mysqli_query($this->linkID, "SET NAMES 'utf8'") or $this->db_error("Set names error!");
	}

	public function save($article = array()) {
        $sql = 'INSERT INTO '.$this->table().' (';
        $values = '';
        $keys = '';
        foreach ($article as $key => $val) {
            $keys .= $key . ',';
            $values .= "'" . $this->escape_string($val) . "',";
        }

        $sql = $sql . trim($keys, ',') . ') values(' . trim($values, ',') . ')';
        return $this->queryBySql($sql);
    }

	public function update($id, $article = array()) {
		$id = intval($id);
        $sql = 'UPDATE ' . $this->table() . ' SET ';
        foreach ($article as $key => $val) {
            $sql .= "$key='" . $this->escape_string($val) . "',";
        }

        $sql = trim($sql, ',') . ' WHERE id = ' . $id;
        return $this->queryBySql($sql);
    }

	public function deleteItemByIds($ids=array()) {
		$ids = implode(',', $ids);
		$sql = "DELETE FROM " . $this->table() . " WHERE id IN ($ids)";

		return $this->queryBySql($sql);
	}

	public function queryBySql($sql = '', $returnType = ''){
		if (!$this->linkID) {
			$this->connect();
		}

		if ($sql != '') {
			$result = mysqli_query($this->linkID, $sql) or $this->db_error("{$sql}");

			if ($returnType == '') {

				return $result;
			}
			else if ($returnType == 'row') {
				$row = mysqli_fetch_row($result);
				$this->free_result($result);

				return $row;
			}
			else if ($returnType == 'assoc') {
				$row = @array_change_key_case(mysqli_fetch_assoc($result),CASE_LOWER);
				$this->free_result($result);

				return $row;
			}
			else if ($returnType == 'all') {
				$i = 0;
				$all = array();
				while ($row = mysqli_fetch_assoc($result)) {
					$all[$i] = @array_change_key_case($row , CASE_LOWER);
					$i++;
				}
				$this->free_result($result);

				return $all;
			}
		}
		else {

			return false;
		}
	}

	public function executeSql($sql = ''){
		if (!$this->linkID) {
			$this->connect();
		}
		
		return mysqli_query($this->linkID, $sql) or $this->db_error("{$sql}");
	}

	public function fetch_row($res){
		return mysqli_fetch_assoc($res);
	}

	function affected_rows(){
		return mysqli_affected_rows($this->linkID);
	}

	function last_insert_id(){
		return mysqli_insert_id($this->linkID);
	}

	function free_result($res){
		return @mysqli_free_result($res);
	}

	public function escape_string($str){
		if(!$this->linkID){
			$this->connect();
		}

		return @mysqli_real_escape_string($this->linkID, $str);
	}

	function db_error($msg){
		exit($msg . " : " . mysqli_error($this->linkID));
	}
}

?>