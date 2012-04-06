<?php defined('_JEXEC') or die('Restricted access'); // no direct access ?>

<!-- Shortlink - &copy; 2009 by Tobias Gesellchen, www.gesellix.de -->
<?php
 if ($introduction) echo "<p>".$introduction."</p>";
?>

<form name="shortlinkinput" action="<?php echo $url; ?>" id="shortlinkInput" method="post">
<input
  type="text"
  id="shortlinkDynabox"
  class="combobox"
  name="phrase"
  value="<?php echo $text; ?>"
  onblur="if(this.value=='') this.value='<?php echo $text; ?>';"
  onfocus="if(this.value=='<?php echo $text; ?>') this.value='';"
  />
<ul id="combobox-shortlinkDynabox" style="display:none;">
<?php
	echo "<li></li>\n";
	foreach ($shortlinks as $key => $shortlink)
	{
		echo '<li>'.$shortlink->phrase.'</li>\n';
	}
?>
</ul>

	<script language="javascript" type="text/javascript">
		window.addEvent('domready', function() {
			$('combobox-shortlinkDynabox-select').addEvent('change', function() {
				document.shortlinkinput.submit();
			});

			/* don't submit on change/focus lost. The user has to press enter instead.
			$('shortlinkDynabox').addEvent('change', function() {
				document.shortlinkinput.submit();
			});
			*/
		});
	</script>
</form>
