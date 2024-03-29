<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'New.php';
require_once 'Controller.php';
require_once 'Bioquimico.php';
require_once 'Notification.php';

class Admin_NewsController extends Controller {

    protected $singular = "Noticia";
    protected $plural = "todos las Noticias";
    protected $messageDelete = "¿Esta seguro que desea eliminar esta Noticia?";
    protected $title = "Noticias";

    public function init() {

        $fields = array(
            array('field' => 'ne_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true),
            array('field' => 'ne_title', 'label' => 'Título', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'ne_date', 'label' => 'Fecha', 'required' => 'required|date_es', 'list' => true, 'search' => true, 'order' => true,'type'=>'date','calendar'=>true),
            array('field' => 'ne_description', 'label' => 'Contenido', 'type' => 'textarea', 'html' => true, 'order' => true),
            array('field' => 'ne_picture', 'label' => 'Foto', 'type' => 'image', 'order' => true, 'list' => true, 'width' => '100px'),
            array('field' => 'ne_status', 'label' => 'Habilitado', 'class' => 'text-center strong', 'list' => true, 'order' => true, 'type' => 'combo', 'data' => array('0' => 'NO', '1' => 'SI')),
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
        $this->model = new Model_DBTable_New();
    }

    public function addAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $this->data = $this->_helper->Form->isValid($this->fields);
                $id = $this->model->add($this->data);
                $notification = new Model_DBTable_Notification();
                $bioquimicos = new Model_DBTable_Bioquimico();
                $users=$bioquimicos->showAll();
                foreach ($users as $user){
                    $notification->save([
                        'ne_id'=>$id,
                        'us_id'=>$user["IDBioquimico"],
                        'is_read'=>0,
                    ]);
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
    public function deleteAction() {
        try {
            $this->_helper->viewRenderer->setNoRender(TRUE);
            if ($this->getRequest()->isPost()) {
                $notification = new Model_DBTable_Notification();
                $this->model->delete_slow($this->getRequest()->getPost('id'));
                $notification->removeByNew($this->getRequest()->getPost('id'));
            }
        } catch (Zend_Exception $exc) {
            exit($exc->getMessage());
        }
    }
}
