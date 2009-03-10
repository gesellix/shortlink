<?php

// Set flag that this is a parent file
define( '_JEXEC', 1 );

define( 'DS', DIRECTORY_SEPARATOR );

// TODO was, wenn das cms in einem Unterordner liegt?!
define('JPATH_BASE', dirname(__FILE__) );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$mainframe =& JFactory::getApplication('site');

$params = &JComponentHelper::getParams( 'com_shortlink' );

// default location
$link = "index.php";

foreach(explode(",", $params->get('paramname', 'link')) as $linkname) {
  $phrase = $_GET[$linkname];
  if ($phrase)
  {
  	$url_path = $params->get('url_path', '');
  	if ( !empty( $url_path ) && substr( $url_path, -1 ) != DIRECTORY_SEPARATOR )
  	{
		$url_path .= DIRECTORY_SEPARATOR;
	}

    $link = $url_path."index.php?option=com_shortlink&phrase=".$phrase;
    break;
  }
} // foreach

// redirect to new location
header("Location: $link");

?>