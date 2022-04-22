<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Sampling extends Model {

    protected $_name = 'apachecms_sampling';
    protected $names = 'sa_title';
    protected $primary = 'sa_id';
    protected $deleted = 'sa_delete';
    protected $status = 'sa_status';
    protected $modified = 'sa_modified';
    protected $created = 'sa_created';
    protected $defultSort = 'sa_id';
    protected $defultOrder = 'DESC';

    public function __construct() {
        parent::__construct();
    }

}

?>