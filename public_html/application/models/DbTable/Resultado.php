<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';
require_once 'Resultadodeterminacion.php';

class Model_DBTable_Resultado extends Model {

    protected $_name = 'resultado';
    protected $names = 'RMuestra';
    protected $primary = 'IDResultado';
    protected $deleted = 'RDelete';
    protected $status = 'RStatus';
    protected $modified = 'RModified';
    protected $created = 'RCreated';
    protected $defultSort = 'IDResultado';
    protected $defultOrder = 'DESC';

    public function __construct() {
        parent::__construct();
        $this->result_determinacion=new Model_DBTable_Resultadodeterminacion();
    }

    public function editByMatricula($matricula) {
        $this->update(array("Aviso"=>1), "BMatricula = '".$matricula."'");
    }

    public function showAllAviso($matricula) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from('resultado', array('BMatricula','RMuestra','IDResultado'));
        $select->joinLeft(array('bioquimico'), 'resultado.BMatricula = bioquimico.BMatricula',array("bioquimico.*"));
        $select->where("Aviso=0 and resultado.BMatricula ='".$matricula."'");
        $select->order('IDResultado ASC');
        //$select->limit(1);
        $results = $this->fetchAll($select);
        return $results->toArray();
    }
    public function showAllAvisoDistinct($matricula) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from('resultado', array('DISTINCT(RMuestra)'));
        $select->joinLeft(array('bioquimico'), 'resultado.BMatricula = bioquimico.BMatricula',array(null));
        $select->where("Aviso=0 and resultado.BMatricula ='".$matricula."'");
        $select->order('IDResultado ASC');
        //$select->limit(1);
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function showMatriculaAviso() {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from('resultado', array('BMatricula'));
        $select->joinLeft(array('bioquimico'), 'resultado.BMatricula = bioquimico.BMatricula',array("bioquimico.BEnvio","bioquimico.BNombre","bioquimico.BEmail"));
        $select->where("Aviso=0");
        $select->order('IDResultado ASC');
        $select->limit(1);
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function getByFechaMatricula($RFecha,$BMatricula) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("IDResultado","RMuestra"));
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where($this->deleted . "=0 AND RFecha='$RFecha' AND BMatricula=$BMatricula");
        $select->order($sort . ' ' . $order);
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function getByDate($day,$matricula=null,$sort = null, $order = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("Aviso","RMuestra","DATE_FORMAT(RFecha,'%d/%m/%Y') as RFecha","RCreated"));
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        if($matricula)
            $where=" AND ".$this->_name.".BMatricula=".$matricula;
        $select->where($this->deleted . "=0 AND RFecha = '$day'".$where);
        $select->joinLeft("bioquimico AS b",$this->_name.".BMatricula=b.BMatricula",array("BNombre","BMatricula"));
        $select->order($sort . ' ' . $order);
        $select->group('RMuestra');

        $results = $this->fetchAll($select);
        return $results->toArray();
    }
    public function getNroMuestra($numeroMuestra,$sort = null, $order = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("Aviso","RMuestra","DATE_FORMAT(RFecha,'%d-%m-%Y') as RFecha","RCreated"));
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where($this->deleted . "=0 AND RMuestra LIKE '%$numeroMuestra%'");
        $select->joinLeft("bioquimico AS b",$this->_name.".BMatricula=b.BMatricula",array("BNombre","BMatricula"));
        $select->order($sort . ' ' . $order);
        $select->group('RMuestra');

        $results = $this->fetchAll($select);
        return $results->toArray();
    }
    public function getByMuestraMatricula($numeroMuestra,$matricula) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("Aviso","RMuestra","DATE_FORMAT(RFecha,'%d-%m-%Y') as RFecha"));
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where($this->deleted . "=0 AND RMuestra LIKE '%$numeroMuestra%' AND ".$this->_name.".BMatricula='".$matricula."'");
        $select->joinLeft("bioquimico AS b",$this->_name.".BMatricula=b.BMatricula",array("BNombre","BMatricula"));
        $select->order($sort . ' ' . $order);
        $select->group('RMuestra');

        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function getDistinctFecha($BMatricula) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from('resultado', array("DISTINCT(DATE_FORMAT(RFecha,'%d/%m/%Y')) as fecha"));
        $select->where('resultado.BMatricula = ' . $BMatricula. ' and RFecha >="'.DATE_LAST.'" ');
        $select->order('RFecha DESC');
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function getByMuestra($nromuestra) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from($this->_name, array($this->primary));
        $select->where('RMuestra = ' . (int) $nromuestra);

        $result = $this->fetchAll($select);
        $idsResult=$result->toArray();
        foreach ($idsResult as $key => $value) 
            $ids[]=$value['IDResultado'];

        $results=$this->result_determinacion->byResult($ids);
        foreach ($results as $key => $value)
            $results[$key]['Contenido_parser']=$this->parser_content($value['Contenido']);
        
        return $results;
    }
    public function replacehtml($string){
        $string=str_replace("<", "&lt;", $string);
        $string=str_replace(">", "&gt;", $string);
        return $string;
    }
    public function parser_content($content) {
        $det = null;
        $met = null;
        $val = null;
        $tit = null;
        $pat = null;
        $value = null;
        $obs = null;
        $string_point = null;
        $ren_obs = false;
            $renglones = explode("\n", $content);
        foreach ($renglones as $key => $renglon) {

            if (trim($renglon) != "") { 
                if ($key > 0) {
                    $renglon_anterior = $renglones[$key - 1];
                }
                $renglon = ltrim($renglon);

                $renglon_parseado = explode("..", $renglon);
                if (count($renglon_parseado) > 1) { 
                    if (trim($renglon_parseado[0]) != "OBS") {
                        $det[] = $renglon_parseado[0];
                        $final = end($renglon_parseado);
                        $value[] = ($final[0] == ".") ? substr($this->replacehtml($final), 1) : $this->replacehtml($final);
                        
                    }
                } else {
                    $metodo_array = explode("Método: ", $renglon);
                    $titulo_array = explode("TÍTULO: ", $renglon);
                    $validado_array = explode("Validado por: ", $renglon);
                    $observacion_array = explode("Observacion: ", $renglon);
                    $patron_array = explode("PATRON: ", $renglon);
                    
                    if (trim(str_replace(".", "", $renglon_anterior)) == "S ROJA*") {
                        $obs[] = "<strong>S. ROJA</strong> - " . str_replace("Observación: ", "", $renglon);
                    } elseif (trim(str_replace(".", "", $renglon_anterior)) == "S BLANCA*") {
                        $obs[] = "<strong>S. BLANCA</strong> - " . str_replace("Observación: ", "", $renglon);
                    } else if (count($observacion_array) > 1) {
                        $obs[] = ltrim($observacion_array[1]);
                    }
                    
                    if (strpos($renglon_anterior,"Observación")!==false && strpos($renglon_anterior,"Validado por:")===false){
                        $obs[] = ltrim($renglon);
                    }
                    if (count($metodo_array) > 1) {
                        $met = ltrim($metodo_array[1]);
                    }
                    if (count($patron_array) > 1) {
                        $pat = ltrim($patron_array[1]);
                    }
                    if (count($titulo_array) > 1) {
                        $tit = ltrim($titulo_array[1]);
                    }
                    if (count($validado_array) > 1) {
                        $val = ltrim($validado_array[1]);
                    }
                }
            }
        }
        $contenido = array(
            'det' => $det,
            'met' => $met,
            'val' => $val,
            'value' => $value,
            'obs' => $obs,
            'tit' => $tit,
            'pat' => $pat
        );
        return $contenido;
    }

}

?>