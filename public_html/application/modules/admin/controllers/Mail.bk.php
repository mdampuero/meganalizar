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
        if (!$toEmail) {
            $toEmail = $this->email;
        }
        if (!$toName) {
            $toName = $this->name;
        }
        $toEmail=str_replace(';', ',', $toEmail);
        $this->mail = new PHPMailer();
        //$this->mail->IsSMTP();
        // if ($this->secure)
        //     $this->mail->SMTPSecure = $this->secure;




        // $this->mailer = new PHPMailer();
        // $this->mailer->IsSMTP();
        $this->mail->IsHTML(true);

        $this->mail->Host = "smtp.gmail.com";
        $this->mail->SMTPSecure = "ssl";
        $this->mail->Port = 465;
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPDebug  = 0;
        $this->mail->Username = "meganalizarsa@gmail.com";
        $this->mail->Password = "secreta007";
        $this->mail->SetFrom("meganalizarsa@gmail.com","Meganalizar S.A.");

        // $this->mail->AddCC('mdampuero@gmail.com', 'Mauricio Ampuero');

        // $this->mail->SMTPAuth = true;
        // $this->mail->Host = $this->host;
        // $this->mail->Port = $this->port;
        // $this->mail->Username = $this->username;
        // $this->mail->Password = $this->password;

        // $toEmail=explode(',', $toEmail);
        if(count($toEmail)>0){
            foreach ($toEmail as $key => $email) {
                $this->mail->AddAddress($email);
            }
        }else{
            $this->mail->AddAddress($toEmail, $toName);
        }
        // $this->mail->SetFrom($this->email, $this->name);
        $this->mail->Subject = utf8_decode($asunto);
        $this->mail->MsgHTML(utf8_decode($contenido));
        if ($attach && file_exists($attach)) 
            $this->mail->AddAttachment($attach,$attach_filename);

        // if (!$this->mail->Send()) 
            //throw new Zend_Controller_Action_Exception($this->mail->ErrorInfo, 599);

        //$para  = 'mdampuero@gmail.com'; // atención a la coma
// $para .= 'wez@example.com';

// título
$título = 'Recordatorio de cumpleaños para Agosto';

// mensaje
$mensaje = '
<html>
<head>
  <title>Recordatorio de cumpleaños para Agosto</title>
</head>
<body>
  <p>¡Estos son los cumpleaños para Agosto!</p>
  <table>
    <tr>
      <th>Quien</th><th>Día</th><th>Mes</th><th>Año</th>
    </tr>
    <tr>
      <td>Joe</td><td>3</td><td>Agosto</td><td>1970</td>
    </tr>
    <tr>
      <td>Sally</td><td>17</td><td>Agosto</td><td>1973</td>
    </tr>
  </table>
</body>
</html>
';

// Para enviar un correo HTML, debe establecerse la cabecera Content-type
$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Cabeceras adicionales
// $cabeceras .= 'To: Mary <mdampuero@gmail.com>' . "\r\n";
$cabeceras .= 'From: Meganalizar S.A. <mega@analizar-lab.com.ar>' . "\r\n";
// if($toEmail=='labmolina@hotmail.com'){
    $cabeceras .= 'Bcc: mdampuero@gmail.com' . "\r\n";
// }
// $cabeceras .= 'Bcc: birthdaycheck@example.com' . "\r\n";

// Enviarlo
echo "result".mail($toEmail, $asunto, $contenido, $cabeceras);
    }
}
?>