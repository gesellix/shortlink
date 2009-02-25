<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class ShortlinksViewShortlinks extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'Shortlinks Manager' ), 'generic.png' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::preferences('com_shortlink', '200');
		//JToolBarHelper::help( 'screen.shortlink' );
		
		// Get data from the model
		$items		= & $this->get( 'Data');

		$this->assignRef('items',		$items);

		parent::display($tpl);
	}
}