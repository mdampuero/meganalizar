<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_New extends Model {

    protected $_name = 'apachecms_new';
    protected $names = 'ne_title';
    protected $primary = 'ne_id';
    protected $deleted = 'ne_delete';
    protected $status = 'ne_status';
    protected $modified = 'ne_modified';
    protected $created = 'ne_created';
    protected $defultSort = 'ne_id';
    protected $defultOrder = 'DESC';

    public function __construct() {
        parent::__construct();
    }

}

?>