<?php

/*
* Author: Mauricio Ampuero 
* Organization: Inamika Interactive
* E-Mail: mdampuero@gmail.com
*/

require_once 'Apicontroller.php';
require_once 'Apiuser.php';

class Api_AuthController extends Apicontroller {

    public $api_user;

    public function init() {
        parent::init("POST",false);
        $this->api_user = new Model_DBTable_Apiuser();
    }

    public function indexAction() {
        try {
            $user = $this->request->getParam('usuario', null);
            if(empty($user))
                throw new Exception('Objeto OB001 inválido', 1001);
            $userObject=json_decode($user);
            if(!property_exists($userObject,'usuario'))
                throw new Exception("No existe a propiedad 'usuario' dentro del objeto OB001", 1002);
            if(!property_exists($userObject,'pass'))
                throw new Exception("No existe a propiedad 'pass' dentro del objeto OB001", 1003);
        
            $data=$this->api_user->logIn(array('au_username'=>$userObject->usuario,'au_password'=>$userObject->pass));
            if(!$data)
                throw new Exception('Usuario/pass inválidos', 1004);
            $token=$this->generateToken();
            $this->response->authKey = $token;
            Zend_Controller_Action_HelperBroker::getStaticHelper('Log')->setLog(array('lo_request'=>$this->params,'lo_description'=>'M001','lo_data'=>$token));
            $this->response->estado = array('exito'=>1,'codigo'=>200,'respuesta'=>'OK');
            $this->sendResponse($this->_helper->json($this->response));
        } catch (Exception $ex) {
            $this->response->estado = array('exito'=>0,'codigo'=>$ex->getCode(),'respuesta'=>$ex->getMessage());
            Zend_Controller_Action_HelperBroker::getStaticHelper('Log')->setLog(array('lo_request'=>$this->params,'lo_description'=>'Error - '.$ex->getMessage(),'lo_data'=>$ex->getCode()));
            $this->sendResponse($this->_helper->json($this->response));
        }

    }

    

}
