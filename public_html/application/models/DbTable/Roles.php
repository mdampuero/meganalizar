<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Roles extends Model {

    protected $_name = 'apachecms_admin_rol';
    protected $primary = 'ar_id';
    protected $names = 'ar_name';
    protected $status = 'ar_status';
    protected $deleted = 'ar_delete';
    protected $modified = 'ar_modified';
    protected $created = 'ar_created';
    protected $defultSort = 'ar_id';
    protected $defultOrder = 'DESC';

    public function __construct() {
        parent::__construct();
    }

    public function clearData() {
        $this->delete($this->primary . ' <> 1  AND ' . $this->primary . ' <> 2  AND ' . $this->primary . ' <> 3');
        return $this->info(Zend_Db_Table::NAME);
    }

}

?>