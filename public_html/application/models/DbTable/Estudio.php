<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */
class Model_DBTable_Estudio extends Zend_Db_Table_Abstract {

    public $_name = 'estudio';
    protected $primary = 'IDEstudio';
    protected $defultSort = 'NroEstudio';
    protected $defultOrder = 'DESC';

    public function showAll($where = null, $sort = null, $order = null) {

        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("*"));
        $where = ($where == null) ? 1 : $where;
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where($where);
        $select->order($sort . ' ' . $order);
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function edit($parameters, $id) {

        if ($id > 0) {
            $this->update($parameters, $this->primary . ' = ' . (int) $id);
        } else {
            return null;
        }
    }

    public function add($parameters) {
        $id = $this->insert($parameters);
        if ($id > 0) {
            return $this->get($id);
        } else {
            return null;
        }
    }

    public function get($id) {

        $id = (int) $id;
        $row = $this->fetchRow($this->primary . ' = ' . $id);
        if ($row):
            $return = $row->toArray();
        endif;
        return $return;
    }
    public function getByNroEstudio($NroEstudio) {

        $id = (int) $id;
        $row = $this->fetchRow('NroEstudio = "' . $NroEstudio.'"');
        if ($row){
            $return = $row->toArray();
        }else{
            $return = false;
        }
        return $return;
    }

}

?>
