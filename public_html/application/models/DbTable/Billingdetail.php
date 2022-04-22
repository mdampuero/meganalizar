<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';
require_once 'Determination.php';

class Model_DBTable_Billingdetail extends Model {

    protected $_name = 'apachecms_billing_detail';
    protected $names = 'bd_id';
    protected $primary = 'bd_id';
    protected $deleted = 'bd_delete';
    protected $status = 'bd_status';
    protected $modified = 'bd_modified';
    protected $created = 'bd_created';
    protected $defultSort = 'bd_id';
    protected $defultOrder = 'ASC';
    protected $campos = array('bd_cuenta', 'bd_nro_muestra', 'bd_fecha', 'bd_cod_practica', 'bd_sector', 'bd_derivacion', 'bd_laboratorio_derivacion', 'bd_estado', 'bd_codigo_iva', 'bd_letra_factura', 'bd_nro_factura', 'bd_fecha_factura', 'bd_valor_practica', 'bd_cod_operacion', 'bd_boca_expendio', 'bd_file', 'bd_bi_id');

    public function __construct() {
        parent::__construct();
    }

    public function group($where = null, $sort = null, $order = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("bd_cuenta","bd_nro_muestra","DATE_FORMAT(bd_fecha,'%d/%m/%Y') as bd_fecha","SUM(bd_valor_practica) as total"));
        $where = ($where == null) ? $this->deleted . "=0" : $this->deleted . "=0 AND " . $where;
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where($where);
        $select->order($sort . ' ' . $order);
        $select->group('bd_nro_muestra');
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function getByMuestra($muestra) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("*"));
        $where = ($where == null) ? $this->deleted . "=0" : $this->deleted . "=0 AND " . $where;
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where('bd_nro_muestra="'.$muestra.'"');
        // $select->joinLeft("apachecms_determination AS d",$this->_name.".bd_cod_practica LIKE d.de_nomenclature",array("de_name"));
        $select->order($sort . ' ' . $order);
        $results = $this->fetchAll($select);
        if($results){
            $this->determination = new Model_DBTable_Determination();
            $arrayResults=$results->toArray();
            foreach ($arrayResults as $key => $r) {
                $determination=$this->determination->getByNomenclature($r['bd_cod_practica']);
                $arrayResults[$key]['de_name']=$determination["de_name"];
            }
            return $arrayResults;
        }else{
            return array();
        }
    }

    public function importarSql($file,$bi_id){
        
        $texto=array();
        $linean=array();
        $i=0;
        $fp = fopen($file,"r");
        while ($linea= fgets($fp,1024)){
            if ($linea<>""){
                $linea = explode(";",str_replace('"', '', $linea));
                foreach ($linea as $key => $value) {
                    switch ($key){
                        case 3:
                        case 4:
                        case 7:
                        case 9:
                        case 16:
                            $linean[$this->campos[$key]]='"'.$value.'"';
                        break;

                        case 2:
                        case 11:
                             $linean[$this->campos[$key]]='"'.date("Y-m-d",strtotime($value)).'"';
                        break;

                        default:
                            $linean[$this->campos[$key]]=$value;
                    }
                    
                }
                $texto[]= $linean;
            }
        }
        $agre=$texto;
        
        foreach ($agre as $key => $value) {
            
            $agre[$key]['file']="'".basename($file)."'";
            $agre[$key]['bd_bi_id']=$bi_id;
            $values[]=$agre[$key];

            
            if ($i==100 OR $i==count($agre)-1){
                $this->insertSql($values);
                $values = array();
                $i=0;
            }

            $i++;
        }
        
        return true;
    }
    public function insertSql($arrValue){
        foreach ($arrValue as $key => $value) {
            $arrValue[$key]='('.implode(',',$value).')';    
        }
        
        $query="INSERT INTO ".$this->_name." (".implode(",", $this->campos).") VALUES ".implode(",", $arrValue).';';
       
        $stmt = $this->getDefaultAdapter()->query($query);
    }
}

?>