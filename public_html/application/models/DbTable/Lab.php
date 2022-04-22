<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Lab extends Model {

    protected $_name = 'apachecms_lab';
    protected $names = 'la_name';
    protected $primary = 'la_id';
    protected $deleted = 'la_delete';
    protected $status = 'la_status';
    protected $modified = 'la_modified';
    protected $created = 'la_created';
    protected $defultSort = 'la_name';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

}

?>