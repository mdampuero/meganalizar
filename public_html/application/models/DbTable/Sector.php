<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Sector extends Model {

    protected $_name = 'apachecms_sector';
    protected $names = 'sc_name';
    protected $primary = 'sc_id';
    protected $deleted = 'sc_delete';
    protected $status = 'sc_status';
    protected $modified = 'sc_modified';
    protected $created = 'sc_created';
    protected $defultSort = 'sc_order';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

}

?>