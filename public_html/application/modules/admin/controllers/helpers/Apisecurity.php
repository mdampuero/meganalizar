<?php

class Zend_Controller_Action_Helper_Apisecurity extends Zend_Controller_Action_Helper_Abstract {

    private $token;
    private $session;
    private $config;

    public function __construct() {
        $this->session = new Zend_Session_Namespace('api-security');
        $this->config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $api = $this->config->getOption('api');
        $this->session->setExpirationSeconds((int)$api["expirationTime"]);
        $this->request = Zend_Controller_Front::getInstance()->getRequest();
    }

    public function setToken() {
        $exp_reg = "[^A-Z0-9]";
        $this->token = substr(@preg_replace($exp_reg, "", md5(rand())) . @preg_replace($exp_reg, "", md5(rand())) . @preg_replace($exp_reg, "", md5(rand())), 0, 50);
        $this->session->token = $this->token;
        return $this->session->token;
    }

    public function getToken() {
        return $this->session->token;
    }

    public function isValidToken($token=null) {
        if ($token == $this->session->token) return true;
        return false;
    }

}

?>
