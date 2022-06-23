<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Notification extends Zend_Db_Table_Abstract {
    protected $_name = 'apachecms_notification';
    public function __construct() {
        parent::__construct();
    }

    public function save($parameters) {
        $id = $this->insert($parameters);
        if ($id > 0) {
            return $id;
        } else {
            return null;
        }
    }

    public function getByBioquimico($bioquimico){
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("ne_id"));
        $select->where("us_id=".$bioquimico);
        $results = $this->fetchAll($select);
        if($results){
            $resultArray=$results->toArray();
            $onlyID=[];
            foreach ($resultArray as $result){
                $onlyID[]=$result["ne_id"];
            }
            return $onlyID;
        }else{
            return [];
        }
    }
    public function removeBy($ne_id,$us_id){
        return $this->delete('us_id = ' . (int) $us_id.' AND ne_id='.$ne_id);
    }
}
?>