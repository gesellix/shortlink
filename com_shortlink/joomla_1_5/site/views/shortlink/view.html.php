<?php

jimport( 'joomla.application.component.view');

class ShortlinkViewShortlink extends JView
{
	function display($tpl = null)
	{
		$greeting = $this->get( 'Greeting' );
		$this->assignRef( 'greeting',	$greeting );

		parent::display($tpl);
	}
}
?>