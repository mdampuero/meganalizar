<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Help extends Model {

    protected $_name = 'apachecms_help';
    protected $names = 'he_name';
    protected $primary = 'he_id';
    protected $deleted = 'he_delete';
    protected $status = 'he_status';
    protected $modified = 'he_modified';
    protected $created = 'he_created';
    protected $defultSort = 'he_name';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

}

?>