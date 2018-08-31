<?php
Class Acl {
   	private $db;
	private $userEmpty = false;
	//initialize the database object here
	public function __construct() {
	     $this->db=new Database(DB_TYPE,DB_HOST,DB_NAME,DB_USER,DB_PASS);
	}
   	public function check($permission,$userid,$roleid) {
		if(!$this->user_permissions($permission,$userid)) {
			return false;
		}
		if(!$this->role_permissions($permission,$roleid) & $this->IsUserEmpty()) {
			return false;
		}
		return true;
	}

	public function user_permissions($permission,$userid) {
		$query=$this->db->prepare("SELECT COUNT(up.id) AS count FROM user_permissions up,permissions p WHERE p.perm_name=? AND up.user_id=? AND up.permission_id=p.id");
		$query->execute(array($permission,$userid));
		$f=$query->fetch(\PDO::FETCH_ASSOC);
		if($f['count']>0) {
			return true;
		}else{
			return false;
		}
		$this->setUserEmpty('true');
	return true;
	}
	public function role_permissions($permission,$roleid) {
		$query=$this->db->prepare("SELECT COUNT(rp.id) AS count FROM role_permissions rp,permissions p WHERE p.perm_name=? AND rp.role_id=? AND rp.permission_id=p.id");
		$query->execute(array($permission,$roleid));
		$f=$query->fetch(\PDO::FETCH_ASSOC);
	    if($f['count']>0) {
			return true;
	    }else{
			return false;
		}
	return true;
   	}
   	public function setUserEmpty($val) {
     	$this->userEmpty = $val;
	}
	public function isUserEmpty() {
   		return $this->userEmpty;
	}
}
?>