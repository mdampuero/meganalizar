<?php

/*
 * Author: Mauricio Ampuero 
 * E-Mail: mdampuero@gmail.com
 */

require_once 'ControllerDefault.php';
require_once 'Bioquimico.php';

class LoginController extends ControllerDefault {

	public function init() {
		parent::init(FALSE);
		$this->bioquimico=new Model_DBTable_Bioquimico();
	}

	public function loginAction() {
		try {
			if ($this->getRequest()->isPost()){
				$bioquimico =$this->bioquimico->login($_POST['matricula'],$_POST['clave']);
				if(!$bioquimico)
					throw new Zend_Controller_Action_Exception('Matrícula o Contraseña inválida',558);
				if($_POST['recordarme'])
					setcookie('matriculaMeganalizar', $_POST['matricula'], time()+31556926 ,'/');
				$this->_helper->Login->loginInPublic($bioquimico);
				$this->_helper->Redirector->gotoSimple('index','account');
			}
		} catch (Zend_Exception $exc) {
			$this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
			$this->_redirect($this->getRequest()->getHeader('referer'));
		}
	}
	public function logoutAction() {
		try {		
			setcookie('matriculaMeganalizar', null, -1 ,'/');	
			$this->_helper->Login->logOutPublic();
			$this->_helper->Redirector->gotoSimple('index','index');
		} catch (Zend_Exception $exc) {
			$this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
			$this->_helper->Redirector->gotoSimple('index','index');
		}
	}
	public function indexAction() {
		try {
			//$this->_redirect('index/index');
		} catch (Zend_Exception $exc) {
			$this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
			$this->_helper->Redirector->gotoSimple('index','index');
		}
	}

}
