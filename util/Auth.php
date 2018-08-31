<?php
class Auth{
	public static function handleLogin(){
		@session_start();
		$session=new Session();
		$loggedIn=$session->checkDbSession($_SESSION['hash']);
		$logged=$_SESSION['loggedIn'];
		if($loggedIn==false || $logged==false){
			session_destroy();
			$session->deleteExistingDbSessions($_SESSION['hash']);
			header('location: '.URL.'login');
			exit;
		}
		/*if($logged==false){
			session_destroy();
			header('location: '.URL.'login');
			exit;
		}*/
	}
}

?>