<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Common.php';

class Controller extends Zend_Controller_Action {

    protected $fields;
    protected $actions;
    protected $options;
    protected $model;
    protected $singular;
    protected $plural;
    protected $messageDelete;
    protected $title;

    public function init($field,$actions,$options) {
        try {
            $this->view->loginInfo = $this->_helper->Login->isLogin();
            $this->view->setting = $this->_helper->Setting->getSetting();
            $this->view->parameters = $this->_request->getParams();
            //SEND DATE VIEW
            $this->fields = $field;
            $this->view->fields = $this->fields;
            $this->actions = $actions;
            $this->view->actions = $this->actions;
            $this->options = $options;
            $this->view->options = $this->options;
            $this->view->messages = $this->_helper->flashMessenger->getMessages();
            if ($this->view->messages[0]["data"])
                $this->view->result = $this->view->messages[0]["data"];
            $this->session_excel = new Zend_Session_Namespace('excel');
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

            //MODELS 
        } catch (Zend_Exception $exc) {
            throw new Zend_Exception($exc->getMessage(), $exc->getCode());
        }
    }

    public function indexAction() {
        try {
            //SAVE MASIVE
            if ($this->getRequest()->isPost()):
                if ($_POST["save"] == 1):
                    if (count($this->fields)):
                        foreach ($this->fields as $field):
                            if ($field['list'] == true && $field['list-edit'] == true) :
                                if (count($_POST[$field["field"]])):
                                    foreach ($_POST[$field["field"]] as $ID => $VALUE):
                                        $_POST[$field["field"]] = $VALUE;
                                        $this->_helper->Form->isValid(array(1 => $field));
                                        $this->data[$ID][$field["field"]] = $VALUE;
                                    endforeach;
                                endif;
                            endif;
                        endforeach;
                    endif;
                    if (count($this->data)):
                        foreach ($this->data as $ID => $data):
                            $this->model->edit($data, $ID);
                        endforeach;
                    endif;
                    $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_EDI));
                    $this->_helper->Redirector->gotoSimple('index', null, null);
                endif;
            endif;
            $this->view->token = $this->_helper->Form->setToken();
            //END SAVE MASIVE
            if (!empty($this->view->parameters['search'])) {
                $where = create_where($this->view->parameters['search'], $this->fields);
            }
            $results_all = $this->model->showAll($where, $this->view->parameters['sort'], $this->view->parameters['order']);
            $this->session_excel->results_all = $results_all;
            $paginator = Zend_Paginator::factory($results_all);
            $paginator->setItemCountPerPage(COUNTPERPAGE)
                    ->setCurrentPageNumber($this->_getParam('page', 1))
                    ->setPageRange(PAGERANGE);

            $this->view->results = $paginator;
            $this->view->enableSearch = true;
            $this->renderScript('_list.phtml');
        } catch (Zend_Exception $exc) {
            throw new Zend_Exception($exc->getMessage(), $exc->getCode());
        }
    }

    public function addAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $this->data = $this->_helper->Form->isValid($this->fields);
                $id = $this->model->add($this->data);
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_NEW));
                $this->_helper->Redirector->gotoSimple('add', null, null);
            }
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
                $this->model->edit($this->data, $this->view->parameters["id"]);
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_EDI));
                $this->_helper->Redirector->gotoSimple('index', null, null);
            }
            $this->view->title = $this->title . ' / Editar';
            $this->view->token = $this->_helper->Form->setToken();
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('edit', null, null, array('id' => $this->view->parameters["id"]));
        }
    }

    public function deleteAction() {
        try {
            $this->_helper->viewRenderer->setNoRender(TRUE);
            if ($this->getRequest()->isPost()) {
                $this->model->delete_slow($this->getRequest()->getPost('id'));
            }
        } catch (Zend_Exception $exc) {
            exit($exc->getMessage());
        }
    }

    public function detailAction() {
        try {
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

    public function excelAction() {
        try {
            set_time_limit(0);
            $this->_helper->getHelper('layout')->disableLayout();
            $this->view->results = $this->session_excel->results_all;
            $this->getResponse()->setRawHeader("Content-Type: application/vnd.ms-excel; charset=UTF-8")
                    ->setRawHeader("Content-Disposition: attachment; filename=" . strtofilename($this->title) . ".xls")
                    ->setRawHeader("Content-Transfer-Encoding: binary")
                    ->setRawHeader("Expires: 0")
                    ->setRawHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0")
                    ->setRawHeader("Pragma: public")
                    ->sendResponse();
            $this->renderScript('_listExcel.phtml');
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

}
