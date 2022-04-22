<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'New.php';
require_once 'Controller.php';

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
}
