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
		//Old Options
		$params = &JComponentHelper::getParams( 'com_shortlink' );
		$path_old = $params->def('url_path', JPATH_SITE);
		$file_old = $params->def('filename', 'goto.php');
		
		// New Options
		$path_new = JRequest::getVar( 'target_path' );
		$file_new = JRequest::getVar( 'target_file' );

		$path_new = (empty($path_new) ? JPATH_SITE : $path_new);
		
		$path_old = $this->appendDsIfNeeded($path_old);
		$path_new = $this->appendDsIfNeeded($path_new);

		$source_path = $path_old.$file_old;
		$target_path = $path_new.$file_new;

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
		    		$tmp = preg_replace('/\\\\/', '\'.DS.\'', $path_new);
		    		$tmp = preg_replace('#/#', '\'.DS.\'', $tmp);

		    		fwrite($handle, "define('JPATH_BASE', '".$tmp."' );\n");
		    	}
		    	else
		    	{
		    		fwrite($handle, $line);
    	    	}
		    }
		    
			fclose($handle);
			
			unlink($source_path);

			echo "Success moving ".$source_path." to ".$target_path;
		}
		else
		{
			echo "Error moving ".$source_path." to ".$target_path;
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