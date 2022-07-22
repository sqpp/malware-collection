<?php
if (!defined('PHPWS_SOURCE_DIR')) {
  exit();
}

/* Security against those with register globals = on */
if (isset($_REQUEST)){
    foreach ($_REQUEST as $postVarName=>$nullIT) {
	unset($postVarName);
    }
}


forbidLowerAscii();

/* Checks the file array for imbedded code */
secureAllFiles();


/* Clean out bad script, php, and newline characters */
cleanArray($_REQUEST);
cleanArray($_GET);

/* Prevent scripting and bad new line characters from being passed via http get */
function cleanArray(&$Value) {
    if (is_array($Value)) {
	array_walk ($Value, 'cleanArray');
    } else {
	$scriptPatterns = array('/<+script/i', '/(%3C)+script/i', '/(&lt;)+script/i', '/(&#60;)+script/i');
	$phpPatterns    = array("'<+\?'", "'(%3C)+\?'", "'(&lt;)+\?'", "'(&#60;)+\?'");

        $Value = preg_replace('/\.\.\//', '', $Value);
	$Value = preg_replace($scriptPatterns, 'NOSCRIPT', $Value);
	$Value = preg_replace($phpPatterns, 'NOPHP', $Value);
    }
    return;
}

function secureAllFiles()
{
  include PHPWS_SOURCE_DIR . 'conf/security_config.php';

  if (empty($_FILES)) {
    return;
  }

  foreach ($_FILES as $file_key => $checkFile) {
    $temp_name = $checkFile['tmp_name'];
    $filename  = $checkFile['name'];

    if (is_array($temp_name)) {
      foreach ($checkFile['tmp_name'] as $tmp_key => $tmp_name) {
	if ( ($parse_all_files == TRUE && !secureFile($tmp_name, $embedded_text))
	     || !secureName($checkFile['name'][$tmp_key], $forbidden_extensions) )
	{
	  $_FILES[$file_key]['tmp_name'][$tmp_key] = NULL;
	  $_FILES[$file_key]['name'][$tmp_key] = NULL;
	}
      }
    } else {
      if ( (($parse_all_files == TRUE && !secureFile($checkFile['tmp_name'], $embedded_text))
	    || !secureName($filename, $forbidden_extensions)))
      {
	$_FILES[$file_key]['name'] = NULL;
	$_FILES[$file_key]['tmp_name'] = NULL;
      }
    }

  }
}


function secureFile($filename, $embedded_text) {
  if (empty($filename)) {
    return FALSE;
  }
  
  $match = implode('|', $embedded_text);
  
  $hdl = fopen($filename,'r');
  if ($hdl) {
    while (!feof($hdl)) {
      $check = preg_replace('/\s/', '', fgets($hdl, 1024));
      if (preg_match('/' . $match . '/i', $check)) {
	fclose($hdl); 
	return FALSE;
      }
    }
    fclose($hdl);
    return TRUE;
  } else {
    return FALSE;
  }
}

function secureName($filename, $forbidden_extensions) {
  $structure = explode('.', trim($filename));

  $match = implode('|', $forbidden_extensions);
  
  $extension = array_pop($structure);
  if (preg_match('/' . $match . '/i', $extension)) {
    return FALSE;
  } else {
    return TRUE;
  }

}

function forbidLowerAscii()
{
    if (isset($_SERVER['REQUEST_URI']) && 
        preg_match('/%(0|1)(\d|[a-f])/i', $_SERVER['REQUEST_URI'])) {
        header ('Location: index.php');
        exit();
    }
}

?>