<?php

include "../../cli.php";
ini_set('display_errors', false);
ini_set('display_startup_errors', false);
session_start();

$iUserId = (int) Phpfox::getUserId();
$iTotalNotifications = Phpfox::getService('notification')->getUnseenTotal();

$iTotalFriendRequests = Phpfox::getService('friend.request')->getUnseenTotal();
$iFirst = isset($_REQUEST['first']) ? (int) $_REQUEST['first'] : 1;

$sJson = Phpfox::getLib('session')->get('yn_fanot_' . $iUserId);

try
{
    $aContents = json_decode(base64_decode($sJson));
}
catch (exception $ex)
{
    $aContents = array();
}

if ($iFirst || empty($aContents) || !isset($aContents->iNextTime) || $aContents->iNextTime <= PHPFOX_TIME)
{
    Phpfox::massCallback('getGlobalNotifications');
    Phpfox::getBlock('fanot.link');
    $sHtml = Phpfox::getLib('ajax')->getContent(false);
    $sHtml = str_replace("'", "\'", $sHtml);
    if (trim($sHtml) != '')
    {    	
        $aContents = array('iTotalNotifications' => $iTotalNotifications,
            'iTotalFriendRequests' => $iTotalFriendRequests,
            'sHtml' => $sHtml,
            'iNextTime' => PHPFOX_TIME + (int) Phpfox::getParam('fanot.notification_refresh_time'),
        );
        $sJson = json_encode($aContents);
        Phpfox::getLib('session')->set('yn_fanot_' . $iUserId, base64_encode($sJson));
    }
    else {
        $aContents = array('iTotalNotifications' => 0,
            'iTotalFriendRequests' => 0,
            'sHtml' => '',
            'iNextTime' => PHPFOX_TIME + (int) Phpfox::getParam('fanot.notification_refresh_time'),
        );
        $sJson = json_encode($aContents);
    }
}
else
{
    $aContents = array('iTotalNotifications' => 0,
        'iTotalFriendRequests' => 0,
        'sHtml' => '',
        'iNextTime' => PHPFOX_TIME + (int) Phpfox::getParam('fanot.notification_refresh_time'),
    );
    $sJson = json_encode($aContents);
}
echo  $sJson;
