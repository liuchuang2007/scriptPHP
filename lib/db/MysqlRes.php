<?php
/**
 *@description: MysqlRes
 *@author: liuchuang
 *@Date:2013-03-03
 */
class MysqlRes  extends ResourceRoute {
	private $linkID;
	public function __construct() {
	}

	public function __destruct() {
		if ($this->linkID) { 
			mysqli_close($this->linkID);
		}
	}

	private function connect() {
		$arrcon = $this->get_conn_source('mysql');
		if (count(explode(':', $arrcon['host'])) > 1) {
			list($host, $port) = explode(':', $arrcon['host']);
			!$port && $port = 3306;
		}
		else {
			$arrcon['port'] = 3306;
		}

		$this->linkID = mysqli_connect($arrcon['host'], $arrcon['user'], $arrcon['password'], $arrcon['db'], $arrcon['port']) or $this->db_error('DB Connect Error!');
		mysqli_query($this->linkID, "SET NAMES 'utf8'") or $this->db_error("Set names error!");
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