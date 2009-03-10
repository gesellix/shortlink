<?php
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
// include the helper file
require_once(dirname(__FILE__).DS.'helper.php');
 
// Setup the module standard parameter settings
//$url          = sefRelToAbs("index.php");
$url          = "index.php";
$text         = "shortlink...";
$introduction = $params->get( 'introduction' );
$showCbo      = $params->get( 'showcbo' );

//require(JModuleHelper::getLayoutPath('mod_shortlink'));
if ($showCbo)
{
	$shortlinks = ModShortlinkHelper::getItems();
	require(JModuleHelper::getLayoutPath('mod_shortlink', 'as_list'));
}
else
{
	require(JModuleHelper::getLayoutPath('mod_shortlink', 'as_input'));
}

?>