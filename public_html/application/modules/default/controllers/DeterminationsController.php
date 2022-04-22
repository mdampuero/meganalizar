<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'ControllerDefault.php';
require_once 'Determination.php';
require_once 'Determinationequipment.php';
require_once 'Sector.php';
require_once 'Helpdetermination.php';

class DeterminationsController extends ControllerDefault {

	public function init() {
		parent::init(FALSE);
		$this->model = new Model_DBTable_Determination();
		$this->sector = new Model_DBTable_Sector();
		$this->equipment = new Model_DBTable_Determinationequipment();
		$this->help = new Model_DBTable_Helpdetermination();
	}

	public function indexAction() {
		try {
			$this->view->sectors=$this->sector->showAll();
			if($this->view->parameters['query']){
				$this->view->entities=$this->model->getAll('de_nomenclature LIKE "%'.$this->view->parameters['query'].'%" OR de_name LIKE "%'.$this->view->parameters['query'].'%"');
			}else{
				if(!$this->view->parameters['sector']){
					$sectorArray=current($this->view->sectors);
					$this->view->parameters['sector']=$sectorArray['sc_id'];
				}
				$this->view->entities=$this->model->getAll('de_sc_id='.$this->view->parameters['sector']);
			}			
		} catch (Zend_Exception $exc) {
			throw new Zend_Exception($exc->getMessage(), $exc->getCode());
		}
	}

	public function getAction() {
		try {
			$this->view->entity=$this->model->getByNomenclature($this->view->parameters['estudio']);
			$eqs=$this->equipment->getByDet($this->view->entity['de_id']);
			$this->view->helps=$this->help->getByDet($this->view->entity['de_id']);
			$this->view->eq_names=array();
			foreach ($eqs as $key => $eq)
				$this->view->eq_names[]=$eq['eq_name'];
		} catch (Zend_Exception $exc) {
			
		}
	}
	public function getreferenceAction() {
		try {
			$this->view->entity=$this->model->getByNomenclature($this->view->parameters['estudio']);
		} catch (Zend_Exception $exc) {
			
		}
	}
}
