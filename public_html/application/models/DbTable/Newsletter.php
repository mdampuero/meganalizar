<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_Newsletter extends Model {

    protected $_name = 'apachecms_newsletter';
    protected $names = 'nl_title';
    protected $primary = 'nl_id';
    protected $deleted = 'nl_delete';
    protected $status = 'nl_status';
    protected $modified = 'nl_modified';
    protected $created = 'nl_created';
    protected $defultSort = 'nl_id';
    protected $defultOrder = 'DESC';

    public function __construct() {
        parent::__construct();
    }

}

?>