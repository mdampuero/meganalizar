<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Determination extends Model {

    protected $_name = 'apachecms_determination';
    protected $names = 'de_nomenclature|de_name';
    protected $primary = 'de_id';
    protected $deleted = 'de_delete';
    protected $status = 'de_status';
    protected $modified = 'de_modified';
    protected $created = 'de_created';
    protected $defultSort = 'de_name';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

    public function getAll($where = null, $sort = null, $order = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("de_name","de_nomenclature"));
        $where = ($where == null) ? $this->deleted . "=0" : $this->deleted . "=0 AND (" . $where.")";
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where($where);
        $select->order($sort . ' ' . $order);
        // echo $select;
        // exit();
        $results = $this->fetchAll($select);

        return $results->toArray();
    }

    public function getByNomenclature($nomenclature) {
        $id = (int) $id;
        $select = $this->select();
        $select->from(array($this->_name), array("*"));
        $select->setIntegrityCheck(false);
        $select->where('de_nomenclature = "' . $nomenclature.'"');
        $select->joinLeft('apachecms_method as m',$this->_name.".de_me_id=m.me_id");
        $row = $this->fetchRow($select);
        if (!$row) {
            return null;
        }
        return $row->toArray();
    }
}

?>