<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Group extends Model {

    protected $_name = 'apachecms_group';
    protected $names = 'gr_name';
    protected $primary = 'gr_id';
    protected $deleted = 'gr_delete';
    protected $status = 'gr_status';
    protected $modified = 'gr_modified';
    protected $created = 'gr_created';
    protected $defultSort = 'gr_name';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

}

?>