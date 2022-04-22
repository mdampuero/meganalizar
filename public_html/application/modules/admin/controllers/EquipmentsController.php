<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Equipment.php';
require_once 'Sector.php';
require_once 'Controller.php';

class Admin_EquipmentsController extends Controller {

    protected $singular = "Equipo";
    protected $plural = "todos los Equipos";
    protected $messageDelete = "¿Esta seguro que desea eliminar este Equipo?";
    protected $title = "Equipos";

    public function init() {
        $this->sector = new Model_DBTable_Sector();
        $fields = array(
            array('field' => 'eq_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true),
            array('field' => 'eq_name', 'label' => 'Nombre', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'eq_sc_id', 'label' => 'Sector', 'required' => 'required', 'list' => true, 'search' => false, 'order' => true,'type'=>'combo','data'=>$this->sector->listAll()),
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
        $this->model = new Model_DBTable_Equipment();
    }
}
