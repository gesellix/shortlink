<?php

defined('_JEXEC') or die('Restricted access');

function com_uninstall() {
   $default_path = JPATH_SITE . DS . 'goto.php';

   $params = &JComponentHelper::getParams('com_shortlink');
   $helper_path = $params->get('helper_path', $default_path);
   if ($helper_path == 'goto.php') {
      $helper_path = $default_path;
   }

   unlink($helper_path);
   ?>

<p>Shortlink successfully uninstalled.</p>
<p>For more information, please contact <a href="http://www.gesellix.de/" title="www.gesellix.de" target="_blank">www.gesellix.de</a>.</p>

<?php
   return true;
}

?>