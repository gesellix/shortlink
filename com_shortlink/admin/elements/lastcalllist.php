<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_shortlink'.DS.'models'.DS.'filter.php');

class JElementLastcalllist extends JElement
{
	/**
	* Element type
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Lastcalllist';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );

		$texts = ShortlinksFilter::getLastCallSelections();

		$options = array ();
		foreach ($texts as $val => $text)
		{
			$options[] = JHTML::_('select.option', $val, JText::_($text));
    	}

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', $class, 'value', 'text', $value, $control_name.$name);
	}
}