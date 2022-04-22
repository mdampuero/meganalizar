<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Newsletter.php';
require_once 'NewsletterReceiver.php';
require_once 'New.php';
require_once 'Group.php';
require_once 'Biochemicalgroup.php';
require_once 'Bioquimico.php';
require_once 'Controller.php';

class Admin_NewslettersController extends Controller {

    protected $singular = "Newsletter";
    protected $plural = "todos los Newsltters";
    protected $messageDelete = "¿Esta seguro que desea eliminar esta Nesletter?";
    protected $title = "Newsletter";

    public function init() {

        $fields = array(
            array('field' => 'nl_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true, 'search' => true),
            array('field' => 'send', 'label' => 'biochemicals',  'type' => 'partial-view', 'notsave'=>true,'file' => $this->_request->getParam('controller').'/send.phtml'),
            array('field' => 'nl_title', 'label' => 'Título', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'nl_file', 'label' => 'Adjunto', 'list' => true, 'search' => true, 'order' => true,'type'=>'file'),
            array('field' => 'nl_body', 'label' => 'Cuerpo del Newsletter', 'type' => 'textarea', 'html' => true, 'order' => true),
            array('field' => 'biochemicals', 'label' => 'biochemicals',  'type' => 'partial-view', 'notsave'=>true,'file' => $this->_request->getParam('controller').'/biochemicals.phtml'),
            array('field' => 'nl_status', 'label' => 'Enviado','notsave'=>true,'notdisplay'=>true, 'class' => 'text-center strong', 'list' => true, 'order' => true, 'type' => 'combo', 'data' => array('0' => 'NO', '1' => 'SI')),
        );
        $actions = array(
            array('type' => 'link', 'label' => 'Agregar ' . $this->singular, 'icon' => 'plus', 'controller' => $this->_request->getParam('controller'), 'action' => 'add'),
            array('type' => 'link', 'label' => 'Listar ' . $this->plural, 'icon' => 'list', 'controller' => $this->_request->getParam('controller'), 'action' => 'index'),
            array('type' => 'link', 'label' => 'Exportar a Excel ' . $this->plural, 'icon' => 'floppy-saved', 'controller' => $this->_request->getParam('controller'), 'action' => 'excel'),
        );
        $options = array(
            array('type' => 'link', 'title' => 'Enviar newsletter', 'icon' => 'glyphicon glyphicon-send text-primary', 'controller' => $this->_request->getParam('controller'), 'action' => 'detail'),
            array('type' => 'link', 'title' => 'Eliminar', 'icon' => 'glyphicon glyphicon-ban-circle text-danger', 'controller' => $this->_request->getParam('controller'), 'action' => 'delete', 'dialog' => true,
                'dialog_message' => $this->messageDelete)
        );
        parent::init($fields, $actions, $options);
        $this->model = new Model_DBTable_Newsletter();
        $this->newsletter_receiver = new Model_DBTable_NewsletterReceiver();
        $this->new = new Model_DBTable_New();
        $this->group = new Model_DBTable_Group();
        $this->biochemical_group = new Model_DBTable_Biochemicalgroup();
        $this->biochemicals = new Model_DBTable_Bioquimico();
    }

    public function addAction() {
        try {
            if ($this->getRequest()->isPost()) {
                
                $this->data = $this->_helper->Form->isValid($this->fields);
                $id = $this->model->add($this->data);
                if($receivers=$_POST["biochemicals"]){
                    foreach ($receivers as $key => $receiver) {
                        $this->newsletter_receiver->add(array('nr_nl_id'=>$id,'nr_receiver'=>$receiver));
                    }
                }
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_NEW));
                $this->_helper->Redirector->gotoSimple('detail', null, null,array('id'=>$id));
            }
            $this->view->news=$this->new->listAll("ne_status=1");
            $groups=$this->group->listAll();
            foreach ($groups as $key => $value) {
                if($bio=$this->biochemical_group->getByGroup($key))
                    $all[$value]=$bio;
            }
            $this->view->groups=$all;
            $this->view->biochemicals=$this->biochemicals->showAll('BEnvio=1');
            $this->view->news_selected=array();
            $this->view->title = $this->title . ' / Nuevo';
            $this->view->token = $this->_helper->Form->setToken();
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('add');
        }
    }
    public function detailAction() {
        try {
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            $this->view->formDisabled = true;
            $this->view->title = $this->title . ' / Detalle';
            $this->view->description = 'Detalle de ' . $this->title . ' "' . $this->view->result[$this->view->fields[2]["field"]] . '"';
            $this->view->biochemicals=$this->newsletter_receiver->getByNewsletter($this->view->parameters["id"]);
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }

    public function sendAction() {
        $this->view->result = $this->model->get($this->view->parameters["id"]);
        $receiver = $this->newsletter_receiver->get($this->view->parameters["next_id"]);
        $this->newsletter_receiver->edit(array('nr_status' => 1),$this->view->parameters["next_id"]);
        if ($this->view->result["nl_file"]){
            $attach = PATH_IMG  . $this->view->result["nl_file"];
            $attach_filename = $this->view->result["nl_file_filename"];
        }
        Zend_Layout::startMvc(array('layout' => 'email', 'layoutPath' => '../application/modules/admin/layouts/scripts/'));
        $layout = $this->_helper->layout->getLayoutInstance();
        $layout->content = $this->view->result['nl_body'];
        $htmlcontent =  $layout->render();
        $this->_helper->Mail->sendEmail($htmlcontent, $this->view->result['nl_title'], $receiver["nr_receiver"], $receiver["nr_receiver"],$attach,$attach_filename);
        $this->_redirect('/admin/newsletters/start/popup/1/id/' . $this->view->parameters["id"]);
    }

    public function startAction() {
        try {
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            $this->view->biochemicals=$this->newsletter_receiver->getBynewsletter($this->view->parameters["id"]);
            $this->view->next = $this->newsletter_receiver->next($this->view->parameters["id"]);
            if (!$this->view->next) {
                $this->view->close = true;
                $this->model->edit(array("nl_status" => 1),$this->view->parameters["id"]);
            }else{
                $c=0;
                foreach ($this->view->biochemicals as $key => $biochemicals) 
                    if($biochemicals["nr_status"]==1) $c++;
                $this->view->percent=(int)($c*100/count($this->view->biochemicals));
                $this->view->interval=rand(2000, 20000);
            }
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }
}
