<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Apicontroller.php';
require_once 'Resultado.php';
require_once 'Bioquimico.php';
require_once 'Orden.php';
require_once 'OrdenEstudioPrueba.php';
require_once 'Orden.php';

class Api_MuestrasController extends Apicontroller {
    
    public $result_model;
    
    public function init() {
        parent::init("POST",false);
        $this->orden=new Model_DBTable_Orden();
        $this->bioquimico=new Model_DBTable_Bioquimico();
        $this->orden_estudio_prueba=new Model_DBTable_OrdenEstudioPrueba();
    }

    // public function indexAction() {
    //     try {
    //         $accessKey=$this->request->getParam('accessKey', null);
    //         $date=$this->request->getParam('date', null);
    //         if(empty($accessKey)) 
    //             throw new Exception("Parámetro 'AccessKey' inválido", 403);
    //         if(empty($date)) 
    //             throw new Exception("Parámetro 'date' inválido, formato permitido YYYY-MM-DD", 403);
    //         $dateParse=explode('-',$date);
    //         if(!checkdate($dateParse[1], $dateParse[2], $dateParse[0]))
    //             throw new Exception("Parámetro 'date' inválido, formato permitido YYYY-MM-DD", 403);

    //         $bioquimico =$this->bioquimico->getByAccessKey($accessKey);
    //         if(empty($bioquimico)) 
    //             throw new Exception("Parámetro 'AccessKey' inválido", 403);
    //         $muestras=$this->orden->getByDate($date,$bioquimico["BMatricula"]);
    //         $this->response->response = true;
    //         $this->response->data = $muestras;
    //         $this->sendResponse($this->_helper->json($this->response));
    //     } catch (Exception $ex) {
    //         $this->response->response = false;
    //         $this->response->data = array('code'=>$ex->getCode(),'message'=>$ex->getMessage());
    //         $this->sendResponse($this->_helper->json($this->response));
    //     }
    // }
    public function getAction() {
        try {
            $accessKey=$this->request->getParam('accessKey', null);
            $or_numero=$this->request->getParam('or_numero', null);
            if(empty($accessKey)) 
                throw new Exception("Parámetro 'AccessKey' inválido", 403);
            if(empty($or_numero)) 
                throw new Exception("Parámetro 'or_numero' inválido", 403);

            $bioquimico =$this->bioquimico->getByAccessKey($accessKey);
            if(empty($bioquimico)) 
                throw new Exception("Parámetro 'AccessKey' inválido", 403);
          
            $resultados=$this->orden_estudio_prueba->getAllByMuestraApi($or_numero,$bioquimico["BMatricula"]);
            $this->response->response = true;
            $this->response->data = $resultados;
            $this->sendResponse($this->_helper->json($this->response));
        } catch (Exception $ex) {
            $this->response->response = false;
            $this->response->data = array('code'=>$ex->getCode(),'message'=>$ex->getMessage());
            $this->sendResponse($this->_helper->json($this->response));
        }

    }
    public function indexAction() {
        try {
            $accessKey=$this->request->getParam('accessKey', null);
            $date=$this->request->getParam('date', null);
            if(empty($accessKey)) 
                throw new Exception("Parámetro 'AccessKey' inválido", 403);
            if(empty($date)) 
                throw new Exception("Parámetro 'date' inválido, formato permitido YYYY-MM-DD", 403);
            $dateParse=explode('-',$date);
            if(!checkdate($dateParse[1], $dateParse[2], $dateParse[0]))
                throw new Exception("Parámetro 'date' inválido, formato permitido YYYY-MM-DD", 403);

            $bioquimico =$this->bioquimico->getByAccessKey($accessKey);
            if(empty($bioquimico)) 
                throw new Exception("Parámetro 'AccessKey' inválido", 403);
          
            $resultados=$this->orden_estudio_prueba->getAllByDate($date,$bioquimico["BMatricula"]);

            $this->response->response = true;
            $this->response->data = $resultados;
            $this->sendResponse($this->_helper->json($this->response));
        } catch (Exception $ex) {
            $this->response->response = false;
            $this->response->data = array('code'=>$ex->getCode(),'message'=>$ex->getMessage());
            $this->sendResponse($this->_helper->json($this->response));
        }

    }
    // public function getAction() {
    //     try {
    //         $numero=$this->request->getParam('numero', null);
    //         if(empty($numero)) 
    //             throw new Exception('Nº de muestra inválido', 2001);
    //         $result=$this->result_model->getByMuestra($numero);
    //         $this->response->data = $result;
    //         $this->response->response = true;
    //         $this->sendResponse($this->_helper->json($this->response));
    //     } catch (Exception $ex) {
    //         $this->response->response = false;
    //         $this->response->data = array('code'=>$ex->getCode(),'message'=>$ex->getMessage());
    //         $this->sendResponse($this->_helper->json($this->response));
    //     }

    // }

    

}