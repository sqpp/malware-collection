<?php
	global $VISUAL_COMPOSER_EXTENSIONS;
	global $TS_VCSC_tinymceCustomCount;
	global $TS_VCSC_Icons_Custom;
	
	function TS_VCSC_RecursiveRMDIR($dir) {
		foreach (scandir($dir) as $file) {
		   if ('.' === $file || '..' === $file) continue;
		   if (is_dir("$dir/$file")) TS_VCSC_RecursiveRMDIR("$dir/$file");
		   else unlink("$dir/$file");
		}
		rmdir($dir);
	}
	
	//print_r (get_option('ts_vcsc_extend_settings_tinymceCustomArray', ''));
	//echo get_option('ts_vcsc_extend_settings_tinymceCustomName', '');
	//echo get_option('ts_vcsc_extend_settings_tinymceCustomAuthor', '');
	//echo get_option('ts_vcsc_extend_settings_tinymceCustomCount', '');
	//echo get_option('ts_vcsc_extend_settings_tinymceCustomDate', '');
	
	/* get uploaded file, unzip .zip, store files in appropriate locations, populate page with custom icons
	wp_handle_upload ( http://codex.wordpress.org/Function_Reference/wp_handle_upload )
	** TO DO RENAME UPLOADED FILE TO ts-vcsc-custom-pack.zip ** */
	if (isset($_POST['Submit']) && (isset($_FILES['custom_icon_pack']))) {
		$uploadedfile 				= $_FILES['custom_icon_pack'];
		$upload_replace   			= ((isset($_POST['ts_vcsc_custom_pack_replace'])) ? $_POST['ts_vcsc_custom_pack_replace'] : 'off');
		$upload_relative   			= ((isset($_POST['ts_vcsc_custom_pack_relative'])) ? $_POST['ts_vcsc_custom_pack_relative'] : 'off');
		// Optional Username / Password
		$upload_user   				= ((isset($_POST['ts_vcsc_custom_pack_user'])) ? $_POST['ts_vcsc_custom_pack_user'] : '');
		$upload_password			= ((isset($_POST['ts_vcsc_custom_pack_password'])) ? $_POST['ts_vcsc_custom_pack_password'] : '');
		// Other Settings
		$upload_overrides 			= array('test_form' => false);
		$upload_directory 			= wp_upload_dir();
		$font_directory				= $upload_directory['basedir'] . '/ts-vcsc-icons/custom-pack';
		// TO DO
		// get filename dynamically so user doesn't need to customize zip name
		// ERROR CHECKING SO ONLY .ZIP's ARE UPLOADED
		// hide ajax loader if no pack is uploaded
		// export json file for importing back to icomoon - spit back out json file 
		// create a 'Download Pack' button and 'Download .json' button  
		/*
		$filename = $uploadedFile
		*/
		$filename 					= $_FILES["custom_icon_pack"]["name"];
		$source 					= $_FILES["custom_icon_pack"]["tmp_name"];
		$type 						= $_FILES["custom_icon_pack"]["type"]; 
		$name 						= explode(".", $filename);
		$accepted_types 			= array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
		foreach ($accepted_types as $mime_type) {
			if ($mime_type == $type) {
				$okay = true;
				break;
			} 
		} 
		$continue 					= strtolower($name[1]) == 'zip' ? true : false;
		if (!$continue) {
			TS_VCSC_CustomFontImportMessages('warning', 'The file you are trying to upload is not a .zip file. Please try again.');
		}
		/* PHP current path */
		$filepath 					= $upload_directory['basedir'] . '/ts-vcsc-icons/custom-pack/';  	// absolute path to the directory where zipper.php is in
		$filenoext 					= basename ($filename, '.zip');  		// absolute path to the directory where zipper.php is in (lowercase)
		$filenoext 					= basename ($filenoext, '.ZIP');  		// absolute path to the directory where zipper.php is in (when uppercase)
		$targetdir 					= $filepath; 							// target directory
		$filenameClear				= trim(str_replace(' ', '-', $filename));
		//$targetzip 				= $filepath . $filename; 	
		$targetzip 					= $filepath . $filenameClear; 			// target zip file
		/* create directory if not exists', otherwise overwrite */
		/* target directory is same as filename without extension */
		if (is_dir($targetdir)) TS_VCSC_RecursiveRMDIR ($targetdir);
		mkdir($targetdir, 0777);
		/* here it is really happening */
		if (move_uploaded_file($source, $targetzip)) {
			if (class_exists('ZipArchive')) {
				$zip 				= new ZipArchive();
				$x 					= $zip->open($targetzip);  				// open the zip file to extract
				if ($x === true) {
					$zip->extractTo($targetdir); 							// place in the directory with same name  
					$zip->close();
					$unzipfile		= true;
				} else {
					$unzipfile		= false;
				}
			} else {
				$dest_path 			= $upload_directory['path'];
				$dest_url			= $upload_directory['url'];				
				//$unzipfile 		= unzip_file($dest_path . '/' . $filename, $dest_path);
				$unzipfile 			= unzip_file($dest_path . '/' . $filenameClear, $dest_path);
			}
			$movefile 				= true;
		} else {	
			$movefile 				= false;
		}		
		// If Upload was Successful
		if ($movefile) {	
			echo '<script>
				jQuery(document).ready(function() {
					jQuery(".ts-vcsc-custom-pack-preloader").hide();
					jQuery("#uninstall-pack-button").removeAttr("disabled");
					jQuery("#ts_vcsc_custom_pack_field").attr("disabled", "disabled");
					jQuery("input[value=Import]").attr("disabled", "disabled");
					jQuery(".ts-vcsc-custom-pack-buttons").after("<div class=updated><p class=fontPackUploadedSuccess>Custom Font Pack successfully uploaded!</p></div>");
				});
			</script>';
			// unzip the file contents to the same directory
			WP_Filesystem();
			$dest 					= wp_upload_dir();
			$dest_path 				= $dest['path'];
			$dest_url				= $dest['url'];
			$fileNameNoSpaces 		= trim(str_replace(' ', '-', $uploadedfile['name']));
			$basicCheck				= true;
			$filesFound				= true;
			if ($unzipfile) {
				if (file_exists($dest_path . '/' . $fileNameNoSpaces)) {
					rename($dest_path . '/' . $fileNameNoSpaces, $dest_path . '/ts-vcsc-custom-pack.zip');
				} else {
					$filesFound 	= false;
				}
				if (file_exists($dest_path . '/selection.json')) {
					rename($dest_path . '/selection.json', $dest_path . '/ts-vcsc-custom-pack.json');
				} else {
					$basicCheck		= false;
				}
				// Change Path of linked Font Files in Style.css
				if ((file_exists($dest_path . '/style.css')) && ($upload_replace == 'on')) {
					$styleCSS 		= $dest_path . '/style.css';
					if (ini_get('allow_url_fopen') == '1') {
						$currentStyles 						= file_get_contents($styleCSS);
						// for css and js files that are not needed any more
						if (strpos($dest_url, '/ts-vcsc-icons/custom-pack') !== false) {
							$newStyles 						= str_replace("url('fonts/", "url('" . $dest_url . "/fonts/", $currentStyles);
						} else {
							$newStyles 						= str_replace("url('fonts/", "url('" . $dest_url . "/ts-vcsc-icons/custom-pack/fonts/", $currentStyles);
						}
						// Write the contents back to the file
						$file_put_contents 					= file_put_contents($styleCSS, $newStyles);
					}
				} else if ($upload_replace == 'on') {
					$basicCheck = false;
				}
				// Delete unecessary files / add error checking
				if (file_exists($dest_path . '/demo-files')) {
					TS_VCSC_RemoveDirectory($dest_path . '/demo-files');
				};
				if (file_exists($dest_path . '/demo.html')) {
					unlink($dest_path . '/demo.html'); 
				};
				if (file_exists($dest_path . '/Read Me.txt')) {
					unlink($dest_path . '/Read Me.txt'); 
				};
				if (file_exists($dest_path . '/variables.scss')) {
					unlink($dest_path . '/variables.scss'); 
				};
				if (($basicCheck == true) && ($filesFound == true)) {
					// Process JSON File to create and store Font Array
					$Custom_JSON_URL 							= $dest_url . '/ts-vcsc-custom-pack.json';
					
					//echo 'Authorization: ' . $upload_user . ' / ' . $upload_password . '<br/>';
					//echo 'JSON #1: ' . $dest_path . '/ts-vcsc-custom-pack.json' . '<br/>';
					//echo 'JSON #2: ' . $Custom_JSON_URL . '<br/>';
					
					if (function_exists('curl_init')) {
						$ch 									= curl_init();
						$timeout 								= 5;
						curl_setopt($ch, CURLOPT_URL, 			$Custom_JSON_URL);
						curl_setopt($ch, CURLOPT_HEADER,		0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
						//curl_setopt($ch, CURLOPT_PROTOCOLS,	CURLPROTO_ALL);
						if (($upload_user != '') && ($upload_password != '')) {
							curl_setopt($ch, CURLOPT_USERPWD,	$upload_user . ":" . $upload_password);
						}
						curl_setopt($ch, CURLOPT_USERAGENT, 	'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
						$Custom_JSON 							= curl_exec($ch);
						curl_close($ch);
					} else if (ini_get('allow_url_fopen') == '1') {
						if (($upload_user != '') && ($upload_password != '')) {
							$context = stream_context_create(array (
								'http' => array (
									'header' => 'Authorization: Basic ' . base64_encode("$upload_user:$upload_password")
								)
							));
							$Custom_JSON 						= file_get_contents($Custom_JSON_URL, false, $context);	
						} else {
							$Custom_JSON						= file_get_contents($Custom_JSON_URL);
						}
					}
					if (!is_wp_error($Custom_JSON) && !empty($Custom_JSON)) {
						
						//echo $Custom_JSON;
						
						$Custom_Code                        	= json_decode($Custom_JSON, true);						
						
						//echo $dest_url . '/style.css' . '<br/>';
						//echo str_replace(home_url(), '', $dest_url . '/style.css') . '<br/>';
						//var_dump($Custom_Code);
						
						if ((isset($Custom_Code['IcoMoonType'])) && (isset($Custom_Code['icons']))){
							$TS_VCSC_Icons_Custom               = array();
							$TS_Custom_User_Font				= array();
							if (isset($Custom_Code['preferences']['fontPref']['prefix'])) {
								$Custom_Class_Prefix			= $Custom_Code['preferences']['fontPref']['prefix'];
							} else {
								$Custom_Class_Prefix			= "";
							}
							if (isset($Custom_Code['preferences']['fontPref']['postfix'])) {
								$Custom_Class_Postfix			= $Custom_Code['preferences']['fontPref']['postfix'];
							} else {
								$Custom_Class_Postfix			= "";
							}
							if (isset($Custom_Code['metadata']['name'])) {
								$Custom_Font_Name				= $Custom_Code['metadata']['name'];
							} else {
								$Custom_Font_Name				= "Unknown Font";
							}
							if (isset($Custom_Code['metadata']['designer'])) {
								$Custom_Font_Author             = $Custom_Code['metadata']['designer'];
							} else {
								$Custom_Font_Author				= "Unknown Author";
							}
							if (isset($Custom_Code['icons'])) {
								foreach ($Custom_Code['icons'] as $item) {
									if (isset($item['properties']['name']) && isset($item['properties']['code'])) {
										$Custom_Class_Full = $Custom_Class_Prefix . $item['properties']['name'] . $Custom_Class_Postfix;
										$Custom_Class_Code = $item['properties']['code'];
										$TS_Custom_User_Font[$Custom_Class_Full] = $Custom_Class_Code;
									}
								}
							}
							$TS_VCSC_Icons_Custom				= $TS_Custom_User_Font;
							if (count($TS_VCSC_Icons_Custom) > 1) {
								if (is_array($TS_VCSC_Icons_Custom)) {
									$TS_VCSC_tinymceCustomCount	= count(array_unique($TS_VCSC_Icons_Custom));
								} else {
									$TS_VCSC_tinymceCustomCount	= count($TS_VCSC_Icons_Custom);
								}
							} else {
								$TS_VCSC_tinymceCustomCount		= count($TS_VCSC_Icons_Custom);
							}
							// Export Font Array to PHP file
							/*if (ini_get('allow_url_fopen') == '1') {
								$phpArray = $dest_path . '/ts-vcsc-custom-pack.php';
								$file_put_contents = file_put_contents($phpArray, '<?php $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Icons_Custom = ' . var_export($TS_VCSC_Icons_Custom, true) . '; ?>');
							}*/
							// Store Custom Font Data in WordPress Settings
							update_option('ts_vcsc_extend_settings_tinymceCustom', 			1);							
							if ($upload_relative == 'on') {
								update_option('ts_vcsc_extend_settings_tinymceCustomJSON', 	$dest_url . '/ts-vcsc-custom-pack.json');
								update_option('ts_vcsc_extend_settings_tinymceCustomPath', 	$dest_url . '/style.css');
								update_option('ts_vcsc_extend_settings_tinymceCustomPHP', 	$dest_url . '/ts-vcsc-custom-pack.php');
							} else {
								update_option('ts_vcsc_extend_settings_tinymceCustomJSON', 	str_replace(home_url(), '', $dest_url . '/ts-vcsc-custom-pack.json'));
								//update_option('ts_vcsc_extend_settings_tinymceCustomPath', wp_make_link_relative($dest_url . '/style.css'));
								update_option('ts_vcsc_extend_settings_tinymceCustomPath', 	str_replace(home_url(), '', $dest_url . '/style.css'));
								update_option('ts_vcsc_extend_settings_tinymceCustomPHP', 	str_replace(home_url(), '', $dest_url . '/ts-vcsc-custom-pack.php'));
							}							
							update_option('ts_vcsc_extend_settings_tinymceCustomArray', 	$TS_VCSC_Icons_Custom);
							update_option('ts_vcsc_extend_settings_tinymceCustomName', 		ucwords($Custom_Font_Name));
							update_option('ts_vcsc_extend_settings_tinymceCustomAuthor', 	ucwords($Custom_Font_Author));
							update_option('ts_vcsc_extend_settings_tinymceCustomCount', 	$TS_VCSC_tinymceCustomCount);
							update_option('ts_vcsc_extend_settings_tinymceCustomDate',		date('Y/m/d h:i:s A'));
							// Display Success Message / Disable File Upload Field
							echo '<script>
								jQuery(document).ready(function() {
									jQuery(".dropDownDownload").removeAttr("disabled");
									jQuery(".fontPackUploadedSuccess").parent("div").after("<div class=updated><p class=fontPackSuccessUnzip>Custom Font Pack successfully unzipped!</p></div>");
								});
							</script>';	 
							echo '<script>
								jQuery(document).ready(function() {
								jQuery(".fontPackSuccessUnzip").parent("div").after("<div class=updated><p>A Custom Font named &quot;' . ucwords($Custom_Font_Name) . '&quot; with ' . $TS_VCSC_tinymceCustomCount . ' icon(s) could be found and installed!</p></div>");
									setTimeout(function() {
										jQuery(".updated").fadeOut();
									}, 5000);
								});
							</script>';
							$output = "";
							$output .= "<div id='ts-vcsc-extend-preview' class=''>";
								$output .="<div id='ts-vcsc-extend-preview-name'>Font Name: " . ucwords($Custom_Font_Name) . "</div>";
								$output .="<div id='ts-vcsc-extend-preview-author'>Font Author: " . 	get_option('ts_vcsc_extend_settings_tinymceCustomAuthor', 'Custom User') . "</div>";
								$output .="<div id='ts-vcsc-extend-preview-count'>Icon Count: " . 		get_option('ts_vcsc_extend_settings_tinymceCustomCount', 0) . "</div>";
								$output .="<div id='ts-vcsc-extend-preview-date'>Uploaded: " . 			get_option('ts_vcsc_extend_settings_tinymceCustomDate', '') . "</div>";
								$output .= "<div id='ts-vcsc-extend-preview-list' class=''>";
								$icon_counter = 0;
								foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Icons_Custom as $key => $option ) {
									$font = explode('-', $key);
									$output .= "<div class='ts-vcsc-icon-preview ts-freewall-active' data-name='" . $key . "' data-code='" . $option . "' data-font='" . strtolower($iconfont) . "' data-count='" . $icon_counter . "' rel='" . $key . "'><span class='ts-vcsc-icon-preview-icon'><i class='" . $key . "'></i></span><span class='ts-vcsc-icon-preview-name'>" . $key . "</span></div>";
									$icon_counter = $icon_counter + 1;
								}
								$output .= "</div>";
							$output .= "</div>";
							TS_VCSC_CustomFontImportMessages('success', 'Your Custom Font Pack has been successfully installed.');
						} else {
							TS_VCSC_ResetCustomFont();
							echo '<script>
								jQuery(document).ready(function() {
									jQuery(".fontPackUploadedSuccess").parent("div").after("<div class=error><p>This font was not created with the IcoMoon App and/or is missing a valid JSON data file. Please upload only font packages created via IcoMoon.</p></div>");
								});
							</script>';
							TS_VCSC_CustomFontImportMessages('warning', 'This font was not created with the IcoMoon App and/or is missing a valid JSON data file. Please upload only font packages created via IcoMoon.');
						}
					} else {
						TS_VCSC_ResetCustomFont();
						echo '<script>
							jQuery(document).ready(function() {
								jQuery(".fontPackUploadedSuccess").parent("div").after("<div class=error><p>There was a problem while importing the custom font package file.</p></div>");
							});
						</script>';
						TS_VCSC_CustomFontImportMessages('warning', 'There was a problem while importing the custom font package file.');
					}
				} else {
					TS_VCSC_ResetCustomFont();
					if ($filesFound == false) {
						echo '<script>
							jQuery(document).ready(function() {
								jQuery(".fontPackUploadedSuccess").parent("div").after("<div class=error><p>There seems to be an issue with the read/write permissions of the upload folder; please check your server settings.</p></div>");
							});
						</script>';
						TS_VCSC_CustomFontImportMessages('warning', 'There seems to be an issue with the read/write permissions of the upload folder; please check your server settings.');
					} else {
						echo '<script>
							jQuery(document).ready(function() {
								jQuery(".fontPackUploadedSuccess").parent("div").after("<div class=error><p>This font package is missing a valid JSON data file and/or style.css file. In that case, please upload only complete font packages created via IcoMoon.</p></div>");
							});
						</script>';
						TS_VCSC_CustomFontImportMessages('warning', 'This font package is missing a valid JSON data file and/or style.css file. Please upload only complete font packages created via IcoMoon.');
					}
				}
			} else {
				TS_VCSC_ResetCustomFont();
				echo '<script>
					jQuery(document).ready(function() {
						jQuery(".fontPackUploadedSuccess").parent("div").after("<div class=error><p>There was a problem while unzipping the custom font package file.</p></div>");
					});
				</script>';
				TS_VCSC_CustomFontImportMessages('warning', 'There was a problem while unzipping the custom font package file.');
			}
		} else {
			TS_VCSC_ResetCustomFont();
			echo '<script>
				jQuery(document).ready(function() {
					jQuery(".ts-vcsc-custom-pack-buttons").after("<div class=error><p class=fontPackUploadedError>There was a problem while uploading the custom font package file.</p></div>");
				});
			</script>';
			TS_VCSC_CustomFontImportMessages('warning', 'There was a problem while uploading the custom font package.');
		}
	}
?>
	<div class="ts-vcsc-settings-group-header">
		<div class="display_header">
			<h2><span class="dashicons dashicons-upload"></span>Visual Composer Extensions - Custom Icon Font Upload</h2>
		</div>
		<div class="clear"></div>
	</div>
	<div class="ts-vcsc-custom-upload-wrap wrap" style="margin-top: 0px;">
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-info"></i>General Information</div>
		<div class="ts-vcsc-section-content">
			<a class="button-secondary" style="width: 200px; margin: 15px auto 10px auto; text-align: center;" href="<?php echo $VISUAL_COMPOSER_EXTENSIONS->settingsLink; ?>" target="_parent"><img src="<?php echo TS_VCSC_GetResourceURL('images/logos/ts_vcsc_menu_icon_16x16.png'); ?>" style="width: 16px; height: 16px; margin-right: 10px;">Back to Plugin Settings</a>
			<p>Welcome to the Visual Composer Extensions - Custom Font Pack section! Use the importer below to import a custom icon pack downloaded from <a href="http://icomoon.io/app/#/select" target="_blank">IcoMoon</a>.</p>
			<div id="ts_vcsc_icons_upload_system_trigger" class="clearFixMe">
				<button id="ts_vcsc_icons_upload_system_button" style="height: 28px; margin-right: 10px; width: 200px;" type="button" data-show="Show System Check" data-hide="Hide System Check" class="button-secondary">Show System Check</button>
				Please ensure hat your server is supporting and providing the following functions or features: cURL / allow_url_fopen / file_get_contents / file_put_contents / unzip_file.
			</div>			
			<div id="ts_vcsc_icons_upload_system_check" style="display: none;">
				<div style="margin-bottom: 20px; color: red;">This is just a basic system check to see if the main requirements are fulfilled in order to use the font upload feature. The check itself is no guaranty for a successful upload as there are other factors involved that can influence the procedure.</div>
				<?php
					WP_Filesystem();
					$dest 				= wp_upload_dir();
					$dest_path 			= $dest['path'];
					echo 'Target Directory: ' . $dest_path . '<br/><br/>';
					if (strnatcmp(phpversion(), '5.2') >= 0)  {
						echo '<div style="width: 150px; float: left;">PHP Version of 5.2+:</div><span style="font-weight: bold; color: green;">' . phpversion() . '</span><br/>';
					} else {
						echo '<div style="width: 150px; float: left;">PHP Version of 5.2+:</div><span style="font-weight: bold; color: red;">' . phpversion() . '</span><br/>';
					}
					if (is_writable($dest_path)) {
						echo '<div style="width: 150px; float: left;">Directory writeable:</div><span style="font-weight: bold; color: green;">true</span><br/>';
					} else {
						echo '<div style="width: 150px; float: left;">Directory writeable:</div><span style="font-weight: bold; color: red;">false</span><br/>';
					}
					if  (in_array  ('curl', get_loaded_extensions())) {
						echo '<div style="width: 150px; float: left;">cURL enabled:</div><span style="font-weight: bold; color: green;">true</span><br/>';
					} else {
						echo '<div style="width: 150px; float: left;">cURL enabled:</div><span style="font-weight: bold; color: red;">false</span><br/>';
					}
					if( ini_get('allow_url_fopen') ) {
						echo '<div style="width: 150px; float: left;">allow_url_fopen:</div><span style="font-weight: bold; color: green;">true</span><br/>';
					} else {
						echo '<div style="width: 150px; float: left;">allow_url_fopen:</div><span style="font-weight: bold; color: red;">false</span><br/>';
					}
					if( function_exists('file_get_contents') ) {
						echo '<div style="width: 150px; float: left;">file_get_contents:</div><span style="font-weight: bold; color: green;">true</span><br/>';
					} else {
						echo '<div style="width: 150px; float: left;">file_get_contents:</div><span style="font-weight: bold; color: red;">false</span><br/>';
					}
					if( function_exists('file_put_contents') ) {
						echo '<div style="width: 150px; float: left;">file_put_contents:</div><span style="font-weight: bold; color: green;">true</span><br/>';
					} else {
						echo '<div style="width: 150px; float: left;">file_put_contents:</div><span style="font-weight: bold; color: red;">false</span><br/>';
					}
					if( function_exists('unzip_file') ) {
						echo '<div style="width: 150px; float: left;">unzip_file:</div><span style="font-weight: bold; color: green;">true</span><br/>';
					} else {
						echo '<div style="width: 150px; float: left;">unzip_file:</div><span style="font-weight: bold; color: red;">false</span><br/>';
					}
				?>
			</div>
		</div>
		<div class="ts-vcsc-section-main">
			<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-format-video"></i>How to build a Custom Icon Font</div>
			<div class="ts-vcsc-section-content slideFade" style="display: none;">				
				<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					You can only upload a custom icon font that has been created and processed with the online IcoMoon App. That is necesseary because IcoMoon is including a custom JSON file with its font packages, which include
					all the information necessary for the plugin to "read" the font data and to add it to its internal databank.
				</div>	
				<div style="width: 50%; height: 100%;">
					<div class="ts-video-container">
						<iframe style="width: 100%;" width="100%" height="100%" src="//www.youtube.com/embed/XA03oGOhtXk" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>		
		<div class="ts-vcsc-section-main">
			<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-upload"></i>Custom Font Upload</div>
			<div class="ts-vcsc-section-content clearFixMe">
				<script>				
					jQuery(document).ready(function() {
						setTimeout(function() {	
							var fontNameString 			= 'ts-vcsc-custom-pack';
							var newfontNameString 		= fontNameString.replace("Font Name:","");
							var customPackFontName 		= newfontNameString.split("(")[0];
							var customPackFontName 		= jQuery.trim(customPackFontName);
							jQuery('.downloadFontZipLink').parent('li').find('img').remove();
							jQuery('.downloadFontZipLink').text('Download ' + customPackFontName + '.zip');
							jQuery('.downloadFontjSonLink').parent('li').find('img').remove();
							jQuery('.downloadFontjSonLink').text('Download ' + customPackFontName + '.json');
						}, 2000);
					});
				</script>
				<!-- Handling Custom Font Pack Uploads -->
				<form id="ts_vcsc_icons_upload_custom_pack_form" enctype="multipart/form-data" action="" method="POST">
					<div id="ts-vcsc-async-upload-wrap" class="clearFixMe" style="margin-bottom:0;">
						<label for="async-upload" style="font-weight: bold;">Import a Custom Font Pack :</label><br />
						<input type="file" id="ts_vcsc_custom_pack_field" name="custom_icon_pack">
							
						<?php if (file_exists($dest_path . '/ts-vcsc-custom-pack.zip') == false) {
							$custom_package_exists = "false";
						?>
							<div class="clearFixMe" style="font-weight: bold; text-align: justify; color: green; margin-top: 10px; padding: 10px; background: #ffffff; border: 1px solid #dddddd;">
								You should have received a .zip file from IcoMoon, containing the font information. Please upload the original file as you received it; do NOT unzip it yourself or recompress prior to uploading it!
							</div>
						<?php } else {
							$custom_package_exists = "true";
						}?>
						
						<!-- Package Path -->
						<div style="width:100%; display: block;">
							<?php
								if ($custom_package_exists == "true") {
									$fontPackLocationString = 'Your Custom Icon Pack is located in: '; 
								} else {
									$fontPackLocationString = 'Your Custom Icon Pack will be installed to: ';
								}
							?>
							<p style="font-size: 10px; display: block;"><?php echo $fontPackLocationString . '<b>' . $dest_path . '</b>'; ?>.
							
							<div class="ts-vcsc-custom-pack-overwrite" style="display: block; width: 100%; margin-top: 10px; margin-bottom: 10px;">
								<input type="checkbox" id="ts_vcsc_custom_pack_replace" class="ts_vcsc_custom_pack_replace" name="ts_vcsc_custom_pack_replace" checked="checked">
								<label id="ts_vcsc_custom_pack_replace_label" class="" for="ts_vcsc_custom_pack_replace"><span>Replace Path Names inside Imported CSS File</span></label>
							</div>
							<div class="ts-vcsc-custom-pack-overwrite" style="display: block; width: 100%; margin-top: 10px; margin-bottom: 10px;">
								<input type="checkbox" id="ts_vcsc_custom_pack_relative" class="ts_vcsc_custom_pack_relative" name="ts_vcsc_custom_pack_relative" checked="checked">
								<label id="ts_vcsc_custom_pack_relative_label" class="" for="ts_vcsc_custom_pack_relative"><span>Use Absolute Path Names for Imported Files</span></label>
							</div>							
							<div id="ts-vcsc-custom-pack-authorization-message" class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; font-size: 13px; text-align: justify;">
								If your server requires authorization in order to load/open any files (for example, because it is a non-public staging/development server), you can provide those credentials here, as the plugin might
								otherwise not be able to read the imported JSON file that contains the icon definitions. Otherwise, leave the inputs empty.
							</div>		
							<div class="ts-vcsc-custom-pack-authorization" style="display: block; width: 100%; margin-top: 10px; margin-bottom: 10px;">
								<label id="ts_vcsc_custom_pack_user_label" class="" for="ts_vcsc_custom_pack_user"><span>Authorization: User Name</span></label>
								<input type="text" id="ts_vcsc_custom_pack_user" class="ts_vcsc_custom_pack_user" name="ts_vcsc_custom_pack_user">								
							</div>
							<div class="ts-vcsc-custom-pack-authorization" style="display: block; width: 100%; margin-top: 10px; margin-bottom: 10px;">
								<label id="ts_vcsc_custom_pack_password_label" class="" for="ts_vcsc_custom_pack_password"><span>Authorization: Password</span></label>
								<input type="text" id="ts_vcsc_custom_pack_password" class="ts_vcsc_custom_pack_password" name="ts_vcsc_custom_pack_password">								
							</div>							
							<?php if ($custom_package_exists == "true") { ?>
								<br/><span style="color: red;">If you have problems uninstalling the font pack, manually delete the "custom-pack" folder (following the path provided above) via FTP.</span></p>
							<?php } ?>
						</div>
						
						<div class="ts-vcsc-custom-pack-buttons">
							<div style="float: left;">
								<?php
									$other_attributes = array( 'id' => 'ts_vcsc_import_font_submit' );
									echo submit_button('Import', 'primary', 'Submit', false, $other_attributes); 
								?>
							</div>
							<div style="margin-left: 2em; float: left;">
								<?php
									$other_attributes = array('onclick' => 'TS_VCSC_UninstallFontPack(); return false;');
									echo submit_button('Uninstall Pack', 'delete', 'uninstall-pack-button', false, $other_attributes);
									$dest 		= wp_upload_dir();
									$dest_url 	= $dest['url'];
									$dest_path 	= $dest['path'];
								?>
							</div> 
							<div style="float: left;">
								<button id="dropDownDownload" style="height:28px; margin-left:2em;" type="button" disabled value="Dropdown" data-dropdown="#ts-dropdown-1" class="dropDownDownload button-secondary">Download</button>
							</div>
							<!-- jQuery Download Dropdown Menu -->
							<div id="ts-dropdown-1" style="" class="ts-dropdown ts-dropdown-anchor-left ts-dropdown-tip ts-dropdown-relative">
								<ul class="ts-dropdown-menu">
									<li><a title="This .zip file contains the original files you uploaded with your icon pack." class="downloadFontZipLink" href="<?php echo $dest_url.'/ts-vcsc-custom-pack.zip'; ?>"></a><img src="<?php echo site_url().'/wp-admin/images/wpspin_light.gif'?>" alt="preloader"></li>
									<li class="ts-dropdown-divider"></li>
									<li><a title="You can use this .json file to export your custom pack back into IcoMoon and then add or remove icons as you please." class="downloadFontjSonLink" download="ts-vcsc-custom-pack.json" href="<?php echo $dest_url.'/ts-vcsc-custom-pack.json'; ?>"></a><img src="<?php echo site_url().'/wp-admin/images/wpspin_light.gif'?>" alt="preloader"></li>
								</ul>
							</div>
						</div>
						<!-- display success or error message after font pack deletion -->
						<p id="delete_succes_and_error_message" style="display: none;"></p>
						<p id="unzip_succes_and_error_message" style="display: none;"></p>
					</div>
				</form>
				<?php if ($custom_package_exists == "true") { ?>
					<hr class="style-six" style="margin-top: 20px; margin-bottom: 20px;">
				<?php } ?>
				<div class="current-font-pack" style="float:left; width: 100%; display: block;">
					<img style="display: none;" class="ts-vcsc-custom-pack-preloader" src="<?php echo site_url().'/wp-admin/images/wpspin_light.gif'?>" alt="preloader">
					<div id="current-font-pack-preview" class="current-font-pack-preview"></div>
				</div>
			</div>
		</div>
<?php

?>