<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'ControllerDefault.php';
require_once 'Slider.php';

class IndexController extends ControllerDefault {

	public function init() {
		parent::init(FALSE);
	}

	public function indexAction() {
		try {
			
			$this->model = new Model_DBTable_Slider();
			$this->view->sliders=$this->model->showAll();
		} catch (Zend_Exception $exc) {
			throw new Zend_Exception($exc->getMessage(), $exc->getCode());
		}
	}
	public function contactAction() {
		try {
			
		} catch (Zend_Exception $exc) {
			throw new Zend_Exception($exc->getMessage(), $exc->getCode());
		}
	}
	public function testAction() {
		try {
			$this->model = new Model_DBTable_Slider();
			$this->view->sliders=$this->model->showAll();
		} catch (Zend_Exception $exc) {
			throw new Zend_Exception($exc->getMessage(), $exc->getCode());
		}
	}

}
