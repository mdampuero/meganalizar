<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Determinationequipment extends Model {

    protected $_name = 'apachecms_determination_equipment';
    protected $names = 'de_eq_eq_id';
    protected $primary = 'de_eq_id';
    protected $deleted = 'de_eq_delete';
    protected $status = 'de_eq_status';
    protected $modified = 'de_eq_modified';
    protected $created = 'de_eq_created';
    protected $defultSort = 'de_eq_id';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

    public function getByDet($de_id) {
        $id = (int) $id;
        $select = $this->select();
        $select->from(array($this->_name), array("*"));
        $select->setIntegrityCheck(false);
        $select->where('de_eq_de_id = "' . $de_id.'"');
        $select->joinLeft('apachecms_equipment as e',$this->_name.".de_eq_eq_id=e.eq_id");
        $results = $this->fetchAll($select);
        return $results->toArray();
    }
}

?>