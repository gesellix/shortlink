<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ShortlinkModelShortlink extends JModel
{
	function getGreeting()
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT greeting FROM #__shortlink';
		$db->setQuery( $query );
		$greeting = $db->loadResult();

		return $greeting;
	}
}
