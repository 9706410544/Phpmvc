<?php
require_once( DS_LIBS .'/phpmail/class.phpmailer.php');

class Mailer{

	public function ds_sendEmailNotification($to,$subject,$body){
		$phpmailer = new PHPMailer;
		$phpmailer->isSMTP();                    
		$phpmailer->Host = 'mail.dev-ekodus.com';
		//$phpmailer->SMTPDebug  = 2;
		$phpmailer->SMTPAuth = true;
		$phpmailer->Username = 'support@dev-ekodus.com';
		$phpmailer->Password = 'Developer@123';
		//$phpmailer->SMTPSecure = 'tls';
		$phpmailer->Port = 25;
		$phpmailer->From = 'support@dev-ekodus.com';
		$phpmailer->FromName = 'Ekodus';
		$phpmailer->addAddress($to);
		$phpmailer->isHTML(true);
		$phpmailer->Subject = $subject;
		$phpmailer->Body    = $body;
		$result = $phpmailer->send();
		return $result;
		/*if(!$phpmailer->send()) {
	      echo 'Mailer Error: ' . $phpmailer->ErrorInfo;
	    } else {
	      echo 'Message has been sent';
	    }*/
		//return $returnData;
	}
	public function ds_sendAutoReplyemail($to,$subject,$body){
		$phpmailer = new PHPMailer;
		$phpmailer->isSMTP();                    
		$phpmailer->Host = 'mail.dev-ekodus.com';
		$phpmailer->SMTPAuth = true;
		$phpmailer->Username = 'support@dev-ekodus.com';
		$phpmailer->Password = 'Developer@123';
		$phpmailer->Port = 25;
		$phpmailer->From = 'support@dev-ekodus.com';
		$phpmailer->FromName = 'Ekodus';
		$phpmailer->addAddress($to);
		$phpmailer->isHTML(true);
		$phpmailer->Subject = $subject;
		$phpmailer->Body    = $body;
		$result = $phpmailer->send();
		return $result;
	}
}

?>