<?php 
session_start();
$backup_folder = "database_backups";
$backup_folder_path = "".$setup['path']."/".$setup['misc_folder']."/$backup_folder";
if(!is_dir($setup['path']."/".$setup['misc_folder']."/$backup_folder")) {
	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/".$setup['misc_folder'])), -4); 
	if($parent_permissions == "0755") {
		$perms = 0755;
	} elseif($parent_permissions == "0777") {
		$perms = 0777;
	} else {
			$perms = 0755;
	}
	mkdir("".$setup['path']."/".$setup['misc_folder']."/$backup_folder", $perms);
	chmod("".$setup['path']."/".$setup['misc_folder']."/$backup_folder", $perms);
	$fp = fopen($setup['path']."/".$setup['misc_folder']."/$backup_folder/index.php", "w");
	$info =  ""; 
	fputs($fp, "$info\n");
	fclose($fp);
}


if($_REQUEST['createBackup'] == "yes") {


	$dbhost = $setup['pc_db_location']; 
	$dbuser = $setup['pc_db_user']; 
	$dbpass = $setup['pc_db_pass'];
	$dbname = $setup['pc_db'];

	updateSQL("ms_history", "db_backup=NOW() ");

	 $backupFile = $dbname.'-'.date("Y-m-d-H-i-s") . '.sql';
	$command = "mysqldump --opt --host=$dbhost --user=$dbuser --password=$dbpass $dbname  > $backup_folder_path"."/"."$backupFile";
	system($command); 
	$_SESSION['sm'] = "Back up created";
	session_write_close();
	header("location: index.php?do=settings&action=dbBackup");
	exit();
}

if($_REQUEST['optimize'] == "yes") { 
	optimizeDatabaseTables();
	$_SESSION['sm'] = "Database Optimized";
	session_write_close();
	header("location: index.php?do=settings&action=dbBackup");
	exit();
}
if(!empty($_REQUEST['deleteFile'])) {
	deleteFile();
}

?>
<div id="pagetitle"><span class=head>Database Optimize & Backup</span> </div>


<div class="pc">
Your database stores all of your website information such as pages, settings, stats, products, etc... It does not contain actual files or folder.
<br><br>
It is  recommended that you optimize & backup your database at least weekly. To create a backup, click the create backup button below. This will create a sql file it will store in a backup file. Once it is created, you will see it listed below.
<br><br>
</div>

<?php if($setup['demo_mode'] == "true") { ?>
<div class="pc center">This function is disabled for the demo.</div>
<?php } else { ?>
<div class="pc left">
<form method=post name=bddu action=index.php>
<input type="hidden" name="createBackup" value="yes">
<input type="hidden" name="do" value="settings">
<input type="hidden" name="action" value="dbBackup">
<input type="submit" name="submit" value="Create Backup Now" id="submitButton" class="submit">
</form>
</div>
<div class="pc left">
<form method="get" name="opt" action=index.php>
<input type="hidden" name="optimize" value="yes">
<input type="hidden" name="do" value="settings">
<input type="hidden" name="action" value="dbBackup">
<input type="submit" name="submit" value="Optimize Database" id="submitButton" class="submit">
</form>
</div>
<div class="clear"></div>
<?php 
if(file_exists("$backup_folder_path")) {
	$theFiles = array();
//	$misc_path = $setup['path']."/".$setup['misc_folder']."/misc";
	$dir = opendir($backup_folder_path); 
	while ($file = readdir($dir)) { 
		if (($file != ".") && ($file != "..") && ($file != "index.php")) {
			$file_count++;
			array_push($theFiles, $file);
//			print "<li><a href=\"photos/misc/$file\">$file</a>";
		}
	}
	closedir($dir); 
}
if($file_count<=0) { ?>
<div class="center"><h2>No backups have been created</h2></div>
	<?php 
} else {
	?>
	<div class="underlinecolumn">
		<div class="left p10">&nbsp;</div>
		<div class="left p40">File - Click to download</div>
		<div class="left p20">Size</div>
		<div class="left p30">Date</div>
		<div class="clear"></div>
	</div>
	<?php 
	sort($theFiles);
	foreach($theFiles AS $id => $file) {
	?>	
		<div class="underline">	
			<div class="left p10"><a href="index.php?do=settings&action=dbBackup&deleteFile=<?php print $file;?>"  onClick="return confirm('Are you sure you want to delete this file?');"><?php print ai_trash;?></a> </div>
			<div class="left p40"><a href="<?php print $setup['url'].$setup['temp_url_folder']."/".$setup['misc_folder']."/$backup_folder/$file"; ?>"><?php print $file;?></a></div>
			<div class="left p20">
		<?php 
		$size = filesize($backup_folder_path."/".$file);
		if($size <=0) { 
			$bufailed = true;
			?><span class="error">Back Up Failed</span>
		<?php } else { 
			print showfilesize($size);
		}
		?>
		</div>
		<div class="left p30">
		<?php 
		print "". date("l, dS F, Y @ h:ia", filemtime("".$backup_folder_path."/".$file."")); 		
		?>
		</div>
		<div class="clear"></div>
		</div>
		<?php 
		}

}
?>
<?php } ?>
<div>&nbsp;</div>
<?php if($bufailed == true) { ?>
<div class="pc"><h2>Backups failing? </h2></div>
<div class="pc">If you are getting a message that backups are failing, then your hosting company probably has the PHP system function disabled. If this is the case, contact your hosting company and make sure they are making regular backups of your website.</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<?php 
}



function deleteFile() {
	global $_REQUEST,$setup,$backup_folder_path;
//	print "<li>".$setup['path']."/".$setup['misc_folder']."/misc/".$_REQUEST['deleteFile']."";
	if(file_exists("$backup_folder_path/".$_REQUEST['deleteFile']."")) {
		unlink("$backup_folder_path/".$_REQUEST['deleteFile']."");
		$_SESSION['sm'] = "File ".$_REQUEST['deleteFile']." deleted";
		session_write_close();
		header("location: index.php?do=settings&action=dbBackup");
		exit();
	} else {
		$_SESSION['sm'] = "Unable to find file ".$_REQUEST['deleteFile']." ";
		session_write_close();
		header("location: index.php?do=settings&action=dbBackup");
		exit();
	}
}


?>