<?php
/**
 * Shortlink - A short to long links translator
 * @version 2.2
 * @copyright (C) 2004 by Tobias Gesellchen, www.gesellix.de - All rights reserved!
 * thanks to Daniel Janesch (admin@the-deejay.com, www.the-deejay.com)
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class menuShortlink
{
  function CONFIG_MENU()
  {
    mosMenuBar::startTable();
    mosMenuBar::spacer();
    mosMenuBar::save('saveconfig');
    mosMenuBar::cancel('cancelconfig');
    mosMenuBar::spacer();
    mosMenuBar::endTable();
  }

  function UNINSTALL_MENU()
  {
    mosMenuBar::startTable();
    mosMenuBar::custom( 'uninstalltask', 'delete.png', 'delete_f2.png', 'delete tables', false );
    mosMenuBar::back();
    mosMenuBar::spacer();
    mosMenuBar::endTable();
  }

  function EDIT_MENU()
  {
    mosMenuBar::startTable();
    mosMenuBar::save();
    mosMenuBar::cancel();
    mosMenuBar::spacer();
    mosMenuBar::endTable();
  }

  function DEFAULT_MENU()
  {
    mosMenuBar::startTable();
//    mosMenuBar::custom( $task='addLinks', $icon='publish.png', $iconOver='publish_f2.png', $alt='Shortlink', false );
//    mosMenuBar::custom( $task='delLinks', $icon='unpublish.png', $iconOver='unpublish_f2.png', $alt='unShortlink', false );
//    mosMenuBar::divider();

    mosMenuBar::addNew();
    mosMenuBar::editList();
    mosMenuBar::deleteList();
    mosMenuBar::spacer();
    mosMenuBar::endTable();
  }
}
?>