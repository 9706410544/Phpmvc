<?php

class Login_model extends Model {

	public function __construct(){
		parent::__construct();
    Session::init();
	}
	public function run(){
    if($this->beforeLoginCheckIP()==true){
  		$query=$this->db->prepare("SELECT * FROM users WHERE username=? and password=? and isActive=1");
  		$query->execute(array(
  			$this->db->ds_clean_input($_POST['username']),
  			Hash::createHash('sha1',$this->db->ds_clean_input($_POST['password']),HASH_PASS_KEY)
  		));
  		if($query->rowCount() == 0){
        Session::set('message','Login failed!!!');
        Session::set('message_type','error');
        $this->afterFailedLogin();
        header('location: '.URL.'login');
    	}else{
    	  $data=$query->fetch(\PDO::FETCH_ASSOC);
        Session::set('loggedIn',true);
        Session::set('message','Login successful!!!');
        Session::set('message_type','success');
        $hash=Session::setDbSession($data['id'],$data['role_id']);
        Session::set('hash',$hash);
        $this->afterSuccessfulLogin();
        header('location: '.URL.'dashboard');
    	}
    }else{
      Session::set('message','Access denied for 30 minutes!!!');
      Session::set('message_type','error');
      header('location: '.URL.'login');
    }
	}
  /**
   * beforeLoginCheckIP - This function will be called before checking the credentials.
   * @return Boolean - true/false if IP address matches the one in db
   */
  public function beforeLoginCheckIP(){
    $query = $this->db->prepare("SELECT attempts,lastlogin from loginattempts where ip = ?");
    $query->execute(array($_SERVER["REMOTE_ADDR"]));
    $data=$query->fetch(\PDO::FETCH_ASSOC);
    if (!$data || !strlen($data["lastlogin"])){
      return true; 
    }
    $time = strtotime($data["lastlogin"]);
    if($data["attempts"]>=3){ 
      if(time() - $time < 30 * 60){
        return false; 
      }else{ 
        $qry = $this->db->prepare("UPDATE loginattempts set attempts=0 where ip = ?");
        $qry->execute(array($_SERVER["REMOTE_ADDR"]));
        return true; 
      } 
    } 
    return true; 
  }
  /**
   * afterSuccessfulLogin - This function is called login is successful gainst the credentials passed. Sets the login attempt to 0 for the user.
   */
  public function afterSuccessfulLogin(){ 
    $query=$this->db->prepare("UPDATE LoginAttempts set attempts=0 where ip = ?");
    $query->execute(array($_SERVER["REMOTE_ADDR"]));
  } 
  /**
   * afterFailedLogin - Check the login attempts made by the user and updates when required.
   */
  public function afterFailedLogin(){
    $query=$this->db->prepare("SELECT * FROM loginattempts WHERE ip=?");
    $query->execute(array($_SERVER["REMOTE_ADDR"]));
    if($query->rowCount() == 0){
      $q=$this->db->prepare("INSERT INTO loginattempts (attempts,ip,lastlogin) values (?,?,?)");
      $q->execute(array(1,$_SERVER["REMOTE_ADDR"],$this->currentDateTime));
    }else{
      $data=$query->fetch(\PDO::FETCH_ASSOC);
      $attempts = $data["attempts"]+1;
      if($attempts==3){
        $qry=$this->db->prepare("UPDATE loginattempts set attempts=?, lastlogin=? where ip = ?");
        $qry->execute(array($attempts,$this->currentDateTime,$_SERVER["REMOTE_ADDR"]));
      }else{
        $qy=$this->db->prepare("UPDATE loginattempts set attempts=? where ip =?");
        $qy->execute(array($attempts,$_SERVER["REMOTE_ADDR"]));
      }
    }
  }
  /**************************************REUSABLE FUNCTIONS*********************************/
  public function getAutoIncrementId($tableName){
    $return=$this->db->ds_select_auto("SHOW TABLE STATUS LIKE '$tableName'");
    if($return['msgType']==true){
      $returnData['msg']=$return['msg'];
      $returnData['data']=$return['data'];
    }else{
      $returnData['msg']=$return['msg'];
    }
    $returnData['msgType']=$return['msgType'];
    return $returnData;
  }
}
?>