<?php

error_reporting(E_ALL);

function inputsec($string){
	
	//get_magic_quotes_gpc is depricated in php 7.4
	if(version_compare(PHP_VERSION, '7.4', '<')){
		if(!get_magic_quotes_gpc()){
		
			$string = addslashes($string);
		
		}else{
		
			$string = stripslashes($string);
			$string = addslashes($string);
		
		}
	}else{
		$string = addslashes($string);
	}
	
	// This is to replace ` which can cause the command to be executed in exec()
	$string = str_replace('`', '\`', $string);
	
	return $string;

}

function softdie($txt){
	$array = array();
	$array['sreq'] = $GLOBALS['sreq'];
	$array['result'] = $txt;
	echo '<aefer>'.base64_encode(serialize($array)).'</aefer>';die();
}

// First Delete yourself !
@unlink(__FILE__); // More has to be done here !

// Do we need to rename the .htaccess back ? 
if(file_exists('soft_htaccess')){
	@rename('soft_htaccess', '.htaccess');
}

// The requirements
$sreq = unserialize(base64_decode('[[[sreq]]]'));

// Do we need to do any checking ?
if(is_array($sreq)){
	
	foreach($sreq as $k => $v){
	
		if($v['type'] == 'function_exists'){
			if(function_exists($v['name'])){
				$sreq[$k]['result'] = 0; // Function exists
			}else{
				$sreq[$k]['result'] = 1; // Error
			}
		}
		
		if($v['type'] == 'extension'){
			if(extension_loaded($v['name'])){
				$sreq[$k]['result'] = 0; // Extension loaded
			}else{
				$sreq[$k]['result'] = 1; // Error
			}
		}
		
		if($v['type'] == 'version'){
			if($v['check'] == 'mysql'){
				// Need to do this
			}
			
			if($v['check'] == 'perl'){
				// Need to do this
			}
			
			if($v['check'] == 'php'){
				$sreq[$k]['result'] = phpversion(); // Dump the PHP version
				if(defined('PHP_BINDIR')){
					$sreq[$k]['eu_php_bin'] = PHP_BINDIR.'/php'; // Dump the PHP bin path
				}
			}
		}	
	}
}
set_time_limit(0);

$max_exec = (int) ini_get('max_execution_time');

softdie('DONE');

