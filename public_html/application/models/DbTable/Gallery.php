<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Gallery extends Model {

    protected $_name = 'apachecms_gallery';
    protected $names = 'ga_name';
    protected $primary = 'ga_id';
    protected $deleted = 'ga_deleted';
    protected $status = 'ga_status';
    protected $modified = 'ga_modified';
    protected $created = 'ga_created';
    protected $defultSort = 'ga_id';
    protected $defultOrder = 'DESC';

    public function __construct() {
        parent::__construct();
    }

}

?>