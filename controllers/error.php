<?php

class Error extends Controller {
	function __construct(){
		parent::__construct();
		//echo "This is ERROR";
	}

	public function index(){
		$this->view->title='Error 404!';
		$this->view->msg='Error 404! Page not found';
		$this->view->render('error/header',true);
		$this->view->render('error/index',true);
		$this->view->render('error/footer',true);
	}

}

?>