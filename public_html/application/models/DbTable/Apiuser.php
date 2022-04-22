<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require 'Model.php';

class Model_DBTable_Apiuser extends Model {

    protected $_name = 'apachecms_api_user';
    protected $primary = 'au_id';
    protected $names = 'au_username';
    protected $status = 'au_status';
    protected $deleted = 'au_delete';
    protected $modified = 'au_modified';
    protected $created = 'au_created';
    protected $defultSort = 'au_username';
    protected $defultOrder = 'DESC';

    public function showAll($where = null, $sort = null, $order = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("*"));
        $where = ($where == null) ? '1' : $where;
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where($where);
        $select->order($sort . ' ' . $order);
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function isExist($au_username, $id = 0) {
        if ($id == 0):
            $row = $this->fetchRow("au_username='" . $au_username . "'");
        else:
            $row = $this->fetchRow("au_username='" . $au_username . "' AND " . $this->primary . "<>" . $id);
        endif;
        if ($row) :
            return $row->toArray();
        else:
            return false;
        endif;
    }

    public function add($parameters) {
        $parameters[$this->created] = time();
        $parameters[$this->modified] = $parameters[$this->created];
        if (!$this->isExist($parameters["au_username"])){
            $id = $this->insert($parameters);
            if ($id > 0) {
                return $id;
            } else {
                return null;
            }
            return ($id > 0) ? $id : -1;
        }else{
            throw new Zend_Controller_Action_Exception("Ya existe un usuario '" . $parameters["au_username"] . "'.");
        }
    }

    public function edit($parameters, $id) {
        if (!$this->isExist($parameters["au_username"], $id)):
            if ($id > 0) {
                $parameters[$this->modified] = time();
                $this->update($parameters, $this->primary . ' = ' . (int) $id);
            } else {
                return null;
            }
            else:
                throw new Zend_Controller_Action_Exception("Ya existe un usuario '" . $parameters["au_username"] . "'.");
            endif;
        }

        public function logIn($data) {
            $row = $this->fetchRow("au_username='" . $data["au_username"] . "' and au_password='" . md5($data["au_password"]) . "' AND au_status=1 AND au_delete=0");
            if ($row)
                return $row->toArray();
            else
                return null;
        }

    }

    ?>