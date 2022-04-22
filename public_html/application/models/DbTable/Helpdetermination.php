<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Helpdetermination extends Model {

    protected $_name = 'apachecms_help_determination';
    protected $names = 'hd_id';
    protected $primary = 'hd_id';
    protected $deleted = 'hd_delete';
    protected $status = 'hd_status';
    protected $modified = 'hd_modified';
    protected $created = 'hd_created';
    protected $defultSort = 'hd_id';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

    public function getByHelp($hd_he_id) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("*"));
        $select->where('hd_he_id='.$hd_he_id);
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->order($sort . ' ' . $order);
        $select->joinLeft("apachecms_determination AS de",$this->_name.".hd_de_id=de.de_id",array("de_name"));
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function getByDet($de_id) {
        $id = (int) $id;
        $select = $this->select();
        $select->from(array($this->_name), array("hd_he_id"));
        $select->setIntegrityCheck(false);
        $select->where('hd_de_id = "' . $de_id.'"');
        $select->joinLeft('apachecms_help as he',$this->_name.".hd_he_id=he.he_id",array("he_name"));
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

}

?>