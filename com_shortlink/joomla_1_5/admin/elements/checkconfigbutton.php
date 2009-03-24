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
		$params = &JComponentHelper::getParams( 'com_shortlink' );
		$source_path = $params->def('helper_path', JPATH_SITE.DS.'goto.php');

		$size = $node->attributes('size');

		$parameter_txt =& $this->_parent->loadElement('text');
		$parameter_hid =& $this->_parent->loadElement('hidden');
		
		$node->addAttribute('size', $size);
		$input_cur = $parameter_txt->fetchElement('Current', $source_path, $node, $control_name);

		$node->addAttribute('size', $size);
		$input_new = $parameter_txt->fetchElement($name, $value, $node, $control_name);
		
		$js = 'onclick="javascript:window.parent.onMoveHelperFile(document.getElementById(\'paramsCurrent\'), document.getElementById(\'params'.$name.'\'), document.getElementById(\'lbl_working\'));"';
		$btn = "<a href=\"#\" ".$js." >".JText::_( 'Move file to new location now' )."</a>";
		
		$lbl_working = '<label id="lbl_working"></label>';
		
		$result = JText::_( 'Current location: ' ).'<br />'.$input_cur;
		$result .= '<br />'.JText::_( 'New location: ' ).'<br />'.$input_new;
		$result .= '<br />'.$btn;
		$result .= '<br />'.$lbl_working;
		
		return $result;
	}

	function appendDsIfNeeded($path)
	{
	  	if ( !empty( $path ) && substr( $path, -1 ) != DS )
	  	{
			$path .= DS;
		}
		return $path;
	}
}