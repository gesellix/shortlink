<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
class ModShortlinkHelper
{
    public function getItems()
    {
        $db = &JFactory::getDBO();
 
        $query = 'SELECT phrase FROM `#__shortlink`';
		
        $db->setQuery($query);
        $items = ($items = $db->loadObjectList())?$items:array();
 
		sort($items);

        return $items;
    }
}
?>