<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Bioquimico extends Model {

    protected $_name = 'bioquimico';
    protected $names = 'BMatricula|BNombre';
    protected $primary = 'IDBioquimico';
    protected $deleted = 'BDelete';
    protected $status = 'BStatus';
    protected $modified = 'BModified';
    protected $created = 'BCreated';
    protected $defultSort = 'BModified';
    protected $defultOrder = 'DESC';
    
    public function __construct() {
        parent::__construct();
    }

    public function getByMatricula($matricula) {
        $matricula = trim($matricula);
        $matricula = (int) $matricula;
        $select = $this->select();
        $select->from(array($this->_name), array("*"));
        $select->setIntegrityCheck(false);
        $select->where('BMatricula = "' . $matricula.'"');
        $row = $this->fetchRow($select);
        if (!$row) {
            return null;
        }
        return $row->toArray();
    }
    
    public function getByAccessKey($accessKey) {
        $select = $this->select();
        $select->from(array($this->_name), array("*"));
        $select->setIntegrityCheck(false);
        $select->where('BAccessKey = "' . $accessKey.'"');
        $row = $this->fetchRow($select);
        if (!$row) {
            return null;
        }
        return $row->toArray();
    }

    public function login($matricula,$clave) {
        $matricula = trim($matricula);
        $matricula = (int) $matricula;
        $select = $this->select();
        $select->from(array($this->_name), array("*"));
        $select->setIntegrityCheck(false);
        $select->where('BMatricula = "' . $matricula.'" AND BPassword="'.$clave.'"');
        $row = $this->fetchRow($select);
        if (!$row) {
            return null;
        }
        return $row->toArray();
    }

    public function addByMatricula($data) {
        $data["matricula"] = trim($data["matricula"]);
        $data["matricula"] = (int) $data["matricula"];
        $parameters=array(
            'BMatricula'=>$data["matricula"],
            'BPassword'=>$data["matricula"],
            'BNombre'=>$data["descripcion"],
            'BEmail'=>$data["correo"],
            'BDireccion'=>$data["domicilio"],
            'BEnvio'=>"0"
            );
        $parameters[$this->created] = time();
        $parameters[$this->modified] = $parameters[$this->created];
        $id = $this->insert($parameters);
        if ($id > 0) {
            return $parameters;
        } else {
            return null;
        }
    }
}

?>