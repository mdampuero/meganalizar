<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_NewsletterReceiver extends Model {

    protected $_name = 'apachecms_newsletter_receiver';
    protected $names = 'nr_receiver';
    protected $primary = 'nr_id';
    protected $deleted = 'nr_delete';
    protected $status = 'nr_status';
    protected $modified = 'nr_modified';
    protected $created = 'nr_created';
    protected $defultSort = 'nr_id';
    protected $defultOrder = 'DESC';

    public function __construct() {
        parent::__construct();
    }

    public function getByNewsletter($nl_id) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("*"));
        $select->where('nr_nl_id='.$nl_id);
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->order($sort . ' ' . $order);
        $results = $this->fetchAll($select);
        return $results->toArray();
    }
    public function next($id){
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from($this->_name,array('*'));
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->order($sort . ' ' . $order);
        $select->where("nr_nl_id=".$id." and nr_status=0");
        $select->limit(1);
        $results = $this->fetchRow($select);
        if(!$results){
            return false;
        }else{
            return $results->toArray();
        }
    }
}

?>