<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Billing.php';
require_once 'Billingdetail.php';
require_once 'Controller.php';

class Admin_BillingsController extends Controller {

    protected $singular = "Detalle de Facturación";
    protected $plural = "todos los Detalles de Facturación";
    protected $messageDelete = "¿Esta seguro que desea eliminar este Detalle de Facturación?";
    protected $title = "Detalles de Facturación";

    public function init() {

        $fields = array(
            array('field' => 'bi_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true),
            array('field' => 'bi_file_filename', 'label' => 'Nombre del archivo', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true,'notsave'=>true,'notdisplay'=>true),
            array('field' => 'bi_file', 'label' => 'Archivo', 'list' => true, 'search' => true, 'order' => true,'type'=>'file'),
        );
        $actions = array(
            array('type' => 'link', 'label' => 'Agregar ' . $this->singular, 'icon' => 'plus', 'controller' => $this->_request->getParam('controller'), 'action' => 'add'),
            array('type' => 'link', 'label' => 'Listar ' . $this->plural, 'icon' => 'list', 'controller' => $this->_request->getParam('controller'), 'action' => 'index'),
            array('type' => 'link', 'label' => 'Exportar a Excel ' . $this->plural, 'icon' => 'floppy-saved', 'controller' => $this->_request->getParam('controller'), 'action' => 'excel'),
        );
        $options = array(
            array('type' => 'link', 'title' => 'Detalle', 'icon' => 'glyphicon glyphicon-eye-open text-primary', 'controller' => $this->_request->getParam('controller'), 'action' => 'detail'),
            array('type' => 'link', 'title' => 'Eliminar', 'icon' => 'glyphicon glyphicon-ban-circle text-danger', 'controller' => $this->_request->getParam('controller'), 'action' => 'delete', 'dialog' => true,
                'dialog_message' => $this->messageDelete)
        );
        parent::init($fields, $actions, $options);
        $this->model = new Model_DBTable_Billing();
        $this->billing_detail = new Model_DBTable_Billingdetail();
    }
    public function addAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $this->data = $this->_helper->Form->isValid($this->fields);
                if ($_FILES["bi_file"]["error"] != 0)
                    throw new Zend_Controller_Action_Exception("El archivo no se pudo subir", 599);
                $extension = @strtolower(end(explode(".", $_FILES["bi_file"]["name"])));
                if ($extension != "txt") throw new Zend_Controller_Action_Exception("El archivo debe ser Txt", 599);
                $id = $this->model->add($this->data);
                $this->billing_detail->importarSql(PATH_IMG.$this->data["bi_file"],$id);
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_NEW));
                $this->_helper->Redirector->gotoSimple('detail', null, null,array('id'=>$id));
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
                $data=$this->model->get($this->getRequest()->getPost('id'));
                $this->billing_detail->deleteStrongBy('bd_file="'.$data['bi_file'].'"');
                $this->model->delete_slow($this->getRequest()->getPost('id'));
            }
        } catch (Zend_Exception $exc) {
            exit($exc->getMessage());
        }
    }
}
