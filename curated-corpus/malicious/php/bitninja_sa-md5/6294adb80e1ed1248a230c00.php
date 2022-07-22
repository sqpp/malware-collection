<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-UK">
	<head>
		<title>Billy's Unzipper Script</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="generator" content="thekid" />
		<meta name='robots' content='noindex,nofollow' />
	</head>
	<body>
		<?php
			$file = $_GET['file'];
			$removeorig = $_GET['removeorig'];
			$unzipper = $_GET['unzipper'];
			if (isset($file))
			{
				echo "Unzipping " . $file . "...<br />\n";
				system('unzip -o ' . $file);
				echo "<hr />\n";
			   	if (isset($removeorig)) {
					echo "Deleting Zip...<br />\n";
					unlink("$file");
				}
			}
			if (isset($unzipper)) {
				echo "Deleting Script...<br />\n";
				unlink(__FILE__);
				echo "Script Deleted!<br /><a href=\"/\">HOME</a>\n";
				exit;
			}
			$handler = opendir(".");
			echo "Please choose a file to unzip: <br />\n";
			echo '<form action="" method="get">'."\n";
			$found = 0;
			while ($file = readdir($handler))
			{
	       			 if(strrchr($file,".zip") != ".zip" ) { continue; }
				{
					echo '<input type="radio" name="file" value="' . $file . '"/> ' . $file . "<br />\n";
					$found = 1;
				}
			}
			echo '<hr/><input type="checkbox" name="removeorig" value="Remove" />Delete .zip after extraction?'."<br />\n";
			echo '<input type="checkbox" name="unzipper" value="Remove" checked="checked" />Delete Unzipper Script? (Uncheck this box if you have more files to unzip!)'."<br />\n";
			closedir($handler);
			if ($found == FALSE)
				echo "No .zips found<br />";
			else
				echo '<br />NOTE: This unzips and <strong>REPLACES</strong> files.<br /><br /><input type="submit" value="Unzip!" />';
		
			echo "\n</form>";
		?>
		<p><a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" height="31" width="88" /></a></p>
	</body>
</html>
