<?php

class Session {
	private $db;
	public function __construct() {
	    $this->db=new Database(DB_TYPE,DB_HOST,DB_NAME,DB_USER,DB_PASS);
	    date_default_timezone_set('asia/kolkata');
	}
	public static function init(){
		@session_start();
	}
	public static function set($key,$value){
		$_SESSION[$key]=$value;
	}
	public static function get($key){
		if(isset($_SESSION[$key])){
			return $_SESSION[$key];
		}
	}
	public static function destroy(){
		//unset($_SESSION);
		session_destroy();
	}
	public function setDbSession($userId,$roleId){
		$hash = sha1(HASH_GEN_KEY . microtime());
		$expiry=strtotime(SESSION_REMEMBER);
		$expiryDate=date("Y-m-d H:i:s", $expiry);
		$agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$s_hash=sha1($hash . HASH_GEN_KEY);
		$query=$this->db->prepare("INSERT INTO sessions(session_hash,session_expiry,user_id,role_id,agent,secure_hash,ip) VALUES(?,?,?,?,?,?,?)");
		$query->execute(array($hash,$expiryDate,$userId,$roleId,$agent,$s_hash,Session::getIp()));
		return $hash;
	}
	public function checkDbSession($hash){
		$query=$this->db->prepare("SELECT session_expiry,secure_hash FROM sessions WHERE session_hash=?");
		$query->execute(array($hash));
		$data=$query->fetch(\PDO::FETCH_ASSOC);
		$currentdate = strtotime(date("Y-m-d H:i:s"));
		if($currentdate > strtotime($data['session_expiry'])){
			$this->deleteExistingDbSessions($hash);
            return false;
		}
		if($data['secure_hash'] == sha1($hash . HASH_GEN_KEY)) {
			return true;
		}
	}
	public function deleteExistingDbSessions($hash){
		$query=$this->db->prepare("DELETE FROM sessions WHERE session_hash=?");
		$query->execute(array($hash));
	}
    protected function getIp(){
        if (getenv('HTTP_CLIENT_IP')) {
            $ipAddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipAddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipAddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipAddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipAddress = getenv('REMOTE_ADDR');
        } else {
            $ipAddress = '127.0.0.1';
        }
        return $ipAddress;
    }
    public function getCurrentLoggedInUseridnRoleId($hash){
    	$query=$this->db->prepare("SELECT user_id,role_id FROM sessions WHERE session_hash=?");
		$query->execute(array($hash));
		$data=$query->fetch(\PDO::FETCH_ASSOC);
		if ($query->rowCount() == 0) {
            return false;
        }else{
        	$finalData=['user_id'=>$data['user_id'],'role_id'=>$data['role_id']];
        	return $finalData;
        }        
    }
}
?>