<?php
/**
 * Shortlink - A short to long links translator
 * @version 2.2
 * @copyright (C) 2004 by Tobias Gesellchen, www.gesellix.de - All rights reserved!
 * thanks to Daniel Janesch (admin@the-deejay.com, www.the-deejay.com)
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class HTML_shortlink
{
  function getTodayYesterdayDate ($date)
  {
    $time_string = "not yet";
    if ($date != "0000-00-00 00:00:00")
    {
      $time = strtotime($date);
      $time_string = date("Y-m-d", $time);
      $today = mktime();
      $today_begin = mktime(0, 0, 0, date("n", $today), date("j", $today), date("Y", $today));
      $yesterday_begin = mktime(0, 0, 0, date("n", $today), date("j", $today)-1, date("Y", $today));
      $daybeforeyesterday_begin = mktime(0, 0, 0, date("n", $today), date("j", $today)-2, date("Y", $today));
      if ($today_begin < $time)
        $time_string = "today";
      else if ($yesterday_begin < $time)
        $time_string = "yesterday";
//      else if ($daybeforeyesterday_begin < $time)
//        $time_string = "day before yesterday";
    }

    return $time_string;
  }

  function showShortlink( &$rows, &$pageNav, $search, $option )
  {
    global $database, $mainframe;

    $database->setQuery("SELECT id, title FROM #__content ORDER BY title");
    $artrows = $database->loadObjectList();
    foreach ($artrows as $artrow)
    {
      $articles[intval($artrow->id)] = $artrow->title;
    }

    $period = $mainframe->getUserStateFromRequest( "period", 'period', -1 );
    $one_day = 60 * 60 * 24;

    $database->setQuery("SELECT COUNT(*) FROM #__shortlink ");
    $total = $database->loadResult();
    $database->setQuery("SELECT COUNT(*) FROM #__shortlink WHERE last_call = '0000-00-00 00:00:00'");
    $no_call = $database->loadResult();
    $database->setQuery("SELECT COUNT(*) FROM #__shortlink WHERE last_call > '".date("Y-m-d H:i:s", time() - $one_day * 365)."'");
    $last_year = $database->loadResult();
    $database->setQuery("SELECT COUNT(*) FROM #__shortlink WHERE last_call > '".date("Y-m-d H:i:s", time() - $one_day * 183)."'");
    $last_6months = $database->loadResult();
    $database->setQuery("SELECT COUNT(*) FROM #__shortlink WHERE last_call > '".date("Y-m-d H:i:s", time() - $one_day * 152)."'");
    $last_5months = $database->loadResult();
    $database->setQuery("SELECT COUNT(*) FROM #__shortlink WHERE last_call > '".date("Y-m-d H:i:s", time() - $one_day * 122)."'");
    $last_4months = $database->loadResult();
    $database->setQuery("SELECT COUNT(*) FROM #__shortlink WHERE last_call > '".date("Y-m-d H:i:s", time() - $one_day * 91)."'");
    $last_3months = $database->loadResult();
    $database->setQuery("SELECT COUNT(*) FROM #__shortlink WHERE last_call > '".date("Y-m-d H:i:s", time() - $one_day * 61)."'");
    $last_2months = $database->loadResult();
    $database->setQuery("SELECT COUNT(*) FROM #__shortlink WHERE last_call > '".date("Y-m-d H:i:s", time() - $one_day * 31)."'");
    $last_month = $database->loadResult();
    $database->setQuery("SELECT COUNT(*) FROM #__shortlink WHERE last_call > '".date("Y-m-d H:i:s", time() - $one_day * 28)."'");
    $last_4weeks = $database->loadResult();
    $database->setQuery("SELECT COUNT(*) FROM #__shortlink WHERE last_call > '".date("Y-m-d H:i:s", time() - $one_day * 21)."'");
    $last_3weeks = $database->loadResult();
    $database->setQuery("SELECT COUNT(*) FROM #__shortlink WHERE last_call > '".date("Y-m-d H:i:s", time() - $one_day * 14)."'");
    $last_2weeks = $database->loadResult();
    $database->setQuery("SELECT COUNT(*) FROM #__shortlink WHERE last_call > '".date("Y-m-d H:i:s", time() - $one_day * 7)."'");
    $last_week = $database->loadResult();
?>

<form action="index2.php" method="post" name="adminForm">
  <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="100%" class="sectionname">Shortlink Manager</td>
      <td nowrap="nowrap">Display #</td>
      <td><?php echo $pageNav->getLimitBox(); ?></td>
      <td>Search:</td>
      <td><input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" /></td>
      <td>
        <select name="period" class="inputbox" size="1" onchange="document.adminForm.submit();">
          <option value="-1" <?php echo ($period == -1) ? "selected=\"selected\"" : ""; ?>>- show all (<?php echo $total; ?>)</option>
          <option value="-2" <?php echo ($period == -2) ? "selected=\"selected\"" : ""; ?>>- never called (<?php echo $no_call; ?>/<?php echo $total; ?>)</option>
          <option value="7" <?php echo ($period == 7) ? "selected=\"selected\"" : ""; ?>>last call within last week (<?php echo $last_week; ?>/<?php echo $total; ?>)</option>
          <option value="14" <?php echo ($period == 14) ? "selected=\"selected\"" : ""; ?>>last call within last 2 weeks (<?php echo $last_2weeks; ?>/<?php echo $total; ?>)</option>
          <option value="21" <?php echo ($period == 21) ? "selected=\"selected\"" : ""; ?>>last call within last 3 weeks (<?php echo $last_3weeks; ?>/<?php echo $total; ?>)</option>
          <option value="28" <?php echo ($period == 28) ? "selected=\"selected\"" : ""; ?>>last call within last 4 weeks (<?php echo $last_4weeks; ?>/<?php echo $total; ?>)</option>
          <option value="31" <?php echo ($period == 31) ? "selected=\"selected\"" : ""; ?>>last call within last month (<?php echo $last_month; ?>/<?php echo $total; ?>)</option>
          <option value="61" <?php echo ($period == 61) ? "selected=\"selected\"" : ""; ?>>last call within last 2 months (<?php echo $last_2months; ?>/<?php echo $total; ?>)</option>
          <option value="91" <?php echo ($period == 91) ? "selected=\"selected\"" : ""; ?>>last call within last 3 months (<?php echo $last_3months; ?>/<?php echo $total; ?>)</option>
          <option value="122" <?php echo ($period == 122) ? "selected=\"selected\"" : ""; ?>>last call within last 4 months (<?php echo $last_4months; ?>/<?php echo $total; ?>)</option>
          <option value="152" <?php echo ($period == 152) ? "selected=\"selected\"" : ""; ?>>last call within last 5 months (<?php echo $last_5months; ?>/<?php echo $total; ?>)</option>
          <option value="183" <?php echo ($period == 183) ? "selected=\"selected\"" : ""; ?>>last call within last 6 months (<?php echo $last_6months; ?>/<?php echo $total; ?>)</option>
          <option value="365" <?php echo ($period == 365) ? "selected=\"selected\"" : ""; ?>>last call within last year (<?php echo $last_year; ?>/<?php echo $total; ?>)</option>
        </select>
      </td>
    </tr>
  </table>
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
    <tr>
      <th width="20px" class="title" nowrap="nowrap">#</th>
      <th width="20px" class="title" nowrap="nowrap"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
      <th width="100px" class="title" nowrap="nowrap">Phrase</th>
      <th class="title" nowrap="nowrap">Description</th>
      <th width="400px" class="title" nowrap="nowrap">Link</th>
<!-- <th width="80px" class="title" nowrap="nowrap">Opens in</th> -->
      <th width="60px" class="title" nowrap="nowrap">Created</th>
      <th width="55px" class="title" nowrap="nowrap">Last Call</th>
      <th width="50px" class="title" nowrap="nowrap">&Oslash; Hits</th>
      <th width="50px" class="title" nowrap="nowrap"># Hits</th>
    </tr>
<?php
    $k = 0;
    for ($i=0, $n=count($rows); $i < $n; $i++)
    {
      $row = $rows[$i];
      $link = $row->link;
      $alt = $row->link;
      if (intval($row->link))
      {
        $link = $articles[intval($row->link)];
        $alt = $link;
      }
      if (strlen($link) > 85)
      {
        $link = substr($link, 0, 82)."...";
      }
?>
    <tr class="<?php echo "row$k"; ?>">
      <td width="20px"><?php echo $i+1+$pageNav->limitstart; ?></td>
      <td width="20px"><input type="checkbox" id="cb<?php echo $i;?>" name="aid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
      <td width="100px"><a href="#edit" onclick="return listItemTask('cb<?php echo $i;?>','edit')"><?php echo $row->phrase; ?></a></td>
      <td class="title" nowrap="nowrap"><?php echo $row->description;?></td>
      <td width="480px" title="<?php echo $alt; ?>" alt="<?php echo $alt; ?>"><?php echo $link; ?></td>
<!-- <td width="20%"><?php if ($row->target == "_blank") echo "New window"; else echo "Same window";?></td> -->
      <td width="60px" align="right" class="title" nowrap="nowrap" title="<?php echo $row->create_date; ?>" name="<?php echo $row->create_date; ?>"><?php echo HTML_shortlink::getTodayYesterdayDate($row->create_date);?></td>
      <td width="60px" align="right" class="title" nowrap="nowrap" title="<?php echo $row->last_call; ?>" name="<?php echo $row->last_call; ?>"><?php echo HTML_shortlink::getTodayYesterdayDate($row->last_call);?></td>
<?php
      $create_date_time = strtotime(substr($row->create_date, 0, 10));
      $last_call_time = strtotime(substr($row->last_call, 0, 10));
      $call_days = round((($last_call_time - $create_date_time) / 86400) +1, 0);
      $call_daily = round ($row->counter / $call_days, 2);
      $call_daily = ($call_daily > 0) ? $call_daily." (".$call_days.")" : "";
?>
      <td width="50px" align="left" class="title" nowrap="nowrap" title="<?php echo $call_days; ?> day(s)" name="<?php echo $call_days; ?> day(s)"><?php echo $call_daily; ?></td>
      <td width="50px" align="left">
<?php
      if ($row->counter && !substr_count($row->phrase,";"))
      {
        $idtag = str_replace("+","-",urlencode($row->phrase));
        echo $row->counter;
//        echo "<a href=\"../index.php?option=search&searchword=$idtag\" target=\"_blank\">".$row->counter."</a>";
      }
      elseif (!$row->counter)
        echo $row->counter;
      else
        echo $row->counter."+";
?>    </td>
    </tr>
<?php
      $k = 1 - $k;
    }
?>
    <tr>
      <th align="center" colspan="10"> <?php echo $pageNav->writePagesLinks(); ?></th>
    </tr>
    <tr>
      <td align="center" colspan="10"> <?php echo $pageNav->writePagesCounter(); ?></td>
    </tr>
  </table>
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
</form>
<p>
<strong>
 Note, when the content bot <a href="http://forge.joomla.org/sf/frs/do/listReleases/projects.gesellix/frs.titlelink" title="TitleLink project site" target="_blank">TitleLink</a> is installed, in your content area you may link to shortlinks like this: {ln:&lt;phrase&gt;}
</strong>
<br /><br />
&copy; 2004 Tobias Gesellchen
</p>
<?php
  }

  function editShortlink( &$row, &$targetslist, &$internallinkslist, $option)
  {
    global $mosConfig_live_site;

    mosMakeHtmlSafe( $row, ENT_QUOTES, 'misc' );

    // http://www.yoursite.com/goto.php?link=
    $baseURL = strlen(_SHORTLINK_CONF_URLPATH) > 0 ? _SHORTLINK_CONF_URLPATH : $mosConfig_live_site;
    $fullshortLink = $baseURL;

    if ((strrpos($fullshortLink, "/") + 1) < strlen($fullshortLink))
      $fullshortLink .= "/";
    $fullshortLink .= _SHORTLINK_CONF_FILENAME."?"._SHORTLINK_CONF_PARAMNAME."=";
?>
    <script language="javascript" type="text/javascript">
      function submitbutton(pressbutton)
      {
        var form = document.adminForm;
        var ownUrl = "<?php echo $baseURL.'/'; ?>";

        if (pressbutton == 'cancel')
        {
          submitform( pressbutton );
          return;
        }

        // do field validation
        if (form.phrase.value == "")
        {
          alert( "You must fill in a phrase." );
        }
        else if ((form.internallink.value == "0") && (form.link.value == ""))
        {
          alert( "You must specify a link." );
        }
        else
        {
          if (form.link.value.indexOf(ownUrl) == 0)
            form.link.value = form.link.value.substring(ownUrl.length - 1);
          submitform( pressbutton );
        }
      }

      function createUserlink()
      {
        var phraseVal = document.forms["adminForm"].phrase.value;
        document.forms["adminForm"].user_link.value = "<?php echo $fullshortLink; ?>" + document.forms["adminForm"].phrase.value;
      }
    </script>
    <form action="index2.php" method="POST" name="adminForm">
      <table cellpadding="4" cellspacing="0" border="0" width="100%">
        <tr>
          <td width="100%"><span class="sectionname"><?php echo $row->id ? 'Edit' : 'Add';?> Shortlink</span></td>
        </tr>
      </table>
      <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
        <tr>
          <td width="20%" align="right">Phrase to shortlink:</td>
          <td width="20%"><input class="inputbox" type="text" name="phrase" size="50" maxlength="100" value="<?php echo $row->phrase; ?>" onchange="createUserlink();" />&nbsp;&nbsp;&nbsp;<a href="#" onclick="document.forms['adminForm'].phrase.value='<?php echo uniqid(""); ?>'; createUserlink();">Set Unique Phrase</a></td>
        </tr>
        <tr>
          <td align="right" valign="top">Description:</td>
          <td><input class="inputbox" type="text" name="description" size="50" maxlength="100" value="<?php echo $row->description; ?>" /></td>
        </tr>
        <tr>
          <td align="right" valign="top">Article to link to:</td>
          <td><?php echo $internallinkslist; ?></td>
        </tr>
        <tr>
          <td align="right" valign="top">Or type explicit link URL:</td>
          <td><input class="inputbox" type="text" name="link" size="50" maxlength="200" value="<?php if (!intval($row->link)) echo $row->link; ?>" onChange="document.adminForm.internallink.value=0;" /></td>
        </tr>
<!--
        <tr>
          <td align="right">Link will open in:</td>
          <td><?php echo $targetslist; ?></td>
        </tr>
-->
        <tr>
          <td>Link for User:</td>
          <td><input type="Text" readonly="" value="" name="user_link" size="76" maxlength="200">&nbsp;&nbsp;&nbsp;<a href="#" onclick="createUserlink();">Show Full URL</a></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
      </table>
      <input type="hidden" name="option" value="<?php echo $option; ?>" />
      <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
      <input type="hidden" name="last_call" value="<?php echo $row->last_call; ?>" />
      <input type="hidden" name="counter" value="<?php echo $row->counter; ?>" />
      <input type="hidden" name="create_date" value="<?php echo (($row->create_date) ? $row->create_date : date("Y-m-d H:i:s")) ?>" />
      <input type="hidden" name="task" value="" />
<p>
<strong>
 Note, when the content bot <a href="http://joomlacode.org/gf/project/titlelink/" title="TitleLink project site" target="_blank">TitleLink</a> is installed, in your content area you may link to shortlinks like this: {ln:&lt;phrase&gt;}
</strong>
<br /><br />
&copy; 2004 Tobias Gesellchen
</p>
<?php
  }

  function showConfig($option, $conf_urlpath, $conf_filename, $conf_paramname)
  {
?>
    <script language="Javascript" src="js/dhtml.js"></script>

    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#C0C0C0" width="90%">
      <tr>
        <td align="center" valign="bottom"><font size=6><b>Shortlink Config</b></font></td>
      </tr>
    </table>
    <br />

    <table cellspacing="0" cellpadding="4" border="0" width="100%">
      <tr>
        <td width="" class="tabpadding">&nbsp;</td>
        <td id="tab1" class="offtab" onClick="dhtml.cycleTab(this.id)">Config</td>
        <td width="90%" class="tabpadding">&nbsp;</td>
      </tr>
    </table>

    <form action="index2.php" method="POST" name="adminForm">
      <input type="hidden" name="task" value="saveconfig">
      <input type="hidden" name="option" value="<?php echo $option;?>" />
      <input type="hidden" name="conf_paramname" value="<?php echo $conf_paramname;?>">
      <div id="page1" class="pagetext">
        <table cellpadding="2" cellspacing="4" border="0" width="100%" class="adminform">
          <tr>
            <td colspan=2 class="sectionname" style="height:25pt;vertical-align:center;font-size:12pt;font-weight:bold">Shortlink Parameters</td>
          </tr>
          <tr>
            <td width="265">Url-Path (the url-path to goto.php)</td>
            <td><input type="text" name="conf_urlpath" size="50" maxlength="100" value="<?php echo $conf_urlpath;?>"></td>
          </tr>
          <tr>
            <td width="265">Filename (the filename of original called goto.php)</td>
            <td><input type="text" name="conf_filename" size="50" maxlength="100" value="<?php echo $conf_filename;?>"></td>
          </tr>
        </table>
      </div>
    </form>
    <script language="javascript" type="text/javascript">dhtml.cycleTab('tab1');</script>
    <br />
<?php
  }

  function showUninstallInfo()
  {
?>
    <table cellspacing="0" cellpadding="4" border="0" width="100%">
      <tbody>
      <tr>
        <td valign="top">If you want to fully uninstall the Shortlink component press the "delete tables" button in the toolbar.<br />
          This will delete the Shortlink tables in the Joomla/Mambo database.<br /><br />
          Then uninstall the Shortlink component in the Installer section.
        </td>
      </tr>
      </tbody>
    </table>
    <form name="adminForm" method="post" action="index2.php">
      <input type="hidden" name="option" value="com_shortlink">
      <input type="hidden" name="task" value="">
      <input type="hidden" name="boxchecked" value="0">
    </form>
<?php
  }

  function doUninstall()
  {
    global $database;

    $database->setQuery("DROP TABLE `#__shortlink`");
    $database->query();

?>
    <p>The Shortlink table has been deleted. Please <a href="index2.php?option=com_installer&element=component">uninstall</a> the component now.<br />Thanks for using it!</p>
<?php
  }
}
?>