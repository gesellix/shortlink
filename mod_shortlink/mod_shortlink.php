<?php
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

// include the helper file
require_once(dirname(__FILE__) . DS . 'helper.php');

// Setup the module standard parameter settings
//$url          = sefRelToAbs("index.php"); TODO?
$url          = "index.php";
$text         = $params->get( 'text',  "shortlink..." );
$introduction = $params->get( 'introduction', "0" );
$presentation = $params->get( 'presentation', "2" );

//require(JModuleHelper::getLayoutPath('mod_shortlink'));
switch($presentation)
{
	case "0":
		$shortlinks = null;

		require(JModuleHelper::getLayoutPath('mod_shortlink', 'as_input'));
		break;
	case "1":
		$shortlinks = ModShortlinkHelper::getItems();

		require(JModuleHelper::getLayoutPath('mod_shortlink', 'as_list'));
		break;
	default:
	case "2":
		$shortlinks = ModShortlinkHelper::getItems();

		JHTML::_('behavior.combobox');
		require(JModuleHelper::getLayoutPath('mod_shortlink', 'as_dynabox'));
		break;
}

?>