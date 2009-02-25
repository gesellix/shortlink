<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ShortlinkModelShortlink extends JModel
{
	function getShortlink($phrase)
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT * FROM #__shortlink';
		$query .= ' WHERE phrase = '. $db->quote( $db->getEscaped( $phrase ), false );

		$db->setQuery( $query );
		$shortlink = $db->loadObject();

		if ($shortlink) {
			// update statistics
			$now =& JFactory::getDate();
			
			$shortlink->counter++;
	  		$shortlink->last_call = $now->toMySQL();
	  		$db->updateObject('#__shortlink', $shortlink, 'id', true);
		}
		
		return $shortlink;
	}
}
?>