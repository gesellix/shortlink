<?php

// default location
$link = "index.php?option=com_shortlink&phrase=".$_GET['link'];

// redirect to new location
header("Location: $link");

?>