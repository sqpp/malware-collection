<?php
if ( ! defined( 'DIR_SYSTEM' ) ) {
	chdir( '../../../../..' );
	require_once './index.php';

	echo PHP_EOL . 'CHDIR and include index.php';
}

echo PHP_EOL . 'Stripe config';
echo PHP_EOL . 'CWD: ' . getcwd() . PHP_EOL;

if ( ! class_exists( 'Advertikon\Stripe\Advertikon' ) ) {
	require_once '../../advertikon.php';
	require_once '../advertikon.php';
}

Advertikon\Stripe\Advertikon::instance();

require_once( 'my_unit.php' );
//require_once( 'test_listner.php' );

