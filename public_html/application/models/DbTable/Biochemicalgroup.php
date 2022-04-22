<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Biochemicalgroup extends Model {

    protected $_name = 'apachecms_biochemical_group';
    protected $names = 'bi_gr_gr_id';
    protected $primary = 'bi_gr_id';
    protected $deleted = 'bi_gr_delete';
    protected $status = 'bi_gr_status';
    protected $modified = 'bi_gr_modified';
    protected $created = 'bi_gr_created';
    protected $defultSort = 'bi_gr_id';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

    public function showAllComplete($where = null, $sort = null, $order = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("*"));
        $where = ($where == null) ? $this->deleted . "=0" : $this->deleted . "=0 AND " . $where;
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->joinLeft("bioquimico AS bi",$this->_name.".bi_gr_gr_id=gr.IDBioquimico AND BEmail<>''",array("BNombre"));
        $select->where($where);
        $select->order($sort . ' ' . $order);
        $results = $this->fetchAll($select);

        return $results->toArray();
    }

    public function showAll($where = null, $sort = null, $order = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("*"));
        $where = ($where == null) ? $this->deleted . "=0" : $this->deleted . "=0 AND " . $where;
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->joinLeft("apachecms_group AS gr",$this->_name.".bi_gr_gr_id=gr.gr_id",array("gr_name"));
        $select->where($where);
        $select->order($sort . ' ' . $order);
        $results = $this->fetchAll($select);

        return $results->toArray();
    }

    public function getByGroup($gr_id) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("*"));
        $select->joinLeft("bioquimico AS bi",$this->_name.".bi_gr_bi_id=bi.IDBioquimico AND BEmail<>'' AND bi.BEnvio=1");
        $select->where('bi_gr_gr_id='.$gr_id);
        $select->order('BNombre ASC');
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function getAll($where = null, $sort = null, $order = null) {
        $result = $this->showAllComplete($where, $sort, $order);
        if (count($result)){
            foreach ($result as $value){
                foreach ($value as $key => $val){
                    if ($key == $this->primary){
                        $names = explode("|", $this->names);
                        $contact = null;
                        foreach ($names as $name){
                            $contact.=$value[$name] . " ";
                        }
                        $list[$val] = trim($contact);
                    }
                }
            }
        }else{
            $list = array();
        }
        return $list;
    }
}

?>