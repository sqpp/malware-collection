<?php
///////////////////////////////////////////////////////////////////////////////
//
// NagiosQL
//
///////////////////////////////////////////////////////////////////////////////
//
// (c) 2005-2020 by Martin Willisegger
//
// Project   : NagiosQL
// Component : Admin configuration verification
// Website   : https://sourceforge.net/projects/nagiosql/
// Version   : 3.4.1
// GIT Repo  : https://gitlab.com/wizonet/NagiosQL
//
///////////////////////////////////////////////////////////////////////////////
//
// Path settings
// ===================
$strPattern = '(admin/[^/]*.php)';
$preRelPath  = preg_replace($strPattern, '', filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_STRING));
$preBasePath = preg_replace($strPattern, '', filter_input(INPUT_SERVER, 'SCRIPT_FILENAME', FILTER_SANITIZE_STRING));
//
// Define common variables
// =======================
$prePageId    = 25;
$preContent   = 'admin/import.htm.tpl';
$preAccess    = 1;
$preFieldvars = 1;
$intModus     = 0;
//
// Include preprocessing files
// ===========================
require $preBasePath.'functions/prepend_adm.php';
require $preBasePath.'functions/prepend_content.php';
//
// Initialize import class
// =======================
$myImportClass = new functions\NagImportClass($_SESSION);
$myImportClass->myDBClass     =& $myDBClass;
$myImportClass->myDataClass   =& $myDataClass;
$myImportClass->myConfigClass =& $myConfigClass;
//
// Get configuration set ID
// ========================
$myConfigClass->getConfigTargets($arrConfigSet);
$intConfigId  = $arrConfigSet[0];
//
// Process form variables
// ======================
if (isset($_FILES['datValue1']) && ($_FILES['datValue1']['name'] != '') && ($chkStatus == 1)) {
    // Upload Error
    if ($_FILES['datValue1']['error'] !== UPLOAD_ERR_OK) {
        $myVisClass->processMessage(
            translate('File upload error:'). ' ' .$_FILES['filMedia']['error'],
            $strErrorMessage
        );
    } else {
        $intModus = 1;
        $strFileName = tempnam($_SESSION['SETS']['path']['tempdir'], 'nagiosql_local_imp');
        move_uploaded_file($_FILES['datValue1']['tmp_name'], $strFileName);
        $intReturn   = $myImportClass->fileImport($strFileName, $intConfigId, $chkChbValue1);
        if ($intReturn != 0) {
            $myVisClass->processMessage($myImportClass->strErrorMessage, $strErrorMessage);
        } else {
            $myVisClass->processMessage($myImportClass->strInfoMessage, $strInfoMessage);
            $myDataClass->writeLog(translate('File imported - File [overwrite flag]:'). ' ' .
                    $_FILES['datValue1']['name']. ' [' .$chkChbValue1. ']');
        }
    }
}
if (($chkMselValue1[0] != '') && ($chkStatus == 1)) {
    /** @var array $chkMselValue1 */
    foreach ($chkMselValue1 as $elem) {
        $intModus    = 1;
        $myImportClass->strErrorMessage = '';
        $myImportClass->strInfoMessage  = '';
        $intReturn   = $myImportClass->fileImport($elem, $intConfigId, $chkChbValue1);
        if ($intReturn != 0) {
            $myVisClass->processMessage($myImportClass->strErrorMessage, $strErrorMessage);
        } else {
            $myVisClass->processMessage($myImportClass->strInfoMessage, $strInfoMessage);
            $myDataClass->writeLog(translate('File imported - File [overwrite flag]:'). ' ' .$elem. ' ['
                .$chkChbValue1. ']');
        }
    }
}
//
// Start content
// =============
$conttp->setVariable('TITLE', translate('Configuration import'));
$conttp->parse('header');
$conttp->show('header');
$conttp->setVariable('LANG_SEARCH_STRING', translate('Filter string'));
$conttp->setVariable('LANG_SEARCH', translate('Search'));
$conttp->setVariable('LANG_DELETE', translate('Delete'));
$conttp->setVariable('LANG_DELETE_SEARCH', translate('Reset filter'));
$conttp->setVariable('DAT_SEARCH', $chkTfSearch);
$conttp->setVariable('TEMPLATE', translate('Template definition'));
$conttp->setVariable('IMPORTFILE', translate('Import file'));
$conttp->setVariable('LOCAL_FILE', translate('Local import file'));
$conttp->setVariable('OVERWRITE', translate('Overwrite database'));
$conttp->setVariable('MAKE', translate('Import'));
$conttp->setVariable('ABORT', translate('Abort'));
$conttp->setVariable('CTRL_INFO', translate('Hold CTRL to select<br>more than one'));
$conttp->setVariable('IMAGE_PATH', $_SESSION['SETS']['path']['base_url']. 'images/');
$conttp->setVariable('ACTION_INSERT', filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_STRING));
$conttp->setVariable('DAT_IMPORTFILE_1', '');
$conttp->setVariable('IMPORT_INFO_1', translate('To prevent errors or misconfigurations, you should import your '
        . 'configurations in an useful order. We recommend to do it like this:<br><br><b><i>commands -> '
        . 'timeperiods -> contacttemplates -> contacts -> contactgroups -> hosttemplates -> hosts -> '
        . 'hostgroups -> servicetemplates -> services -> servicegroups</i></b><br><br>'));
$conttp->setVariable('IMPORT_INFO_2', '<span style="color:#FF0000">' .translate('<b>Check your configuration after '
        . 'import!</b><br>In cause of an error or an uncomplete configuration, re-importing the wrong configuration '
        . 'can solve the problem.'). '</span>');
$conttp->parse('filelist1');
// Get settings
$myConfigClass->getConfigValues($intConfigId, 'method', $intMethod);
$myConfigClass->getConfigValues($intConfigId, 'basedir', $strBaseDir);
$myConfigClass->getConfigValues($intConfigId, 'hostconfig', $strHostDir);
$myConfigClass->getConfigValues($intConfigId, 'serviceconfig', $strServiceDir);
$myConfigClass->getConfigValues($intConfigId, 'backupdir', $strBackupDir);
$myConfigClass->getConfigValues($intConfigId, 'hostbackup', $strHostBackupDir);
$myConfigClass->getConfigValues($intConfigId, 'servicebackup', $strServiceBackupDir);
$myConfigClass->getConfigValues($intConfigId, 'importdir', $strImportDir);
$myConfigClass->getConfigValues($intConfigId, 'nagiosbasedir', $strNagiosBaseDir);
if ($intMethod == 1) {
    // Building local file list
    $arrOutput1 = array();
    $myConfigClass->storeDirToArray(
        $strBaseDir,
        "\.cfg",
        'cgi.cfg|nagios.cfg|nrpe.cfg|nsca.cfg',
        $arrOutput1,
        $strErrorMessage
    );
    if ($strNagiosBaseDir != $strBaseDir) {
        $myConfigClass->storeDirToArray(
            $strNagiosBaseDir,
            "\.cfg",
            'cgi.cfg|nagios.cfg|nrpe.cfg|nsca.cfg',
            $arrOutput1,
            $strErrorMessage
        );
    }
    $myConfigClass->storeDirToArray($strHostDir, "\.cfg", '', $arrOutput1, $strErrorMessage);
    $myConfigClass->storeDirToArray($strServiceDir, "\.cfg", '', $arrOutput1, $strErrorMessage);
    $myConfigClass->storeDirToArray($strHostBackupDir, "\.cfg_", '', $arrOutput1, $strErrorMessage);
    $myConfigClass->storeDirToArray($strServiceBackupDir, "\.cfg_", '', $arrOutput1, $strErrorMessage);
    if (($strImportDir != '') && ($strImportDir != $strBaseDir) && ($strImportDir != $strNagiosBaseDir)) {
        $myConfigClass->storeDirToArray($strImportDir, "\.cfg", '', $arrOutput1, $strErrorMessage);
    }
    $arrOutput2=array_unique($arrOutput1);
    if (is_array($arrOutput2) && (count($arrOutput2) != 0)) {
        foreach ($arrOutput2 as $elem) {
            if (($chkTfSearch == '') || (substr_count($elem, $chkTfSearch) != 0)) {
                $conttp->setVariable('DAT_IMPORTFILE_2', $elem);
                $conttp->parse('filelist2');
            }
        }
    }
} elseif ($intMethod == 2) {
    // Set up basic connection
    if ($myConfigClass->getFTPConnection($intConfigId) == '0') {
        $arrFiles  = array();
        $arrFiles1 = ftp_nlist($myConfigClass->resConnectId, $strBaseDir);
        if (is_array($arrFiles1)) {
            $arrFiles = array_merge($arrFiles, $arrFiles1);
        }
        $arrFiles2 = ftp_nlist($myConfigClass->resConnectId, $strHostDir);
        if (is_array($arrFiles2)) {
            $arrFiles = array_merge($arrFiles, $arrFiles2);
        }
        $arrFiles3 = ftp_nlist($myConfigClass->resConnectId, $strServiceDir);
        if (is_array($arrFiles3)) {
            $arrFiles = array_merge($arrFiles, $arrFiles3);
        }
        $arrFiles4 = ftp_nlist($myConfigClass->resConnectId, $strHostBackupDir);
        if (is_array($arrFiles4)) {
            $arrFiles = array_merge($arrFiles, $arrFiles4);
        }
        $arrFiles5 = ftp_nlist($myConfigClass->resConnectId, $strServiceBackupDir);
        if (is_array($arrFiles5)) {
            $arrFiles = array_merge($arrFiles, $arrFiles5);
        }
        if ($strImportDir != '') {
            $arrFiles6 = ftp_nlist($myConfigClass->resConnectId, $strImportDir);
            if (is_array($arrFiles6)) {
                $arrFiles = array_merge($arrFiles, $arrFiles6);
            }
        }
        if (is_array($arrFiles) && (count($arrFiles) != 0)) {
            foreach ($arrFiles as $elem) {
                if (!substr_count($elem, 'cfg')) {
                    continue;
                }
                if (substr_count($elem, 'resource.cfg')) {
                    continue;
                }
                if (substr_count($elem, 'nagios.cfg')) {
                    continue;
                }
                if (substr_count($elem, 'cgi.cfg')) {
                    continue;
                }
                if (substr_count($elem, 'nrpe.cfg')) {
                    continue;
                }
                if (substr_count($elem, 'nsca.cfg')) {
                    continue;
                }
                if (($chkTfSearch == '') || (substr_count($elem, $chkTfSearch) != 0)) {
                    $conttp->setVariable('DAT_IMPORTFILE_2', str_replace('//', '/', $elem));
                    $conttp->parse('filelist2');
                }
            }
        }
        ftp_close($myConfigClass->resConnectId);
    } else {
        $myVisClass->processMessage($myConfigClass->strErrorMessage, $strErrorMessage);
    }
} elseif ($intMethod == 3) {
    // Set up basic connection
    if ($myConfigClass->getSSHConnection($intConfigId) == '0') {
        $arrFiles  = array();
        $intReturn = $myConfigClass->sendSSHCommand('ls ' .$strBaseDir. '*.cfg', $arrFiles1);
        if (($intReturn == 0) && is_array($arrFiles1)) {
            $arrFiles = array_merge($arrFiles, $arrFiles1);
        }
        $intReturn = $myConfigClass->sendSSHCommand('ls ' .$strHostDir. '*.cfg', $arrFiles2);
        if (($intReturn == 0) && is_array($arrFiles2)) {
            $arrFiles = array_merge($arrFiles, $arrFiles2);
        }
        $intReturn = $myConfigClass->sendSSHCommand('ls ' .$strServiceDir. '*.cfg', $arrFiles3);
        if (($intReturn == 0) && is_array($arrFiles3)) {
            $arrFiles = array_merge($arrFiles, $arrFiles3);
        }
        $intReturn = $myConfigClass->sendSSHCommand('ls ' .$strHostBackupDir. '*.cfg*', $arrFiles4);
        if (($intReturn == 0) && is_array($arrFiles4)) {
            $arrFiles = array_merge($arrFiles, $arrFiles4);
        }
        $intReturn = $myConfigClass->sendSSHCommand('ls ' .$strServiceBackupDir. '*.cfg*', $arrFiles5);
        if (($intReturn == 0) && is_array($arrFiles5)) {
            $arrFiles = array_merge($arrFiles, $arrFiles5);
        }
        if ($strImportDir != '') {
            $intReturn = $myConfigClass->sendSSHCommand('ls ' .$strImportDir. '*.cfg', $arrFiles6);
            if (($intReturn == 0) && is_array($arrFiles6)) {
                $arrFiles = array_merge($arrFiles, $arrFiles6);
            }
        }
        if (is_array($arrFiles) && (count($arrFiles) != 0)) {
            foreach ($arrFiles as $elem) {
                if (!substr_count($elem, 'cfg')) {
                    continue;
                }
                if (substr_count($elem, 'resource.cfg')) {
                    continue;
                }
                if (substr_count($elem, 'nagios.cfg')) {
                    continue;
                }
                if (substr_count($elem, 'cgi.cfg')) {
                    continue;
                }
                if (substr_count($elem, 'nrpe.cfg')) {
                    continue;
                }
                if (substr_count($elem, 'nsca.cfg')) {
                    continue;
                }
                if (($chkTfSearch == '') || (substr_count($elem, $chkTfSearch) != 0)) {
                    $conttp->setVariable('DAT_IMPORTFILE_2', str_replace('//', '/', $elem));
                    $conttp->parse('filelist2');
                }
            }
        }
    } else {
        $myVisClass->processMessage($myConfigClass->strErrorMessage, $strErrorMessage);
    }
}
// Check access rights for adding new objects
if ($myVisClass->checkAccountGroup($prePageKey, 'write') != 0) {
    $conttp->setVariable('ADD_CONTROL', 'disabled="disabled"');
}
if ($strErrorMessage != '') {
    $conttp->setVariable('ERRORMESSAGE', $strErrorMessage);
}
$conttp->setVariable('INFOMESSAGE', $strInfoMessage);
$conttp->parse('main');
$conttp->show('main');
//
// Process footer
// ==============
$maintp->setVariable('VERSION_INFO', "<a href='https://sourceforge.net/projects/nagiosql/' "
        . "target='_blank'>NagiosQL</a> $setFileVersion");
$maintp->parse('footer');
$maintp->show('footer');
