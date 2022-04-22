<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Page extends Model {

    protected $_name = 'apachecms_page';
    protected $names = 'pa_name';
    protected $primary = 'pa_id';
    protected $deleted = 'pa_deleted';
    protected $status = 'pa_status';
    protected $modified = 'pa_modified';
    protected $created = 'pa_created';
    protected $defultSort = 'pa_name';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

}

?>