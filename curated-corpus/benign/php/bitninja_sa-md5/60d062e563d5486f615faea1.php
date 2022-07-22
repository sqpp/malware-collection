<?php

/************************************************************************************
 ************************************************************************************
 **                                                                                **
 **  If you can read this text in your browser then you don't have PHP installed.  **
 **  Please install PHP 5.6.0 or higher.                                           **
 **                                                                                **
 ************************************************************************************
 ************************************************************************************/

if (file_exists(__DIR__ . '/vendor/silverstripe/installer-wizard/src/install.php')) {
    include __DIR__ . '/vendor/silverstripe/installer-wizard/src/install.php';
} elseif (file_exists(__DIR__ . '/../vendor/silverstripe/installer-wizard/src/install.php')) {
    include __DIR__ . '/../vendor/silverstripe/installer-wizard/src/install.php';
} else {
    include 'install-frameworkmissing.html';
}