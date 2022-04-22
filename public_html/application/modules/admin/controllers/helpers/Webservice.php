<?php

require_once 'NuSoap' . DS . 'lib' . DS . 'nusoap.php';

class Zend_Controller_Action_Helper_Webservice extends Zend_Controller_Action_Helper_Abstract {

    protected $MclientLocalidad;
    protected $MclientCliente;
    protected $MclientTipoDocumento;
    protected $MclientProfesion;
    protected $MclientProvincia;
    protected $MclientFormaEnvio;
    protected $MclientEntidadCab;
    protected $MclientEmpresa;
    protected $MclientCalle;
    protected $MclientCesionPendiente;
    protected $MclientCesionPendienteLibreDeuda;
    protected $MOrigenPedidoCesion;
    protected $MEntidadDet;
    protected $MEntidadDetEstado;
    protected $MClienteE;
    protected $MTypeIVA;
    protected $clientEntidadCab;
    protected $ClientEFactura;

    public function __construct() {
        $this->MclientLocalidad = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boLocalidadSoapPort');
        $this->MclientCliente = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boClienteSoapPort');
        $this->MclientTipoDocumento = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boTipoDocumentoSoapPort');
        $this->MclientProfesion = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boProfesionSoapPort');
        $this->MclientProvincia = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boProvinciaSoapPort');
        $this->MclientFormaEnvio = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boFormaEnvioSoapPort');
        $this->MclientEntidadCab = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boEntidadCabSoapPort');
        $this->MclientEmpresa = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boEmpresaSoapPort');
        $this->MclientCalle = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boCalleSoapPort');
        $this->MclientCesionPendiente = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boCesionPendienteSoapPort');
        $this->MclientCesionPendienteLibreDeuda = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boCesionPendienteLibreDeudaSoapPort');
        $this->MOrigenPedidoCesion = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boOrigenPedidoCesionSoapPort');
        $this->MEntidadDet = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boEntidadDetSoapPort');
        $this->MEntidadDetEstado = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boEntidadDetEstadoSoapPort');
        $this->MClienteE = new nusoap_client(WSM, true, false, true, false, false, 0, 30, 'boClienteESoapPort');
        $this->MTypeIVA = new nusoap_client(WSM, true, false, true, false, false, 0, 30, 'boTipoIvaSoapPort');
        $this->clientEntidadCab = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boEntidadCabSoapPort');
        $this->ClientEFactura = new nusoap_client(WSM, true, false, true, false, false, 0, 300, 'boClienteEFacturaSoapPort');
    }

    public function listAllNeighborhoodsFox() {
        try {
            $parameters = array(
                'cFilterCriteria' => null,
                'tcCursorAlias' => null,
                'tcOrder' => "cbarrio ASC"
            );
            $resultString = $this->MclientEntidadCab->call('GetAll', $parameters);
            $result = new SimpleXMLElement($resultString);
            if (!count($result))
                throw new Zend_Controller_Action_Exception();

            $arr = array();
            foreach ($result->cEntidadCab as $entidad) {
                $arr[(String) $entidad->identidadcab . '|' . (String) ($entidad->cbarrio)] = (String) ($entidad->cbarrio);
            }

            return $arr;
        } catch (Exception $exc) {
            return false;
        }
    }

    public function ClienteGetOne($fox_id, $nLevel = 3) {
        try {
            $parameters = array(
                'idEntidad' => $fox_id,
                'nLevel' => $nLevel
            );
            $resultString = $this->MclientCliente->call('GetOne', $parameters);
            $result = new SimpleXMLElement($resultString);
            if (!count($result)):
                throw new Zend_Controller_Action_Exception();
            endif;
            $data["cu_fox_id"] = trim((String) $result->cCliente->idcliente);
            $data["cu_last_name"] = trim((String) $result->cCliente->capellido);
            $data["cu_first_name"] = trim((String) $result->cCliente->cnombre);
            $data["cu_phone"] = trim((String) $result->cCliente->ctelefono);
            $data["cu_phone_jobs"] = trim((String) $result->cCliente->ctelefonocomercial);
            $data["cu_phone_mobile"] = trim((String) $result->cCliente->ctelefonomovil);
            $data["cu_phone_other"] = trim((String) $result->cCliente->cfax);
            $data["cu_email"] = strtolower(trim((String) $result->cCliente->cemail));
            $data["cu_type_document"] = trim((String) $result->cClientePersonal->idtipodocumento);
            $data["cu_number_document"] = trim((String) $result->cClientePersonal->cnumerodocumento);
            $data["cu_street"] = trim((String) $result->cClientePersonal->idcalle);
            $data["cu_street_number"] = trim((String) $result->cClientePersonal->cnumero);
            $data["cu_floor"] = trim((String) $result->cClientePersonal->cpiso);
            $data["cu_department"] = trim((String) $result->cClientePersonal->cdepartamento);
            $data["cu_zipcode"] = trim((String) $result->cClientePersonal->ccodigopostal);
            $data["cu_locality"] = trim((String) $result->cClientePersonal->idlocalidad);
            $data["cu_provence"] = trim((String) $result->cClientePersonal->idprovincia);
            $data["cu_birthday"] = substr(trim((String) $result->cClientePersonal->dfechanacimiento), 0, -9);
            $data["cu_sex"] = trim((String) $result->cClientePersonal->csexo);
            $data["cu_profesion"] = trim((String) $result->cClientePersonal->idprofesion);
            $data["cu_civil"] = trim((String) $result->cClientePersonal->cestadocivil);
            $data["cu_nacionality"] = trim((String) $result->cClientePersonal->idnacionalidad);
            $data["cu_birthplace"] = trim((String) $result->cClientePersonal->idpais);
            $data["cu_husband_last_name"] = trim((String) $result->cClienteFamiliar->capellidoconyuge);
            $data["cu_husband_first_name"] = trim((String) $result->cClienteFamiliar->cnombreconyuge);
            $data["cu_husband_type_document"] = trim((String) $result->cClienteFamiliar->idtipodocumentoconyuge);
            $data["cu_husband_number_document"] = trim((String) $result->cClienteFamiliar->cdocumentoconyuge);
            $data["cu_father_last_name"] = trim((String) $result->cClienteFamiliar->capellidopadre);
            $data["cu_father_first_name"] = trim((String) $result->cClienteFamiliar->cnombrepadre);
            $data["cu_mother_last_name"] = trim((String) $result->cClienteFamiliar->capellidomadre);
            $data["cu_mother_first_name"] = trim((String) $result->cClienteFamiliar->cnombremadre);
            $return = array(
                'result' => $result,
                'data' => $data
            );
            return $return;
        } catch (Exception $exc) {
            return false;
        }
    }

    public function searchClientByDocument($type_document, $number_documnet) {
        try {
            $parameters = array(
                'cFilterCriteria' => "Cliente.idCliente in (Select idCliente FROM ClientePersonal where (cNumeroDocumento='" . $number_documnet . "' AND idTipoDocumento='" . $type_document . "'))",
                'tcCursorAlias' => null,
                'tcOrder' => null
            );
            $result = $this->ClienteGetAll($parameters);
            if (!$result->cCliente && $type_document == 32) {
                //CUIT 27
                //LE 29
                //LC 30
                //CUIL 36
                $where = " WHERE (cNumeroDocumento LIKE '%" . trim($number_documnet) . "%' AND idTipoDocumento='27')";
                $where.=" OR (cNumeroDocumento LIKE '%" . trim($number_documnet) . "%' AND idTipoDocumento='36')";
                $where.=" OR (cNumeroDocumento='" . trim($number_documnet) . "' AND idTipoDocumento='30')";
                $where.=" OR (cNumeroDocumento='" . trim($number_documnet) . "' AND idTipoDocumento='29')";
                $parameters = array(
                    'cFilterCriteria' => "Cliente.idCliente in (Select idCliente FROM ClientePersonal " . $where . ")",
                    'tcCursorAlias' => null,
                    'tcOrder' => null
                );
                $result = $this->ClienteGetAll($parameters);
            }
            return $result;
        } catch (Exception $exc) {
            return false;
        }
    }

    public function ClienteGetAll($parameters) {
        try {
            $resultString = $this->MclientCliente->call('GetAll', $parameters);
            $result = new SimpleXMLElement($resultString);
            if (!count($result)):
                throw new Zend_Controller_Action_Exception();
            endif;
            $data["cu_fox_id"] = trim((String) $result->cCliente->idcliente);
            $data["cu_last_name"] = trim((String) $result->cCliente->capellido);
            $data["cu_first_name"] = trim((String) $result->cCliente->cnombre);
            $data["cu_phone"] = trim((String) $result->cCliente->ctelefono);
            $data["cu_phone_jobs"] = trim((String) $result->cCliente->ctelefonocomercial);
            $data["cu_phone_mobile"] = trim((String) $result->cCliente->ctelefonomovil);
            $data["cu_phone_other"] = trim((String) $result->cCliente->cfax);
            $data["cu_email"] = trim((String) $result->cCliente->cemail);
            $data["cu_type_document"] = trim((String) $result->cCliente->idtipodocumento);
            $data["cu_number_document"] = trim((String) $result->cCliente->cnumerodocumento);
            $data["cu_street"] = trim((String) $result->cClientePersonal->idcalle);
            $data["cu_street_number"] = trim((String) $result->cClientePersonal->cnumero);
            $data["cu_floor"] = trim((String) $result->cClientePersonal->cpiso);
            $data["cu_department"] = trim((String) $result->cClientePersonal->cdepartamento);
            $data["cu_zipcode"] = trim((String) $result->cClientePersonal->ccodigopostal);
            $data["cu_locality"] = trim((String) $result->cClientePersonal->idlocalidad);
            $data["cu_provence"] = trim((String) $result->cClientePersonal->idprovincia);
            $data["cu_birthday"] = substr(trim((String) $result->cClientePersonal->dfechanacimiento), 0, -9);
            $data["cu_sex"] = trim((String) $result->cClientePersonal->csexo);
            $data["cu_profesion"] = trim((String) $result->cClientePersonal->idprofesion);
            $data["cu_civil"] = trim((String) $result->cClientePersonal->cestadocivil);
            $data["cu_nacionality"] = trim((String) $result->cClientePersonal->idnacionalidad);
            $data["cu_birthplace"] = trim((String) $result->cClientePersonal->idpais);
            $data["cu_husband_last_name"] = trim((String) $result->cClienteFamiliar->capellidoconyuge);
            $data["cu_husband_first_name"] = trim((String) $result->cClienteFamiliar->cnombreconyuge);
            $data["cu_husband_type_document"] = trim((String) $result->cClienteFamiliar->idtipodocumentoconyuge);
            $data["cu_husband_number_document"] = trim((String) $result->cClienteFamiliar->cdocumentoconyuge);
            $data["cu_father_last_name"] = trim((String) $result->cClienteFamiliar->capellidopadre);
            $data["cu_father_first_name"] = trim((String) $result->cClienteFamiliar->cnombrepadre);
            $data["cu_mother_last_name"] = trim((String) $result->cClienteFamiliar->capellidomadre);
            $data["cu_mother_first_name"] = trim((String) $result->cClienteFamiliar->cnombremadre);
            $return = array(
                'result' => $result,
                'data' => $data
            );
            return $return;
        } catch (Exception $exc) {
            return false;
        }
    }

    public function ClienteGetPut($param_cesionario_xml = array()) {
        // return 0;
        if (count($param_cesionario_xml)):
            $xsd = file_get_contents(LIBRARY_PATH . DS . "put.xml");
            $xml = '<cCliente diffgr:id="ccliente_1" msdata:rowOrder="0" diffgr:hasChanges="inserted">
          <idcliente>0</idcliente>
          <capellido>' . strtoupper(trim($param_cesionario_xml['cu_last_name'])) . '</capellido>
          <cnombre>' . strtoupper(trim($param_cesionario_xml['cu_first_name'])) . '</cnombre>
          <dfechaingreso>' . trim(gmdate("Y-m-d")) . '</dfechaingreso>
          <ctelefono>' . strtoupper(trim($param_cesionario_xml['cu_phone'])) . '</ctelefono>
          <ctelefonocomercial>' . strtoupper(trim($param_cesionario_xml['cu_phone_jobs'])) . '</ctelefonocomercial>
          <ctelefonomovil>' . strtoupper(trim($param_cesionario_xml['cu_phone_mobile'])) . '</ctelefonomovil>
          <cfax>' . strtoupper(trim($param_cesionario_xml['cu_phone_other'])) . '</cfax>
          <cemail>' . trim($param_cesionario_xml['cu_email']) . '</cemail>
          </cCliente>
          <cClientePersonal diffgr:id="cclientepersonal_1" msdata:rowOrder="0" diffgr:hasChanges="inserted">
          <idtipodocumento>' . trim($param_cesionario_xml['cu_type_document']) . '</idtipodocumento>
          <cnumerodocumento>' . trim($param_cesionario_xml['cu_number_document']) . '</cnumerodocumento>
          <csexo>' . strtoupper(trim($param_cesionario_xml['cu_sex'])) . '</csexo>
          <cestadocivil>' . strtoupper(trim($param_cesionario_xml['cu_civil'])) . '</cestadocivil>
          <dfechanacimiento>' . trim($param_cesionario_xml['cu_birthday']) . '</dfechanacimiento>
          <idpais>' . trim($param_cesionario_xml['cu_birthplace']) . '</idpais>
          <idnacionalidad>' . trim($param_cesionario_xml['cu_nacionality']) . '</idnacionalidad>
          <idcalle>' . trim($param_cesionario_xml['cu_street']) . '</idcalle>
          <cnumero>' . strtoupper(trim($param_cesionario_xml['cu_street_number'])) . '</cnumero>
          <cpiso>' . strtoupper(trim($param_cesionario_xml['cu_floor'])) . '</cpiso>
          <cdepartamento>' . strtoupper(trim($param_cesionario_xml['cu_department'])) . '</cdepartamento>
          <ccodigopostal>' . strtoupper(trim($param_cesionario_xml['cu_zipcode'])) . '</ccodigopostal>
          <idlocalidad>' . trim($param_cesionario_xml['cu_locality']) . '</idlocalidad>
          <idprovincia>' . trim($param_cesionario_xml['cu_provence']) . '</idprovincia>
          <idprofesion>' . trim($param_cesionario_xml['cu_profesion']) . '</idprofesion>
          </cClientePersonal>
          <cClienteFamiliar diffgr:id="cclientefamiliar_1" msdata:rowOrder="0" diffgr:hasChanges="inserted">';
            if ($param_cesionario_xml['cu_civil'] == 'C') {
                $xml .='<capellidoconyuge>' . strtoupper(trim($param_cesionario_xml['cu_husband_last_name'])) . '</capellidoconyuge>
          <cnombreconyuge>' . strtoupper(trim($param_cesionario_xml['cu_husband_first_name'])) . '</cnombreconyuge>
          <idtipodocumentoconyuge>' . trim($param_cesionario_xml['cu_husband_type_document']) . '</idtipodocumentoconyuge>
          <cdocumentoconyuge>' . trim($param_cesionario_xml['cu_husband_number_document']) . '</cdocumentoconyuge>';
            }
            $xml .='<cnombrepadre>' . strtoupper(trim($param_cesionario_xml['cu_father_last_name'])) . '</cnombrepadre>
          <capellidopadre>' . strtoupper(trim($param_cesionario_xml['cu_father_first_name'])) . '</capellidopadre>
          <cnombremadre>' . strtoupper(trim($param_cesionario_xml['cu_mother_last_name'])) . '</cnombremadre>
          <capellidomadre>' . strtoupper(trim($param_cesionario_xml['cu_mother_first_name'])) . '</capellidomadre>
          </cClienteFamiliar>
          ';
            $string = str_replace("__xml__", $xml, $xsd);
            $parameters = array(
                'idEntidad' => 0,
                'tcDiffGram' => $string,
                'nLevel' => 2
            );
            try {
                $resultString = $this->MclientCliente->call('Put', $parameters);
                $result = new SimpleXMLElement($resultString);
            } catch (Exception $ex) {
                return 0;
            }
            if (!count($result)):
                throw new Zend_Controller_Action_Exception();
            endif;
            if ($result->cExceptionInformation):
                throw new Zend_Controller_Action_Exception(nl2br($result->cExceptionInformation->details));
            endif;
            $user_fox_id = (String) $result->cCliente->idcliente;
            return $user_fox_id;
        else:
            throw new Zend_Controller_Action_Exception("Datos vacios");
        endif;
    }

    public function xml2array($xmlObject, $out = array()) {
        foreach ((array) $xmlObject as $index => $node)
            $out[$index] = ( is_object($node) ) ? $this->xml2array($node) : $node;

        return $out;
    }

}

?>
