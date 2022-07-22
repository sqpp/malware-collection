<?php
/*
	MP3-jPlayer 2.0
	www.sjward.org
*/

function strip_scripts ( $field )
{ 
	$search = array(
		'@<script[^>]*?>.*?</script>@si',  // Strip out javascript 
		'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly 
		'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA 
	); 
	
	$text = preg_replace( $search, '', $field ); 
	return $text; 
}



$mp3 = false;
$playerID = "";
$fp = "";
$file = "";
$dbug = "";
$sent = "";
$pagetext = '';
$js_pagetext = '';
$rooturl = preg_replace("/^www\./i", "", $_SERVER['HTTP_HOST']);



if ( isset($_GET['mp3']) ) {
		
	
	$mp3 = strip_tags($_GET['mp3']);
	$mp3 = rawurldecode( $mp3 );
	$mp3 = strip_scripts( $mp3 );
	
	$playerID = ( isset($_GET['pID']) ) ? strip_tags($_GET['pID']) : "";
	$playerID = strip_scripts( $playerID );
	
	$matches = array();
	if ( preg_match("!\.(mp3|mp4|m4a|ogg|oga|wav|webm)$!i", $mp3, $matches) ) {
		
		$fileExtension = $matches[0];
		if ( $fileExtension === 'mp3' || $fileExtension === 'mp4' || $fileExtension === 'm4a' ) {
			$mimeType = 'audio/mpeg';
		}
		elseif( $fileExtension === 'ogg' || $fileExtension === 'oga' ) {
			$mimeType = 'audio/ogg';
		}
		else {
			$mimeType = 'audio/' . ( str_replace('.', '', $fileExtension) );
		}
		
		
		$sent = substr($mp3, 3);
		$file = substr(strrchr($sent, "/"), 1);
		
		if ( ($lp = strpos($sent, $rooturl)) || preg_match("!^/!", $sent) ) { //if local
			
			if ( $lp !== false ) { //url
				
				$fp = str_replace($rooturl, "", $sent);
				$fp = str_replace("www.", "", $fp);
				$fp = str_replace("http://", "", $fp);
				$fp = str_replace("https://", "", $fp);
			
			} else { //folder path
				
				$fp = $sent;
			}
			
			if ( ($fsize = @filesize($_SERVER['DOCUMENT_ROOT'] . $fp)) !== false ) { //if file can be read then set headers and cookie
				
				header('Content-Type: ' . $mimeType);
				$cookiename = 'mp3Download' . $playerID;
				setcookie($cookiename, "true", 0, '/', '', '', false);
				header('Accept-Ranges: bytes');  // download resume
				header('Content-Disposition: attachment; filename=' . $file);
				
				header('Content-Length: ' . $fsize);
				
				readfile($_SERVER['DOCUMENT_ROOT'] . $fp);
				
				
				$dbug .= "#read failed"; //if past readfile() then something went wrong
				
			} else {
				
				$dbug .= "#no file";
			}
				
		} else {
			
			$dbug .= "#unreadable"; 
		}
	
	} else {

		$dbug .= "#unsupported format";
	}

} else {

	$dbug .= "#no get param";
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Download Audio</title>
	</head>
	<body>

		<?php 
		echo $js_pagetext;
		$info = "<p>
			Get: " . $mp3 . "<br />
			Sent: " . $sent . "<br />
			File: " . $file . "<br />
			Open: " . $_SERVER['DOCUMENT_ROOT'] . $fp . "<br />
			Root: " . $rooturl . "<br />
			pID: " . $playerID . "<br />
			Dbug: " . $dbug . "<br />
			extension: " . $fileExtension . "</p>";
		echo $info;
		
		if ( $playerID != "" ) { 
		?>	
			
			<script type="text/javascript">
				if ( typeof window.parent.MP3_JPLAYER.dl_dialogs !== 'undefined' ) {
					window.parent.MP3_JPLAYER.dl_dialogs[<?php echo $playerID; ?>] = window.parent.MP3_JPLAYER.vars.message_fail;
				}
			</script>
				
		<?php 
		} 
		?>

	</body>
</html>