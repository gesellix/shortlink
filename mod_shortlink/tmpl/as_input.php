<?php defined('_JEXEC') or die('Restricted access'); // no direct access ?>

<!-- Shortlink - &copy; 2009 by Tobias Gesellchen, www.gesellix.de -->
<?php if ($introduction) echo "<p>".$introduction."</p>"; ?>

<form name="shortlinkinput" action="<?php echo $url; ?>" id="shortlinkInput" method="post">
<input
  alt="shortlink input"
  type="text"
  name="phrase"
  value="<?php echo $text; ?>"
  class="inputbox"
  id="shortlinkInput"
  onblur="if(this.value=='') this.value='<?php echo $text; ?>';"
  onfocus="if(this.value=='<?php echo $text; ?>') this.value='';"
/>
<input type="hidden" name="option" value="com_shortlink" />
</form>
