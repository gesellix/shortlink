<?php defined('_JEXEC') or die('Restricted access'); // no direct access ?>

<!-- Shortlink - &copy; 2009 by Tobias Gesellchen, www.gesellix.de -->
<?php if ($introduction) echo "<p>".$introduction."</p>"; ?>

<form name="shortlinkCbo" action="<?php echo $url; ?>" id="shortlinkCbo" method="get">
	<select name="phrase" class="inputbox" onchange="document.shortlinkCbo.submit()">
	<?php
		echo '<option value="dummy"></option>\n';
		foreach ($shortlinks as $key => $shortlink)
		{
			echo '<option value="'.$shortlink->phrase.'">'.$shortlink->phrase.'</option>\n';
		}
	?>
	</select>
	<input type="hidden" name="option" value="com_shortlink" />
</form>
