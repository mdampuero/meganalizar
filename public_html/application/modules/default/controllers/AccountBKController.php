<?php

/*
 * Author: Mauricio Ampuero 
 * E-Mail: mdampuero@gmail.com
 */

require_once 'ControllerDefault.php';
require_once 'Resultadodeterminacion.php';
require_once 'Determination.php';
require_once 'Resultado.php';
require_once 'Bioquimico.php';
require_once 'Price.php';
require_once 'Billingdetail.php';

class AccountController extends ControllerDefault {

	public function init() {
		parent::init(TRUE);
		$this->result=new Model_DBTable_Resultado();
		$this->result_determinacion=new Model_DBTable_Resultadodeterminacion();
	}

	public function indexAction() {
		try {
			$this->view->dates=$this->result->getDistinctFecha($this->view->login['BMatricula']);
			if($this->view->parameters['muestra']){
				$result=$this->result->getByMuestraMatricula($this->view->parameters['muestra'],$this->view->login['BMatricula']);
			}else{
				$currentDate=current($this->view->dates);
				$this->view->fecha=($this->view->parameters['fecha'])?$this->view->parameters['fecha']:$currentDate['fecha'];
				$result=$this->result->getByDate($this->_helper->Date->getDateFormatted(str_replace('/', '-', $this->view->fecha)),$this->view->login['BMatricula'],$this->view->parameters['sort'],$this->view->parameters['order']);
			}
			$this->view->result=$result;
		} catch (Zend_Exception $exc) {
			throw new Zend_Exception($exc->getMessage(), $exc->getCode());
		}
	}
	public function billingAction() {
		try {
			$this->billing_detail = new Model_DBTable_Billingdetail();
			if(!$this->view->parameters['desde'])
				$this->view->parameters['desde']='01'.date('/m/Y');
			if(!$this->view->parameters['hasta'])
				$this->view->parameters['hasta']=date('d/m/Y');
			
			$where[]='bd_cuenta='.$this->view->login['BMatricula'];
			$where[]='bd_fecha>="'.$this->_helper->Date->getDateFormatted(str_replace('/', '-', $this->view->parameters['desde'])).'"';
			$where[]='bd_fecha<="'.$this->_helper->Date->getDateFormatted(str_replace('/', '-', $this->view->parameters['hasta'])).'"';

			$this->view->entities=$this->billing_detail->group(implode(" AND ",$where),$this->view->parameters['sort'],$this->view->parameters['order']);
		} catch (Zend_Exception $exc) {
			throw new Zend_Exception($exc->getMessage(), $exc->getCode());
		}
	}
	public function billingdetailAction() {
		try {
			$this->billing_detail = new Model_DBTable_Billingdetail();
			$this->view->entities=$this->billing_detail->getByMuestra($this->view->parameters['muestra']);
		} catch (Zend_Exception $exc) {
			throw new Zend_Exception($exc->getMessage(), $exc->getCode());
		}
	}
	public function dataAction() {
		try {
			$this->bioquimico=new Model_DBTable_Bioquimico();
			if ($this->getRequest()->isPost()){
				if(empty($_POST['nombre'])) 
					throw new Zend_Exception('El Nombre completo no puede estar vacío');
				
				if(!empty($_POST['contrasenia'])){
					if($_POST['contrasenia']!=$_POST['contrasenia2'])
						throw new Zend_Exception('Las contraseñas no son iguales');
					$data['BPassword']=$_POST['contrasenia'];
				}
				if(empty($_POST['email'])) 
					throw new Zend_Exception('El Email no puede estar vacío');
				$data['BNombre']=$_POST['nombre'];
				$data['BEmail']=$_POST['email'];
				$data['BDireccion']=$_POST['direccion'];
				$data['BLocalidad']=$_POST['localidad'];
				$data['BTelefono']=$_POST['telefono'];
				$data['BCelular']=$_POST['celular'];
				$data['BDni']=$_POST['documento'];
				$data['BEnvio']=($_POST['envio'])?1:0;
				$this->bioquimico->edit($data,$this->view->login['IDBioquimico']);
				$this->_helper->Login->loginInPublic($this->bioquimico->get($this->view->login['IDBioquimico']));
				$this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => 'Sus datos se guardaron correctamente'));
				$this->_helper->Redirector->gotoSimple('data','account');
			}
		} catch (Zend_Exception $exc) {
			$this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
			$this->_helper->Redirector->gotoSimple('data','account');
		}
	}
	public function priceAction() {
		try {
			$this->price=new Model_DBTable_Price();
			$this->view->entities=$this->price->showAll();
		} catch (Zend_Exception $exc) {
			$this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
			$this->_helper->Redirector->gotoSimple('price','account');
		}
	}
	public function loadAction(){  
		$this->view->results=$this->result->getByMuestra($this->view->parameters['muestra']);
		$this->renderScript('account/partial/result_detail.phtml');
	}
}
