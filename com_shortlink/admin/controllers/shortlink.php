<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

class ShortlinksControllerShortlink extends ShortlinksControllerBase {
   /**
    * constructor (registers additional tasks to methods)
    * @return void
    */
   function __construct() {
      parent::__construct();

      // Register Extra tasks
      $this->registerTask('add', 'edit');
   }

   /**
    * display the edit form
    * @return void
    */
   function edit() {
      JRequest::setVar('view', 'shortlink');
      JRequest::setVar('layout', 'form');
      JRequest::setVar('hidemainmenu', 1);

      parent::display();
   }

   /**
    * save a record (and redirect to main page)
    * @return void
    */
   function save() {
      $model = $this->getModel('shortlink');

      if ($model->store($post)) {
         $msg = JText::_('Shortlink saved!');
      } else {
         $msg = JText::_('Error saving Shortlink');
      }

      $link = 'index.php?option=com_shortlink';
      $this->setRedirect($link, $msg);
   }

   /**
    * remove record(s)
    * @return void
    */
   function remove() {
      $model = $this->getModel('shortlink');
      if (!$model->delete()) {
         $msg = JText::_('Error: One or more Shortlinks could not be deleted');
      } else {
         $msg = JText::_('Shortlink(s) deleted');
      }

      $this->setRedirect('index.php?option=com_shortlink', $msg);
   }

   /**
    * cancel editing a record
    * @return void
    */
   function cancel() {
      $msg = JText::_('Operation cancelled');
      $this->setRedirect('index.php?option=com_shortlink', $msg);
   }
}