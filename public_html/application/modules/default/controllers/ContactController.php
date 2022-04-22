<?php

/*
 * Author: Mauricio Ampuero 
 * E-Mail: mdampuero@gmail.com
 */

require_once 'ControllerDefault.php';

class ContactController extends ControllerDefault {

	public function init() {
		parent::init(FALSE);
	}

	public function indexAction() {
		
	}
	public function sendAction() {
		try {
			if ($this->getRequest()->isPost()){
				if(empty($_POST['nombre'])) 
					throw new Zend_Exception('El Nombre completo no puede estar vacío');
				if(empty($_POST['telefono'])) 
					throw new Zend_Exception('El Teléfono no puede estar vacío');
				if(empty($_POST['email'])) 
					throw new Zend_Exception('El Email no puede estar vacío');
				if(empty($_POST['consulta'])) 
					throw new Zend_Exception('El consulta no puede estar vacío');
				$this->view->data=$_POST;
				/* DEFINE LAYUT EMAIL*/
				Zend_Layout::startMvc(array('layout' => 'email', 'layoutPath' => '../application/modules/admin/layouts/scripts/'));
				$layout = $this->_helper->layout->getLayoutInstance();
				$layout->content = $this->view->render('contact/send.phtml');
				$htmlcontent =  $layout->render();
				/* SEND EMAIL*/
				$this->_helper->Mail->sendEmail($htmlcontent, 'Consulta desde la Web', $this->view->setting["se_system_email"], $this->view->setting["se_system_email"]);
				/* REDIRECT */
				$this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => 'Su consulta fue enviada, a la brevedad nos comunicaremos con usted.'));
				$this->_helper->Redirector->gotoSimple('index','contact');
			}
		} catch (Zend_Exception $exc) {
			$this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
			$this->_helper->Redirector->gotoSimple('index','contact');
		}
	}
}
