<?php

defined('_JEXEC') or die( 'Restricted access' );

function com_uninstall()
{
  unlink(JPATH_SITE.DS.'goto.php');
?>

  <p>Shortlink successfully uninstalled.</p>
  <p>For more information, please contact <a href="http://www.gesellix.de/" title="www.gesellix.de" target="_blank">www.gesellix.de</a>.</p>

<?php
  return true;
}
?>