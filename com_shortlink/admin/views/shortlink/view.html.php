<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class ShortlinksViewShortlink extends JView
{
	function display($tpl = null)
	{
		$default_path = JPATH_SITE.DS.'goto.php';
		
		$params = &JComponentHelper::getParams( 'com_shortlink' );

		//get the shortlink
		$shortlink		=& $this->get('Data');
		$articles		=& $this->get('Articles');

    	$helper_path = $params->def('helper_path', $default_path);
		if ($helper_path == 'goto.php')
		{
			$helper_path = $default_path;
		}

		// make directory separators look equal
		$helper_path = preg_replace('#\\\\#', '/', $helper_path);
		$doc_root = preg_replace('#\\\\#', '/', $_SERVER["DOCUMENT_ROOT"]);
    	
    	$baseURL = "";
    	$baseURL .= ($_SERVER["SERVER_PORT"]=="443" ? "https" : "http").":////";
    	$baseURL .= $_SERVER["HTTP_HOST"]."/";
    	$baseURL .= str_replace($doc_root, "", $helper_path);
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