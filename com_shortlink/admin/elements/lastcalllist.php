<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JForm::addFieldPath(JPATH_COMPONENT . DS . 'elements');

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_shortlink' . DS . 'models' . DS . 'filter.php');

class JFormFieldLastcalllist extends JFormFieldList {

   public $type = 'Lastcalllist';

   protected function getOptions() {
      $texts = ShortlinksFilter::getLastCallSelections();

      $options = array();
      foreach ($texts as $val => $text) {
         $options[] = JHtml::_('select.option', $val, JText::_($text));
      }

      return $options;
   }
}