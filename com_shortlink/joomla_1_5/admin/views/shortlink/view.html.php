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
    	
    	$baseURL = "";
    	$baseURL .= ($_SERVER["SERVER_PORT"]=="443" ? "https" : "http").":////";
    	$baseURL .= $_SERVER["HTTP_HOST"]."/";
    	$baseURL .= str_replace($_SERVER["DOCUMENT_ROOT"], "", $params->def('helper_path', ''));
    	$baseURL = str_replace("//", "/", $baseURL);

    	// Because we can have more than one paramname for the shorlink we take the first given:
    	$linknames = explode(",", $params->get('paramname', 'link'));
    	// http://www.example.com/goto.php?link=
    	$fullshortLink = $baseURL."?".$linknames[0]."=";
		
		$isNew		= ($shortlink->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Shortlink' ).': <small><small>[ ' . $text . ' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', JText::_('Close'));
		}

		$this->assignRef('shortlink',		$shortlink);
		$this->assignRef('articles',		$articles);
		$this->assignRef('baseURL',			$baseURL);
		$this->assignRef('fullshortLink',	$fullshortLink);
		
		parent::display($tpl);
	}
}