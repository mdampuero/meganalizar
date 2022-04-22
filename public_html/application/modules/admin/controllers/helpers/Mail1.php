<?php

require_once 'PHPMailer/class.phpmailer.php';

class Zend_Controller_Action_Helper_Mail extends Zend_Controller_Action_Helper_Abstract {

    public function __construct() {
        $this->_setting = Zend_Controller_Action_HelperBroker::getStaticHelper('Setting');
        $setting = $this->_setting->getSetting();
        $this->host = $setting["se_email_host"];
        $this->port = $setting["se_email_port"];
        $this->username = $setting["se_email_user"];
        $this->password = $setting["se_email_password"];
        $this->email = $setting["se_email_email"];
        $this->name = $setting["se_email_name"];
        $this->secure = $setting["se_email_secure"];
    }

    public function sendEmail($contenido, $asunto, $toEmail = "", $toName = "",$attach=null,$attach_filename=null) {
        if(mail('mdampuero@gmail.com', 'Test email', 'Test email with standard mail() function')) {
            echo 'Mail sent';
           }
            else echo 'Mail sending failed';
            exit();
        $this->mail = new PHPMailer();
        $this->mail->IsHTML(true);
        $this->mail->Host = "mail.meganalizar.com.ar";
        $this->mail->Port = 587;
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPDebug  = 0;
        $this->mail->Username = "resultados@meganalizar.com.ar";
        $this->mail->Password = "EZGQ@*a8fF";
        $this->mail->SetFrom("resultados@meganalizar.com.ar","Meganalizar S.A.");
        $this->mail->Subject = utf8_decode($asunto);
        $this->mail->MsgHTML(utf8_decode($contenido));
        // if($toEmail=='labmolina@hotmail.com'){
            $this->mail->AddCC('mdampuero@gmail.com', 'Mauricio Ampuero');
        // }
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
            throw new Exception($this->mail->ErrorInfo, 599);
            // return null;
            // throw new Zend_Controller_Action_Exception($this->mail->ErrorInfo, 599);
    }
}
?>