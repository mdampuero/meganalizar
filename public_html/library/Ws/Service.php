<?php

require_once 'Product.php';
require_once 'Paymethod.php';
require_once 'Order.php';
require_once 'Orderproduct.php';
require_once 'Orderhistory.php';
require_once 'Customer.php';
require_once 'Statusorder.php';

class Ws_Service {

    protected function getBaseUrl() {
        $view = Zend_Layout::getMvcInstance()->getView();
        return HOST . $view->baseUrl();
    }

    /**
     * Metodo que recibe el ID de producto y devuelve la descripciÃ³n completa
     *
     * @param String $product_id
     * @return Array
     */
    public function getOneProduct($product_id = null) {
        try {
            $pr_id = (int) $product_id;
            if (empty($pr_id))
                throw new Exception('Id de Producto Incorrecto: ' . $product_id);
            $this->product = new Model_DBTable_Product();
            $product = $this->product->get($pr_id);
            if (!$product)
                throw new Exception('No se encontro ningun producto con el ID: ' . $product_id);
            if ($product["pr_deleted"] == 1)
                throw new Exception('El producto ' . $product_id . ' fue Eliminado  [' . date("d-m-Y H:i:s", $product["pr_modified"]) . ']');
            if ($product["pr_status"] == 0)
                throw new Exception('El producto ' . $product_id . ' fue Deshabilitado [' . date("d-m-Y H:i:s", $product["pr_modified"]) . ']');
            if (!empty($product["pr_discount"]) && $product["pr_discount_from"] != '0000-00-00' && $product["pr_discount_to"] != '0000-00-00' && $product["pr_discount_from"] <= date("Y-m-d") && $product["pr_discount_to"] >= date("Y-m-d")):
                $product["pr_discount_price"] = ($product["pr_price"]) - (($product["pr_discount"] * $product["pr_price"] / 100));
                $product["pr_discount_from"] = date("d-m-Y", strtotime($product["pr_discount_from"]));
                $product["pr_discount_to"] = date("d-m-Y", strtotime($product["pr_discount_to"]));
            else:
                unset($product["pr_discount_from"]);
                unset($product["pr_discount"]);
                unset($product["pr_discount_to"]);
            endif;
            $product["pr_picture_url"] = $this->getBaseUrl() . "/files/" . $product["pr_picture"];
            $product["pr_qrcode_url"] = $this->getBaseUrl() . "/files/" . $product["pr_qrcode"];
            $product["pr_stock_available"] = $product["pr_stock"] - $product["pr_stock_min"];
            $product["pr_stock_label"] = ($product["pr_stock_available"] < 1) ? "No Disponible" : "Disponible";
            return array('response' => 1, 'data' => $product);
        } catch (Exception $ex) {
            return array('response' => 0, 'message' => $ex->getMessage());
        }
    }

    /**
     * Metodo que recibe un string opcional de condicion y devuelve una lista de productos 
     *
     * @param String $conditions
     * @return Array
     */
    public function getAllProduct($conditions = null) {
        try {
            $this->product = new Model_DBTable_Product();
            $products = $this->product->showAll($conditions);
            foreach ($products as $key => $product):
                if (!empty($product["pr_discount"]) && $product["pr_discount_from"] != '0000-00-00' && $product["pr_discount_to"] != '0000-00-00' && $product["pr_discount_from"] <= date("Y-m-d") && $product["pr_discount_to"] >= date("Y-m-d")):
                    $products[$key]["pr_offer"] = ($product["pr_price"]) - (($product["pr_discount"] * $product["pr_price"] / 100));
                    $products[$key]["pr_discount_from"] = date("d-m-Y", strtotime($product["pr_discount_from"]));
                    $products[$key]["pr_discount_to"] = date("d-m-Y", strtotime($product["pr_discount_to"]));
                else:
                    unset($products[$key]["pr_discount_from"]);
                    unset($products[$key]["pr_discount_to"]);
                endif;
                $products[$key]["pr_picture_url"] = $this->getBaseUrl() . "/files/" . $product["pr_picture"];
                $products[$key]["pr_qrcode_url"] = $this->getBaseUrl() . "/files/" . $product["pr_qrcode"];
                $products[$key]["pr_stock_available"] = $product["pr_stock"] - $product["pr_stock_min"];
                $products[$key]["pr_stock_label"] = (empty($products[$key]["pr_stock_available"])) ? "No Disponible" : "Disponible";
            endforeach;
            return array('response' => 1, 'data' => $products);
        } catch (Exception $ex) {
            return array('response' => 0, 'message' => $ex->getMessage());
        }
    }

    /**
     * Metodo que recibe 4 parametros  
     * Ej: saveOrder('Roberto Carlos','mail@mail.com','11222333','[{"id":1,"amount":2,"price":20.35},{"id":2,"amount":5,"price":45.25}]')
     * @param String $name
     * @param String $email
     * @param Int $doc
     * @param String $items
     * @return Array
     */
    public function saveOrder($name = null, $email = null, $doc = null, $items = null) {
        try {
            if (empty($name) || strlen($name) < 2)
                throw new Exception('Nombre incorrecto');
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
                throw new Exception('E-Mail incorrecto');
            if (empty($doc) || (strlen((int) $doc) < 6))
                throw new Exception('Nº Documento incorrecto');
            if (empty($items))
                throw new Exception('Items de carro de compras incorrecto');
            $itemsArray = json_decode($items, true);
            if (!is_array($itemsArray))
                throw new Exception('Items de carro de compras incorrecto');
            /*
             * Get Helpers
             */
            $this->_setting = Zend_Controller_Action_HelperBroker::getStaticHelper('Setting');
            $this->setting = $this->_setting->getSetting();
            /*
             * Item Order
             */
            foreach ($itemsArray as $key => $item):
                $subtotal = ($item["price"] * $item['amount']);
                $total+=$subtotal;
            endforeach;
            $this->product = new Model_DBTable_Product();
            $this->customer = new Model_DBTable_Customer();
            $this->order = new Model_DBTable_Order();
            $this->order_product = new Model_DBTable_Orderproduct();
            $this->order_history = new Model_DBTable_Orderhistory();
            $this->status_order = new Model_DBTable_Statusorder();
            //GET ORDER
            $new_status = 2;
            $status_order = $this->status_order->get($new_status);
            /*
             * Save Customer
             */
            $cu_id = $this->customer->add(array(
                'cu_name' => $name,
                'cu_email' => $email,
                'cu_document' => $doc,
                'cu_phone' => '',
            ));
            /*
             * Save Order
             */
            $or_id = $this->order->add(array(
                'or_cu_id' => $cu_id,
                'or_total' => $total,
                'or_so_id' => $new_status,
                'or_code_auth' => "00" . rand(10, 120),
                'or_card' => "VISA",
                'or_card_number' => "7980",
                'or_ticket' => rand(10, 120),
                'or_number_operation' => rand(10, 120),
                'or_validate_address' => "VTE0010",
                'or_observation' => "APROBADA  (authno)"
            ));

            /*
             * Save History
             */
            $this->order_history->add(array(
                'oh_or_id' => $or_id,
                'oh_old_status' => 1,
                'oh_new_status' => $new_status,
                'oh_ad_id' => 0,
                'oh_observation' => "Sin observaciones",
            ));

            foreach ($itemsArray as $key => $item):
                $c++;
                $subtotal = 0;
                $product = $this->product->get($item['id']);
                $subtotal = ($item["price"] * $item['amount']);
                /*
                 * Save Product by Order
                 */
                $this->order_product->add(array(
                    'op_or_id' => $or_id,
                    'op_pr_id' => $product["pr_id"],
                    'op_description' => $product["pr_name"],
                    'op_amount' => $item['amount'],
                    'op_price_list' => $product["pr_price"],
                    'op_price' => $item["price"],
                    'op_subtotal' => $subtotal
                ));
                /*
                 * Decrement stock
                 */
                $this->product->edit(array('pr_stock' => new Zend_Db_Expr('pr_stock - ' . $item['amount'])), $product["pr_id"]);

                /*
                 * E-Mail product
                 */
                $product_email.='<tr>'
                        . '<td valign="top" style="padding: 0 20px; color: #000;  font-family: Verdana; line-height: 20px; font-size:13px;">' . $c . '</td>'
                        . '<td valign="top" style="padding: 0 20px; color: #000;  font-family: Verdana; line-height: 20px; font-size:13px;">' . $product["pr_name"] . '</td>'
                        . '<td valign="top" style="padding: 0 20px; color: #000;  font-family: Verdana; line-height: 20px; font-size:13px;" align="right"><strong>$' . number_format($item["price"], 2, ",", ".") . '</strong></td>'
                        . '<td valign="top" style="padding: 0 20px; color: #000;  font-family: Verdana; line-height: 20px; font-size:13px;" align="right"><strong>' . $item["amount"] . '</strong></td>'
                        . '<td valign="top" style="padding: 0 20px; color: #000;  font-family: Verdana; line-height: 20px; font-size:13px;" align="right"><strong>$' . number_format($subtotal, 2, ",", ".") . '</strong></td>'
                        . '</tr>';
            endforeach;

            //SEND EMAIL USER
            $this->_mail = Zend_Controller_Action_HelperBroker::getStaticHelper('Mail');
            $body = '<table cellspacing="0" border="0" height="auto" cellpadding="0" width="600">
              <tr>
              <td class="article-title" height="45" valign="top" style="padding: 0 20px; font-family: Verdana; font-size: 20px; font-weight: bold;" width="600" colspan="2">
              Hola ' . $name . ',
              </td>
              </tr>
              <tr>
              <td class="content-copy" valign="top" style="padding: 0 20px; color: #000; font-family: Verdana; line-height: 20px;" colspan="2">
              <p>Tu pedido ya está listo para que lo retires</p>
              <table cellspacing="0" border="0" height="auto" cellpadding="0" width="600">

              <tr>
              <th align="left" style="padding: 0 20px;">N# de Orden</th>
              <td><strong># ' . $or_id . '</strong></td>
              </tr>
              <tr>
              <th align="left" style="padding: 0 20px;">Fecha y Hora</th>
              <td>' . date("d-m-Y H:i") . '</td>
              </tr>
              <tr>
              <th align="left" style="padding: 0 20px;">Estado</th>
              <td>' . $status_order["so_name"] . '</td>
              </tr>
              </table>
              <br/>
              <br/>
              <table cellspacing="0" border="0" height="auto" cellpadding="0" width="600">
              <tr>
              <th align="left" style="padding: 0 20px;">Nº.</th>
              <th align="left" style="padding: 0 20px;">Descripción.</th>
              <th align="right" style="padding: 0 20px;">Precio</th>
              <th align="right" style="padding: 0 20px;">Cant.</th>
              <th align="right" style="padding: 0 20px;">Subtotal.</th>
              </tr>
              ' . $product_email . '
              <tr><td colspan="4" style="padding: 0 20px; color: #000;  font-family: Verdana; line-height: 20px;" align="right"><strong>TOTAL:</strong></td>
              <td style="padding: 0 20px; color: #000;  font-family: Verdana; line-height: 20px;" align="right"><strong>$' . number_format($total, 2, ",", ".") . '</strong></td></tr>
              </table>
              </td>
              </tr>
              </table>';
            $template_newsletter = file_get_contents(PUBLIC_PATH . DS . "html" . DS . "template.html");
            $html = str_replace("##urlEmail##", $this->getBaseUrl() . "/", $template_newsletter);
            $html = str_replace("##titleEmail##", "Orden de compra N#: " . $or_id, $html);
            $html = str_replace("##subtitleEmail##", "GRACIAS POR TU COMPRA", $html);
            $html = str_replace("##footerEmail##", $this->setting["se_footer"], $html);
            $html = str_replace("##contentEmail##", $body, $html);
            $this->_mail->sendEmail($html, $this->setting["se_title"] . " - Orden de compra N#: " . $or_id, $email, $name);

            //SEND EMAIL ADMIN
            $body = '<table cellspacing="0" border="0" height="auto" cellpadding="0" width="600">
              <tr>
              <td class="article-title" height="45" valign="top" style="padding: 0 20px; font-family: Verdana; font-size: 20px; font-weight: bold;" width="600" colspan="2">
              Nuevo Orden de Compra de "' . $name . '"
              </td>
              </tr>
              <tr>
              <td class="content-copy" valign="top" style="padding: 0 20px; color: #000; font-family: Verdana; line-height: 20px;" colspan="2">
              <p>Resumen de la orden</p>
              <table cellspacing="0" border="0" height="auto" cellpadding="0" width="600">

              <tr>
              <th align="left" style="padding: 0 20px;">N# de Orden</th>
              <td><strong><a href="' . $this->getBaseUrl() . '/admin/order/detail/id/' . $or_id . '"># ' . $or_id . '</a></strong></td>
              </tr>
              <tr>
              <th align="left" style="padding: 0 20px;">Fecha y Hora</th>
              <td>' . date("d-m-Y H:i") . '</td>
              </tr>
              <tr>
              <th align="left" style="padding: 0 20px;">Estado</th>
              <td>' . $status_order["so_name"] . '</td>
              </tr>
              </table>
              <br/>
              <br/>
              <table cellspacing="0" border="0" height="auto" cellpadding="0" width="600">
              <tr>
              <th align="left" style="padding: 0 20px;">Nº.</th>
              <th align="left" style="padding: 0 20px;">Descripción.</th>
              <th align="right" style="padding: 0 20px;">Precio</th>
              <th align="right" style="padding: 0 20px;">Cant.</th>
              <th align="right" style="padding: 0 20px;">Subtotal.</th>
              </tr>
              ' . $product_email . '
              <tr><td colspan="4" style="padding: 0 20px; color: #000;  font-family: Verdana; line-height: 20px;" align="right"><strong>TOTAL:</strong></td>
              <td style="padding: 0 20px; color: #000;  font-family: Verdana; line-height: 20px;" align="right"><strong>$' . number_format($total, 2, ",", ".") . '</strong></td></tr>
              </table>
              </td>
              </tr>
              </table>';
            $template_newsletter = file_get_contents(PUBLIC_PATH . DS . "html" . DS . "template.html");
            $html = str_replace("##urlEmail##", $this->getBaseUrl() . "/", $template_newsletter);
            $html = str_replace("##titleEmail##", "Orden de compra N#: " . $or_id, $html);
            $html = str_replace("##subtitleEmail##", "no responda este correo", $html);
            $html = str_replace("##footerEmail##", $this->setting["se_footer"], $html);
            $html = str_replace("##contentEmail##", $body, $html);
            $this->_mail->sendEmail($html, $this->setting["se_title"] . " - Nueva Orden de compra N#: " . $or_id, $this->setting["se_system_email"], $this->setting["se_email_name"]);

            return array('response' => 1, 'data' => $or_id);
        } catch (Exception $ex) {
            return array('response' => 0, 'message' => $ex->getMessage());
        }
    }

    /**
     * Metodo que devuelve una lista de Medios de Pago 
     *
     * @return Array
     */
    public function getPayMethod() {
        try {
            $this->paymethod = new Model_DBTable_Paymethod();
            $pm = $this->paymethod->showAll("pm_status=1");
            return array('response' => 1, 'data' => $pm);
            
        } catch (Exception $ex) {
            return array('response' => 0, 'message' => $ex->getMessage());
        }
    }

}
