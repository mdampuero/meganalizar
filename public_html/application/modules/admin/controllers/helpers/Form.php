<?php

class Zend_Controller_Action_Helper_Form extends Zend_Controller_Action_Helper_Abstract {

    private $token;
    private $session;

    public function __construct() {
        $this->session = new Zend_Session_Namespace('form');
        $this->request = Zend_Controller_Front::getInstance()->getRequest();
    }

    public function setToken() {
        $exp_reg = "[^A-Z0-9]";
        $this->token = substr(@preg_replace($exp_reg, "", md5(rand())) . @preg_replace($exp_reg, "", md5(rand())) . @preg_replace($exp_reg, "", md5(rand())), 0, 50);
        $this->session->token = $this->token;
        return $this->session->token;
    }

    public function getToken() {

        return $this->session->token;
    }

    public function isValid($fields = array()) {
        $this->params = $this->request->getParams();
        if ($_POST["token"] != $this->session->token)
            throw new Zend_Controller_Action_Exception("El token no coincide");
        if (count($fields) > 0):
            foreach ($fields as $key => $field):
                if ($key == 0)
                    continue;
                if ($field["notsave"] != true):
                    switch ($field["type"]):
                        case "image":
                            if ($_POST["remove_" . $field["field"]] == 1):
                                $data[$field["field"]] = "";
                            elseif ($_FILES[$field["field"]]["error"] == 0):
                                $ext = end(explode(".", $_FILES[$field["field"]]["name"]));
                                if ($ext != "jpg" && $ext != "jpeg" && $ext != "png" && $ext != "gif")
                                    throw new Zend_Controller_Action_Exception("La imagen '" . $field["label"] . "' no es válida, solo se admite JPG, PNG o GIF.");
                                $data[$field["field"]] = uniqid() . "." . $ext;
                                move_uploaded_file($_FILES[$field["field"]]["tmp_name"], PATH_IMG . $data[$field["field"]]);
                            endif;
                            break;
                        case "file":
                            if ($_POST["remove_" . $field["field"]] == 1):
                                $data[$field["field"]] = "";
                            elseif ($_FILES[$field["field"]]["error"] == 0):
                                $ext = end(explode(".", $_FILES[$field["field"]]["name"]));
                                $data[$field["field"]] = uniqid() . "." . $ext;
                                $data[$field["field"]."_filename"] = $_FILES[$field["field"]]["name"];
                                move_uploaded_file($_FILES[$field["field"]]["tmp_name"], PATH_IMG . $data[$field["field"]]);
                            endif;
                            break;
                        case "password":
                            $required = explode("|", trim($field["required"]));
                            if (in_array("required", $required)):
                                if (empty($_POST[$field["field"]]) && $this->params["action"]=="add")
                                    throw new Zend_Controller_Action_Exception("El campo '" . $field["label"] . "' es obligatorio.");
                            endif;
                            $data[$field["field"]] = $_POST[$field["field"]];
                            break;
                        default :
                            $required = explode("|", trim($field["required"]));
                            if (in_array("required", $required)):
                                if (empty($_POST[$field["field"]]))
                                    throw new Zend_Controller_Action_Exception("El campo '" . $field["label"] . "' es obligatorio.");
                            endif;
                            if (in_array("email", $required)):
                                if (!filter_var($_POST[$field["field"]], FILTER_VALIDATE_EMAIL))
                                    throw new Zend_Controller_Action_Exception("La dirección de '" . $field["label"] . "' no es válida.");
                            endif;
                            if (in_array("date_es", $required)):
                                list($d, $m, $y) = explode("/", $_POST[$field["field"]]);
                                if (!checkdate($m, $d, $y))
                                    throw new Zend_Controller_Action_Exception("La dirección de '" . $field["label"] . "' no es válida.");
                                $_POST[$field["field"]] = $y . "-" . $m . "-" . $d;
                            endif;
                            $data[$field["field"]] = $_POST[$field["field"]];
                    endswitch;
                endif;
            endforeach;
        endif;
        return $data;
    }

    public function isValidToken($fields = array()) {
        if ($fields["token"] == $this->session->token):
            return true;
        else:
            return false;
        endif;
    }

}

?>
