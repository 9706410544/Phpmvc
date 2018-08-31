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
	public function getB64Type($str) {
    	return substr($str, 5, strpos($str, ';')-5);
	}
	public function globalUpload($post,$tableName,$folderName){
		$autoId=$this->model->getAutoIncrementId($tableName);
		$data=array();
		$type=$this->getB64Type($post);
		$imgType=explode("/",$type);
		$base64img = str_replace('data:'.$type.';base64,', '', $post);
    	$dataImg = base64_decode($base64img);
    	$target_dir=DS_PUBLIC.'admin/images/'.$folderName.'/'.$autoId['data'].'/';
    	if(!file_exists($target_dir)){
            mkdir($target_dir, 0777, true);
        }else{
        	$this->deleteDirectory($target_dir);
		    mkdir($target_dir, 0777, true);
        }
        $part2 = strtotime(date("Y-m-d H:i:s"));
        $str= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $part1= str_shuffle(substr(str_shuffle($str),0,8));
        $target_file =$target_dir.$part1.$part2.'.'.$imgType[1];

	    $data['portfolio']=$target_file;
	    $success=file_put_contents($target_file, $dataImg);
		if($success){
			$response['msgType']=true;
			$response['msg']='Portfolio photo uploaded!!!';
			$response['data']=$target_file;
		}else{
			$response['msgType']=false;
			$response['msg']='Photo couldn\'t be uploaded';
		}
		return $response;
	}
}
?>