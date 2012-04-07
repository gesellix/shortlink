<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JForm::addFieldPath(JPATH_COMPONENT . DS . 'elements');

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('text');

class JFormFieldCheckconfigbutton extends JFormFieldText {

   public $type = 'Checkconfigbutton';

   protected function getInput() {
      $default_path = JPATH_SITE . DS . 'goto.php';

      $params = &JComponentHelper::getParams('com_shortlink');
      $source_path = $params->def('helper_path', $default_path);
      if ($source_path == 'goto.php') {
         $source_path = $default_path;
      }

      if ($this->value == 'goto.php') {
         $this->value = $default_path;
      }

      $fieldCurrentHelperPath = JFormHelper::loadFieldType('text');
      $fieldCurrentHelperPath->name = 'CurrentHelperPath';
      $fieldCurrentHelperPath->id = 'CurrentHelperPath';
      $fieldCurrentHelperPath->value = $source_path;
      $fieldCurrentHelperPath->element['size'] = $this->element['size'];

      $js = "<script>
         window.addEvent('domready', function () {
            $('btnChangePath').addEvent('click', function (e) {
               e = new Event(e).stop();

               var currentPath = document.getElementById('CurrentHelperPath');
               var newPath = document.getElementById('".$this->id."');
               var workingElem = document.getElementById('lblWorking');

               window.parent.onMoveHelperFile(currentPath, newPath, workingElem);
            });
         });</script>";

      $btn = "<a id=\"btnChangePath\" href=\"#\">" . JText::_('MOVE_FILE_TO_NEW_LOCATION_NOW') . "</a>";

      $lbl_working = '<label id="lblWorking"></label>';

      $result = "<div>";
      $result .= "<br/>". "<label for=\"".$fieldCurrentHelperPath->id."\">". JText::_('CURRENT_LOCATION'). "</label>".$fieldCurrentHelperPath->getInput();
      $result .= "<br/>". "<label for=\"".$this->id."\">". JText::_('NEW_LOCATION'). "</label>". parent::getInput();
      $result .= "<br/>". "<label for=\"btnChangePath\">".$btn ."</label>". $lbl_working;
      $result .= "</div>";
      $result .= $js;

      return $result;
   }
}