<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Adicionales extends Model {

    protected $_name = 'apachecms_adicionales';
    protected $names = 'ad_nombre';
    protected $primary = 'ad_id';
    protected $deleted = 'ad_delete';
    protected $status = 'ad_status';
    protected $modified = 'ad_modified';
    protected $created = 'ad_created';
    protected $defultSort = 'ad_id';
    protected $defultOrder = 'DESC';

    public function __construct() {
        parent::__construct();
    }

}

?>