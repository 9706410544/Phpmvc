<?php
class Database extends PDO {

	public function __construct($DB_TYPE,$DB_HOST,$DB_NAME,$DB_USER,$DB_PASS){
		$options = [
		    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		    PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING
		];
		parent::__construct($DB_TYPE.':host='.$DB_HOST.';dbname='.$DB_NAME,$DB_USER,$DB_PASS,$options);
		//parent::setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION );
		//parent::setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
		parent::query('SET NAMES utf8');
	}
	/**
	 * Select
	 * @param  string $sql An SQL string
	 * @param  array $array Parameters to bind
	 * @param  constant $fetchMode A PDO fetch mode
	 * @return mixed        
	 */
	public function ds_select($sql,$array=array(),$fetchMode=PDO::FETCH_ASSOC){
		//var_dump($sql);
		$return['msgType']=false;
		$return['data']=[];

		$query=$this->prepare($sql);
		foreach($array as $key => $value){
			$query->bindValue(":$key",$value);
		}
		$query->execute();
		if($query->rowCount() == 0){
	        $return['msg']="No records found";
    	}else{
    		$return['msgType'] = true;
		    $return['msg']="Records found";
		    $return['data']=$query->fetchAll($fetchMode);
    	} 
    	return $return;
	}
	/**
	 * Insert
	 * @param  [type] $table Name of table to insert into
	 * @param  [type] $data  Data to be inserted[Associative array]
	 * @return [type]        
	 */
	public function ds_insert($table,$data){
		$return['msgType']=false;
		$return['data']=[];
		
		$fieldNames=implode('`,`', array_keys($data));
		$fieldValues=':'.implode(', :', array_keys($data));
		$query=$this->prepare("INSERT INTO $table(`$fieldNames`) VALUES($fieldValues)");
		foreach($data as $key => $value){
			$query->bindValue(":$key",$value);
		}
		$query->execute();
		if($query->rowCount() == 0){
	        $return['msg']="No record inserted";
    	}else{
    		$return['msgType'] = true;
		    $return['msg']="Records added successfully";
		    $return['lastInsertId']=$this->lastInsertId();
    	} 
    	return $return;
	}

	/**
	 * Update
	 * @param  string $table Name of table for updating data
	 * @param  array $data  Data to be updated[Associative array]
	 * @param  string $where The where query part
	 * @return [type]        
	 */
	public function ds_update($table,$data,$where){
		$return['msgType']=false;
		$return['data']=[];
		$fieldDetails=NULL;
		foreach($data as $key => $value){
			$fieldDetails .="`$key`=:$key,";
		}
		$fieldDetails=rtrim($fieldDetails,',');
		$fieldNames=implode('`,`', array_keys($data));
		$fieldValues=':'.implode(', :', array_keys($data));

		$query=$this->prepare("UPDATE $table SET $fieldDetails WHERE $where");
		foreach($data as $key => $value){
			$query->bindValue(":$key",$value);
		}
		$query->execute();
		$err=$query->errorInfo();
	    if($query->rowCount() == 0 && $err[0] != 0){
	      $return['msg']="Record couldnt be updated";
	      return $return;
	    }else{
	      $return['msgType']=true;
	      $return['msg']="Record updated";
	      return $return;
	    }     
	}
	/**
	 * delete
	 * @param  string  $table name of table
	 * @param  string  $where 
	 * @param  integer $limit So that we delete only a single value
	 * @return [type]         
	 */
	public function ds_delete($table,$where,$limit=1){
		//return $this->exec("DELETE FROM $table WHERE $where LIMIT $limit");	
		$return['msgType']=false;
	    $return['data']=[];
	    $query = $this->prepare("DELETE FROM $table WHERE $where LIMIT $limit");
	    $query->execute();
	    if($query->rowCount() == 0){
	      $return['msg']="Record couldnt be deleted";
	      return $return;
	    }else{
	      $return['msgType']=true;
	      $return['msg']="Record deleted successfully";
	      return $return;
	    }
	}
	/**
	* Clean any input that is posted
	* @param  mixed $data Data is posted from frontend
	* @return mixed       cleaned input data
	*/
	public function ds_clean_input($data){
		$data = trim($data);
		$data = strip_tags($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		$data = mysql_real_escape_string($data);
		return $data;
	}
	/**
	 * [ds_select_auto - This function is used for getting the autoincrement id from the table beforehand]
	 * @param  [type] $sql       [description]
	 * @param  [type] $fetchMode [description]
	 * @return [type]            [description]
	 */
	public function ds_select_auto($sql,$fetchMode=PDO::FETCH_ASSOC){
		$return['msgType']=false;
		$return['data']=[];
		$query=$this->prepare($sql);
		$query->execute();
		if($query->rowCount() == 0){
	        $return['msg']="No data found";
    	}else{
    		$return['msgType'] = true;
		    $return['msg']="Data found";
		    $return['data']=$query->fetch($fetchMode)['Auto_increment'];
    	} 
    	return $return;
	}
}