<?php

require_once 'PHPMailer/class.phpmailer.php';
require_once 'PHPMailer/PHPMailerAutoload.php';

class Zend_Controller_Action_Helper_Mail extends Zend_Controller_Action_Helper_Abstract {

    public function __construct() {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV );
        $this->host = $config->mail->host; 
        $this->port = $config->mail->port; 
        $this->username = $config->mail->username; 
        $this->password = $config->mail->password; 
        $this->email = $config->mail->email; 
        $this->name = $config->mail->name; 
        $this->secure = $config->mail->secure;
    }
    
    
    public function sendEmail($contenido, $asunto, $toEmail = "", $toName = "",$attach=null,$attach_filename=null) {

        $this->mail = new PHPMailer();
        $this->mail->IsSMTP();
        if ($this->secure)
            $this->mail->SMTPSecure = $this->secure;
        $this->mail->SMTPAuth = true;
        $this->mail->Host = $this->host;
        $this->mail->Port = $this->port;
        $this->mail->Username = $this->username;
        $this->mail->Password = $this->password;
        
        $this->mail->charset = 'UTF-8';
        $this->mail->SetFrom($this->email, $this->name);
        $this->mail->Subject = $asunto;
        $this->mail->MsgHTML(utf8_decode($contenido));
        if($toEmail=='labmolina@hotmail.com' || $toEmail=='labmolina@hotmail.com'){
            $this->mail->AddCC('mdampuero@gmail.com', 'Mauricio Ampuero');
        }
        $toEmail=explode(',', $toEmail);
        if(count($toEmail)>0){
            foreach ($toEmail as $key => $email) {
                $this->mail->AddAddress($email);
            }
        }else{
            $this->mail->AddAddress($toEmail, $toName);
        }
        
        if ($attach && file_exists($attach)) 
            $this->mail->AddAttachment($attach,$attach_filename);
        if (!$this->mail->Send()) 
            return null;
    }

}

?>