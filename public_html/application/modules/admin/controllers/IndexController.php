<?php

require_once 'Resultado.php';
require_once 'Resultadodeterminacion.php';
require_once 'Bioquimico.php';

class Admin_IndexController extends Zend_Controller_Action {

    public function init() {
        try {
            $this->view->loginInfo = $this->_helper->Login->isLogin();
            $this->view->setting = $this->_helper->Setting->getSetting();
            $this->view->parameters = $this->_request->getParams();
            $this->view->messages = $this->_helper->flashMessenger->getMessages();

            //LAYOUT
            $this->response = $this->getResponse();
            if ($this->getRequest()->isXmlHttpRequest()) {
                $this->_helper->getHelper('layout')->disableLayout();
            } elseif ($this->view->parameters["popup"] == true) {
                Zend_Layout::startMvc(array('layout' => 'iframe', 'layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
            } else {
                Zend_Layout::startMvc(array('layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
            }
            $this->view->title = $this->title;
            $this->result=new Model_DBTable_Resultado();
            $this->result_determinacion=new Model_DBTable_Resultadodeterminacion();
            $this->biochemicals=new Model_DBTable_Bioquimico();
            $this->search = new Zend_Session_Namespace('search');
        } catch (Zend_Exception $exc) {
            exit($exc->getMessage());
        }
    }

    public function indexAction(){   
    try {
        if($this->search->parameters['method']=='form1'){
            $this->view->fecha=($this->search->parameters['fecha'])?$this->search->parameters['fecha']:date('d/m/Y');
            $this->view->matricula=$this->search->parameters['matricula'];
            $this->view->method=$this->search->parameters['method'];
            $result=$this->result->getByDate($this->_helper->Date->getDateFormatted(str_replace('/', '-', $this->view->fecha)),$this->view->matricula,$this->view->parameters['sort'],$this->view->parameters['order']);
        }else if($this->search->parameters['method']=='form2'){
            $result=$this->result->getNroMuestra($this->search->parameters['muestra'],$this->view->parameters['sort'],$this->view->parameters['order']);
            $this->view->method=$this->search->parameters['method'];
            $this->view->muestra=$this->search->parameters['muestra'];
        }else{
            $this->view->fecha=($this->search->parameters['fecha'])?$this->search->parameters['fecha']:date('d/m/Y');
            $result=$this->result->getByDate(date('Y-m-d'));
        }
        $this->view->biochemicals=$this->biochemicals->showAll(null,"BNombre");
        
        $paginator = Zend_Paginator::factory($result);
        $paginator->setItemCountPerPage(COUNTPERPAGE)
        ->setCurrentPageNumber($this->_getParam('page', 1))
        ->setPageRange(PAGERANGE);
        $this->view->results = $paginator;
        } catch (Exception $e) {
         exit($e->getMessage()); 
      }  
    }

    public function loadAction(){  
        $this->view->results=$this->result->getByMuestra($this->view->parameters['RMuestra']);
    }
    public function searchAction(){
        $this->search->parameters = $this->view->parameters;
        $this->_helper->Redirector->gotoSimple('index');
    }
    public function rmAction(){ 
        $this->search->parameters = array();
        $this->_helper->Redirector->gotoSimple('index');
    }

}
