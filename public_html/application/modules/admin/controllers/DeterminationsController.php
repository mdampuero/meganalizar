<?php

/*
* Author: Mauricio Ampuero 
* Organization: Inamika Interactive
* E-Mail: mdampuero@gmail.com
*/

require_once 'Determination.php';
require_once 'Determinationequipment.php';
require_once 'Determinationlab.php';
require_once 'Sector.php';
require_once 'Method.php';
require_once 'Lab.php';
require_once 'Equipment.php';
require_once 'Controller.php';

class Admin_DeterminationsController extends Controller {

    protected $singular = "Determinación";
    protected $plural = "todas las Determinaciones";
    protected $messageDelete = "¿Esta seguro que desea eliminar esta Determinación?";
    protected $title = "Determinaciones";

    public function init() {
        $this->sector = new Model_DBTable_Sector();
        $this->method = new Model_DBTable_Method();

        $fields = array(
            array('field' => 'de_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true),
            array('field' => 'de_nomenclature', 'label' => 'Nomenclador', 'required' => 'required', 'list' => true,'restrict'=>true, 'search' => true, 'order' => true),
            array('field' => 'de_name', 'label' => 'Nombre', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'de_sc_id', 'label' => 'Sector', 'required' => 'required', 'list' => true, 'search' => false, 'order' => true,'type'=>'combo','data'=>$this->sector->listAll()),
            array('field' => 'de_me_id', 'label' => 'Método', 'required' => 'required', 'list' => true, 'search' => false, 'order' => true,'type'=>'combo','data'=>$this->method->listAll()),
            array('field' => 'de_status', 'label' => 'Publicada',  'list' => true, 'order' => true, 'type' => 'combo', 'data' => array('0' => 'NO', '1' => 'SI')),
            array('field' => 'de_proccess', 'label' => 'Días de procesamiento'),
            array('field' => 'equipment', 'label' => 'equipment',  'type' => 'partial-view', 'notsave'=>true, 'file' => $this->_request->getParam('controller').'/equipment.phtml'),
            array('field' => 'technical_information', 'label' => 'technical_information', 'notsave'=>true,  'type' => 'partial-view', 'file' => $this->_request->getParam('controller').'/technical_information.phtml'),
            array('field' => 'aditional_information', 'label' => 'aditional_information', 'notsave'=>true, 'type' => 'partial-view', 'file' => $this->_request->getParam('controller').'/aditional_information.phtml'),
            array('field' => 'prices', 'label' => 'prices',  'type' => 'partial-view', 'notsave'=>true,'file' => $this->_request->getParam('controller').'/prices.phtml'),

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
        $this->model = new Model_DBTable_Determination();
        $this->model_equipment = new Model_DBTable_Equipment();
        $this->model_determination_equipment = new Model_DBTable_Determinationequipment();
        $this->model_determination_lab = new Model_DBTable_Determinationlab();
        $this->model_lab = new Model_DBTable_Lab();
    }

    public function addAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $this->data = $this->_helper->Form->isValid($this->fields);
                $this->data["de_shipping"]=$_POST["de_shipping"];
                $this->data["de_sample"]=$_POST["de_sample"];
                $this->data["de_reference_value"]=$_POST["de_reference_value"];   
                $this->data["de_patient_indication"]=$_POST["de_patient_indication"];   
                $this->data["de_biochemical_indication"]=$_POST["de_biochemical_indication"]; 
                $id = $this->model->add($this->data);
                if(count($_POST["equipments"])){
                    foreach ($_POST["equipments"] as $key => $value) {
                        $this->model_determination_equipment->add(array('de_eq_de_id'=>$id,'de_eq_eq_id'=>$key));
                    }
                }
                if(count($_POST["prices"])){
                    foreach ($_POST["prices"] as $key => $value) {
                        $this->model_determination_lab->add(array('de_la_de_id'=>$id,'de_la_la_id'=>$key,'de_la_price'=>$value["price"],'de_la_reference_value'=>$value["reference"]));
                    }
                }
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_NEW));
                $this->_helper->Redirector->gotoSimple('add', null, null);
            }
            $this->view->equipments=$this->model_equipment->listAll();
            $this->view->labs=$this->model_lab->listAll("","la_order","ASC");
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
                $this->data["de_shipping"]=$_POST["de_shipping"];
                $this->data["de_sample"]=$_POST["de_sample"];
                $this->data["de_reference_value"]=$_POST["de_reference_value"];   
                $this->data["de_patient_indication"]=$_POST["de_patient_indication"];   
                $this->data["de_biochemical_indication"]=$_POST["de_biochemical_indication"];
                $this->model_determination_equipment->deleteStrongBy('de_eq_de_id='.$this->view->parameters["id"]);
                if(count($_POST["equipments"])){
                    foreach ($_POST["equipments"] as $key => $value) {
                        $this->model_determination_equipment->add(array('de_eq_de_id'=>$this->view->parameters["id"],'de_eq_eq_id'=>$key));
                    }
                }
                $this->model_determination_lab->deleteStrongBy('de_la_de_id='.$this->view->parameters["id"]);
                if(count($_POST["prices"])){
                    foreach ($_POST["prices"] as $key => $value) {
                        $this->model_determination_lab->add(array('de_la_de_id'=>$this->view->parameters["id"],'de_la_la_id'=>$key,'de_la_price'=>$value["price"],'de_la_reference_value'=>$value["reference"]));
                    }
                }
                $this->model->edit($this->data, $this->view->parameters["id"]);
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_EDI));
                $this->_helper->Redirector->gotoSimple('index', null, null);
            }
            $this->view->title = $this->title . ' / Editar';
            $this->view->token = $this->_helper->Form->setToken();

            $this->view->equipments=$this->model_equipment->listAll();
            $this->view->equipments_selected=$this->model_determination_equipment->listAll("de_eq_de_id=".$this->view->parameters["id"]);
            $labs_selected=$this->model_determination_lab->showAll("de_la_de_id=".$this->view->parameters["id"]);
            foreach ($labs_selected as $key => $lab_selected) {
                $labs_data[$lab_selected["de_la_la_id"]]=$lab_selected;
            }
            $this->view->labs_selected=$labs_data;
            $this->view->labs=$this->model_lab->listAll("","la_order","ASC");
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('edit', null, null, array('id' => $this->view->parameters["id"]));
        }
    }
    public function detailAction() {
        try {
            $this->view->equipments=$this->model_equipment->listAll();
            $this->view->equipments_selected=$this->model_determination_equipment->listAll("de_eq_de_id=".$this->view->parameters["id"]);
            $labs_selected=$this->model_determination_lab->showAll("de_la_de_id=".$this->view->parameters["id"]);
            foreach ($labs_selected as $key => $lab_selected) {
                $labs_data[$lab_selected["de_la_la_id"]]=$lab_selected;
            }
            $this->view->labs_selected=$labs_data;
            $this->view->labs=$this->model_lab->listAll("","la_order","ASC");
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            $this->view->formDisabled = true;
            $this->view->title = $this->title . ' / Detalle';
            $this->view->description = 'Detalle de ' . $this->title . ' "' . $this->view->result[$this->view->fields[1]["field"]] . '"';
            if ($this->getRequest()->isXmlHttpRequest()){
                $this->renderScript('_detail.phtml');
            }else{
                $this->renderScript('_form.phtml');
            }
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }
    public function loadlabAction() {
        try {

        } catch (Zend_Exception $exc) {

        }
    }
}
