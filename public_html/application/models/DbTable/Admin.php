<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require 'Model.php';

class Model_DBTable_Admin extends Model {

    protected $_name = 'apachecms_admin';
    protected $primary = 'ad_id';
    protected $names = 'ad_user';
    protected $status = 'ad_status';
    protected $deleted = 'ad_delete';
    protected $modified = 'ad_modified';
    protected $created = 'ad_created';
    protected $defultSort = 'ad_user';
    protected $defultOrder = 'DESC';

    public function showAll($where = null, $sort = null, $order = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("*"));
        $where = ($where == null) ? '1' : $where;
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->joinLeft("apachecms_admin_rol as r", "r.ar_id=" . $this->_name . ".ad_ar_id");
        $select->where($where);
        $select->order($sort . ' ' . $order);
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function isExist($ad_user, $id = 0) {
        if ($id == 0):
            $row = $this->fetchRow("ad_user='" . $ad_user . "'");
        else:
            $row = $this->fetchRow("ad_user='" . $ad_user . "' AND " . $this->primary . "<>" . $id);
        endif;
        if ($row) :
            return $row->toArray();
        else:
            return false;
        endif;
    }

    public function add($parameters) {
        if (!$this->isExist($parameters["ad_user"])):
            $id = $this->insert($parameters);
            if ($id > 0) {
                return $id;
            } else {
                return null;
            }
            return ($id > 0) ? $id : -1;
        else:
            throw new Zend_Controller_Action_Exception("Ya existe un usuario '" . $parameters["ad_user"] . "'.");
        endif;
    }

    public function edit($parameters, $id) {
        if (!$this->isExist($parameters["ad_user"], $id)):
            if ($id > 0) {
                $this->update($parameters, $this->primary . ' = ' . (int) $id);
            } else {
                return null;
            }
        else:
            throw new Zend_Controller_Action_Exception("Ya existe un usuario '" . $parameters["ad_user"] . "'.");
        endif;
    }

    public function logIn($data) {
        $row = $this->fetchRow("ad_user='" . $data["ad_user"] . "' and ad_password='" . md5($data["ad_password"]) . "'");
        if ($row)
            return $row->toArray();
        else
            return null;
    }

}

?>