<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Orden extends Model {

    protected $_name = 'apachecms_orden';
    protected $names = 'or_numero';
    protected $primary = 'or_id';
    protected $deleted = 'or_delete';
    protected $status = 'or_status';
    protected $modified = 'or_modified';
    protected $created = 'or_created';
    protected $defultSort = 'or_id';
    protected $defultOrder = 'DESC';

    public function __construct() {
        parent::__construct();
    }

    public function getOneByNumero($numero) {
        $numero=trim($numero);
        $numero=$numero;
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from($this->_name, array('*'));
        $select->where("or_numero='".$numero."'");
        $results = $this->fetchRow($select);
        if($results)
            return $results->toArray();
        return null;
    }

    public function addByNumero($data) {
        $numero=trim($data->numero);
        $matricula=trim($data->matricula);
        $matricula=(int) $matricula;
        $parameters=array(
            'or_numero'=>$numero,
            'or_matricula'=>$matricula,
            'or_fecha'=>$data->fecha,
            'or_fecha_entrega'=>$data->fechaEntrega,
            'or_estado'=>$data->estado,
            'or_observacion'=>$data->observacion,
            'or_status'=>1
            );
        $id = $this->add($parameters);
        if ($id > 0) {
            return $this->get($id);
        } else {
            return null;
        }
    }

    public function getDistinctFecha($BMatricula) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from($this->_name, array("DISTINCT(DATE_FORMAT(or_fecha,'%d/%m/%Y')) as fecha"));
        $select->where('or_matricula = ' . $BMatricula. ' and or_fecha >="'.DATE_LAST.'" ');
        $select->order('or_fecha DESC');
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function getByDate($day,$matricula=null,$sort = null, $order = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("or_id","or_numero","DATE_FORMAT(or_fecha,'%d/%m/%Y') as or_fecha","or_created"));
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        if($matricula)
            $where=" AND ".$this->_name.".or_matricula=".$matricula;
        $select->where($this->deleted . "=0 AND or_fecha = '$day'".$where);
        $select->joinLeft("bioquimico AS b",$this->_name.".or_matricula=b.BMatricula",array("BNombre","BMatricula"));
        $select->order($sort . ' ' . $order);
        $select->group('or_numero');

        $results = $this->fetchAll($select);
        return $results->toArray();
    }
    public function getByMuestraMatricula($numeroMuestra,$matricula) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("or_numero","DATE_FORMAT(or_fecha,'%d/%m/%Y') as or_fecha"));
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where($this->deleted . "=0 AND or_numero LIKE '%$numeroMuestra%' AND ".$this->_name.".or_matricula='".$matricula."'");
        $select->joinLeft("bioquimico AS b",$this->_name.".or_matricula=b.BMatricula",array("BNombre","BMatricula"));
        $select->order($sort . ' ' . $order);
        $select->group('or_numero');

        $results = $this->fetchAll($select);
        return $results->toArray();
    }
    public function getByMuestra($nromuestra) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from($this->_name, array($this->primary));
        $select->where('or_numero = "' . $nromuestra.'"');
        $result = $this->fetchAll($select);
        
        $idsResult=$result->toArray();
        foreach ($idsResult as $key => $value) 
            $ids[]=$value['IDResultado'];

        $results=$this->result_determinacion->byResult($ids);
        foreach ($results as $key => $value)
            $results[$key]['Contenido_parser']=$this->parser_content($value['Contenido']);
        
        return $results;
    }
}

?>