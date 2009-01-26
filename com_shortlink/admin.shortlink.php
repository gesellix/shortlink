<?php
/**
 * Shortlink - A short to long links translator
 * @version 2.2
 * @copyright (C) 2004 by Tobias Gesellchen, www.gesellix.de - All rights reserved!
 * thanks to Daniel Janesch (admin@the-deejay.com, www.the-deejay.com)
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// ensure user has access to this function
if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
    | $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_shortlink' )))
{
  mosRedirect( 'index2.php', _NOT_AUTH );
}

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );

$aid = mosGetParam( $_POST, 'aid', array(0) );
if (!is_array( $aid ))
{
  $aid = array(0);
}

$config_file = "components".DIRECTORY_SEPARATOR.$option.DIRECTORY_SEPARATOR."shortlink_config.php";
include_once($config_file);

switch ($task)
{
  case "config":
    HTML_shortlink::showConfig($option, _SHORTLINK_CONF_URLPATH, _SHORTLINK_CONF_FILENAME, _SHORTLINK_CONF_PARAMNAME);
    break;

  case "missingconfig":
    missConfig($config_file);
    HTML_shortlink::showConfig($option, _SHORTLINK_CONF_URLPATH, _SHORTLINK_CONF_FILENAME, _SHORTLINK_CONF_PARAMNAME);
    break;

  case "saveconfig":
    saveConfig ($config_file, $option);
    break;

  case "cancelconfig":
    mosRedirect("index2.php?option=$option", '');
    break;

  case "new":
    editShortlink( '0', $option);
    break;

  case "edit":
    editShortlink( $aid[0], $option );
    break;

  case "save":
    saveShortlink( $option );
    break;

  case "remove":
    removeShortlink( $aid, $option );
    break;
/*
  case "addLinks";
    shortlink("add", $option);
    break;

  case "delLinks";
    shortlink("clean", $option);
    break;
*/

  case "uninstall":
    HTML_shortlink::showUninstallInfo();
    break;

  case "uninstalltask":
    HTML_shortlink::doUninstall();
    break;

  default:
    showShortlink( $option );
    break;
}

/**
* List the records
* @param string The current GET/POST option
*/
function showShortlink( $option )
{
  global $database, $mainframe;

//  $limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
  $limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', 50 );
  $limitstart = $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );
  $search = $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
  $search = $database->getEscaped( trim( strtolower( $search ) ) );
  $period = $mainframe->getUserStateFromRequest( "period", 'period', -1 );

  $where = "";
  if ($period > -1)
    $where = " WHERE last_call > '".date ("Y-m-d H:i:s", time() - ($period * 24 * 60 * 60))."'";
  if ($period == -2)
    $where = " WHERE last_call = '0000-00-00 00:00:00'";
  $where = $search ? " WHERE phrase LIKE '%$search%'" : $where;

  // get the total number of records
  $querycnt = "SELECT COUNT(*) FROM #__shortlink $where";
  $database->setQuery($querycnt);
  $total = $database->loadResult();

  require_once( "includes/pageNavigation.php" );
  $pageNav = new mosPageNav( $total, $limitstart, $limit  );
  $quer = "SELECT * FROM #__shortlink $where ORDER BY last_call DESC LIMIT $pageNav->limitstart, $pageNav->limit";
  $database->setQuery($quer);
  $rows = $database->loadObjectList();
  HTML_shortlink::showShortlink( $rows, $pageNav, $search, $option );
}

/**
 * Creates a new or edits and existing user record
 * @param int The id of the record, 0 if a new entry
 * @param string The current GET/POST option
 */
function editShortlink($id, $option )
{
  global $database, $my;
  global $mosConfig_absolute_path;
  $row = new mosShortlink( $database );
  $row->load( $id );
  if ($id)
  {
    // do stuff for existing records
  }
  else
  {
    // do stuff for new records
    $row->target = '';
    $row->counter = 0;
  }

  $targets[] = mosHTML::makeOption('', 'Same window');
  $targets[] = mosHTML::makeOption('_blank', 'New window');
  $targetslist = mosHTML::selectList( $targets, 'target', 'class="inputbox" size="1"', 'value', 'text', $row->target );

  $internals[] = mosHTML::makeOption(0, '- Choose article -');
  $database->setQuery("SELECT id, title FROM #__content WHERE state='1' ORDER BY title");
  $artrows = $database->loadObjectList();
  foreach ($artrows as $artrow)
  {
    $internals[] = mosHTML::makeOption(intval($artrow->id), $artrow->title);
  }
  $internalnumber = intval($row->link);
  $internallinkslist = mosHTML::selectList($internals, 'internallink', 'class="inputbox" size="1" onChange="document.adminForm.link.value=\'\';"', 'value', 'text', $internalnumber);

  HTML_Shortlink::editShortlink($row, $targetslist, $internallinkslist, $option);
}

/**
 * Saves the shortlink record to the database from an edit or new form submit
 * @param string The current GET/POST option
 */
function saveShortlink( $option )
{
  global $database, $my;
  if ($_POST[link] == "")
    $_POST[link] = $_POST[internallink];

  $row = new mosShortlink( $database );
  $row->counter = 0;
  $row->create_date = date("Y-m-d H:i:s");
  $row->last_call = "0000-00-00 00:00:00";
  if (!$row->bind( $_POST ))
  {
    echo "<script> alert('bind: ".$row->getError()."'); window.history.go(-1); </script>\n";
    exit();
  }

  // pre-save checks
  if (!$row->check())
  {
    echo "<script> alert('check: ".$row->getError()."'); window.history.go(-1); </script>\n";
    exit();
  }

  // save the changes
  if (!$row->store())
  {
    echo "<script> alert('store: ".$row->getError()."'); window.history.go(-1); </script>\n";
    exit();
  }

  $row->checkin();
  mosRedirect( "index2.php?option=$option" );
}

/**
 * Removes shortlink records from the database
 * @param array An array of id keys to remove
 * @param string The current GET/POST option
 */
function removeShortlink( &$aid, $option )
{
  global $database;
  if (count( $aid ))
  {
    $aids = implode( ',', $aid );
    $database->setQuery( "DELETE FROM #__shortlink WHERE id IN ($aids)" );
    if (!$database->query())
    {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
    }
  }
  mosRedirect( "index2.php?option=$option" );
}

/**
 * Removes shortlinks
 * @param string A string to clean (by reference)
 */
function stripShortlink(&$text2clean)
{
  while (preg_match("{<a id=['\"]auto(.*)>(.*)</a>}i", $text2clean))
  {
    $text2clean = preg_replace("{(.*)<a id=['\"]auto(.*)>(.*)</a>(.*)}i", "$1$3$4", $text2clean);
  }
}

/**
 * Adds autolinks to a piece of html
 * @param array An array with the phrase, link and target of the autolink to add
 * @param string The string to add the autolinks to (by reference)
 */
function addShortlink(&$shortlink, &$text2add)
{
  global $shortlinkcounter;
  $phrase = $shortlink[phrase];
  $link = $shortlink[link];
  $target = $shortlink[target];
  if ($target != "")
    $target = " target=\"$target\"";
  if (intval($link))
    $link = "index.php?option=content&task=view&id=$link";
  $html = new HTML_Object($text2add);
  $html->parseHTML();
  foreach ($html->elems as $j=>$elem)
  {
    // only if elems[$j] is not already surrounded by an A tag, will we do something
    if (!$html->surroundedBy($j, "A"))
    {
      $textarray = explode($phrase, $elem[content]);

      // only if size is > 1, the phrase was found and we have work to do
      if (count($textarray) > 1)
      {
        $newtext = "";
        foreach ($textarray as $k=>$origtext)
        {
          // add the link, but not after the last piece of content
          if (isset($textarray[$k+1]))
          {
            // create id-attribute for shortlink $shortlinkcounter will make sure that the ID tags are unique
            $idattr = "auto-".str_replace("+","-",urlencode($phrase))."-$shortlinkcounter";
            $newtext .= $origtext."<a id=\"$idattr\" href=\"$link\"$target>$phrase</a>";
            $shortlinkcounter++;
            $shortlink[counter]++;
          }
          else
          {
            $newtext .= $origtext;
          }
        }
        $html->elems[$j][content] = $newtext;
      }
    }
    $text2add = $html->getHTML();
  }
}

/**
 * Helper function (for usort()) to sort the array of phrase objects by string length of the phrase
 * @param array Compare array a
 * @param array Compare array b
 */
function phraseSort($a, $b)
{
  if (($a == $b) || (strlen($a[phrase]) == strlen($b[phrase])))
  {
    return 0;
  }
  return (strlen($a[phrase]) > strlen($b[phrase])) ? -1 : 1;
}

/**
 * Clean old shortlinks from content and optionally add them again
 * @param string Set to 'add' explicitly to add autolinks to all content. Otherwise, shortlinks will only be removed.
 * @param string The current GET/POST option
 */
function shortlink($cleanshortlink, $option)
{
  global $database, $shortlinkcounter;
  $shortlinkcounter = 1;
  if ($cleanshortlink == "add")
    $cleanshortlink = 0;
  else
    $cleanshortlink = 1;
  $database->setQuery("SELECT * FROM #__shortlink");
  $rows = $database->loadObjectList();
  $i = 0;
  foreach ($rows as $row)
  {
    $phrases = explode(";",$row->phrase);
    foreach ($phrases as $phrase)
    {
      // trimming or not is a matter of taste. if not, it's easier to avoid links to mid-word phrases by
      // specifying " phrase " and or "phrase." etc as the phrase. without trimming, all words that can
      // contain the smaller phrase mid-word should be shortlinked to somewhere themselves....
      //$phrase = trim($phrase);
      $shortlink[$i][id] = $row->id;
      $shortlink[$i][phrase] = $phrase;
      $shortlink[$i][link] = $row->link;
      $shortlink[$i][target] = $row->target;
      $shortlink[$i][counter] = $row->counter;
      $shortlink[$i][description] = $row->description;
      $shortlink[$i][create_date] = $row->create_date;
      $shortlink[$i][last_call] = $row->last_call;
      $i++;
    }
  }

  // sort the phrases. longest first. that way long phrases will always overrule shorter sub-phrases
  usort($shortlink, 'phraseSort');

  // vicious: fulltext is a reserved word in mysql... so we prefix it with mos_content.
  $database->setQuery("SELECT id, introtext, #__content.fulltext FROM #__content");
  $rows = $database->loadObjectList();
  foreach ($rows as $row)
  {
    $id = $row->id;
    stripShortlink($row->introtext);
    stripShortlink($row->fulltext);

    // set $cleanshortlink to just strip out all autolinks and not add new ones
    if (!$cleanshortlink)
    {
      foreach ($shortlink as $key=>$shortlink)
      {
        // Don't link articles to themselves
        if ($id != $shortlink[link])
        {
          addShortlink($shortlink, $row->introtext);
          addShortlink($shortlink, $row->fulltext);
          $shortlink[$key][counter] = $shortlink[counter];
        }
      }
    }

    // vicious: fulltext is a reserved word in mysql... so we prefix it with mos_content.
    $database->setQuery("UPDATE #__content SET introtext='".addslashes($row->introtext)."', #__content.fulltext='".addslashes($row->fulltext)."' where id=$id");
    $database->query();
  }

  foreach ($shortlink as $key=>$shortlink)
  {
    $database->setQuery("UPDATE #__shortlink SET counter=$shortlink[counter] where id=$shortlink[id]");
    $database->query();
  }

  mosRedirect( "index2.php?option=$option" );
}

function saveConfig ($config_file, $option)
{
  //Options
  $conf_urlpath = trim( strtolower( mosGetParam( $_POST, 'conf_urlpath', '' ) ) );
  $conf_filename = trim( strtolower( mosGetParam( $_POST, 'conf_filename', '' ) ) );
  $conf_paramname = trim( strtolower( mosGetParam( $_POST, 'conf_paramname', '' ) ) );

  clearstatcache();
  @chmod ($config_file, 0766);

  $configpermission = is_writable($config_file);
  if (!$configpermission)
  {
    mosRedirect("index2.php?option=$option&task=missingconfig");
    break;
  }

  $configtxt = "<?php\n\n";
  $configtxt .= "/**\n";
  $configtxt .= "* Shortlink - A short to long links translator\n";
  $configtxt .= "* @version 2.2\n";
  $configtxt .= "* @copyright (C) 2004 by Tobias Gesellchen, www.gesellix.de - All rights reserved!\n";
  $configtxt .= "* thanks to Daniel Janesch (admin@the-deejay.com, www.the-deejay.com)\n";
  $configtxt .= "*/\n\n";
  $configtxt .= "defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );\n\n";
  $configtxt .= "DEFINE(\"_SHORTLINK_CONF_URLPATH\", \"".$conf_urlpath."\");\n";
  $configtxt .= "DEFINE(\"_SHORTLINK_CONF_FILENAME\", \"".$conf_filename."\");\n";
  $configtxt .= "DEFINE(\"_SHORTLINK_CONF_PARAMNAME\", \"".$conf_paramname."\");\n";
  $configtxt .= "?>";

  if ($fp = fopen("$config_file", "w+"))
  {
    fputs($fp, $configtxt, strlen($configtxt));
    fclose($fp);
  }
  $mosmsg = "Config is saved !";
  mosRedirect("index2.php?option=$option&task=config", $mosmsg);
}

function missConfig($config_file)
{
  echo "<center><h1><font color=red>Warning...</font></h1><br />";
  echo "<strong>You need to chmod config file '".dirname(__FILE__)."/".basename($config_file)."' to 766 in order for the config to be updated</strong></center><br /><br />";
}
?>