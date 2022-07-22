<?php
include 'function.php';

// Save edited file
if($_POST['save']) {
    $file = dirname(__FILE__).'/../..'.$_POST['file'];
    $filename = substr(strrchr($file, '/'), 1);
    $content = htmlspecialchars_decode($_POST['content']);
	
    if (is_writable($file)) {
	if(!$handle = fopen($file, 'w')) { die(message('Cannot open file',NULL,'form.php?do=edit&file='.$_POST['file'])); }
	if(fwrite($handle, $content, strlen($content)) === FALSE) { die(message('Cannot write to file',NULL,'form.php?do=edit&file='.$_POST['file'])); }
	fclose($handle);
	echo message('File Saved',NULL,'form.php?do=edit&file='.$_POST['file']);
	} 
    else echo message('The file '.$filename.' is not writable',NULL,'form.php?do=edit&file='.$_POST['file']);
    echo $file;
    }

// Create New file/folder
if($_POST['create']) {
    if(empty($_POST['filename'])) { die(message('please insert NAME for files/folder')); }
    else {
	$basedir = dirname(__FILE__).'/../..'.$_POST['file'];
	@chdir($basedir) or die(message('You dont have permission to access this','cant access '.$_POST['file'].'/'.$_POST['filename'],2));
	$handle = opendir('.');
	while ($dir = readdir($handle)) $fileArray[] = $dir;

	if(in_array($_POST['filename'],$fileArray,true)) { die(message('Specify different file name',$_POST['file'].'/'.$_POST['filename'].' is already exsist',2)); }
	else {
	    switch($_POST['type']) {
		case 'dir':
		    if (!mkdir($basedir.'/'.$_POST['filename'], 0755)) { die(message('Failed to create folder...',NULL,2)); }
		    else echo message('Folder created',$_POST['file'].'/'.$_POST['filename'].'...........<i>created</i>',1);
		    break;

		case 'file':
		    if (!fopen($basedir.'/'.$_POST['filename'], 'w')) { die(message('Failed to create file...',NULL,2) ); }
		    else echo message('File created',$_POST['file'].'/'.$_POST['filename'].'...........<i>created</i>',1);
		    break;
		}
	    }
	}	
    }

// Rename
if($_POST['rename']) {
    $oPath = dirname(__FILE__).'/../..'.$_POST['oDir'].'/'.$_POST['oName'];
    $nPath = dirname(__FILE__).'/../..'.$_POST['oDir'].'/'.$_POST['nName'];
    if(empty($_POST['nName'])) { die(message('please insert the name',NULL,2)); }
    if(!@rename($oPath,$nPath)) { die(message('failed to rename file/folder...',NULL,2)); }
    echo message('file/folder renamed',NULL,1);
}

// Delete
if($_POST['delete']) {
    $_fileList = explode(',',$_POST[fileBox]);
    if(!is_array($_fileList)) { die(message('hack attempt','please select a file/folder by tick on the chekboxes',1)); }
    else {
	foreach($_fileList as $file) {
	    $_file = realpath(dirname(__FILE__).'/../..'.$file);
	    if(is_file($_file)) {unlink($_file);} 
	    elseif(is_dir($_file)) { delTree($_file); }
	    $msg .= '<b>'.$file.'</b>.......... <i>deleted</i></br>'.PHP_EOL;
	    }
	echo message('File/Folder deleted',$msg,1);
	}
    }

// Copy
if($_POST['copy']) {
    $_POST['backup'] == 1 ? $bak = '.bak' : '';
    $_fileList = explode(',',$_POST['fileBox']);
    if(!is_array($_fileList)) { die(message('hack attempt','please select a file/folder by tick on the chekboxes',1)); }
    foreach($_fileList as $_file) { 
	$oPath = realpath(dirname(__FILE__).'/../..'.$_POST['oDir'].strrchr($_file, '/'));
	$nPath = $_POST['nDir'].strrchr($_file, '/').$bak;
	if($nPath == realpath(dirname(__FILE__).'/../..'.$_POST['oDir']) || $oPath == $_POST['nDir'] ) { die(message('cant copy folder to itself','please specify diferent target folder',2)); }
	if($oPath == $nPath) { die(message('please specify diferent target folder',NULL,2)); }
	if(file_exists($nPath)) { die(message('cant copy file/folder','file <b>'.strrchr($_file, '/').'</b> already exists on folder <u>'.$_POST['nDir'].'</u>',2) ); }
	if(!@smartCopy($oPath,$nPath)) { die(message('failed to copy file/folder',NULL,2)); }
	echo message('File/Folder copied', NULL);
	}
    }

// Move
if($_POST['move']) {
    $_fileList = explode(',',$_POST['fileBox']);
    if(!is_array($_fileList)) { die(message('hack attempt','please select a file/folder by tick on the chekboxes',1)); }
    foreach($_fileList as $_file) {
	$oPath = realpath(dirname(__FILE__).'/../..'.$_POST['oDir'].strrchr($_file, '/'));
	$nPath = $_POST['nDir'].strrchr($_file, '/');
	if(file_exists($nPath)) { die(message('cant move file/folder','file <b>'.strrchr($_file, '/').'</b> already exists on folder <u>'.$_POST['nDir'].'</u>',2) ); }
	if(!@rename($oPath,$nPath)) { die(message('failed to move file/folder',NULL,2)); }
	echo message('File/Folder moved', NULL);
	}
    }

//Permission
if($_POST['perm']) {
    //print_r($_POST);
    $_fileList = explode(',',$_POST['fileBox']);
    if(!is_array($_fileList)) { die(message('hack attempt','please select a file/folder by tick on the chekboxes',1)); }
    if(!$_POST['mod']) { die(message('cant change file/folder permission','please insert file/folder <i>mode</i>',2)); } 
    foreach($_fileList as $_file) {
	$file = realpath(dirname(__FILE__).'/../..'.$_file);
	if(!file_exists($nPath)) { die(message('cant change file/folder permission','<b>'.strrchr($_file, '/').'</b> not found',2) ); }
	$mod = '0'.octdec($_POST['mod']);
	if($_POST['recrusive']) chmodr($file,$mod);
	else chmod($file,$mod );
	}

    }

//Upload
if($_POST['upload']) {
    $dir = realpath(dirname(__FILE__).'/../..'.$_POST['file']);
    $file = $dir .'/'. basename($_FILES['userfile']['name']);
    if(!is_writable($dir)) { die(message('You dont have permission','cant upload file to folder '.$_POST['file'],1)); }
    if(file_exists($file)) { die(message('Specify different file','File <b>'.$_POST['file'].'/'.$_FILES['userfile']['name'].'</b> is already exsist',2)); }
    else {
	if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
	    move_uploaded_file($_FILES['userfile']['tmp_name'], $file);
	    echo message('Upload Finished','File <b>'. $_FILES['userfile']['name'] .'</b> uploaded successfully',1);
	    }
	else { echo message('Upload Error','Cant read the source file',2); }
	}
    }
?>