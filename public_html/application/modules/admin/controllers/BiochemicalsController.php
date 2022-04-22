<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Bioquimico.php';
require_once 'Biochemicalgroup.php';
require_once 'Group.php';
require_once 'Controller.php';

class Admin_BiochemicalsController extends Controller {

    protected $singular = "Bioquímico";
    protected $plural = "todos los Bioquímicos";
    protected $messageDelete = "¿Esta seguro que desea eliminar este Bioquímico?";
    protected $title = "Bioquímicos";

    public function init() {

        $fields = array(
            array('field' => 'IDBioquimico', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true),
            array('field' => 'BMatricula', 'label' => 'Matrícula', 'required' => 'required', 'list' => true, 'search' => true, 'restrict'=>true,'order' => true),
            array('field' => 'BNombre', 'label' => 'Nombre Completo', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'BDni', 'label' => 'DNI'),
            array('field' => 'BDireccion', 'label' => 'Dirección'),
            array('field' => 'BLocalidad', 'label' => 'Localidad'),
            array('field' => 'BTelefono', 'label' => 'Teléfono'),
            array('field' => 'BCelular', 'label' => 'Celular'),
            array('field' => 'BEmail', 'label' => 'E-Mail', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'BPassword', 'label' => 'Contraseña','required' => 'required'),
            array('field' => 'aditional_information', 'label' => 'aditional_information', 'notsave'=>true, 'type' => 'partial-view', 'file' => $this->_request->getParam('controller').'/groups.phtml'),
            array('field' => 'BEnvio', 'label' => 'Enviar resultados por E-Mail.','type'=>'checkbox'),
            array('field' => 'BEnvio', 'label' => 'Enviar resultados por E-Mail.','notsave'=>true,'notdisplay'=>true,'class' => 'text-center strong', 'list' => true, 'order' => true, 'type' => 'combo', 'data' => array('0' => 'NO', '1' => 'SI'))
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
        $this->model = new Model_DBTable_Bioquimico();
        $this->model_group = new Model_DBTable_Group();
        $this->model_biochemical_group = new Model_DBTable_Biochemicalgroup();

    }

    public function addAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $this->data = $this->_helper->Form->isValid($this->fields);
                $id = $this->model->add($this->data);
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_NEW));
                $this->_helper->Redirector->gotoSimple('add', null, null);
            }
            $this->view->groups=$this->model_group->listAll();
            $this->view->groupsSelected=array();
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
                $this->model_biochemical_group->deleteStrongBy('bi_gr_bi_id='.$this->view->parameters["id"]);
                if(count($_POST["groups"])){
                    foreach ($_POST["groups"] as $key => $value) {
                        $this->model_biochemical_group->add(array('bi_gr_bi_id'=>$this->view->parameters["id"],'bi_gr_gr_id'=>$key));
                    }
                }
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_EDI));
                $this->_helper->Redirector->gotoSimple('index', null, null);
            }
            $this->view->groups=$this->model_group->listAll();
            $this->view->groupsSelected=$this->model_biochemical_group->listAll("bi_gr_bi_id=".$this->view->parameters["id"]);
            $this->view->title = $this->title . ' / Editar';
            $this->view->token = $this->_helper->Form->setToken();
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('edit', null, null, array('id' => $this->view->parameters["id"]));
        }
    }

    public function detailAction() {
        try {
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            $this->view->groups=$this->model_group->listAll();
            $this->view->groupsSelected=$this->model_biochemical_group->listAll("bi_gr_bi_id=".$this->view->parameters["id"]);
            $this->view->formDisabled = true;
            $this->view->title = $this->title . ' / Detalle';
            $this->view->description = 'Detalle de ' . $this->title . ' "' . $this->view->result[$this->view->fields[2]["field"]] . '"';
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }
}
