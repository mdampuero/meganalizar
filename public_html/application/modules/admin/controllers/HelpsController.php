<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Help.php';
require_once 'Determination.php';
require_once 'Helpdetermination.php';
require_once 'Controller.php';

class Admin_HelpsController extends Controller {

    protected $singular = "Dato Útil";
    protected $plural = "todos los datos útiles";
    protected $messageDelete = "¿Esta seguro que desea eliminar este dato útil?";
    protected $title = "Datos útiles";

    public function init() {

        $fields = array(
            array('field' => 'he_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true),
            array('field' => 'he_name', 'label' => 'Nombre', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'he_determinations', 'label' => 'determinations',  'type' => 'partial-view', 'notsave'=>true,'file' => $this->_request->getParam('controller').'/determinations.phtml'),
            array('field' => 'he_content', 'label' => 'Contenido', 'type' => 'textarea', 'html' => true, 'order' => true)
        );
        $actions = array(
            array('type' => 'link', 'label' => 'Agregar ' . $this->singular, 'icon' => 'plus', 'controller' => $this->_request->getParam('controller'), 'action' => 'add'),
            array('type' => 'link', 'label' => 'Listar ' . $this->plural, 'icon' => 'list', 'controller' => $this->_request->getParam('controller'), 'action' => 'index'),
            array('type' => 'link', 'label' => 'Exportar a Excel ' . $this->plural, 'icon' => 'floppy-saved', 'controller' => $this->_request->getParam('controller'), 'action' => 'excel'),
        );
        $options = array(
            array('type' => 'link', 'title' => 'Detalle', 'icon' => 'glyphicon glyphicon-eye-open text-primary', 'controller' => $this->_request->getParam('controller'), 'action' => 'detail'),
            array('type' => 'link', 'title' => 'Editar', 'icon' => 'glyphicon glyphicon-edit text-primary', 'controller' => $this->_request->getParam('controller'), 'action' => 'edit'),
            array('type' => 'link', 'title' => 'Eliminar', 'icon' => 'glyphicon glyphicon-ban-circle text-danger', 'controller' => $this->_request->getParam('controller'), 'action' => 'delete', 'dialog' => true,
                'dialog_message' => $this->messageDelete)
        );
        parent::init($fields, $actions, $options);
        $this->model = new Model_DBTable_Help();
        $this->determination = new Model_DBTable_Determination();
        $this->help_determination = new Model_DBTable_Helpdetermination();
        $this->view->determinations=$this->determination->listAll();
        if($this->view->parameters["id"])
            $this->view->help_determinations=$this->help_determination->getByHelp($this->view->parameters["id"]);
    }
    public function addAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $this->data = $this->_helper->Form->isValid($this->fields);
                $id = $this->model->add($this->data);
                if($determinations=$_POST["determinations"]){
                    foreach ($determinations as $key => $determination) {
                        $this->help_determination->add(array('hd_he_id'=>$id,'hd_de_id'=>$determination));
                    }
                }
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_NEW));
                $this->_helper->Redirector->gotoSimple('add', null, null);
            }
            $this->view->title = $this->title . ' / Nuevo';
            $this->view->token = $this->_helper->Form->setToken();
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('add');
        }
    }
    public function editAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $this->data = $this->_helper->Form->isValid($this->fields);
                $this->model->edit($this->data, $this->view->parameters["id"]);
                $this->help_determination->deleteStrongBy('hd_he_id='.$this->view->parameters["id"]);
                if($determinations=$_POST["determinations"]){
                    foreach ($determinations as $key => $determination) {
                        $this->help_determination->add(array('hd_he_id'=>$this->view->parameters["id"],'hd_de_id'=>$determination));
                    }
                }
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_EDI));
                $this->_helper->Redirector->gotoSimple('index', null, null);
            }
            $this->view->title = $this->title . ' / Editar';
            $this->view->token = $this->_helper->Form->setToken();
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('edit', null, null, array('id' => $this->view->parameters["id"]));
        }
    }
}
