<?php

/*
* Author: Mauricio Ampuero 
* Organization: Inamika Interactive
* E-Mail: mdampuero@gmail.com
*/

class Apicontroller extends Zend_Rest_Controller {

    public $response;
    public $request;
    public $params;
    public $testJson;

    public function init($method,$requiredToken=true) {

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
        $this->request = new Zend_Controller_Request_Http();
        $setting = Zend_Controller_Action_HelperBroker::getStaticHelper('Setting')->getSetting();
        $this->params=json_encode($this->request->getParams());
        
        try {
            if ($method !== $this->request->getMethod())
                throw new Exception('Método inválido',405);
            if($requiredToken){
                $token=$this->request->getParam('authKey', null);
                $token=str_replace('"', '', $token);
                $token=str_replace("'", "", $token);
                // if(empty($token) || $token !=Zend_Controller_Action_HelperBroker::getStaticHelper('Apisecurity')->getToken())
                //     throw new Exception("Unauthorized",401);
            }

        } catch (Exception $ex) {
            $this->response->estado = array('exito'=>0,'codigo'=>$ex->getCode(),'respuesta'=>$ex->getMessage());
            Zend_Controller_Action_HelperBroker::getStaticHelper('Log')->setLog(array('lo_request'=>$this->params,'lo_description'=>'Error - '.$ex->getMessage(),'lo_data'=>$ex->getCode()));
            $this->sendResponse($this->_helper->json($this->response));
        }
    }

    protected function getBaseUrl() {
        $view = Zend_Layout::getMvcInstance()->getView();
        return HOST . $view->baseUrl();
    }
    
    protected function generateToken(){
        return Zend_Controller_Action_HelperBroker::getStaticHelper('Apisecurity')->setToken();
    }

    protected function getTestJson(){
        $testJson='{
            "Socio":{
                "id" : false,
                "matricula" : "1058",
                "descripcion" : "Dr. Lopez",
                "correo" : "algo@hotmail.com",
                "domicilio" : "Calle 13",
                "adicionales" : [{
                    "tipo" : "localidad",
                    "valor" : "godoy cruz"
                }]
            },
            "Orden":{
                "id" : false,
                "matricula" : "1058",
                "numero" : "000192828",
                "fecha" : "20170310",
                "fechaEntrega" : "20170323",
                "estado" : "F",
                "observacion" : "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                "adicionales" : []
            },
            "OrdenEstudio":{
                "id" : 1209276,
                "idOrden" : 1000,
                "codigoEstudio" : "475",
                "nombreEstudio" : "Hemograma",
                "fechaEntrega" : "20170323",
                "estado" : "F"
            },
            "OrdenEstudioPrueba":[{
                "id" : 109202992,
                "codigoPrueba" : "120_1",
                "nombrePrueba" : "GGT",
                "resultado" : "198,30",
                "observacion" : "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                "unidad" : "mg/dl",
                "referencia" : "Hasta 200",
                "metodologia" : "Inmunodifusion Radial",
                "estado" : "V"
            },{
                "id" : 1092029922,
                "codigoPrueba" : "120_1",
                "nombrePrueba" : "GGT",
                "resultado" : "1982,30",
                "observacion" : "dsadsa ipsum dolor sit amet, consectetur adipiscing elit.",
                "unidad" : "mg/dl",
                "referencia" : "Hasta 360",
                "metodologia" : "Inmunodifusion Radial 2",
                "estado" : "V"
            }]
        }';
        $this->testJson=json_decode($testJson,true);
        return true;
    }

    public function indexAction() {

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
