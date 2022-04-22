<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Determinationlab extends Model {

    protected $_name = 'apachecms_determination_lab';
    protected $names = 'de_la_id';
    protected $primary = 'de_la_id';
    protected $deleted = 'de_la_delete';
    protected $status = 'de_la_status';
    protected $modified = 'de_la_modified';
    protected $created = 'de_la_created';
    protected $defultSort = 'de_la_id';
    protected $defultOrder = 'ASC';

    public function __construct() {
        parent::__construct();
    }

}

?>