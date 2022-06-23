<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'ControllerDefault.php';
require_once 'New.php';
require_once 'Notification.php';

class NewsController extends ControllerDefault {

	public function init() {
		parent::init(FALSE);
		$this->model = new Model_DBTable_New();
	}

	public function indexAction() {
		try {
			$this->view->entities=$this->model->showAll("ne_status=1","ne_date","DESC");
		} catch (Zend_Exception $exc) {
			throw new Zend_Exception($exc->getMessage(), $exc->getCode());
		}
	}
	public function viewAction() {
		try {
			$this->view->entity=$this->model->get($this->view->parameters["id"]);
            $notification = new Model_DBTable_Notification();
            $notification->removeBy($this->view->parameters["id"],$this->view->login["IDBioquimico"]);
			if(!$this->view->entity) throw new Zend_Controller_Action_Exception('No se pudo localizar la noticia solicitada.',558);
		} catch (Zend_Exception $exc) {
			$this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('index');
		}
	}

}
