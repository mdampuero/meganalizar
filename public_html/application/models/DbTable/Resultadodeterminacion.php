<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Resultadodeterminacion extends Model {

    protected $_name = 'relacion_resultado_estudio';
    protected $names = 'IDRRE';
    protected $primary = 'IDRRE';
    protected $deleted = 'REDelete';
    protected $status = 'REStatus';
    protected $modified = 'REModified';
    protected $created = 'RECreated';
    protected $defultSort = 'IDRRE';
    protected $defultOrder = 'DESC';

    public function __construct() {
        parent::__construct();
    }

    // public function showAll($RFecha = '2017-02-08', $BMatricula='1058') {
    //     $select = $this->select();
    //     $select->setIntegrityCheck(false);
    //     $select->from(array($this->_name), array("*"));
    //     $where = ($where == null) ? $this->deleted . "=0" : $this->deleted . "=0 AND " . $where;
    //     $sort = ($sort == null) ? $this->defultSort : $sort;
    //     $order = ($order == null) ? $this->defultOrder : $order;
    //     $select->joinLeft("resultado AS r",$this->_name.".IDResultado=r.IDResultado",array("RMuestra"));
    //     $select->where("r.RFecha='".$RFecha."' AND r.BMatricula=".$BMatricula);
    //     $select->order($sort . ' ' . $order);
    //     $results = $this->fetchAll($select);
    //     return $results->toArray();
    // }
    public function byResult($ids) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from($this->_name, array('DISTINCT(relacion_resultado_estudio.IDEstudio)','*'));
        $select->joinNatural(array('estudio'));
        $select->joinLeft('apachecms_determination as d','estudio.NroEstudio=d.de_nomenclature',array('de_reference_value'));
        if(is_array($ids)){
            $select->where('IDResultado IN('.join(',',$ids).')');
        }else{
            $select->where('IDResultado IN('.$ids.')');
        }
        $select->group('relacion_resultado_estudio.IDEstudio');
        $resultado = $this->fetchAll($select);
        return $resultado->toArray();
    }
    public function getByDate($day) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("IDRRE"));
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->joinLeft("resultado AS r",$this->_name.".IDResultado=r.IDResultado",array("RMuestra","RFecha","Aviso"));
        $select->joinLeft("bioquimico AS b","r.BMatricula=b.BMatricula",array("BNombre","BMatricula"));
        $select->joinLeft("estudio AS e",$this->_name.".IDEstudio=e.IDEstudio",array("NroEstudio","NEstudio"));
        $select->where("r.RFecha='".$day."'");
        $select->order('RMuestra ' . $order);
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

}

?>