<?php
/**
 * Shortlink - A short to long links translator
 * @version 2.2
 * @copyright (C) 2004 by Tobias Gesellchen, www.gesellix.de - All rights reserved!
 * thanks to Daniel Janesch (admin@the-deejay.com, www.the-deejay.com)
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $database, $mosConfig_live_site, $mainframe;

// default location
$link = $mosConfig_live_site;

$phrase = mosGetParam( $_GET, 'phrase' );

$database->setQuery("SELECT * FROM #__shortlink WHERE phrase='$phrase'");

$my = null;
if ($database->loadObject( $my ))  // found something?
{
  // update statistics
  $my->counter++;
  $my->last_call = date("Y-m-d H:i:s");
  $database->updateObject('#__shortlink', $my, 'id', true);

  // if $link is a number, we will use it as content id
  if (intval($my->link))
  {
    $link = "index.php?option=content&task=view&id=".$my->link."&Itemid=".$mainframe->getItemid( $my->link );
    $link = sefRelToAbs($link);
  }
  else
  {
    $link = $my->link;
  }

  if (!(startswith($link, "http://")
     || startswith($link, "https://")
     || startswith($link, "ftp://")
     || startswith($link, "mailto://")
     || startswith($link, "file://")))
  {
    $link = sefRelToAbs($link);
  }
}

// redirect to new location
header("Location: $link");

function startswith($source, $mask)
{
  $pos = strpos($source, $mask);
  return (($pos !== false) && ($pos == 0));
}

?>