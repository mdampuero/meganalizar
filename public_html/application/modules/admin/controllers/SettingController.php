<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Setting.php';
require_once 'Controller.php';

class Admin_SettingController extends Controller {

    protected $singular = "Configuración General";
    protected $plural = "todos los parámetros de configuración";
    protected $title = "Configuración / General";

    public function init() {

        $fields = array(
            array('field' => 'se_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true, 'search' => true),
            array('field' => 'se_title', 'label' => 'Nombre del Sitio', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'se_footer', 'label' => 'Pie de Página', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'se_system_email', 'label' => 'E-Mail de sistema', 'required' => 'required'),
            array('field' => 'se_api_key', 'label' => 'Api Key', 'required' => 'required'),
            array('field' => 'se_img_logo_header', 'label' => 'Imagen', 'type' => 'image'),
            array('field' => 'se_count_page', 'label' => 'Máximo registro por página', 'required' => 'required'),
            array('field' => 'se_page_range', 'label' => 'Máximo rango por página', 'required' => 'required'),
            array('field' => 'se_email_host', 'label' => 'SMTP Servidor', 'required' => 'required'),
            array('field' => 'se_email_port', 'label' => 'SMTP Puerto', 'required' => 'required'),
            array('field' => 'se_email_user', 'label' => 'SMTP Usuario', 'required' => 'required'),
            array('field' => 'se_email_password', 'label' => 'SMTP Clave', 'required' => 'required'),
            array('field' => 'se_email_email', 'label' => 'SMTP E-Mail', 'required' => 'required'),
            array('field' => 'se_email_name', 'label' => 'SMTP Nombre', 'required' => 'required'),
            array('field' => 'se_email_secure', 'label' => 'SMTP Seguridad'),
            array('field' => 'sendTest', 'label' => 'Enviar E-Mail de prueba', 'notsave' => true, 'type' => 'checkbox'),
        );
        $actions = array(
            array('type' => 'link', 'label' => 'Listar ' . $this->plural, 'icon' => 'list', 'controller' => $this->_request->getParam('controller'), 'action' => 'index'),
        );
        $options = array(
            array('type' => 'link', 'title' => 'Detalle', 'icon' => 'glyphicon glyphicon-eye-open text-primary', 'controller' => $this->_request->getParam('controller'), 'action' => 'detail', 'modal' => true),
            array('type' => 'link', 'title' => 'Editar', 'icon' => 'glyphicon glyphicon-edit text-primary', 'controller' => $this->_request->getParam('controller'), 'action' => 'edit'),
        );
        parent::init($fields, $actions, $options);
        $this->model = new Model_DBTable_Setting();
    }

    public function editAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $this->data = $this->_helper->Form->isValid($this->fields);
                $this->model->edit($this->data, $this->view->parameters["id"]);
                if ($_POST["sendTest"] == 1){
                    $this->_mail = $this->_helper->Mail;
                    /* DEFINE LAYUT EMAIL*/
                    Zend_Layout::startMvc(array('layout' => 'email', 'layoutPath' => '../application/modules/admin/layouts/scripts/'));
                    $layout = $this->_helper->layout->getLayoutInstance();
                    $layout->content = '<h2>Felicitaciones! Configuracion SMTP correcta</h2><br/><p>Si Ud. logra visualizar este E-Mail es porque ha configurado correctamente todos los parámetros de envío SMTP.</p>';
                    $htmlcontent =  $layout->render();
                    $this->_mail->sendEmail($htmlcontent, "Prueba de Configuración de Correo", $this->view->setting["se_system_email"], $this->view->setting["se_system_email"]);
                    $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => "Los cambios se guardaron correctamente.<br/>Se envió un correo de prueba a '" . $this->view->setting["se_system_email"] . "'"));
                }else{
                    $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => "Los cambios se guardaron correctamente"));
                }
                $this->_helper->Redirector->gotoSimple('edit', null, null, array('status' => 'success', 'id' => 1));
            }
            $this->view->title = $this->title . ' / Editar';
            $this->view->token = $this->_helper->Form->setToken();
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('edit', 'setting', 'admin', array('id' => 1));
        }
    }

}
