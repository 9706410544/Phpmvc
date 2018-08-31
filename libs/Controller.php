<?php

class Controller {

	public function __construct(){
		$this->view=new View();
	}
	/**
	 * loadModel - Load the model being called
	 * @param  String $name - Nmae of the model
	 * @param  String $path - Location of the model
	 * @return [type]       [description]
	 */
	public function loadModel($name,$modelPath){
		$path=$modelPath . $name.'_model.php';
		if(file_exists($path)){
			require $modelPath .$name.'_model.php';
			$modelName=$name.'_Model';
			$this->model=new $modelName();
		}		
	}
}
?>