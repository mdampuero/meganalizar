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
			$id = $this->_getParam('id', 0);
        	$this->_helper->layout->disableLayout();
			$matricula = $this->model_resultados->showMatriculaAviso();
			if ($matricula) {
				if ($matricula[0]["BEnvio"] == 1) {
					$resultados = $this->model_resultados->showAllAviso($matricula[0]["BMatricula"]);
					$html = '
					<html><body style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
					<table width="600" cellpadding="5" style="border:#429C9D solid 1px;" cellspacing="0">
					<tr>
					<th align="left">
					<img src="http://' . $_SERVER["HTTP_HOST"] . $this->view->baseUrl() . '/imgs/marca_mail.gif" border="0" />
					</th>
					</tr>
					<tr>
					<td bgcolor="#429C9D">
					&nbsp;
					</td>
					</tr>';
					$html.='
					<tr>
					<th bgcolor="#429C9D" align="left" >
					<font color="#FFF">Para: ' . $matricula[0]["BNombre"] . ' (' . $matricula[0]["BMatricula"] . ')</font>
					</th> 
					</tr>
					<tr>
					<td bgcolor="#FFF" style="heigth:10px;"></td>
					</tr>';
					$arrayNumeroMuestra=array();
					foreach ($resultados as $key => $resultado) {
						if(!in_array($resultado["RMuestra"], $arrayNumeroMuestra)){
							// $arrayNumeroMuestra[]=$resultado["RMuestra"];
							$content = $this->model_resultados->parser_content($contenido["Contenido"]);
							$html.='
							<tr>
							<th bgcolor="#429C9D" align="left" >
							<font color="#FFF" style="font-size:16px;">Muestra N° ' . $resultado["RMuestra"] . ' <a target="_blank" href="http://' . $_SERVER["HTTP_HOST"] . $this->view->url(array('module' => 'default', 'controller' => 'account', 'action' => 'index', 'n' => $resultado["RMuestra"]), '', true) . '"> <font color="#FFF">Ver resultados en la web</font></a></font>
							</th>
							</tr>';
							$detalle_resultado = $this->model_resultado_estudio->byResult($resultado["IDResultado"]);
							
							$arrayNumeroEstudio=array();
							foreach ($detalle_resultado as $detalle) {
								if(!in_array($detalle["NEstudio"], $arrayNumeroEstudio)){
									$arrayNumeroEstudio[]=$detalle["NEstudio"];
									$content = $this->model_resultados->parser_content($detalle["Contenido"]);
									$metodo = '';
									if (trim($content["met"])) {
										$metodo.= '<tr><td colspan="2" style="font-size:12px;">';
										$metodo.= '<strong>Método: </strong>' . $content["met"] . '<br/>';
										$metodo.= '</td></tr>';
									}
									$observaciones = '';
									if ($content["obs"]) {
										foreach ($content["obs"] as $obser) {
											$observaciones.= '<tr bgcolor="#FFFFFF"><td colspan="2" style="font-size:12px;">';
											$observaciones.= '<strong>* Observaciones: </strong>' . $obser . '<br/>';
											$observaciones.= '</td></tr>';
										}
									}
									if (trim($content["pat"])) {
										$observaciones.= '<tr bgcolor="#FFFFFF"><td colspan="2" style="font-size:12px;">';
										$observaciones.= '<strong>PATRON: </strong>' . $content["pat"] . '<br/>';
										$observaciones.= '</td></tr>';
									}
									if (trim($content["tit"])) {
										$observaciones.= '<tr bgcolor="#FFFFFF"><td colspan="2" style="font-size:12px;">';
										$observaciones.= '<strong>TÍTULO: </strong>' . $content["tit"] . '<br/>';
										$observaciones.= '</td></tr>';
									}

									if (trim($content["val"])) {
										$observaciones.= '<tr bgcolor="#FFFFFF"><td colspan="2" style="font-size:12px;">';
										$observaciones.= '<strong>Validado por: </strong>' . $content["val"] . '<br/>';
										$observaciones.= '</td></tr>';
									}

									$determinaciones = '';

									foreach ($content["det"] as $index => $det) {

										$determinaciones.='
										<tr>
										<td bgcolor="#FFFFFF" style="border-bottom: #CCCCCC solid 1px;">' . $det . '</td>
										<td bgcolor="#FFFFFF" style="width: 150px; text-align: right; border-bottom: #CCCCCC solid 1px;"><strong style="color: #333333;">' . $content["value"][$index] . '</strong></td>
										</tr>';
									}

									$html.='
									<tr>
									<td bgcolor="#CCCCCC"></td>
									</tr>
									<tr>
									<th align="left">
									Estudio: ' . $detalle["NEstudio"] . ' (' . $detalle["NroEstudio"] . ')

									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $metodo . '
									</th>
									</tr>
									<tr>     
									<td style="margin-left: 50px;">
									<table style="margin-left: 50px; border: #CCC solid 1px;" bgcolor="#EFEFEF">
									' . $determinaciones . '
									' . $observaciones . '
									</table>
									</td>
									</tr>
									';
								}
							}
							$html.='
							<tr>
							<td bgcolor="#999999">
							<a target="_blank" href="http://' . $_SERVER["HTTP_HOST"] . $this->view->url(array('module' => 'default', 'controller' => 'account', 'action' => 'index', 'n' => $resultado["RMuestra"]), '', true) . '"> <font color="#FFF">Ver valores de referencia en web</font></a>
							</td>
							</tr>';
						}
					}
					$html.='</table>
					</body>
					</html>';
                    //ENVIO EL CORREO
					//$resultSend = $this->mail->sendEmail($resultado["BEmail"], "MEG@NALIZAR - Informe de Resultados", $html);
					// echo '<pre>';
					// print_r($html);
					// echo '</pre>';
					// exit();

					// $resultSend=$this->_helper->Mail->sendEmail($html, "MEG@NALIZAR - Informe de Resultados", $resultado["BEmail"]);
					$resultSend=$this->_helper->Mail->sendEmail($html, "MEG@NALIZAR - Informe de Resultados", "mdampuero@gmail.com");
					if ($resultSend == 'ok') {
						echo 'Enviado OK';
					} else {
						echo $resultSend;
					}
				}
                //ACTUALIZO EL ENVIO A 0 DE ESA MATRICULA PORQUE NO TIENE HABILITADO EL ENVIO
				//$this->model_resultados->editByMatricula($matricula[0]["BMatricula"]);
				$this->view->refresh = "20000";
			} else {
				$this->view->refresh = "20000";
			}
			exit("dsads");
		} catch (Zend_Exception $exc) {
			throw new Zend_Exception($exc->getMessage(), $exc->getCode());
		}
	}
}
