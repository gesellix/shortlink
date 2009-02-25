<?php
/**
 * Shortlink - A short to long links translator
 * @version 2.2
 * @copyright (C) 2004 by Tobias Gesellchen, www.gesellix.de - All rights reserved!
 * thanks to Daniel Janesch (admin@the-deejay.com, www.the-deejay.com)
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

function com_install()
{
  global $mosConfig_absolute_path, $database;

  $source_file = $mosConfig_absolute_path.'/components/com_shortlink/goto.php';
  $target_file = $mosConfig_absolute_path.'/goto.php';

  installLinkHandler($source_file, $target_file);
  initDB($database);

  echo "<p>Shortlink successfully installed!</p>\n";
?>

  <p>Shortlink is free for non-commercial use.</p>
  <p>For more information, please contact <a href="http://www.gesellix.de/" title="www.gesellix.de" target="_blank">www.gesellix.de</a>.</p>
  <p>Take me to the <a href="index2.php?option=com_shortlink">Shortlink Control Panel</a> now.</p>
<?php

  return true;
}

function initDB($database)
{
  $myTable = "#__shortlink";
  
  $tableArray[] = $myTable;
  $tableFields = $database->getTableFields($tableArray);
  
  if (!findField("description", $tableFields[$myTable]))
  {
    $database->setQuery("ALTER TABLE `".$myTable."` ADD COLUMN `description` varchar(255) NOT NULL default ''");
    $database->query();
  }
  if (!findField("create_date", $tableFields[$myTable]))
  {
    $database->setQuery("ALTER TABLE `".$myTable."` ADD COLUMN `create_date` datetime NOT NULL default '0000-00-00 00:00:00'");
    $database->query();
  }
  if (!findField("last_call", $tableFields[$myTable]))
  {
    $database->setQuery("ALTER TABLE `".$myTable."` ADD COLUMN `last_call` datetime NOT NULL default '0000-00-00 00:00:00'");
    $database->query();
  }

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

# function echoarray . echo an array in a structured way for debugging purposes.
/*
function echoarray($a)
{
  if (is_array($a))
  {
    foreach ($a as $key => $value)
    {
      echo "<ul><li>$key: ";
      if (is_array($value))
        echoarray($value);
      else
        echo htmlentities($value)."</li>";
      echo "</ul>";
    }
  }
}
*/

function installLinkHandler($source_file, $target_file)
{
  if (!rename($source_file, $target_file))
  {
    echo "<p>FAILED to move $source_file to $target_file...<br />\n";
    echo "<strong>please do this step manually to make the component work properly!</strong></p>\n";
    return false;
  }

  return true;
}
?>