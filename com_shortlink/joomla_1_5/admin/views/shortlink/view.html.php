<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class ShortlinksViewShortlink extends JView
{
	function display($tpl = null)
	{
		//get the shortlink
		$shortlink		=& $this->get('Data');
		$articles		=& $this->get('Articles');
		$isNew		= ($shortlink->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Shortlink' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::apply('apply');
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('shortlink',		$shortlink);
		$this->assignRef('articles',		$articles);
		
		parent::display($tpl);
	}
}