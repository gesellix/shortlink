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
		$shortlink = $myModel->getShortlink( $phrase );

		if ($shortlink)
		{
    	    // if $link is a number, we will use it as content id
			if (intval($shortlink->link))
	        {
	        	$link = $myModel->getArticleUrl($shortlink->link);
	        	
	        	$link = JRoute::_($link, false);
	        }
	        else
	        {
	        	$link = $shortlink->link;

	        	// is relative url?
	        	if (!($this->isExternal($link)
	        	    || $this->startswith($link, "http://")
		        	|| $this->startswith($link, "https://")
		        	|| $this->startswith($link, "ftp://")
		        	|| $this->startswith($link, "mailto://")
		        	|| $this->startswith($link, "file://")))
		        {
		        	$link = JRoute::_($link, false);
		        }
	        }
		}

		// redirect to new location
		header("Location: $link");
	}

  function isExternal($link)
  {
  	$link_pattern='/^(http:\/\/|)(www.|)([^\/]+)/i';

    preg_match($link_pattern, $link, $domain);
    preg_match($link_pattern, $_SERVER['HTTP_HOST'], $http);

    return $this->startswith($link, "http") && ((isset($domain[3])) and (isset($http[3])) and ($domain[3]!==$http[3]));
  }

function startswith($source, $mask)
	{
	  $pos = strpos($source, $mask);
	  return (($pos !== false) && ($pos == 0));
	}
}
?>