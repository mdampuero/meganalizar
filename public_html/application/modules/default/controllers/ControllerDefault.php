<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */
require_once 'Common.php';
require_once 'Bioquimico.php';

class ControllerDefault extends Zend_Controller_Action {

    public function init($requiredLogin = FALSE) {
        $this->bioquimico=new Model_DBTable_Bioquimico();
        $request = new Zend_Controller_Request_Http();
        $matriculaMeganalizar = $request->getCookie('matriculaMeganalizar');
        $login = $this->_helper->Login->isLoginPublic($requiredLogin);
        if(!$login && $matriculaMeganalizar){
            $bioquimico=$this->bioquimico->getByMatricula($matriculaMeganalizar);
            $this->_helper->Login->loginInPublic($bioquimico);
            $this->_helper->Redirector->gotoSimple('index','account');
            $this->view->login = $this->_helper->Login->isLoginPublic($requiredLogin);
        }else{
            $this->view->login = $this->_helper->Login->isLoginPublic($requiredLogin);
        }
        $this->view->parameters = $this->_request->getParams();
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        $this->view->setting = $this->_helper->Setting->getSetting();

        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->getHelper('layout')->disableLayout();
        } elseif ($this->view->parameters["popup"] == true) {
            Zend_Layout::startMvc(array('layout' => 'iframe', 'layoutPath' => '../application/modules/default/layouts/scripts/'));
        } else {
            Zend_Layout::startMvc(array('layout' => 'default', 'layoutPath' => '../application/modules/default/layouts/scripts/'));
        }
        if ($this->view->messages[0]["data"])
            $this->view->result = $this->view->messages[0]["data"];
    }

}
