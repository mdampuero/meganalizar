<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'ControllerDefault.php';
require_once 'Resultado.php';
require_once 'Resultadodeterminacion.php';


class EmailsController extends ControllerDefault {

	public function init() {
		parent::init(FALSE);

		$this->model_resultados=new Model_DBTable_Resultado();
		$this->model_resultado_estudio=new Model_DBTable_Resultadodeterminacion();
	}

	public function indexAction() {
		try {
			$matricula = $this->model_resultados->showMatriculaAviso();
			if ($matricula) {
				foreach($matricula as $bioquimico){
					if ($bioquimico["BEnvio"] == 1) {
						$this->view->bioquimico=$bioquimico;
						$resultados = $this->model_resultados->showAllAvisoDistinct($bioquimico["BMatricula"]);
						if($resultados){
							$results=array();
							foreach($resultados as $key=>$resultado){
								$result[$resultado['RMuestra']]=$this->model_resultados->getByMuestra($resultado['RMuestra']);
							}
							$this->view->results=$result;
							Zend_Layout::startMvc(array('layout' => 'email', 'layoutPath' => '../application/modules/admin/layouts/scripts/'));
							$layout = $this->_helper->layout->getLayoutInstance();
							$layout->content = $this->view->render('emails/index.phtml');
							$html =  $layout->render();
							// exit($html);
							$resultSend=$this->_helper->Mail->sendEmail($html, "MEG@NALIZAR - Informe de Resultados", $bioquimico["BEmail"]);
						}
					}
				}
				$this->model_resultados->editByMatricula($bioquimico["BMatricula"]);
				
				// $resultSend=$this->_helper->Mail->sendEmail($html, "MEG@NALIZAR - Informe de Resultados", "mdampuero@gmail.com");
			}
			exit();
		} catch (Zend_Exception $exc) {
			throw new Zend_Exception($exc->getMessage(), $exc->getCode());
		}
	}
	public function testAction() {
		try {
			Zend_Layout::startMvc(array('layout' => 'email', 'layoutPath' => '../application/modules/admin/layouts/scripts/'));
			$layout = $this->_helper->layout->getLayoutInstance();
			$layout->content = $this->view->render('emails/test.phtml');
			$html =  $layout->render();
			// exit($html);
			$resultSend=$this->_helper->Mail->sendEmail($html, "MEG@NALIZAR - Informe de Resultados", 'mdampuero@gmail.com');
			echo '<pre>';
			print_r($resultSend);
			echo '</pre>';
			exit();
			exit();
		} catch (Zend_Exception $exc) {
			throw new Zend_Exception($exc->getMessage(), $exc->getCode());
		}
	}
}
