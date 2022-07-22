<?php
/*
======================= Coded By x-Phisher ======================
____  ___        __________.__    .__       .__                  
\   \/  /        \______   \  |__ |__| _____|  |__   ___________ 
 \     /   ______ |     ___/  |  \|  |/  ___/  |  \_/ __ \_  __ \
 /     \  /_____/ |    |   |   Y  \  |\___ \|   Y  \  ___/|  | \/
/___/\  \         |____|   |___|  /__/____  >___|  /\___  >__|   
      \_/                       \/        \/     \/     \/       
========================= xphisher.com ===========================
*/
function X_OS($USER_AGENT){
	$OS_ERROR    =   "Unknown OS Platform";
    $OS  =   array( '/windows nt 10/i'      =>  'Windows 10',
	                '/windows nt 6.3/i'     =>  'Windows 8.1',
	                '/windows nt 6.2/i'     =>  'Windows 8',
	                '/windows nt 6.1/i'     =>  'Windows 7',
	                '/windows nt 6.0/i'     =>  'Windows Vista',
	                '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
	                '/windows nt 5.1/i'     =>  'Windows XP',
	                '/windows xp/i'         =>  'Windows XP',
	                '/windows nt 5.0/i'     =>  'Windows 2000',
	                '/windows me/i'         =>  'Windows ME',
	                '/win98/i'              =>  'Windows 98',
	                '/win95/i'              =>  'Windows 95',
	                '/win16/i'              =>  'Windows 3.11',
	                '/macintosh|mac os x/i' =>  'Mac OS X',
	                '/mac_powerpc/i'        =>  'Mac OS 9',
	                '/linux/i'              =>  'Linux',
	                '/ubuntu/i'             =>  'Ubuntu',
	                '/iphone/i'             =>  'iPhone',
	                '/ipod/i'               =>  'iPod',
	                '/ipad/i'               =>  'iPad',
	                '/android/i'            =>  'Android',
	                '/blackberry/i'         =>  'BlackBerry',
	                '/webos/i'              =>  'Mobile');
    foreach ($OS as $regex => $value) { 
        if (preg_match($regex, $USER_AGENT)) {
            $OS_ERROR = $value;
        }

    }   
    return $OS_ERROR;
}
function X_Browser($USER_AGENT){
	$BROWSER_ERROR    =   "Unknown Browser";
    $BROWSER  =   array('/msie/i'       =>  'Internet Explorer',
                        '/firefox/i'    =>  'Firefox',
                        '/safari/i'     =>  'Safari',
                        '/chrome/i'     =>  'Chrome',
                        '/edge/i'       =>  'Edge',
                        '/opera/i'      =>  'Opera',
                        '/netscape/i'   =>  'Netscape',
                        '/maxthon/i'    =>  'Maxthon',
                        '/konqueror/i'  =>  'Konqueror',
                        '/UCBrowser/i'  =>  'UCBrowser',
                        '/Opr/i'  =>  'Opera',
                        '/Firebird/i'  =>  'Firebird',
                        '/Camino/i'  =>  'Camino',
                        '/Chimera/i' => 'Chimera',
                        '/Phoenix/i' => 'Phoenix',
                        '/icab/i' => 'iCab',
                        '/Lynx/i' => 'Lynx',
                        '/Links/i' => 'Links',
                        '/hotjava/i' => 'HotJava',
                        '/amaya/i' => 'Amaya',
                        '/IBrowse/i' => 'IBrowse',
                        '/iTunes/i' => 'iTunes',
                        '/Silk/i' => 'Silk',
                        '/Dillo/i' => 'Dillo', 
                        '/Maxthon/i' => 'Maxthon',
                        '/Arora/i' => 'Arora',
                        '/Galeon/i' => 'Galeon',
                        '/Iceape/i' => 'Iceape',
                        '/Iceweasel/i' => 'Iceweasel',
                        '/Midori/i' => 'Midori',
                        '/QupZilla/i' => 'QupZilla',
                        '/Namoroka/i' => 'Namoroka',
                        '/NetSurf/i' => 'NetSurf',
                        '/BOLT/i' => 'BOLT',
                        '/EudoraWeb/i' => 'EudoraWeb',
                        '/shadowfox/i' => 'ShadowFox',
                        '/Swiftfox/i' => 'Swiftfox',
                        '/Uzbl/i' => 'Uzbl',
                        '/UCBrowser/i' => 'UCBrowser',
                        '/Kindle/i' => 'Kindle',
                        '/wOSBrowser/i' => 'wOSBrowser',
                        '/Epiphany/i' => 'Epiphany', 
                        '/SeaMonkey/i' => 'SeaMonkey',
                        '/Avant Browser/i' => 'Avant Browser',
                        '/Internet Explorer/i' => 'Internet Explorer',
                        '/Safari/i' => 'Safari',
                        '/Mozilla/i' => 'Mozilla',
                        '/mobile/i'     =>  'Handheld Browser');
    foreach ($BROWSER as $regex => $value) { 
        if (preg_match($regex, $USER_AGENT)) {
            $BROWSER_ERROR = $value;
        }
    }
    return $BROWSER_ERROR;
}
?>
