<?php
/**
 * Shortlink - A short to long links translator
 * @version 2.2
 * @copyright (C) 2004 by Tobias Gesellchen, www.gesellix.de - All rights reserved!
 * thanks to Daniel Janesch (admin@the-deejay.com, www.the-deejay.com)
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );
require_once( $mainframe->getPath( 'toolbar_default' ) );

switch ( $task )
{
  case "uninstall":
    menuShortlink::UNINSTALL_MENU();
    break;

  case "config":
  case "missingconfig":
  case "saveconfig":
    menuShortlink::CONFIG_MENU();
    break;

  case "new":
  case "edit":
    menuShortlink::EDIT_MENU();
    break;

  default:
    menuShortlink::DEFAULT_MENU();
    break;
}
?>