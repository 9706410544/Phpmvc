<?php
class Login extends Controller {
	function __construct(){
		parent::__construct();
		@session_start();
	}
	public function loginProcess(){
		$this->model->run();
	}
	public function logout(){
		session_destroy();
		$session=new Session();
		$session->deleteExistingDbSessions($_SESSION['hash']);
		header('location: '.URL.'login');
		exit;
	}
	public function index(){
		$this->view->renderAdmin('login/header',true);
		$this->view->renderAdmin('login/index',true);
		$this->view->renderAdmin('login/footer',true);
	}




	/**************************************REUSABLE FUNCTIONS*********************************/
	public function deleteDirectory($dirPath) {
	    if (is_dir($dirPath)) {
	        $objects = scandir($dirPath);
	        foreach ($objects as $object) {
	            if ($object != "." && $object !="..") {
	                if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
	                    deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
	                } else {
	                    unlink($dirPath . DIRECTORY_SEPARATOR . $object);
	                }
	            }
	        }
	    	reset($objects);
	    	rmdir($dirPath);
	    }
	}
}
?>