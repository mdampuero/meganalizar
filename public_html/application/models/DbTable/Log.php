<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Log extends Model {

    protected $_name = 'apachecms_log';
    protected $names = 'lo_description';
    protected $primary = 'lo_id';
    protected $deleted = 'lo_delete';
    protected $status = 'lo_status';
    protected $modified = 'lo_modified';
    protected $created = 'lo_created';
    protected $defultSort = 'lo_id';
    protected $defultOrder = 'DESC';

    public function __construct() {
        parent::__construct();
    }

}

?>