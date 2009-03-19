<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class ShortlinksControllerBase extends JController
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
		parent::display();
	}
	
	function rename()
	{
		// TODO rename/move file to new location.
		// TODO edit JBASE_PATH to match relative path to Joomla
		// TODO deliver success msg.

		//Old Options
		$params = &JComponentHelper::getParams( 'com_shortlink' );
		$path_old = $params->def('url_path', '');
		$file_old = $params->def('filename', 'goto.php');
		
		// New Options
		$path_new = JRequest::getVar( 'target_path' );
		$file_new = JRequest::getVar( 'target_file' );

		echo "TARGET:".$path_new.DS.$file_new.".";
		
		global $mainframe;
		$mainframe->close();
	}

	function moveFile($path, $filename)
	{
		$params = &JComponentHelper::getParams( 'com_shortlink' );

		//Old Options
		$path_old = $params->def('url_path', '');
		$filename_old = $params->def('filename', 'goto.php');

		// TODO move from ($path_old.DS.$filename_old) to ($path.DS.$filename)
		// then check for success.

		/*
		clearstatcache();
		@chmod ($config_file, 0766);
	
		$configpermission = is_writable($config_file);
	  	if (!$configpermission)
	  	{
	    	mosRedirect("index2.php?option=$option&task=missingconfig");
	    	break;
	  	}
	
		if ($fp = fopen("$config_file", "w+"))
	  	{
	    	fputs($fp, $configtxt, strlen($configtxt));
	    	fclose($fp);
	  	}
	  	$mosmsg = "Config is saved !";
	  	mosRedirect("index2.php?option=$option&task=config", $mosmsg);
	  	*/
	}
}