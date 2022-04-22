<?php

require_once 'Zend/Db/Table/Abstract.php';

class Model_DBTable_Bioquimicos extends Zend_Db_Table_Abstract {

    protected $_name = 'bioquimico';
    protected $primary = 'IDBioquimico';
    protected $loginname = 'BMatricula';

    public function showAll() {
        $results = $this->fetchAll();
        return $results;
    }

    public function getByMatricula($matricula) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from($this->_name, array('IDBioquimico'));
        $select->where("BMatricula='".$matricula."'");
        $results = $this->fetchAll($select);
        return $results->toArray();
    }
    
    public function getOneByMatricula($matricula) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from($this->_name, array('*'));
        $select->where("BMatricula='".$matricula."'");
        $results = $this->fetchRow($select);
        if($results)
            return $results->toArray();
        return null;
    }

    public function addByMatricula($data) {
        $matricula=trim($data->matricula);
        $matricula=(int) $matricula;
        $parameters=array(
            'BMatricula'=>$matricula,
            'BPassword'=>$data->matricula,
            'BNombre'=>$data->descripcion,
            'BEmail'=>$data->correo,
            'BDireccion'=>$data->domicilio,
            'BEnvio'=>0
            );
        $id = $this->insert($parameters);
        if ($id > 0) {
            return $this->getOneByMatricula($data->matricula);
        } else {
            return null;
        }
    }

    public function getAllByMatricula($matricula) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from($this->_name, array('*'));
        $select->where("BMatricula='".$matricula."'");
        $results = $this->fetchRow($select);
        return $results->toArray();
    }

    public function add($parameters) {
        $loginname = $parameters['BMatricula'];
        
        if (!$this->get_by_loginname($loginname)) {

            unset($parameters['BPassword_rectify']);
            unset($parameters['IDGrupo']);
            unset($parameters['sel1']);
            $IDUser = $this->insert($parameters);
        }

        if ($IDUser > 0) {
            return $IDUser;
        } else {
            return -1;
        }
    }

    public function get($id) {
        $id = (int) $id;
        $row = $this->fetchRow($this->primary . ' = ' . $id);

        if (!$row) {
            throw new Exception("No se encontro el usuario $id");
        }
        return $row->toArray();
    }

    public function get_by_loginname($loginname) {

        $select = $this->select();
        $select->from($this->_name, array('IDBioquimico'));

        $select->where('BMatricula = ' . $loginname);

        $select = $this->select()
                ->from('bioquimico', array('IDBioquimico'))
                ->where('BMatricula = ?', $loginname);

        $row = $this->fetchRow($select);

        if ($row) {
            return true;
        }else
            return false;
    }

    public function edit($parameters) {
        unset($parameters['sel1']);
        unset($parameters['IDGrupo']);
       unset($parameters['BPassword_rectify']);

        $this->update($parameters, $this->primary . ' = ' . (int) $parameters[$this->primary]);
    }

    public function delete_row($id) {
        return $this->delete($this->primary . ' = ' . (int) $id);
    }

}

?>