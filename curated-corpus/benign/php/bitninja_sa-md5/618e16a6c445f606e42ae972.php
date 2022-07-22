<?php
error_reporting(E_ALL);
include_once(SSF_WP_INCLUDES_PATH."/top-nav.php");

if (!function_exists("ssf_wp_initialize_variables")) { include("../ssf-wp-functions.php"); }
?>
<div class='wrap'>
<?php 

    $msg="";
    if(isset($_FILES["default_location"]["name"])){
		if(!empty($_FILES["default_location"]["name"])){
	    $extension= explode(".", $_FILES['default_location']['name']);
         // isset Determines if a variable is set and is not NULL. Set Size Limit less then 10 MB=10485760 bytes. Extension must be CSV.
		if (isset($_FILES['default_location']) && $_FILES['default_location']['size'] < 10485760 && $extension[1]== 'csv')
		{
		   $dir=SSF_WP_UPLOADS_PATH."/csv/import/";
			 if(!is_dir($dir))
			{
				mkdir($dir, 0777, true);
				@chmod($dir, 0777);
			}
			$dest=$dir.$_FILES["default_location"]["name"];
			if(move_uploaded_file($_FILES["default_location"]["tmp_name"],$dest)){
			
			   $msg=csvSave($dest,$wpdb);
			   print "<div class='ssf_wp_admin_success'>".__(" $msg",SSF_WP_TEXT_DOMAIN).". $view_link</div> <!--meta http-equiv='refresh' content='0'-->"; 				
			}else{
			 
			 
			  print "<div class='ssf-wp-menu-alert'>".__(" CSV Upload failed to the database.",SSF_WP_TEXT_DOMAIN).". $view_link</div>"; 
			   
			}
			
			
		}else{
		
		     
			 print "<div class='ssf-wp-menu-alert'>".__(" Error in File.",SSF_WP_TEXT_DOMAIN).". $view_link</div>"; 
			 
		}
		
	  }else{
		  print "<div class='ssf-wp-menu-alert'>".__(" Please Upload a CSV file ",SSF_WP_TEXT_DOMAIN)."</div>"; 
		  
	  }
 
    }
	
if (empty($_POST)) {ssf_wp_move_upload_directories();}
if (!empty($_POST['ssf_wp_map_settings'])) { 
    
    function ssf_wp_md_save($data) {
	global $ssf_wp_vars;
	

	
	$ssf_wp_vars['sensor']=(empty($_POST['ssf_wp_geolocate']))? "false" : "true";
	

	foreach ($data as $value) {
	    
	    if (!empty($value['field_name'])) {
		$fname = $value['field_name'];
	
		if (!empty($value['field_type']) && $value['field_type'] == "checkbox") {
			
			if (is_array($fname)) {
				foreach ($fname as $the_field) {
					$ssf_wp_vars[$the_field] = (empty($_POST["ssf_wp_".$the_field]))? 0 : $_POST["ssf_wp_".$the_field] ;
				}
			} else {
				$ssf_wp_vars[$fname] = (empty($_POST["ssf_wp_".$fname]))? 0 : $_POST["ssf_wp_".$fname] ;
			}
		} else {
			if (is_array($fname)) {
				$fctr = 0;
				foreach ($fname as $the_field) {
					$post_data = (isset($_POST["ssf_wp_".$the_field]))? $_POST["ssf_wp_".$the_field] : $_POST[$the_field] ;
					$post_data = (!empty($value['stripslashes'][$fctr]) && $value['stripslashes'][$fctr] == 1)? stripslashes($post_data) : $post_data;
					$post_data = (!empty($value['numbers_only'][$fctr]) && $value['numbers_only'][$fctr] == 1)? preg_replace("@[^0-9]@", "", $post_data) : $post_data;
					$ssf_wp_vars[$the_field] = $post_data;
					$fctr++;
				}
			} else {
				$post_data = (isset($_POST["ssf_wp_".$fname]))? $_POST["ssf_wp_".$fname] : $_POST[$fname] ;
				$post_data = (!empty($value['stripslashes']) && $value['stripslashes'] == 1)? stripslashes($post_data) : $post_data;
				$post_data = (!empty($value['numbers_only']) && $value['numbers_only'] == 1)? preg_replace("@[^0-9]@", "", $post_data) : $post_data;
				$ssf_wp_vars[$fname] = $post_data;
			}
		}
	    }
	    
	}

	ssf_wp_data('ssf_wp_vars', 'update', $ssf_wp_vars);
	
    }
    
  ssf_wp_initialize_variables();
    include(SSF_WP_INCLUDES_PATH."/export.php");
    ssf_wp_md_save($ssf_wp_mdo);
    unset($ssf_wp_mdo);  
 
 ssf_wp_initialize_variables(); 
 
	print "<div class='ssf_wp_admin_success' >".__(" Settings successfully saved.", SSF_WP_TEXT_DOMAIN)." $view_link</div> <!--meta http-equiv='refresh' content='0'-->";
}




$update_button="<input type='button'  onclick='document.location.href=\"".SSF_WP_BASE."/csv/sample-data.csv\"' value='".__("Download Sample CSV", SSF_WP_TEXT_DOMAIN)."' class='button-primary'> <input type='submit'  value='".__("Import & Geo Code", SSF_WP_TEXT_DOMAIN)."' class='button-primary'>";

print "
<div class='input_section'>
	<form method='post' name='settings' enctype='multipart/form-data'>
	
					<div class='input_title'>
						
						<h3><span class='fa fa-globe'>&nbsp;</span> Import</h3>
						<span class='submit'>
						$update_button
						</span>
						<div class='clearfix'></div>
					</div>
					<div class='all_options'>
";

function ssf_wp_md_display($data, $input_zone, $template, $additional_classes = "") {
    $GLOBALS['input_zone_type'] = $input_zone;
    $filtered_data = array_filter($data, "filter_ssf_wp_mdo");
    unset($GLOBALS['input_zone_type']);
	$showregion=0;
    
    $labels_ctr = 0;
    foreach ($filtered_data as $key => $value) {
     
    	if ($template == 1) {
		
		$the_row_id = (!empty($value["row_id"]))? " id = '$value[row_id]' " : "";
		$hide_row = (!empty($value['hide_row']) && $value['hide_row'] == true)? "style='display:none' " : "" ;
		$colspan = (!empty($value['colspan']) && $value['colspan'] > 1)? "colspan = '$value[colspan]'" : "" ;
		
		print "<div class='option_input option_text'>
					<label for='shortname_logo'>".$value['label'];
		if (!empty($value['more_info_label'])) {
			print "&nbsp;(<a href='#$value[more_info_label]' rel='ssf_wp_pop'>?</a>)&nbsp;";
		}
		print "</label>";
	   if (empty($value['colspan']) || $value['colspan'] < 2) {
		print "".$value['input_template']."<div class='clearfix'></div></div>";

	    }
	   
		
    	} elseif ($template == 2) {
		
		
		if ($labels_ctr % 3 == 0) {
			$the_row_id = (!empty($value["row_id"]))? " id = '$value[row_id]' " : "";
			
		}	
		
		if($value['label']=="Search bar caption"){
		print "<div class='input_title'>
						
						<h3><span class='fa fa-bookmark'>&nbsp;</span> Labels</h3>
						
						<div class='clearfix'></div>
					</div>";
		}
		
		
		if($value['label']=="Loading Google Maps..."){
		print "<div class='input_title'>
						
						<h3><span class='fa fa-exclamation-circle'>&nbsp;</span> Notification</h3>
						
						<div class='clearfix'></div>
					</div>";
		}
		
		
		if($value['label']=="We have encountered an error."){
		  $showregion=1;
		}
		
		
		
		
		print "<div class='option_input option_text'>";
		print "<label for='shortname_logo'>".$value['label']."</label>".$value['input_template']."<small></small><div class='clearfix'></div></div>";
	
if($showregion==1){
$showregion=0;
		print "<div class='input_title'>
						
						<h3><span class='fa fa-map-marker'>&nbsp;</span> Regions</h3>
						
						<div class='clearfix'></div>
					</div>";
		}

    	}
    	    	
    }
    
    print "</div>";
}

include(SSF_WP_INCLUDES_PATH."/export.php");


ssf_wp_md_display($ssf_wp_mdo, 'defaults', 1);

if (function_exists('icl_register_string')) {
    
	$GLOBALS['input_zone_type'] = "labels";
	$labels_arr = array_filter($ssf_wp_mdo, "filter_ssf_wp_mdo");
	unset($GLOBALS['input_zone_type']);
	
	
	foreach ($labels_arr as $value) {
		$the_field = $value['field_name'];
		$varname = "ssf_wp_".$the_field;
		
		icl_register_string(SSF_WP_DIR, $value['label'], $ssf_wp_vars[$the_field]);
	}
	
	
	icl_register_string(SSF_WP_DIR, 'Search Button Filename', "search_button.png");
	icl_register_string(SSF_WP_DIR, 'Search Button Filename (Down State)', "search_button_down.png");
	icl_register_string(SSF_WP_DIR, 'Search Button Filename (Over State)', "search_button_over.png");
}

print "</form>";
print "<form method=\"post\">";
print "<input type=\"hidden\" name='export' value=\"export\">
				<div class='input_title'>
					<label for=\"shortname_logo\"><h3><span class='fa fa-sign-out'>&nbsp;</span> Export</h3></label>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"button\" onclick=\"exportStores();\" class=\"button-primary\" value=\"Export Stores\" style=\"float:right; margin-right:20px;\">
			     <div class=\"clearfix\"></div>
				</div>
				</div>";

print "</form>";

   
?>

</div>
<?php include(SSF_WP_INCLUDES_PATH."/ssf-wp-footer.php"); ?>
<?php
function PreSuperTags($value){
	$value=str_replace("&", "&amp;", $value);
	$value=str_replace('"', "&quot;", $value);
	$value=str_replace("'", "&#39;", $value);
	$value=str_replace(">", "&gt;", $value);
	$value=str_replace("<", "&lt;", $value);
	$value=str_replace(" & ", " &amp; ", $value);
	$value=str_replace("," ,"&#44;" ,$value);
	return stripslashes($value);
}

function csvSave($file,$wpdb){
   
			    //$handle is a valid file pointer to a file successfully opened by fopen(), popen(), or fsockopen(). fopen() used to open file.
                 $handle = fopen($file, "r"); 
   $import=array();
   if ($handle !== FALSE) 
				{
		
	// fgets() Gets a line from file pointer and read the first line from $handle and ignore it.   
					fgets($handle);
	// While loop used here and  fgetcsv() parses the line it reads for fields in CSV format and returns an array containing the fields read.
					ini_set('auto_detect_line_endings',TRUE);
					
					while (($data = fgetcsv($handle)) !== FALSE)
					{
					
					$address= str_replace('\'', '\\\'',$data[2]);
					$address.= ", ".str_replace('\'', '\\\'',$data[3]);
					$address .= ", ".str_replace('\'', '\\\'',$data[4]);
					$address.= ", ".str_replace('\'', '\\\'',$data[5]);
					$address.= ", ".str_replace('\'', '\\\'',$data[6]);

					$arrayLatLong=geolocate($address);

					$qry="INSERT INTO ".SSF_WP_TABLE."(`ssf_wp_id`, `ssf_wp_store`, `ssf_wp_tags`,`ssf_wp_address`,
   					 `ssf_wp_city`, `ssf_wp_state`, `ssf_wp_country`, `ssf_wp_zip`, 
					 `ssf_wp_phone`, `ssf_wp_fax`, `ssf_wp_email`,`ssf_wp_url`,`ssf_wp_ext_url`, 
					  `ssf_wp_description`, `ssf_wp_hours`,`ssf_wp_latitude`, 
					  `ssf_wp_longitude`)
					VALUES (null,%s,%s,%s,
					%s,%s,%s,%s,
					%s,%s,%s,%s,
					%s,%s,%s,%s,
					%s)";
					if (!empty($data[1])){
					  	$tag =ssf_wp_prepare_tag_string($data[1]);
					}else{ $tag='';}
	$q = $wpdb->prepare($qry,$data[0],PreSuperTags($tag),$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8]
	                                ,$data[9],$data[10],$data[11],$data[12],$data[13],$arrayLatLong[0],$arrayLatLong[1]); 
	
	$wpdb->query($q);
		$new_store_id=$wpdb->insert_id;
		if (!empty($data[1])){
			ssf_wp_process_tags($data[1], "insert", $new_store_id);
		}
	}
	fclose($handle);
	$dir=SSF_WP_UPLOADS_PATH."/csv/import/";
	if (is_dir($dir)){
				$images = @scandir($dir);
				foreach($images as $k=>$v):
				endforeach;
				unlink($dir.'/'.$v);
	}
}	

     return "Insert successfully";
}

function geolocate($address)
{
	$lat = 0;
	$lng = 0;
	$google_api_key=getGoogleMapsApi();
	$request_url = "https://maps.googleapis.com/maps/api/geocode/json?".$google_api_key."address=".urlencode(trim($address));
	if (extension_loaded("curl") && function_exists("curl_init")) {
		$cURL = curl_init();
		curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);  // Disable SSL verification
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_URL, $request_url);
		$resp_json = curl_exec($cURL);
		curl_close($cURL);
	}else{
		$resp_json = file_get_contents($request_url) or die("url not loading");
	}
	
	$resp = json_decode($resp_json, true); 
	if (strcmp($resp['status'], "OK") == 0) {
		$lat = $resp['results'][0]['geometry']['location']['lat'];
		$lng = $resp['results'][0]['geometry']['location']['lng'];
	}
	// concatenate lat/long coordinates
	$lat= (string)$lat;
	$lng = (string)$lng;
	return array($lat,$lng);
}

?>
<script>
function exportStores(){
		var url='<?php echo SSF_WP_BASE;?>/ssf-wp-admin/pages/exportAjax.php';
		window.location=url;

	}
</script>