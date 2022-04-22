<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Price extends Model {

    protected $_name = 'apachecms_price';
    protected $names = 'pr_title';
    protected $primary = 'pr_id';
    protected $deleted = 'pr_delete';
    protected $status = 'pr_status';
    protected $modified = 'pr_modified';
    protected $created = 'pr_created';
    protected $defultSort = 'pr_id';
    protected $defultOrder = 'DESC';

    public function __construct() {
        parent::__construct();
    }

}

?>