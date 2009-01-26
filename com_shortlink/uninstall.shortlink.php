<?php
/**
 * Shortlink - A short to long links translator
 * @version 2.2
 * @copyright (C) 2004 by Tobias Gesellchen, www.gesellix.de - All rights reserved!
 * thanks to Daniel Janesch (admin@the-deejay.com, www.the-deejay.com)
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

function com_uninstall()
{
  global $mosConfig_absolute_path, $database;

  unlink($mosConfig_absolute_path.'/goto.php');
?>

  <p>Shortlink successfully uninstalled.</p>
<!--  <p>Please remove the file \"goto.php\" manually.</p> -->
  <p>For more information, please contact <a href="http://www.gesellix.de/" title="www.gesellix.de" target="_blank">www.gesellix.de</a>.</p>

<?php
  return true;
}
?>