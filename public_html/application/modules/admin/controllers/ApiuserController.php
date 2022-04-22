<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Apiuser.php';
require_once 'Controller.php';

class Admin_ApiuserController extends Controller {

    protected $singular = "Usuario Api";
    protected $plural = "todos los Usuarios Api";
    protected $messageDelete = "¿Esta seguro que desea eliminar este Usuario?";
    protected $title = "Configuración / Usuarios Api";

    public function init() {
        $fields = array(
            array('field' => 'au_id', 'label' => 'N', 'list' => true, 'width' => 100, 'class' => 'id', 'order' => true),
            array('field' => 'au_username', 'label' => 'Usuario/E-Mail', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'au_password', 'label' => 'Clave', 'type' => 'password', 'required' => 'required'),
            array('field' => 'au_status', 'label' => 'Habilitado','list' => true, 'order' => true, 'type' => 'combo', 'data' => array('0' => 'NO', '1' => 'SI')),
            array('field' => 'au_created', 'label' => 'Creado', 'list' => true, 'type' => 'date', 'order' => true, 'notsave' => true, 'notdisplay' => true),
        );
        $actions = array(
            array('type' => 'link', 'label' => 'Agregar ' . $this->singular, 'icon' => 'plus', 'controller' => $this->_request->getParam('controller'), 'action' => 'add'),
            array('type' => 'link', 'label' => 'Listar ' . $this->plural, 'icon' => 'list', 'controller' => $this->_request->getParam('controller'), 'action' => 'index'),
            array('type' => 'link', 'label' => 'Exportar a Excel ' . $this->plural, 'icon' => 'floppy-saved', 'controller' => $this->_request->getParam('controller'), 'action' => 'excel'),
        );
        $options = array(
            array('type' => 'link', 'title' => 'Detalle', 'icon' => 'glyphicon glyphicon-eye-open text-primary', 'controller' => $this->_request->getParam('controller'), 'action' => 'detail', 'modal' => true),
            array('type' => 'link', 'title' => 'Editar', 'icon' => 'glyphicon glyphicon-edit text-primary', 'controller' => $this->_request->getParam('controller'), 'action' => 'edit'),
            array('type' => 'link', 'title' => 'Eliminar', 'icon' => 'glyphicon glyphicon-ban-circle text-danger', 'controller' => $this->_request->getParam('controller'), 'action' => 'delete', 'dialog' => true,
                'dialog_message' => $this->messageDelete)
        );
        parent::init($fields, $actions, $options);
        $this->model = new Model_DBTable_Apiuser();
    }

    public function addAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $this->data = $this->_helper->Form->isValid($this->fields);
                $this->data['au_password'] = md5($this->data['au_password']);
                $this->model->add($this->data);
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
                if (!empty($_POST["au_password"])){
                    $this->data['au_password'] = md5($this->data['au_password']);
                }else{
                    unset($this->data['au_password']);
                }
                $this->model->edit($this->data, $this->view->parameters["id"]);
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_EDI));
                $this->_helper->Redirector->gotoSimple('index', null, null);
            }
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            $this->view->title = $this->title . ' / Editar';
            $this->view->token = $this->_helper->Form->setToken();
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('edit', null, null, array('id' => $this->view->parameters["id"]));
        }
    }
}
