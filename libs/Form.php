<?php
require 'Form/Val.php';
class Form {
	/*@var array $_currentItem -> the immediately posted item*/
	private $_currentItem=null;
	/*@var array $_postData -> Stores the posted data*/
	private $_postData = array();
	/*@var object $_val -> The validator object*/
	private $_val=array();
	/*@var array $_error -> Holds the current form errors*/
	private $_error=array();
	/**
	 * __construct - Instantiates the validator object
	 */
	public function __construct() {
		$this->_val=new Val();
	}
	/**
	 * This is to run $_POST
	 * @param  String $field - The HTML fieldname to post
	 * @return [type]        [description]
	 */
	public function post($field){
		$this->_postData[$field]=$_POST[$field];
		$this->_currentItem=$field;
		return $this;
	}
	/**
	 * val - Will be used for validation
	 * @param  [type] $typeOfValidator - A method from Form/Val class
	 * @param  [type] $arg             - A property to validate against
	 * @return [type]                  [description]
	 */
	public function val($typeOfValidator,$arg=null){
		if($arg == null){
			$error=$this->_val->{$typeOfValidator}($this->_postData[$this->_currentItem]);
		}else{
			$error=$this->_val->{$typeOfValidator}($this->_postData[$this->_currentItem],$arg);
		}
		if($error){
			$this->_error[$this->_currentItem]=$error;
		}
		/*echo '<pre>';
		print_r($this->_error);*/
		return $this;
	}
	/**
	 * submit - Handles the form and throws an exception opn error
	 * @return boolean
	 */
	public function submit(){
		if(empty($this->_error)){
			return true;
		}else{
			$str='';
			foreach ($this->_error as $key => $value) {
				$str .=$key. ' => '. $value."\n";
			}
			throw new Exception($str);
		}
	} 
	/**
	 * Return posted data
	 * @param  mixed $fieldname
	 * @return mixed String or array
	 */
	public function fetch($fieldname=false){
		if($fieldname){
			if(isset($this->_postData[$fieldname])){
				return $this->_postData[$fieldname];
			}else{
				return false;
			}
		}else{
			return $this->_postData;
		}
	}
}
?>