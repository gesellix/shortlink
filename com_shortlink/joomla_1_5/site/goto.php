<?php

// Set flag that this is a parent file
define( '_JEXEC', 1 );

define( 'DS', DIRECTORY_SEPARATOR );

/* DON'T EDIT THE FOLLOWING LINE! IT CAN BE EDITED IN THE COMPONENT CONFIGURATION. */
define('JPATH_BASE', dirname(__FILE__) );

// get component parameters
$params = getParams();

// generate target link. Defaults to 'index.php' if no valid link/phrase is found
$link = getLink($params);

// redirect to new location
header("Location: ".$link);


////////////////////////////////////////////////////
// internal functions



function getParams()
{
	require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

	$mainframe =& JFactory::getApplication('site');

	$params = &JComponentHelper::getParams( 'com_shortlink' );

	return $params;
}

function getLink($params)
{
	// default location
	$link = "index.php";

	$linknames = explode(",", $params->get('paramname', 'link'));
	foreach($linknames as $linkname) {
	  $phrase = $_GET[$linkname];
	  if ($phrase)
	  {
	  	$url_path = getUrlPath();
	
	  	// optionally go to a subdirectory
	    $link = $url_path;
	    
	    // base url
	    $link .= "index.php?option=com_shortlink&phrase=".$phrase;

	    // don't let Joomla send any output
	    // see http://blog.joomlatools.eu/2008/01/joomla-15-generating-raw-ouput.html
	    $link .= "&tmpl=component";
	    
	    break;
	  }
	} // foreach
	
	return $link;
}

function getUrlPath()
{
  	$url_path = '';

  	$this_dir = dirname(__FILE__);
  	if ($this_dir != JPATH_BASE)
  	{
		if (strlen($this_dir) > strlen(JPATH_BASE))
  		{
  			$url_path = substr($this_dir, strlen(JPATH_BASE) + 1);
  		}
  		else if (strlen($this_dir) < strlen(JPATH_BASE))
  		{
  			$url_path = substr(JPATH_BASE, strlen($this_dir) + 1);
    	}
		else
		{
			// Houston, we've got a problem...
		}
  	}

  	if ( !empty( $url_path ) && substr( $url_path, -1 ) != '/' )
  	{
		$url_path .= '/';
	}
  	
  	return $url_path;
}
?>