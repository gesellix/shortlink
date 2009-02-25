<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class ShortlinksViewShortlink extends JView
{
	function display($tpl = null)
	{
		$params = &JComponentHelper::getParams( 'com_shortlink' );

		//get the shortlink
		$shortlink		=& $this->get('Data');
		$articles		=& $this->get('Articles');

    	$baseURL = $params->def('url_path', JURI::root());

		// http://www.example.com/goto.php?link=
    	$fullshortLink = $baseURL;
    	if ((strrpos($fullshortLink, "/") + 1) < strlen($fullshortLink)) {
    		$fullshortLink .= "/";
    	}      
    	$fullshortLink .= $params->def('filename', 'goto.php')."?".$params->def('paramname', 'link')."=";
		
		$isNew		= ($shortlink->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Shortlink' ).': <small><small>[ ' . $text . ' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('shortlink',		$shortlink);
		$this->assignRef('articles',		$articles);
		$this->assignRef('baseURL',			$baseURL);
		$this->assignRef('fullshortLink',	$fullshortLink);
		
		parent::display($tpl);
	}
}