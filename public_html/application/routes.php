<?php 
$routesArray['contacto']=array('module' => 'default', 'controller' => 'contact');
$routesArray['nosotros/sistema_eidico']=array('module' => 'default', 'controller' => 'about','action'=>'system');

foreach ($routesArray as $key => $value) {
	$route = new Zend_Controller_Router_Route ($key, $value);
	$router->addRoute($key, $route);
}

?>