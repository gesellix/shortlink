<?php
/**
 * Shortlink - A short to long links translator
 * @version 2.2
 * @copyright (C) 2004 by Tobias Gesellchen, www.gesellix.de - All rights reserved!
 * thanks to Daniel Janesch (admin@the-deejay.com, www.the-deejay.com)
 */

// default location
$link = "index.php?option=com_shortlink&phrase=".$_GET['link'];

// redirect to new location
header("Location: $link");

?>