<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Slider.php';

class Api_SliderController extends Zend_Rest_Controller {

    public $response;

    public function init() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $this->_helper->AjaxContext()
                ->addActionContext('index', 'json')
                ->addActionContext('get', 'json')
                ->addActionContext('post', 'json')
                ->addActionContext('put', 'json')
                ->addActionContext('delete', 'json')
                ->initContext('json');
        $this->response = new StdClass();
        $request = new Zend_Controller_Request_Http();
        $key = $request->getHeader('key', false);
        if ($key !== "Secreta007"):
            $this->response->response = 0;
            $this->response->data = 'Key inválida!.';
            $this->sendResponse($this->_helper->json($this->response));
        endif;
    }

    protected function getBaseUrl() {
        $view = Zend_Layout::getMvcInstance()->getView();
        return HOST . $view->baseUrl();
    }

    public function indexAction() {
        try { 
            $this->model = new Model_DBTable_Slider();
            $this->response->response = 1;
            $results=$this->model->showAll();
            foreach ($results as $key => $result) {
                $results[$key]["sl_picture"]=$this->getBaseUrl()."/files/".$result["sl_picture"];
            }
            $this->response->data = $results;
            $this->sendResponse($this->_helper->json($this->response));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->data = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }

    public function getAction() {
        
    }

    public function postAction() {
        
    }

    public function putAction() {
        
    }

    public function deleteAction() {
        
    }

}
