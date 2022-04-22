<?php

/*
* Author: Mauricio Ampuero 
* Organization: Inamika Interactive
* E-Mail: mdampuero@gmail.com
*/

require_once 'Apicontroller.php';
require_once 'Bioquimicos.php';
require_once 'Adicionales.php';
require_once 'Orden.php';
require_once 'OrdenEstudio.php';
require_once 'OrdenEstudioPrueba.php';
require_once 'Resultado.php';
require_once 'Estudio.php';
require_once 'Resultadodeterminacion.php';

class Api_ResultController extends Apicontroller {

    public function init() {
        parent::init("POST");
    }

    public function indexAction() {
        try {

            $socio=$this->getSocio($this->request->getParam('socio', null));
            $orden=$this->getOrden($this->request->getParam('orden', null));
            $ordenViejo=$this->getOrdenViejo($orden);
            $ordenEstudio=$this->getOrdenEstudio($this->request->getParam('ordenEstudio', null),$orden);
            $ordenEstudioPrueba=$this->getOrdenEstudioPrueba($this->request->getParam('ordenEstudioPrueba', null),$ordenEstudio);
            $relacionResultadoEstudio=$this->getRelacionResultadoEstudio($ordenViejo,$ordenEstudio,$ordenEstudioPrueba);
            Zend_Controller_Action_HelperBroker::getStaticHelper('Log')->setLog(array('lo_request'=>$this->params,'lo_description'=>'M002','lo_data'=>'OK'));
            $this->sendResponse($this->_helper->json(array('exito'=>1,'codigo'=>200,'respuesta'=>'OK')));
        } catch (Exception $ex) {
            $this->response->estado = array('exito'=>0,'codigo'=>$ex->getCode(),'respuesta'=>$ex->getMessage());
            Zend_Controller_Action_HelperBroker::getStaticHelper('Log')->setLog(array('lo_request'=>$this->params,'lo_description'=>'Error - '.$ex->getMessage(),'lo_data'=>$ex->getCode()));
            $this->sendResponse($this->_helper->json($this->response));

        }
    }

    protected function getSocio($socio){
        if(empty($socio)) 
            throw new Exception('Objeto OB003 (socio) inválido', 2001);
        $socioObject=json_decode($socio);
        if(!property_exists($socioObject,'matricula'))
            throw new Exception("No existe a propiedad 'matricula' dentro del objeto OB003", 2002);
        if(!property_exists($socioObject,'descripcion'))
            throw new Exception("No existe a propiedad 'descripcion' dentro del objeto OB003", 2004);
        if(!property_exists($socioObject,'correo'))
            throw new Exception("No existe a propiedad 'correo' dentro del objeto OB003", 2005);
        if(!property_exists($socioObject,'domicilio'))
            throw new Exception("No existe a propiedad 'domicilio' dentro del objeto OB003", 2006);

        $bioquimicoModel=new Model_DBTable_Bioquimicos();
        $bioquimico=$bioquimicoModel->getOneByMatricula($socioObject->matricula);
        if(empty($bioquimico)){
            $bioquimico=$bioquimicoModel->addByMatricula($socioObject);
            if(property_exists($socioObject,'adicionales')){
                if(count($socioObject->adicionales)){
                    $adicionalesModel=new Model_DBTable_Adicionales();
                    foreach ($socioObject->adicionales as $key => $adicionales) {
                        $adicionalesModel->add(array('ad_nombre'=>$adicionales->tipo,'ad_valor'=>$adicionales->valor,'ad_bi_id'=>$bioquimico["IDBioquimico"]));
                    }
                }
            }
        }
        return $bioquimico;
    }

    protected function getOrden($orden){
        if(empty($orden)) 
            throw new Exception('Objeto OB004 (orden) inválido', 2007);
        $ordenObject=json_decode($orden);
        if(!property_exists($ordenObject,'matricula'))
            throw new Exception("No existe a propiedad 'matricula' dentro del objeto OB004", 2008);
        if(!property_exists($ordenObject,'numero'))
            throw new Exception("No existe a propiedad 'numero' dentro del objeto OB004", 2009);
        if(!property_exists($ordenObject,'fecha'))
            throw new Exception("No existe a propiedad 'fecha' dentro del objeto OB004", 2010);
        if(!property_exists($ordenObject,'fechaEntrega'))
            throw new Exception("No existe a propiedad 'fechaEntrega' dentro del objeto OB004", 2011);
        if(!property_exists($ordenObject,'estado'))
            throw new Exception("No existe a propiedad 'estado' dentro del objeto OB004", 2012);
        if(!property_exists($ordenObject,'observacion'))
            throw new Exception("No existe a propiedad 'observacion' dentro del objeto OB004", 2013);
        $ordenObject->fecha=$this->getDate($ordenObject->fecha,"La propiedad 'fecha', no es una fecha válida del objeto OB004",2014);
        $ordenObject->fechaEntrega=$this->getDate($ordenObject->fechaEntrega,"La propiedad 'fechaEntrega', no es una fecha válida del objeto OB004",2015);
        
        $ordenModel=new Model_DBTable_Orden();
        $orden=$ordenModel->addByNumero($ordenObject);
        if(property_exists($socioObject,'adicionales')){
            if(count($ordenObject->adicionales)){
                $adicionalesModel=new Model_DBTable_Adicionales();
                foreach ($ordenObject->adicionales as $key => $adicionales) {
                    $adicionalesModel->add(array('ad_nombre'=>$adicionales->tipo,'ad_valor'=>$adicionales->valor,'ad_or_id'=>$orden["or_id"]));
                }
            }
        }
        return $orden;
    }
    protected function getOrdenViejo($orden){
        $ordenModelViejo=new Model_DBTable_Resultado();
        $resultado=array(
            'RFecha'=>$orden['or_fecha'],
            'RFechaEntrega'=>$orden['or_fecha_entrega'],
            'BMatricula'=>$orden['or_matricula'],
            'RMuestra'=>(int)$orden['or_numero'],
            'REstado'=>$orden['or_estado'],
            'Aviso'=>0,
            'RObservaciones'=>$orden['or_observacion'],
            'RStatus'=>1
        );
        $ordenViejo=$ordenModelViejo->add($resultado);
        return $ordenViejo;
    }
    
    protected function getRelacionResultadoEstudio($ordenViejo,$ordenEstudio,$ordenEstudioPrueba){
        $modelEstudio=new Model_DBTable_Estudio();
        $modelResultadoDeterminacion=new Model_DBTable_Resultadodeterminacion();
       // echo '<pre>';
        $estudio=$modelEstudio->getByNroEstudio($ordenEstudio['oe_codigo_estudio']);
        if(!$estudio)
            $estudio=$modelEstudio->add(array('NroEstudio'=>$ordenEstudio['oe_codigo_estudio'],'NEstudio'=>$ordenEstudio['oe_nombre_estudio']));
//         $txt='

     
//         VIT D 25OH ...86.7  ng/mL

// Mètodo:    QUIMIOLUMINISCENCIA (CMIA)

// Validado por: Sergio Bontti. Mat: 836






// ';    
// echo $txt;
$txt2='';
        if($ordenEstudioPrueba){
            foreach($ordenEstudioPrueba as $prueba){
                $txt2.='
'.$prueba['op_nombre_prueba'].'...'.$prueba['op_resultado'].' '.$prueba['op_unidad'].'
';
            }
        }
        $txt2.='
Mètodo: '.$ordenEstudioPrueba[count($ordenEstudioPrueba)-1]['op_metodologia'].'

Validado por: '.$ordenEstudioPrueba[count($ordenEstudioPrueba)-1]['op_validado_nombre'].'. Mat: '.$ordenEstudioPrueba[count($ordenEstudioPrueba)-1]['op_validado_matricula'].'

';
        //echo $txt2;
        $modelResultadoDeterminacion->add(array(
            'IDResultado'=>$ordenViejo,
            'IDEstudio'=>$estudio['IDEstudio'],
            'Contenido'=>$txt2,
            'REStatus'=>0,
            'RECreated'=>0,
            'REModified'=>0,
            'REDelete'=>0
        ));
       
    }

    protected function getOrdenEstudio($ordenEstudio,$orden){
        if(empty($ordenEstudio)) 
            throw new Exception('Objeto OB005 (ordenEstudio) inválido', 2015);
        $ordenEstudioObject=json_decode($ordenEstudio);

        if(!property_exists($ordenEstudioObject,'idOrden'))
            throw new Exception("No existe a propiedad 'idOrden' dentro del objeto OB005", 2016);
        if(!property_exists($ordenEstudioObject,'codigoEstudio'))
            throw new Exception("No existe a propiedad 'codigoEstudio' dentro del objeto OB005", 2017);
        if(!property_exists($ordenEstudioObject,'nombreEstudio'))
            throw new Exception("No existe a propiedad 'nombreEstudio' dentro del objeto OB005", 2018);
        if(!property_exists($ordenEstudioObject,'fechaEntrega'))
            throw new Exception("No existe a propiedad 'fechaEntrega' dentro del objeto OB005", 2019);
        if(!property_exists($ordenEstudioObject,'estado'))
            throw new Exception("No existe a propiedad 'estado' dentro del objeto OB005", 2020); 

        $ordenEstudioObject->fechaEntrega=$this->getDate($ordenEstudioObject->fechaEntrega,"La propiedad 'fechaEntrega', no es una fecha válida del objeto OB005",2021);        
        $ordenEstudioModel=new Model_DBTable_OrdenEstudio();
        $ordenEstudio=$ordenEstudioModel->add($ordenEstudioObject,$orden);
        return $ordenEstudio;
    }

    protected function getOrdenEstudioPrueba($ordenEstudioPruebaLista,$ordenEstudio){
        $ordenEstudioPruebaArray=array();
        if(empty($ordenEstudioPruebaLista)) 
            throw new Exception('Lista de Objeto OB007 (ordenEstudioPrueba) inválido', 2022);
        $ordenEstudioPruebaListaObject=json_decode($ordenEstudioPruebaLista);
        if(!is_array($ordenEstudioPruebaListaObject))
            throw new Exception('Lista de Objeto OB007 (ordenEstudioPrueba) inválido', 2023);

        foreach ($ordenEstudioPruebaListaObject as $key => $ordenEstudioPruebaObject) {

            if(!property_exists($ordenEstudioPruebaObject,'id'))
                throw new Exception("No existe a propiedad 'id' dentro del objeto OB007", 2024);

            if(!property_exists($ordenEstudioPruebaObject,'codigoPrueba'))
                throw new Exception("No existe a propiedad 'codigoPrueba' dentro del objeto OB007", 2025);

            if(!property_exists($ordenEstudioPruebaObject,'nombrePrueba'))
                throw new Exception("No existe a propiedad 'nombrePrueba' dentro del objeto OB007", 2026);

            if(!property_exists($ordenEstudioPruebaObject,'resultado'))
                throw new Exception("No existe a propiedad 'resultado' dentro del objeto OB007", 2027);

            if(!property_exists($ordenEstudioPruebaObject,'observacion'))
                throw new Exception("No existe a propiedad 'observacion' dentro del objeto OB007", 2028);

            if(!property_exists($ordenEstudioPruebaObject,'unidad'))
                throw new Exception("No existe a propiedad 'unidad' dentro del objeto OB007", 2029);

            if(!property_exists($ordenEstudioPruebaObject,'referencia'))
                throw new Exception("No existe a propiedad 'referencia' dentro del objeto OB007", 2030); 

            if(!property_exists($ordenEstudioPruebaObject,'metodologia'))
                throw new Exception("No existe a propiedad 'metodologia' dentro del objeto OB007", 2031);

            if(!property_exists($ordenEstudioPruebaObject,'estado'))
                throw new Exception("No existe a propiedad 'estado' dentro del objeto OB007", 2032);

            if(!property_exists($ordenEstudioPruebaObject,'validado'))
                throw new Exception("No existe a propiedad 'validado' dentro del objeto OB007", 2033);
            
            if(!property_exists($ordenEstudioPruebaObject->validado,'matricula'))
                throw new Exception("No existe a propiedad 'matricula' dentro de la propiedad validado del objeto OB007", 2034);
            
            if(!property_exists($ordenEstudioPruebaObject->validado,'nombre'))
                throw new Exception("No existe a propiedad 'nombre' dentro de la propiedad validado del objeto OB0077", 2035);

            $ordenEstudioPruebaModel=new Model_DBTable_OrdenEstudioPrueba();
            $ordenEstudioPruebaArray[]=$ordenEstudioPruebaModel->add($ordenEstudioPruebaObject,$ordenEstudio);

        }
        return $ordenEstudioPruebaArray;
    }

    protected function getDate($string,$message,$code){
        $year=substr($string,0,4);
        $month=substr($string,4,2);
        $day=substr($string,6,2);
        if(!checkdate($month, $day, $year))
            throw new Exception($message, $code);
        return  $year."-".$month."-".$day;
    }
}
