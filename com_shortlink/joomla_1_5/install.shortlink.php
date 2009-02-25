<?php

defined('_JEXEC') or die( 'Restricted access' );

function com_install()
{
  $database = &JFactory::getDBO();

  $source_file = JPATH_SITE.DS.'components'.DS.'com_shortlink'.DS.'goto.php';
  $target_file = JPATH_SITE.DS.'goto.php';

  installLinkHandler($source_file, $target_file);
  initDB($database);

  echo "<p>Shortlink successfully installed!</p>\n";
?>

  <p>Shortlink is free for non-commercial use.</p>
  <p>For more information, please contact <a href="http://www.gesellix.de/" title="www.gesellix.de" target="_blank">www.gesellix.de</a>.</p>
  <p>Take me to the <a href="index.php?option=com_shortlink">Shortlink Control Panel</a> now.</p>
<?php

  return true;
}

function initDB($database)
{
  $myTable = "#__shortlink";

/*
  this release needs a fresh installation, so no update mechanism necessary
  $tableArray[] = $myTable;
  $tableFields = $database->getTableFields($tableArray);
  
  if (!findField("description", $tableFields[$myTable]))
  {
    $database->setQuery("ALTER TABLE `".$myTable."` ADD COLUMN `description` varchar(255) NOT NULL default ''");
    $database->query();
  }
*/

  $database->setQuery("INSERT IGNORE INTO `".$myTable."` VALUES ('', 'gesellix', 'http://www.gesellix.de/', '_blank', 0, 'Tobias Gesellchen', NOW(), '0000-00-00 00:00:00')");
  $database->query();
  $database->setQuery("INSERT IGNORE INTO `".$myTable."` VALUES ('', 'deejay_', 'http://www.the-deejay.com/', '_blank', 0, 'Daniel Janesch', NOW(), '0000-00-00 00:00:00')");
  $database->query();
}

function findField($fieldName, $fields)
{
  foreach ($fields as $field => $fieldType)
  {
    if ($field == $fieldName)
    {
      return true;
    }
  }

  return false;
}

function installLinkHandler($source_file, $target_file)
{
  // remove, if already there.
  if (file_exists($target_file))
  {
  	unlink($target_file);
  }

  if (!rename($source_file, $target_file))
  {
  	if (!file_exists($target_file))
  	{
	    echo "<p>FAILED to move <br />$source_file to <br />$target_file...<br />\n";
	    echo "<strong>please do this step manually to make the component work properly!</strong></p>\n";
	    return false;
    }
  }

  return true;
}
?>