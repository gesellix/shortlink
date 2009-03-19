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

		$js = 'onclick="javascript:window.parent.onMoveHelperFile(document.getElementById(\'paramsurl_path\'), document.getElementById(\'paramsfilename\'), document.getElementById(\'paramsdummy_action-lbl\'));"';
		
		return "<a href=\"#\" ".$js." >".JText::_( 'Move file to new location' )."</a>";
	}
}