<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Log.php';
require_once 'Controller.php';

class Admin_LogController extends Controller {

    protected $singular = "Log";
    protected $plural = "todos los logs";
    protected $messageDelete = "¿Esta seguro que desea eliminar este Log?";
    protected $title = "Logs";

    public function init() {

        $fields = array(
            array('field' => 'lo_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true),
            array('field' => 'lo_description', 'label' => 'Descripción', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'lo_url', 'label' => 'Url', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'lo_request', 'label' => 'Request', 'required' => 'required', 'list' => false, 'type' => 'code', 'order' => true),
            array('field' => 'lo_data', 'label' => 'Datos', 'required' => 'required', 'list' => false, 'type' => 'code', 'order' => true),
            array('field' => 'lo_created', 'label' => 'Fecha y hora', 'list' => true, 'type' => 'time', 'order' => true, 'notsave' => true)
        );
        $actions = array(
            array('type' => 'link', 'label' => 'Listar ' . $this->plural, 'icon' => 'list', 'controller' => $this->_request->getParam('controller'), 'action' => 'index'),
            array('type' => 'link', 'label' => 'Exportar a Excel ' . $this->plural, 'icon' => 'floppy-saved', 'controller' => $this->_request->getParam('controller'), 'action' => 'excel')
        );
        $options = array(
            array('type' => 'link', 'title' => 'Detalle', 'icon' => 'glyphicon glyphicon-eye-open text-primary', 'controller' => $this->_request->getParam('controller'), 'action' => 'detail', 'modal' => false)
        );
        parent::init($fields, $actions, $options);
        $this->model = new Model_DBTable_Log();
    }
}
