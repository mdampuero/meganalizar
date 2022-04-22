<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Roles.php';
require_once 'Controller.php';

class Admin_RolesController extends Controller {

    protected $singular = "Rol de Administrador";
    protected $plural = "todos los Roles de Administrador";
    protected $messageDelete = "¿Esta seguro que desea eliminar este Rol de Administrador?";
    protected $title = "Configuración / Roles de Administrador";

    public function init() {

        $fields = array(
                array('field' => 'ar_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true),
                array('field' => 'ar_name', 'label' => 'Nombre','list-edit' => true, 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
                array('field' => 'ar_created', 'label' => 'Creado', 'list' => true, 'type' => 'date', 'order' => true, 'notsave' => true, 'notdisplay' => true),
                array('field' => 'ar_modified', 'label' => 'Modificado', 'list' => true, 'type' => 'date', 'order' => true, 'notsave' => true, 'notdisplay' => true),
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
        $this->model = new Model_DBTable_Roles();
    }
}
