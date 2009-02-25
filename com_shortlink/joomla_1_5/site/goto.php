<?php

// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) );

define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$mainframe =& JFactory::getApplication('site');

$params = &JComponentHelper::getParams( 'com_shortlink' );

// default location
$link = "index.php";

$phrase = $_GET[$params->get('paramname', 'link')];
if ($phrase) {
	$link = "index.php?option=com_shortlink&phrase=".$phrase;
}

// redirect to new location
header("Location: $link");

?>