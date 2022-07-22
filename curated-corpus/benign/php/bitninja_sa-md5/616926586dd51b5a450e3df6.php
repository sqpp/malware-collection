<?php
/*
*	Local Downloader
*	MP3-jPlayer 2.5
*	http://mp3-jplayer.com
*	---
*/


//~~~ Flag
function checkCharsOK ( $string ) 
{	
	$badChars = 0;
	$charArray = str_split( $string );
	
	if ( is_array( $charArray ) ) {	
		foreach ( $charArray as $char ) {
			$ascii = ord( $char );
			if ( 0 === $ascii ) { //null bytes
				$badChars += 1;
			}
		}
	} else {
		$badChars += 1;
	}
	return ( $badChars === 0 ? true : false );
}


//~~~ Clean
function stripScripts ( $field ) 
{ 
	$search = array(
		'@<script[^>]*?>.*?</script>@si',	// Javascript 
		'@<style[^>]*?>.*?</style>@siU',    // Style tags 
		'@<![\s\S]*?--[ \t\n\r]*>@',        // Multi-line comments including CDATA
		'@%00@',							// Null bytes
		'@\.\.@'							// Traversals
	); 
	$text = preg_replace( $search, '', $field ); 
	return $text; 
}



// Start ~~~~~~~~~~~~~~~~~~~~~~

$dbug 		= "";
$sent 		= "";
$mp3 		= false;
$playerID 	= "";
$fp 		= "";
$file 		= "";
$rooturl 	= preg_replace("/^www\./i", "", $_SERVER['HTTP_HOST']);


if ( isset($_GET['mp3']) ) {
	
	
	// Clean the url/path
	$mp3 	= strip_tags($_GET['mp3']);
	$mp3 	= rawurldecode( $mp3 );
	$mp3 	= stripScripts( $mp3 );
	$sent 	= substr($mp3, 3);
	if ( ! checkCharsOK( $sent ) ) {
		die();
	}
	
	
	// Clean player ID
	$playerID = ( isset($_GET['pID']) ) ? strip_tags($_GET['pID']) : "";
	$playerID = stripScripts( $playerID );
	$playerID = preg_replace( '![^0-9]!', '', $playerID );
	if ( $playerID == '' ) {
		die();
	}
		
	
	// Check it's a valid file type
	$matches = array();
	if ( preg_match("!\.(mp3|mp4|m4a|ogg|oga|wav|webm)$!i", $mp3, $matches) ) 
	{
		
		// Decide the mime type
		$fileExtension = $matches[0];
		if ( $fileExtension === 'mp3' || $fileExtension === 'mp4' || $fileExtension === 'm4a' ) {
			$mimeType = 'audio/mpeg';
		} elseif( $fileExtension === 'ogg' || $fileExtension === 'oga' ) {
			$mimeType = 'audio/ogg';
		} else {
			$mimeType = 'audio/' . ( str_replace('.', '', $fileExtension) );
		}
		
		
		// Get the file's name
		$file = substr( strrchr( $sent, "/" ), 1 );
		
		
		// Check that the file is locally hosted
		if ( ($lp = strpos($sent, $rooturl)) || preg_match("!^/!", $sent) ) 
		{ 
			if ( $lp !== false ) { 					
				// It's url format, prep as path
				$fp = str_replace($rooturl, "", $sent);
				$fp = str_replace("www.", "", $fp);
				$fp = str_replace("http://", "", $fp);
				$fp = str_replace("https://", "", $fp);
			} else { 
				// It's a path already
				$fp = $sent;
			}
			
			
			// Bail if path to the file is not valid
			$realPath = realpath( $_SERVER['DOCUMENT_ROOT'] . $fp );
			if ( $realPath === false ) {
				die();
			} 
			

			// Try to send file
			$fsize = @filesize( $realPath );
			if ( $fsize !== false ) { 
				
				//set headers and cookie
				header('Content-Type: ' . $mimeType);
				$cookiename = 'mp3Download' . $playerID;
				setcookie($cookiename, "true", 0, '/', '', '', false);
				header('Accept-Ranges: bytes');  // download resume
				header('Content-Disposition: attachment; filename=' . $file);
				header('Content-Length: ' . $fsize);
				
				//Send the file
				readfile( $realPath );
				
				//If past readfile() then something went wrong
				$dbug .= "#read failed"; 
				
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
