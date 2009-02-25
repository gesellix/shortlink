<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Require the base controller
$controller_base = JPATH_COMPONENT.DS.'controllers'.DS.'base'.'.php';
require_once ($controller_base);

// Require specific controller if requested
$controller = JRequest::getWord('controller');
if (!$controller) {
	$controller = 'base';
}
$controller_specific = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
if ($controller_base != $controller_specific) {
	require_once ($controller_specific);	
}

// Create the controller
$classname	= 'ShortlinksController'.$controller;
$controller	= new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();