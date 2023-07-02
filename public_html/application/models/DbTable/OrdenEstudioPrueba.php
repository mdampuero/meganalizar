<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_OrdenEstudioPrueba extends Model {

    protected $_name = 'apachecms_orden_estudio_prueba';
    protected $names = 'op_numero';
    protected $primary = 'op_id';
    protected $deleted = 'op_delete';
    protected $status = 'op_status';
    protected $modified = 'op_modified';
    protected $created = 'op_created';
    protected $defultSort = 'op_id';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

    public function add($data,$ordenEstudio) {
        $parameters=array(
            'op_oe_id'=>$ordenEstudio["oe_id"],
            'op_id_orden_estudio'=>$data->id,
            'op_codigo_prueba'=>$data->codigoPrueba,
            'op_nombre_prueba'=>$data->nombrePrueba,
            'op_resultado'=>$data->resultado,
            'op_observacion'=>$data->observacion,
            'op_unidad'=>($data->unidad)?$data->unidad:'',
            'op_referencia'=>$data->referencia,
            'op_metodologia'=>$data->metodologia,
            'op_estado'=>$data->estado,
            'op_status'=>1,
            'op_validado_matricula'=>$data->validado->matricula,
            'op_validado_nombre'=>$data->validado->nombre
            );
        $id = $this->insert($parameters);
        if ($id > 0) {
            return $this->get($id);
        } else {
            return null;
        }
    }

    public function getAllByMuestra($muestra) {
        try {
            $select = $this->select();
            $select->setIntegrityCheck(false);
            $select->from(array($this->_name), array(
                "op_codigo_prueba",
                "op_nombre_prueba",
                "op_resultado",
                "op_unidad",
                "op_observacion",
                "op_validado_nombre",
                "op_validado_matricula"
            ));
            $sort = ($sort == null) ? $this->defultSort : $sort;
            $order = ($order == null) ? $this->defultOrder : $order;
            $select->where($this->deleted . "=0 AND o.or_numero='".$muestra."'");
            $select->joinLeft("apachecms_orden_estudio AS oe", $this->_name.".op_oe_id=oe.oe_id", array("oe_codigo_estudio","oe_nombre_estudio","oe_id"));
            $select->joinLeft("apachecms_orden AS o", "oe.oe_or_id=o.or_id", array(null));
            $select->order('op_id ASC');
            // $select->group('oe_id');
            $resultsObject = $this->fetchAll($select);
            $results=($resultsObject)?$resultsObject->toArray():null;
            if(!$results) return null;
            $repeatCode=[];
            foreach ($results as $key => $result) {
                if(in_array($result['oe_codigo_estudio'],$repeatCode)){
                    continue;
                }
                $repeatCode[]=$result['oe_codigo_estudio'];
                $response[$result['oe_id']]['oe_codigo_estudio']=$result['oe_codigo_estudio'];
                $response[$result['oe_id']]['oe_nombre_estudio']=$result['oe_nombre_estudio'];
                $response[$result['oe_id']]['practicas'][]=$result;
            }
            return $response;
        }catch (Zend_Exception $exc) {
			echo '<pre>';
            print_r($exc->getMessage());
            echo '</pre>';
            exit();
		}
    }
    public function getAllByDate($date,$matricula) {
        try {
            $select = $this->select();
            $select->setIntegrityCheck(false);
            $select->from(array($this->_name), array("*"));
            $sort = ($sort == null) ? $this->defultSort : $sort;
            $order = ($order == null) ? $this->defultOrder : $order;
            $select->where($this->deleted . "=0 AND o.or_fecha='".$date."' AND or_matricula='".$matricula."'");
            $select->joinLeft("apachecms_orden_estudio AS oe", $this->_name.".op_oe_id=oe.oe_id");
            $select->joinLeft("apachecms_orden AS o", "oe.oe_or_id=o.or_id");
            $select->order('op_id ASC');
            // $select->group('oe_');
            $resultsObject = $this->fetchAll($select);
            $results=($resultsObject)?$resultsObject->toArray():null;
            if(!$results) return null;
            // foreach ($results as $key => $result) {
            //     $response[$result['oe_id']]['oe_codigo_estudio']=$result['oe_codigo_estudio'];
            //     $response[$result['oe_id']]['oe_nombre_estudio']=$result['oe_nombre_estudio'];
            //     $response[$result['oe_id']]['practicas'][]=$result;
            // }
            return $results;
        }catch (Zend_Exception $exc) {
			echo '<pre>';
            print_r($exc->getMessage());
            echo '</pre>';
            exit();
		}
    }
    public function getAllByDates($date) {
        try {
            $select = $this->select();
            $select->setIntegrityCheck(false);
            $select->from(array($this->_name), array("*"));
            $sort = ($sort == null) ? $this->defultSort : $sort;
            $order = ($order == null) ? $this->defultOrder : $order;
            $select->where($this->deleted . "=0 AND o.or_fecha='".$date."'");
            $select->joinLeft("apachecms_orden_estudio AS oe", $this->_name.".op_oe_id=oe.oe_id");
            $select->joinLeft("apachecms_orden AS o", "oe.oe_or_id=o.or_id");
           // $select->order('op_id ASC');
            // $select->group('oe_');
            $resultsObject = $this->fetchAll($select);
            $results=($resultsObject)?$resultsObject->toArray():null;
            if(!$results) return null;
            // foreach ($results as $key => $result) {
            //     $response[$result['oe_id']]['oe_codigo_estudio']=$result['oe_codigo_estudio'];
            //     $response[$result['oe_id']]['oe_nombre_estudio']=$result['oe_nombre_estudio'];
            //     $response[$result['oe_id']]['practicas'][]=$result;
            // }
            return $results;
        }catch (Zend_Exception $exc) {
			echo '<pre>';
            print_r($exc->getMessage());
            echo '</pre>';
            exit();
		}
    }
    public function getAllByMuestraApi($muestra,$matricula) {
        try {
            $select = $this->select();
            $select->setIntegrityCheck(false);
            $select->from(array($this->_name), array("*"));
            $sort = ($sort == null) ? $this->defultSort : $sort;
            $order = ($order == null) ? $this->defultOrder : $order;
            $select->where($this->deleted . "=0 AND o.or_numero='".$muestra."' AND o.or_matricula='".$matricula."'");
            $select->joinLeft("apachecms_orden_estudio AS oe", $this->_name.".op_oe_id=oe.oe_id");
            $select->joinLeft("apachecms_orden AS o", "oe.oe_or_id=o.or_id");
            $select->order('op_id ASC');
            // $select->group('oe_');
            $resultsObject = $this->fetchAll($select);
            $results=($resultsObject)?$resultsObject->toArray():null;
            if(!$results) return null;
            return $results;
        }catch (Zend_Exception $exc) {
			echo '<pre>';
            print_r($exc->getMessage());
            echo '</pre>';
            exit();
		}
    }
}

?>