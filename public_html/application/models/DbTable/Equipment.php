<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Equipment extends Model {

    protected $_name = 'apachecms_equipment';
    protected $names = 'eq_name';
    protected $primary = 'eq_id';
    protected $deleted = 'eq_delete';
    protected $status = 'eq_status';
    protected $modified = 'eq_modified';
    protected $created = 'eq_created';
    protected $defultSort = 'eq_name';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

}

?>