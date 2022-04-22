<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Slider.php';
require_once 'Controller.php';

class Admin_SliderController extends Controller {

    protected $singular = "Slider";
    protected $plural = "todas las imágenes";
    protected $messageDelete = "¿Esta seguro que desea eliminar esta Imagen?";
    protected $title = "Imágenes";

    public function init() {

        $fields = array(
            array('field' => 'sl_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true),
            array('field' => 'sl_name', 'label' => 'Título', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'sl_picture', 'label' => 'Imagen', 'type' => 'image', 'order' => true, 'list' => true, 'width' => '100px'),
            array('field' => 'sl_status', 'label' => 'Habilitado', 'class' => 'visible-lg visible-md visible-sm', 'list' => true, 'order' => true, 'type' => 'combo', 'data' => array('0' => 'NO', '1' => 'SI')),
            array('field' => 'sl_order', 'label' => 'Orden', 'list' => true, 'order' => true, 'type' => 'number'),
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
        $this->model = new Model_DBTable_Slider();
    }

}
