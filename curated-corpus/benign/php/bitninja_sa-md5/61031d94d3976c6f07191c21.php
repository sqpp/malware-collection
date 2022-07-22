<?php
/********************************************************************************
 MachForm
  
 Copyright 2007-2014 Appnitro Software. This code cannot be redistributed without
 permission from http://www.appnitro.com/
 
 More info at: http://www.appnitro.com/
 ********************************************************************************/
	require('includes/init.php');
	
	ob_start();
	
	require('config.php');
	require('includes/db-core.php');
	require('includes/helper-functions.php');
	require('includes/filter-functions.php');
	
	$dbh = mf_connect_db();
	$mf_settings = mf_get_settings($dbh);
	
	$upload_success = false;
	
	$machform_data_path = '';
	
	if(!empty($_FILES) && !empty($_POST['form_id']) && !empty($_POST['element_id']) && !empty($_POST['file_token'])){
		
		$form_id 	= (int) $_POST['form_id'];
		$element_id = (int) $_POST['element_id'];
		$file_token = trim($_POST['file_token']);
		
		if(!is_writable($machform_data_path.$mf_settings['upload_dir']."/form_{$form_id}/files")){
			echo "Unable to write into upload folder! (".$machform_data_path.$mf_settings['upload_dir']."/form_{$form_id}/files)";
		}
		
		
		//check if this is a multi upload or not
		//if not multi upload, we need to overwrite any previous entry, which can be on the review table or list file
		$query = "select 
						element_file_enable_multi_upload,
						element_file_enable_type_limit,
       					element_file_block_or_allow,
       					element_file_type_list,
       					element_file_enable_size_limit,
       					element_file_size_max
					from 
						".MF_TABLE_PREFIX."form_elements 
				   where 
				   		form_id = ? and element_id = ? and element_status = 1";
		$params = array($form_id,$element_id);
		$sth = mf_do_query($query,$params,$dbh);
		$row = mf_do_fetch_result($sth);
		
		if(!empty($row['element_file_enable_multi_upload'])){
			$is_multi_upload = true;
		}else{
			$is_multi_upload = false;
		}
		
		$file_enable_type_limit = $row['element_file_enable_type_limit'];
		$file_block_or_allow 	= $row['element_file_block_or_allow'];
		$file_type_list 		= $row['element_file_type_list'];
		$file_enable_size_limit	= $row['element_file_enable_size_limit'];
		$file_size_max			= $row['element_file_size_max'];

		//extra security measure for file upload
		//even though the user disabled 'file type limit', we need to enforce it here and block dangerous files
		if(empty($file_enable_type_limit)){
			$file_enable_type_limit = 1;
			$file_block_or_allow    = 'b';
			$file_type_list = 'php,php3,php4,php5,phtml,exe,pl,cgi,html,htm,js';
		}else{
			//if the limit being enabled but the list type is empty
			if(empty($file_type_list)){
				$file_block_or_allow = 'b'; //block
				$file_type_list = 'php,php3,php4,php5,phtml,exe,pl,cgi,html,htm,js';
			}else{
				//if the list is not empty, and it set to block files, make sure to add dangerous file types into the list
				if($file_block_or_allow == 'b'){
					$file_type_list .= ',php,php3,php4,php5,phtml,exe,pl,cgi,html,htm,js';
				}
			}
		}
		
		//validate file type
		$ext = pathinfo(strtolower($_FILES['Filedata']['name']), PATHINFO_EXTENSION);
		if(!empty($file_type_list) && !empty($file_enable_type_limit)){
		
			$file_type_array = explode(',',$file_type_list);
			array_walk($file_type_array,create_function('&$val','$val = strtolower(trim($val));'));
			
			if($file_block_or_allow == 'b'){
				if(in_array($ext,$file_type_array)){
					die('Error! Filetype blocked!');
				}	
			}else if($file_block_or_allow == 'a'){
				if(!in_array($ext,$file_type_array)){
					die('Error! Filetype not allowed!');
				}
			}
		}
		
		//validate file size if this rule being enabled
		if(!empty($file_enable_size_limit) && !empty($file_size_max)){
			$file_size_max = $file_size_max * 1048576; //turn into bytes from MB
			if($_FILES['Filedata']['size'] > $file_size_max){
				die('Error! File size exceeded!');
			}
		}
		
		
		//move file and check for invalid file
		$destination_file = $machform_data_path.$mf_settings['upload_dir']."/form_{$form_id}/files/element_{$element_id}_{$file_token}-{$_FILES['Filedata']['name']}.tmp";
		
		//if destination file already exist having the exact file name (could be happen if user is uploading multiple files using same names)
		if(file_exists($destination_file)){
			//add random numbers to the filename
			$rand_suffix = substr(strtoupper(md5(mt_rand())),0,5);
			$path_parts = pathinfo($_FILES['Filedata']['name']);

			$destination_file = $machform_data_path.$mf_settings['upload_dir']."/form_{$form_id}/files/element_{$element_id}_{$file_token}-{$path_parts['filename']}{$rand_suffix}.{$path_parts['extension']}.tmp";
		}

		$destination_file = mf_sanitize($destination_file);

		$source_file	  = $_FILES['Filedata']['tmp_name'];
		if(move_uploaded_file($source_file,$destination_file)){
			
			//add the file name into the list file
			$listfile_name = $machform_data_path.$mf_settings['upload_dir']."/form_{$form_id}/files/listfile_{$file_token}.php";
			
			if(!file_exists($listfile_name)){ //if the listfile is not being created yet
				$listfile_content = '<?php'."\n".$destination_file."\n"."?>";
			}else{
				
				if($is_multi_upload){
					//insert the new file into the listfile, we need to make sure there is no duplicate
					$current_listfile_array = array();
					$current_listfile_array = file($listfile_name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
					
					array_shift($current_listfile_array); //remove the first index of the array
					array_pop($current_listfile_array); //remove the last index of the array
					array_push($current_listfile_array,$destination_file); //push the new filename
					
					$current_listfile_array = array_unique($current_listfile_array); //make sure there are only uniques files
					
					array_unshift($current_listfile_array,"<?php");
					array_push($current_listfile_array,"?>");
					
					$listfile_content = implode("\n",$current_listfile_array);
				}else{
					
					//delete previous file from the listfile if any
					$current_listfile_array = array();
					$current_listfile_array = file($listfile_name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
					
					if(file_exists($current_listfile_array[1])){
						unlink($current_listfile_array[1]);
					}
					
					$listfile_content = '<?php'."\n".$destination_file."\n"."?>";
				}
			}
			
			// Write the contents to the file
			file_put_contents($listfile_name, $listfile_content, LOCK_EX);
			
			$upload_success = true;
		}else{
			$upload_success = false;
			$error_message  = "Unable to move file!";
		}
		
	}
	
	$response_data = new stdClass();
	
	if($upload_success){
		$response_data->status    	= "ok";
		$response_data->message 	= mf_sanitize($_FILES['Filedata']['name']);
	}else{
		$response_data->status    	= "error";
		$response_data->message 	= $error_message;
	}
	
	$response_json = json_encode($response_data);
	
	echo $response_json;
	
	//we need to use output buffering to be able capturing error messages
	$output = ob_get_contents();
	ob_end_clean();
	
	echo $output;
?>