<?php

require_once 'Log.php';

class Zend_Controller_Action_Helper_Log extends Zend_Controller_Action_Helper_Abstract {

    private $log;

    public function __construct() {
        $this->log = new Model_DBTable_Log();
    }

    public function setLog($parameters) {
        $parameters["lo_url"]=$this->getUrl();
        return $this->log->add($parameters);
    }

    protected function getUrl() {
        $view = Zend_Layout::getMvcInstance()->getView();
        return HOST . $view->url();
    }

}

?>
