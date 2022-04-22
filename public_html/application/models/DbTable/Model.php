<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

class Model extends Zend_Db_Table_Abstract {

    protected $_name;
    protected $names;
    protected $primary;
    protected $deleted;
    protected $status;
    protected $modified;
    protected $created;
    protected $defultSort;
    protected $defultOrder;

    public function __construct() {
        parent::__construct();
    }

    public function showAll($where = null, $sort = null, $order = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("*"));
        $where = ($where == null) ? $this->deleted . "=0" : $this->deleted . "=0 AND " . $where;
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where($where);
        $select->order($sort . ' ' . $order);
        $results = $this->fetchAll($select);

        return $results->toArray();
    }
    
    /*
    * @return int
    */
    public function countAll($where = null, $sort = null, $order = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("COUNT(".$this->primary.") as total"));
        $where = ($where == null) ? $this->deleted . "=0" : $this->deleted . "=0 AND " . $where;
        $select->where($where);
        $results = $this->fetchRow($select);
        if(count($results)){
            $total=$results->toArray();
            return $total["total"];
        }else{
            return 0;
        }
    }

    public function listAll($where = null, $sort = null, $order = null) {
        $result = $this->showAll($where, $sort, $order);
        if (count($result)){
            foreach ($result as $value){
                foreach ($value as $key => $val){
                    if ($key == $this->primary){
                        $names = explode("|", $this->names);
                        $contact = null;
                        foreach ($names as $name)
                            $contact.=$value[$name] . " - ";
                        if(count($names)>0)
                            $contact=substr($contact, 0,-3);
                        $list[$val] = trim($contact);
                    }
                }
            }
        }else{
            $list = array();
        }
        return $list;
    }

    public function add($parameters) {
        $parameters[$this->created] = time();
        $parameters[$this->modified] = $parameters[$this->created];
        $id = $this->insert($parameters);
        if ($id > 0) {
            return $id;
        } else {
            return null;
        }
    }

    public function beforeEdit($parameters,$id){
        $dataOld=$this->get($id);
        foreach ($parameters as $key => $value) {
            if($dataOld[$key]!=$value){
                return true;
            }
        }
        return false;
    }

    public function edit($parameters, $id) {
        if ($id > 0 && $this->beforeEdit($parameters,$id)) {
            $parameters[$this->modified] = time();
            $this->update($parameters, $this->primary . ' = ' . (int) $id);
            return true;
        } else {
            return false;
        }
    }

    public function get($id) {
        $id = (int) $id;
        $select = $this->select();
        $select->from(array($this->_name), array("*"));
        $select->setIntegrityCheck(false);
        $select->where($this->primary . ' = ' . $id);
        $row = $this->fetchRow($select);
        if (!$row) {
            return null;
        }
        return $row->toArray();
    }

    public function delete_row($id) {
        return $this->delete($this->primary . ' = ' . (int) $id);
    }

    public function deleteStrongBy($conditions=null) {
        if(!empty($conditions)) $this->delete($conditions);
        return true;
    }

    public function delete_slow($id) {

        $parameters[$this->modified] = time();
        $parameters[$this->deleted] = 1;
        if ($id > 0) {
            $this->update($parameters, $this->primary . ' = ' . (int) $id);
        } else {
            return null;
        }
    }

    public function clearData($conditions = 1) {
        $this->delete($conditions);
        return $this->info(Zend_Db_Table::NAME);
    }

}

?>