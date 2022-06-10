<?php
/**
 * Project:   WU GRAPHS
 * Module:    WUG-test.php 
 * Copyright: (C) 2010 Radomir Luza
 * Email: luzar(a-t)post(d-o-t)cz
 * WeatherWeb: http://pocasi.hovnet.cz 
 */
################################################################################
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 3
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>. 
################################################################################

include_once('WUG-settings.php');
session_start();

if(time() - $_SESSION['accessts'] >= 60*15) { // unset login after 15 minutes
  $_SESSION['wulogin'] = false;
}

if ($_SESSION['wulogin'] and $_GET['info']) {
  phpinfo(); 
} elseif ($_POST['passw'] == $WUpassw || $_SESSION['wulogin']) {
$_SESSION['wulogin'] = true;
$_SESSION['accessts'] = time();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>WU Graphs - test</title>
  <style>
  ul li {
  padding-bottom: 10px;
  }
  </style>
  </head>
  <body style="font:15px arial; text-align:center;">
<?php  
#### TESTS AND INFO 
// WRITABLE CACHE DIR
    if (is_writable($WUcacheDir)) {
      $writable = 'Cache directory is <span style="color:green; font-weight:bold;">writable</span>';
      $writableFlag = true;
    } else {
      $writable = 'Cache directory is <span style="color:red; font-weight:bold;">not writable</span>';
      $writableFlag = false;
    }
    
// CACHE INFO
    if ($cacheWUfiles || $db_cache_type == 'file') {
      $cachetxt = 'enabled';
    } else {
      $cachetxt = 'disabled';
    }    
    if (!$writableFlag and $cacheWUfiles || $db_cache_type == 'file') {
      $cacheColor = 'style="color:red"';
    } else {
      $cacheColor = '';
    }
    
    $Imetric = $metric ? 'metric':'english'; 
    
    $IlangFile = is_file($WUGlangFile) ? '<b><span style="color: green;">found</span></b>' : '<b><span style="color: red;">missing</span></b> >>> using default language file'; 
    $IlangFile2 = !is_file($WUGlangFile)  ? true : false;
    
    $IdpckLang = is_file($dpckFile) ? '<span style="color: green;">found</span>' : '<span style="color: red;">missing</span>' ;
    
    $IverCheck = $updateCheck ? 'enabled':'disabled';
    
// LANG TEST
    if ($IlangFile2) {
      $langSource = 'default because lang file not found';
    } elseif (!empty($_REQUEST['lang'])) {
      $langSource = 'URL';    
    } elseif (!empty($_COOKIE['cookie_lang'])) {
      $langSource = 'cookie';
    } else {
      $langSource = 'default';
    }    
    if ($IlangFile2)  {
      $usedLang = strtolower($defaultWUGlang);
    } else {
      $usedLang = strtolower($WUGLang);
    }
// FIND ALL AVAILABLE LANGUAGES
    foreach (glob( $langDir. 'WUG-language-*.php') as $LangName) {     
      $avaiLangs .= substr($LangName, 25, -4).', '; //./languages/WUG-language-cs.php
    }
    $avaiLangs = substr($avaiLangs, 0, -2);
// test bad/default WUID
    $wuidErr = $WUID == 'changeMe' && $dataSource != 'mysql' ? '<br>Error: Default $WUID detected! Please change your Wunderground ID ($WUID) in WUG-settings.php <br>' : '' ;

// test ./WUG-settings.php in root
      $pgRoot = substr(realpath(dirname(__FILE__)), 0, -10); // eg: /home/dir/www/WUG-settings.php (removed 'wxwugraphs/')
      $bsErr = is_file($pgRoot.'WUG-settings.php') ? 'Error: Probably you have file WUG-settings.php in root dir. of your website - this may causing errors in wu graphs.<br>' : '' ;

// test for "allow_url_fopen" 
    if( !ini_get('allow_url_fopen') && $dataSource != 'mysql' ) {
      $baseErr = 'You have disabled allow_url_fopen on your server/webhosting so WU Graphs cannot download graph data. This is critical error. You can try to enable "<i>Alternative URL fopen<i/>" in <a href="configurator.php" target="_blank">WU Graphs configurator</a>';
    }    
    
// test whole get WU data process
    if ($writableFlag and $_GET['complex'] == "1") {
          $unitsLnk = $metric ? 'metric' : 'english' ;    
          $opts = array( 
              'http' => array( 
                  'method' => 'GET', 
                  'header' => 'Cookie: Units='.$unitsLnk 
              ) 
          );  
          $context = stream_context_create($opts); 
          $WUcacheFile = $WUcacheDir . '/test-url-fopen.txt'; 
          $WUsourceFile = 'http://www.wunderground.com/weatherstation/WXDailyHistory.asp?ID='.$WUID.'&graphspan=month&year=' . date('j') . '&year=' . date('Y') .  '&month=' . date('n') . '&format=1&units='.$unitsLnk;
          
          copyWUfile();
    
          // is cache file empty???
          if ( '' == @file_get_contents($WUcacheFile) ) {  // file is empty
            $aufErr = 'Error: Unspecified error in the data acquisition. You can try set $sendAgent value in WUG-settings.php to <b>true</b>.<br>';     
          } else {
            $aufErr = '<span style="color: green;">No error was detected during a comprehensive test.</span><br />';
          }  
    } elseif (!$writableFlag and $_GET['complex'] == "1") {
        $aufErr = 'For this test must be cache directory (configured in WUG-settings.php) writable for PHP. (chmod 777)';
    } else {
        $aufErr = '';
    }
    
// PHP version test
  if (strnatcmp(phpversion(),'5.0.0') >= 0) { 
    $phpVerNfo = '<span style="color: green;"> - O.K.</span>';
  } else { 
    $phpVerNfo = '<span style="color: orange;"> - PHP 5 or newer is required. </span>';
    $pvnAddit = 'but you can try ';
  }
  
// MB_ FUNCTIONS TEST
  if (!function_exists('mb_stroupper')) {
  $mbsErr = '<br>You don\'t have available multibyte (mb_xxxx) functions. <a href="http://technaugh.com/technaugh/dotproject/call-to-undefined-function-mb_strpos-or-mb_stripos-or-mb_strlen-or-mb_strtoupper-or-mb_stristrll/" target="_blank">More info</a>. Instead of multibyte functions will be used standard string functions. This can cause problems with special characters on non-English or multilingual Web sites. If you have thiese problems, you can copletely disable this text transformation in configuration (set <i>Disable multibyte support</i> to Yes)<br>';
  } 

// MOD_SECURITY detection
  $mSecCook = $cookieEnabled ? ', please disable cookie support (may help) in WUG-setting.php (set $cookieEnabled to false)<br><b>OR</b> ' : '.';
  $mSecErrIn = 'On your Host/Server is enabled module mod_security.<br>If you have problems with appearance of WU graphs'.$mSecCook.'<br>
  You can add to .htacces file this code (it maybe turns off this module):<br>
<textarea rows="5" style="width:500px;">
# Turn off mod_security filtering fo wxwugraphs directory
<directory '.realpath(dirname(__FILE__)).'>
  SecFilterEngine Off
  SecFilterScanPOST Off
</Directory>
</textarea>
<br>
info about .htaccess and mod_security at <a href="http://www.askapache.com/htaccess/mod_security-htaccess-tricks.html" target="_blank">askapache.com</a><br>
more info/basics about .htacces:
<a href="http://en.wikipedia.org/wiki/Htaccess" target="_blank">1</a>, <a href="http://httpd.apache.org/docs/2.2/howto/htaccess.html" target="_blank">2</a>, <a href="http://www.javascriptkit.com/howto/htaccess.shtml" target="_blank">3</a>, <a href="http://www.thesitewizard.com/apache/index.shtml" target="_blank">4</a> or just use <a href="www.google.com" target="_blank">Google</a>
  <br><b>OR</b><br>You can contact your host and ask him about disabling "mod_security" on your website.<br>';
  $mSecErr = '';  
  /* abadoned ... problems with new future versions of mod_security
  if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array("mod_security", $modules) or in_array("mod_security2", $modules)) {
      $mSecErr = $mSecErrIn; 
    }
  } else { // Host 'outlawed' the use of function apache_get_modules
    */
    ob_start(); 
    phpinfo(INFO_MODULES); 
    $contents = ob_get_clean(); 
    $moduleAvailable = strpos($contents, 'mod_security') !== false;
    if ($moduleAvailable) {
      $mSecErr = $mSecErrIn; 
    }    
  //}

// WD MYSQL connection test
  if ($dataSource == 'mysql') {
    $mysqlConT = @mysql_connect($dbhost, $dbuser, $dbpass);
    $mysqlErrT = !$mysqlConT ? mysql_errno().": ".mysql_error().'<br>' : '';
    if ($mysqlConT) {
      $mysqlSelT = @mysql_select_db($dbname, $mysqlConT);
      $mysqlErrT = !$mysqlSelT ? mysql_errno().": ".mysql_error().'<br>' : '';
    }
    $msqlErr = empty($mysqlErrT) ? '' : 'WD MySQL database connection error '.$mysqlErrT;
  } else {
    $msqlErr = '';
  }
// WD MYSQL table test
  if ($mysqlConT) {
    $sqil = mysql_query("SHOW TABLES FROM $dbname");
    while ($row = mysql_fetch_row($sqil)) {
      if ($row[0] == $dbtable) {
        $tbfound = true;
      }
      if ($row[0] == $db_cache_table) {
        $tbfound2 = true;
      }
    }
    if (!isset($tbfound)) {
      $dbtnfErr = "Table '$dbtable' not found in database '$dbname'. Please check your MySQL configuration.".'<br>';
    }
  }
// WD MYSQL db caching table test
  if ($db_cache_type == 'db' && $mysqlConT) {
    if (!isset($tbfound2)) {
      $dbtnfErr2 = "You have set caching for MySQL datasource to 'db' but configured table '$db_cache_table' not found in database '$dbname'. If you have problem with creating this table, you can use 'file' caching for MySQL datasource.".'<br>';
    }
  }

// BOM detection
  $fileBom = file_get_contents('WUG-settings.php');
  $bom = pack("CCC", 0xef, 0xbb, 0xbf);
  if (0 == strncmp($fileBom, $bom, 3)) {
  	$errBom = 'BOM detected in WUG-settings.php file! Please read information about BOM and WU graphs at <a href="http://pocasi.hovnet.cz/wxwug.php#tips">WU Graphs page</a>.<br />';
    //echo "BOM detected - file is UTF-8\n";
  	//$fileBom = substr($fileBom, 3);  // remove BOM
  } else {
    $errBom = '';
  } 

// HOURLY GRAPHS TEST
  if ($hourGraphs == 'craw') {
    $hgType = 'clientrawhour.txt';
    if (is_file($clientRawHpath.'clientrawhour.txt')) {
      $hgerr = ' (<span style="color:green; font-weight: bold">found</span>)';
    } else {
      $hgerr = ' (<span style="color:red; font-weight: bold">file not found</span>)';
    }
  } elseif ($hourGraphs == 'db') {
    $hgType = 'WD MySQL database';
    if ($dbtnfErr) {
      $hgerr = ' - <span style="color:red; font-weight: bold">connection error to MySQL database</span>';
    } 
  } else {
    $hgType = 'Disabled';
  }


// CONTINUE IN PAGE
    echo
    '
    <div style="width: 690px; margin:0px auto; text-align:left;">
    <div style="text-align: center; text-decoration: underline; font-size: 120%; line-height: 1.4em;">
    <span>WU graphs - test script for station<br></span> 
    "'.$stationName.'"
    </div>
    <br>
    <br>

    TEST RESULTS:
    <div id="critical_error" style="font-size:12pt; color: red; ">'.$baseErr.$aufErr.$wuidErr.$bsErr.$mSecErr.$msqlErr.$errBom.$dbtnfErr.$dbtnfErr2.'</div>
    <ul>
    <li>Current PHP version: <b>' . phpversion() . $phpVerNfo . '</b></li>
    <li>Graph data source: <b>'.($dataSource == 'mysql' ? 'WD MySQL database' : 'Weather Underground').'</b></li>
    <li>WU Graphs location: "<b>'.realpath(dirname(__FILE__)).'</b>"</li>
    <li>Cache directory: "<b>'.$WUcacheDir.'</b>"</li>
    <li>'.$writable.' and file caching is <b><span '.$cacheColor.'>'.$cachetxt.'</span></b></li>
    <li>Hourly graphs data source: <b>'.$hgType.'</b>'.$hgerr.'</li>
    <li>Default language: <b>'.strtolower($defaultWUGlang).'</b></li>
    <li>Tested language: <b>'.strtolower($_REQUEST['lang']).'</b> **)
    <li>Used langugage: <b>'.$usedLang.'</b> - configured from <b>'.$langSource.'</b> &nbsp;*)</li>
    <li>Used language file: "<b>'.$WUGlangFile.'</b>" '.$IlangFile.'</li>
    <li>Used Datepicker language file: "<b>'.$dpckFile.'</b>" <b>'.$IdpckLang.'</b></li>
    <li>Available languages: <b>'.$avaiLangs.'</b></li>
    <li>Version checker: <b>'.$IverCheck.'</b></li>
    <li>Units: <b>'.$Imetric.'</b></li>
    </ul>
    
    <div style="margin-top: 30px;">
    *) Normally is language set from COOKIE or from configured DEFAULT value.<br>
    COOKIE value is also automatically taken in WU Graphs tabbed mode from currently language set in Saratoga Carterlake templates or from URL parameter (lang=xx) at all WUG pages. <br>       
    <br>
    
    **) For testing another language use URL parameter "<i>lang</i>".<br>
    For example: German language test will have adress "<i>http://'.$_SERVER['HTTP_HOST'].'/WUG-test.php?lang=de</i>"<br>
    <a href="./WUG-test.php?lang=">direct test link</a> 
    <br /><br />
    
    ***) If you have empty graphs (and no error here) you can try a Complex WU data retrieve test with URL parameter "<i>complex=1</i>"<br />
    <a href="./WUG-test.php?complex=1">direct link</a>
    <br /><br />     
    
    ****) For complete PHP (only) information use URL parameter "<i>info=1</i>" <br />
    <a href="./WUG-test.php?info=1">direct link</a>   
    <br />
    
    </div>
    
    </div>
    ';
echo 
'
  </body>
</html>
';
} elseif ($_POST['passw'] != $WUpassw) {
?>
    <div style="text-align: center;">
      <form id="formp" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table allign="center" style="width:270px; margin:0px auto;">
          <tr>
            <td>Password: 
            <td><input name="passw" type="password" size="12">
            <td><input type="submit" value="Submit" name="send">
        </table>
      </form>
    </div>
<?php
}

    function copyWUfile() {
      global $WUsourceFile, $context, $WUcacheFile; 
      $wsource = @file_get_contents($WUsourceFile, 0, $context);
      $ctarget = @fopen($WUcacheFile, "w");
      fwrite($ctarget, $wsource);
      fclose($ctarget);
    }

//echo getcwd();
?>