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
		$source_path = JRequest::getVar( 'path_old' );
		$target_path = JRequest::getVar( 'path_new' );

    	// replace directory separators with locally valid ones
    	$target_path = preg_replace('#\\\\#', DS, $target_path);
    	$target_path = preg_replace('#/#', DS, $target_path);
		
		$pattern_path = '#.+?\\'.DS.'.+?#'; 

		$matches = preg_match($pattern_path, $source_path);
    	if (!$matches || !file_exists($source_path))
    	{
			$this->closeAjax("'path_old' is wrong or doesn't exist.");
			return;
		}
    	$matches = preg_match($pattern_path, $target_path);
		if (empty($target_path))
		{
			$this->closeAjax("'path_new' is wrong.");
			return;
		}
		
		$result = copy($source_path, $target_path);
		if ($result)
		{
		    $lines = file($target_path);

		    $handle = fopen($target_path, 'w');

		    foreach ($lines as $index => $line)
		    {
		    	$matches = preg_match("/define\('JPATH_BASE', .*\);/", $line);
		    	if ($matches)
		    	{
		    		// cut off '/goto.php'
		    		$tmp = substr($target_path, 0, strrpos($target_path, DS));
			    	$tmp = preg_replace('#\\'.DS.'#', '\'.DS.\'', $tmp);

		    		fwrite($handle, "define('JPATH_BASE', '".$tmp."' );\n");
		    	}
		    	else
		    	{
		    		fwrite($handle, $line);
    	    	}
		    }
		    
			fclose($handle);
			
			unlink($source_path);

			$this->closeAjax("Success moving ".$source_path." to ".$target_path);
		}
		else
		{
			$this->closeAjax("Error moving ".$source_path." to ".$target_path);
		}
		
		// $mainframe should already be closed here (AJAX request)!
		return;
	}

	function closeAjax($msg = '')
	{
		if ($msg)
		{
			echo $msg;
		}

		// exit Joomla - this is only an AJAX request
		global $mainframe;
		$mainframe->close();
	}
	
	function appendDsIfNeeded($path)
	{
	  	if ( !empty( $path ) && substr( $path, -1 ) != DS )
	  	{
			$path .= DS;
		}
		return $path;
	}
}