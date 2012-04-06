<?php
/**
* Shortlink - A short to long links translator
* @version 1.3
* @copyright (C) 2004 by Tobias Gesellchen, www.gesellix.de - All rights reserved!
**/

# Don't allow direct access to the file
  defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

  global $database;

# Setup the module standard parameter settings
  $url          = sefRelToAbs("index.php");
  $text         = "shortlink...";
  $introduction = $params->get( 'introduction' );
  $showCbo      = $params->get( 'showcbo' );

#Bring it all to the screen
?>

  <!-- Shortlink - (C) 2004 by Tobias Gesellchen, www.gesellix.de -->
  <?php if ($introduction) echo "<p>$introduction</p>"; ?>

<?php
  if ($showCbo)
  {
    showDropdownBox($url, $database);
  }
  else
  {
    showInputfield($url, $text);
  }


# helper functions

function showInputfield($url, $text)
{
?>
  <form name="shortlinkinput" action="<?php echo $url; ?>" id="shortlinkInput" method="post">
    <input
      alt="shortlink input"
      type="text"
      name="phrase"
      value="<?php echo $text; ?>"
      class="inputbox"
      accesskey="l"
      id="shortlinkInput"
      onblur="if(this.value=='') this.value='<?php echo $text; ?>';"
      onfocus="if(this.value=='<?php echo $text; ?>') this.value='';"
    />
    <input type="hidden" name="option" value="com_shortlink" />
  </form>
<?php
}

function showDropdownBox($url, $database)
{
  $database->setQuery("SELECT phrase FROM #__shortlink");

  $phrases = $database->loadResultArray();
  if (count($phrases) > 0 ) // found something?
  {
?>
    <form name="shortlinkCbo" action="<?php echo $url; ?>" id="shortlinkCbo" method="get">
    <select name="phrase" class="inputbox" onchange="document.shortlinkCbo.submit()">
<?php
      echo "<option value=\"dummy\"></option>\n";
    sort($phrases);
    foreach ($phrases as $key => $phrase)
    {
      echo "<option value=\"".$phrase."\">".$phrase."</option>\n";
    }
?>
    </select>
    <input type="hidden" name="option" value="com_shortlink" />
    </form>
<?php
  }
}
?>