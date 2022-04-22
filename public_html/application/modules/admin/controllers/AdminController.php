<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Admin.php';
require_once 'Roles.php';
require_once 'Controller.php';

class Admin_AdminController extends Controller {

    protected $singular = "Administrador";
    protected $plural = "todos los Administradores";
    protected $messageDelete = "¿Esta seguro que desea eliminar este Administrador?";
    protected $title = "Configuración / Administradores";

    public function init() {
        $this->roles = new Model_DBTable_Roles();
        $fields = array(
            array('field' => 'ad_id', 'label' => 'N', 'list' => true, 'width' => 100, 'class' => 'id', 'order' => true),
            array('field' => 'ad_ar_id', 'label' => 'Rol', 'required' => 'required', 'type' => 'combo', 'data' => $this->roles->listAll()),
            array('field' => 'ar_name', 'label' => 'Rol', 'notdisplay' => true, 'notsave' => true, 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'ad_name', 'label' => 'Nombre', 'list-edit' => false, 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'ad_last', 'label' => 'Apellido', 'list-edit' => false, 'required' => 'required', 'list' => true, 'search' => true, 'order' => true, 'class' => 'hidden-xs'),
            array('field' => 'ad_user', 'label' => 'Nombre de Usuario', 'list-edit' => false, 'required' => 'required', 'list' => true, 'search' => true, 'order' => true, 'class' => 'hidden-xs'),
            array('field' => 'ad_password', 'label' => 'Clave', 'type' => 'password', 'required' => 'required'),
            array('field' => 'ad_email', 'label' => 'E-Mail', 'required' => 'required|email'),
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
        $this->model = new Model_DBTable_Admin();
    }

    public function addAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $this->data = $this->_helper->Form->isValid($this->fields);
                $this->data['ad_password'] = md5($this->data['ad_password']);
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
                if (empty($_POST["ad_password"])):
                    unset($this->fields[6]);
                endif;
                $this->data = $this->_helper->Form->isValid($this->fields);
                if (!empty($_POST["ad_password"])):
                    $this->data['ad_password'] = md5($this->data['ad_password']);
                endif;
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
