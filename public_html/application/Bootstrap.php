<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initAutoload() {

        $options = array(
            'layout' => 'default',
            'layoutPath' => '../application/modules/'
            );
        $router = Zend_Controller_Front::getInstance()->getRouter();

        $route = new Zend_Controller_Router_Route ('Inicio',array('module' => 'default','controller' => 'index','action'  => 'index'));
        $router->addRoute('inicio', $route);

        $route = new Zend_Controller_Router_Route ('MiCuenta/Resultados',array('module' => 'default','controller' => 'account','action'  => 'index'));
        $router->addRoute('micuenta', $route);

        $route = new Zend_Controller_Router_Route ('MiCuenta/MisDatos',array('module' => 'default','controller' => 'account','action'  => 'data'));
        $router->addRoute('misdatos', $route);

        $route = new Zend_Controller_Router_Route ('MiCuenta/ListaDePrecios',array('module' => 'default','controller' => 'account','action'  => 'price'));
        $router->addRoute('precios', $route);

        $route = new Zend_Controller_Router_Route ('MiCuenta/Facturación',array('module' => 'default','controller' => 'account','action'  => 'billing'));
        $router->addRoute('facturacion', $route);

        $route = new Zend_Controller_Router_Route ('Notas',array('module' => 'default','controller' => 'news','action'  => 'index'));
        $router->addRoute('notas', $route);

        $route = new Zend_Controller_Router_Route ('Organizacion/Introducción',array('module' => 'default','controller' => 'organization','action'  => 'index'));
        $router->addRoute('organizacion', $route);

        $route = new Zend_Controller_Router_Route ('Organizacion/Historia',array('module' => 'default','controller' => 'organization','action'  => 'history'));
        $router->addRoute('historia', $route);

        $route = new Zend_Controller_Router_Route ('Organizacion/Visión&Misión',array('module' => 'default','controller' => 'organization','action'  => 'vision'));
        $router->addRoute('vision', $route);

        $route = new Zend_Controller_Router_Route ('Organizacion/Autoridades',array('module' => 'default','controller' => 'organization','action'  => 'autority'));
        $router->addRoute('autoridades', $route);

        $route = new Zend_Controller_Router_Route ('Calidad/Introducción',array('module' => 'default','controller' => 'quality','action'  => 'index'));
        $router->addRoute('calidad', $route);

        $route = new Zend_Controller_Router_Route ('Calidad/PolíticasDeCalidad',array('module' => 'default','controller' => 'quality','action'  => 'policies'));
        $router->addRoute('politicas', $route);

        $route = new Zend_Controller_Router_Route ('Calidad/ObjetivosGenerales',array('module' => 'default','controller' => 'quality','action'  => 'target'));
        $router->addRoute('objetivos', $route);

        $route = new Zend_Controller_Router_Route ('Contacto',array('module' => 'default','controller' => 'contact','action'  => 'index'));
        $router->addRoute('contacto', $route);

        $route = new Zend_Controller_Router_Route ('Determinaciones',array('module' => 'default','controller' => 'determinations','action'  => 'index'));
        $router->addRoute('determinaciones', $route);
        
        $route = new Zend_Controller_Router_Route ('TomaDeMuestra',array('module' => 'default','controller' => 'sampling','action'  => 'index'));
        $router->addRoute('tomaDeMuestra', $route);

        $route = new Zend_Controller_Router_Route ('DatosUtiles',array('module' => 'default','controller' => 'usedata','action'  => 'index'));
        $router->addRoute('datosutiles', $route);

        $route = new Zend_Controller_Router_Route ('DatosUtiles/:id',array('module' => 'default','controller' => 'usedata','action'  => 'view'));
        $router->addRoute('datoutil', $route);

        $route = new Zend_Controller_Router_Route ('Nota/:id',array('module' => 'default','controller' => 'news','action'  => 'view'));
        $router->addRoute('nota', $route);

        $route = new Zend_Controller_Router_Route ('Contactenos',array('module' => 'default','controller' => 'index','action'  => 'contact'));
        $router->addRoute('contactenos', $route);

        $route = new Zend_Controller_Router_Route ('M001.json',array('module' => 'api','controller' => 'auth','action'  => 'index'));
        $router->addRoute('M001.json', $route);
        
        $route = new Zend_Controller_Router_Route ('M002.json',array('module' => 'api','controller' => 'result','action'  => 'index'));
        $router->addRoute('M002.json', $route);
        
        // $route = new Zend_Controller_Router_Route ('Api/Muestras.json',array('module' => 'api','controller' => 'muestras','action'  => 'index'));
        // $router->addRoute('Api/Muestras.json', $route);
        
        $route = new Zend_Controller_Router_Route ('Api/Resultados.json',array('module' => 'api','controller' => 'muestras','action'  => 'index'));
        $router->addRoute('Api/Resultados.json', $route);
        
        /*$route = new Zend_Controller_Router_Route ('Api/Determinaciones.json',array('module' => 'api','controller' => 'determinaciones','action'  => 'get'));
        $router->addRoute('Api/Determinaciones.json', $route);*/


        /************************** FRIENDLY URLs ****************************/
        /************************** INSTANCIAR TABLA ****************************/
            // $this->bootstrap('db'); 
            // $db = $this->getResource('tabla');
        /************************ FIN INSTANCIAR TABLA **************************/

    //     $router = Zend_Controller_Front::getInstance()->getRouter();
    //     $route = new Zend_Controller_Router_Route (
    //         'nombre_ruta',
    //         array(
    //             'module' => 'default',
    //             'controller' => 'controller',
    //             'action'     => 'action',
    //             'id'     => 'id'
    //             )
    //         );
    //     $router->addRoute('nombre_ruta', $route);
    // }

            // include_once 'routes.php';
        /************************ FIN FRIENDLY URLs **************************/

        Zend_Layout::startMvc($options);
    }

    protected function _initViewHelpers() {

    }

    protected function _initHelperPath() {
        Zend_Controller_Action_HelperBroker::addPath(
            APPLICATION_PATH . '/modules/admin/controllers/helpers', 'Application_Controller_Action_Helper_');
    }

}
