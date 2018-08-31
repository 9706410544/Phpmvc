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
  public function paginate_function($item_per_page, $current_page, $total_records, $total_pages,$controllerName){
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){
        $pagination .= '<ul class="pagination pagination-sm float-right">';
        $right_links    = $current_page + 3;
        $previous       = $current_page - 1;
        $next           = $current_page + 1; 
        $first_link     = true; 
        if($current_page > 1){
            $previous_link = ($previous==0)?1:$previous;
            $pagination .= '<li class="page-item first"><a class="page-link" href="'.URL.'phpladmin/'.$controllerName.'/?page=1&max='.DS_PAGE_MAX_RESULT.'" data-page="1" title="First">&laquo;</a></li>'; 
            $pagination .= '<li class="page-item"><a class="page-link" href="'.URL.'phpladmin/'.$controllerName.'/?page='.$previous_link.'&max='.DS_PAGE_MAX_RESULT.'" data-page="'.$previous_link.'" title="Previous">&lt;</a></li>';
                for($i = ($current_page-2); $i < $current_page; $i++){ 
                    if($i > 0){
                        $pagination .= '<li class="page-item"><a class="page-link" href="'.URL.'phpladmin/'.$controllerName.'/?page='.$i.'&max='.DS_PAGE_MAX_RESULT.'" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
                    }
                }   
            $first_link = false; 
        }
        if($first_link){ 
            $pagination .= '<li class="page-item first active"><a class="page-link" href="#">'.$current_page.'</a></li>';
        }elseif($current_page == $total_pages){ 
            $pagination .= '<li class="page-item last active"><a class="page-link" href="#">'.$current_page.'</a></li>';
        }else{ 
            $pagination .= '<li class="page-item active"><a class="page-link" href="#">'.$current_page.'</a></li>';
        }      
        for($i = $current_page+1; $i < $right_links ; $i++){ 
            if($i<=$total_pages){
                $pagination .= '<li class="page-item"><a class="page-link" href="'.URL.'phpladmin/'.$controllerName.'/?page='.$i.'&max='.DS_PAGE_MAX_RESULT.'" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){ 
                $next_link = ($i > $total_pages)? $total_pages : $i;
                $pagination .= '<li class="page-item"><a class="page-link" href="'.URL.'phpladmin/'.$controllerName.'/?page='.$next_link.'&max='.DS_PAGE_MAX_RESULT.'" data-page="'.$next_link.'" title="Next">&gt;</a></li>';
                $pagination .= '<li class="page-item last"><a class="page-link" href="'.URL.'phpladmin/'.$controllerName.'/?page='.$total_pages.'&max='.DS_PAGE_MAX_RESULT.'" data-page="'.$total_pages.'" title="Last">&raquo;</a></li>'; 
        }
        $pagination .= '</ul>'; 
    }
    return $pagination;
  }
}
?>