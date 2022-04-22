<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Model.php';

class Model_DBTable_OrdenEstudio extends Model {

    protected $_name = 'apachecms_orden_estudio';
    protected $names = 'oe_id';
    protected $primary = 'oe_id';
    protected $deleted = 'oe_delete';
    protected $status = 'oe_status';
    protected $modified = 'oe_modified';
    protected $created = 'oe_created';
    protected $defultSort = 'oe_id';
    protected $defultOrder = 'DESC';

    public function __construct() {
        parent::__construct();
    }

    public function add($data,$orden) {
        $parameters=array(
            'oe_or_id'=>$orden["or_id"],
            'oe_id_orden'=>$data->idOrden,
            'oe_codigo_estudio'=>$data->codigoEstudio,
            'oe_nombre_estudio'=>$data->nombreEstudio,
            'oe_fecha_entrega'=>$data->fechaEntrega,
            'oe_estado'=>$data->estado,
            'oe_status'=>1
            );
        $id = $this->insert($parameters);
        if ($id > 0) {
            return $this->get($id);
        } else {
            return null;
        }
    }
}

?>