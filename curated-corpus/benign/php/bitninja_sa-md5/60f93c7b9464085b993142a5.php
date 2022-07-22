<?php
/**
 * @package tikiwiki
 */
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$
/*
About the design:
tiki-check.php is designed to run in 2 modes
1) Regular mode. From inside Tiki, in Admin | General
2) Stand-alone mode. Used to check a server pre-Tiki installation, by copying (only) tiki-check.php onto the server and pointing your browser to it.
tiki-check.php should not crash but rather avoid running tests which lead to tiki-check crashes.
*/

use Tiki\Lib\Alchemy\AlchemyLib;
use Tiki\Package\ComposerManager;

// TODO : Create sane 3rd mode for Monitoring Software like Nagios, Icinga, Shinken
// * needs authentication, if not standalone
isset($_REQUEST['nagios']) ? $nagios = true : $nagios = false;
file_exists('tiki-check.php.lock') ? $locked = true : $locked = false;
$font = 'lib/captcha/DejaVuSansMono.ttf';

$inputConfiguration = array(
	array(
		'staticKeyFilters' => array(
			'dbhost' => 'text',
			'dbuser' => 'text',
			'dbpass' => 'text',
			'email_test_to' => 'email',
		),
	),
);

// reflector for SefURL check
if (isset($_REQUEST['tiki-check-ping'])) {
	die('pong:' . (int)$_REQUEST['tiki-check-ping']);
}


function checkOPCacheCompatibility()
{
	return ! ((version_compare(PHP_VERSION, '7.1.0', '>=') && version_compare(PHP_VERSION, '7.2.0', '<')) //7.1.x
		|| (version_compare(PHP_VERSION, '7.2.0', '>=') && version_compare(PHP_VERSION, '7.2.19', '<')) // >= 7.2.0 < 7.2.19
		|| (version_compare(PHP_VERSION, '7.3.0', '>=') && version_compare(PHP_VERSION, '7.3.6', '<'))); // >= 7.3.0 < 7.3.6
}

if (file_exists('./db/local.php') && file_exists('./templates/tiki-check.tpl')) {
	$standalone = false;
	require_once('tiki-setup.php');
	// TODO : Proper authentication
	$access->check_permission('tiki_p_admin');

	// This page is an admin tool usually used in the early stages of setting up Tiki, before layout considerations.
	// Restricting the width is contrary to its purpose.
	$prefs['feature_fixed_width'] = 'n';
} else {
	$standalone = true;
	$render = "";

	/**
	 * @param $string
	 * @return mixed
	 */
	function tra($string)
	{
		return $string;
	}

	function tr($string)
	{
		return tra($string);
	}


	/**
	  * @param $var
	  * @param $style
	  */
	function renderTable($var, $style = "")
	{
		global $render;
		$morestyle = "";
		if ($style == "wrap") {
			$morestyle = "overflow-wrap: anywhere;";
		}
		if (is_array($var)) {
			$render .= '<table style="border:2px solid grey;' . $morestyle . '">';
			foreach ($var as $key => $value) {
				$render .= '<tr style="border:1px solid">';
				$render .= '<td style="border:1px black;padding:5px;white-space:nowrap;">';
				$render .= $key;
				$render .= "</td>";
				$iNbCol = 0;
				foreach ($var[$key] as $key2 => $value2) {
					$render .= '<td style="border:1px solid;';
					if ($iNbCol != count(array_keys($var[$key])) - 1) {
						$render .= 'text-align: center;white-space:nowrap;';
					}
					$render .= '"><span class="';
					switch ($value2) {
						case 'good':
						case 'safe':
						case 'unsure':
						case 'bad':
						case 'risky':
						case 'info':
							$render .= "button $value2";
							break;
					}
					$render .= '">' . $value2 . '</span></td>';
					$iNbCol++;
				}
				$render .= '</tr>';
			}
			$render .= '</table>';
		} else {
			$render .= 'Nothing to display.';
		}
	}
}

// Get PHP properties and check them
$php_properties = false;

// Check error reporting level
$e = error_reporting();
$d = ini_get('display_errors');
$l = ini_get('log_errors');
if ($l) {
	if (! $d) {
		$php_properties['Error logging'] = array(
		'fitness' => tra('info'),
		'setting' => 'Enabled',
		'message' => tra('Errors will be logged, since log_errors is enabled. Also, display_errors is disabled. This is good practice for a production site, to log the errors instead of displaying them.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
		);
	} else {
		$php_properties['Error logging'] = array(
		'fitness' => tra('info'),
		'setting' => 'Enabled',
		'message' => tra('Errors will be logged, since log_errors is enabled, but display_errors is also enabled. Good practice, especially for a production site, is to log all errors instead of displaying them.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
		);
	}
} else {
	$php_properties['Error logging'] = array(
	'fitness' => tra('info'),
	'setting' => 'Full',
	'message' => tra('Errors will not be logged, since log_errors is not enabled. Good practice, especially for a production site, is to log all errors.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
}
if ($e == 0) {
	if ($d != 1) {
		$php_properties['Error reporting'] = array(
			'fitness' => tra('info'),
			'setting' => 'Disabled',
			'message' => tra('Errors will not be reported, because error_reporting and display_errors are both turned off. This may be appropriate for a production site but, if any problems occur, enable these in php.ini to get more information.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
		);
	} else {
		$php_properties['Error reporting'] = array(
			'fitness' => tra('info'),
			'setting' => 'Disabled',
			'message' => tra('No errors will be reported, although display_errors is On, because the error_reporting level is set to 0. This may be appropriate for a production site but, in if any problems occur, raise the value in php.ini to get more information.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
		);
	}
} elseif ($e > 0 && $e < 32767) {
	if ($d != 1) {
		$php_properties['Error reporting'] = array(
			'fitness' => tra('info'),
			'setting' => 'Disabled',
			'message' => tra('No errors will be reported, because display_errors is turned off. This may be appropriate for a production site but, in any problems occur, enable it in php.ini to get more information. The error_reporting level is reasonable at ' . $e . '.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
		);
	} else {
		$php_properties['Error reporting'] = array(
			'fitness' => tra('info'),
			'setting' => 'Partly',
			'message' => tra('Not all errors will be reported as the error_reporting level is at ' . $e . '. ' . 'This is not necessarily a bad thing (and it may be appropriate for a production site) as critical errors will be reported, but sometimes it may be useful to get more information. Check the error_reporting level in php.ini if any problems are occurring.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
		);
	}
} else {
	if ($d != 1) {
		$php_properties['Error reporting'] = array(
			'fitness' => tra('info'),
			'setting' => 'Disabled',
			'message' => tra('No errors will be reported although the error_reporting level is all the way up at ' . $e . ', because display_errors is off. This may be appropriate for a production site but, in case of problems, enable it in php.ini to get more information.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
		);
	} else {
		$php_properties['Error reporting'] = array(
			'fitness' => tra('info'),
			'setting' => 'Full',
			'message' => tra('All errors will be reported as the error_reporting level is all the way up at ' . $e . ' and display_errors is on. This is good because, in case of problems, the error reports usually contain useful information.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
		);
	}
}

// Now we can raise our error_reporting to make sure we get all errors
// This is especially important as we can't use proper exception handling with PDO as we need to be PHP 4 compatible
error_reporting(-1);

// Check if ini_set works
if (function_exists('ini_set')) {
	$php_properties['ini_set'] = array(
		'fitness' => tra('good'),
		'setting' => 'Enabled',
		'message' => tra('ini_set is used in some places to accommodate special needs of some Tiki features.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
	// As ini_set is available, use it for PDO error reporting
	ini_set('display_errors', '1');
} else {
	$php_properties['ini_set'] = array(
		'fitness' => tra('unsure'),
		'setting' => 'Disabled',
		'message' => tra('ini_set is used in some places to accommodate special needs of some Tiki features. Check disable_functions in your php.ini.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
}

// First things first
// If we don't have a DB-connection, some tests don't run
$s = extension_loaded('pdo_mysql');
if ($s) {
	$php_properties['DB Driver'] = array(
		'fitness' => tra('good'),
		'setting' => 'PDO',
		'message' => tra('The PDO extension is the suggested database driver/abstraction layer.')
	);
} elseif ($s = extension_loaded('mysqli')) {
	$php_properties['DB Driver'] = array(
		'fitness' => tra('unsure'),
		'setting' => 'MySQLi',
		'message' => tra('The recommended PDO database driver/abstraction layer cannot be found. The MySQLi driver is available, though, so the database connection will fall back to the AdoDB abstraction layer that is bundled with Tiki.')
	);
} elseif (extension_loaded('mysql')) {
	$php_properties['DB Driver'] = array(
		'fitness' => tra('unsure'),
		'setting' => 'MySQL',
		'message' => tra('The recommended PDO database driver/abstraction layer cannot be found. The MySQL driver is available, though, so the database connection will fall back to the AdoDB abstraction layer that is bundled with Tiki.')
	);
} else {
	$php_properties['DB Driver'] = array(
		'fitness' => tra('bad'),
		'setting' => 'Not available',
		'message' => tra('None of the supported database drivers (PDO/mysqli/mysql) is loaded. This prevents Tiki from functioning.')
	);
}

// Now connect to the DB and make all our connectivity methods work the same
$connection = false;
if ($standalone && ! $locked) {
	if (empty($_POST['dbhost']) && ! ($php_properties['DB Driver']['setting'] == 'Not available')) {
			$render .= <<<DBC
<h2>Database credentials</h2>
Couldn't connect to database, please provide valid credentials.
<form method="post" action="{$_SERVER['SCRIPT_NAME']}">
	<p><label for="dbhost">Database host</label>: <input type="text" id="dbhost" name="dbhost" value="localhost" /></p>
	<p><label for="dbuser">Database username</label>: <input type="text" id="dbuser" name="dbuser" /></p>
	<p><label for="dbpass">Database password</label>: <input type="password" id="dbpass" name="dbpass" /></p>
	<p><input type="submit" class="btn btn-primary btn-sm" value=" Connect " /></p>
</form>
DBC;
	} else {
		try {
			switch ($php_properties['DB Driver']['setting']) {
				case 'PDO':
					// We don't do exception handling here to be PHP 4 compatible
					$connection = new PDO('mysql:host=' . $_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass']);
					/**
					  * @param $query
					   * @param $connection
					   * @return mixed
					  */
					function query($query, $connection)
					{
						$result = $connection->query($query);
						$return = $result->fetchAll();
						return($return);
					}
					break;
				case 'MySQLi':
					$error = false;
					$connection = new mysqli($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass']);
					$error = mysqli_connect_error();
					if (! empty($error)) {
						$connection = false;
						$render .= 'Couldn\'t connect to database: ' . htmlspecialchars($error);
					}
					/**
					 * @param $query
					 * @param $connection
					 * @return array
					 */
					function query($query, $connection)
					{
						$result = $connection->query($query);
						$return = array();
						while ($row = $result->fetch_assoc()) {
							$return[] = $row;
						}
						return($return);
					}
					break;
				case 'MySQL':
					$connection = mysql_connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass']);
					if ($connection === false) {
						$render .= 'Cannot connect to MySQL. Wrong credentials?';
					}
					/**
					 * @param $query
					 * @param string $connection
					 * @return array
					 */
					function query($query, $connection = '')
					{
						$result = mysql_query($query);
						$return = array();
						while ($row = mysql_fetch_array($result)) {
							$return[] = $row;
						}
						return($return);
					}
					break;
			}
		} catch (Exception $e) {
			$render .= 'Cannot connect to MySQL. Error: ' . htmlspecialchars($e->getMessage());
		}
	}
} else {
	/**
	  * @param $query
	  * @return array
	  */
	function query($query)
	{
		global $tikilib;
		$result = $tikilib->query($query);
		$return = array();
		while ($row = $result->fetchRow()) {
			$return[] = $row;
		}
		return($return);
	}
}

// Basic Server environment
$server_information['Operating System'] = array(
	'value' => PHP_OS,
);

if (PHP_OS == 'Linux' && function_exists('exec')) {
	exec('lsb_release -d', $output, $retval);
	if ($retval == 0) {
		$server_information['Release'] = array(
			'value' => str_replace('Description:', '', $output[0])
		);
		# Check for FreeType fails without a font, i.e. standalone mode
		# Using a URL as font source doesn't work on all PHP installs
		# So let's try to gracefully fall back to some locally installed font at least on Linux
		if (! file_exists($font)) {
			$font = exec('find /usr/share/fonts/ -type f -name "*.ttf" | head -n 1', $output);
		}
	} else {
		$server_information['Release'] = array(
			'value' => tra('N/A')
		);
	}
}

$server_information['Web Server'] = array(
	'value' => $_SERVER['SERVER_SOFTWARE']
);

$server_information['Server Signature']['value'] = ! empty($_SERVER['SERVER_SIGNATURE']) ? $_SERVER['SERVER_SIGNATURE'] : 'off';

// Free disk space
if (function_exists('disk_free_space')) {
	$bytes = @disk_free_space('.');	// this can fail on 32 bit systems with lots of disc space so suppress the possible warning
	$si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
	$base = 1024;
	$class = min((int) log($bytes, $base), count($si_prefix) - 1);
	$free_space = sprintf('%1.2f', $bytes / pow($base, $class)) . ' ' . $si_prefix[$class];
	if ($bytes === false) {
		$server_properties['Disk Space'] = array(
			'fitness' => 'unsure',
			'setting' => tra('Unable to detect'),
			'message' => tra('Cannot determine the size of this disk drive.')
		);
	} elseif ($bytes < 200 * 1024 * 1024) {
		$server_properties['Disk Space'] = array(
			'fitness' => 'bad',
			'setting' => $free_space,
			'message' => tra('Less than 200MB of free disk space is available. Tiki will not fit in this amount of disk space.')
		);
	} elseif ($bytes < 250 * 1024 * 1024) {
		$server_properties['Disk Space'] = array(
			'fitness' => 'unsure',
			'setting' => $free_space,
			'message' => tra('Less than 250MB of free disk space is available. This would be quite tight for a Tiki installation. Tiki needs disk space for compiled templates and uploaded files.') . ' ' . tra('When the disk space is filled, users, including administrators, will not be able to log in to Tiki.') . ' ' . tra('This test cannot reliably check for quotas, so be warned that if this server makes use of them, there might be less disk space available than reported.')
		);
	} else {
		$server_properties['Disk Space'] = array(
			'fitness' => 'good',
			'setting' => $free_space,
			'message' => tra('More than 251MB of free disk space is available. Tiki will run smoothly, but there may be issues when the site grows (because of file uploads, for example).') . ' ' . tra('When the disk space is filled, users, including administrators, will not be able to log in to Tiki.') . ' ' . tra('This test cannot reliably check for quotas, so be warned that if this server makes use of them, there might be less disk space available than reported.')
		);
	}
} else {
		$server_properties['Disk Space'] = array(
			'fitness' => 'N/A',
			'setting' => 'N/A',
			'message' => tra('The PHP function disk_free_space is not available on your server, so the amount of available disk space can\'t be checked for.')
		);
}

// PHP Version
if (version_compare(PHP_VERSION, '5.6.0', '<')) {
	$php_properties['PHP version'] = array(
		'fitness' => tra('unsure'),
		'setting' => phpversion(),
		'message' => 'This PHP version is somewhat old. Tiki 12.x LTS or 15.x LTS can be run, but not newer versions. Please see http://doc.tiki.org/Requirements for details.'
	);
} elseif (version_compare(PHP_VERSION, '7.0.0', '<')) {
	$php_properties['PHP version'] = array(
		'fitness' => tra('unsure'),
		'setting' => phpversion(),
		'message' => 'This version of PHP is good, and Tiki versions between 15.x LTS and 18.x LTS will work fine on this version of PHP. Please see http://doc.tiki.org/Requirements for details.'
	);
} elseif (version_compare(PHP_VERSION, '7.1.0', '<')) {
	$php_properties['PHP version'] = array(
		'fitness' => tra('good'),
		'setting' => phpversion(),
		'message' => 'This version of PHP is good, Tiki 18.x - Tiki 20 will work fine on this version of PHP. Please see http://doc.tiki.org/Requirements for details.'
	);
} elseif (version_compare(PHP_VERSION, '7.2.0', '<')) {
	$php_properties['PHP version'] = array(
		'fitness' => tra('good'),
		'setting' => phpversion(),
		'message' => 'This version of PHP is good, Tiki 19.x - Tiki 21.x will work fine on this version of PHP. Please see http://doc.tiki.org/Requirements for details.'
	);
} else {
	$php_properties['PHP version'] = array(
		'fitness' => tra('good'),
		'setting' => phpversion(),
		'message' => 'This version of PHP is recent. Versions 19.x and newer will work fine on this version of PHP. Please see http://doc.tiki.org/Requirements for details.'
	);
}

// Check PHP command line version
if (function_exists('exec')) {
	$cliSearchList = array('php', 'php56', 'php5.6', 'php5.6-cli');
	$isUnix = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? false : true;
	$cliCommand = '';
	$cliVersion = '';
	foreach ($cliSearchList as $command) {
		if ($isUnix) {
			$output = exec('command -v ' . escapeshellarg($command) . ' 2>/dev/null');
		} else {
			$output = exec('where ' . escapeshellarg($command . '.exe'));
		}
		if (! $output) {
			continue;
		}

		$cliCommand = trim($output);
		exec(escapeshellcmd(trim($cliCommand)) . ' --version', $output);
		foreach ($output as $line) {
			$parts = explode(' ', $line);
			if ($parts[0] === 'PHP') {
				$cliVersion = $parts[1];
				break;
			}
		}
		break;
	}
	if ($cliCommand) {
		if (phpversion() == $cliVersion) {
			$php_properties['PHP CLI version'] = array(
				'fitness' => tra('good'),
				'setting' => $cliVersion,
				'message' => 'The version of the command line executable of PHP (' . $cliCommand . ') is the same version as the web server version.',
			);
		} else {
			$php_properties['PHP CLI version'] = array(
				'fitness' => tra('unsure'),
				'setting' => $cliVersion,
				'message' => 'The version of the command line executable of PHP (' . $cliCommand . ') is not the same as the web server version.',
			);
		}
	} else {
		$php_properties['PHP CLI version'] = array(
			'fitness' => tra('unsure'),
			'setting' => '',
			'message' => 'Unable to determine the command line executable for PHP.',
		);
	}
}

// PHP Server API (SAPI)
$s = php_sapi_name();
if (substr($s, 0, 3) == 'cgi') {
	$php_properties['PHP Server API'] = array(
		'fitness' => tra('info'),
		'setting' => $s,
		'message' => tra('PHP is being run as CGI. Feel free to use a threaded Apache MPM to increase performance.')
	);

	$php_sapi_info = array(
		'message' => tra('Looks like you are running PHP as FPM/CGI/FastCGI, you may be able to override some of your PHP configurations by add them to .user.ini files, see:'),
		'link' => 'http://php.net/manual/en/configuration.file.per-user.php'
	);
} elseif (substr($s, 0, 3) == 'fpm') {
	$php_properties['PHP Server API'] = array(
		'fitness' => tra('info'),
		'setting' => $s,
		'message' => tra('PHP is being run using FPM (Fastcgi Process Manager). Feel free to use a threaded Apache MPM to increase performance.')
	);

	$php_sapi_info = array(
		'message' => tra('Looks like you are running PHP as FPM/CGI/FastCGI, you may be able to override some of your PHP configurations by add them to .user.ini files, see:'),
		'link' => 'http://php.net/manual/en/configuration.file.per-user.php'
	);
} else {
	if (substr($s, 0, 6) == 'apache') {
		$php_sapi_info = array(
			'message' => tra('Looks like you are running PHP as a module in Apache, you may be able to override some of your PHP configurations by add them to .htaccess files, see:'),
			'link' => 'http://php.net/manual/en/configuration.changes.php#configuration.changes.apache'
		);
	}

	$php_properties['PHP Server API'] = array(
		'fitness' => tra('info'),
		'setting' => $s,
		'message' => tra('PHP is not being run as CGI. Be aware that PHP is not thread-safe and you should not use a threaded Apache MPM (like worker).')
	);
}

// ByteCode Cache
if (function_exists('apc_sma_info') && ini_get('apc.enabled')) {
	$php_properties['ByteCode Cache'] = array(
		'fitness' => tra('good'),
		'setting' => 'APC',
		'message' => tra('APC is being used as the ByteCode Cache, which increases performance if correctly configured. See Admin->Performance in the Tiki for more details.')
	);
} elseif (function_exists('xcache_info') && ( ini_get('xcache.cacher') == '1' || ini_get('xcache.cacher') == 'On' )) {
	$php_properties['ByteCode Cache'] = array(
		'fitness' => tra('good'),
		'setting' => 'xCache',
		'message' => tra('xCache is being used as the ByteCode Cache, which increases performance if correctly configured. See Admin->Performance in the Tiki for more details.')
	);
} elseif (function_exists('opcache_get_configuration') && (ini_get('opcache.enable') == 1 || ini_get('opcache.enable') == '1')) {
	$message = tra('OPcache is being used as the ByteCode Cache, which increases performance if correctly configured. See Admin->Performance in the Tiki for more details.');
	$fitness = tra('good');
	if (! checkOPCacheCompatibility()) {
		$message = tra('Some PHP versions may exhibit randomly issues with the OpCache leading to the server starting to fail to serve all PHP requests, your PHP version seems to
		 be affected, despite the performance penalty, we would recommend disabling the OpCache if you experience random crashes.');
		$fitness = tra('unsure');
	}
	$php_properties['ByteCode Cache'] = array(
		'fitness' => $fitness,
		'setting' => 'OPcache',
		'message' => $message
	);
} elseif (function_exists('wincache_fcache_fileinfo')) {
	// Determine if version 1 or 2 is used. Version 2 does not support ocache

	if (function_exists('wincache_ocache_fileinfo')) {
		// Wincache version 1
		if (ini_get('wincache.ocenabled') == '1') {
			$sapi_type = php_sapi_name();
			if ($sapi_type == 'cgi-fcgi') {
				$php_properties['ByteCode Cache'] = array(
					'fitness' => tra('good'),
					'setting' => 'WinCache',
					'message' => tra('WinCache is being used as the ByteCode Cache, which increases performance if correctly configured. See Admin->Performance in the Tiki for more details.')
				);
			} else {
				$php_properties['ByteCode Cache'] = array(
					'fitness' => tra('unsure'),
					'setting' => 'WinCache',
					'message' => tra('WinCache is being used as the ByteCode Cache, but the required CGI/FastCGI server API is apparently not being used.')
				);
			}
		} else {
			no_cache_found();
		}
	} else {
		// Wincache version 2 or higher
		if (ini_get('wincache.fcenabled') == '1') {
			$sapi_type = php_sapi_name();
			if ($sapi_type == 'cgi-fcgi') {
				$php_properties['ByteCode Cache'] = array(
					'fitness' => tra('info'),
					'setting' => 'WinCache',
					'message' => tra('WinCache version 2 or higher is being used as the FileCache. It does not support a ByteCode Cache.') . ' ' . tra('It is recommended to use Zend opcode cache as the ByteCode Cache.')
				);
			} else {
				$php_properties['ByteCode Cache'] = array(
					'fitness' => tra('unsure'),
					'setting' => 'WinCache',
					'message' => tra('WinCache version 2 or higher is being used as the FileCache, but the required CGI/FastCGI server API is apparently not being used.') . ' ' . tra('It is recommended to use Zend opcode cache as the ByteCode Cache.')
				);
			}
		} else {
			no_cache_found();
		}
	}
} else {
	no_cache_found();
}


// memory_limit
$memory_limit = ini_get('memory_limit');
$s = trim($memory_limit);
$last = strtolower(substr($s, -1));
$s = substr($s, 0, -1);
switch ($last) {
	case 'g':
		$s *= 1024;
		// no break
	case 'm':
		$s *= 1024;
		// no break
	case 'k':
		$s *= 1024;
}
if ($s >= 160 * 1024 * 1024) {
	$php_properties['memory_limit'] = array(
		'fitness' => tra('good'),
		'setting' => $memory_limit,
		'message' => tra('The memory_limit is at') . ' ' . $memory_limit . '. ' . tra('This is known to support smooth functioning even for bigger sites.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} elseif ($s < 160 * 1024 * 1024 && $s > 127 * 1024 * 1024) {
	$php_properties['memory_limit'] = array(
		'fitness' => tra('unsure') ,
		'setting' => $memory_limit,
		'message' => tra('The memory_limit is at') . ' ' . $memory_limit . '. ' . tra('This will normally work, but the site might run into problems when it grows.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} elseif ($s == -1) {
	$php_properties['memory_limit'] = array(
		'fitness' => tra('unsure') ,
		'setting' => $memory_limit,
		'message' => tra("The memory_limit is unlimited. This is not necessarily bad, but it's a good idea to limit this on productions servers in order to eliminate unexpectedly greedy scripts.") . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} else {
	$php_properties['memory_limit'] = array(
		'fitness' => tra('bad'),
		'setting' => $memory_limit,
		'message' => tra('Your memory_limit is at') . ' ' . $memory_limit . '. ' . tra('This is known to cause issues! Ther memory_limit should be increased to at least 128M, which is the PHP default.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
}

// session.save_handler
$s = ini_get('session.save_handler');
if ($s != 'files') {
	$php_properties['session.save_handler'] = array(
		'fitness' => tra('unsure'),
		'setting' => $s,
		'message' => tra('The session.save_handler should be set to \'files\'.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} else {
	$php_properties['session.save_handler'] = array(
		'fitness' => tra('good'),
		'setting' => $s,
		'message' => tra('Well set! The default setting of \'files\' is recommended for Tiki.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
}

// session.save_path
$s = ini_get('session.save_path');
if ($php_properties['session.save_handler']['setting'] == 'files') {
	if (empty($s) || ! is_writable($s)) {
		$php_properties['session.save_path'] = array(
			'fitness' => tra('bad'),
			'setting' => $s,
			'message' => tra('The session.save_path must writable.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
		);
	} else {
		$php_properties['session.save_path'] = array(
			'fitness' => tra('good'),
			'setting' => $s,
			'message' => tra('The session.save_path is writable.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
		);
	}
} else {
	if (empty($s) || ! is_writable($s)) {
		$php_properties['session.save_path'] = array(
			'fitness' => tra('unsure'),
			'setting' => $s,
			'message' => tra('If you would be using the recommended session.save_handler setting of \'files\', the session.save_path would have to be writable. Currently it is not.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
		);
	} else {
		$php_properties['session.save_path'] = array(
			'fitness' => tra('info'),
			'setting' => $s,
			'message' => tra('The session.save_path is writable.') . tra('It doesn\'t matter though, since your session.save_handler is not set to \'files\'.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
		);
	}
}

$s = ini_get('session.gc_probability');
$php_properties['session.gc_probability'] = array(
	'fitness' => tra('info'),
	'setting' => $s,
	'message' => tra('In conjunction with gc_divisor is used to manage probability that the gc (garbage collection) routine is started.')
);

$s = ini_get('session.gc_divisor');
$php_properties['session.gc_divisor'] = array(
	'fitness' => tra('info'),
	'setting' => $s,
	'message' => tra('Coupled with session.gc_probability defines the probability that the gc (garbage collection) process is started on every session initialization. The probability is calculated by using gc_probability/gc_divisor, e.g. 1/100 means there is a 1% chance that the GC process starts on each request.')
);

$s = ini_get('session.gc_maxlifetime');
$php_properties['session.gc_maxlifetime'] = array(
	'fitness' => tra('info'),
	'setting' => $s . 's',
	'message' => tra('Specifies the number of seconds after which data will be seen as \'garbage\' and potentially cleaned up. Garbage collection may occur during session start.')
);

// test session work
@session_start();

if (empty($_SESSION['tiki-check'])) {
	$php_properties['session'] = array(
		'fitness' => tra('unsure'),
		'setting' => tra('empty'),
		'message' => tra('The session is empty. Try reloading the page and, if this message is displayed again, there may be a problem with the server setup.')
	);
	$_SESSION['tiki-check'] = 1;
} else {
	$php_properties['session'] = array(
		'fitness' => tra('good'),
		'setting' => 'ok',
		'message' => tra('This appears to work.')
	);
}

// zlib.output_compression
$s = ini_get('zlib.output_compression');
if ($s) {
	$php_properties['zlib.output_compression'] = array(
		'fitness' => tra('info'),
		'setting' => 'On',
		'message' => tra('zlib output compression is turned on. This saves bandwidth. On the other hand, turning it off would reduce CPU usage. The appropriate choice can be made for this Tiki.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} else {
	$php_properties['zlib.output_compression'] = array(
		'fitness' => tra('info'),
		'setting' => 'Off',
		'message' => tra('zlib output compression is turned off. This reduces CPU usage. On the other hand, turning it on would save bandwidth. The appropriate choice can be made for this Tiki.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
}

// default_charset
$s = ini_get('default_charset');
if (strtolower($s) == 'utf-8') {
	$php_properties['default_charset'] = array(
		'fitness' => tra('good'),
		'setting' => $s,
		'message' => tra('Correctly set! Tiki is fully UTF-8 and so should be this installation.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} else {
	$php_properties['default_charset'] = array(
		'fitness' => tra('unsure'),
		'setting' => $s,
		'message' => tra('default_charset should be UTF-8 as Tiki is fully UTF-8. Please check the php.ini file.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
}

// date.timezone
$s = ini_get('date.timezone');
if (empty($s)) {
	$php_properties['date.timezone'] = array(
		'fitness' => tra('unsure'),
		'setting' => $s,
		'message' => tra('No time zone is set! While there are a number of fallbacks in PHP to determine the time zone, the only reliable solution is to set it explicitly in php.ini! Please check the value of date.timezone in php.ini.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} else {
	$php_properties['date.timezone'] = array(
		'fitness' => tra('good'),
		'setting' => $s,
		'message' => tra('Well done! Having a time zone set protects the site from related errors.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
}

// file_uploads
$s = ini_get('file_uploads');
if ($s) {
	$php_properties['file_uploads'] = array(
		'fitness' => tra('good'),
		'setting' => 'On',
		'message' => tra('Files can be uploaded to Tiki.')
	);
} else {
	$php_properties['file_uploads'] = array(
		'fitness' => tra('bad'),
		'setting' => 'Off',
		'message' => tra('Files cannot be uploaded to Tiki.')
	);
}

// max_execution_time
$s = ini_get('max_execution_time');
if ($s >= 30 && $s <= 90) {
	$php_properties['max_execution_time'] = array(
		'fitness' => tra('good'),
		'setting' => $s . 's',
		'message' => tra('The max_execution_time is at') . ' ' . $s . '. ' . tra('This is a good value for production sites. If timeouts are experienced (such as when performing admin functions) this may need to be increased nevertheless.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} elseif ($s == -1 || $s == 0) {
	$php_properties['max_execution_time'] = array(
		'fitness' => tra('unsure'),
		'setting' => $s . 's',
		'message' => tra('The max_execution_time is unlimited.') . ' ' . tra('This is not necessarily bad, but it\'s a good idea to limit this time on productions servers in order to eliminate unexpectedly long running scripts.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} elseif ($s > 90) {
	$php_properties['max_execution_time'] = array(
		'fitness' => tra('unsure'),
		'setting' => $s . 's',
		'message' => tra('The max_execution_time is at') . ' ' . $s . '. ' . tra('This is not necessarily bad, but it\'s a good idea to limit this time on productions servers in order to eliminate unexpectedly long running scripts.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} else {
	$php_properties['max_execution_time'] = array(
		'fitness' => tra('bad'),
		'setting' => $s . 's',
		'message' => tra('The max_execution_time is at') . ' ' . $s . '. ' . tra('It is likely that some scripts, such as admin functions, will not finish in this time! The max_execution_time should be incresed to at least 30s.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
}

// max_input_time
$s = ini_get('max_input_time');
if ($s >= 30 && $s <= 90) {
	$php_properties['max_input_time'] = array(
		'fitness' => tra('good'),
		'setting' => $s . 's',
		'message' => tra('The max_input_time is at') . ' ' . $s . '. ' . tra('This is a good value for production sites. If timeouts are experienced (such as when performing admin functions) this may need to be increased nevertheless.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} elseif ($s == -1 || $s == 0) {
	$php_properties['max_input_time'] = array(
		'fitness' => tra('unsure'),
		'setting' => $s . 's',
		'message' => tra('The max_input_time is unlimited.') . ' ' . tra('This is not necessarily bad, but it\'s a good idea to limit this time on productions servers in order to eliminate unexpectedly long running scripts.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} elseif ($s > 90) {
	$php_properties['max_input_time'] = array(
		'fitness' => tra('unsure'),
		'setting' => $s . 's',
		'message' => tra('The max_input_time is at') . ' ' . $s . '. ' . tra('This is not necessarily bad, but it\'s a good idea to limit this time on productions servers in order to eliminate unexpectedly long running scripts.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} else {
	$php_properties['max_input_time'] = array(
		'fitness' => tra('bad'),
		'setting' => $s . 's',
		'message' => tra('The max_input_time is at') . ' ' . $s . '. ' . tra('It is likely that some scripts, such as admin functions, will not finish in this time! The max_input_time should be increased to at least 30 seconds.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
}
// max_file_uploads
$max_file_uploads = ini_get('max_file_uploads');
if ($max_file_uploads) {
	$php_properties['max_file_uploads'] = array(
		'fitness' => tra('info'),
		'setting' => $max_file_uploads,
		'message' => tra('The max_file_uploads is at') . ' ' . $max_file_uploads . '. ' . tra('This is the maximum number of files allowed to be uploaded simultaneously.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} else {
	$php_properties['max_file_uploads'] = array(
		'fitness' => tra('info'),
		'setting' => 'Not Available',
		'message' => tra('The maximum number of files allowed to be uploaded is not available')
	);
}
// upload_max_filesize
$upload_max_filesize = ini_get('upload_max_filesize');
$s = trim($upload_max_filesize);
$last = strtolower(substr($s, -1));
$s = substr($s, 0, -1);
switch ($last) {
	case 'g':
		$s *= 1024;
		// no break
	case 'm':
		$s *= 1024;
		// no break
	case 'k':
		$s *= 1024;
}
if ($s >= 8 * 1024 * 1024) {
	$php_properties['upload_max_filesize'] = array(
		'fitness' => tra('good'),
		'setting' => $upload_max_filesize,
		'message' => tra('The upload_max_filesize is at') . ' ' . $upload_max_filesize . '. ' . tra('Quite large files can be uploaded, but keep in mind to set the script timeouts accordingly.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} elseif ($s == 0) {
	$php_properties['upload_max_filesize'] = array(
		'fitness' => tra('unsure'),
		'setting' => $upload_max_filesize,
		'message' => tra('The upload_max_filesize is at') . ' ' . $upload_max_filesize . '. ' . tra('Upload size is unlimited and this not advised. A user could mistakenly upload a very large file which could fill up the disk. This value should be set to accommodate the realistic needs of the site.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} else {
	$php_properties['upload_max_filesize'] = array(
		'fitness' => tra('unsure'),
		'setting' => $upload_max_filesize,
		'message' => tra('The upload_max_filesize is at') . ' ' . $upload_max_filesize . '. ' . tra('This is not a bad amount, but be sure the level is high enough to accommodate the needs of the site.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
}

// post_max_size
$post_max_size = ini_get('post_max_size');
$s = trim($post_max_size);
$last = strtolower(substr($s, -1));
$s = substr($s, 0, -1);
switch ($last) {
	case 'g':
		$s *= 1024;
		// no break
	case 'm':
		$s *= 1024;
		// no break
	case 'k':
		$s *= 1024;
}
if ($s >= 8 * 1024 * 1024) {
	$php_properties['post_max_size'] = array(
		'fitness' => tra('good'),
		'setting' => $post_max_size,
		'message' => tra('The post_max_size is at') . ' ' . $post_max_size . '. ' . tra('Quite large files can be uploaded, but keep in mind to set the script timeouts accordingly.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} else {
	$php_properties['post_max_size'] = array(
		'fitness' => tra('unsure'),
		'setting' => $post_max_size,
		'message' => tra('The post_max_size is at') . ' ' . $post_max_size . '. ' . tra('This is not a bad amount, but be sure the level is high enough to accommodate the needs of the site.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
}

// PHP Extensions
// fileinfo
$s = extension_loaded('fileinfo');
if ($s) {
	$php_properties['fileinfo'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => tra("The fileinfo extension is needed for the 'Validate uploaded file content' preference.")
	);
} else {
	$php_properties['fileinfo'] = array(
		'fitness' => tra('unsure'),
		'setting' => 'Not available',
		'message' => tra("The fileinfo extension is needed for the 'Validate uploaded file content' preference.")
	);
}

// intl
$s = extension_loaded('intl');
if ($s) {
	$php_properties['intl'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => tra("The intl extension is required for Tiki 15 and newer.")
	);
} else {
	$php_properties['intl'] = array(
		'fitness' => tra('unsure'),
		'setting' => 'Not available',
		'message' => tra("intl extension is preferred for Tiki 15 and newer. Because is not available, the filters for text will not be able to detect the language and will use a generic range of characters as letters.")
	);
}

// GD
$s = extension_loaded('gd');
if ($s && function_exists('gd_info')) {
	$gd_info = gd_info();
	$im = $ft = null;
	if (function_exists('imagecreate')) {
		$im = @imagecreate(110, 20);
	}
	if (function_exists('imageftbbox')) {
		$ft = @imageftbbox(12, 0, $font, 'test');
	}
	if ($im && $ft) {
		$php_properties['gd'] = array(
			'fitness' => tra('good'),
			'setting' => $gd_info['GD Version'],
			'message' => tra('The GD extension is needed for manipulation of images and for CAPTCHA images.')
		);
		imagedestroy($im);
	} elseif ($im) {
		$php_properties['gd'] = array(
				'fitness' => tra('unsure'),
				'setting' => $gd_info['GD Version'],
				'message' => tra('The GD extension is loaded, and Tiki can create images, but the FreeType extension is needed for CAPTCHA text generation.')
			);
			imagedestroy($im);
	} else {
		$php_properties['gd'] = array(
			'fitness' => tra('unsure'),
			'setting' => 'Dysfunctional',
			'message' => tra('The GD extension is loaded, but Tiki is unable to create images. Please check your GD library configuration.')
		);
	}
} else {
	$php_properties['gd'] = array(
		'fitness' => tra('bad'),
		'setting' => 'Not available',
		'message' => tra('The GD extension is needed for manipulation of images and for CAPTCHA images.')
	);
}

// Image Magick
$s = class_exists('Imagick');
if ($s) {
	$image = new Imagick();
	$image->newImage(100, 100, new ImagickPixel('red'));
	if ($image) {
		$php_properties['Image Magick'] = array(
			'fitness' => tra('good'),
			'setting' => 'Available',
			'message' => tra('ImageMagick is used as a fallback in case GD is not available.')
		);
		$image->destroy();
	} else {
		$php_properties['Image Magick'] = array(
			'fitness' => tra('unsure'),
			'setting' => 'Dysfunctional',
			'message' => tra('ImageMagick is used as a fallback in case GD is not available.') . tra('ImageMagick is available, but unable to create images. Please check your ImageMagick configuration.')
			);
	}
} else {
	$php_properties['Image Magick'] = array(
		'fitness' => tra('info'),
		'setting' => 'Not Available',
		'message' => tra('ImageMagick is used as a fallback in case GD is not available.')
		);
}

// mbstring
$s = extension_loaded('mbstring');
if ($s) {
	$func_overload = ini_get('mbstring.func_overload');
	if ($func_overload == 0 && function_exists('mb_split')) {
		$php_properties['mbstring'] = array(
			'fitness' => tra('good'),
			'setting' => 'Loaded',
			'message' => tra('mbstring extension is needed for an UTF-8 compatible lower case filter, in the admin search for example.')
		);
	} elseif ($func_overload != 0) {
		$php_properties['mbstring'] = array(
			'fitness' => tra('unsure'),
			'setting' => 'Badly configured',
			'message' => tra('mbstring extension is loaded, but mbstring.func_overload = ' . ' ' . $func_overload . '.' . ' ' . 'Tiki only works with mbstring.func_overload = 0. Please check the php.ini file.')
		);
	} else {
		$php_properties['mbstring'] = array(
			'fitness' => tra('bad'),
			'setting' => 'Badly installed',
			'message' => tra('mbstring extension is loaded, but missing important functions such as mb_split(). Reinstall it with --enable-mbregex or ask your a server administrator to do it.')
		);
	}
} else {
	$php_properties['mbstring'] = array(
		'fitness' => tra('bad'),
		'setting' => 'Not available',
		'message' => tra('mbstring extension is needed for an UTF-8 compatible lower case filter.')
	);
}

// calendar
$s = extension_loaded('calendar');
if ($s) {
	$php_properties['calendar'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => tra('calendar extension is needed by Tiki.')
	);
} else {
	$php_properties['calendar'] = array(
		'fitness' => tra('bad'),
		'setting' => 'Not available',
		'message' => tra('calendar extension is needed by Tiki.') . ' ' . tra('The calendar feature of Tiki will not function without this.')
	);
}

// ctype
$s = extension_loaded('ctype');
if ($s) {
	$php_properties['ctype'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => tra('ctype extension is needed by Tiki.')
	);
} else {
	$php_properties['ctype'] = array(
		'fitness' => tra('bad'),
		'setting' => 'Not available',
		'message' => tra('ctype extension is needed by Tiki.')
	);
}

// libxml
$s = extension_loaded('libxml');
if ($s) {
	$php_properties['libxml'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => tra('This extension is needed for the dom extension (see below).')
	);
} else {
	$php_properties['libxml'] = array(
		'fitness' => tra('bad'),
		'setting' => 'Not available',
		'message' => tra('This extension is needed for the dom extension (see below).')
	);
}

// dom (depends on libxml)
$s = extension_loaded('dom');
if ($s) {
	$php_properties['dom'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => tra('This extension is needed by Tiki')
	);
} else {
	$php_properties['dom'] = array(
		'fitness' => tra('bad'),
		'setting' => 'Not available',
		'message' => tra('This extension is needed by Tiki')
	);
}

$s = extension_loaded('ldap');
if ($s) {
	$php_properties['LDAP'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => tra('This extension is needed to connect Tiki to an LDAP server. More info at: http://doc.tiki.org/LDAP ')
	);
} else {
	$php_properties['LDAP'] = array(
		'fitness' => tra('info'),
		'setting' => 'Not available',
		'message' => tra('Tiki will not be able to connect to an LDAP server as the needed PHP extension is missing. More info at: http://doc.tiki.org/LDAP')
	);
}

$s = extension_loaded('memcached');
if ($s) {
	$php_properties['memcached'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => tra('This extension can be used to speed up Tiki by saving sessions as well as wiki and forum data on a memcached server.')
	);
} else {
	$php_properties['memcached'] = array(
		'fitness' => tra('info'),
		'setting' => 'Not available',
		'message' => tra('This extension can be used to speed up Tiki by saving sessions as well as wiki and forum data on a memcached server.')
	);
}

$s = extension_loaded('redis');
if ($s) {
	$php_properties['redis'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => tra('This extension can be used to speed up Tiki by saving wiki and forum data on a redis server.')
	);
} else {
	$php_properties['redis'] = array(
		'fitness' => tra('info'),
		'setting' => 'Not available',
		'message' => tra('This extension can be used to speed up Tiki by saving wiki and forum data on a redis server.')
	);
}

$s = extension_loaded('ssh2');
if ($s) {
	$php_properties['SSH2'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => tra('This extension is needed for the show.tiki.org tracker field type, up to Tiki 17.')
	);
} else {
	$php_properties['SSH2'] = array(
		'fitness' => tra('info'),
		'setting' => 'Not available',
		'message' => tra('This extension is needed for the show.tiki.org tracker field type, up to Tiki 17.')
	);
}

$s = extension_loaded('curl');
if ($s) {
	$php_properties['curl'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => tra('This extension is required for H5P.')
	);
} else {
	$php_properties['curl'] = array(
		'fitness' => tra('bad'),
		'setting' => 'Not available',
		'message' => tra('This extension is required for H5P.')
	);
}

$s = extension_loaded('json');
if ($s) {
	$php_properties['json'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => tra('This extension is required for many features in Tiki.')
	);
} else {
	$php_properties['json'] = array(
		'fitness' => tra('bad'),
		'setting' => 'Not available',
		'message' => tra('This extension is required for many features in Tiki.')
	);
}

/*
*	If TortoiseSVN 1.7 is used, it uses an sqlite database to store the SVN info. sqlite3 extention needed to read svn info.
*/
if (is_file('.svn/wc.db')) {
	// It's an TortoiseSVN 1.7+ installation
	$s = extension_loaded('sqlite3');
	if ($s) {
		$php_properties['sqlite3'] = array(
			'fitness' => tra('good'),
			'setting' => 'Loaded',
			'message' => tra('This extension is used to interpret SVN information for TortoiseSVN 1.7 or higher.')
			);
	} else {
		$php_properties['sqlite3'] = array(
			'fitness' => tra('unsure'),
			'setting' => 'Not available',
			'message' => tra('This extension is used to interpret SVN information for TortoiseSVN 1.7 or higher.')
			);
	}
}


$s = extension_loaded('openssl');
$msg = tra('Enable safe, encrypted storage of data such as passwords. Required for the User Encryption feature and improves encryption in other features, when available.');
if ($s) {
	$php_properties['openssl'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => $msg
	);
} else {
	$php_properties['openssl'] = array(
		'fitness' => tra('unsure'),
		'setting' => 'Not available',
		'message' => $msg
	);
}


$s = extension_loaded('mcrypt');
$msg = tra('MCrypt is abandonware and is being phased out. Starting in version 18, Tiki uses OpenSSL where it previously used MCrypt, except perhaps via third-party libraries.');
if (! $standalone) {
	$msg .= ' ' . tra('Tiki still uses MCrypt to decrypt user data encrypted with MCrypt, when converting that data to OpenSSL.') . ' ' . tra('Please check the \'User Data Encryption\' section to see if there is user data encrypted with MCrypt.');

	//User Data Encryption MCrypt
	$usersWithMCrypt = check_userPreferencesMCrypt();
}
if ($s) {
	$php_properties['mcrypt'] = array(
		'fitness' => tra('info'),
		'setting' => 'Loaded',
		'message' => $msg
	);
} else {
	$php_properties['mcrypt'] = array(
		'fitness' => tra('info'),
		'setting' => 'Not available',
		'message' => $msg
	);
}


if (! $standalone) {
	// check Zend captcha will work which depends on \Zend\Math\Rand
	$captcha = new Zend\Captcha\Dumb;
	$math_random = array(
		'fitness' => tra('good'),
		'setting' => 'Available',
		'message' => tra('Ability to generate random numbers, useful for example for CAPTCHA and other security features.'),
	);
	try {
		$captchaId = $captcha->getId();    // simple test for missing random generator
	} catch (Exception $e) {
		$math_random['fitness'] = tra('unsure');
		$math_random['setting'] = 'Not available';
	}
	$php_properties['\Laminas\Math\Rand'] = $math_random;
}


$s = extension_loaded('iconv');
$msg = tra('This extension is required and used frequently in validation functions invoked within Zend Framework.');
if ($s) {
	$php_properties['iconv'] = array(
		'fitness' => tra('good'),
		'setting' => 'Loaded',
		'message' => $msg
	);
} else {
	$php_properties['iconv'] = array(
		'fitness' => tra('bad'),
		'setting' => 'Not available',
		'message' => $msg
	);
}

// Check for existence of eval()
// eval() is a language construct and not a function
// so function_exists() doesn't work
$s = eval('return 42;');
if ($s == 42) {
	$php_properties['eval()'] = array(
		'fitness' => tra('good'),
		'setting' => 'Available',
		'message' => tra('The eval() function is required by the Smarty templating engine.')
	);
} else {
	$php_properties['eval()'] = array(
		'fitness' => tra('bad'),
		'setting' => 'Not available',
		'message' => tra('The eval() function is required by the Smarty templating engine.') . ' ' . tra('You will get "Please contact support about" messages instead of modules. eval() is most probably disabled via Suhosin.')
	);
}

// Zip Archive class
$s = class_exists('ZipArchive');
if ($s) {
	$php_properties['ZipArchive class'] = array(
		'fitness' => tra('good'),
		'setting' => 'Available',
		'message' => tra('The ZipArchive class is needed for features such as XML Wiki Import/Export and PluginArchiveBuilder.')
		);
} else {
	$php_properties['ZipArchive class'] = array(
		'fitness' => tra('unsure'),
		'setting' => 'Not Available',
		'message' => tra('The ZipArchive class is needed for features such as XML Wiki Import/Export and PluginArchiveBuilder.')
		);
}

// DateTime class
$s = class_exists('DateTime');
if ($s) {
	$php_properties['DateTime class'] = array(
		'fitness' => tra('good'),
		'setting' => 'Available',
		'message' => tra('The DateTime class is needed for the WebDAV feature.')
		);
} else {
	$php_properties['DateTime class'] = array(
		'fitness' => tra('unsure'),
		'setting' => 'Not Available',
		'message' => tra('The DateTime class is needed for the WebDAV feature.')
		);
}

// Xdebug
$has_xdebug = function_exists('xdebug_get_code_coverage') && is_array(xdebug_get_code_coverage());
if ($has_xdebug) {
	$php_properties['Xdebug'] = array(
		'fitness' => tra('info'),
		'setting' => 'Loaded',
		'message' => tra('Xdebug can be very handy for a development server, but it might be better to disable it when on a production server.')
	);
} else {
	$php_properties['Xdebug'] = array(
		'fitness' => tra('info'),
		'setting' => 'Not Available',
		'message' => tra('Xdebug can be very handy for a development server, but it might be better to disable it when on a production server.')
	);
}

// Get MySQL properties and check them
$mysql_properties = false;
$mysql_variables = false;
if ($connection || ! $standalone) {
	// MySQL version
	$query = 'SELECT VERSION();';
	$result = query($query, $connection);
	$mysql_version = $result[0]['VERSION()'];
	$s = version_compare($mysql_version, '5.5.3', '>=');
	if ($s == true) {
		$mysql_properties['Version'] = array(
			'fitness' => tra('good'),
			'setting' => $mysql_version,
			'message' => tra('Tiki requires MariaDB >= 5.5 or MySQL >= 5.5.3')
		);
	} else {
		$mysql_properties['Version'] = array(
			'fitness' => tra('bad'),
			'setting' => $mysql_version,
			'message' => tra('Tiki requires MariaDB >= 5.5 or MySQL >= 5.5.3')
		);
	}

	// max_allowed_packet
	$query = "SHOW VARIABLES LIKE 'max_allowed_packet'";
	$result = query($query, $connection);
	$s = $result[0]['Value'];
	$max_allowed_packet = $s / 1024 / 1024;
	if ($s >= 8 * 1024 * 1024) {
		$mysql_properties['max_allowed_packet'] = array(
			'fitness' => tra('good'),
			'setting' => $max_allowed_packet . 'M',
			'message' => tra('The max_allowed_packet setting is at') . ' ' . $max_allowed_packet . 'M. ' . tra('Quite large files can be uploaded, but keep in mind to set the script timeouts accordingly.') . ' ' . tra('This limits the size of binary files that can be uploaded to Tiki, when storing files in the database. Please see: <a href="http://doc.tiki.org/File+Storage">file storage</a>.')
		);
	} else {
		$mysql_properties['max_allowed_packet'] = array(
			'fitness' => tra('unsure'),
			'setting' => $max_allowed_packet . 'M',
			'message' => tra('The max_allowed_packet setting is at') . ' ' . $max_allowed_packet . 'M. ' . tra('This is not a bad amount, but be sure the level is high enough to accommodate the needs of the site.') . ' ' . tra('This limits the size of binary files that can be uploaded to Tiki, when storing files in the database. Please see: <a href="http://doc.tiki.org/File+Storage">file storage</a>.')
		);
	}

	// UTF-8 MB4 test (required for Tiki19+)
	$query = "SELECT COUNT(*) FROM `information_schema`.`character_sets` WHERE `character_set_name` = 'utf8mb4';";
	$result = query($query, $connection);
	if (! empty($result[0]['COUNT(*)'])) {
		$mysql_properties['utf8mb4'] = array(
			'fitness' => tra('good'),
			'setting' => 'available',
			'message' => tra('Your database supports the utf8mb4 character set required in Tiki19 and above.')
		);
	} else {
		$mysql_properties['utf8mb4'] = array(
			'fitness' => tra('bad'),
			'setting' => 'not available',
			'message' => tra('Your database does not support the utf8mb4 character set required in Tiki19 and above. You need to upgrade your mysql or mariadb installation.')
		);
	}

	// UTF-8 Charset
	// Tiki communication is done using UTF-8 MB4 (required for Tiki19+)
	$charset_types = "client connection database results server system";
	foreach (explode(' ', $charset_types) as $type) {
		$query = "SHOW VARIABLES LIKE 'character_set_" . $type . "';";
		$result = query($query, $connection);
		foreach ($result as $value) {
			if ($value['Value'] == 'utf8mb4') {
				$mysql_properties[$value['Variable_name']] = array(
					'fitness' => tra('good'),
					'setting' => $value['Value'],
					'message' => tra('Tiki is fully utf8mb4 and so should be every part of the stack.')
				);
			} else {
				$mysql_properties[$value['Variable_name']] = array(
					'fitness' => tra('unsure'),
					'setting' => $value['Value'],
					'message' => tra('On a fresh install everything should be set to utf8mb4 to avoid unexpected results. For further information please see <a href="http://doc.tiki.org/Understanding+Encoding">Understanding Encoding</a>.')
				);
			}
		}
	}
	// UTF-8 is correct for character_set_system
	// Because mysql does not allow any config to change this value, and character_set_system is overwritten by the other character_set_* variables anyway. They may change this default in later versions.
	$query = "SHOW VARIABLES LIKE 'character_set_system';";
	$result = query($query, $connection);
	foreach ($result as $value) {
		if (substr($value['Value'], 0, 4) == 'utf8') {
			$mysql_properties[$value['Variable_name']] = array(
				'fitness' => tra('good'),
				'setting' => $value['Value'],
				'message' => tra('Tiki is fully utf8mb4 but some database underlying variables are set to utf8 by the database engine and cannot be modified.')
			);
		} else {
			$mysql_properties[$value['Variable_name']] = array(
				'fitness' => tra('unsure'),
				'setting' => $value['Value'],
				'message' => tra('On a fresh install everything should be set to utf8mb4 or utf8 to avoid unexpected results. For further information please see <a href="http://doc.tiki.org/Understanding+Encoding">Understanding Encoding</a>.')
			);
		}
	}
	// UTF-8 Collation
	$collation_types = "connection database server";
	foreach (explode(' ', $collation_types) as $type) {
		$query = "SHOW VARIABLES LIKE 'collation_" . $type . "';";
		$result = query($query, $connection);
		foreach ($result as $value) {
			if (substr($value['Value'], 0, 7) == 'utf8mb4') {
				$mysql_properties[$value['Variable_name']] = array(
					'fitness' => tra('good'),
					'setting' => $value['Value'],
					'message' => tra('Tiki is fully utf8mb4 and so should be every part of the stack. utf8mb4_unicode_ci is the default collation for Tiki.')
				);
			} else {
				$mysql_properties[$value['Variable_name']] = array(
					'fitness' => tra('unsure'),
					'setting' => $value['Value'],
					'message' => tra('On a fresh install everything should be set to utf8mb4 to avoid unexpected results. utf8mb4_unicode_ci is the default collation for Tiki. For further information please see <a href="http://doc.tiki.org/Understanding+Encoding">Understanding Encoding</a>.')
				);
			}
		}
	}

	// slow_query_log
	$query = "SHOW VARIABLES LIKE 'slow_query_log'";
	$result = query($query, $connection);
	$s = $result[0]['Value'];
	if ($s == 'OFF') {
		$mysql_properties['slow_query_log'] = array(
			'fitness' => tra('info'),
			'setting' => $s,
			'message' => tra('MySQL doesn\'t log slow queries. If performance issues are noticed, this could be enabled, but keep in mind that the logging itself slows MySQL down.')
		);
	} else {
		$mysql_properties['slow_query_log'] = array(
			'fitness' => tra('info'),
			'setting' => $s,
			'message' => tra('MySQL logs slow queries. If no performance issues are noticed, this should be disabled on a production site as it slows MySQL down.')
		);
	}

	// MySQL SSL
	$query = 'show variables like "have_ssl";';
	$result = query($query, $connection);
	if (empty($result)) {
		$query = 'show variables like "have_openssl";';
		$result = query($query, $connection);
	}
	$haveMySQLSSL = false;
	if (! empty($result)) {
		$ssl = $result[0]['Value'];
		$haveMySQLSSL = $ssl == 'YES';
	}
	$s = '';
	if ($haveMySQLSSL) {
		$query = 'show status like "Ssl_cipher";';
		$result = query($query, $connection);
		$isSSL = ! empty($result[0]['Value']);
	} else {
		$isSSL = false;
	}
	if ($isSSL) {
		$msg = tra('MySQL SSL connection is active');
		$s = tra('ON');
	} elseif ($haveMySQLSSL && ! $isSSL) {
		$msg = tra('MySQL connection is not encrypted');
		$s = tra('OFF');
	} else {
		$msg = tra('MySQL Server does not have SSL activated.');
		$s = 'OFF';
	}
	$fitness = tra('info');
	if ($s == tra('ON')) {
		$fitness = tra('good');
	}
	$mysql_properties['SSL connection'] = array(
		'fitness' => $fitness,
		'setting' => $s,
		'message' => $msg
	);

	// Strict mode
	$query = 'SELECT @@sql_mode as Value;';
	$result = query($query, $connection);
	$s = '';
	$msg = 'Unable to query strict mode';
	if (! empty($result)) {
		$sql_mode = $result[0]['Value'];
		$modes = explode(',', $sql_mode);

		if (in_array('STRICT_ALL_TABLES', $modes)) {
			$s = 'STRICT_ALL_TABLES';
		}
		if (in_array('STRICT_TRANS_TABLES', $modes)) {
			if (! empty($s)) {
				$s .= ',';
			}
			$s .= 'STRICT_TRANS_TABLES';
		}

		if (! empty($s)) {
			$msg = 'MySQL is using strict mode';
		} else {
			$msg = 'MySQL is not using strict mode.';
		}
	}
	$mysql_properties['Strict Mode'] = array(
		'fitness' => tra('info'),
		'setting' => $s,
		'message' => $msg
	);

	// MySQL Variables
	$query = "SHOW VARIABLES;";
	$result = query($query, $connection);
	foreach ($result as $value) {
		$mysql_variables[$value['Variable_name']] = array('value' => $value['Value']);
	}

	if (! $standalone) {
		$mysql_crashed_tables = array();
		// This should give all crashed tables (MyISAM at least) - does need testing though !!
		$query = 'SHOW TABLE STATUS WHERE engine IS NULL AND comment <> "VIEW";';
		$result = query($query, $connection);
		foreach ($result as $value) {
			$mysql_crashed_tables[$value['Name']] = array('Comment' => $value['Comment']);
		}
	}
}

// Apache properties

$apache_properties = false;
if (function_exists('apache_get_version')) {
	// Apache Modules
	$apache_modules = apache_get_modules();

	// mod_rewrite
	$s = false;
	$s = array_search('mod_rewrite', $apache_modules);
	if ($s) {
		$apache_properties['mod_rewrite'] = array(
			'setting' => 'Loaded',
			'fitness' => tra('good') ,
			'message' => tra('Tiki needs this module for Search Engine Friendly URLs via .htaccess. However, it can\'t be checked if this web server respects configurations made in .htaccess. For further information go to Admin->SefURL in your Tiki.')
		);
	} else {
		$apache_properties['mod_rewrite'] = array(
			'setting' => 'Not available',
			'fitness' => tra('unsure') ,
			'message' => tra('Tiki needs this module for Search Engine Friendly URLs. For further information go to Admin->SefURL in the Tiki.')
		);
	}

	if (! $standalone) {
		// work out if RewriteBase is set up properly
		global $url_path;
		$enabledFileName = '.htaccess';
		if (file_exists($enabledFileName)) {
			$enabledFile = fopen($enabledFileName, "r");
			$rewritebase = '/';
			while ($nextLine = fgets($enabledFile)) {
				if (preg_match('/^RewriteBase\s*(.*)$/', $nextLine, $m)) {
					$rewritebase = substr($m[1], -1) !== '/' ? $m[1] . '/' : $m[1];
					break;
				}
			}
			if ($url_path == $rewritebase) {
				$smarty->assign('rewritebaseSetting', $rewritebase);
				$apache_properties['RewriteBase'] = array(
					'setting' => $rewritebase,
					'fitness' => tra('good') ,
					'message' => tra('RewriteBase is set correctly in .htaccess. Search Engine Friendly URLs should work. Be aware, though, that this test can\'t checked if Apache really loads .htaccess.')
				);
			} else {
				$apache_properties['RewriteBase'] = array(
					'setting' => $rewritebase,
					'fitness' => tra('bad') ,
					'message' => tra('RewriteBase is not set correctly in .htaccess. Search Engine Friendly URLs are not going to work with this configuration. It should be set to "') . substr($url_path, 0, -1) . '".'
				);
			}
		} else {
			$apache_properties['RewriteBase'] = array(
				'setting' => tra('Not found'),
				'fitness' => tra('info') ,
				'message' => tra('The .htaccess file has not been activated, so this check cannot be  performed. To use Search Engine Friendly URLs, activate .htaccess by copying _htaccess into its place (or a symlink if supported by your Operating System). Then do this check again.')
			);
		}
	}

	if ($pos = strpos($_SERVER['REQUEST_URI'], 'tiki-check.php')) {
		$sef_test_protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) ? 'https://' : 'http://';
		$sef_test_base_url = $sef_test_protocol . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, $pos);
		$sef_test_ping_value = mt_rand();
		$sef_test_url = $sef_test_base_url . 'tiki-check?tiki-check-ping=' . $sef_test_ping_value;
		$sef_test_folder_created = false;
		$sef_test_folder_writable = true;
		if ($standalone) {
			$sef_test_path_current = __DIR__;
			$sef_test_dir_name = 'tiki-check-' . $sef_test_ping_value;
			$sef_test_folder = $sef_test_path_current . DIRECTORY_SEPARATOR . $sef_test_dir_name;
			if (is_writable($sef_test_path_current)&&! file_exists($sef_test_folder)) {
				if (mkdir($sef_test_folder)) {
					$sef_test_folder_created = true;
					copy(__FILE__, $sef_test_folder . DIRECTORY_SEPARATOR . 'tiki-check.php');
					file_put_contents($sef_test_folder . DIRECTORY_SEPARATOR . '.htaccess', "<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteRule tiki-check$ tiki-check.php [L]\n</IfModule>\n");
					$sef_test_url = $sef_test_base_url . $sef_test_dir_name . '/tiki-check?tiki-check-ping=' . $sef_test_ping_value;
				}
			} else {
				$sef_test_folder_writable = false;
			}
		}

		if (! $sef_test_folder_writable) {
			$apache_properties['SefURL Test'] = array(
			'setting' => tra('Not Working'),
			'fitness' => tra('info') ,
			'message' => tra('The automated test could not run. The required files could not be created  on the server to run the test. That may only mean that there were no permissions, but the Apache configuration should be checked. For further information go to Admin->SefURL in the Tiki.')
			);
		} else {
			$pong_value = get_content_from_url($sef_test_url);
			if ($pong_value != 'fail-no-request-done') {
				if ('pong:' . $sef_test_ping_value == $pong_value) {
					$apache_properties['SefURL Test'] = array(
						'setting' => tra('Working'),
						'fitness' => tra('good') ,
						'message' => tra('An automated test was done, and the server appears to be configured correctly to handle Search Engine Friendly URLs.')
					);
				} else {
					if (strncmp('fail-http-', $pong_value, 10) == 0) {
						$apache_return_code = substr($pong_value, 10);
						$apache_properties['SefURL Test'] = array(
							'setting' => tra('Not Working'),
							'fitness' => tra('info') ,
							'message' => sprintf(tra('An automated test was done and, based on the results, the server does not appear to be configured correctly to handle Search Engine Friendly URLs. The server returned an unexpected HTTP code: "%s". This automated test may fail due to the infrastructure setup, but the Apache configuration should be checked. For further information go to Admin->SefURL in your Tiki.'), $apache_return_code)
						);
					} else {
						$apache_properties['SefURL Test'] = array(
							'setting' => tra('Not Working'),
							'fitness' => tra('info') ,
							'message' => tra('An automated test was done and, based on the results, the server does not appear to be configured correctly to handle Search Engine Friendly URLs. This automated test may fail due to the infrastructure setup, but the Apache configuration should be checked. For further information go to Admin->SefURL in your Tiki.')
						);
					}
				}
			}
		}
		if ($sef_test_folder_created) {
			unlink($sef_test_folder . DIRECTORY_SEPARATOR . 'tiki-check.php');
			unlink($sef_test_folder . DIRECTORY_SEPARATOR . '.htaccess');
			rmdir($sef_test_folder);
		}
	}

	// mod_expires
	$s = false;
	$s = array_search('mod_expires', $apache_modules);
	if ($s) {
		$apache_properties['mod_expires'] = array(
			'setting' => 'Loaded',
			'fitness' => tra('good') ,
			'message' => tra('With this module, the HTTP Expires header can be set, which increases performance. It can\'t be checked, though, if mod_expires is configured correctly.')
		);
	} else {
		$apache_properties['mod_expires'] = array(
			'setting' => 'Not available',
			'fitness' => tra('unsure') ,
			'message' => tra('With this module, the HTTP Expires header can be set, which increases performance. Once it is installed, it still needs to be configured correctly.')
		);
	}

	// mod_deflate
	$s = false;
	$s = array_search('mod_deflate', $apache_modules);
	if ($s) {
		$apache_properties['mod_deflate'] = array(
			'setting' => 'Loaded',
			'fitness' => tra('good') ,
			'message' => tra('With this module, the data the webserver sends out can be compressed, which reduced data transfer amounts and increases performance. This test can\'t check, though, if mod_deflate is configured correctly.')
		);
	} else {
		$apache_properties['mod_deflate'] = array(
			'setting' => 'Not available',
			'fitness' => tra('unsure') ,
			'message' => tra('With this module, the data the webserver sends out can be compressed, which reduces data transfer amounts and increases performance. Once it is installed, it still needs to be configured correctly.')
		);
	}

	// mod_security
	$s = false;
	$s = array_search('mod_security', $apache_modules);
	if ($s) {
		$apache_properties['mod_security'] = array(
			'setting' => 'Loaded',
			'fitness' => tra('info') ,
			'message' => tra('This module can increase security of Tiki and therefore the server, but be aware that it is very tricky to configure correctly. A misconfiguration can lead to failed page saves or other hard to trace bugs.')
		);
	} else {
		$apache_properties['mod_security'] = array(
			'setting' => 'Not available',
			'fitness' => tra('info') ,
			'message' => tra('This module can increase security of Tiki and therefore the server, but be aware that it is very tricky to configure correctly. A misconfiguration can lead to failed page saves or other hard to trace bugs.')
		);
	}

	// Get /server-info, if available
	if (function_exists('curl_init') && function_exists('curl_exec')) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://localhost/server-info');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
		$apache_server_info = curl_exec($curl);
		if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$apache_server_info = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $apache_server_info);
		} else {
			$apache_server_info = false;
		}
		curl_close($curl);
	} else {
		$apache_server_info = 'nocurl';
	}
}


// IIS Properties
$iis_properties = false;

if (check_isIIS()) {
	// IIS Rewrite module
	if (check_hasIIS_UrlRewriteModule()) {
		$iis_properties['IIS Url Rewrite Module'] = array(
			'fitness' => tra('good'),
			'setting' => 'Available',
			'message' => tra('The URL Rewrite Module is required to use SEFURL on IIS.')
			);
	} else {
		$iis_properties['IIS Url Rewrite Module'] = array(
			'fitness' => tra('bad'),
			'setting' => 'Not Available',
			'message' => tra('The URL Rewrite Module is required to use SEFURL on IIS.')
			);
	}
}

// Check Tiki Packages
if (! $standalone) {
	global $tikipath;

	$composerManager = new ComposerManager($tikipath);
	$installedLibs = $composerManager->getInstalled();

	$packagesToCheck = array(
		array(
			'name' => 'media-alchemyst/media-alchemyst',
			'preferences' => array(
				'alchemy_ffmpeg_path' => array(
					'name' => tra('ffmpeg path'),
					'type' => 'path'
				),
				'alchemy_ffprobe_path' => array(
					'name' => tra('ffprobe path'),
					'type' => 'path'
				),
				'alchemy_unoconv_path' => array(
					'name' => tra('unoconv path'),
					'type' => 'path'
				),
				'alchemy_gs_path' => array(
					'name' => tra('ghostscript path'),
					'type' => 'path'
				),
				'alchemy_imagine_driver' => array(
					'name' => tra('Alchemy Image library'),
					'type' => 'classOptions',
					'options' => array(
						'imagick' => array(
							'name' => tra('Imagemagick'),
							'classLib' => 'Imagine\Imagick\Imagine',
							'className' => 'Imagick',
							'extension' => false
						),
						'gd' => array(
							'name' => tra('GD'),
							'classLib' => 'Imagine\Gd\Imagine',
							'className' => false,
							'extension' => 'gd'
						)
					),
				),
			)
		),
		array(
			'name' => 'php-unoconv/php-unoconv',
			'preferences' => array(
				'alchemy_unoconv_path' => array(
					'name' => tra('unoconv path'),
					'type' => 'path'
				)
			)
		)
	);

	$packagesToDisplay = array();
	foreach ($installedLibs as $installedPackage) {
		$key = array_search($installedPackage['name'], array_column($packagesToCheck, 'name'));
		if ($key !== false) {
			$warnings = checkPreferences($packagesToCheck[$key]['preferences']);
			checkPackageWarnings($warnings, $installedPackage);

			$packageInfo = array(
				'name' => $installedPackage['name'],
				'version' => $installedPackage['installed'],
				'status' => count($warnings) > 0 ? tra('unsure') : tra('good'),
				'message' => $warnings
			);
		} else {
			$packageInfo = array(
				'name' => $installedPackage['name'],
				'version' => $installedPackage['installed'],
				'status' => tra('good'),
				'message' => array()
			);
		}
		$packagesToDisplay[] = $packageInfo;
	}

	/**
	 * Tesseract PHP Package Check
	 */

	/** @var string The version of Tesseract required */
	$TesseractVersion = '2.7.0';
	/** @var string Current Tesseract installed version */
	$ocrVersion = false;
	foreach ($packagesToDisplay as $arrayValue) {
		if ($arrayValue['name'] === 'thiagoalessio/tesseract_ocr') {
			$ocrVersion = $arrayValue['version'];
			break;
		}
	}

	if (! $ocrVersion) {
		$ocrVersion = tra('Not Installed');
		$ocrMessage = tra(
			'Tesseract PHP package could not be found. Try installing through Packages.'
		);
		$ocrStatus = 'bad';
	} elseif (version_compare($ocrVersion, $TesseractVersion, '>=')) {
		$ocrMessage = tra('Tesseract PHP dependency installed.');
		$ocrStatus = 'good';
	} else {
		$ocrMessage = tra(
			'The installed Tesseract version is lower than the required version.'
		);
		$ocrStatus = 'bad';
	}

	$ocrToDisplay = array(array(
						 'name'    => tra('Tesseract package'),
						 'version' => $ocrVersion,
						 'status'  => $ocrStatus,
						 'message' => $ocrMessage,
					 ));

	/**
	 * Tesseract Binary dependency Check
	 */

	$ocr = Tikilib::lib('ocr');
	$langCount = count($ocr->getTesseractLangs());

	if ($langCount >= 5) {
		$ocrMessage = $langCount . ' ' . tra('languages installed.');
		$ocrStatus = 'good';
	} else {
		$ocrMessage = tra(
			'Not all languages installed. You may need to install additional languages for multilingual support.'
		);
		$ocrStatus = 'unsure';
	}

	$ocrToDisplay[] = array(
		'name'    => tra('Tesseract languages'),
		'status'  => $ocrStatus,
		'message' => $ocrMessage,
	);

	if ($ocrVersion !== 'Not Installed') {
		$ocrVersion = $ocr->getTesseractVersion();
	} else {
		$ocrVersion = false;
	}

	if (! $ocrVersion) {
		$ocrVersion = tra('Not Found');
		$ocrMessage = tra(
			'Tesseract could not be found.'
		);
		$ocrStatus = 'bad';
	} elseif ($ocr->checkTesseractVersion()) {
		$ocrMessage = tra(
			'Tesseract meets or exceeds the version requirements.'
		);
		$ocrStatus = 'good';
	} else {
		$ocrMessage = tra(
			'The installed Tesseract version is lower than the required version.'
		);
		$ocrStatus = 'bad';
	}

	$ocrToDisplay[] = array(
		'name'    => tra('Tesseract binary'),
		'version' => $ocrVersion,
		'status'  => $ocrStatus,
		'message' => $ocrMessage,
	);
	try {
		if (empty($prefs['ocr_tesseract_path'])	|| $prefs['ocr_tesseract_path'] === 'tesseract') {
			$ocrStatus = 'bad';
			$ocrMessage = tra(
				'Your path preference is not configured. It may work now but will likely fail with cron. Specify an absolute path.'
			);
		} elseif ($prefs['ocr_tesseract_path'] === $ocr->whereIsExecutable('tesseract')) {
			$ocrStatus = 'good';
			$ocrMessage = tra('Path setup correctly.');
		} else {
			$ocrStatus = 'unsure';
			$ocrMessage = tra(
				'Your path may not be configured correctly. It appears to be located at '
			) . $ocr->whereIsExecutable(
				'tesseract' . '.'
			);
		}
	} catch (Exception $e) {
		if (empty($prefs['ocr_tesseract_path'])
			|| $prefs['ocr_tesseract_path'] === 'tesseract'
		) {
			$ocrStatus = 'bad';
			$ocrMessage = tra(
				'Your path preference is not configured. It may work now but will likely fail with cron. Specify an absolute path.'
			);
		} else {
			$ocrStatus = 'unsure';
			$ocrMessage = tra(
				'Your path is configured, but we were unable to tell if it was configured properly or not.'
			);
		}
	}

	$ocrToDisplay[] = array(
		'name'    => tra('Tesseract path'),
		'status'  => $ocrStatus,
		'message' => $ocrMessage,
	);


	$pdfimages = Tikilib::lib('pdfimages');
	$pdfimages->setVersion();

	//lets fall back to configured options for a binary path if its not found with default options.
	if (! $pdfimages->version) {
		$pdfimages->setBinaryPath();
		$pdfimages->setVersion();
	}

	if ($pdfimages->version) {
		$ocrStatus = 'good';
		$ocrMessage = tra('It appears that pdfimages is installed on your system.');
	} else {
		$ocrStatus = 'bad';
		$ocrMessage = tra('Could not find pdfimages. PDF files will not be processed.');
	}

	$ocrToDisplay[] = array(
		'name'    => tra('Pdfimages binary'),
		'version' => $pdfimages->version,
		'status'  => $ocrStatus,
		'message' => $ocrMessage,
	);

	try {
		if (empty($prefs['ocr_pdfimages_path']) || $prefs['ocr_pdfimages_path'] === 'pdfimages') {
			$ocrStatus = 'bad';
			$ocrMessage = tra('Your path preference is not configured. It may work now but will likely fail with cron. Specify an absolute path.');
		} elseif ($prefs['ocr_pdfimages_path'] === $ocr->whereIsExecutable('pdfimages')) {
			$ocrStatus = 'good';
			$ocrMessage = tra('Path setup correctly');
		} else {
			$ocrStatus = 'unsure';
			$ocrMessage = tra('Your path may not be configured correctly. It appears to be located at ') .
				$ocr->whereIsExecutable('pdfimages' . ' ');
		}
	} catch (Exception $e) {
		if (empty($prefs['ocr_pdfimages_path']) || $prefs['ocr_pdfimages_path'] === 'pdfimages') {
			$ocrStatus = 'bad';
			$ocrMessage = tra('Your path preference is not configured. It may work now but will likely fail with cron. Specify an absolute path.');
		} else {
			$ocrStatus = 'unsure';
			$ocrMessage = tra(
				'Your path is configured, but we were unable to tell if it was configured properly or not.'
			);
		}
	}

	$ocrToDisplay[] = array(
		'name'    => tra('Pdfimages path'),
		'status'  => $ocrStatus,
		'message' => $ocrMessage,
	);

	// check if scheduler is set up properly.
	$scheduleDb = $ocr->table('tiki_scheduler');
	$conditions['status'] = 'active';
	$conditions['params'] = $scheduleDb->contains('ocr:all');
	if ($scheduleDb->fetchBool($conditions)) {
		$ocrToDisplay[] = array(
			'name'    => tra('Scheduler'),
			'status'  => 'good',
			'message' => tra('Scheduler has been successfully setup.'),
		);
	} else {
		$ocrToDisplay[] = array(
			'name'    => tra('Scheduler'),
			'status'  => 'bad',
			'message' => tra('Scheduler needs to have a console command of "ocr:all" set.'),
		);
	}

	$smarty->assign('ocr', $ocrToDisplay);
}
// Security Checks
// get all dangerous php settings and check them
$security = false;

// check file upload dir and compare it to tiki root dir
$s = ini_get('upload_tmp_dir');
$sn = substr($_SERVER['SCRIPT_NAME'], 0, -14);
if ($s != "" && strpos($sn, $s) !== false) {
	$security['upload_tmp_dir'] = array(
		'fitness' => tra('unsafe') ,
		'setting' => $s,
		'message' => tra('upload_tmp_dir is probably inside the Tiki directory. There is a risk that someone can upload any file to this directory and access it via web browser.')
	);
} else {
	$security['upload_tmp_dir'] = array(
		'fitness' => tra('unknown') ,
		'setting' => $s,
		'message' => tra('It can\'t be reliably determined if the upload_tmp_dir is accessible via a web browser. To be sure, check the webserver configuration.')
	);
}

// Determine system state
$pdf_webkit = '';
if (isset($prefs) && $prefs['print_pdf_from_url'] == 'webkit') {
	$pdf_webkit = '<b>' . tra('WebKit is enabled') . '.</b> ';
}
$feature_blogs = '';
if (isset($prefs) && $prefs['feature_blogs'] == 'y') {
	$feature_blogs = '<b>' . tra('The Blogs feature is enabled') . '.</b> ';
}

$fcts = array(
		 array(
			'function' => 'exec',
			'risky' => tra('Exec can potentially be used to execute arbitrary code on the server.') . ' ' . tra('Tiki does not need it; perhaps it should be disabled.') . ' ' . tra('However, the Plugins R/RR need it. If you use the Plugins R/RR and the other PHP software on the server can be trusted, this should be enabled.'),
			'safe' => tra('Exec can be potentially be used to execute arbitrary code on the server.') . ' ' . tra('Tiki needs it to run the Plugins R/RR.') . tra('If this is needed and the other PHP software on the server can be trusted, this should be enabled.')
		 ),
		 array(
			'function' => 'passthru',
			'risky' => tra('Passthru is similar to exec.') . ' ' . tra('Tiki does not need it; perhaps it should be disabled. However, the Composer package manager used for installations in Subversion checkouts may need it.'),
			'safe' => tra('Passthru is similar to exec.') . ' ' . tra('Tiki does not need it; it is good that it is disabled. However, the Composer package manager used for installations in Subversion checkouts may need it.')
		 ),
		 array(
			'function' => 'shell_exec',
			'risky' => tra('Shell_exec is similar to exec.') . ' ' . tra('Tiki needs it to run PDF from URL: WebKit (wkhtmltopdf). ' . $pdf_webkit . 'If this is needed and the other PHP software on the server can be trusted, this should be enabled.'),
			'safe' => tra('Shell_exec is similar to exec.') . ' ' . tra('Tiki needs it to run PDF from URL: WebKit (wkhtmltopdf). ' . $pdf_webkit . 'If this is needed and the other PHP software on the server can be trusted, this should be enabled.')
		 ),
		 array(
			'function' => 'system',
			'risky' => tra('System is similar to exec.') . ' ' . tra('Tiki does not need it; perhaps it should be disabled.'),
			'safe' => tra('System is similar to exec.') . ' ' . tra('Tiki does not need it; it is good that it is disabled.')
		 ),
		 array(
			'function' => 'proc_open',
			'risky' => tra('Proc_open is similar to exec.') . ' ' . tra('Tiki does not need it; perhaps it should be disabled. However, the Composer package manager used for installations in Subversion checkouts or when using the package manager from the <a href="https://doc.tiki.org/Packages" target="_blank">admin interface</a> may need it.'),
			'safe' => tra('Proc_open is similar to exec.') . ' ' . tra('Tiki does not need it; it is good that it is disabled. However, the Composer package manager used for installations in Subversion checkouts or when using the package manager from the <a href="https://doc.tiki.org/Packages" target="_blank">admin interface</a> may need it.')
		 ),
		 array(
			'function' => 'popen',
			'risky' => tra('popen is similar to exec.') . ' ' . tra('Tiki needs it for file search indexing in file galleries. If this is needed and other PHP software on the server can be trusted, this should be enabled.'),
			'safe' => tra('popen is similar to exec.') . ' ' . tra('Tiki needs it for file search indexing in file galleries. If this is needed and other PHP software on the server can be trusted, this should be enabled.')
		 ),
		 array(
			'function' => 'curl_exec',
			'risky' => tra('Curl_exec can potentially be abused to write malicious code.') . ' ' . tra('Tiki needs it to run features like Kaltura, CAS login, CClite and the myspace and sf wiki-plugins. If these are needed and other PHP software on the server can be trusted, this should be enabled.'),
			'safe' => tra('Curl_exec can potentially be abused to write malicious code.') . ' ' . tra('Tiki needs it to run features like Kaltura, CAS login, CClite and the myspace and sf wiki-plugins. If these are needed and other PHP software on the server can be trusted, this should be enabled.')
		 ),
		 array(
			'function' => 'curl_multi_exec',
			'risky' => tra('Curl_multi_exec can potentially be abused to write malicious code.') . ' ' . tra('Tiki needs it to run features like Kaltura, CAS login, CClite and the myspace and sf wiki-plugins. If these are needed and other PHP software on the server can be trusted, this should be enabled.'),
			'safe' => tra('Curl_multi_exec can potentially be abused to write malicious code.') . ' ' . tra('Tiki needs it to run features like Kaltura, CAS login, CClite and the myspace and sf wiki-plugins. If these are needed and other PHP software on the server can be trusted, this should be enabled.')
		 ),
		 array(
			'function' => 'parse_ini_file',
			'risky' => tra('It is probably an urban myth that this is dangerous. Tiki team will reconsider this check, but be warned.') . ' ' . tra('It is required for the <a href="http://doc.tiki.org/System+Configuration" target="_blank">System Configuration</a> feature.'),
			'safe' => tra('It is probably an urban myth that this is dangerous. Tiki team will reconsider this check, but be warned.') . ' ' . tra('It is required for the <a href="http://doc.tiki.org/System+Configuration" target="_blank">System Configuration</a> feature.'),
		 ),
		 array(
			'function' => 'show_source',
			'risky' => tra('It is probably an urban myth that this is dangerous. Tiki team will reconsider this check, but be warned.'),
			'safe' => tra('It is probably an urban myth that this is dangerous. Tiki team will reconsider this check, but be warned.'),
		 )
	);

foreach ($fcts as $fct) {
	if (function_exists($fct['function'])) {
		$security[$fct['function']] = array(
			'setting' => tra('Enabled'),
			'fitness' => tra('risky'),
			'message' => $fct['risky']
		);
	} else {
		$security[$fct['function']] = array(
			'setting' => tra('Disabled'),
			'fitness' => tra('safe'),
			'message' => $fct['safe']
		);
	}
}

// trans_sid
$s = ini_get('session.use_trans_sid');
if ($s) {
	$security['session.use_trans_sid'] = array(
		'setting' => 'Enabled',
		'fitness' => tra('unsafe'),
		'message' => tra('session.use_trans_sid should be off by default. See the PHP manual for details.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} else {
	$security['session.use_trans_sid'] = array(
		'setting' => 'Disabled',
		'fitness' => tra('safe'),
		'message' => tra('session.use_trans_sid should be off by default. See the PHP manual for details.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
}

$s = ini_get('xbithack');
if ($s == 1) {
	$security['xbithack'] = array(
		'setting' => 'Enabled',
		'fitness' => tra('unsafe'),
		'message' => tra('Setting the xbithack option is unsafe. Depending on the file handling of the webserver and the Tiki settings, an attacker may be able to upload scripts to file gallery and execute them.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
} else {
	$security['xbithack'] = array(
		'setting' => 'Disabled',
		'fitness' => tra('safe'),
		'message' => tra('setting the xbithack option is unsafe. Depending on the file handling of the webserver and the Tiki settings,  an attacker may be able to upload scripts to file gallery and execute them.') . ' <a href="#php_conf_info">' . tra('How to change this value') . '</a>'
	);
}

$s = ini_get('allow_url_fopen');
if ($s == 1) {
	$security['allow_url_fopen'] = array(
		'setting' => 'Enabled',
		'fitness' => tra('risky'),
		'message' => tra('allow_url_fopen may potentially be used to upload remote data or scripts. Also used by Composer to fetch dependencies. ' . $feature_blogs . 'If this Tiki does not use the Blogs feature, this can be switched off.')
	);
} else {
	$security['allow_url_fopen'] = array(
		'setting' => 'Disabled',
		'fitness' => tra('safe'),
		'message' => tra('allow_url_fopen may potentially be used to upload remote data or scripts. Also used by Composer to fetch dependencies. ' . $feature_blogs . 'If this Tiki does not use the Blogs feature, this can be switched off.')
	);
}

if ($standalone || (! empty($prefs) && $prefs['fgal_enable_auto_indexing'] === 'y')) {
	// adapted from \FileGalLib::get_file_handlers
	$fh_possibilities = array(
		'application/ms-excel' => array('xls2csv %1'),
		'application/msexcel' => array('xls2csv %1'),
		// vnd.openxmlformats are handled natively in Zend
		//'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => array('xlsx2csv.py %1'),
		'application/ms-powerpoint' => array('catppt %1'),
		'application/mspowerpoint' => array('catppt %1'),
		//'application/vnd.openxmlformats-officedocument.presentationml.presentation' => array('pptx2txt.pl %1 -'),
		'application/msword' => array('catdoc %1', 'strings %1'),
		//'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => array('docx2txt.pl %1 -'),
		'application/pdf' => array('pstotext %1', 'pdftotext %1 -'),
		'application/postscript' => array('pstotext %1'),
		'application/ps' => array('pstotext %1'),
		'application/rtf' => array('catdoc %1'),
		'application/sgml' => array('col -b %1', 'strings %1'),
		'application/vnd.ms-excel' => array('xls2csv %1'),
		'application/vnd.ms-powerpoint' => array('catppt %1'),
		'application/x-msexcel' => array('xls2csv %1'),
		'application/x-pdf' => array('pstotext %1', 'pdftotext %1 -'),
		'application/x-troff-man' => array('man -l %1'),
		'application/zip' => array('unzip -l %1'),
		'text/enriched' => array('col -b %1', 'strings %1'),
		'text/html' => array('elinks -dump -no-home %1'),
		'text/richtext' => array('col -b %1', 'strings %1'),
		'text/sgml' => array('col -b %1', 'strings %1'),
		'text/tab-separated-values' => array('col -b %1', 'strings %1'),
	);

	$fh_native = array(
		'application/pdf' => 18.0,
		'application/x-pdf' => 18.0,
	);

	$file_handlers = array();
	if (! $standalone) {
		$tikiWikiVersion = new TWVersion();
	}

	foreach ($fh_possibilities as $type => $options) {
		$file_handler = array(
			'fitness' => '',
			'message' => '',
		);

		if (! $standalone && array_key_exists($type, $fh_native)) {
			if ($tikiWikiVersion->getBaseVersion() >= $fh_native["$type"]) {
				$file_handler['fitness'] = 'good';
				$file_handler['message'] = "will be handled natively";
			}
		}
		if ($standalone && array_key_exists($type, $fh_native)) {
			$file_handler['fitness'] = 'info';
			$file_handler['message'] = "will be handled natively by Tiki &gt;= " . $fh_native["$type"];
		}
		if ($file_handler['fitness'] == '' || $file_handler['fitness'] == 'info') {
			foreach ($options as $opt) {
				$optArray = explode(' ', $opt, 2);
				$exec = reset($optArray);
				$which_exec = `which $exec`;
				if ($which_exec) {
					if ($file_handler['fitness'] == 'info') {
						$file_handler['message'] .= ", otherwise handled by $which_exec";
					} else {
						$file_handler['message'] = "will be handled by $which_exec";
					}
					$file_handler['fitness'] = 'good';
					break;
				}
			}
			if ($file_handler['fitness'] == 'info') {
				$fh_commands = '';
				foreach ($options as $opt) {
					$fh_commands .= $fh_commands ? ' or ' : '';
					$fh_commands .= '"' . substr($opt, 0, strpos($opt, ' ')) . '"';
				}
				$file_handler['message'] .= ', otherwise you need to install ' . $fh_commands . ' to index this type of file';
			}
		}
		if (! $file_handler['fitness']) {
			$file_handler['fitness'] = 'unsure';
			$fh_commands = '';
			foreach ($options as $opt) {
				$fh_commands .= $fh_commands ? ' or ' : '';
				$fh_commands .= '"' . substr($opt, 0, strpos($opt, ' ')) . '"';
			}
			$file_handler['message'] = 'You need to install ' . $fh_commands . ' to index this type of file';
		}
		$file_handlers[$type] = $file_handler;
	}
}


if (! $standalone) {
	// The following is borrowed from tiki-admin_system.php
	if ($prefs['feature_forums'] == 'y') {
		$dirs = TikiLib::lib('comments')->list_directories_to_save();
	} else {
		$dirs = array();
	}
	if ($prefs['feature_galleries'] == 'y' && ! empty($prefs['gal_use_dir'])) {
		$dirs[] = $prefs['gal_use_dir'];
	}
	if ($prefs['feature_file_galleries'] == 'y' && ! empty($prefs['fgal_use_dir'])) {
		$dirs[] = $prefs['fgal_use_dir'];
	}
	if ($prefs['feature_trackers'] == 'y') {
		if (! empty($prefs['t_use_dir'])) {
			$dirs[] = $prefs['t_use_dir'];
		}
		$dirs[] = 'img/trackers';
	}
	if ($prefs['feature_wiki'] == 'y') {
		if (! empty($prefs['w_use_dir'])) {
			$dirs[] = $prefs['w_use_dir'];
		}
		if ($prefs['feature_create_webhelp'] == 'y') {
			$dirs[] = 'whelp';
		}
		$dirs[] = 'img/wiki';
		$dirs[] = 'img/wiki_up';
	}
	$dirs = array_unique($dirs);
	$dirsExist = array();
	foreach ($dirs as $i => $d) {
		$dirsWritable[$i] = is_writable($d);
	}
	$smarty->assign_by_ref('dirs', $dirs);
	$smarty->assign_by_ref('dirsWritable', $dirsWritable);

	// Prepare Monitoring acks
	$query = "SELECT `value` FROM tiki_preferences WHERE `name`='tiki_check_status'";
	$result = $tikilib->getOne($query);
	$last_state = json_decode($result, true);
	$smarty->assign_by_ref('last_state', $last_state);

	function deack_on_state_change(&$check_group, $check_group_name)
	{
		global $last_state;
		foreach ($check_group as $key => $value) {
			if (! empty($last_state["$check_group_name"]["$key"])) {
				$check_group["$key"]['ack'] = $last_state["$check_group_name"]["$key"]['ack'];
				if (isset($check_group["$key"]['setting']) && isset($last_state["$check_group_name"]["$key"]['setting']) &&
							$check_group["$key"]['setting'] != $last_state["$check_group_name"]["$key"]['setting']) {
					$check_group["$key"]['ack'] = false;
				}
			}
		}
	}
	deack_on_state_change($mysql_properties, 'MySQL');
	deack_on_state_change($server_properties, 'Server');
	if ($apache_properties) {
		deack_on_state_change($apache_properties, 'Apache');
	}
	if ($iis_properties) {
		deack_on_state_change($iis_properties, 'IIS');
	}
	deack_on_state_change($php_properties, 'PHP');
	deack_on_state_change($security, 'PHP Security');

	$tikiWikiVersion = new TWVersion();
	if (version_compare($tikiWikiVersion->getBaseVersion(), '18.0', '<') && ! class_exists('mPDF')
		|| version_compare($tikiWikiVersion->getBaseVersion(), '18.0', '>=') && ! class_exists('\\Mpdf\\Mpdf')) {
		$smarty->assign('mPDFClassMissing', true);
	}

	// Engine tables type
	global $dbs_tiki;
	if (! empty($dbs_tiki)) {
		$engineType = '';
		$query = 'SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_NAME = "tiki_schema" AND TABLE_SCHEMA = "' . $dbs_tiki . '";';
		$result = query($query, $connection);
		if (! empty($result[0]['ENGINE'])) {
			$engineType = $result[0]['ENGINE'];
		}
	}
	if (version_compare($tikiWikiVersion->getBaseVersion(), '18.0', '>=') && ! empty($dbs_tiki) && $engineType != 'InnoDB') {
		$smarty->assign('engineTypeNote', true);
	} else {
		$smarty->assign('engineTypeNote', false);
	}

	$smarty->assign('composer_available', $composerManager->composerIsAvailable());
	$smarty->assign('packages', $packagesToDisplay);
}

$sensitiveDataDetectedFiles = array();
check_for_remote_readable_files($sensitiveDataDetectedFiles);

if (! empty($sensitiveDataDetectedFiles)) {
	$files = ' (Files: ' . trim(implode(', ', $sensitiveDataDetectedFiles)) . ')';
	$tiki_security['Sensitive Data Exposure'] = array(
		'fitness' => tra('risky'),
		'message' => tra('Tiki detected that there are temporary files in the db folder that may expose credentials or other sensitive information.') . $files
	);
} else {
	$tiki_security['Sensitive Data Exposure'] = array(
		'fitness' => tra('safe'),
		'message' => tra('Tiki did not detect temporary files in the db folder that may expose credentials or other sensitive information.')
	);
}

if (isset($_REQUEST['benchmark'])) {
	$benchmark = BenchmarkPhp::run();
} else {
	$benchmark = '';
}

/**
 * TRIM (Tiki Remote Instance Manager) Section
 **/
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$trimCapable = false;
} else {
	$trimCapable = true;
}

if ($trimCapable) {
	$trimServerRequirements = array();
	$trimClientRequirements = array();

	$trimServerRequirements['Operating System Path'] = array(
		'fitness' => tra('info'),
		'message' => $_SERVER['PATH']
	);

	$trimClientRequirements['Operating System Path'] = array(
		'fitness' => tra('info'),
		'message' => $_SERVER['PATH']
	);

	$trimClientRequirements['SSH or FTP server'] = array(
		'fitness' => tra('info'),
		'message' => tra('To manage this instance from a remote server you need SSH or FTP access to this server')
	);

	$serverCommands = array(
		'make' => 'make',
		'php-cli' => 'php',
		'rsync' => 'rsync',
		'nice' => 'nice',
		'tar' => 'tar',
		'bzip2' => 'bzip2',
		'ssh' => 'ssh',
		'ssh-copy-id' => 'ssh-copy-id',
		'scp' => 'scp',
		'sqlite' => 'sqlite3'
	);

	$serverPHPExtensions = array(
		'php-sqlite' => 'sqlite3',
	);

	$clientCommands = array(
		'php-cli' => 'php',
		'mysql' => 'mysql',
		'mysqldump' => 'mysqldump',
		'gzip' => 'gzip',
	);

	foreach ($serverCommands as $key => $command) {
		if (commandIsAvailable($command)) {
			$trimServerRequirements[$key] = array(
				'fitness' => tra('good'),
				'message' => tra('Command found')
			);
		} else {
			$trimServerRequirements[$key] = array(
				'fitness' => tra('unsure'),
				'message' => tra('Command not found, check if it is installed and available in one of the paths above')
			);
		}
	}

	foreach ($serverPHPExtensions as $key => $extension) {
		if (extension_loaded($extension)) {
			$trimServerRequirements[$key] = array(
				'fitness' => tra('good'),
				'message' => tra('Extension loaded in PHP')
			);
		} else {
			$trimServerRequirements[$key] = array(
				'fitness' => tra('unsure'),
				'message' => tra('Extension not loaded in PHP')
			);
		}
	}

	foreach ($clientCommands as $key => $command) {
		if (commandIsAvailable($command)) {
			$trimClientRequirements[$key] = array(
				'fitness' => tra('good'),
				'message' => tra('Command found')
			);
		} else {
			$trimClientRequirements[$key] = array(
				'fitness' => tra('unsure'),
				'message' => tra('Command not found, check if it is installed and available in one of the paths above')
			);
		}
	}
}

if ($standalone && ! $nagios) {
	$render .= '<style type="text/css">td, th { border: 1px solid #000000; vertical-align: baseline; padding: .5em; }</style>';
//	$render .= '<h1>Tiki Server Compatibility</h1>';
	if (! $locked) {
		$render .= '<h2>MySQL or MariaDB Database Properties</h2>';
		renderTable($mysql_properties);
		$render .= '<h2>Test sending emails</h2>';
		if (isset($_REQUEST['email_test_to'])) {
			$email = filter_var($_POST['email_test_to'], FILTER_SANITIZE_EMAIL);
			$email_test_headers = 'From: noreply@tiki.org' . "\n";	// needs a valid sender
			$email_test_headers .= 'Reply-to: ' . $email . "\n";
			$email_test_headers .= "Content-type: text/plain; charset=utf-8\n";
			$email_test_headers .= 'X-Mailer: Tiki-Check - PHP/' . phpversion() . "\n";
			$email_test_subject = tra('Test mail from Tiki Server Compatibility Test');
			$email_test_body = tra("Congratulations!\n\nThis server can send emails.\n\n");
			$email_test_body .= "\t" . tra('Server:') . ' ' . (empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['SERVER_NAME']) . "\n";
			$email_test_body .= "\t" . tra('Sent:') . ' ' . date(DATE_RFC822) . "\n";

			$sentmail = mail($email, $email_test_subject, $email_test_body, $email_test_headers);
			if ($sentmail) {
				$mail['Sending mail'] = array(
					'setting' => 'Accepted',
					'fitness' => tra('good'),
					'message' => tra('It was possible to send an e-mail. This only means that a mail server accepted the mail for delivery. This check can\;t verify if that server actually delivered the mail. Please check the inbox of ' . htmlspecialchars($email) . ' to see if the mail was delivered.')
				);
			} else {
				$mail['Sending mail'] = array(
					'setting' => 'Not accepted',
					'fitness' => tra('bad'),
					'message' => tra('It was not possible to send an e-mail. It may be that there is no mail server installed on this machine or that it is incorrectly configured. If the local mail server cannot be made to work, a regular mail account can be set up and its SMTP settings configured in tiki-admin.php.')
				);
			}
			renderTable($mail);
		} else {
			$render .= '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">';
			$render .= '<p><label for="e-mail">e-mail address to send test mail to</label>: <input type="text" id="email_test_to" name="email_test_to" /></p>';
			$render .= '<p><input type="submit" class="btn btn-primary btn-sm" value=" Send e-mail " /></p>';
			$render .= '<p><input type="hidden" id="dbhost" name="dbhost" value="';
			if (isset($_POST['dbhost'])) {
				$render .= htmlentities(strip_tags($_POST['dbhost']));
			};
				$render .= '" /></p>';
				$render .= '<p><input type="hidden" id="dbuser" name="dbuser" value="';
			if (isset($_POST['dbuser'])) {
				$render .= htmlentities(strip_tags($_POST['dbuser']));
			};
				$render .= '"/></p>';
				$render .= '<p><input type="hidden" id="dbpass" name="dbpass" value="';
			if (isset($_POST['dbpass'])) {
				$render .= htmlentities(strip_tags($_POST['dbpass']));
			};
				$render .= '"/></p>';
			$render .= '</form>';
		}
	}

	$render .= '<h2>Server Information</h2>';
	renderTable($server_information);
	$render .= '<h2>Server Properties</h2>';
	renderTable($server_properties);
	$render .= '<h2>Apache properties</h2>';
	if ($apache_properties) {
		renderTable($apache_properties);
		if ($apache_server_info != 'nocurl' && $apache_server_info != false) {
			if (isset($_REQUEST['apacheinfo']) && $_REQUEST['apacheinfo'] == 'y') {
				$render .= $apache_server_info;
			} else {
				$render .= '<a href="' . $_SERVER['SCRIPT_NAME'] . '?apacheinfo=y">Append Apache /server-info;</a>';
			}
		} elseif ($apache_server_info == 'nocurl') {
			$render .= 'You don\'t have the Curl extension in PHP, so we can\'t append Apache\'s server-info.';
		} else {
			$render .= 'Apparently you have not enabled mod_info in your Apache, so we can\'t append more verbose information to this output.';
		}
	} else {
		$render .= 'You are either not running the preferred Apache web server or you are running PHP with a SAPI that does not allow checking Apache properties (for example, CGI or FPM).';
	}
	$render .= '<h2>IIS properties</h2>';
	if ($iis_properties) {
		renderTable($iis_properties);
	} else {
		$render .= "You are not running IIS web server.";
	}
	$render .= '<h2>PHP scripting language properties</h2>';
	renderTable($php_properties);

	$render_sapi_info = '';
	if (! empty($php_sapi_info)) {
		if (! empty($php_sapi_info['message'])) {
			$render_sapi_info .= $php_sapi_info['message'];
		}
		if (! empty($php_sapi_info['link'])) {
			$render_sapi_info .= '<a href="' . $php_sapi_info['link'] . '"> ' . $php_sapi_info['link'] . '</a>';
		}
		$render_sapi_info = '<p>' . $render_sapi_info . '</p>';
	}

	$render .= '<p><a name="php_conf_info"></a>Change PHP configuration values:' . $render_sapi_info . ' You can check the full documentation on how to change the configurations values in <a href="http://www.php.net/manual/en/configuration.php">http://www.php.net/manual/en/configuration.php</a></p>';
	$render .= '<h2>PHP security properties</h2>';
	renderTable($security);
	$render .= '<h2>Tiki Security</h2>';
	renderTable($tiki_security);
	$render .= '<h2>MySQL Variables</h2>';
	renderTable($mysql_variables, 'wrap');

	$render .= '<h2>File Gallery Search Indexing</h2>';
	$render .= '<em>More info <a href="https://doc.tiki.org/Search+within+files">here</a></em>
	';
	renderTable($file_handlers);

	$render .= '<h2>PHP Info</h2>';
	if (isset($_REQUEST['phpinfo']) && $_REQUEST['phpinfo'] == 'y') {
		ob_start();
		phpinfo();
		$info = ob_get_contents();
		ob_end_clean();
		$info = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $info);
		$render .= $info;
	} else {
		$render .= '<a href="' . $_SERVER['SCRIPT_NAME'] . '?phpinfo=y">Append phpinfo();</a>';
	}

	$render .= '<a name="benchmark"></a><h2>Benchmark PHP/MySQL</h2>';
	$render .= '<a href="tiki-check.php?benchmark=run&ts=' . time() . '#benchmark" style="margin-bottom: 10px;">Check</a>';
	if (! empty($benchmark)) {
		renderTable($benchmark);
	}

	$render .= '<h2>TRIM</h2>';
	$render .= '<em>For more detailed information about Tiki Remote Instance Manager please check <a href="https://doc.tiki.org/TRIM">doc.tiki.org</a></em>.';
	if ($trimCapable) {
		$render .= '<h3>Server Instance</h3>';
		renderTable($trimServerRequirements);
		$render .= '<h3>Client Instance</h3>';
		renderTable($trimClientRequirements);
	} else {
		$render .= '<p>Apparently Tiki is running on a Windows based server. This feature is not supported natively.</p>';
	}

	createPage('Tiki Server Compatibility', $render);
} elseif ($nagios) {
//  0	OK
//  1	WARNING
//  2	CRITICAL
//  3	UNKNOWN
	$monitoring_info = array( 'state' => 0,
			 'message' => '');

	function update_overall_status($check_group, $check_group_name)
	{
		global $monitoring_info;
		$state = 0;
		$message = '';

		foreach ($check_group as $property => $values) {
			if (! isset($values['ack']) || $values['ack'] != true) {
				switch ($values['fitness']) {
					case 'unsure':
						$state = max($state, 1);
						$message .= "$property" . "->unsure, ";
						break;
					case 'risky':
						$state = max($state, 1);
						$message .= "$property" . "->risky, ";
						break;
					case 'bad':
						$state = max($state, 2);
						$message .= "$property" . "->BAD, ";
						break;
					case 'info':
						$state = max($state, 3);
						$message .= "$property" . "->info, ";
						break;
					case 'good':
					case 'safe':
						break;
				}
			}
		}
		$monitoring_info['state'] = max($monitoring_info['state'], $state);
		if ($state != 0) {
			$monitoring_info['message'] .= $check_group_name . ": " . trim($message, ' ,') . " -- ";
		}
	}

	// Might not be set, i.e. in standalone mode
	if ($mysql_properties) {
		update_overall_status($mysql_properties, "MySQL");
	}
	update_overall_status($server_properties, "Server");
	if ($apache_properties) {
		update_overall_status($apache_properties, "Apache");
	}
	if ($iis_properties) {
		update_overall_status($iis_properties, "IIS");
	}
	update_overall_status($php_properties, "PHP");
	update_overall_status($security, "PHP Security");
	update_overall_status($tiki_security, "Tiki Security");
	$return = json_encode($monitoring_info);
	echo $return;
} else {	// not stand-alone
	if (isset($_REQUEST['acknowledge']) || empty($last_state)) {
		$tiki_check_status = array();
		function process_acks(&$check_group, $check_group_name)
		{
			global $tiki_check_status;
			foreach ($check_group as $key => $value) {
				$formkey = str_replace(array('.',' '), '_', $key);
				if (isset($check_group["$key"]['fitness']) && ($check_group["$key"]['fitness'] === 'good' || $check_group["$key"]['fitness'] === 'safe') ||
					(isset($_REQUEST["$formkey"]) && $_REQUEST["$formkey"] === "on")) {
					$check_group["$key"]['ack'] = true;
				} else {
					$check_group["$key"]['ack'] = false;
				}
			}
			$tiki_check_status["$check_group_name"] = $check_group;
		}
		process_acks($mysql_properties, 'MySQL');
		process_acks($server_properties, 'Server');
		if ($apache_properties) {
			process_acks($apache_properties, "Apache");
		}
		if ($iis_properties) {
			process_acks($iis_properties, "IIS");
		}
		process_acks($php_properties, "PHP");
		process_acks($security, "PHP Security");
		$json_tiki_check_status = json_encode($tiki_check_status);
		$query = "INSERT INTO tiki_preferences (`name`, `value`) values('tiki_check_status', ? ) on duplicate key update `value`=values(`value`)";
		$bindvars = array($json_tiki_check_status);
		$result = $tikilib->query($query, $bindvars);
	}
	$smarty->assign_by_ref('server_information', $server_information);
	$smarty->assign_by_ref('server_properties', $server_properties);
	$smarty->assign_by_ref('mysql_properties', $mysql_properties);
	$smarty->assign_by_ref('php_properties', $php_properties);
	$smarty->assign_by_ref('php_sapi_info', $php_sapi_info);
	if ($apache_properties) {
		$smarty->assign_by_ref('apache_properties', $apache_properties);
	} else {
		$smarty->assign('no_apache_properties', 'You are either not running the preferred Apache web server or you are running PHP with a SAPI that does not allow checking Apache properties (e.g. CGI or FPM).');
	}
	if ($iis_properties) {
		$smarty->assign_by_ref('iis_properties', $iis_properties);
	} else {
		$smarty->assign('no_iis_properties', 'You are not running IIS web server.');
	}
	$smarty->assign_by_ref('security', $security);
	$smarty->assign_by_ref('mysql_variables', $mysql_variables);
	$smarty->assign_by_ref('mysql_crashed_tables', $mysql_crashed_tables);
	if ($prefs['fgal_enable_auto_indexing'] === 'y') {
		$smarty->assign_by_ref('file_handlers', $file_handlers);
	}
	// disallow robots to index page:

	$fmap = array(
		'good' => array('icon' => 'ok', 'class' => 'success'),
		'safe' => array('icon' => 'ok', 'class' => 'success'),
		'bad' => array('icon' => 'ban', 'class' => 'danger'),
		'unsafe' => array('icon' => 'ban', 'class' => 'danger'),
		'risky' => array('icon' => 'warning', 'class' => 'warning'),
		'unsure' => array('icon' => 'warning', 'class' => 'warning'),
		'info' => array('icon' => 'information', 'class' => 'info'),
		'unknown' => array('icon' => 'help', 'class' => 'muted'),
	);
	$smarty->assign('fmap', $fmap);

	if (isset($_REQUEST['bomscanner']) && class_exists('BOMChecker_Scanner')) {
		$timeoutLimit = ini_get('max_execution_time');
		if ($timeoutLimit < 120) {
			set_time_limit(120);
		}

		$BOMScanner = new BOMChecker_Scanner();
		$BOMFiles = $BOMScanner->scan();
		$BOMTotalScannedFiles = $BOMScanner->getScannedFiles();

		$smarty->assign('bom_total_files_scanned', $BOMTotalScannedFiles);
		$smarty->assign('bom_detected_files', $BOMFiles);
		$smarty->assign('bomscanner', true);
	}

	$smarty->assign('trim_capable', $trimCapable);
	if ($trimCapable) {
		$smarty->assign('trim_server_requirements', $trimServerRequirements);
		$smarty->assign('trim_client_requirements', $trimClientRequirements);
	}

	$smarty->assign('sensitive_data_detected_files', $sensitiveDataDetectedFiles);

	$smarty->assign('benchmark', $benchmark);
	$smarty->assign('users_with_mcrypt_data', $usersWithMCrypt);
	$smarty->assign('metatag_robots', 'NOINDEX, NOFOLLOW');
	$smarty->assign('mid', 'tiki-check.tpl');
	$smarty->display('tiki.tpl');
}

/**
 * Check package warnings based on specific nuances of each package
 * @param $warnings
 * @param $package
 */
function checkPackageWarnings(&$warnings, $package)
{
	switch ($package['name']) {
		case 'media-alchemyst/media-alchemyst':
			if (! AlchemyLib::hasReadWritePolicies()) {
				$warnings[] = tr(
					'Alchemy requires "Read" and "Write" policy rights. More info: <a href="%0" target="_blank">%1</a>',
					'https://doc.tiki.org/tiki-index.php?page=Media+Alchemyst#Document_to_Image_issues',
					'Media Alchemyst - Document to Image issues'
				);
			}

			break;
	}
}

/**
 * Check if paths set in preferences exist in the system, or if classes exist in project/system
 *
 * @param array $preferences An array with preference key and preference info
 *
 * @return array An array with warning messages.
 */
function checkPreferences(array $preferences)
{
	global $prefs;

	$warnings = array();

	foreach ($preferences as $prefKey => $pref) {
		if ($pref['type'] == 'path') {
			if (isset($prefs[$prefKey]) && ! file_exists($prefs[$prefKey])) {
				$warnings[] = tr("The path '%0' on preference '%1' does not exist", $prefs[$prefKey], $pref['name']);
			}
		} elseif ($pref['type'] == 'classOptions') {
			if (isset($prefs[$prefKey])) {
				$options = $pref['options'][$prefs[$prefKey]];

				if (! empty($options['classLib']) && ! class_exists($options['classLib'])) {
					$warnings[] = tr("The lib '%0' on preference '%1', option '%2' does not exist", $options['classLib'], $pref['name'], $options['name']);
				}

				if (! empty($options['className']) && ! class_exists($options['className'])) {
					$warnings[] = tr("The class '%0' needed for preference '%1', with option '%2' selected, does not exist", $options['className'], $pref['name'], $options['name']);
				}

				if (! empty($options['extension']) && ! extension_loaded($options['extension'])) {
					$warnings[] = tr("The extension '%0' on preference '%1', with option '%2' selected, is not loaded", $options['extension'], $pref['name'], $options['name']);
				}
			}
		}
	}

	return $warnings;
}

/**
 * Check if a given command can be located in the system
 *
 * @param $command
 * @return bool true if available, false if not.
 */
function commandIsAvailable($command)
{
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		$template = "where %s";
	} else {
		$template = "command -v %s 2>/dev/null";
	}

	$returnCode = '';
	if (function_exists('exec')) {
		exec(sprintf($template, escapeshellarg($command)), $output, $returnCode);
	}

	return $returnCode === 0 ? true : false;
}

/**
 * Script to benchmark PHP and MySQL
 * @see https://github.com/odan/benchmark-php
 */
class BenchmarkPhp
{
	/**
	 * Executes the benchmark and returns an array in the format expected by renderTable
	 * @return array Benchmark results
	 */
	public static function run()
	{
		set_time_limit(120); // 2 minutes

		global $host_tiki, $dbs_tiki, $user_tiki, $pass_tiki;

		$options = array();

		$options['db.host'] = $host_tiki;
		$options['db.user'] = $user_tiki;
		$options['db.pw'] = $pass_tiki;
		$options['db.name'] = $dbs_tiki;

		$benchmarkResult = self::test_benchmark($options);

		$benchmark = $benchmarkResult['benchmark'];
		if (isset($benchmark['mysql'])) {
			foreach ($benchmark['mysql'] as $k => $v) {
				$benchmark['mysql.' . $k] = $v;
			}
			unset($benchmark['mysql']);
		}
		$benchmark['total'] = $benchmarkResult['total'];
		$benchmark = array_map(
			function ($v) {
				return array('value' => $v);
			},
			$benchmark
		);

		return $benchmark;
	}

	/**
	 * Execute the benchmark
	 * @param $settings database connection settings
	 * @return array Benchmark results
	 */
	protected static function test_benchmark($settings)
	{
		$timeStart = microtime(true);

		$result = array();
		$result['version'] = '1.1';
		$result['sysinfo']['time'] = date("Y-m-d H:i:s");
		$result['sysinfo']['php_version'] = PHP_VERSION;
		$result['sysinfo']['platform'] = PHP_OS;
		$result['sysinfo']['server_name'] = $_SERVER['SERVER_NAME'];
		$result['sysinfo']['server_addr'] = $_SERVER['SERVER_ADDR'];

		self::test_math($result);
		self::test_string($result);
		self::test_loops($result);
		self::test_ifelse($result);
		if (isset($settings['db.host']) && function_exists('mysqli_connect')) {
			self::test_mysql($result, $settings);
		}

		$result['total'] = self::timer_diff($timeStart);
		return $result;
	}

	/**
	 * Benchmark the execution of multiple math functions
	 * @param $result Benchmark results
	 * @param int $count Number of iterations
	 */
	protected static function test_math(&$result, $count = 99999)
	{
		$timeStart = microtime(true);

		$mathFunctions = array(
			"abs",
			"acos",
			"asin",
			"atan",
			"bindec",
			"floor",
			"exp",
			"sin",
			"tan",
			"pi",
			"is_finite",
			"is_nan",
			"sqrt",
		);
		for ($i = 0; $i < $count; $i++) {
			foreach ($mathFunctions as $function) {
				call_user_func_array($function, array($i));
			}
		}
		$result['benchmark']['math'] = self::timer_diff($timeStart);
	}

	/**
	 * Benchmark the execution of multiple string functions
	 * @param $result Benchmark results
	 * @param int $count Number of iterations
	 */
	protected static function test_string(&$result, $count = 99999)
	{
		$timeStart = microtime(true);
		$stringFunctions = array(
			"addslashes",
			"chunk_split",
			"metaphone",
			"strip_tags",
			"md5",
			"sha1",
			"strtoupper",
			"strtolower",
			"strrev",
			"strlen",
			"soundex",
			"ord",
		);

		$string = 'the quick brown fox jumps over the lazy dog';
		for ($i = 0; $i < $count; $i++) {
			foreach ($stringFunctions as $function) {
				call_user_func_array($function, array($string));
			}
		}
		$result['benchmark']['string'] = self::timer_diff($timeStart);
	}

	/**
	 * Benchmark the execution of loops
	 * @param $result Benchmark results
	 * @param int $count Number of iterations
	 */
	protected static function test_loops(&$result, $count = 999999)
	{
		$timeStart = microtime(true);
		for ($i = 0; $i < $count; ++$i) {
		}
		$i = 0;
		while ($i < $count) {
			++$i;
		}
		$result['benchmark']['loops'] = self::timer_diff($timeStart);
	}

	/**
	 * Benchmark the execution of conditional operators
	 * @param $result Benchmark results
	 * @param int $count Number of iterations
	 */
	protected static function test_ifelse(&$result, $count = 999999)
	{
		$timeStart = microtime(true);
		for ($i = 0; $i < $count; $i++) {
			if ($i == -1) {
			} elseif ($i == -2) {
			} else {
				if ($i == -3) {
				}
			}
		}
		$result['benchmark']['ifelse'] = self::timer_diff($timeStart);
	}

	/**
	 * Benchmark MySQL operations
	 * @param $result Benchmark results
	 * @param $settings MySQL connection information
	 * @return array
	 */
	protected static function test_mysql(&$result, $settings)
	{
		$timeStart = microtime(true);

		$link = mysqli_connect($settings['db.host'], $settings['db.user'], $settings['db.pw']);
		$result['benchmark']['mysql']['connect'] = self::timer_diff($timeStart);

		mysqli_select_db($link, $settings['db.name']);
		$result['benchmark']['mysql']['select_db'] = self::timer_diff($timeStart);

		$dbResult = mysqli_query($link, 'SELECT VERSION() as version;');
		$arr_row = mysqli_fetch_array($dbResult);
		$result['sysinfo']['mysql_version'] = $arr_row['version'];
		$result['benchmark']['mysql']['query_version'] = self::timer_diff($timeStart);

		$query = "SELECT BENCHMARK(1000000,ENCODE('hello',RAND()));";
		$dbResult = mysqli_query($link, $query);
		$result['benchmark']['mysql']['query_benchmark'] = self::timer_diff($timeStart);

		mysqli_close($link);

		$result['benchmark']['mysql']['total'] = self::timer_diff($timeStart);
		return $result;
	}

	/**
	 * Helper to calculate time elapsed
	 * @param $timeStart time to compare against now
	 * @return string time elapsed
	 */
	protected static function timer_diff($timeStart)
	{
		return number_format(microtime(true) - $timeStart, 3);
	}
}

/**
 * Identify files, like backup copies made by editors, or manual copies of the local.php files,
 * that may be accessed remotely and, because they are not interpreted as PHP, may expose the source,
 * which might contain credentials or other sensitive information.
 * Ref: http://feross.org/cmsploit/
 *
 * @param array $files Array of filenames. Suspicious files will be added to this array.
 * @param string $sourceDir Path of the directory to check
 */
function check_for_remote_readable_files(array &$files, $sourceDir = 'db')
{
	//fix dir slash
	$sourceDir = str_replace('\\', '/', $sourceDir);

	if (substr($sourceDir, -1, 1) != '/') {
		$sourceDir .= '/';
	}

	if (! is_dir($sourceDir)) {
		return;
	}

	$sourceDirHandler = opendir($sourceDir);

	if ($sourceDirHandler === false) {
		return;
	}

	while ($file = readdir($sourceDirHandler)) {
		// Skip ".", ".."
		if ($file == '.' || $file == '..') {
			continue;
		}

		$sourceFilePath = $sourceDir . $file;

		if (is_dir($sourceFilePath)) {
			check_for_remote_readable_files($files, $sourceFilePath);
		}

		if (! is_file($sourceFilePath)) {
			continue;
		}

		$pattern = '/(^#.*#|~|.sw[op])$/';
		preg_match($pattern, $file, $matches);

		if (! empty($matches[1])) {
			$files[] = $file;
			continue;
		}

		// Match "local.php.bak", "local.php.bck", "local.php.save", "local.php." or "local.txt", for example
		$pattern = '/local(?!.*[.]php$).*$/'; // The negative lookahead prevents local.php and other files which will be interpreted as PHP from matching.
		preg_match($pattern, $file, $matches);

		if (! empty($matches[0])) {
			$files[] = $file;
			continue;
		}
	}
}

function check_isIIS()
{
	static $IIS;
	// Sample value Microsoft-IIS/7.5
	if (! isset($IIS) && isset($_SERVER['SERVER_SOFTWARE'])) {
		$IIS = substr($_SERVER['SERVER_SOFTWARE'], 0, 13) == 'Microsoft-IIS';
	}
	return $IIS;
}

function check_hasIIS_UrlRewriteModule()
{
	return isset($_SERVER['IIS_UrlRewriteModule']) == true;
}

/**
 * Returns the number of users with data preferences encrypted with mcrypt
 * @return bool|int|mixed
 */
function check_userPreferencesMCrypt()
{
	global $tikilib;

	$query = 'SELECT count(DISTINCT user) FROM `tiki_user_preferences` WHERE `prefName` like \'dp.%\'';
	return $tikilib->getOne($query);
}

function get_content_from_url($url)
{
	if (function_exists('curl_init') && function_exists('curl_exec')) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
		if (isset($_SERVER) && isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
			curl_setopt($curl, CURLOPT_USERPWD, $_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']);
		}
		$content = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($http_code != 200) {
			$content = "fail-http-" . $http_code;
		}
		curl_close($curl);
	} else {
		$content = "fail-no-request-done";
	}
	return $content;
}

function createPage($title, $content)
{
	echo <<<END
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="//dev.tiki.org/vendor/twitter/bootstrap/dist/css/bootstrap.css" />
		<title>$title</title>
		<style type="text/css">
			table { border-collapse: collapse;}
			#middle {  margin: 0 auto; }
			.button {
				border-radius: 3px 3px 3px 3px;
				font-size: 12.05px;
				font-weight: bold;
				padding: 2px 4px 3px;
				text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
				color: #FFF;
				text-transform: uppercase;
			}
			.unsure {background: #f89406;}
			.bad, .risky { background-color: #bd362f;}
			.good, .safe { background-color: #5bb75b;}
			.info {background-color: #2f96b4;}
//			h1 { border-bottom: 1px solid #DADADA; color: #7e7363; }
		</style>
	</head>
	<body class="tiki_wiki ">
	<div id="fixedwidth" >
		<div class="header_outer">
			<div class="header_container">
				<div class="clearfix ">
					<header id="header" class="header">
					<div class="content clearfix modules" id="top_modules" style="min-height: 168px;">
						<div class="sitelogo" style="float: left">
END;
	echo tikiLogo();
							echo <<< END
						</div>
						<div class="sitetitles" style="float: left;">
							<div class="sitetitle" style="font-size: 42px;">$title</div>
						</div>
					</div>
					</header>
				</div>
			</div>
		</div>
		<div class="middle_outer">
			<div id="middle" >
				<div class="topbar clearfix">
					<h1 style="font-size: 30px; line-height: 30px; color: #fff; text-shadow: 3px 2px 0 #781437; margin: 8px 0 0 10px; padding: 0;">
					</h1>
				</div>
			</div>
			<div id="middle" >
				$content
			</div>
		</div>
	</div>
	<footer id="footer" class="footer" style="margin-top: 50px;">
	<div class="footer_liner">
		<div class="footerbgtrap" style="padding: 10px 0;">
			<a href="http://tiki.org" target="_blank" title="Powered by Tiki Wiki CMS Groupware">
END;
	echo tikiButton();
	echo <<< END
				<img src="img/tiki/tikibutton.png" alt="Powered by Tiki Wiki CMS Groupware" />
			</a>
		</div>
	</div>
</footer>
</div>
	</body>
</html>
END;
	die;
}

function tikiLogo()
{
	return '<img alt="Tiki Logo" src="data:image/png;base64,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%3D" />';
}

function tikiButton()
{
	return '<img alt="tikibutton" src="data:image/png;base64,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%3D%3D"';
}

function no_cache_found()
{
	global $php_properties;

	if (check_isIIS()) {
		$php_properties['ByteCode Cache'] = array(
			'fitness' => tra('info'),
			'setting' => 'N/A',
			'message' => tra('Neither APC, WinCache nor xCache is being used as the ByteCode Cache; if one of these were used and correctly configured, performance would be increased. See Admin->Performance in the Tiki for more details.')
		);
	} else {
		$php_properties['ByteCode Cache'] = array(
			'fitness' => tra('info'),
			'setting' => 'N/A',
			'message' => tra('Neither APC, xCache, nor OPcache is being used as the ByteCode Cache; if one of these were used and correctly configured, performance would be increased. See Admin->Performance in the Tiki for more details.')
		);
	}
}
