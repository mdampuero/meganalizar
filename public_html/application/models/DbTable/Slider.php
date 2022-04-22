<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Slider extends Model {

    protected $_name = 'apachecms_slider';
    protected $names = 'sl_name';
    protected $primary = 'sl_id';
    protected $deleted = 'sl_deleted';
    protected $status = 'sl_status';
    protected $modified = 'sl_modified';
    protected $created = 'sl_created';
    protected $defultSort = 'sl_order';
    protected $defultOrder = 'ASC';
    
    public function __construct() {
        parent::__construct();
    }
}

?>