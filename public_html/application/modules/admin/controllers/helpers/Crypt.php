<?php

class Zend_Controller_Action_Helper_Crypt extends Zend_Controller_Action_Helper_Abstract {
    private $_cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';

    public function __construct() {

    }

    public function encryptIt( $q ) {
        $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $this->_cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $this->_cryptKey ) ) ) );
        $qEncoded=str_replace("+","****",$qEncoded);
        $qEncoded=str_replace("/","-----",$qEncoded);
        return( $qEncoded );
    }

    public function decryptIt( $q ) {
        $q=str_replace("****","+",$q);
        $q=str_replace("-----","/",$q);
        $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $this->_cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $this->_cryptKey ) ) ), "\0");
        return( $qDecoded );
    }

}

?>