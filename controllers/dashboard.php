<?php
class Dashboard extends Controller {
	function __construct(){
		parent::__construct();
		@session_start();
		Auth::handleLogin();
		$this->Acl=new Acl();
		$this->Session=new Session();
	}
	public function index(){
		$sessionData=$this->Session->getCurrentLoggedInUseridnRoleId($_SESSION['hash']);
		$this->view->perm=$this->Acl->check("add_role",$sessionData['user_id'],$sessionData['role_id']);
		$this->view->renderAdmin('dashboard/index');
	}
}
?>