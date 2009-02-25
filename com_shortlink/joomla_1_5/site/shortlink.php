<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controllers'.DS.'base.php');

// Require specific controller if requested
if($controller = JRequest::getVar('controller')) {
	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
} else {
	$controller = 'base';
}

// Create the controller
$classname	= 'ShortlinkController'.$controller;
$controller = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();

?>