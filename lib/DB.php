<?php
/**
* DB类
* 作者: zwj
* 最后更新日期: 2012-03-09
*/
class MyDb{

	private $host = "";
	private $user = "";
	private $pass = "";
	private $db = "";
	private $queries = 0;
	public $linkID;
	private $stop_on_error = 1;
	private $db_errors = 0;

	function __construct($_host,$_user,$_pass,$_db){
		$this->host = $_host;
		$this->user = $_user;
		$this->pass = $_pass;
		$this->db = $_db;
	}

	function __destruct(){
		if($this->linkID){ 
			mysqli_close($this->linkID);
		}
	}

	function connect(){
		if(count(explode(':',$this->host))>1){
			list($host,$port) = explode(':',$this->host);
			!$port && $port = 3306;
		}else{
			$host = $this->host;
			$port = 3306;
		}
		//echo $host,$this->user,$this->pass,$this->db,$port;die();
		$this->linkID=mysqli_connect($host,$this->user,$this->pass,$this->db,$port) or die('DB Connect Error!');
		mysqli_query($this->linkID, "SET NAMES 'utf8'") or $this->db_error("Set names error!");
	}

	function query($querysql = '', $returnType = ''){
		if(!$this->linkID){
			$this->connect();
		}
		if ($querysql != ''){
			$result = mysqli_query($this->linkID, $querysql) or $this->db_error("{$querysql}");
			$this->queries++;
			if ($returnType == ''){
				return $result;
			}elseif ($returnType == 'row'){
				$row = mysqli_fetch_row($result);
				$this->free_result($result);
				return $row;
			}elseif ($returnType == 'assoc'){
				$row = @array_change_key_case(mysqli_fetch_assoc($result),CASE_LOWER);
				$this->free_result($result);
				return $row;
			}elseif($returnType == 'all'){
				$i = 0;
				$all=array();
				while ($row = mysqli_fetch_assoc($result)){
					$all[$i] = @array_change_key_case($row , CASE_LOWER);
					$i++;
				}
				$this->free_result($result);
				return $all;
			}
		}else{
			$this->db_error ('NO SQL');
		}
	}

	 function checkInsert($dataArray='',$type='single'){
		if(empty($dataArray)){
			return -2;
		}
		if(!is_array($dataArray)){
			return -3;
		}
		if($type=='mult'){
			$values='';
			foreach($dataArray as $key=>$value){
				
				$tmp=implode("','",$value);
				if(!empty($tmp)){
					$values.=',(\''.$tmp.'\')';
				}
				$feild='';
				foreach($value as $key=>$v){
					$feild.=','.$key;
				}
			}
			$feild=substr($feild,1);
			$values=substr($values,1);
		}else{
			foreach($dataArray as $key=>$value){
				$feild.=','.$key;
				$values.=",'".$value."'";
			}
			$feild=substr($feild,1);
			$values=substr($values,1);
		}
		return array('feild'=>$feild,'values'=>$values);

	}

	function insert($tab,$arr,$type='single',$showsql=false){

		$ret = $this->checkInsert($arr,$type);

		if($type=='mult'){

			$sql='insert into '.$tab.'('.$ret['feild'].') values '.$ret['values'].'';

		}else{

			$sql='insert into '.$tab.'('.$ret['feild'].') values ('.$ret['values'].')';
		}

		if($showsql){
			echo $sql;
		}else{
			return $this->query($sql);
		}
	}

	function update($tab,$condition,$arr,$showsql=false){
		$sql='update '.$tab;
		$i=0;
		foreach($arr as $key => $value){
			$value=$this->escape_string($value).'';
			if($value!=''){
				if($i==0){
					$sql.=" set $key ='$value'";
				}else{
					$sql.=",$key ='$value'";
				}
				$i++;
			}
		}
		if ($condition) {
			$sql.=" where $condition";
		}

		if($showsql){
			echo $sql;
		}else{
			return $this->query($sql);
		}
	}

	public function escape_string($s){
		if(!$this->linkID){
			$this->connect();
		}
		return @mysqli_real_escape_string($this->linkID, $s);
	}

	function free_result($res){
		return @mysqli_free_result($res);
	}

	function fetch_row($res){
		return mysqli_fetch_assoc($res);
	}

	function num_rows($result){
		return mysqli_num_rows($result);
	}

	function affected_rows(){
		return mysqli_affected_rows($this->linkID);
	}

	function last_insert_id(){
		return mysqli_insert_id($this->linkID);
	}

	function db_error($msg){
		$this->db_errors++;
		if($this->stop_on_error){
			exit($msg . " : " . mysqli_error($this->linkID));
		}
	}
}

?>