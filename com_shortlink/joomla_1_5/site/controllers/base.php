<?php

jimport('joomla.application.component.controller');

class ShortlinkControllerBase extends JController
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
		//parent::display();

		// default location
		$link = JURI::base();
		
		$myModel = $this->getModel();
		
		$phrase = JRequest::getVar( 'phrase' );
		$my = $myModel->getShortlink( $phrase );
		
		if ($my)
		{
    	    // if $link is a number, we will use it as content id
			if (intval($my->link))
	        {
	        	$mainframe = &JFactory::getApplication();
	        	$link = "index.php?option=content&task=view&id=".$my->link."&Itemid=".$mainframe->getItemid( $my->link );
	        	$link = sefRelToAbs($link);
	        }
	        else
	        {
	        	$link = $my->link;

	        	if (!($this->startswith($link, "http://")
		        	|| $this->startswith($link, "https://")
		        	|| $this->startswith($link, "ftp://")
		        	|| $this->startswith($link, "mailto://")
		        	|| $this->startswith($link, "file://")))
		        {
		        	$link = sefRelToAbs($link);
		        }
	        }
		}

		// redirect to new location
		header("Location: $link");
	}

	function startswith($source, $mask)
	{
	  $pos = strpos($source, $mask);
	  return (($pos !== false) && ($pos == 0));
	}
}
?>