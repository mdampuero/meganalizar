<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Method extends Model {

    protected $_name = 'apachecms_method';
    protected $names = 'me_name';
    protected $primary = 'me_id';
    protected $deleted = 'me_delete';
    protected $status = 'me_status';
    protected $modified = 'me_modified';
    protected $created = 'me_created';
    protected $defultSort = 'me_name';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

}

?>