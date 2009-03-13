<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

class JElementCheckconfigbutton extends JElement
{
	/**
	* Element type
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Checkconfigbutton';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );

		return "<a href=\"#\" onclick=\"javascript:alert('TODO');return false;\">".JText::_( 'Move file to new location' )."</a>";
	}
}