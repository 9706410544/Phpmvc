<?php

class Bootstrap {

	private $_url=null;
	private $_controller=null;
	private $_controllerPath='controllers/';
	private $_modelPath='models/';
	private $_errorFile='error.php';
	private $_defaultFile='login.php';
	/**
	 * Starts the Bootstrap
	 */
	public function init(){
		$this->_getUrl();
		///////////////////////////////////////////////////////
		//Load the default controller index if no url is set //
		///////////////////////////////////////////////////////
		if(empty($this->_url[0])){
			$this->_loadDefaultController();
			return false;
		}	
		/////////////////////////////////////
		//Get the controller name from url //
		/////////////////////////////////////
		$this->_loadExistingController();
		$this->_callControllerMethod();
	}
	/**
	 * Set a custom path to controllers
	 * @param [type] $path [description]
	 */
	public function setControllerPath($path){
		$this->_controllerPath = trim($path,'/').'/';
	}
	/**
	 * Set a custom path to models
	 * @param [type] $path [description]
	 */
	public function setModelPath($path){
		$this->_modelPath = trim($path,'/').'/';	
	}
	/**
	 * Set a custom path to error file
	 * @param String $path - Use the filename only of your controller
	 */
	public function setErrorFile($path){
		$this->_errorFile = trim($path,'/');
	}
	/**
	 * Set a custom path to error file
	 * @param String $path - Use the filename only of your controller
	 */
	public function setDefaultFile($path){
		$this->_defaultFile = trim($path,'/');
	}
	/**
	 * _getUrl - Fetches the $_GET from url
	 * @return [type] [description]
	 */
	private function _getUrl(){
		$url=isset($_GET['url']) ? $_GET['url'] : null;
		$url=rtrim($url,'/');
		$url=filter_var($url,FILTER_SANITIZE_URL);
		$this->_url=explode('/',$url);
		//print_r($url);
	}
	/**
	 * __loadDefaultController - This loads if there is no GET parameter passed
	 * @return [type] [description]
	 */
	private function _loadDefaultController(){
		require $this->_controllerPath . $this->_defaultFile;
		$name=explode('.',$this->_defaultFile);
		$this->_controller=new $name[0]();
		/*If model exists for default controller*/
		if(file_exists($this->_modelPath.$name[0].'_model.php')){
			$this->_controller->loadModel($name[0] , $this->_modelPath);
		}
		$this->_controller->index();
	}
	/**
	 * _loadExistingController - Load an existing controller if there is a GET parameter passed
	 * @return boolean|string
	 */
	private function _loadExistingController(){
		//$file='controllers/'.$this->_url[0].'.php';
		$file=$this->_controllerPath.$this->_url[0].'.php';
		if(file_exists($file)){
			require $file;
			$this->_controller=new $this->_url[0]; //Call an instance of the controller
			$this->_controller->loadModel($this->_url[0] , $this->_modelPath); //Load the model for the respective controller
		}else{
			//throw new Exception("The file: $file does not exists");
			$this->_error();
			return false;
		}
	}
	/**
	 * _callControllerMethod - If a method is passed in the GET parameter
	 * @return [type] [description]
	 */
	private function _callControllerMethod(){
		$length=count($this->_url);
		//Make sure the method we are calling exists
		if($length > 1){
			if(!method_exists($this->_controller, $this->_url[1])){
				$this->_error();
			}
		}
		//Determine what to load
		switch ($length) {
			case 5:
				//controller->Method(Param1,param2,param3)
				$this->_controller->{$this->_url[1]}($this->_url[2],$this->_url[3],$this->_url[4]);
				break;
			case 4:
				$this->_controller->{$this->_url[1]}($this->_url[2],$this->_url[3]);
				break;
			case 3:
				$this->_controller->{$this->_url[1]}($this->_url[2]);
				break;
			case 2:
				$this->_controller->{$this->_url[1]}();
				break;
			default:
				/*die('Something went wrong with the parameters');*/
				$this->_controller->index();
				break;
		}
	}
	/**
	 * _error - Display an error page if nothing exists
	 * @return boolean
	 */
	private function _error(){
		//require 'controllers/error.php';
		require $this->_controllerPath . $this->_errorFile;
		$this->_controller=new Error();
		$this->_controller->index();
		exit;
		//return false;
	}
}
?>