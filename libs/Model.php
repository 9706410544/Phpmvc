<?php

class Model {
	function __construct(){
		$this->db=new Database(DB_TYPE,DB_HOST,DB_NAME,DB_USER,DB_PASS);
		date_default_timezone_set('asia/kolkata');
		$date = new DateTime();
		$this->currentDateTime=date_format($date, 'Y-m-d h:i:s');
	}
}

?>