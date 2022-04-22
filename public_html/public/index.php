<?php
ini_set('memory_limit', '-1');
// header('Access-Control-Allow-Origin: *');
// date_default_timezone_set('America/Argentina/Buenos_Aires');
error_reporting(E_ERROR); 
ini_set("soap.wsdl_cache_enabled", 0);
// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
defined('PUBLIC_PATH') || define('PUBLIC_PATH', realpath(dirname(__FILE__)));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../library/Inamika'),
	realpath(APPLICATION_PATH . '/../application/models/DbTable'),
	realpath(APPLICATION_PATH . '/modules/admin/controllers/helpers'),
	get_include_path(),
	)));

//PAGINATOR
define('COUNTPERPAGE', 30);
define('TIME_SESSION',10);
define('PAGERANGE', 20);
define('FORCE_CUT_GALLERY', true);
define('DS', DIRECTORY_SEPARATOR);
define('URL_IMG','/files/');
define('PATH_IMG', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR);
define('URL_SERVICE','http://' . $_SERVER['HTTP_HOST'].str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]).'service/index/');
define('PREFIX_GALLERY', "apachecms__");
define('TITLE_SITE', "Titulo"); //
define('COPYRIGTH', "Pie"); //
define('PATH_CONTENT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR."content".DIRECTORY_SEPARATOR);

define('SHOW_MESSAGE_CHAT', 4); 
define('MESSAGE_ERR', "Ocurri&oacute; algun error en la ejecuci&oacute;n de la transacci&oacute;n."); 
define('MESSAGE_NEW', "<b>Informaci&oacute;n:</b> Alta exitosa!"); 
define('MESSAGE_EDI', "<b>Informaci&oacute;n:</b> Edici&oacute;n exitosa!");
define('MESSAGE_DEL', "<b>Informaci&oacute;n:</b> Eliminaci&oacute;n exitosa!"); 
define('MESSAGE_CAN', "<b>Informaci&oacute;n:</b> Operaci&oacute;n cancelada!"); 
$date_last = strtotime ( '-1 year' , strtotime ( date('Y-m-d') ) ) ;
$date_last = date ( 'Y-m-d' , $date_last );
define('DATE_LAST',$date_last);

define('HEADER','<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
		<title>Meganalizar</title>
	</head>
	<body>');
define('FOOTER','</body></html>');

/** Zend_Application */
require_once 'Zend/Application.php';
define('HOST', 'http://' . $_SERVER['HTTP_HOST']);

// Create application, bootstrap, and run
$application = new Zend_Application(
        APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()->run();