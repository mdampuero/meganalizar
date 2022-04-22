<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Billing extends Model {

    protected $_name = 'apachecms_billing';
    protected $names = 'bi_file';
    protected $primary = 'bi_id';
    protected $deleted = 'bi_delete';
    protected $status = 'bi_status';
    protected $modified = 'bi_modified';
    protected $created = 'bi_created';
    protected $defultSort = 'bi_file';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

}

?>