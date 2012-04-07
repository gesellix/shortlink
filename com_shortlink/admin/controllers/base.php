<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class ShortlinksControllerBase extends JController {
   /**
    * Method to display the view
    *
    * @access   public
    */
   function display() {
      parent::display();
   }

   function rename() {
      $source_path = JRequest::getVar('path_old');
      $target_path = JRequest::getVar('path_new');

      // replace directory separators with locally valid ones
      $target_path = preg_replace('#\\\\#', DS, $target_path);
      $target_path = preg_replace('#/#', DS, $target_path);

      $pattern_path = '#.+?\\' . DS . '.+?#';

      $matches = preg_match($pattern_path, $source_path);
      if (!$matches || !file_exists($source_path)) {
         $this->closeAjax(JText::_("ERR_WRONG_PATH_OLD"));
         return;
      }

      $matches = preg_match($pattern_path, $target_path);

      // replace directory separators with locally valid ones
      $regExp = $this->replaceDS($_SERVER["DOCUMENT_ROOT"]);
      $regExp = preg_quote($regExp);
      $regExp = '$^' . $regExp . '$i';

      // Check if the new path is not out of the domain root!
      // TODO symlinks don't work here
      if (!$matches || empty($target_path) || !preg_match($regExp, $target_path)) {
         // TODO: If moving to new path is not possible either don't let save the settings or
         // set the new path to old path!
         $this->closeAjax(JText::_("ERR_WRONG_PATH_NEW"));
         return;
      }

      $result = copy($source_path, $target_path);
      if ($result) {
         $lines = file($target_path);

         $handle = fopen($target_path, 'w');

         foreach ($lines as $index => $line) {
            $matches = preg_match("/define\('JPATH_BASE', .*\);/", $line);
            if ($matches) {
               // use JPATH_ROOT always for path to joomla:
               $tmp = $this->replaceDS(JPATH_ROOT);
               $tmp = preg_replace('#\\' . DS . '#', '\'.DS.\'', $tmp);

               fwrite($handle, "define('JPATH_BASE', '" . $tmp . "' );\n");
            }
            else {
               fwrite($handle, $line);
            }
         }

         fclose($handle);

         unlink($source_path);

         // TODO language
         $this->closeAjax(JText::_("Success moving " . $source_path . " to " . $target_path));
      }
      else {
         // TODO language
         $this->closeAjax(JText::_("Error moving " . $source_path . " to " . $target_path));
      }

      // $mainframe should already be closed here (AJAX request)!
      return;
   }

   function closeAjax($msg = '') {
      if ($msg) {
         echo $msg;
      }

      // exit Joomla - this is only an AJAX request
      $mainframe = JFactory::getApplication();
      $mainframe->close();
   }

   function replaceDS($path) {
      // replace directory separators with locally valid ones
      $tmp = preg_replace('#\\\\#', DS, $path);
      $tmp = preg_replace('#/#', DS, $path);
      return $tmp;
   }
}