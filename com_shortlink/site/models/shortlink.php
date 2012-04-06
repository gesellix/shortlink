<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

class ShortlinkModelShortlink extends JModel {
   function getShortlink($phrase) {
      $db =& JFactory::getDBO();

      $query = 'SELECT * FROM #__shortlink';
      $query .= ' WHERE phrase = ' . $db->quote($db->getEscaped($phrase), false);

      $db->setQuery($query);
      $shortlink = $db->loadObject();

      if ($shortlink) {
         // update statistics
         $now =& JFactory::getDate();

         $shortlink->counter++;
         $shortlink->last_call = $now->toMySQL();
         $db->updateObject('#__shortlink', $shortlink, 'id', true);
      }

      return $shortlink;
   }

   function getArticleUrl($artid) {
      $db =& JFactory::getDBO();
      $mainframe = &JFactory::getApplication();

      if (!class_exists('ContentHelperRoute')) {
         require_once(JPATH_SITE . DS . 'components' . DS . 'com_content' . DS . 'helpers' . DS . 'route.php');
      }

      $query = "SELECT a.id, a.title AS arttitle, a.alias AS artalias, c.id as catid, c.alias AS catalias, a.sectionid";
      $query .= " FROM #__content AS a ";
      $query .= " LEFT JOIN #__categories AS c ON a.catid = c.id ";
      $query .= " LEFT JOIN #__sections AS s ON a.sectionid = s.id ";
      $query .= " WHERE a.state=1 ";
      $query .= " AND a.id=" . (int)$artid;

      $db->setQuery($query);
      $article = $db->loadObject();

      $result = null;

      if ($article) {
         if (empty($article->catid) || empty($article->catalias) || empty($article->sectionid)) {
            $result = ContentHelperRoute::getArticleRoute($article->id . ':' . $article->artalias);
         }
         else {
            $result = ContentHelperRoute::getArticleRoute($article->id . ':' . $article->artalias, $article->catid . ':' . $article->catalias, $article->sectionid);
         }

         if (strpos($result, '&Itemid=') < 0) {
            $itemid = $mainframe->getItemid($article->id);
            if ($itemid) {
               $result .= "&Itemid=" . $itemid;
            }
         }
      }

      return $result;
   }
}

?>