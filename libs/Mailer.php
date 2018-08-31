<?php
require_once( DS_LIBS .'/phpmail/class.phpmailer.php');

class Mailer{

	public function ds_sendEmailNotification($to,$subject,$body){
		$phpmailer = new PHPMailer;
		$phpmailer->isSMTP();                    
		$phpmailer->Host = 'XXXXXXXXXXXX';
		//$phpmailer->SMTPDebug  = 2;
		$phpmailer->SMTPAuth = true;
		$phpmailer->Username = 'XXXXXX';
		$phpmailer->Password = 'XXXXXXX';
		//$phpmailer->SMTPSecure = 'tls';
		$phpmailer->Port = XX;
		$phpmailer->From = 'XXXXXXX';
		$phpmailer->FromName = 'XXXXXX';
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
		$phpmailer->Host = 'XXXXXXXXXXXX';
		$phpmailer->SMTPAuth = true;
		$phpmailer->Username = 'XXXXXXXXXXXX';
		$phpmailer->Password = 'XXXXXXXXXXXX';
		$phpmailer->Port = XX;
		$phpmailer->From = 'XXXXXXXXXXXX';
		$phpmailer->FromName = 'XXXXXXXXXXXX';
		$phpmailer->addAddress($to);
		$phpmailer->isHTML(true);
		$phpmailer->Subject = $subject;
		$phpmailer->Body    = $body;
		$result = $phpmailer->send();
		return $result;
	}
}

?>