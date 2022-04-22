<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Apicontroller.php';
require_once 'Bioquimico.php';
require_once 'Determination.php';

class Api_DeterminacionesController extends Apicontroller {
    
    
    public function init() {
        parent::init("POST",false);
        $this->bioquimico=new Model_DBTable_Bioquimico();
        $this->model = new Model_DBTable_Determination();
    }

    public function getAction() {
        try {
            $accessKey=$this->request->getParam('accessKey', null);
            $nomenclador=$this->request->getParam('nomenclador', null);
            if(empty($accessKey)) 
                throw new Exception("Parámetro 'AccessKey' inválido", 403);
            if(empty($nomenclador)) 
                throw new Exception("Parámetro 'nomenclador' inválido", 403);

            $bioquimico =$this->bioquimico->getByAccessKey($accessKey);
            if(empty($bioquimico)) 
                throw new Exception("Parámetro 'AccessKey' inválido", 403);
            $results=$this->model->getByNomenclature($nomenclador);
            // $resultados=$this->orden_estudio_prueba->getAllByMuestraApi($or_numero,$bioquimico["BMatricula"]);
            $this->response->response = true;
            $this->response->data = $results;
            $this->sendResponse($this->_helper->json($this->response));
        } catch (Exception $ex) {
            $this->response->response = false;
            $this->response->data = array('code'=>$ex->getCode(),'message'=>$ex->getMessage());
            $this->sendResponse($this->_helper->json($this->response));
        }

    }

}