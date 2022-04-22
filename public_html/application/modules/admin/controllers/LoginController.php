<?php

require_once 'Admin.php';

class Admin_LoginController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->disableLayout();
        $this->users = new Model_DBTable_Admin();
        $this->view->setting = $this->_helper->Setting->getSetting();
        $this->session_url = new Zend_Session_Namespace('url');
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
    }

    public function indexAction() {
        try {
            $response=null;
            if ($this->getRequest()->isPost()) {
                $this->data=$this->_helper->Form->isValid();
                if (!$this->_helper->Login->loginIn($this->users->logIn($this->getRequest()->getPost()))) {
                    throw new Zend_Controller_Action_Exception("Usuario o contraseña no válidos.");
                } else {
                    if (count($this->session_url->params)>3){
                        foreach ($this->session_url->params as $key => $p):
                            if ($key == "module" || $key == "controller" || $key == "action"):
                                $url.= $p . "/";
                            else:
                                $url.= $key . "/" . $p . "/";
                            endif;
                        endforeach;
                    }else{
                        $login=$this->_helper->Login->isLogin();
                        $defaultPage=$login['defaultPage'];
                        $url = 'admin'.'/'.$defaultPage['controller'].'/'.$defaultPage['action'];
                    }
                }
                
                $this->_redirect($url);
            }
            $this->view->response = $response;
            $this->view->token = $this->_helper->Form->setToken();
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }

    public function logoutAction() {
        $this->_helper->Login->logOut();
        $this->_redirect('/admin/slider');
    }

}
