<?php
// Direct calls to this file are Forbidden when core files are not present
if ( !function_exists('add_action') ){
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}
 
if ( !current_user_can('manage_options') ){ 
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}
?>

<div class="wrap">
<div id="bpsUprade"><strong>
<a href="http://www.ait-pro.com/bulletproof-security-pro-flash/bulletproof.html" target="_blank" title="BulletProof Security Pro Flash Movie">Upgrade to BulletProof Security Pro</a></strong></div>

<!-- Begin Rating CSS - needs to be inline to load on first launch -->
<style type="text/css">
div.bps-star-container { float:right; position: relative; top:-10px; right:-100px; height:19px; width:100px; font-size:19px;}
div.bps-star {height: 100%; position:absolute; top:0px; left:0px; background-color: transparent; letter-spacing:1ex; border:none;}
.bps-star1 {width:20%;} .bps-star2 {width:40%;} .bps-star3 {width:60%;} .bps-star4 {width:80%;} .bps-star5 {width:100%;}
.bps-star.bps-star-rating {background-color: #fc0;}
.bps-star img{display:block; position:absolute; right:0px; border:none; text-decoration:none;}
div.bps-star img {width:19px; height:19px; border-left:1px solid #fff; border-right:1px solid #fff;}
.bps-downloaded {float:right; position: relative; top:15px; right:0px; }
.bps-star-link {position: relative; top:43px; right:0px; font-size:12px;}
</style>
<!-- End Rating CSS - needs to be inline to load on first launch -->

<?php
if (function_exists('get_transient')) {
require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

	if (false === ($bpsapi = get_transient('bulletproof-security_info'))) {
		$bpsapi = plugins_api('plugin_information', array('slug' => stripslashes( 'bulletproof-security' ) ));
	
	if ( !is_wp_error($bpsapi) ) {
		$bpsexpire = 60 * 15; // Cache data for 15 minutes
		set_transient('bulletproof-security_info', $bpsapi, $bpsexpire);
	}
	}
  
	if ( !is_wp_error($bpsapi) ) {
		$plugins_allowedtags = array('a' => array('href' => array(), 'title' => array(), 'target' => array()),
								'abbr' => array('title' => array()), 'acronym' => array('title' => array()),
								'code' => array(), 'pre' => array(), 'em' => array(), 'strong' => array(),
								'div' => array(), 'p' => array(), 'ul' => array(), 'ol' => array(), 'li' => array(),
								'h1' => array(), 'h2' => array(), 'h3' => array(), 'h4' => array(), 'h5' => array(), 'h6' => array(),
								'img' => array('src' => array(), 'class' => array(), 'alt' => array()));
	//Sanitize HTML
	foreach ( (array)$bpsapi->sections as $section_name => $content )
		$bpsapi->sections[$section_name] = wp_kses($content, $plugins_allowedtags);
	foreach ( array('version', 'author', 'requires', 'tested', 'homepage', 'downloaded', 'slug') as $key )
		$bpsapi->$key = wp_kses($bpsapi->$key, $plugins_allowedtags);

	  if ( !empty($bpsapi->downloaded) ) {
        echo '<div class="bps-downloaded">'.sprintf(__('%s Downloads', 'bulletproof-security'),number_format_i18n($bpsapi->downloaded)).'</div>';
      }
?>
		<?php if ( !empty($bpsapi->rating) ) : ?>
		<div class="bps-star-container" title="<?php //echo esc_attr(sprintf(__('Average Rating (%s ratings)', 'bulletproof-security'),number_format_i18n($bpsapi->num_ratings))); ?>">
			<div class="bps-star bps-star-rating" style="width: <?php echo esc_attr($bpsapi->rating) ?>px"></div>
			<div class="bps-star bps-star5"><img src="<?php echo plugins_url('/bulletproof-security/admin/images/star.png'); ?>" alt="<?php _e('5 stars', 'bulletproof-security') ?>" /></div>
			<div class="bps-star bps-star4"><img src="<?php echo plugins_url('/bulletproof-security/admin/images/star.png'); ?>" alt="<?php _e('4 stars', 'bulletproof-security') ?>" /></div>
			<div class="bps-star bps-star3"><img src="<?php echo plugins_url('/bulletproof-security/admin/images/star.png'); ?>" alt="<?php _e('3 stars', 'bulletproof-security') ?>" /></div>
			<div class="bps-star bps-star2"><img src="<?php echo plugins_url('/bulletproof-security/admin/images/star.png'); ?>" alt="<?php _e('2 stars', 'bulletproof-security') ?>" /></div>
			<div class="bps-star bps-star1"><img src="<?php echo plugins_url('/bulletproof-security/admin/images/star.png'); ?>" alt="<?php _e('1 star', 'bulletproof-security') ?>" /></div>
		
        <div class="bps-star-link"><a target="_blank" title="Link opens in new browser window" href="http://wordpress.org/extend/plugins/<?php echo $bpsapi->slug ?>/"> <?php _e('Rate BPS', 'bulletproof-security'); ?></a> <small><?php //echo sprintf(__('%s Ratings', 'bulletproof-security'),number_format_i18n($bpsapi->num_ratings)); ?> </small></div>
        
        </div>
		
        <br />
		<?php endif; 
	  } // if ( !is_wp_error($bpsapi)
 }// end if (function_exists('get_transient'
?>

<h2 style="margin-left:70px;"><?php _e('BulletProof Security ~ htaccess Core', 'bulletproof-security'); ?></h2>
<div id="message" class="updated" style="border:1px solid #999999; margin-left:70px;background-color: #000;">

<?php
// HUD - Heads Up Display - Warnings and Error messages
echo bps_check_php_version_error();
// echo bps_check_permalinks_error(); // this is now an admin notice w/dimiss button
//echo bps_check_iis_supports_permalinks(); // this is now an admin notice w/dimiss button
echo bps_hud_check_bpsbackup();
echo bps_check_safemode();
echo @bps_w3tc_htaccess_check($plugin_var);
echo @bps_wpsc_htaccess_check($plugin_var);

// default.htaccess, secure.htaccess, maintenance.htaccess fwrite content for all WP site types
$bps_get_domain_root = bpsGetDomainRoot();
$bps_get_wp_root_default = bps_wp_get_root_folder();
// Replace ABSPATH = wp-content/plugins
$bps_plugin_dir = str_replace( ABSPATH, '', WP_PLUGIN_DIR);
// Replace ABSPATH = wp-content
$bps_wpcontent_dir = str_replace( ABSPATH, '', WP_CONTENT_DIR);
// Replace ABSPATH = wp-content/uploads
$wp_upload_dir = wp_upload_dir();
$bps_uploads_dir = str_replace( ABSPATH, '', $wp_upload_dir['basedir'] );
$bps_topDiv = '<div id="message" class="updated" style="background-color:#ffffe0;font-size:1em;font-weight:bold;border:1px solid #999999; margin-left:70px;"><p>';
$bps_bottomDiv = '</p></div>';

// Form - copy and rename htaccess files to root folder & delete maintenance mode files if they exist
// Root BulletProof Mode and Default Mode
$bpsecureroot = 'unchecked';
$bpdefaultroot = 'unchecked';
if (isset($_POST['submit12']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_root_copy' );
	
	$options = get_option('bulletproof_security_options_autolock');
	$DefaultHtaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/default.htaccess';
	$RootHtaccess = ABSPATH . '.htaccess';
	$SecureHtaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/secure.htaccess';
	$bpmaintenance = ABSPATH . 'bp-maintenance.php';
	$bpmaintenance_values = ABSPATH . 'bps-maintenance-values.php';
	$permsRootHtaccess = @substr(sprintf('%o', fileperms($RootHtaccess)), -4);
	$sapi_type = php_sapi_name();	
	$deleteMaintenanceFiles = array($bpmaintenance, $bpmaintenance_values);

	$selected_radio = $_POST['selection12'];
	
	if ($selected_radio == 'bpsecureroot') {
		$bpsecureroot = 'checked';
		
		if ( @substr($sapi_type, 0, 6) != 'apache' && @$permsRootHtaccess != '0666' || @$permsRootHtaccess != '0777') { // Windows IIS, XAMPP, etc
			@chmod($RootHtaccess, 0644);
		}		
		
		if ( !copy($SecureHtaccess, $RootHtaccess) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to Activate BulletProof Security Root Folder Protection! Your Website is NOT protected with BulletProof Security!', 'bulletproof-security').'</strong></font>';
			echo $text;
   			echo $bps_bottomDiv;
		
		} else {
			
		if ( @$permsRootHtaccess == '0644' && @substr($sapi_type, 0, 6) != 'apache' && $options['bps_root_htaccess_autolock'] != 'Off') {			
			@chmod($RootHtaccess, 0404);
		}
			
			foreach( $deleteMaintenanceFiles as $deleteMaintenanceFile ) {
				if ( file_exists($deleteMaintenanceFile) ) {
					unlink($deleteMaintenanceFile);
				}
			}
			
			echo $bps_topDiv;
			$text = '<font color="green"><strong>'.__('BulletProof Security Root Folder Protection Activated. Your website Root folder is now protected with BulletProof Security.', 'bulletproof-security').'</strong></font><br><font color="red"><strong>'.__('IMPORTANT!', 'bulletproof-security').'</strong></font><strong> '.__('BulletProof Mode for the wp-admin folder MUST also be activated when you have BulletProof Mode activated for the Root folder.', 'bulletproof-security').'</strong>';
			echo $text;
    		echo $bps_bottomDiv;
		}
	}
	elseif ($selected_radio == 'bpdefaultroot') {
		$bpdefaultroot = 'checked';

		if ( @substr($sapi_type, 0, 6) != 'apache' || @$permsRootHtaccess != '0666' || @$permsRootHtaccess != '0777') { // Windows IIS, XAMPP, etc
			@chmod($RootHtaccess, 0644);
		}

		if ( !copy($DefaultHtaccess, $RootHtaccess) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to Activate Default htaccess Mode!', 'bulletproof-security').'</strong></font>';
			echo $text;
   			echo $bps_bottomDiv;
		
		} else {

		if ( @$permsRootHtaccess == '0644' && @substr($sapi_type, 0, 6) != 'apache' && $options['bps_root_htaccess_autolock'] != 'Off') {
				@chmod($RootHtaccess, 0404);
			}
			
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Warning: Default htaccess Mode Is Activated In Your Website Root Folder. Your Website Is Not Protected With BulletProof Security.', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo $bps_bottomDiv;
		}
	}
}

// Form - copy and rename htaccess file to wp-admin folder
// Do String Replacements for Custom Code AFTER new .htaccess file has been copied to wp-admin
$bpsecurewpadmin = 'unchecked';
$Removebpsecurewpadmin = 'unchecked';
if (isset($_POST['submit13']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_wpadmin_copy' );
	
	$options = get_option('bulletproof_security_options_customcode_WPA');  
	
	$HtaccessMaster = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/wpadmin-secure.htaccess';
	$wpadminHtaccess = ABSPATH . 'wp-admin/.htaccess';
	$permsHtaccess = @substr(sprintf('%o', fileperms($wpadminHtaccess)), -4);
	$sapi_type = php_sapi_name();	
	$bpsString1 = "# CCWTOP";
	$bpsString2 = "# CCWPF";
	$bpsString3 = '/#\sBEGIN\sBPS\sWPADMIN\sDENY\sACCESS\sTO\sFILES(.*)#\sEND\sBPS\sWPADMIN\sDENY\sACCESS\sTO\sFILES/s';
	$bpsString4 = '/#\sBEGIN\sBPSQSE-check\sBPS\sQUERY\sSTRING\sEXPLOITS\sAND\sFILTERS(.*)#\sEND\sBPSQSE-check\sBPS\sQUERY\sSTRING\sEXPLOITS\sAND\sFILTERS/s';
	$bpsReplace1 = htmlspecialchars_decode($options['bps_customcode_one_wpa'], ENT_QUOTES);
	$bpsReplace2 = htmlspecialchars_decode($options['bps_customcode_two_wpa'], ENT_QUOTES);
	$bpsReplace3 = htmlspecialchars_decode($options['bps_customcode_deny_files_wpa'], ENT_QUOTES);	
	$bpsReplace4 = htmlspecialchars_decode($options['bps_customcode_bpsqse_wpa'], ENT_QUOTES);	
	
	$selected_radio = $_POST['selection13'];
	
	if ($selected_radio == 'bpsecurewpadmin') {
		$bpsecurewpadmin = 'checked';

		if ( @substr($sapi_type, 0, 6) != 'apache' || @$permsHtaccess != '0666' || @$permsHtaccess != '0777') { // Windows IIS, XAMPP, etc
			@chmod($wpadminHtaccess, 0644);
		}		

		if ( !copy($HtaccessMaster, $wpadminHtaccess) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to Activate BulletProof Security wp-admin Folder Protection! Your wp-admin folder is NOT protected with BulletProof Security!', 'bulletproof-security').'</strong></font>';
			echo $text;
   			echo $bps_bottomDiv;
		
		} else {
	
			if ( file_exists($wpadminHtaccess) ) {
				
				if ( @$permsHtaccess != '0666' || @$permsHtaccess != '0777') { // Windows IIS, XAMPP, etc
					@chmod($wpadminHtaccess, 0644);
				}				
				
				$bpsBaseContent = @file_get_contents($wpadminHtaccess);
		
			if ( $options['bps_customcode_deny_files_wpa'] != '') {        
				$bpsBaseContent = preg_replace('/#\sBEGIN\sBPS\sWPADMIN\sDENY\sACCESS\sTO\sFILES(.*)#\sEND\sBPS\sWPADMIN\sDENY\sACCESS\sTO\sFILES/s', $bpsReplace3, $bpsBaseContent);
			}
			
			if ( $options['bps_customcode_bpsqse_wpa'] != '') {        
				$bpsBaseContent = preg_replace('/#\sBEGIN\sBPSQSE-check\sBPS\sQUERY\sSTRING\sEXPLOITS\sAND\sFILTERS(.*)#\sEND\sBPSQSE-check\sBPS\sQUERY\sSTRING\sEXPLOITS\sAND\sFILTERS/s', $bpsReplace4, $bpsBaseContent);
			}
				
				$bpsBaseContent = str_replace($bpsString1, $bpsReplace1, $bpsBaseContent);
				$bpsBaseContent = str_replace($bpsString2, $bpsReplace2, $bpsBaseContent);
				@file_put_contents($wpadminHtaccess, $bpsBaseContent);

				echo $bps_topDiv;
				$text = '<font color="green"><strong>'.__('BulletProof Security wp-admin Folder Protection Activated. Your wp-admin folder is now protected with BulletProof Security.', 'bulletproof-security').'</strong></font>';
				echo $text;
				echo $bps_bottomDiv;
			}
		}
	}
	elseif ($selected_radio == 'Removebpsecurewpadmin') {
		$Removebpsecurewpadmin = 'checked';
		$fh = fopen($wpadminHtaccess, 'a');
		fwrite($fh, 'delete');
		fclose($fh);
		@unlink($wpadminHtaccess);
	
	if ( file_exists($wpadminHtaccess) ) {
		echo $bps_topDiv;
		$text = '<font color="red"><strong>'.__('Failed to Delete the wp-admin htaccess file! The file does not exist. It may have been deleted or renamed already.', 'bulletproof-security').'</strong></font>';
		echo $text;
   		echo $bps_bottomDiv;
	
	} else {
		
		echo $bps_topDiv;
		$text = '<font color="green"><strong>'.__('The wp-admin htaccess file has been Deleted. ', 'bulletproof-security').'</strong></font><font color="red"><strong>'.__('Your wp-admin folder is no longer htaccess protected. ', 'bulletproof-security').'</strong></font>'.__('If you are testing then be sure to reactivate BulletProof Mode for your wp-admin folder when you are done testing. If you are removing BPS from your website then be sure to also Activate Default Mode for your Root folder. The Root and wp-admin BulletProof Modes must be activated together or removed together.', 'bulletproof-security').'</strong></font>';
		echo $text;
		echo $bps_bottomDiv;
	}
	}
}

// Form rename Deny All htaccess file to .htaccess for the BPS Master htaccess folder
$bps_rename_htaccess_files = 'unchecked';
if (isset($_POST['submit8']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_denyall_master' );
	
	$bps_rename_htaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/deny-all.htaccess';
	$bps_rename_htaccess_renamed = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/.htaccess';
	
	$selected_radio = $_POST['selection8'];
	if ($selected_radio == 'bps_rename_htaccess_files') {
		$bps_rename_htaccess_files = 'checked';

		if ( !copy($bps_rename_htaccess, $bps_rename_htaccess_renamed) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to Activate BulletProof Security Deny All Folder Protection! Your BPS Master htaccess folder is NOT Protected with Deny All htaccess folder protection!', 'bulletproof-security').'</strong></font>';
			echo $text;
   			echo $bps_bottomDiv;
			
		} else {
			
			echo $bps_topDiv;
			$text = __('BulletProof Security Deny All Folder Protection', 'bulletproof-security').'<font color="green"><strong> '.__('Activated.', 'bulletproof-security').' </strong></font>'.__('Your BPS Master htaccess folder is Now Protected with Deny All htaccess folder protection.', 'bulletproof-security');
			echo $text;
			echo $bps_bottomDiv;
		}
	}
}

// Form copy and rename the Deny All htaccess file to the BPS backup folder
$bps_rename_htaccess_files_backup = 'unchecked';
if (isset($_POST['submit14']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_denyall_bpsbackup' );
	
	$bps_rename_htaccess_backup = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/deny-all.htaccess';
	$bps_rename_htaccess_backup_online = WP_CONTENT_DIR . '/bps-backup/.htaccess';
	
	$selected_radio = $_POST['selection14'];
	if ($selected_radio == 'bps_rename_htaccess_files_backup') {
		$bps_rename_htaccess_files_backup = 'checked';
		
		if ( !copy($bps_rename_htaccess_backup, $bps_rename_htaccess_backup_online) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to Activate BulletProof Security Deny All Folder Protection! Your BPS /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup folder is NOT Protected with Deny All htaccess folder protection!', 'bulletproof-security').'</strong></font>';
			echo $text;
   			echo $bps_bottomDiv;
			
		} else {
			
			echo $bps_topDiv;
			$text = __('BulletProof Security Deny All Folder Protection', 'bulletproof-security').'<font color="green"><strong> '.__('Activated.', 'bulletproof-security').' </strong></font>'.__('Your BPS /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup folder is Now Protected with Deny All htaccess folder protection.', 'bulletproof-security');
			echo $text;
			echo $bps_bottomDiv;
		}
	}
}

// Form - Backup and rename existing and / or currently active htaccess files from 
// the root and wpadmin folders to /wp-content/bps-backup
$backup_htaccess = 'unchecked';
if (isset($_POST['submit9']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_backup_active_htaccess_files' );
	
	$old_backroot = ABSPATH . '.htaccess';
	$new_backroot = WP_CONTENT_DIR . '/bps-backup/master-backups/root.htaccess';
	$old_backwpadmin = ABSPATH . 'wp-admin/.htaccess';
	$new_backwpadmin = WP_CONTENT_DIR . '/bps-backup/master-backups/wpadmin.htaccess';
	$selected_radio = $_POST['selection9'];
	
	if ($selected_radio == 'backup_htaccess') {
		$backup_htaccess = 'checked';
		
		if ( !file_exists($old_backroot) ) { 
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('You do not currently have an .htaccess file in your Root folder to backup.', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo $bps_bottomDiv;
		
		} else {	
		
		if ( !copy($old_backroot, $new_backroot) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to Backup Your Root .htaccess File! File copy function failed. Check the folder permissions for the /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup folder. Folder permissions should be set to 755.', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo $bps_bottomDiv;
		
		} else {
			
			echo $bps_topDiv;
			$text = '<font color="green"><strong>'.__('Your currently active Root .htaccess file has been backed up successfully!', 'bulletproof-security').' </strong></font><br>'.__('Use the Restore feature to restore your htaccess files if you run into a problem at any time. If you make additional changes or install a plugin that writes to the htaccess files then back them up again. This will overwrite the currently backed up htaccess files. Please read the', 'bulletproof-security').' <font color="red"><strong> '.__('CAUTION:', 'bulletproof-security').' </strong></font>'.__('Read Me button on the Backup & Restore Page for more detailed information.', 'bulletproof-security');
			echo $text;
			echo $bps_bottomDiv;
		}
		}
		
		if ( !file_exists($old_backwpadmin)) { 
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('You do not currently have an htaccess file in your wp-admin folder to backup.', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo $bps_bottomDiv;
		
		} else {
		
		if ( !copy($old_backwpadmin, $new_backwpadmin) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to Backup Your wp-admin htaccess File! File copy function failed. Check the folder permissions for the /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup folder. Folder permissions should be set to 755.', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo $bps_bottomDiv;
		
		} else {
			
			echo $bps_topDiv;
			$text = '<font color="green"><strong>'.__('Your currently active wp-admin htaccess file has been backed up successfully!', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo $bps_bottomDiv;
		}
		}
	}
}

// Form - Restore backed up htaccess files
$restore_htaccess = 'unchecked';
if (isset($_POST['submit10']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_restore_active_htaccess_files' );
	
	$old_restoreroot = WP_CONTENT_DIR . '/bps-backup/master-backups/root.htaccess';
	$new_restoreroot = ABSPATH . '.htaccess';
	$old_restorewpadmin = WP_CONTENT_DIR . '/bps-backup/master-backups/wpadmin.htaccess';
	$new_restorewpadmin = ABSPATH . 'wp-admin/.htaccess';
	
	$selected_radio = $_POST['selection10'];
	if ($selected_radio == 'restore_htaccess') {
		$restore_htaccess = 'checked';
		
		if ( file_exists($old_restoreroot) ) { 
		if ( !copy($old_restoreroot, $new_restoreroot) ) {
			echo $bps_topDiv;
			echo '<font color="red"><strong>'.__('Failed to Restore Your Root htaccess File! This is most likely because you DO NOT currently have a Backed up Root htaccess file.', 'bulletproof-security').'</strong></font>';
   			echo $bps_bottomDiv;
			
		} else {
			
			echo $bps_topDiv;
			$text = '<font color="green"><strong>'.__('Your Root htaccess file has been Restored successfully!', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo $bps_bottomDiv;
		}
		}
		
		if ( file_exists($old_restorewpadmin) ) { 	
		if ( !copy($old_restorewpadmin, $new_restorewpadmin) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to Restore Your wp-admin htaccess File! This is most likely because you DO NOT currently have a Backed up wp-admin htaccess file.', 'bulletproof-security').'</strong></font>';
			echo $text;
   			echo $bps_bottomDiv;
		
		} else {
			
			echo $bps_topDiv;
			$text = '<font color="green"><strong>'.__('Your wp-admin htaccess file has been Restored successfully!', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo $bps_bottomDiv;
		}
		}
	}
}

// Form - Backup the BPS Master Files to /wp-content/bps-backup/master-backups
$backup_master_htaccess_files = 'unchecked';

if (isset($_POST['submit11']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_backup_master_htaccess_files' );

	$default_master = 'default.htaccess';
	$secure_master = 'secure.htaccess';
	$wpadmin_master = 'wpadmin-secure.htaccess';
	$maintenance_master = 'maintenance.htaccess';
	$bp_maintenance_master = 'bp-maintenance.php';
	$bps_maintenance_values = 'bps-maintenance-values.php';
	$bps_htaccess_master_dir = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/';
	$bps_master_backups_dir = WP_CONTENT_DIR . '/bps-backup/master-backups/';

	$selected_radio = $_POST['selection11'];
	
	if ($selected_radio == 'backup_master_htaccess_files') {
		$backup_master_htaccess_files = 'checked';
		
	$files = array($default_master, $secure_master, $wpadmin_master, $maintenance_master, $bp_maintenance_master, $bps_maintenance_values);
	
	echo $bps_topDiv;
	
	foreach( $files as $file ) {
		if ( file_exists($bps_htaccess_master_dir . $file) ) {				
		
			if ( !copy($bps_htaccess_master_dir . $file, $bps_master_backups_dir . 'backup_' . $file) ) {
				$text = '<font color="red"><strong>'.__('Failed to Backup the ', 'bulletproof-security').$file.__(' File', 'bulletproof-security').'</strong></font><br>';
				echo $text;
			} else {
				$text = '<font color="green"><strong>'.$file.__(' has been backed up successfully!', 'bulletproof-security').'</strong></font><br>';
				echo $text;
			}		
		}
	}
	echo $bps_bottomDiv;
	}
}

// Form - Activate Maintenance Mode copy and rename maintenance htaccess, bp-maintenance.php and bps-maintenance-values.php to root and ARQ
$bpmaintenance = 'unchecked';
if (isset($_POST['submit15']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_maintenance_copy' );

$oldmaint = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/maintenance.htaccess';
$newmaint = ABSPATH . '.htaccess';
$oldmaint1 = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/bp-maintenance.php';
$newmaint1 = ABSPATH . 'bp-maintenance.php';
$oldmaint_values = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/bps-maintenance-values.php';
$newmaint_values = ABSPATH . 'bps-maintenance-values.php';
$permsHtaccess = @substr(sprintf('%o', fileperms($newmaint)), -4);
$sapi_type = php_sapi_name();	
	
	$selected_radio = $_POST['selection15'];
	
	if ($selected_radio == 'bpmaintenance') {
		$bpmaintenance = 'checked';

		if ( @substr($sapi_type, 0, 6) != 'apache' || @$permsHtaccess != '0666' || @$permsHtaccess != '0777') { // Windows IIS, XAMPP, etc
				@chmod($newmaint, 0644);
		}

		if ( !copy($oldmaint, $newmaint) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to Activate Maintenance Mode! Your Website is NOT in Maintenance Mode!', 'bulletproof-security').'<br>'.__('If your Root htaccess file is locked you must unlock it first before activating Maintenance Mode.', 'bulletproof-security').'</strong></font>';
			echo $text;
   			echo $bps_bottomDiv;
		
		} else {
			
			@copy($oldmaint1, $newmaint1);
			@copy($oldmaint_values, $newmaint_values);
			
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Warning: ', 'bulletproof-security').'</strong></font>'.__('Maintenance Mode Is Activated. Your website is now displaying the Website Under Maintenance page to everyone except you. To switch out of Maintenance mode activate BulletProof Security Mode. You can log in and out of your Dashboard / WordPress website in Maintenance Mode as long as your current IP address does not change. If your current IP address changes you will have to FTP to your website and delete the htaccess file in your website root folder (or download the htaccess file and add your new IP address and upload it back to your root website folder) to be able to log back into your WordPress Dashboard. Your ISP provides your current Public IP address. If you reboot your computer or disconnect from the Internet there is a good chance that you will get a new Public IP address from your ISP.', 'bulletproof-security');
			echo $text;
			echo $bps_bottomDiv;
		}
	}
}	

/*****************************/
// BEGIN HTACCESS FILE WRITING
/*****************************/
$BPSCustomCodeOptions = get_option('bulletproof_security_options_customcode');
$bps_auto_write_default_file = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/default.htaccess';

$bpsSuccessMessageDef = '<font color="green"><strong>'.__('Success! Your Default Mode Master htaccess file was created successfully!', 'bulletproof-security').'</strong></font><br><font color="red"><strong>'.__('CAUTION: Default Mode should only be activated for testing or troubleshooting purposes. Default Mode does not protect your website with any security protection.', 'bulletproof-security').'</strong></font><br><font color="black"><strong>'.__('To activate Default Mode select the Default Mode radio button and click Activate to put your website in Default Mode.', 'bulletproof-security').'</strong></font>';

$bpsFailMessageDef = '<font color="red"><strong>'.__('The file ', 'bulletproof-security').$bps_auto_write_default_file.__(' is not writable or does not exist.', 'bulletproof-security').'</strong></font><br><strong>'.__('Check that the file is named default.htaccess and that the file exists in the /bulletproof-security/admin/htaccess master folder. If this is not the problem click ', 'bulletproof-security').'<a href="http://forum.ait-pro.com/" target="_blank">'.__('HERE', 'bulletproof-security').'</a>'.__(' to go the the BulletProof Security Forum.', 'bulletproof-security').'</strong>';

if ( $BPSCustomCodeOptions['bps_customcode_wp_rewrite_start'] != '') {        
$bpsBeginWP = "# CUSTOM CODE WP REWRITE LOOP START - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_wp_rewrite_start'], ENT_QUOTES)."\n\n";
} else {
$bpsBeginWP = "# WP REWRITE LOOP START
RewriteEngine On
RewriteBase $bps_get_wp_root_default
RewriteRule ^index\.php$ - [L]\n\n";
}

$bps_default_content_top = "#   BULLETPROOF DEFAULT .HTACCESS      \n
# If you edit the line of code above you will see error messages on the BPS Security Status page
# WARNING!!! THE default.htaccess FILE DOES NOT PROTECT YOUR WEBSITE AGAINST HACKERS
# This is a standard generic htaccess file that does NOT provide any website security
# The DEFAULT .HTACCESS file should be used for testing and troubleshooting purposes only\n
# BEGIN WordPress";

$bps_default_content_bottom = "\n<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase $bps_get_wp_root_default
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . $bps_get_wp_root_default"."index.php [L]
</IfModule>\n
# END WordPress";

$bpsMUEndWP = "# END WordPress";

// Multisite subfolder WordPress 3.0 through 3.4.2
if ( preg_match('/blogs.dir/', UPLOADBLOGSDIR ) ) {
$bpsMUSDirTop = "# uploaded files
RewriteRule ^([_0-9a-zA-Z-]+/)?files/(.+) wp-includes/ms-files.php?file=$2 [L]\n
# add a trailing slash to /wp-admin
RewriteRule ^([_0-9a-zA-Z-]+/)?wp-admin$ $1wp-admin/ [R=301,L]\n\n";
} else {
// Multisite subfolder WordPress 3.5+ first installation not upgraded from 3.4
$bpsMUSDirTop = "# add a trailing slash to /wp-admin
RewriteRule ^([_0-9a-zA-Z-]+/)?wp-admin$ $1wp-admin/ [R=301,L]\n\n";	
}

// Multisite subdomain WordPress 3.0 through 3.4.2
if ( preg_match('/blogs.dir/', UPLOADBLOGSDIR ) ) {
$bpsMUSDomTop = "# uploaded files
RewriteRule ^files/(.+) wp-includes/ms-files.php?file=$1 [L]\n\n";
} else {
// Multisite subdomain WordPress 3.5+
$bpsMUSDomTop = "# add a trailing slash to /wp-admin
RewriteRule ^wp-admin$ wp-admin/ [R=301,L]\n\n";	
}

// Multisite subfolder WordPress 3.0 through 3.4.2
if ( preg_match('/blogs.dir/', UPLOADBLOGSDIR ) ) {
$bpsMUSDirBottom = "RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule  ^[_0-9a-zA-Z-]+/(wp-(content|admin|includes).*) $1 [L]
RewriteRule  ^[_0-9a-zA-Z-]+/(.*\.php)$ $1 [L]
RewriteRule . index.php [L]
# WP REWRITE LOOP END\n\n";
} else {
// Multisite subfolder WordPress 3.5+ first installation not upgraded from 3.4
$bpsMUSDirBottom = "RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule ^([_0-9a-zA-Z-]+/)?(wp-(content|admin|includes).*) $2 [L]
RewriteRule ^([_0-9a-zA-Z-]+/)?(.*\.php)$ $2 [L]
RewriteRule . index.php [L]
# WP REWRITE LOOP END\n\n";	
}

// Multisite subdomain WordPress 3.0 through 3.4.2
if ( preg_match('/blogs.dir/', UPLOADBLOGSDIR ) ) {
$bpsMUSDomBottom = "RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule . index.php [L]
# WP REWRITE LOOP END\n\n";
} else {
// Multisite subdomain WordPress 3.5+
$bpsMUSDomBottom = "RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule ^(wp-(content|admin|includes).*) $1 [L]
RewriteRule ^(.*\.php)$ wp/$1 [L]
RewriteRule . index.php [L]
# WP REWRITE LOOP END\n\n";	
}

// secure.htaccess fwrite content for all WP site types
$bps_get_wp_root_secure = bps_wp_get_root_folder();
$bps_auto_write_secure_file = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/secure.htaccess';

$bpsSuccessMessageSec = '<font color="green"><strong>'.__('Success! Your BulletProof Security Root Master htaccess file was created successfully!', 'bulletproof-security').'</strong></font><br><font color="black"><strong>'.__('You can now Activate BulletProof Mode for your Root folder. Select the BulletProof Mode radio button and click Activate to put your website in BulletProof Mode.', 'bulletproof-security').'</strong></font>';

$bpsFailMessageSec = '<font color="red"><strong>'.__('The file ', 'bulletproof-security').$bps_auto_write_secure_file.__(' is not writable or does not exist.', 'bulletproof-security').'</strong></font><br><strong>'.__('Check that the file is named secure.htaccess and that the file exists in the /bulletproof-security/admin/htaccess master folder. If this is not the problem click', 'bulletproof-security').' <a href="http://forum.ait-pro.com/" target="_blank">'.__('HERE', 'bulletproof-security').'</a>'.__(' to go the the BulletProof Security Forum.', 'bulletproof-security').'</strong>';

$bps_secure_content_top = "#   BULLETPROOF $bps_version >>>>>>> SECURE .HTACCESS     \n
# If you edit the BULLETPROOF $bps_version >>>>>>> SECURE .HTACCESS text above
# you will see error messages on the BPS Security Status page
# BPS is reading the version number in the htaccess file to validate checks
# If you would like to change what is displayed above you
# will need to edit the BPS /includes/functions.php file to match your changes
# If you update your WordPress Permalinks the code between BEGIN WordPress and
# END WordPress is replaced by WP htaccess code.
# This removes all of the BPS security code and replaces it with just the default WP htaccess code
# To restore this file use BPS Restore or activate BulletProof Mode for your Root folder again.\n
# BEGIN WordPress
# IMPORTANT!!! DO NOT DELETE!!! - B E G I N Wordpress above or E N D WordPress - text in this file
# They are reference points for WP, BPS and other plugins to write to this htaccess file.
# IMPORTANT!!! DO NOT DELETE!!! - BPSQSE BPS QUERY STRING EXPLOITS - text
# BPS needs to find the - BPSQSE - text string in this file to validate that your security filters exist\n
# TURN OFF YOUR SERVER SIGNATURE
ServerSignature Off\n
# ADD A PHP HANDLER
# If you are using a PHP Handler add your web hosts PHP Handler below\n\n";

$bpsCCTop = '';
if ($BPSCustomCodeOptions['bps_customcode_one'] != '') {
$bpsCCTop = 'CustomCodeOne';

// AutoMagic - CUSTOM CODE TOP
switch ($bpsCCTop) {
	case "CustomCodeOne":
        $phpiniHCode = "# CUSTOM CODE TOP PHP/PHP.INI HANDLER/CACHE CODE - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_one'], ENT_QUOTES)."\n\n";
		break;
	default:
		$phpiniHCode = "# CUSTOM CODE TOP PHP/PHP.INI HANDLER/CACHE CODE - Your Custom .htaccess code will be created here with AutoMagic\n\n";
	}
}

if ( $BPSCustomCodeOptions['bps_customcode_directory_index'] != '') {        
$bps_secure_content_top_two = "# CUSTOM CODE DIRECTORY LISTING/DIRECTORY INDEX - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_directory_index'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_content_top_two = "# DO NOT SHOW DIRECTORY LISTING
# If you are getting 500 Errors when activating BPS then comment out Options -Indexes 
# by adding a # sign in front of it. If there is a typo anywhere in this file you will also see 500 errors.
Options -Indexes\n
# DIRECTORY INDEX FORCE INDEX.PHP
# Use index.php as default directory index file
# index.html will be ignored will not load.
DirectoryIndex index.php index.html /index.php\n\n";
}

// 95%/5% success/fail ratio for the original/standard code - code and has been modified as optional vs standard code
// this code needs to remain for those folks who choose/want to use this optional/Bonus code in Custom Code
if ( $BPSCustomCodeOptions['bps_customcode_server_protocol'] != '') {        
$bps_secure_server_protocol = "# CUSTOM CODE BRUTE FORCE LOGIN PAGE PROTECTION - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_server_protocol'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_server_protocol = "# BRUTE FORCE LOGIN PAGE PROTECTION
# PLACEHOLDER ONLY
# See this link: http://forum.ait-pro.com/forums/topic/protect-login-page-from-brute-force-login-attacks/
# for more information before choosing to add this code to BPS Custom Code
# Protects the Login page from SpamBots & Proxies
# that use Server Protocol HTTP/1.0 or a blank User Agent\n\n";
}

if ( $BPSCustomCodeOptions['bps_customcode_error_logging'] != '') {        
$bps_secure_error_logging = "# CUSTOM CODE ERROR LOGGING AND TRACKING - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_error_logging'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_error_logging = "# BPS ERROR LOGGING AND TRACKING
# BPS has premade 403 Forbidden, 400 Bad Request and 404 Not Found files that are used 
# to track and log 403, 400 and 404 errors that occur on your website. When a hacker attempts to
# hack your website the hackers IP address, Host name, Request Method, Referering link, the file name or
# requested resource, the user agent of the hacker and the query string used in the hack attempt are logged.
# All BPS log files are htaccess protected so that only you can view them. 
# The 400.php, 403.php and 404.php files are located in /$bps_plugin_dir/bulletproof-security/
# The 400 and 403 Error logging files are already set up and will automatically start logging errors
# after you install BPS and have activated BulletProof Mode for your Root folder.
# If you would like to log 404 errors you will need to copy the logging code in the BPS 404.php file
# to your Theme's 404.php template file. Simple instructions are included in the BPS 404.php file.
# You can open the BPS 404.php file using the WP Plugins Editor.
# NOTE: By default WordPress automatically looks in your Theme's folder for a 404.php template file.\n
ErrorDocument 400 $bps_get_wp_root_secure"."$bps_plugin_dir/bulletproof-security/400.php
ErrorDocument 401 default
ErrorDocument 403 $bps_get_wp_root_secure"."$bps_plugin_dir/bulletproof-security/403.php
ErrorDocument 404 $bps_get_wp_root_secure"."404.php\n\n";
}

$bps_secure_dot_server_files = "# DENY ACCESS TO PROTECTED SERVER FILES AND FOLDERS
# Files and folders starting with a dot: .htaccess, .htpasswd, .errordocs, .logs
RedirectMatch 403 \.(htaccess|htpasswd|errordocs|logs)$\n\n";

if ( $BPSCustomCodeOptions['bps_customcode_admin_includes'] != '') {        
$bps_secure_content_wpadmin = "# CUSTOM CODE WP-ADMIN/INCLUDES - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_admin_includes'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_content_wpadmin = "# WP-ADMIN/INCLUDES
RewriteEngine On
RewriteBase $bps_get_wp_root_secure
RewriteRule ^wp-admin/includes/ - [F,L]
RewriteRule !^wp-includes/ - [S=3]
RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]
RewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F,L]
RewriteRule ^wp-includes/theme-compat/ - [F,L]\n\n";
}

if ( $BPSCustomCodeOptions['bps_customcode_request_methods'] != '') {        
$bps_secure_content_mid_top = "# CUSTOM CODE REQUEST METHODS FILTERED - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_request_methods'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_content_mid_top = "# REQUEST METHODS FILTERED
# This filter is for blocking junk bots and spam bots from making a HEAD request, but may also block some
# HEAD request from bots that you want to allow in certains cases. This is not a security filter and is just
# a nuisance filter. This filter will not block any important bots like the google bot. If you want to allow
# all bots to make a HEAD request then remove HEAD from the Request Method filter.
# The TRACE, DELETE, TRACK and DEBUG request methods should never be allowed against your website.
RewriteEngine On
RewriteCond %{REQUEST_METHOD} ^(HEAD|TRACE|DELETE|TRACK|DEBUG) [NC]
RewriteRule ^(.*)$ - [F,L]\n\n";
}

$bps_secure_begin_plugins_skip_rules_text = "# PLUGINS/THEMES AND VARIOUS EXPLOIT FILTER SKIP RULES
# IMPORTANT!!! If you add or remove a skip rule you must change S= to the new skip number
# Example: If RewriteRule S=5 is deleted than change S=6 to S=5, S=7 to S=6, etc.\n\n";

// AutoMagic - CUSTOM CODE PLUGIN FIXES
$CustomCodeTwo = '';
if ( $BPSCustomCodeOptions['bps_customcode_two'] != '') {
$CustomCodeTwo = "# CUSTOM CODE PLUGIN/THEME SKIP/BYPASS RULES - Your plugins/themes skip/bypass rules .htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_two'], ENT_QUOTES)."\n\n";
}

$bps_secure_content_mid_top2 = "# Adminer MySQL management tool data populate
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."$bps_plugin_dir/adminer/ [NC]
RewriteRule . - [S=12]
# Comment Spam Pack MU Plugin - CAPTCHA images not displaying 
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."$bps_wpcontent_dir/mu-plugins/custom-anti-spam/ [NC]
RewriteRule . - [S=11]
# Peters Custom Anti-Spam display CAPTCHA Image
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."$bps_plugin_dir/peters-custom-anti-spam-image/ [NC] 
RewriteRule . - [S=10]
# Status Updater plugin fb connect
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."$bps_plugin_dir/fb-status-updater/ [NC] 
RewriteRule . - [S=9]
# Stream Video Player - Adding FLV Videos Blocked
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."$bps_plugin_dir/stream-video-player/ [NC]
RewriteRule . - [S=8]
# XCloner 404 or 403 error when updating settings
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."$bps_plugin_dir/xcloner-backup-and-restore/ [NC]
RewriteRule . - [S=7]
# BuddyPress Logout Redirect
RewriteCond %{QUERY_STRING} action=logout&redirect_to=http%3A%2F%2F(.*) [NC]
RewriteRule . - [S=6]
# redirect_to=
RewriteCond %{QUERY_STRING} redirect_to=(.*) [NC]
RewriteRule . - [S=5]
# Login Plugins Password Reset And Redirect 1
RewriteCond %{QUERY_STRING} action=resetpass&key=(.*) [NC]
RewriteRule . - [S=4]
# Login Plugins Password Reset And Redirect 2
RewriteCond %{QUERY_STRING} action=rp&key=(.*) [NC]
RewriteRule . - [S=3]\n\n";

if ( $BPSCustomCodeOptions['bps_customcode_timthumb_misc'] != '') {        
$bps_secure_timthumb_misc = "# CUSTOM CODE TIMTHUMB FORBID RFI and MISC FILE SKIP/BYPASS RULE - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_timthumb_misc'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_timthumb_misc = "# TIMTHUMB FORBID RFI and MISC FILE SKIP/BYPASS RULE
# Only Allow Internal File Requests From Your Website
# To Allow Additional Websites Access to a File Use [OR] as shown below.
# RewriteCond %{HTTP_REFERER} ^.*YourWebsite.com.* [OR]
# RewriteCond %{HTTP_REFERER} ^.*AnotherWebsite.com.*
RewriteCond %{QUERY_STRING} ^.*(http|https|ftp)(%3A|:)(%2F|/)(%2F|/)(w){0,3}.?(blogger|picasa|blogspot|tsunami|petapolitik|photobucket|imgur|imageshack|wordpress\.com|img\.youtube|tinypic\.com|upload\.wikimedia|kkc|start-thegame).*$ [NC,OR]
RewriteCond %{THE_REQUEST} ^.*(http|https|ftp)(%3A|:)(%2F|/)(%2F|/)(w){0,3}.?(blogger|picasa|blogspot|tsunami|petapolitik|photobucket|imgur|imageshack|wordpress\.com|img\.youtube|tinypic\.com|upload\.wikimedia|kkc|start-thegame).*$ [NC]
RewriteRule .* index.php [F,L]
RewriteCond %{REQUEST_URI} (timthumb\.php|phpthumb\.php|thumb\.php|thumbs\.php) [NC]
RewriteCond %{HTTP_REFERER} ^.*$bps_get_domain_root.*
RewriteRule . - [S=1]\n\n";
}

if ( $BPSCustomCodeOptions['bps_customcode_bpsqse'] != '') {        
$bps_secure_BPSQSE = "# CUSTOM CODE BPSQSE BPS QUERY STRING EXPLOITS - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_bpsqse'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_BPSQSE = "# BEGIN BPSQSE BPS QUERY STRING EXPLOITS
# The libwww-perl User Agent is forbidden - Many bad bots use libwww-perl modules, but some good bots use it too.
# Good sites such as W3C use it for their W3C-LinkChecker. 
# Add or remove user agents temporarily or permanently from the first User Agent filter below.
# If you want a list of bad bots / User Agents to block then scroll to the end of this file.
RewriteCond %{HTTP_USER_AGENT} (havij|libwww-perl|wget|python|nikto|curl|scan|java|winhttp|clshttp|loader) [NC,OR]
RewriteCond %{HTTP_USER_AGENT} (%0A|%0D|%27|%3C|%3E|%00) [NC,OR]
RewriteCond %{HTTP_USER_AGENT} (;|<|>|'|".'"'."|\)|\(|%0A|%0D|%22|%27|%28|%3C|%3E|%00).*(libwww-perl|wget|python|nikto|curl|scan|java|winhttp|HTTrack|clshttp|archiver|loader|email|harvest|extract|grab|miner) [NC,OR]
RewriteCond %{THE_REQUEST} \?\ HTTP/ [NC,OR]
RewriteCond %{THE_REQUEST} \/\*\ HTTP/ [NC,OR]
RewriteCond %{THE_REQUEST} etc/passwd [NC,OR]
RewriteCond %{THE_REQUEST} cgi-bin [NC,OR]
RewriteCond %{THE_REQUEST} (%0A|%0D|\\"."\\"."r|\\"."\\"."n) [NC,OR]
RewriteCond %{REQUEST_URI} owssvr\.dll [NC,OR]
RewriteCond %{HTTP_REFERER} (%0A|%0D|%27|%3C|%3E|%00) [NC,OR]
RewriteCond %{HTTP_REFERER} \.opendirviewer\. [NC,OR]
RewriteCond %{HTTP_REFERER} users\.skynet\.be.* [NC,OR]
RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=http:// [NC,OR]
RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=(\.\.//?)+ [NC,OR]
RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=/([a-z0-9_.]//?)+ [NC,OR]
RewriteCond %{QUERY_STRING} \=PHP[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12} [NC,OR]
RewriteCond %{QUERY_STRING} (\.\./|%2e%2e%2f|%2e%2e/|\.\.%2f|%2e\.%2f|%2e\./|\.%2e%2f|\.%2e/) [NC,OR]
RewriteCond %{QUERY_STRING} ftp\: [NC,OR]
RewriteCond %{QUERY_STRING} http\: [NC,OR] 
RewriteCond %{QUERY_STRING} https\: [NC,OR]
RewriteCond %{QUERY_STRING} \=\|w\| [NC,OR]
RewriteCond %{QUERY_STRING} ^(.*)/self/(.*)$ [NC,OR]
RewriteCond %{QUERY_STRING} ^(.*)cPath=http://(.*)$ [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (<|%3C)([^s]*s)+cript.*(>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*embed.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (<|%3C)([^e]*e)+mbed.*(>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*object.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (<|%3C)([^o]*o)+bject.*(>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*iframe.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (<|%3C)([^i]*i)+frame.*(>|%3E) [NC,OR] 
RewriteCond %{QUERY_STRING} base64_encode.*\(.*\) [NC,OR]
RewriteCond %{QUERY_STRING} base64_(en|de)code[^(]*\([^)]*\) [NC,OR]
RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} ^.*(\(|\)|<|>|%3c|%3e).* [NC,OR]
RewriteCond %{QUERY_STRING} ^.*(\\x00|\\x04|\\x08|\\x0d|\\x1b|\\x20|\\x3c|\\x3e|\\x7f).* [NC,OR]
RewriteCond %{QUERY_STRING} (NULL|OUTFILE|LOAD_FILE) [OR]
RewriteCond %{QUERY_STRING} (\.{1,}/)+(motd|etc|bin) [NC,OR]
RewriteCond %{QUERY_STRING} (localhost|loopback|127\.0\.0\.1) [NC,OR]
RewriteCond %{QUERY_STRING} (<|>|'|%0A|%0D|%27|%3C|%3E|%00) [NC,OR]
RewriteCond %{QUERY_STRING} concat[^\(]*\( [NC,OR]
RewriteCond %{QUERY_STRING} union([^s]*s)+elect [NC,OR]
RewriteCond %{QUERY_STRING} union([^a]*a)+ll([^s]*s)+elect [NC,OR]
RewriteCond %{QUERY_STRING} \-[sdcr].*(allow_url_include|allow_url_fopen|safe_mode|disable_functions|auto_prepend_file) [NC,OR]
RewriteCond %{QUERY_STRING} (;|<|>|'|".'"'."|\)|%0A|%0D|%22|%27|%3C|%3E|%00).*(/\*|union|select|insert|drop|delete|update|cast|create|char|convert|alter|declare|order|script|set|md5|benchmark|encode) [NC,OR]
RewriteCond %{QUERY_STRING} (sp_executesql) [NC]
RewriteRule ^(.*)$ - [F,L]
# END BPSQSE BPS QUERY STRING EXPLOITS\n";
}

$bps_secure_content_mid_bottom = "RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . $bps_get_wp_root_secure"."index.php [L]
# WP REWRITE LOOP END\n\n";

if ( $BPSCustomCodeOptions['bps_customcode_deny_files'] != '') {        
$bps_secure_content_bottom = "# CUSTOM CODE DENY BROWSER ACCESS TO THESE FILES - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_deny_files'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_content_bottom = "# DENY BROWSER ACCESS TO THESE FILES 
# wp-config.php, bb-config.php, php.ini, php5.ini, readme.html
# Replace Allow from 88.77.66.55 with your current IP address and remove the  
# pound sign # from in front of the Allow from line of code below to access these
# files directly from your browser.\n
<FilesMatch ".'"'."^(wp-config\.php|php\.ini|php5\.ini|readme\.html|bb-config\.php)".'"'.">
Order allow,deny
Deny from all
#Allow from 88.77.66.55
</FilesMatch>\n\n";
}

$bps_secure_end_wordpress_text = "# IMPORTANT!!! DO NOT DELETE!!! the END WordPress text below
# END WordPress\n\n";

// AutoMagic - CUSTOM CODE BOTTOM
$CustomCodeThree = '';
if ( $BPSCustomCodeOptions['bps_customcode_three'] != '') {
$CustomCodeThree = "# CUSTOM CODE BOTTOM HOTLINKING/FORBID COMMENT SPAMMERS/BLOCK BOTS/BLOCK IP/REDIRECT CODE - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_three'], ENT_QUOTES)."\n\n";
} else {
$CustomCodeThree = "# BLOCK HOTLINKING TO IMAGES
# To Test that your Hotlinking protection is working visit http://altlab.com/htaccess_tutorial.html
#RewriteEngine On
#RewriteCond %{HTTP_REFERER} !^https?://(www\.)?add-your-domain-here\.com [NC]
#RewriteCond %{HTTP_REFERER} !^$
#RewriteRule .*\.(jpeg|jpg|gif|bmp|png)$ - [F]\n
# FORBID COMMENT SPAMMERS ACCESS TO YOUR wp-comments-post.php FILE
# This is a better approach to blocking Comment Spammers so that you do not 
# accidentally block good traffic to your website. You can add additional
# Comment Spammer IP addresses on a case by case basis below.
# Searchable Database of known Comment Spammers http://www.stopforumspam.com/\n
<FilesMatch ".'"'."^(wp-comments-post\.php)".'"'.">
Order Allow,Deny
Deny from 46.119.35.
Deny from 46.119.45.
Deny from 91.236.74.
Deny from 93.182.147.
Deny from 93.182.187.
Deny from 94.27.72.
Deny from 94.27.75.
Deny from 94.27.76.
Deny from 193.105.210.
Deny from 195.43.128.
Deny from 198.144.105.
Deny from 199.15.234.
Allow from all
</FilesMatch>\n
# BLOCK MORE BAD BOTS RIPPERS AND OFFLINE BROWSERS
# If you would like to block more bad bots you can get a blacklist from
# http://perishablepress.com/press/2007/06/28/ultimate-htaccess-blacklist/
# You should monitor your site very closely for at least a week if you add a bad bots list
# to see if any website traffic problems or other problems occur.
# Copy and paste your bad bots user agent code list directly below.";
}

// Create maintenance htaccess file
if (isset($_POST['bps-auto-write-maint']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_auto_write_maint' );
	
$bps_string_replace_maint = array(".");
$bps_get_IP_maint = str_replace($bps_string_replace_maint, "\.", $_SERVER['REMOTE_ADDR']) . "$";
$bps_get_wp_root_maint = bps_wp_get_root_folder();
$bps_auto_write_maint_file = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/maintenance.htaccess';
$bps_maint_top = "#   BULLETPROOF MAINTENANCE .HTACCESS     \n\n";    
$bps_maint_content = "# BEGIN WordPress
RewriteEngine On
RewriteBase $bps_get_wp_root_maint\n
RewriteCond %{REQUEST_METHOD} ^(HEAD|TRACE|DELETE|TRACK|DEBUG) [NC]
RewriteRule ^(.*)$ - [F,L]\n
# TIMTHUMB FORBID RFI BY HOST NAME BUT ALLOW INTERNAL REQUESTS
RewriteCond %{QUERY_STRING} ^.*(http|https|ftp)(%3A|:)(%2F|/)(%2F|/)(w){0,3}.?(blogger|picasa|blogspot|tsunami|petapolitik|photobucket|imgur|imageshack|wordpress\.com|img\.youtube|tinypic\.com|upload\.wikimedia|kkc|start-thegame).*$ [NC,OR]
RewriteCond %{THE_REQUEST} ^.*(http|https|ftp)(%3A|:)(%2F|/)(%2F|/)(w){0,3}.?(blogger|picasa|blogspot|tsunami|petapolitik|photobucket|imgur|imageshack|wordpress\.com|img\.youtube|tinypic\.com|upload\.wikimedia|kkc|start-thegame).*$ [NC]
RewriteRule .* index.php [F,L]
RewriteCond %{REQUEST_URI} (timthumb\.php|phpthumb\.php|thumb\.php|thumbs\.php) [NC]
RewriteCond %{HTTP_REFERER} ^.*$bps_get_domain_root.*
RewriteRule . - [S=1]\n
# BPSQSE BPS QUERY STRING EXPLOITS
RewriteCond %{HTTP_USER_AGENT} (havij|libwww-perl|wget|python|nikto|curl|scan|java|winhttp|clshttp|loader) [NC,OR]
RewriteCond %{HTTP_USER_AGENT} (%0A|%0D|%27|%3C|%3E|%00) [NC,OR]
RewriteCond %{HTTP_USER_AGENT} (;|<|>|'|".'"'."|\)|\(|%0A|%0D|%22|%27|%28|%3C|%3E|%00).*(libwww-perl|wget|python|nikto|curl|scan|java|winhttp|HTTrack|clshttp|archiver|loader|email|harvest|extract|grab|miner) [NC,OR]
RewriteCond %{THE_REQUEST} \?\ HTTP/ [NC,OR]
RewriteCond %{THE_REQUEST} \/\*\ HTTP/ [NC,OR]
RewriteCond %{THE_REQUEST} etc/passwd [NC,OR]
RewriteCond %{THE_REQUEST} cgi-bin [NC,OR]
RewriteCond %{REQUEST_URI} owssvr\.dll [NC,OR]
RewriteCond %{HTTP_REFERER} (%0A|%0D|%27|%3C|%3E|%00) [NC,OR]
RewriteCond %{HTTP_REFERER} \.opendirviewer\. [NC,OR]
RewriteCond %{HTTP_REFERER} users\.skynet\.be.* [NC,OR]
RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=http:// [NC,OR]
RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=(\.\.//?)+ [NC,OR]
RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=/([a-z0-9_.]//?)+ [NC,OR]
RewriteCond %{QUERY_STRING} \=PHP[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12} [NC,OR]
RewriteCond %{QUERY_STRING} (\.\./|%2e%2e%2f|%2e%2e/|\.\.%2f|%2e\.%2f|%2e\./|\.%2e%2f|\.%2e/) [NC,OR]
RewriteCond %{QUERY_STRING} ftp\: [NC,OR]
RewriteCond %{QUERY_STRING} http\: [NC,OR] 
RewriteCond %{QUERY_STRING} https\: [NC,OR]
RewriteCond %{QUERY_STRING} \=\|w\| [NC,OR]
RewriteCond %{QUERY_STRING} ^(.*)/self/(.*)$ [NC,OR]
RewriteCond %{QUERY_STRING} ^(.*)cPath=http://(.*)$ [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (<|%3C)([^s]*s)+cript.*(>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*iframe.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (<|%3C)([^i]*i)+frame.*(>|%3E) [NC,OR] 
RewriteCond %{QUERY_STRING} base64_encode.*\(.*\) [NC,OR]
RewriteCond %{QUERY_STRING} base64_(en|de)code[^(]*\([^)]*\) [NC,OR]
RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} ^.*(\(|\)|<|>|%3c|%3e).* [NC,OR]
RewriteCond %{QUERY_STRING} ^.*(\\x00|\\x04|\\x08|\\x0d|\\x1b|\\x20|\\x3c|\\x3e|\\x7f).* [NC,OR]
RewriteCond %{QUERY_STRING} (NULL|OUTFILE|LOAD_FILE) [OR]
RewriteCond %{QUERY_STRING} (\.{1,}/)+(motd|etc|bin) [NC,OR]
RewriteCond %{QUERY_STRING} (localhost|loopback|127\.0\.0\.1) [NC,OR]
RewriteCond %{QUERY_STRING} (<|>|'|%0A|%0D|%27|%3C|%3E|%00) [NC,OR]
RewriteCond %{QUERY_STRING} concat[^\(]*\( [NC,OR]
RewriteCond %{QUERY_STRING} union([^s]*s)+elect [NC,OR]
RewriteCond %{QUERY_STRING} union([^a]*a)+ll([^s]*s)+elect [NC,OR]
RewriteCond %{QUERY_STRING} \-[sdcr].*(allow_url_include|allow_url_fopen|safe_mode|disable_functions|auto_prepend_file) [NC,OR]
RewriteCond %{QUERY_STRING} (;|<|>|'|".'"'."|\)|%0A|%0D|%22|%27|%3C|%3E|%00).*(/\*|union|select|insert|drop|delete|update|cast|create|char|convert|alter|declare|order|script|set|md5|benchmark|encode) [NC,OR]
RewriteCond %{QUERY_STRING} (sp_executesql) [NC]
RewriteRule ^(.*)$ - [F,L]\n
RewriteCond %{REMOTE_ADDR} !^$bps_get_IP_maint
RewriteCond %{REQUEST_URI} !^$bps_get_wp_root_maint"."bp-maintenance\.php$
RewriteCond %{REQUEST_URI} !^$bps_get_wp_root_maint"."$bps_plugin_dir/bulletproof-security/abstract-blue-bg\.png$
RewriteRule ^(.*)$ $bps_get_wp_root_maint"."bp-maintenance.php [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . $bps_get_wp_root_maint"."index.php [L]";
	
		$stringReplace = file_get_contents($bps_auto_write_maint_file);

	if ( file_exists($bps_auto_write_maint_file) ) {
		$stringReplace = $bps_maint_top.$phpiniHCode.$bps_maint_content;
		
		if ( file_put_contents( $bps_auto_write_maint_file, $stringReplace ) ) {
    		
			echo $bps_topDiv;
			$text = '<font color="green"><strong>'.__('Success! Your Maintenance Mode htaccess file was created successfully! Select the Maintenance Mode radio button and click Activate to put your website in Maintenance Mode.', 'bulletproof-security').'</strong></font>';
			echo $text;		
			echo $bps_bottomDiv;
		
		} else {
		
			echo $bps_topDiv;
    		$text = '<font color="red"><strong>'.__('The file ', 'bulletproof-security').$bps_auto_write_maint_file.__(' is not writable or does not exist.', 'bulletproof-security').'</strong></font><br><strong>'.__('Check that the file is named maintenance.htaccess and that the file exists in the /bulletproof-security/admin/htaccess master folder. If this is not the problem click ', 'bulletproof-security').'<a href="http://forum.ait-pro.com/" target="_blank">HERE</a>'.__(' to go the the BulletProof Security Forum.', 'bulletproof-security').'</strong>';
			echo $text;		
			echo $bps_bottomDiv;
		}
	}
}

// Create Default htaccess file - Single Site
if (isset($_POST['bps-auto-write-default']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_auto_write_default' );

		$stringReplace = file_get_contents($bps_auto_write_default_file);

	if ( file_exists($bps_auto_write_default_file) ) {
		$stringReplace = $bps_default_content_top.$bps_default_content_bottom;
		
		if ( file_put_contents( $bps_auto_write_default_file, $stringReplace ) ) {
    		
			echo $bps_topDiv;
			echo $bpsSuccessMessageDef;
			echo $bps_bottomDiv;
		
		} else {
		
			echo $bps_topDiv;
    		echo $bpsFailMessageDef;
			echo $bps_bottomDiv;
		}
	}
}

// Create Default htaccess file - MU Subdirectory
if (isset($_POST['bps-auto-write-default-MUSDir']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_auto_write_default_MUSDir' );

		$stringReplace = file_get_contents($bps_auto_write_default_file);

	if ( file_exists($bps_auto_write_default_file) ) {
		$stringReplace = $bps_default_content_top.$bpsBeginWP.$bpsMUSDirTop.$bpsMUSDirBottom.$bpsMUEndWP;
		
		if ( file_put_contents( $bps_auto_write_default_file, $stringReplace ) ) {
    		
			echo $bps_topDiv;
			echo $bpsSuccessMessageDef;
			echo $bps_bottomDiv;
		
		} else {
		
			echo $bps_topDiv;
    		echo $bpsFailMessageDef;
			echo $bps_bottomDiv;
		}
	}
}

// Create Default htaccess file - MU Subdomain
if (isset($_POST['bps-auto-write-default-MUSDom']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_auto_write_default_MUSDom' );

		$stringReplace = file_get_contents($bps_auto_write_default_file);

	if ( file_exists($bps_auto_write_default_file) ) {
		$stringReplace = $bps_default_content_top.$bpsBeginWP.$bpsMUSDomTop.$bpsMUSDomBottom.$bpsMUEndWP;
		
		if ( file_put_contents( $bps_auto_write_default_file, $stringReplace ) ) {
    		
			echo $bps_topDiv;
			echo $bpsSuccessMessageDef;
			echo $bps_bottomDiv;
		
		} else {
		
			echo $bps_topDiv;
    		echo $bpsFailMessageDef;
			echo $bps_bottomDiv;
		}
	}
}

// Create Secure htaccess master Root file - Single Site
if (isset($_POST['bps-auto-write-secure-root']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_auto_write_secure_root' );

		$stringReplace = file_get_contents($bps_auto_write_secure_file);

	if ( file_exists($bps_auto_write_secure_file) ) {
		$stringReplace = $bps_secure_content_top.$phpiniHCode.$bps_secure_content_top_two.$bps_secure_server_protocol.$bps_secure_error_logging.$bps_secure_dot_server_files.$bps_secure_content_wpadmin.$bpsBeginWP.$bps_secure_content_mid_top.$bps_secure_begin_plugins_skip_rules_text.$CustomCodeTwo.$bps_secure_content_mid_top2.$bps_secure_timthumb_misc.$bps_secure_BPSQSE.$bps_secure_content_mid_bottom.$bps_secure_content_bottom.$bps_secure_end_wordpress_text.$CustomCodeThree;
		
		if ( file_put_contents( $bps_auto_write_secure_file, $stringReplace ) ) {
    		
			echo $bps_topDiv;
			echo $bpsSuccessMessageSec;
			echo $bps_bottomDiv;
		
		} else {
		
			echo $bps_topDiv;
    		echo $bpsFailMessageSec;
			echo $bps_bottomDiv;
		}
	}
}

// Create Secure htaccess master Root file - MU Subdirectory
if (isset($_POST['bps-auto-write-secure-root-MUSDir']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_auto_write_secure_root_MUSDir' );

		$stringReplace = file_get_contents($bps_auto_write_secure_file);

	if ( file_exists($bps_auto_write_secure_file) ) {
		$stringReplace = $bps_secure_content_top.$phpiniHCode.$bps_secure_content_top_two.$bps_secure_server_protocol.$bps_secure_error_logging.$bps_secure_dot_server_files.$bpsBeginWP.$bpsMUSDirTop.$bps_secure_content_mid_top.$bps_secure_begin_plugins_skip_rules_text.$CustomCodeTwo.$bps_secure_content_mid_top2.$bps_secure_timthumb_misc.$bps_secure_BPSQSE.$bpsMUSDirBottom.$bps_secure_content_bottom.$bps_secure_end_wordpress_text.$CustomCodeThree;
		
		if ( file_put_contents( $bps_auto_write_secure_file, $stringReplace ) ) {
    		
			echo $bps_topDiv;
			echo $bpsSuccessMessageSec;
			echo $bps_bottomDiv;
		
		} else {
		
			echo $bps_topDiv;
    		echo $bpsFailMessageSec;
			echo $bps_bottomDiv;
		}
	}
}

// Create Secure htaccess master Root file - MU Subdomain
if (isset($_POST['bps-auto-write-secure-root-MUSDom']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_auto_write_secure_MUSDom' );

		$stringReplace = file_get_contents($bps_auto_write_secure_file);

	if ( file_exists($bps_auto_write_secure_file) ) {
		$stringReplace = $bps_secure_content_top.$phpiniHCode.$bps_secure_content_top_two.$bps_secure_server_protocol.$bps_secure_error_logging.$bps_secure_dot_server_files.$bpsBeginWP.$bpsMUSDomTop.$bps_secure_content_mid_top.$bps_secure_begin_plugins_skip_rules_text.$CustomCodeTwo.$bps_secure_content_mid_top2.$bps_secure_timthumb_misc.$bps_secure_BPSQSE.$bpsMUSDomBottom.$bps_secure_content_bottom.$bps_secure_end_wordpress_text.$CustomCodeThree;
		
		if ( file_put_contents( $bps_auto_write_secure_file, $stringReplace ) ) {
    		
			echo $bps_topDiv;
			echo $bpsSuccessMessageSec;
			echo $bps_bottomDiv;
		
		} else {
		
			echo $bps_topDiv;
    		echo $bpsFailMessageSec;
			echo $bps_bottomDiv;
		}
	}
}
/*****************************/
// END HTACCESS FILE WRITING
/*****************************/

// Create the Maintenance Mode Settings Values Form File - values from DB
if (isset($_POST['bps-maintenance-create-values_submit']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_create_values_form' );
	
	$options = get_option('bulletproof_security_options_maint');
	$bps_retry_after_write = $options['bps-retry-after'];
	$bps_site_title_write = $options['bps-site-title'];
	$bps_message1_write = $options['bps-message-1'];
	$bps_message2_write = $options['bps-message-2'];
	$bps_body_background_image_write = $options['bps-background-image'];
	$bps_auto_write_maint_file_form = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/bps-maintenance-values.php';

$bps_maint_content_form = "<?php".'
$bps_retry_after'." = '$bps_retry_after_write';\n"
.'$bps_site_title'." = '$bps_site_title_write';\n"
.'$bps_message1'." = '$bps_message1_write';\n"
.'$bps_message2'." = '$bps_message2_write';\n"
.'$bps_body_background_image'." = '$bps_body_background_image_write';
?>";
	
		$stringReplace = file_get_contents($bps_auto_write_maint_file_form);

	if ( file_exists($bps_auto_write_maint_file_form) ) {
		$stringReplace = $bps_maint_content_form;
		
		if ( file_put_contents( $bps_auto_write_maint_file_form, $stringReplace ) ) {
			copy($bps_auto_write_maint_file_form, $bps_auto_write_maint_file_formAR);
    		
			echo $bps_topDiv;
			$text = '<font color="green"><strong>'.__('Success! Your Maintenance Mode Form has been created successfully! Click the Preview button to preview your Website Under Maintenance page.', 'bulletproof-security').'</strong></font>';
			echo $text;		
			echo $bps_bottomDiv;
		
		} else {
		
			echo $bps_topDiv;
    		$text = '<font color="red"><strong>'.__('The file ', 'bulletproof-security').$bps_auto_write_maint_file_form.__(' is not writable or does not exist.', 'bulletproof-security').'</strong></font><br><strong>'.__('Check that the bps-maintenance-values.php file exists in the /bulletproof-security/admin/htaccess master folder. If this is not the problem click', 'bulletproof-security').' <a href="http://www.ait-pro.com/aitpro-blog/2566/bulletproof-security-plugin-support/bulletproof-security-error-messages" target="_blank">'.__('here', 'bulletproof-security').'</a> '.__('for more help info.', 'bulletproof-security').'</strong>';
			echo $text;		
			echo $bps_bottomDiv;
		}
	}
}

// General all purpose "Settings Saved." message for forms
if ( current_user_can('manage_options') && wp_script_is( 'bps-js', $list = 'queue' ) ) {
if ( @$_GET['settings-updated'] == true) {
	$text = '<p style="background-color:#ffffe0;font-size:1em;font-weight:bold;padding:5px;margin:0px;"><font color="green"><strong>'.__('Settings Saved', 'bulletproof-security').'</strong></font></p>';
	echo $text;
	}
}

$bpsSpacePop = '-------------------------------------------------------------';

?>
</div>

<!-- jQuery UI Tabs Menu -->
<div id="bps-container">
	<div id="bps-tabs" class="bps-menu">
    <div id="bpsHead" style="position:relative; top:0px; left:0px;"><img src="<?php echo plugins_url('/bulletproof-security/admin/images/bps-security-shield.png'); ?>" style="float:left; padding:0px 8px 0px 0px; margin:-72px 0px 0px 0px;" /></div>
		<ul>
			<li><a href="#bps-tabs-1"><?php _e('Security Modes', 'bulletproof-security'); ?></a></li>
            <li><a href="#bps-tabs-2"><?php _e('Security Status', 'bulletproof-security'); ?></a></li>
			<!--<li><a href="#bps-tabs-3"><?php //_e('Security Log', 'bulletproof-security'); ?></a></li>-->
			<!--<li><a href="#bps-tabs-4"><?php //_e('System Info', 'bulletproof-security'); ?></a></li>-->
			<li><a href="#bps-tabs-5"><?php _e('Backup &amp; Restore', 'bulletproof-security'); ?></a></li>
            <li><a href="#bps-tabs-6"><?php _e('htaccess File Editor', 'bulletproof-security'); ?></a></li>
            <li><a href="#bps-tabs-7"><?php _e('Custom Code', 'bulletproof-security'); ?></a></li>
			<li><a href="#bps-tabs-8"><?php _e('Maintenance Mode', 'bulletproof-security'); ?></a></li>
			<li><a href="#bps-tabs-9"><?php _e('Help &amp; FAQ', 'bulletproof-security'); ?></a></li>
			<li><a href="#bps-tabs-10"><?php _e('Whats New', 'bulletproof-security'); ?></a></li>
            <li><a href="#bps-tabs-11"><?php _e('My Notes', 'bulletproof-security'); ?></a></li>
            <li><a href="#bps-tabs-12"><?php _e('BPS Pro Features', 'bulletproof-security'); ?></a></li>
            <li><a href="#bps-tabs-13"><?php _e('Website Scanner', 'bulletproof-security'); ?></a></li>
            <li><a href="#bps-tabs-14"><?php _e('Website SEO', 'bulletproof-security'); ?></a></li>
		</ul>
            
<div id="bps-tabs-1" class="bps-tab-page">
<h2><?php _e('BulletProof Security Modes', 'bulletproof-security'); ?></h2>

<div id="bpsMonitoringAlerting" style="border-top:1px solid #999999;">

<h3><?php _e('Setup Steps & AutoMagic - Create Your htaccess Master Files', 'bulletproof-security'); ?>  <button id="bps-open-modal1" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button></h3>

<div id="bps-modal-content1" title="<?php _e('Setup Steps & AutoMagic', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br><strong>'.__('You can backup your existing htaccess files if you have any by clicking on the Backup & Restore menu tab - click on the Backup htaccess files radio button to select it and click on the Backup Files button to back up your existing htaccess files.','bulletproof-security').'</strong><br><br><strong>'.__('AutoMagic - BPS Creates Customized htaccess Master Files For Your Website Automatically','bulletproof-security').'</strong><br>'.__('BPS detects what type of WordPress installation you have and will display which AutoMagic buttons to use for your website in Green font.','bulletproof-security').'<br><br><strong>'.__('htaccess Core Setup Steps', 'bulletproof-security').'</strong><br>'.__('1. Click the ','bulletproof-security').'<strong>'.__('Create default.htaccess File ','bulletproof-security').'</strong>'.__('button.','bulletproof-security').'<br>'.__('2. Click the ','bulletproof-security').'<strong>'.__('Create secure.htaccess File ','bulletproof-security').'</strong>'.__('button.','bulletproof-security').'<br>'.__('3. Activate BulletProof Mode for your Root folder.','bulletproof-security').'<br>'.__('4. Activate BulletProof Mode for your wp-admin folder.','bulletproof-security').'<br><br><strong>'.__('BPS Master and BPS Backup folder steps below are done Automatically unless your Server does not allow this then you will have to activate the Deny All BulletProof Modes manually:','bulletproof-security').'</strong><br>'.__('1. Activate BulletProof Mode for the BPS Master htaccess folder.','bulletproof-security').'<br>'.__('2. Activate BulletProof Mode for the BPS Backup folder.','bulletproof-security').'<br><br><strong>'.__('NOTE: ', 'bulletproof-security').'</strong>'.__('If you would like to view, edit or add any additional .htaccess code to your new secure.htaccess Master file use BPS Custom Code or click on the htaccess File Editor tab page, click on the secure.htaccess menu tab and make your editing changes before you Activate BulletProof Mode for your Root folder.','bulletproof-security').'<br><br><strong>'.__('NOTE: If you activate BulletProof Mode for your Root folder you must also activate BulletProof Mode for your wp-admin folder.','bulletproof-security').'</strong><br><br><strong>'.__('WordPress Network (Multisite) Sites Info','bulletproof-security').'</strong><br>'.__('BPS will automatically detect whether you have a subdomain or subdirectory Network (Multisite) installation and display, which AutoMagic buttons to use in Green font. The BPS plugin can be Network Activated or you can allow the BPS plugin to be activated individually on each Network / Multisite subsite or of course you can choose not to Network Activate BPS or allow the BPS plugin on subsites. Super Admins will see BPS Dashboard Alerts and other Status displays on the Primary Site only. Administrators can activate or deactivate BPS on subsites, if you allow this on your Network / Multisite.','bulletproof-security').'<br><br><strong>'.__('BPS Subsite Menus:  Login Security and System Info: ', 'bulletproof-security').'</strong><br>'.__('Login Security and System Info menus / pages are available on Network / Multisite subsites to Super Admins and Administrators.','bulletproof-security').'<br><br><strong>'.__('Login Security has all the same functionality on Network/Multisite subsites with these exceptions: ', 'bulletproof-security').'</strong><br>'.__('Login Security email alerting is not available for subsites.','bulletproof-security').'<br><br><strong>'.__('System Info has all the same functionality on Network/Multisite subsites with these exceptions: ', 'bulletproof-security').'</strong><br>'.__('MySQL Database information is not displayed on subsites .','bulletproof-security').'<br><br><strong>'.__('BPS Primary Site Menus: All BPS menus are displayed on the Primary Site: ', 'bulletproof-security').'</strong><br>'.__('All other BulletProof Security features are not available on subsites since Network/Multisite subsites are virtual and do not have separate website files of their own. All of the other standard BulletProof Security features work sitewide and affect all other virtual subsites (with the exception of Login Security which works individually for each specific website - Primary or virtual subsites) and therefore should only be available to and controlled by the Super Admin with Network Admin capabilities for the Network/Multisite website.','bulletproof-security').'<br><br><strong>'.__('Additional Info:','bulletproof-security').'</strong><br>'.__('If you see error messages when performing a first time backup do not worry about it. BPS will backup whatever files should be or are available to backup for your website.','bulletproof-security').'<br><br>'.__('Clicking the ','bulletproof-security').'<strong>'.__('Create default.htaccess File ','bulletproof-security').'</strong>'.__('button and the ','bulletproof-security').'<strong>'.__('Create secure.htaccess File ','bulletproof-security').'</strong>'.__('button will create these two new customized master htaccess files for your website. The correct RewriteBase and RewriteRule for your website will be automatically added to these files. The default.htaccess file is the master htaccess file that is copied to your root folder when you Activate Default Mode. Default Mode should only be activated for testing and troubleshooting purposes - it does not provide any website security. The secure.htaccess file is the master htaccess file that is copied to your Root folder when you Activate BulletProof Mode for your Root folder.','bulletproof-security').'<br><br><strong>'.__('When you Activate BulletProof Mode for your Root folder it will overwrite the existing Root htaccess file.','bulletproof-security').'</strong> '.__('If you have added any custom htaccess code in your Root htaccess file you should save that custom code to BPS Custom Code.','bulletproof-security').'<br><br><strong>'.__('Editing htaccess Files - BPS Built-in File Editor','bulletproof-security').'</strong><br>'.__('BPS has a built-in htaccess File Editor if you want to edit your htaccess files manually. Go to the htaccess File Editor menu tab.','bulletproof-security').'<br><br><strong>'.__('BPS help links can be found in the Help & FAQ pages.','bulletproof-security').'</strong>'; echo $text; ?></p>
</div>

<?php if ( !current_user_can('manage_options') ) { _e('Permission Denied', 'bulletproof-security'); } else { ?>

<table width="100%" border="0">
  <tr>
    <td width="33%"><?php echo bps_multsite_check_smode_single(); ?></td>
    <td width="33%"><?php echo bps_multsite_check_smode_MUSDir(); ?></td>
    <td width="34%"><?php echo bps_multsite_check_smode_MUSDom(); ?></td>
  </tr>
  <tr>
    <td>
    
    <form name="bps-auto-write-default" action="admin.php?page=bulletproof-security/admin/options.php" method="post">
<?php wp_nonce_field('bulletproof_security_auto_write_default'); ?>
<input type="hidden" name="filename" value="bps-auto-write-default_write" />
<p class="submit">
<input type="submit" name="bps-auto-write-default" value="<?php _e('Create default.htaccess File', 'bulletproof-security'); ?>" class="bps-blue-button" onClick="return confirm('<?php $text = __('Clicking OK will create a new customized default.htaccess Master file for your website.', 'bulletproof-security').'\n\n'.__('This is only creating a Master file and NOT activating it. To activate Master files go to the Activate Security Modes section below.', 'bulletproof-security').'\n\n'.__('NOTE: Default Mode should ONLY be activated for Testing and Troubleshooting.', 'bulletproof-security').'\n\n'.__('Click OK to Create your new default.htaccess Master file or click Cancel.', 'bulletproof-security'); echo $text; ?>')" />
</p>
</form>

<form name="bps-auto-write-secure-root" action="admin.php?page=bulletproof-security/admin/options.php" method="post">
<?php wp_nonce_field('bulletproof_security_auto_write_secure_root'); ?>
<input type="hidden" name="filename" value="bps-auto-write-secure_write" />
<p class="submit">
<input type="submit" name="bps-auto-write-secure-root" value="<?php _e('Create secure.htaccess File', 'bulletproof-security'); ?>" class="bps-blue-button" onClick="return confirm('<?php $text = __('Clicking OK will create a new customized secure.htaccess Master file for your website.', 'bulletproof-security').'\n\n'.__('This is only creating a Master file and NOT activating it. To activate Master files go to the Activate Security Modes section below.', 'bulletproof-security').'\n\n'.__('Click OK to Create your new secure.htaccess Master file or click Cancel.', 'bulletproof-security'); echo $text; ?>')" />
</p>
</form>

</td>
    <td>

<form name="bps-auto-write-default-MUSDir" action="admin.php?page=bulletproof-security/admin/options.php" method="post">
<?php wp_nonce_field('bulletproof_security_auto_write_default_MUSDir'); ?>
<input type="hidden" name="filename" value="bps-auto-write-default_write-MUSDir" />
<p class="submit">
<input type="submit" name="bps-auto-write-default-MUSDir" value="<?php _e('Create default.htaccess File', 'bulletproof-security'); ?>" class="bps-blue-button" onClick="return confirm('<?php $text = __('Clicking OK will create a new customized default.htaccess Master file for your Network / Multisite subdirectory website.', 'bulletproof-security').'\n\n'.__('This is only creating a Master file and NOT activating it. To activate Master files go to the Activate Security Modes section below.', 'bulletproof-security').'\n\n'.__('NOTE: Default Mode should ONLY be activated for Testing and Troubleshooting.', 'bulletproof-security').'\n\n'.__('Click OK to Create your new default.htaccess Master file or click Cancel.', 'bulletproof-security'); echo $text; ?>')" />
</p>
</form>

<form name="bps-auto-write-secure-root-MUSDir" action="admin.php?page=bulletproof-security/admin/options.php" method="post">
<?php wp_nonce_field('bulletproof_security_auto_write_secure_root_MUSDir'); ?>
<input type="hidden" name="filename" value="bps-auto-write-secure_write_MUSDir" />
<p class="submit">
<input type="submit" name="bps-auto-write-secure-root-MUSDir" value="<?php _e('Create secure.htaccess File', 'bulletproof-security'); ?>" class="bps-blue-button" onClick="return confirm('<?php $text = __('Clicking OK will create a new customized secure.htaccess Master file for your Network / Multisite subdirectory website.', 'bulletproof-security').'\n\n'.__('This is only creating a Master file and NOT activating it. To activate Master files go to the Activate Security Modes section below.', 'bulletproof-security').'\n\n'.__('Click OK to Create your new secure.htaccess Master file or click Cancel.', 'bulletproof-security'); echo $text; ?>')" />
</p>
</form>

</td>
    <td>

<form name="bps-auto-write-default-MUSDom" action="admin.php?page=bulletproof-security/admin/options.php" method="post">
<?php wp_nonce_field('bulletproof_security_auto_write_default_MUSDom'); ?>
<input type="hidden" name="filename" value="bps-auto-write-default_write_MUSDom" />
<p class="submit">
<input type="submit" name="bps-auto-write-default-MUSDom" value="<?php _e('Create default.htaccess File', 'bulletproof-security'); ?>" class="bps-blue-button" onClick="return confirm('<?php $text = __('Clicking OK will create a new customized default.htaccess Master file for your Network / Multisite subdomain website.', 'bulletproof-security').'\n\n'.__('This is only creating a Master file and NOT activating it. To activate Master files go to the Activate Security Modes section below.', 'bulletproof-security').'\n\n'.__('NOTE: Default Mode should ONLY be activated for Testing and Troubleshooting.', 'bulletproof-security').'\n\n'.__('Click OK to Create your new default.htaccess Master file or click Cancel.', 'bulletproof-security'); echo $text; ?>')" />
</p>
</form>

<form name="bps-auto-write-secure-root-MUSDom" action="admin.php?page=bulletproof-security/admin/options.php" method="post">
<?php wp_nonce_field('bulletproof_security_auto_write_secure_MUSDom'); ?>
<input type="hidden" name="filename" value="bps-auto-write-secure_write_MUSDom" />
<p class="submit">
<input type="submit" name="bps-auto-write-secure-root-MUSDom" value="<?php _e('Create secure.htaccess File', 'bulletproof-security'); ?>" class="bps-blue-button" onClick="return confirm('<?php $text = __('Clicking OK will create a new customized secure.htaccess Master file for your Network / Multisite subdomain website.', 'bulletproof-security').'\n\n'.__('This is only creating a Master file and NOT activating it. To activate Master files go to the Activate Security Modes section below.', 'bulletproof-security').'\n\n'.__('Click OK to Create your new secure.htaccess Master file or click Cancel.', 'bulletproof-security'); echo $text; ?>')" />
</p>
</form>

</td>
  </tr>
</table>
<?php } ?>
</div>

<?php if ( !current_user_can('manage_options') ) { _e('Permission Denied', 'bulletproof-security'); } else { ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    
    <h2><?php _e('Activate Security Modes', 'bulletproof-security'); ?></h2>
    <h3><?php _e('Activate Website Root Folder .htaccess Security Mode', 'bulletproof-security'); ?>  <button id="bps-open-modal2" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button></h3>
    
    <div id="bps-modal-content2" title="<?php _e('Activate Root Folder BulletProof Mode', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br><strong>'.__('If you activate BulletProof Mode for your Root folder you must also activate BulletProof Mode for your wp-admin folder.','bulletproof-security').'</strong><br><br>'.__('Perform a backup first before activating any BulletProof Security modes (backs up both currently active root and wp-admin htaccess files at the same time).','bulletproof-security').'<br><br><strong>'.__('What Is Going On Here?','bulletproof-security').'</strong><br> '.__('Clicking the AutoMagic buttons creates your customized Master htaccess files for your website. You can add your own custom htaccess code to the Custom Code feature in BPS to have your custom code automatically created in your htaccess files with AutoMagic. For more information on how Custom Code works see the Read Me help button on the Custom Code page. Activating BulletProof Modes copies and renames those Master htaccess files from /plugins/bulletproof-security/admin/htaccess/ to your website root folder. Default Mode does not have any security protection - it is just a standard generic WordPress htaccess file that you should only Activate for testing or troubleshooting purposes.','bulletproof-security').'<br><br><strong>'.__('Help and FAQ links are available on the BPS Help and FAQ page','bulletproof-security').'</strong><br><br><strong>'.__('Testing or Removing / Uninstalling BPS','bulletproof-security').'</strong><br>'.__('If you are testing BPS to determine if there is a plugin conflict or other conflict then Activate Default Mode and select the Delete wp-admin htaccess File radio button and click the Activate button or you can now just go to the WordPress Permalinks page and update / resave your permalinks. This overwrites all BPS security code with the standard default WP htaccess code. This puts your site in a standard WordPress state with a default or generic Root htaccess file and no htaccess file in your wp-admin folder if you selected Delete wp-admin htaccess file. After testing or troubleshooting is completed reactivate BulletProof Modes for both the Root and wp-admin folders. If you are removing / uninstalling BPS then follow the same steps and then select Deactivate from the Wordpress Plugins page and then click Delete to uninstall the BPS plugin.','bulletproof-security').'<br><br><strong>'.__('BPS Video Tutorial links can be found in the Help & FAQ pages.','bulletproof-security').'</strong>'; echo $text; ?></p>
</div>

<form name="BulletProof-Root" action="admin.php?page=bulletproof-security/admin/options.php" method="post">
<?php wp_nonce_field('bulletproof_security_root_copy'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection12" type="radio" value="bpsecureroot" class="tog" <?php checked('', $bpsecureroot); ?> /> <?php _e('BulletProof Mode', 'bulletproof-security'); ?></label></th>
	<td class="url-path"><?php echo get_site_url(); ?>/.htaccess<br /><?php $text = '<font color="green">'.__('Copies the file secure.htaccess to your root folder and renames the file name to just .htaccess', 'bulletproof-security').'</font>'; echo $text; ?></td>
   </tr>
   <tr>   
   <th><label><input name="selection12" type="radio" value="bpdefaultroot" class="tog" <?php checked('', $bpdefaultroot); ?> /><?php $text = '<font color="red">'.__('Default Mode', 'bulletproof-security').'<br>'.__('WP Default htaccess File', 'bulletproof-security').'</font>'; echo $text; ?></label></th>
	<td class="url-path"><?php echo get_site_url(); ?>/.htaccess<br /><?php $text = '<font color="red">'.__(' CAUTION: ', 'bulletproof-security').'</font>'.__('Your site will not be protected if you activate Default Mode. ONLY activate Default Mode for Testing and Troubleshooting.', 'bulletproof-security'); echo $text; ?></td>
   </tr>
</table>
<p class="submit">
<input type="submit" name="submit12" value="<?php esc_attr_e('Activate', 'bulletproof-security') ?>" class="bps-blue-button" onClick="return confirm('<?php $text = __('Did you create your Master .htaccess files using the AutoMagic buttons?', 'bulletproof-security').'\n\n'.__('Did you backup your existing .htaccess files?', 'bulletproof-security').'\n\n'.__('Do you have any custom .htaccess code in your Root .htaccess file that you want to save before Activating BulletProof Mode?', 'bulletproof-security').'\n\n'.__('Clicking OK will overwrite your existing Root .htaccess file.', 'bulletproof-security').'\n\n'.__('Click OK to Activate BulletProof Mode for your Root folder or click Cancel.', 'bulletproof-security'); echo $text; ?>')" /></p>
</form>

<h3><?php _e('Activate Website wp-admin Folder .htaccess Security Mode', 'bulletproof-security'); ?>  <button id="bps-open-modal3" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button></h3>

<div id="bps-modal-content3" title="<?php _e('Activate wp-admin BulletProof Mode', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br><strong>'.__('If you activate BulletProof Mode for your wp-admin folder you must also activate BulletProof Mode for your Root folder.','bulletproof-security').'</strong><br><br>'.__('Activating BulletProof Mode copies, renames and moves the master htaccess file wpadmin-secure.htaccess from /plugins/bulletproof-security/admin/htaccess/ to your /wp-admin folder.','bulletproof-security').'<br><br><strong>'.__('Testing or Removing / Uninstalling BPS','bulletproof-security').'</strong><br>'.__('If you are testing BPS to determine if there is a plugin conflict or other conflict then Activate Default Mode and select the Delete wp-admin htaccess File radio button and click the Activate button. This puts your site in a standard WordPress state with a default or generic Root htaccess file and no htaccess file in your wp-admin folder. After testing or troubleshooting is completed reactivate BulletProof Modes for both the Root and wp-admin folders. If you are removing / uninstalling BPS then follow the same steps and then select Deactivate from the Wordpress Plugins page and then click Delete to uninstall the BPS plugin.','bulletproof-security').'<br><br><strong>'.__('BPS Video Tutorial links can be found in the Help & FAQ pages.','bulletproof-security').'</strong>'; echo $text; ?></p>
</div>

<form name="BulletProof-WPadmin" action="admin.php?page=bulletproof-security/admin/options.php" method="post">
<?php wp_nonce_field('bulletproof_security_wpadmin_copy'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection13" type="radio" value="bpsecurewpadmin" class="tog" <?php checked('', $bpsecurewpadmin); ?> /> <?php _e('BulletProof Mode', 'bulletproof-security'); ?></label></th>
	<td class="url-path"><?php echo get_site_url(); ?>/wp-admin/.htaccess<br /><?php $text = '<font color="green">'.__(' Copies the file wpadmin-secure.htaccess to your /wp-admin folder and renames the file name to just .htaccess', 'bulletproof-security').'</font>'; echo $text; ?></td>
   </tr>
   <tr>
	<th><label><input name="selection13" type="radio" value="Removebpsecurewpadmin" class="tog" <?php checked('', $Removebpsecurewpadmin); ?> /> <?php $text = '<font color="red">'.__('Delete wp-admin', 'bulletproof-security').'<br>'.__('htaccess File', 'bulletproof-security').'</font>'; echo $text; ?></label></th>
	<td class="url-path"><?php echo get_site_url(); ?>/wp-admin/.htaccess<br /><?php $text = '<font color="red">'.__(' CAUTION: ', 'bulletproof-security').'</font>'.__('Deletes the .htaccess file in your /wp-admin folder. ONLY delete For testing or BPS removal.', 'bulletproof-security'); echo $text; ?></td>
   </tr>
</table>
<p class="submit"><input type="submit" name="submit13" class="bps-blue-button" value="<?php esc_attr_e('Activate', 'bulletproof-security') ?>" />
</p>
</form>

<h3><?php _e('The Deny All htaccess BulletProof Modes/files below are activated/created automatically. If your Server does not allow this then manually activate the Deny All htaccess files below.', 'bulletproof-security'); ?> 
<h3><?php _e('Activate Deny All htaccess Folder Protection For The BPS Master htaccess Folder', 'bulletproof-security'); ?>  <button id="bps-open-modal4" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button></h3>

<div id="bps-modal-content4" title="<?php _e('BPS Master htaccess Folder', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br><strong>'.__('Your BPS Master htaccess folder should already be automatically protected by BPS, but if it is not then activate BulletProof Mode for your BPS Master htaccess folder.','bulletproof-security').'</strong><br><br>'.__('Activating BulletProof Mode for Deny All htaccess Folder Protection copies and renames the deny-all.htaccess file located in the /plugins/bulletproof-security/admin/htaccess/ folder and renames it to just .htaccess. The Deny All htaccess file blocks everyone, except for you, from accessing and viewing the BPS Master htaccess files.','bulletproof-security'); echo $text; ?></p>
</div>

<form name="BulletProof-deny-all-htaccess" action="admin.php?page=bulletproof-security/admin/options.php" method="post">
<?php wp_nonce_field('bulletproof_security_denyall_master'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection8" type="radio" value="bps_rename_htaccess_files" class="tog" <?php checked('', $bps_rename_htaccess_files); ?> /> <?php _e('BulletProof Mode', 'bulletproof-security'); ?></label></th>
	<td class="url-path"><?php echo plugins_url('/bulletproof-security/admin/htaccess/'); ?><br /><?php $text = '<font color="green">'.__(' Copies the file deny-all.htaccess to the BPS Master htaccess folder and renames the file name to just .htaccess', 'bulletproof-security').'</font>'; echo $text; ?></td>
   </tr>
</table>
<p class="submit"><input type="submit" name="submit8" class="bps-blue-button" value="<?php esc_attr_e('Activate', 'bulletproof-security') ?>" />
</p>
</form>

<h3><?php _e('Activate Deny All htaccess Folder Protection For The BPS Backup Folder', 'bulletproof-security'); ?>  <button id="bps-open-modal5" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button></h3>

<div id="bps-modal-content5" title="<?php _e('BPS Backup Folder', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br><strong>'.__('Your BPS Backup folder should already be automatically protected by BPS, but if it is not then activate BulletProof Mode for your BPS Backup folder.','bulletproof-security').'</strong><br><br>'.__('Activating BulletProof Mode for Deny All BPS Backup Folder Protection copies and renames the deny-all.htaccess file located in the /bulletproof-security/admin/htaccess/ folder to the BPS Backup folder /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup and renames it to just .htaccess. The Deny All htaccess file blocks everyone, except for you, from accessing and viewing your backed up htaccess files.','bulletproof-security'); echo $text; ?></p>
</div>

<form name="BulletProof-deny-all-backup" action="admin.php?page=bulletproof-security/admin/options.php" method="post">
<?php wp_nonce_field('bulletproof_security_denyall_bpsbackup'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection14" type="radio" value="bps_rename_htaccess_files_backup" class="tog" <?php checked('', $bps_rename_htaccess_files_backup); ?> /> <?php _e('BulletProof Mode', 'bulletproof-security'); ?></label></th>
	<td class="url-path"><?php echo get_site_url().'/'.$bps_wpcontent_dir.'/bps-backup/'; ?><br /><?php $text = '<font color="green">'.__(' Copies the file deny-all.htaccess to the BPS Backup folder and renames the file name to just .htaccess', 'bulletproof-security').'</font>'; echo $text; ?></td>
   </tr>
</table>
<p class="submit"><input type="submit" name="submit14" class="bps-blue-button" value="<?php esc_attr_e('Activate', 'bulletproof-security') ?>" />
</p>
</form>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>
            
<div id="bps-tabs-2" class="bps-tab-page">
<h2><?php _e('BulletProof Security Status', 'bulletproof-security'); ?></h2>

<?php if ( !current_user_can('manage_options') ) { _e('Permission Denied', 'bulletproof-security'); } else { ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-status_table">
  <tr>
    <td width="49%" class="bps-table_title_SS">
	
	<?php _e('Activated BulletProof Security .htaccess Files', 'bulletproof-security'); ?>  <button id="bps-open-modal6" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button>
    
    <div id="bps-modal-content6" title="<?php _e('Activated htaccess Files', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br><strong>'.__('If you activate BulletProof Mode for your Root folder you must also activate BulletProof Mode for your wp-admin folder.','bulletproof-security').'</strong><br><br>'.__('Perform a backup first before activating any BulletProof Security modes (backs up both currently active root and wp-admin htaccess files at the same time).','bulletproof-security').'<br><br><strong>'.__('Help and FAQ links are available on the BPS Help and FAQ page','bulletproof-security').'</strong><br><br>'.__('The Text Strings you see listed in the Activated BulletProof Security Status window if you have an active BulletProof htaccess file (or an existing htaccess file) is reading and displaying the actual contents of any existing htaccess files here. ','bulletproof-security').'<strong>'.__('This is not just a displayed message - this is the actual first 46 string characters (text) of the contents of your htaccess files.','bulletproof-security').'</strong>'.__('The BPSQSE BPS QUERY STRING EXPLOITS code check is done by searching the root htaccess file to verify that the string/text/word BPSQSE is in the file.','bulletproof-security').'<br><br><strong>'.__('Troubleshooting Error Messages','bulletproof-security').'</strong><br>'.__('See the Forum: Post Questions and Comments For Assistance link on the Help and FAQ page. ','bulletproof-security').'<br><br><strong>'.__('Miscellaneous Info','bulletproof-security').'</strong><br>'.__('To change or modify the Text String that you see displayed here you would use the BPS built in Text Editor to change the actual text content of the BulletProof Security master htaccess files. If the change the BULLETPROOF title shown here then you must also change the code in the /', 'bulletproof-security').$bps_plugin_dir.__('/bulletproof-security/includes/functions.php file to match your changes or you will get some error messages. The rest of the text content in the htaccess files can be modified just like a normal post. Just this top line ot text in the htaccess files contains version information that BPS checks to do verifications and other file checking. For detailed instructions on modifying what text is displayed here click this Read Me button link.','bulletproof-security').'<br><br><strong>'.__('BPS Video Tutorial links can be found in the Help & FAQ pages.','bulletproof-security').'</strong>';  echo $text; ?></p>
</div>

</td>
    <td width="2%">&nbsp;</td>
    <td width="49%" class="bps-table_title"><?php _e('Additional Website Security Measures', 'bulletproof-security'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell">
<?php 
	echo bps_root_htaccess_status();
	echo bps_denyall_htaccess_status_master();
	echo bps_denyall_htaccess_status_backup();
	echo bps_wpadmin_htaccess_status();
?>
    <td>&nbsp;</td>
    <td class="bps-table_cell">
<?php 
	echo bps_wpdb_errors_off();
	echo bps_wp_remove_version();
	echo bps_check_admin_username();
	echo bps_filesmatch_check_readmehtml();
	echo bps_filesmatch_check_installphp();

// Reset/Recheck Dismiss Notices
function bpsDeleteUserMetaDismiss() {
	if (isset($_POST['bpsResetDismissSubmit']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_reset_dismiss_notices' );	  

	global $current_user;
	$user_id = $current_user->ID;

	if ( !delete_user_meta($user_id, 'bps_ignore_iis_notice') ) {
		$text = '<div id="message" class="updated fade" style="color:#000000; font-weight:bold; border:1px solid #999999; margin-left:70px; background-color:#ffffe0;"><p>'.__('The Windows IIS Dismiss Notice is NOT set. Nothing to reset.', 'bulletproof-security').'</p></div>';
		echo $text;
	} else {
		$text = '<div id="message" class="updated fade" style="color:#008000; font-weight:bold; border:1px solid #999999; margin-left:70px;background-color:#ffffe0;"><p>'.__('Success! The Windows IIS check is reset.', 'bulletproof-security').'</p><div class="bps-message-button" style="width:90px;margin-bottom:9px;"><a href="admin.php?page=bulletproof-security/admin/options.php">'.__('Refresh Status', 'bulletproof-security').'</a></div></div>';
		echo $text;
	}

	if ( !delete_user_meta($user_id, 'bps_hud_NetworkActivationAlert_notice') ) {
		$text = '<div id="message" class="updated fade" style="color:#000000; font-weight:bold; border:1px solid #999999; margin-left:70px; background-color:#ffffe0;"><p>'.__('The BPS Network/Multisite Plugin Network Activation Notice is NOT set. Nothing to reset.', 'bulletproof-security').'</p></div>';
		echo $text;
	} else {
		$text = '<div id="message" class="updated fade" style="color:#008000; font-weight:bold; border:1px solid #999999; margin-left:70px;background-color:#ffffe0;"><p>'.__('Success! The BPS Network/Multisite Plugin Network Activation Notice is reset.', 'bulletproof-security').'</p><div class="bps-message-button" style="width:90px;margin-bottom:9px;"><a href="admin.php?page=bulletproof-security/admin/options.php">'.__('Refresh Status', 'bulletproof-security').'</a></div></div>';
		echo $text;
	}

	if ( !delete_user_meta($user_id, 'bps_brute_force_login_protection_notice') ) {
		$text = '<div id="message" class="updated fade" style="color:#000000; font-weight:bold; border:1px solid #999999; margin-left:70px; background-color:#ffffe0;"><p>'.__('The Bonus Custom Code: Brute Force Login Protection Dismiss Notice is NOT set. Nothing to reset.', 'bulletproof-security').'</p></div>';
		echo $text;
	} else {
		$text = '<div id="message" class="updated fade" style="color:#008000; font-weight:bold; border:1px solid #999999; margin-left:70px;background-color:#ffffe0;"><p>'.__('Success! The Bonus Custom Code: Brute Force Login Protection Notice is reset.', 'bulletproof-security').'</p><div class="bps-message-button" style="width:90px;margin-bottom:9px;"><a href="admin.php?page=bulletproof-security/admin/options.php">'.__('Refresh Status', 'bulletproof-security').'</a></div></div>';
		echo $text;
	}

	if ( !delete_user_meta($user_id, 'bps_speed_boost_cache_notice') ) {
		$text = '<div id="message" class="updated fade" style="color:#000000; font-weight:bold; border:1px solid #999999; margin-left:70px; background-color:#ffffe0;"><p>'.__('The Bonus Custom Code: Speed Boost Cache Code Dismiss Notice is NOT set. Nothing to reset.', 'bulletproof-security').'</p></div>';
		echo $text;
	} else {
		$text = '<div id="message" class="updated fade" style="color:#008000; font-weight:bold; border:1px solid #999999; margin-left:70px;background-color:#ffffe0;"><p>'.__('Success! The Bonus Custom Code: Speed Boost Cache Code Notice is reset.', 'bulletproof-security').'<br>'.__('Note: The Speed Boost Cache Code Notice will ONLY be displayed after you dismiss the Brute Force Login Protection Notice.', 'bulletproof-security').'</p><div class="bps-message-button" style="width:90px;margin-bottom:9px;"><a href="admin.php?page=bulletproof-security/admin/options.php">'.__('Refresh Status', 'bulletproof-security').'</a></div></div>';
		echo $text;
	}

	if ( !delete_user_meta($user_id, 'bps_author_enumeration_notice') ) {
		$text = '<div id="message" class="updated fade" style="color:#000000; font-weight:bold; border:1px solid #999999; margin-left:70px; background-color:#ffffe0;"><p>'.__('The Bonus Custom Code: Author Enumeration BOT Probe Code Dismiss Notice is NOT set. Nothing to reset.', 'bulletproof-security').'</p></div>';
		echo $text;
	} else {
		$text = '<div id="message" class="updated fade" style="color:#008000; font-weight:bold; border:1px solid #999999; margin-left:70px;background-color:#ffffe0;"><p>'.__('Success! The Bonus Custom Code: Author Enumeration BOT Probe Code Notice is reset.', 'bulletproof-security').'<br>'.__('Note: The Author Enumeration BOT Probe Code Notice will ONLY be displayed after you dismiss the Speed Boost Cache Code Notice.', 'bulletproof-security').'</p><div class="bps-message-button" style="width:90px;margin-bottom:9px;"><a href="admin.php?page=bulletproof-security/admin/options.php">'.__('Refresh Status', 'bulletproof-security').'</a></div></div>';
		echo $text;
	}

	if ( !delete_user_meta($user_id, 'bps_ignore_PhpiniHandler_notice') ) {
		$text = '<div id="message" class="updated fade" style="color:#000000; font-weight:bold; border:1px solid #999999; margin-left:70px; background-color:#ffffe0;"><p>'.__('The PHP/php.ini handler htaccess code check Dismiss Notice is NOT set. Nothing to reset.', 'bulletproof-security').'</p></div>';
		echo $text;
	} else {
		$text = '<div id="message" class="updated fade" style="color:#008000; font-weight:bold; border:1px solid #999999; margin-left:70px;background-color:#ffffe0;"><p>'.__('Success! The PHP/php.ini handler htaccess code check Notice is reset.', 'bulletproof-security').'<br>'.__('Note: The PHP/php.ini handler htaccess code check Notice will ONLY be displayed after you dismiss the Speed Boost Cache Code Notice.', 'bulletproof-security').'</p><div class="bps-message-button" style="width:90px;margin-bottom:9px;"><a href="admin.php?page=bulletproof-security/admin/options.php">'.__('Refresh Status', 'bulletproof-security').'</a></div></div>';
		echo $text;
	}

	if ( !delete_user_meta($user_id, 'bps_ignore_Permalinks_notice') ) {
		$text = '<div id="message" class="updated fade" style="color:#000000; font-weight:bold; border:1px solid #999999; margin-left:70px; background-color:#ffffe0;"><p>'.__('The Custom Permalinks HUD Check Dismiss Notice is NOT set. Nothing to reset.', 'bulletproof-security').'</p></div>';
		echo $text;
	} else {
		$text = '<div id="message" class="updated fade" style="color:#008000; font-weight:bold; border:1px solid #999999; margin-left:70px;background-color:#ffffe0;"><p>'.__('Success! The Custom Permalinks HUD Check is reset.', 'bulletproof-security').'</p><div class="bps-message-button" style="width:90px;margin-bottom:9px;"><a href="admin.php?page=bulletproof-security/admin/options.php">'.__('Refresh Status', 'bulletproof-security').'</a></div></div>';
		echo $text;
	}

	if ( !delete_user_meta($user_id, 'bps_ignore_sucuri_notice') ) {
		$text = '<div id="message" class="updated fade" style="color:#000000; font-weight:bold; border:1px solid #999999; margin-left:70px; background-color:#ffffe0;"><p>'.__('The Sucuri 1-click Hardening wp-content HUD Check Dismiss Notice is NOT set. Nothing to reset.', 'bulletproof-security').'</p></div>';
		echo $text;
	} else {
		$text = '<div id="message" class="updated fade" style="color:#008000; font-weight:bold; border:1px solid #999999; margin-left:70px;background-color:#ffffe0;"><p>'.__('Success! The Sucuri 1-click Hardening wp-content HUD Check is reset.', 'bulletproof-security').'</p><div class="bps-message-button" style="width:90px;margin-bottom:9px;"><a href="admin.php?page=bulletproof-security/admin/options.php">'.__('Refresh Status', 'bulletproof-security').'</a></div></div>';
		echo $text;
	}

	if ( !delete_user_meta($user_id, 'bps_ignore_BLC_notice') ) {
		$text = '<div id="message" class="updated fade" style="color:#000000; font-weight:bold; border:1px solid #999999; margin-left:70px; background-color:#ffffe0;"><p>'.__('The Broken Link Checker plugin HEAD Request Method filter HUD Check Dismiss Notice is NOT set. Nothing to reset.', 'bulletproof-security').'</p></div>';
		echo $text;
	} else {
		$text = '<div id="message" class="updated fade" style="color:#008000; font-weight:bold; border:1px solid #999999; margin-left:70px;background-color:#ffffe0;"><p>'.__('Success! The Broken Link Checker plugin HEAD Request Method filter HUD Check is reset.', 'bulletproof-security').'</p><div class="bps-message-button" style="width:90px;margin-bottom:9px;"><a href="admin.php?page=bulletproof-security/admin/options.php">'.__('Refresh Status', 'bulletproof-security').'</a></div></div>';
		echo $text;
	}

/* maybe later version - not now
	if ( !delete_user_meta($user_id, 'bps_ignore_public_username_notice') ) {
		$text = '<div id="message" class="updated fade" style="color:#000000; font-weight:bold; border:1px solid #999999; margin-left:70px; background-color:#ffffe0;"><p>'.__('The username/user account Public Display Dismiss Notice is NOT set. Nothing to reset.', 'bulletproof-security').'</p></div>';
		echo $text;
	} else {
		$text = '<div id="message" class="updated fade" style="color:#008000; font-weight:bold; border:1px solid #999999; margin-left:70px;background-color:#ffffe0;"><p>'.__('Success! The username/user account Public Display check is reset.', 'bulletproof-security').'</p><div class="bps-message-button" style="width:90px;margin-bottom:9px;"><a href="admin.php?page=bulletproof-security/admin/monitor/monitor.php">'.__('Refresh Status', 'bulletproof-security').'</a></div></div>';
		echo $text;
	}
*/
	}
}

?>

<div id="ResetDismissNotices" style="position:relative;top:0px;left:0px;">
<form name="bpsResetDismissNotices" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-2" method="post">
<?php wp_nonce_field('bulletproof_security_reset_dismiss_notices'); ?>
    <p><strong><?php _e('Reset / Recheck Dismiss Notices: ', 'bulletproof-security'); ?>
<input type="hidden" name="bpsRDN" value="bps-RDN" />
<input type="submit" name="bpsResetDismissSubmit" class="bps-blue-button" value="<?php esc_attr_e('Reset / Recheck', 'bulletproof-security') ?>" />
</strong></p>
<?php echo bpsDeleteUserMetaDismiss(); ?>
</form>
</div>

  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
</table>
<?php } ?>

<?php if ( !current_user_can('manage_options') ) { _e('Permission Denied', 'bulletproof-security'); } else { ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-perms_table">
  <tr>
    <td class="bps-table_title_SS">
	
	<?php _e('File and Folder Permissions - CGI or DSO', 'bulletproof-security'); ?>  <button id="bps-open-modal7" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button>
    
    <div id="bps-modal-content7" title="<?php _e('File and Folder Permissions', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br><strong>'.__('CGI And DSO File And Folder Permission Recommendations','bulletproof-security').'</strong><br>'.__('If your Server API (SAPI) is CGI you will see a table displayed with recommendations for file and folder permissions for CGI. If your SAPI is DSO / Apache mod_php you will see a table listing file and folder permission recommendations for DSO.', 'bulletproof-security').'<br><br>'.__('If your Host is using CGI, but they do not allow you to set your folder permissions more restrictive to 705 and file permissions more restrictive to 604 then most likely when you change your folder and file permissions they will automatically be changed back to 755 and 644 by your Host or you may see a 403 or 500 error and will need to change the folder permissions back to what they were before. CGI 705 folder permissions have been thoroughly tested with WordPress and no problems have been discovered with WP or with WP Plugins on several different Web Hosts, but all web hosts have different things that they specifically allow or do not allow.', 'bulletproof-security').'<br><br>'.__('Most Hosts now use 705 Root folder permissions. Your Host might not be doing this or allow this, but typically 755 is fine for your Root folder. Changing your folder permissions to 705 helps in protecting against Mass Host Code Injections. CGI 604 file permissions have been thoroughly tested with WordPress and no problems have been discovered with WP or with WP Plugins. Changing your file permissions to 604 helps in protecting your files from Mass Host Code Injections. CGI Mission Critical files should be set to 400 and 404 respectively.','bulletproof-security').'<br><br><strong>'.__('If you have BPS Pro installed then use F-Lock to Lock or Unlock your Mission Critical files. BPS Pro S-Monitor will automatically display warning messages if your files are unlocked.','bulletproof-security').'</strong><br><br><strong>'.__('The wp-content/bps-backup/ folder permission recommendation is 755 for CGI or DSO for compatibility reasons. The /bps-backup folder has a deny all htaccess file in it so that it cannot be accessed by anyone other than you so the folder permissions for this folder are irrelevant.','bulletproof-security').'</strong><br><br>'.__('Your current file and folder permissions are shown below with suggested / recommended file and folder permissions. ','bulletproof-security').'<strong>'.__('Not all web hosts will allow you to set your folder permissions to these Recommended folder permissions.', 'bulletproof-security').'</strong> '.__('If you see 500 errors after changing your folder permissions than change them back to what they were.','bulletproof-security').'<br><br>'.__('I recommend using FileZilla to change your file and folder permissions. FileZilla is a free FTP software that makes changing your file and folder permissions very simple and easy as well as many other very nice FTP features. With FileZilla you can right mouse click on your files or folders and set the permissions with a Numeric value like 755, 644, etc. Takes the confusion out of which attributes to check or uncheck.','bulletproof-security'); echo $text; ?></p>
</div>

</td>
    <td width="2%">&nbsp;</td>
    <td width="49%" class="bps-table_title_SS">
	
	<?php _e('General BulletProof Security File Checks', 'bulletproof-security'); ?>  <button id="bps-open-modal8" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button>
    
    <div id="bps-modal-content8" title="<?php _e('General File Checks', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br>'.__('This is a quick visual check to verify that you have active .htaccess files in your root and /wp-admin folders and that all the required BPS files are in your BulletProof Security plugin folder. The BulletProof Security .htaccess master files (default.htaccess, secure.htaccess, wpadmin-secure.htaccess, maintenance.htaccess and bp-maintenance.php) are located in this folder /', 'bulletproof-security').$bps_plugin_dir.__('/bulletproof-security/admin/htaccess/','bulletproof-security').'<br><br>'.__('For new installations and upgrades of BulletProof Security you will see red warning messages. This is completely normal. These warnings are there to remind you to perform backups if they have not been performed yet. Also you may see warning messages if files do not exist yet.','bulletproof-security').'<br><br>'.__('You can also download backups of any existing .htaccess files using the BPS File Downloader.','bulletproof-security'); echo $text; ?></p>
</div>

</td>
  </tr>
  <tr>
  	<td height="100%" class="bps-table_cell_perms_blank">
	
	<?php 
	$sapi_type = php_sapi_name();
	if ( @substr($sapi_type, 0, 6) != 'apache') {	
	
	//if (substr($sapi_type, 0, 3) == 'cgi' || substr($sapi_type, 0, 9) == 'litespeed' || substr($sapi_type, 0, 7) == 'caudium' || substr($sapi_type, 0, 8) == 'webjames' || substr($sapi_type, 0, 3) == 'tux' || substr($sapi_type, 0, 5) == 'roxen' || substr($sapi_type, 0, 6) == 'thttpd' || substr($sapi_type, 0, 6) == 'phttpd' || substr($sapi_type, 0, 10) == 'continuity' || substr($sapi_type, 0, 6) == 'pi3web' || substr($sapi_type, 0, 6) == 'milter') {
	
	echo '<div style=\'padding:5px 0px 5px 5px;\'><strong>'; _e('CGI File and Folder Permissions / Recommendations', 'bulletproof-security'); echo '</strong></div>';
	echo '<table style="width:100%;background-color:#A9F5A0;border-bottom:1px solid black;border-top:1px solid black;">';
	echo '<tr>';
	echo '<td style="padding:2px;width:35%;font-weight:bold;">'; _e('File Name', 'bulletproof-security'); echo '<br>'; _e('Folder Name', 'bulletproof-security'); echo '</td>';
    echo '<td style="padding:2px;width:35%;font-weight:bold;">'; _e('File Path', 'bulletproof-security'); echo '<br>'; _e('Folder Path', 'bulletproof-security'); echo '</td>';
    echo '<td style="padding:2px;width:15%;font-weight:bold;">'; _e('Recommended', 'bulletproof-security'); echo '<br>'; _e('Permissions', 'bulletproof-security'); echo '</td>';
    echo '<td style="padding:2px;width:15%;font-weight:bold;">'; _e('Current', 'bulletproof-security'); echo '<br>'; _e('Permissions', 'bulletproof-security'); echo '</td>';
	echo '</tr>';
    echo '</table>';

	bps_check_perms(".htaccess","../.htaccess","404");
	bps_check_perms("wp-config.php","../wp-config.php","400");
	bps_check_perms("index.php","../index.php","400");
	bps_check_perms("wp-blog-header.php","../wp-blog-header.php","400");
	bps_check_perms("root folder","../","705");
	bps_check_perms("wp-admin/","../wp-admin","705");
	bps_check_perms("wp-includes/","../wp-includes","705");
	bps_check_perms("$bps_wpcontent_dir/","../$bps_wpcontent_dir","705");
	bps_check_perms("$bps_wpcontent_dir/bps-backup/","../$bps_wpcontent_dir/bps-backup","755");
	echo '<div style=\'padding-bottom:15px;\'></div>';
	
	} else {
	
	echo '<div style=\'padding:5px 0px 5px 5px;\'><strong>'; _e('DSO File and Folder Permissions / Recommendations', 'bulletproof-security'); echo '</strong></div>';
	echo '<table style="width:100%;background-color:#A9F5A0;border-bottom:1px solid black;border-top:1px solid black;">';
	echo '<tr>';
	echo '<td style="padding:2px;width:35%;font-weight:bold;">'; _e('File Name', 'bulletproof-security'); echo '<br>'; _e('Folder Name', 'bulletproof-security'); echo '</td>';
    echo '<td style="padding:2px;width:35%;font-weight:bold;">'; _e('File Path', 'bulletproof-security'); echo '<br>'; _e('Folder Path', 'bulletproof-security'); echo '</td>';
    echo '<td style="padding:2px;width:15%;font-weight:bold;">'; _e('Recommended', 'bulletproof-security'); echo '<br>'; _e('Permissions', 'bulletproof-security'); echo '</td>';
    echo '<td style="padding:2px;width:15%;font-weight:bold;">'; _e('Current', 'bulletproof-security'); echo '<br>'; _e('Permissions', 'bulletproof-security'); echo '</td>';
	echo '</tr>';
    echo '</table>';
	
	bps_check_perms(".htaccess","../.htaccess","644");
	bps_check_perms("wp-config.php","../wp-config.php","644");
	bps_check_perms("index.php","../index.php","644");
	bps_check_perms("wp-blog-header.php","../wp-blog-header.php","644");
	bps_check_perms("root folder","../","755");
	bps_check_perms("wp-admin/","../wp-admin","755");
	bps_check_perms("wp-includes/","../wp-includes","755");
	bps_check_perms("$bps_wpcontent_dir/","../$bps_wpcontent_dir","755");
	bps_check_perms("$bps_wpcontent_dir/bps-backup/","../$bps_wpcontent_dir/bps-backup","755");
	echo '<div style=\'padding-bottom:15px;\'></div>';
	}
?>

</td>
    <td>&nbsp;</td>
    <td rowspan="4" class="bps-table_cell_file_checks">
    
	<?php echo bps_general_file_checks(); ?>
   <!--  style="margin-top:38px;" -->
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-file_checks_bottom_table" style="margin-top:6px;">
      <tr>
        <td class="bps-file_checks_bottom_bps-table_cell">&nbsp;</td>
      </tr>
    </table>
    </td>
  </tr>
 <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-file_checks_bottom_table_special">
      <tr>
        <td class="bps-file_checks_bottom_bps-table_cell">&nbsp;</td>
      </tr>
    </table>    
    </td>
    <td>&nbsp;</td>
    </tr>
</table>
<br />
<?php } ?>
</div>
            
<div id="bps-tabs-5" class="bps-tab-page">
<h2><?php _e('BulletProof Security Backup &amp; Restore', 'bulletproof-security'); ?></h2>

<?php if ( !current_user_can('manage_options') ) { _e('Permission Denied', 'bulletproof-security'); } else { ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">

<h3><?php echo '<font color="red"><strong>'; _e('CAUTION: ', 'bulletproof-security'); echo '</strong></font>'; ?>  <button id="bps-open-modal10" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button></h3>

<div id="bps-modal-content10" title="<?php _e('htaccess File Backup', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br>'.__('Back up your existing htaccess files first before activating any BulletProof Security Modes in case of a problem when you first install and activate any BulletProof Security Modes. Once you have backed up your original existing htaccess files you will see the status listed in the ','bulletproof-security').'<strong>'.__('Current Backed Up htaccess Files Status','bulletproof-security').'</strong> '.__('window below. ','bulletproof-security').'<br><br><strong>'.__('Backup files are stored in this folder /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup.','bulletproof-security').'</strong><br><br>'.__('In cases where you install a plugin that writes to your htaccess files you will want to perform another backup of your htaccess files. Each time you perform a backup you are overwriting older backed up htaccess files. Backed up files are stored in the /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-folder.','bulletproof-security').'<br><br><strong>'.__('The BPS Master htaccess files are stored in your /plugins/bulletproof-security/admin/htaccess folder and can also be backed up to the /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup/master-backups folder.','bulletproof-security').'</strong><br><br>'.__('Backed up files are stored online so they will be available to you after upgrading to a newer version of BPS if you run into a problem. There is no Restore feature for the BPS Master files because you should be using the latest versions of the BPS master htaccess files after you upgrade BPS. You can manually download the files from this folder /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup/master-backups using FTP or your web host file downloader.','bulletproof-security').'<br><br>'.__('When you upgrade BPS your currently active root and wp-admin .htaccess files are updated with any new htaccess code additions in the new version of BPS, but any htaccess customizations that you have done are not changed or overwritten. And custom htaccess code that you have added to BPS Custom Code is not changed or overwritten. BPS master htaccess files are replaced when you upgrade BPS so if you have made changes to your BPS master files that you want to keep then back them up first before upgrading.', 'bulletproof-security').'<br><br><strong>'.__('If something goes wrong in the htaccess file editing process or at any point you can restore your good htaccess files with one click as long as you already backed them up.','bulletproof-security').'</strong><br><br><strong>'.__('BPS Video Tutorial links can be found in the Help & FAQ pages.','bulletproof-security').'</strong>'; echo $text; ?></p>
</div>

<form name="BulletProof-Backup" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-5" method="post">
<?php wp_nonce_field('bulletproof_security_backup_active_htaccess_files'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection9" type="radio" value="backup_htaccess" class="tog" <?php echo checked('', $backup_htaccess); ?> />
<?php _e('Backup .htaccess Files', 'bulletproof-security'); ?></label></th>
	<td><?php $text = '<font color="green"><strong>'.__('Backs up your currently active .htaccess files in your root and /wp-admin folders.', 'bulletproof-security').'</strong></font><br><strong>'.__('Backup your htaccess files for first time installations of BPS or whenever new modifications have been made to your htaccess files. Read the ', 'bulletproof-security').'<font color="red"><strong>'.__('CAUTION: ', 'bulletproof-security').'</strong></font>'.__('Read Me button.', 'bulletproof-security').'</strong>'; echo $text; ?></td>
	<td>
    </td>
   </tr>
</table>
<p class="submit">
<input type="submit" name="submit9" class="bps-blue-button" value="<?php esc_attr_e('Backup Files', 'bulletproof-security') ?>" />
</p>
</form>

<h3><?php _e('Restore Your .htaccess Files From Backup', 'bulletproof-security'); ?>  <button id="bps-open-modal11" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button></h3>

<div id="bps-modal-content11" title="<?php _e('htaccess File Restore', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br>'.__('Restores your backed up htaccess files that you backed up. Your backed up htaccess files were renamed to root.htaccess and wpadmin.htaccess and copied to the /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup/master-backups folder. Restoring your backed up htaccess files will rename them back to htaccess and copy them back to your root and /wp-admin folders respectively.','bulletproof-security').'<br><br><strong>'.__('If you did not have any original htaccess files to begin with and / or you did not back up any files then you will not have any backed up htaccess files.','bulletproof-security').'</strong><br><br><strong>'.__('BPS Video Tutorial links can be found in the Help & FAQ pages.','bulletproof-security').'</strong>'; echo $text; ?></p>
</div>

<form name="BulletProof-Restore" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-5" method="post">
<?php wp_nonce_field('bulletproof_security_restore_active_htaccess_files'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection10" type="radio" value="restore_htaccess" class="tog" <?php checked('', $restore_htaccess); ?> />
<?php _e('Restore .htaccess Files', 'bulletproof-security'); ?></label></th>
	<td><?php $text = '<font color="green"><strong>'.__('Restores your backed up .htaccess files to your root and /wp-admin folders.', 'bulletproof-security').'</strong></font><br><strong>'.__('Restore your backed up .htaccess files if you have any problems or for use between BPS ugrades.', 'bulletproof-security').'</strong>'; echo $text; ?></td>
	<td>
    </td>
   </tr>
</table>
<p class="submit">
<input type="submit" name="submit10" class="bps-blue-button" value="<?php esc_attr_e('Restore Files', 'bulletproof-security') ?>" />
</p>
</form>

<h3><?php _e('Backup Your BPS Master .htaccess Files', 'bulletproof-security'); ?>  <button id="bps-open-modal12" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button></h3>

<div id="bps-modal-content12" title="<?php _e('Master .htaccess File Backup', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br>'.__('The BPS Master .htaccess files are stored in your /plugins/bulletproof-security/admin/htaccess folder and can also be backed up using this Master Backup feature. The backed up BPS Master .htaccess files are copied to this folder /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup/master-backups folder. This way they will be available to you online after upgrading to a newer version of BPS. There is no Restore feature for the BPS Master files because you should be using the latest versions of the BPS master .htaccess files after you upgrade BPS. You can manually download the files from this folder /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup/master-backups using FTP or your web host file downloader.','bulletproof-security'); echo $text; ?></p>
</div>

<form name="BPS-Master-Htaccess-Backup" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-5" method="post">
<?php wp_nonce_field('bulletproof_security_backup_master_htaccess_files'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection11" type="radio" value="backup_master_htaccess_files" class="tog" <?php checked('', $backup_master_htaccess_files); ?> />
<?php _e('Backup BPS Master .htaccess Files', 'bulletproof-security'); ?></label></th>
	<td><?php $text = '<font color="green"><strong>'.__('Backs up your BPS Master .htaccess files to the /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup/master-backups folder.', 'bulletproof-security').'</strong></font><br><strong>'.__('There is no Restore feature for the BPS Master .htaccess files because you should be using the latest most current BPS Master .htaccess security coding and plugin fixes included in the most current version of the BPS master .htacess files.', 'bulletproof-security').'</strong>'; echo $text; ?></td>
	<td>
    </td>
   </tr>
</table>
<p class="submit">
<input type="submit" name="submit11" class="bps-blue-button" value="<?php esc_attr_e('Backup Master Files', 'bulletproof-security') ?>" />
</p>
</form>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-backup_restore_table">
  <tr>
    <td class="bps-table_title_SS">
	
	<?php _e('Current Backed Up .htaccess Files Status', 'bulletproof-security'); ?>  <button id="bps-open-modal13" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button>
    
    <div id="bps-modal-content13" title="<?php _e('Backup .htaccess File', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br>'.__('General file checks to check which files have been backed up or not.','bulletproof-security'); echo $text; ?></p>
</div>

</td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><strong><?php bps_general_file_checks_backup_restore(); ?></strong></td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php echo bps_backup_restore_checks(); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php echo bps_master_file_backups(); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<br />
</div>
        
<div id="bps-tabs-6" class="bps-tab-page">
<table width="100%" border="0">
  <tr>
    <td width="33%"><h2><?php _e('BulletProof Security File Editing', 'bulletproof-security'); ?></h2></td>
    <td width="21%"><button id="bps-open-modal14" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button>
    
    <div id="bps-modal-content14" title="<?php _e('File Editing', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br><strong>'.__('Lock / Unlock .htaccess Files','bulletproof-security').'</strong><br>'.__('If your Server API is using CGI then you will see Lock and Unlock buttons to lock your Root htaccess file with 404 Permissions and unlock your root htaccess file with 644 Permissions. If your Server API is using CLI - DSO / Apache / mod_php then you will not see lock and unlock buttons. 644 Permissions are required to write to / edit the root htaccess file. Once you are done editing your root htaccess file use the lock button to lock it with 404 Permissions. 644 Permissions for DSO are considered secure for DSO because of the different way that file security is handled with DSO.','bulletproof-security').'<br><br>'.__('If your Root htaccess file is locked and you try to save your editing changes you will see a pop message that your Root htaccess file is locked. You will need to unlock your Root htaccess file before you can save your changes.','bulletproof-security').'<br><br><strong>'.__('Turn On AutoLock / Turn Off AutoLock','bulletproof-security').'</strong><br>'.__('AutoLock is designed to automatically lock your root .htaccess file to save you an additional step of locking your root .htaccess file when performing certain actions, tasks or functions and AutoLock also automatically locks your root .htaccess during BPS upgrades. This can be a problem for some folks whose Web Hosts do not allow locking the root .htaccess file with 404 file permissions and can cause 403 errors and/or cause a website to crash. For 99.99% of folks leaving AutoLock turned On will work fine. If your Web Host ONLY allows 644 file permissions for your root .htaccess file then click the Turn Off AutoLock button. This turns Off AutoLocking for all BPS actions, tasks, functions and also for BPS upgrades.','bulletproof-security').'<br><br><strong>'.__('The File Editor is designed to open all of your htaccess files simultaneously and allow you to copy and paste from one window (file) to another window (file), BUT you can ONLY save your edits for one file at a time. Whichever file you currently have opened (the tab that you are currently viewing) when you click the Update File button is the file that will be updated / saved.','bulletproof-security').'</strong><br><br>'.__('Help links and Video Tutorial links are provided on the Help & FAQ page ','bulletproof-security'); echo $text; ?></p>
</div>

</td>
    <td width="19%" align="right"></td>
    <td width="27%" align="center"></td>
  </tr>
</table>

<?php if ( !current_user_can('manage_options') ) { _e('Permission Denied', 'bulletproof-security'); } else { ?>

<table width="100%" border="0">
  <tr>
    <td colspan="2">
    <div id="bps_file_editor" class="bps_file_editor_update">

<?php
echo bps_secure_htaccess_file_check();
echo bps_default_htaccess_file_check();
echo bps_maintenance_htaccess_file_check();
echo bps_wpadmin_htaccess_file_check();

// Perform File Open and Write test first by appending a literal blank space
// or nothing at all to end of the htaccess files.
// If append write test is successful file is writable on submit
if (current_user_can('manage_options')) {
$secure_htaccess_file = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/secure.htaccess';
$write_test = "";
	
	if (is_writable($secure_htaccess_file)) {
    if (!$handle = fopen($secure_htaccess_file, 'a+b')) {
    	exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    	exit;
    }
		$text = '<strong>'.__('File Open and Write test successful! The secure.htaccess file is writable.', 'bulletproof-security').'</strong><br>';
		echo $text;
	} else {
	
	if (file_exists($secure_htaccess_file)) {
		$text = '<font color="blue"><strong>'.__('Cannot write to file: ', 'bulletproof-security').$secure_htaccess_file.'</strong></font><br>';
		echo $text;
	}
	}
	}
	
	if (isset($_POST['submit1']) && current_user_can('manage_options')) {
		check_admin_referer( 'bulletproof_security_save_settings_1' );
		$newcontent1 = stripslashes($_POST['newcontent1']);
	
	if ( is_writable($secure_htaccess_file) ) {
		$handle = fopen($secure_htaccess_file, 'w+b');
		fwrite($handle, $newcontent1);
		$text = '<font color="green"><strong>'.__('Success! The secure.htaccess file has been updated.', 'bulletproof-security').'</strong></font><br>';
		echo $text;
    fclose($handle);
	}
}

if (current_user_can('manage_options')) {
$default_htaccess_file = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/default.htaccess';
$write_test = "";
	
	if (is_writable($default_htaccess_file)) {
    if (!$handle = fopen($default_htaccess_file, 'a+b')) {
    	exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    	exit;
    }
		$text = '<strong>'.__('File Open and Write test successful! The default.htaccess file is writable.', 'bulletproof-security').'</strong><br>';
		echo $text;
	} else {
	
	if (file_exists($default_htaccess_file)) {
		$text = '<font color="blue"><strong>'.__('Cannot write to file: ', 'bulletproof-security').$default_htaccess_file.'</strong></font><br>';
		echo $text;
	}
	}
	}
	
	if (isset($_POST['submit2']) && current_user_can('manage_options')) {
		check_admin_referer( 'bulletproof_security_save_settings_2' );
		$newcontent2 = stripslashes($_POST['newcontent2']);
	
	if ( is_writable($default_htaccess_file) ) {
		$handle = fopen($default_htaccess_file, 'w+b');
		fwrite($handle, $newcontent2);
		$text = '<font color="green"><strong>'.__('Success! The default.htaccess file has been updated.', 'bulletproof-security').'</strong></font><br>';
		echo $text;
    fclose($handle);
	}
}

if (current_user_can('manage_options')) {
$maintenance_htaccess_file = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/maintenance.htaccess';
$write_test = "";
	
	if (is_writable($maintenance_htaccess_file)) {
    if (!$handle = fopen($maintenance_htaccess_file, 'a+b')) {
    	exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    	exit;
    }
		$text = '<strong>'.__('File Open and Write test successful! The maintenance.htaccess file is writable.', 'bulletproof-security').'</strong><br>';
		echo $text;
	} else {
	
	if (file_exists($maintenance_htaccess_file)) {
		$text = '<font color="blue"><strong>'.__('Cannot write to file: ', 'bulletproof-security').$maintenance_htaccess_file.'</strong></font><br>';
		echo $text;
	}
	}
	}
	
	if (isset($_POST['submit3']) && current_user_can('manage_options')) {
		check_admin_referer( 'bulletproof_security_save_settings_3' );
		$newcontent3 = stripslashes($_POST['newcontent3']);
	
	if ( is_writable($maintenance_htaccess_file) ) {
		$handle = fopen($maintenance_htaccess_file, 'w+b');
		fwrite($handle, $newcontent3);
		$text = '<font color="green"><strong>'.__('Success! The maintenance.htaccess file has been updated.', 'bulletproof-security').'</strong></font><br>';
		echo $text;	
    fclose($handle);
	}
}

if (current_user_can('manage_options')) {
$wpadmin_htaccess_file = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/wpadmin-secure.htaccess';
$write_test = "";
	
	if (is_writable($wpadmin_htaccess_file)) {
    if (!$handle = fopen($wpadmin_htaccess_file, 'a+b')) {
	    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
	    exit;
    }
		$text = '<strong>'.__('File Open and Write test successful! The wpadmin-secure.htaccess file is writable.', 'bulletproof-security').'</strong><br>';
		echo $text;
	} else {
	
	if (file_exists($wpadmin_htaccess_file)) {
		$text = '<font color="blue"><strong>'.__('Cannot write to file: ', 'bulletproof-security').$wpadmin_htaccess_file.'</strong></font><br>';
		echo $text;
	}
	}
	}
	
	if (isset($_POST['submit4']) && current_user_can('manage_options')) {
		check_admin_referer( 'bulletproof_security_save_settings_4' );
		$newcontent4 = stripslashes($_POST['newcontent4']);
	
	if ( is_writable($wpadmin_htaccess_file) ) {
		$handle = fopen($wpadmin_htaccess_file, 'w+b');
		fwrite($handle, $newcontent4);
		$text = '<font color="green"><strong>'.__('Success! The wpadmin-secure.htaccess file has been updated.', 'bulletproof-security').'</strong></font><br>';
		echo $text;
    fclose($handle);
	}
}

if ( current_user_can('manage_options') ) {
$root_htaccess_file = ABSPATH . '.htaccess';
$write_test = "";
	
	if (is_writable($root_htaccess_file)) {
    if (!$handle = fopen($root_htaccess_file, 'a+b')) {
	    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
	    exit;
    }
		$text = '<strong>'.__('File Open and Write test successful! Your currently active root .htaccess file is writable.', 'bulletproof-security').'</strong><br>';
		echo $text;
	} else {
	
	if (file_exists($root_htaccess_file)) {
		$text = '<font color="blue"><strong>'.__('Your root .htaccess file is Locked with Read Only Permissions.', 'bulletproof-security').'<br>'.__('Use the Lock and Unlock buttons below to Lock or Unlock your root .htaccess file for editing.', 'bulletproof-security').'</strong></font><br>';
		echo $text;
	} else {
		$text = '<font color="black"><strong>'.__('Cannot write to file: ', 'bulletproof-security').$root_htaccess_file.'</strong></font><br>';
		echo $text;
	}
	}
	}
	
	if (isset($_POST['submit5']) && current_user_can('manage_options')) {
		check_admin_referer( 'bulletproof_security_save_settings_5' );
		$newcontent5 = stripslashes($_POST['newcontent5']);
	
	if ( !is_writable($root_htaccess_file) ) {
		$text = '<font color="red"><strong>'.__('Error: Unable to write to the Root .htaccess file. If your Root .htaccess file is locked you must unlock first.', 'bulletproof-security').'</strong></font><br>';
		echo $text;
	}	
	
	if ( is_writable($root_htaccess_file) ) {
		$handle = fopen($root_htaccess_file, 'w+b');
		fwrite($handle, $newcontent5);
		$text = '<font color="green"><strong>'.__('Success! Your currently active root .htaccess file has been updated.', 'bulletproof-security').'</strong></font><br>';
		echo $text;
    fclose($handle);
	}
}

if ( current_user_can('manage_options') ) {
$current_wpadmin_htaccess_file = ABSPATH . 'wp-admin/.htaccess';
$write_test = "";
	
	if (is_writable($current_wpadmin_htaccess_file)) {
    if (!$handle = fopen($current_wpadmin_htaccess_file, 'a+b')) {
	    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
	    exit;
    }
		$text = '<strong>'.__('File Open and Write test successful! Your currently active wp-admin .htaccess file is writable.', 'bulletproof-security').'</strong><br>';
		echo $text;
	} else {
	
	if (file_exists($current_wpadmin_htaccess_file)) {
		$text = '<font color="blue"><strong>'.__('Cannot write to file: ', 'bulletproof-security').$current_wpadmin_htaccess_file.'</strong></font><br>';
		echo $text;
	}
	}
	}
	
	if (isset($_POST['submit6']) && current_user_can('manage_options')) {
		check_admin_referer( 'bulletproof_security_save_settings_6' );
		$newcontent6 = stripslashes($_POST['newcontent6']);
	
	if ( is_writable($current_wpadmin_htaccess_file) ) {
		$handle = fopen($current_wpadmin_htaccess_file, 'w+b');
		fwrite($handle, $newcontent6);
		$text = '<font color="green"><strong>'.__('Success! Your currently active wp-admin .htaccess file has been updated.', 'bulletproof-security').'</strong></font><br>';
		echo $text;
    fclose($handle);
	}
}

// BPS Pro Only - Lock and Unlock Root .htaccess file 
if (isset($_POST['submit-ProFlockLock']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_flock_lock' );
$bpsRootHtaccessOL = ABSPATH . '.htaccess';
	
	if (file_exists($bpsRootHtaccessOL)) {
		chmod($bpsRootHtaccessOL, 0404);
		$text = '<font color="blue"><strong><br>'.__('Your Root .htaccess file has been Locked.', 'bulletproof-security').'</strong></font><br>';
		echo $text;
	} else {
		$text = '<font color="red"><strong><br>'.__('Unable to Lock your Root .htaccess file.', 'bulletproof-security').'</strong></font><br>';
		echo $text;
	}
}
	
if (isset($_POST['submit-ProFlockUnLock']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_flock_unlock' );
$bpsRootHtaccessOL = ABSPATH . '.htaccess';
		
	if (file_exists($bpsRootHtaccessOL)) {
		chmod($bpsRootHtaccessOL, 0644);
		$text = '<font color="blue"><strong><br>'.__('Your Root .htaccess file has been Unlocked.', 'bulletproof-security').'</strong></font><br>';
		echo $text;
	} else {
		$text = '<font color="red"><strong><br>'.__('Unable to Unlock your Root .htaccess file.', 'bulletproof-security').'</strong></font><br>';
		echo $text;
	}
}
?>
</div>
</td>
<td width="33%" align="center" valign="top"></td>
  </tr>
  <tr>
    <td width="22%">
<?php // Detect the SAPI - display form submit button only if sapi is cgi
	$sapi_type = php_sapi_name();
	if ( @substr($sapi_type, 0, 6) != 'apache') {	
?>    
 
 	<div style="margin: 5px;">  
    <form name="bpsFlockLockForm" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-6" method="post">
<?php wp_nonce_field('bulletproof_security_flock_lock'); ?>
	<input type="submit" name="submit-ProFlockLock" value="<?php _e('Lock htaccess File', 'bulletproof-security'); ?>" class="bps-blue-button" onClick="return confirm('<?php $text = __('Click OK to Lock your Root htaccess file or click Cancel.', 'bulletproof-security').'\n\n'.$bpsSpacePop.'\n\n'.__('Note: The File Open and Write Test window will still display the last status of the file as Unlocked. To see the current status refresh your browser.', 'bulletproof-security'); echo $text; ?>')" />
</form>
<br />
	
    <form name="bpsRootAutoLock-On" action="options.php#bps-tabs-6" method="post">
    <?php settings_fields('bulletproof_security_options_autolock'); ?>
	<?php $options = get_option('bulletproof_security_options_autolock'); ?>
<input type="hidden" name="bulletproof_security_options_autolock[bps_root_htaccess_autolock]" value="On" />
<input type="submit" name="submit-RootHtaccessAutoLock-On" value="<?php _e('Turn On AutoLock', 'bulletproof-security'); ?>" class="bps-blue-button" onClick="return confirm('<?php $text = __('Turning AutoLock On will allow BPS Pro to automatically lock your Root .htaccess file. For some folks this causes a problem because their Web Hosts do not allow the Root .htaccess file to be locked. For most folks allowing BPS Pro to AutoLock the Root .htaccess file works fine.', 'bulletproof-security').'\n\n'.$bpsSpacePop.'\n\n'.__('Click OK to Turn AutoLock On or click Cancel.', 'bulletproof-security'); echo $text; ?>')" /><?php if ($options['bps_root_htaccess_autolock'] == '' || $options['bps_root_htaccess_autolock'] == 'On') { $text = '<font color="blue" style="font-size:14px;border:2px solid gray;padding:2px;margin-left:5px;background-color:#5cf1f9;position:relative;top:1px;"><strong>'.__('On', 'bulletproof-security').'</strong></font>'; echo $text; } ?>
</form>
</div>

<?php } else { echo ''; } ?>

</td>
    <td width="45%">

<?php // Detect the SAPI - display form submit button only if sapi is cgi
	$sapi_type = php_sapi_name();
	if ( @substr($sapi_type, 0, 6) != 'apache') {	
?>        

	<div style="margin: 5px;">    
    <form name="bpsFlockUnLockForm" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-6" method="post">
<?php wp_nonce_field('bulletproof_security_flock_unlock'); ?>

	<input type="submit" name="submit-ProFlockUnLock" value="<?php _e('Unlock htaccess File', 'bulletproof-security'); ?>" class="bps-blue-button" onClick="return confirm('<?php $text = __('Click OK to Unlock your Root htaccess file or click Cancel.', 'bulletproof-security').'\n\n'.$bpsSpacePop.'\n\n'.__('Note: The File Open and Write Test window will still display the last status of the file as Locked. To see the current status refresh your browser.', 'bulletproof-security'); echo $text; ?>')" />
</form>
<br />
    
    <form name="bpsRootAutoLock-Off" action="options.php#bps-tabs-6" method="post">
    <?php settings_fields('bulletproof_security_options_autolock'); ?>
	<?php $options = get_option('bulletproof_security_options_autolock'); ?>
<input type="hidden" name="bulletproof_security_options_autolock[bps_root_htaccess_autolock]" value="Off" />
<input type="submit" name="submit-RootHtaccessAutoLock-Off" value="<?php _e('Turn Off AutoLock', 'bulletproof-security'); ?>" class="bps-blue-button" onClick="return confirm('<?php $text = __('Turning AutoLock Off will prevent BPS Pro from automatically locking your Root .htaccess file. For some folks this is necessary because their Web Hosts do not allow the Root .htaccess file to be locked. For most folks allowing BPS Pro to AutoLock the Root .htaccess file works fine.', 'bulletproof-security').'\n\n'.$bpsSpacePop.'\n\n'.__('Click OK to Turn AutoLock Off or click Cancel.', 'bulletproof-security'); echo $text; ?>')" /><?php if ($options['bps_root_htaccess_autolock'] == 'Off') { $text = '<font color="blue" style="font-size:14px;border:2px solid gray;padding:2px;margin-left:5px;background-color:#5cf1f9;position:relative;top:1px;"><strong>'.__('Off', 'bulletproof-security').'</strong></font>'; echo $text; } ?>
</form>
</div>

<?php } else { echo ''; } ?>

</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
    
    <!-- jQuery UI File Editor Tab Menu -->
<div id="bps-edittabs" class="bps-edittabs-class">
		<ul>
			<li><a href="#bps-edittabs-1"><?php _e('secure.htaccess', 'bulletproof-security'); ?></a></li>
			<li><a href="#bps-edittabs-2"><?php _e('default.htaccess', 'bulletproof-security'); ?></a></li>
			<li><a href="#bps-edittabs-3"><?php _e('maintenance.htaccess', 'bulletproof-security'); ?></a></li>
			<li><a href="#bps-edittabs-4"><?php _e('wpadmin-secure.htaccess', 'bulletproof-security'); ?></a></li>
            <li><a href="#bps-edittabs-5"><?php _e('Your Current Root htaccess File', 'bulletproof-security'); ?></a></li>
            <li><a href="#bps-edittabs-6"><?php _e('Your Current wp-admin htaccess File', 'bulletproof-security'); ?></a></li>
        </ul>
       
<?php 
$scrollto1 = isset($_REQUEST['scrollto1']) ? (int) $_REQUEST['scrollto1'] : 0; 
$scrollto2 = isset($_REQUEST['scrollto2']) ? (int) $_REQUEST['scrollto2'] : 0;
$scrollto3 = isset($_REQUEST['scrollto3']) ? (int) $_REQUEST['scrollto3'] : 0;
$scrollto4 = isset($_REQUEST['scrollto4']) ? (int) $_REQUEST['scrollto4'] : 0;
$scrollto5 = isset($_REQUEST['scrollto5']) ? (int) $_REQUEST['scrollto5'] : 0;
$scrollto6 = isset($_REQUEST['scrollto6']) ? (int) $_REQUEST['scrollto6'] : 0;
?>

<div id="bps-edittabs-1" class="bps-edittabs-page-class">
<form name="template1" id="template1" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-6" method="post">
<?php wp_nonce_field('bulletproof_security_save_settings_1'); ?>
    <div>
    <textarea class="bps-text-area-600x700" name="newcontent1" id="newcontent1" tabindex="1"><?php echo bps_get_secure_htaccess(); ?></textarea>
	<input type="hidden" name="action" value="update" />
    <input type="hidden" name="filename" value="<?php echo esc_attr($secure_htaccess_file) ?>" />
	<input type="hidden" name="scrollto1" id="scrollto1" value="<?php echo $scrollto1; ?>" />
    <p class="submit">
	<input type="submit" name="submit1" class="bps-blue-button" value="<?php esc_attr_e('Update File', 'bulletproof-security') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#template1').submit(function(){ $('#scrollto1').val( $('#newcontent1').scrollTop() ); });
	$('#newcontent1').scrollTop( $('#scrollto1').val() ); 
});
/* ]]> */
</script>     
</div>

<div id="bps-edittabs-2" class="bps-edittabs-page-class">
<form name="template2" id="template2" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-6" method="post">
<?php wp_nonce_field('bulletproof_security_save_settings_2'); ?>
	<div>
    <textarea class="bps-text-area-600x700" name="newcontent2" id="newcontent2" tabindex="2"><?php echo bps_get_default_htaccess(); ?></textarea>
	<input type="hidden" name="action" value="update" />
    <input type="hidden" name="filename" value="<?php echo esc_attr($default_htaccess_file) ?>" />
	<input type="hidden" name="scrollto2" id="scrollto2" value="<?php echo $scrollto2; ?>" />
    <p class="submit">
	<input type="submit" name="submit2" class="bps-blue-button" value="<?php esc_attr_e('Update File', 'bulletproof-security') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#template2').submit(function(){ $('#scrollto2').val( $('#newcontent2').scrollTop() ); });
	$('#newcontent2').scrollTop( $('#scrollto2').val() );
});
/* ]]> */
</script>     
</div>

<div id="bps-edittabs-3" class="bps-edittabs-page-class">
<form name="template3" id="template3" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-6" method="post">
<?php wp_nonce_field('bulletproof_security_save_settings_3'); ?>
	<div>
    <textarea class="bps-text-area-600x700" name="newcontent3" id="newcontent3" tabindex="3"><?php echo bps_get_maintenance_htaccess(); ?></textarea>
	<input type="hidden" name="action" value="update" />
    <input type="hidden" name="filename" value="<?php echo esc_attr($maintenance_htaccess_file) ?>" />
	<input type="hidden" name="scrollto3" id="scrollto3" value="<?php echo $scrollto3; ?>" />
    <p class="submit">
	<input type="submit" name="submit3" class="bps-blue-button" value="<?php esc_attr_e('Update File', 'bulletproof-security') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#template3').submit(function(){ $('#scrollto3').val( $('#newcontent3').scrollTop() ); });
	$('#newcontent3').scrollTop( $('#scrollto3').val() );
});
/* ]]> */
</script>     
</div>

<div id="bps-edittabs-4" class="bps-edittabs-page-class">
<form name="template4" id="template4" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-6" method="post">
<?php wp_nonce_field('bulletproof_security_save_settings_4'); ?>
	<div>
    <textarea class="bps-text-area-600x700" name="newcontent4" id="newcontent4" tabindex="4"><?php echo bps_get_wpadmin_htaccess(); ?></textarea>
	<input type="hidden" name="action" value="update" />
    <input type="hidden" name="filename" value="<?php echo esc_attr($wpadmin_htaccess_file) ?>" />
	<input type="hidden" name="scrollto4" id="scrollto4" value="<?php echo $scrollto4; ?>" />
    <p class="submit">
	<input type="submit" name="submit4" class="bps-blue-button" value="<?php esc_attr_e('Update File', 'bulletproof-security') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#template4').submit(function(){ $('#scrollto4').val( $('#newcontent4').scrollTop() ); });
	$('#newcontent4').scrollTop( $('#scrollto4').val() );
});
/* ]]> */
</script>     
</div>

<?php
// File Editor Root .htaccess file Lock check with pop up Confirm message
function bpsStatusRHE() {
clearstatcache();
$file = ABSPATH . '.htaccess';
$perms = @substr(sprintf('%o', fileperms($file)), -4);
$sapi_type = php_sapi_name();
	
	if ( file_exists($file) && @substr($sapi_type, 0, 6) != 'apache') {		
	return $perms;
	}
}
?>

<div id="bps-edittabs-5" class="bps-edittabs-page-class">
<form name="template5" id="template5" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-6" method="post">
<?php wp_nonce_field('bulletproof_security_save_settings_5'); ?>
	<div>
    <textarea class="bps-text-area-600x700" name="newcontent5" id="newcontent5" tabindex="5"><?php echo bps_get_root_htaccess(); ?></textarea>
	<input type="hidden" name="action" value="update" />
    <input type="hidden" name="filename" value="<?php echo esc_attr($root_htaccess_file) ?>" />
	<input type="hidden" name="scrollto5" id="scrollto5" value="<?php echo $scrollto5; ?>" />
    <p class="submit">
    <?php if (@bpsStatusRHE($perms) == '0404') { ?>
	<input type="submit" name="submit5" value="<?php esc_attr_e('Update File', 'bulletproof-security') ?>" class="bps-blue-button" onClick="return confirm('<?php $text = __('YOUR ROOT HTACCESS FILE IS LOCKED.', 'bulletproof-security').'\n\n'.__('YOUR FILE EDITS / CHANGES CANNOT BE SAVED.', 'bulletproof-security').'\n\n'.__('Click Cancel, copy the file editing changes you made to save them and then click the Unlock .htaccess File button to unlock your Root .htaccess file. After your Root .htaccess file is unlocked paste your file editing changes back into your Root .htaccess file and click this Update File button again to save your file edits / changes.', 'bulletproof-security'); echo $text; ?>')" />
	<?php } else { ?>
	<input type="submit" name="submit5" class="bps-blue-button" value="<?php esc_attr_e('Update File', 'bulletproof-security') ?>" /></p>
<?php } ?>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#template5').submit(function(){ $('#scrollto5').val( $('#newcontent5').scrollTop() ); });
	$('#newcontent5').scrollTop( $('#scrollto5').val() );
});
/* ]]> */
</script>     
</div>

<div id="bps-edittabs-6" class="bps-edittabs-page-class">
<form name="template6" id="template6" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-6" method="post">
<?php wp_nonce_field('bulletproof_security_save_settings_6'); ?>
	<div>
    <textarea class="bps-text-area-600x700" name="newcontent6" id="newcontent6" tabindex="6"><?php echo bps_get_current_wpadmin_htaccess_file(); ?></textarea>
	<input type="hidden" name="action" value="update" />
    <input type="hidden" name="filename" value="<?php echo esc_attr($current_wpadmin_htaccess_file) ?>" />
	<input type="hidden" name="scrollto6" id="scrollto6" value="<?php echo $scrollto6; ?>" />
    <p class="submit">
	<input type="submit" name="submit6" class="bps-blue-button" value="<?php esc_attr_e('Update File', 'bulletproof-security') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#template6').submit(function(){ $('#scrollto6').val( $('#newcontent6').scrollTop() ); });
	$('#newcontent6').scrollTop( $('#scrollto6').val() );
});
/* ]]> */
</script>     
</div>
</div>
</td>
  </tr>
</table>
<?php } ?>
</div>

<div id="bps-tabs-7" class="bps-tab-page">
<h2><?php _e('Custom Code', 'bulletproof-security'); ?></h2>
<div id="bpsCustomCode" style="border-top:1px solid #999999;">

<h3><?php _e('Add Custom htaccess Code To Root and wp-admin htaccess Files', 'bulletproof-security'); ?>  <button id="bps-open-modal16" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button></h3>

<div id="bps-modal-content16" title="<?php _e('Custom Code', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br><strong>'.__('IMPORTANT!!! Custom Code General Help Information','bulletproof-security').'</strong><br><br>'.__('ONLY add valid htaccess code into these text areas/text boxes. If you want to add regular text instead of .htaccess code then you will need to add a pound sign # in front of the text to comment it out. If you do not do this then the next time you use AutoMagic and activate BulletProof Mode for your Root folder or your wp-admin folder your website WILL crash.','bulletproof-security').'<br><br>'.__('For Custom Code text areas/ text boxes the require that you copy the entire section of code that you want to edit and modify you will see this blue help text - ', 'bulletproof-security').'<strong><font color="blue">'.__('"You MUST copy and paste the entire xxxxx section of code from your root .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes."', 'bulletproof-security').'</font></strong><br><br>'.__('If you do not copy the entire section of code into a text area/text box that requires this then the next time you use AutoMagic and activate BulletProof Mode for your Root folder or your wp-admin folder your website WILL crash.','bulletproof-security').'<br><br><strong>'.__('If your website crashes: FTP to your website and delete the root .htaccess file or the wp-admin file or both files if necessary. Log back into your website and correct/fix the invalid/incorrect .htaccess code that was added in any of the Custom Code text areas/text boxes, save your changes, click the AutoMagic buttons on the Security Modes page and activate BulletProof Modes again.','bulletproof-security').'</strong><br><br>'.__('Your Custom Code is saved permanently to your WordPress Database until you delete it and will not be removed or deleted when you upgrade BPS.','bulletproof-security').'<br><br><strong>'.__('Root htaccess File Custom Code Setup Steps','bulletproof-security').'</strong><br>'.__('1. Enter your custom code in the appropriate Custom Code text box.', 'bulletproof-security').'<br>'.__('2. Click the Save Root Custom Code button to save your custom code.', 'bulletproof-security').'<br>'.__('3. Go to the Security Modes page and click the AutoMagic buttons.', 'bulletproof-security').'<br>'.__('4. Activate BulletProof Mode for your Root folder.','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE TOP PHP/PHP.INI HANDLER/CACHE CODE: Add php.ini handler and/or plugin cache code here','bulletproof-security').'</strong><br>'.__('ONLY add valid htaccess code below or text commented out with a pound sign # The CUSTOM CODE TOP text area should ONLY be used for php/php.ini handler code and/or plugin cache code.','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE DIRECTORY LISTING/DIRECTORY INDEX:','bulletproof-security').'</strong><br>'.__('You MUST copy and paste the entire Show Directory Listing and Directory Index section of code from your root .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes. If you do not copy the entire section of code into a text area/text box that requires this then the next time you use AutoMagic and activate BulletProof Mode for your Root folder your website WILL crash.','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE BRUTE FORCE LOGIN PAGE PROTECTION:','bulletproof-security').'</strong><br>'.__('This Custom Code text box is for optional/Bonus code. To get this code go to this link: http://forum.ait-pro.com/forums/topic/protect-login-page-from-brute-force-login-attacks/. CAUTION! This code has a 95%/5% success/fail ratio meaning that this code will not work on 5% of websites. If you see a 403 error when logging out and logging into your website then you cannot use this code on your website and will need to delete this code to correct the 403 error when logging out and logging into your website.','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE ERROR LOGGING AND TRACKING: Add/Modify Error logging code here','bulletproof-security').'</strong><br>'.__('You MUST copy and paste the entire Error Logging section of code from your root .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes. If you do not copy the entire section of code into a text area/text box that requires this then the next time you use AutoMagic and activate BulletProof Mode for your Root folder your website WILL crash.','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE WP-ADMIN/INCLUDES: DO NOT add wp-admin .htaccess code here','bulletproof-security').'</strong><br>'.__('Add one pound sign # below to remove the WP-ADMIN/INCLUDES section of code from your root .htaccess file. If you do not want to use the wp-admin/includes section of code in your root .htaccess file you can prevent this code from being created in your root .htaccess file by adding a pound sign # in this text area/text box.','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE WP REWRITE LOOP START: Add www to non-www/non-www to www code here','bulletproof-security').'</strong><br>'.__('You MUST copy and paste the entire Timthumb section of code from your root .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes. If you do not copy the entire section of code into a text area/text box that requires this then the next time you use AutoMagic and activate BulletProof Mode for your Root folder your website WILL crash.','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE REQUEST METHODS FILTERED: Whitelist User Agents or remove HEAD here','bulletproof-security').'</strong><br>'.__('You MUST copy and paste the entire Request Methods Filtered section of code from your root .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes. If you do not copy the entire section of code into a text area/text box that requires this then the next time you use AutoMagic and activate BulletProof Mode for your Root folder your website WILL crash.','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE PLUGIN/THEME SKIP/BYPASS RULES: Add personal plugin/theme skip/bypass rules here','bulletproof-security').'</strong><br>'.__('ONLY add valid htaccess code below or text commented out with a pound sign # This text area is for plugin fixes that are specific to your website. BPS already has some plugin fixes included in the Root htaccess file. Adding additional plugin fixes for your personal plugins on your website goes in this text area. For each plugin fix that you add above RewriteRule . - [S=12] you will need to increase the S= number by one. For Example: if you added 2 plugin fixes above the Adminer plugin fix they would be htaccess Skip rules #13 and #14 - RewriteRule . - [S=13] and RewriteRule . - [S=14]. If you added a third Skip rule above #13 and #14 it would be Skip rule #15 - RewriteRule . - [S=15].','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE TIMTHUMB FORBID RFI and MISC FILE SKIP/BYPASS RULE: Add additional Referers and/or misc file names','bulletproof-security').'</strong><br>'.__('You MUST copy and paste the entire Timthumb section of code from your root .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes. If you do not copy the entire section of code into a text area/text box that requires this then the next time you use AutoMagic and activate BulletProof Mode for your Root folder your website WILL crash.','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE BPSQSE BPS QUERY STRING EXPLOITS: Modify Query String Exploit code here ','bulletproof-security').'</strong><br>'.__('You MUST copy and paste the entire BPSQSE QUERY STRING EXPLOITS section of code from your root .htaccess file from # BEGIN BPSQSE BPS QUERY STRING EXPLOITS to # END BPSQSE BPS QUERY STRING EXPLOITS into this text box first. You can then edit and modify the code in this text window and save your changes. If you do not copy the entire section of code into a text area/text box that requires this then the next time you use AutoMagic and activate BulletProof Mode for your Root folder your website WILL crash.','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE DENY BROWSER ACCESS TO THESE FILES: Add or remove files that you want to block or allow access to here','bulletproof-security').'</strong><br>'.__('You MUST copy and paste the entire Deny Browser Access section of code from your root .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes. If you do not copy the entire section of code into a text area/text box that requires this then the next time you use AutoMagic and activate BulletProof Mode for your Root folder your website WILL crash.','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE BOTTOM HOTLINKING/FORBID COMMENT SPAMMERS/BLOCK BOTS/BLOCK IP/REDIRECT CODE: Add miscellaneous code here','bulletproof-security').'</strong><br>'.__('ONLY add valid htaccess code below or text commented out with a pound sign # You can save any miscellaneous custom htaccess code here as long as it is valid htaccess code or if it is just plain text then you will need to comment it out with a pound sign # in front of the text.','bulletproof-security').'<br><br><strong>'.__('wp-admin htaccess File Custom Code','bulletproof-security').'</strong><br>'.__('The wp-admin htaccess File Custom Code feature works differently then the Root htaccess Custom Code feature. The wp-admin htaccess file does not use AutoMagic and your Custom Code is written directly to your wp-admin htaccess file when you Activate BulletProof Mode for your wp-admin folder.','bulletproof-security').'<br><br><strong>'.__('wp-admin htaccess File Custom Code Steps','bulletproof-security').'</strong><br>'.__('1. Enter your custom code in the appropriate Custom Code text box.', 'bulletproof-security').'<br>'.__('2. Click the Save wp-admin Custom Code button to save your custom code.', 'bulletproof-security').'<br>'.__('3. Go to the Security Modes page and activate BulletProof Mode for your wp-admin folder.', 'bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE BPS WPADMIN DENY ACCESS TO FILES: Add additional wp-admin files that you would like to block here','bulletproof-security').'</strong><br>'.__('You MUST copy and paste the entire BPS WPADMIN DENY ACCESS TO FILES section of code from your wp-admin .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes. If you do not copy the entire section of code into a text area/text box that requires this then the next time you activate wp-admin BulletProof Mode for your wp-admin folder your website WILL crash.','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE WPADMIN TOP: Add wp-admin password protection, IP whitelist allow access & miscellaneous custom code here','bulletproof-security').'</strong><br>'.__('ONLY add valid htaccess code below or text commented out with a pound sign # You can save any miscellaneous custom htaccess code here as long as it is valid htaccess code or if it is just plain text then you will need to comment it out with a pound sign # in front of the text.','bulletproof-security').__('CUSTOM CODE WPADMIN PLUGIN FIXES: Add ONLY wp-admin personal plugin fixes code here','bulletproof-security').'</strong><br>'.__('ONLY add valid htaccess code below or text commented out with a pound sign # There is currently one skip rule in the wp-admin htaccess file - the WP Press This skip rule - RewriteRule . - [S=1]. For each plugin fix / skip rule that you add above RewriteRule . - [S=1] you will need to increase the S= number by one. For Example: if you added 2 wp-admin plugin fixes above the - WP Press This skip rule - they would be htaccess Skip rules #2 and #3 - RewriteRule . - [S=2] and RewriteRule . - [S=3]. If you added a third Skip rule above #2 and #3 it would be Skip rule #4 - RewriteRule . - [S=4].','bulletproof-security').'<br><br><strong>'.__('CUSTOM CODE BPSQSE-check BPS QUERY STRING EXPLOITS AND FILTERS: Modify wp-admin Query String Exploit code here','bulletproof-security').'</strong><br>'.__('You MUST copy and paste the entire BPS QUERY STRING EXPLOITS section of code from your wp-admin .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes. If you do not copy the entire section of code into a text area/text box that requires this then the next time you activate wp-admin BulletProof Mode for your wp-admin folder your website WILL crash.','bulletproof-security').'<br><br><strong>'.__('BPS Video Tutorial links can be found in the Help & FAQ pages.','bulletproof-security').'</strong>'; echo $text; ?></p>
</div>

<h3><?php $text = '<strong><a href="http://forum.ait-pro.com/video-tutorials/" target="_blank" title="">'.__('Custom Code Video Tutorial', 'bulletproof-security').'</a></strong>'; echo $text; ?></h3>
<h3><?php $text = '<strong><a href="http://forum.ait-pro.com/read-me-first/" target="_blank" title="">'.__('BulletProof Security Forum', 'bulletproof-security').'</a></strong>'; echo $text; ?></h3>

<?php 
if ( !current_user_can('manage_options') ) { _e('Permission Denied', 'bulletproof-security'); } else {
	$scrolltoCCode = isset($_REQUEST['scrolltoCCode']) ? (int) $_REQUEST['scrolltoCCode'] : 0; 
	$scrolltoCCodeWPA = isset($_REQUEST['scrolltoCCodeWPA']) ? (int) $_REQUEST['scrolltoCCodeWPA'] : 0; 

// Custom Code Check BPS Query String DB option for invalid code
function bps_CustomCode_BPSQSE_check() {
global $bps_topDiv, $bps_bottomDiv;
$options = get_option('bulletproof_security_options_customcode');	
$subject = $options['bps_customcode_bpsqse'];
$pattern = '/RewriteCond\s%{REQUEST_FILENAME}\s!-f\s*RewriteCond\s%{REQUEST_FILENAME}\s!-d\s*RewriteRule\s\.(.*)\/index\.php\s\[L\]/';

	if ( preg_match($pattern, $subject, $matches) ) {
 		echo $bps_topDiv;
		$text = '<strong><font color="red">'.__('The BPS Query String Exploits Custom Code below is NOT valid.', 'bulletproof-security').'</font><br>'.__('Delete the code shown below from the CUSTOM CODE BPSQSE BPS QUERY STRING EXPLOITS: text box and click the Save Root Custom Code button again.', 'bulletproof-security').'</strong><br>';
 		echo $text;
		echo '<pre>';
 		print_r($matches[0]);
 		echo '</pre>';
		echo $bps_bottomDiv;
	}
}
bps_CustomCode_BPSQSE_check();
?>
        
<div id="bps-accordion-2" class="bps-accordian-main-2">
    <h3><?php _e('Root htaccess File Custom Code', 'bulletproof-security'); ?></h3>
<div style="margin:0px 0px 0px -25px;">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td colspan="2" class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td width="50%" class="bps-table_cell_help">    

<form name="bpsCustomCodeForm" action="options.php#bps-tabs-7" method="post">
	<?php settings_fields('bulletproof_security_options_customcode'); ?>
	<?php $options = get_option('bulletproof_security_options_customcode'); ?>
	<div>
	<strong><label for="bps-CCode"><?php _e('CUSTOM CODE TOP PHP/PHP.INI HANDLER/CACHE CODE:<br>Add php.ini handler and/or plugin cache code here', 'bulletproof-security'); ?> </label></strong><br />
<strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('ONLY add valid htaccess code below or text commented out with a pound sign #', 'bulletproof-security').'</font>'; echo $text ; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode[bps_customcode_one]" tabindex="1"><?php echo $options['bps_customcode_one']; ?></textarea><br /><br />
	<strong><label for="bps-CCode"><?php _e('CUSTOM CODE DO NOT SHOW DIRECTORY LISTING/DIRECTORY INDEX:', 'bulletproof-security'); ?> </label></strong><br />
<strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('You MUST copy and paste the entire DO NOT SHOW DIRECTORY LISTING and DIRECTORY INDEX sections of code from your root .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes.', 'bulletproof-security').'</font>'; echo $text ; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode[bps_customcode_directory_index]" tabindex="1"><?php echo $options['bps_customcode_directory_index']; ?></textarea><br /><br />

	<strong><label for="bps-CCode"><?php _e('CUSTOM CODE BRUTE FORCE LOGIN PAGE PROTECTION:', 'bulletproof-security'); ?> </label></strong><br />
<strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('This Custom Code text box is for optional/Bonus code. To get this code click the link below:', 'bulletproof-security').'<br><a href="http://forum.ait-pro.com/forums/topic/protect-login-page-from-brute-force-login-attacks/" title="Link opens in a new Browser window" target="_blank" style="font-size:11px;">http://forum.ait-pro.com/forums/topic/protect-login-page-from-brute-force-login-attacks/</a></font>'; echo $text ; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode[bps_customcode_server_protocol]" tabindex="1"><?php echo $options['bps_customcode_server_protocol']; ?></textarea><br /><br />

	<strong><label for="bps-CCode"><?php _e('CUSTOM CODE ERROR LOGGING AND TRACKING: Add/Modify Error logging code here', 'bulletproof-security'); ?> </label></strong><br />
<strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('You MUST copy and paste the entire ERROR LOGGING AND TRACKING section of code from your root .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes.', 'bulletproof-security').'</font>'; echo $text ; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode[bps_customcode_error_logging]" tabindex="1"><?php echo $options['bps_customcode_error_logging']; ?></textarea><br /><br />
	<strong><label for="bps-CCode"><?php _e('CUSTOM CODE WP-ADMIN/INCLUDES: DO NOT add wp-admin .htaccess code here', 'bulletproof-security'); ?> </label></strong><br />
<strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('Add one pound sign # below to remove the WP-ADMIN/INCLUDES section of code from your root .htaccess file', 'bulletproof-security').'</font>'; echo $text ; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode[bps_customcode_admin_includes]" tabindex="1"><?php echo $options['bps_customcode_admin_includes']; ?></textarea><br /><br />
	<strong><label for="bps-CCode"><?php _e('CUSTOM CODE WP REWRITE LOOP START: Add www to non-www/non-www to www code here', 'bulletproof-security'); ?> </label></strong><br />
<strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('You MUST copy and paste the entire WP REWRITE LOOP START section of code from your root .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes.', 'bulletproof-security').'</font>'; echo $text ; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode[bps_customcode_wp_rewrite_start]" tabindex="1"><?php echo $options['bps_customcode_wp_rewrite_start']; ?></textarea><br /><br />
	<strong><label for="bps-CCode"><?php _e('CUSTOM CODE REQUEST METHODS FILTERED: Whitelist User Agents or remove HEAD here', 'bulletproof-security'); ?> </label></strong><br />
<strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('You MUST copy and paste the entire REQUEST METHODS FILTERED section of code from your root .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes.', 'bulletproof-security').'</font>'; echo $text ; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode[bps_customcode_request_methods]" tabindex="1"><?php echo $options['bps_customcode_request_methods']; ?></textarea><br /><br />
    <strong><label for="bps-CCode"><?php _e('CUSTOM CODE PLUGIN/THEME SKIP/BYPASS RULES:<br>Add personal plugin/theme skip/bypass rules here', 'bulletproof-security'); ?> </label></strong><br />
 <strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('ONLY add valid htaccess code below or text commented out with a pound sign #', 'bulletproof-security').'</font>'; echo $text; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode[bps_customcode_two]" tabindex="2"><?php echo $options['bps_customcode_two']; ?></textarea><br /><br />
    <strong><label for="bps-CCode"><?php _e('CUSTOM CODE TIMTHUMB FORBID RFI and MISC FILE SKIP/BYPASS RULE:<br>Add additional Referers and/or misc file names', 'bulletproof-security'); ?> </label></strong><br />
 <strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('You MUST copy and paste the entire TIMTHUMB FORBID RFI section of code from your root .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes.', 'bulletproof-security').'</font>'; echo $text; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode[bps_customcode_timthumb_misc]" tabindex="2"><?php echo $options['bps_customcode_timthumb_misc']; ?></textarea><br /><br />
    <strong><label for="bps-CCode"><?php _e('CUSTOM CODE BPSQSE BPS QUERY STRING EXPLOITS: Modify Query String Exploit code here', 'bulletproof-security'); ?> </label></strong><br />
 <strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('You MUST copy and paste the entire BPSQSE QUERY STRING EXPLOITS section of code from your root .htaccess file from # BEGIN BPSQSE BPS QUERY STRING EXPLOITS to # END BPSQSE BPS QUERY STRING EXPLOITS into this text box first. You can then edit and modify the code in this text window and save your changes.', 'bulletproof-security').'</font>'; echo $text; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode[bps_customcode_bpsqse]" tabindex="2"><?php echo $options['bps_customcode_bpsqse']; ?></textarea><br /><br />
    <strong><label for="bps-CCode"><?php _e('CUSTOM CODE DENY BROWSER ACCESS TO THESE FILES:<br>Add or remove files that you want to block or allow access to here', 'bulletproof-security'); ?> </label></strong><br />
 <strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('You MUST copy and paste the entire DENY BROWSER ACCESS section of code from your root .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes.', 'bulletproof-security').'</font>'; echo $text; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode[bps_customcode_deny_files]" tabindex="2"><?php echo $options['bps_customcode_deny_files']; ?></textarea><br /><br />
    <strong><label for="bps-CCode"><?php _e('CUSTOM CODE BOTTOM HOTLINKING/FORBID COMMENT SPAMMERS/BLOCK BOTS/BLOCK IP/REDIRECT CODE: Add miscellaneous code here', 'bulletproof-security'); ?> </label></strong><br />
 <strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('ONLY add valid htaccess code below or text commented out with a pound sign #', 'bulletproof-security').'</font>'; echo $text; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode[bps_customcode_three]" tabindex="3"><?php echo $options['bps_customcode_three']; ?></textarea>
    <input type="hidden" name="scrolltoCCode" value="<?php echo $scrolltoCCode; ?>" />
    <p class="submit">
	<input type="submit" name="bps_customcode_submit" value="<?php esc_attr_e('Save Root Custom Code', 'bulletproof-security') ?>" class="bps-blue-button" onclick="return confirm('<?php $text = __('Clicking OK will save your Root custom .htaccess code to your database and not actually write that custom htaccess code to your Root htaccess file.', 'bulletproof-security').'\n\n'.$bpsSpacePop.'\n\n'.__('To write your custom htaccess code to your Root htacess file click the Create secure.htaccess File AutoMagic button on the Security Modes page and then Activate BulletProof Mode for your Root folder.', 'bulletproof-security').'\n\n'.$bpsSpacePop.'\n\n'.__('Click OK to save your Root Custom Code or click Cancel.', 'bulletproof-security'); echo $text; ?>')" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#bpsCustomCodeForm').submit(function(){ $('#scrolltoCCode').val( $('#bulletproof_security_options_customcode[bps_customcode_one]').scrollTop() ); });
	$('#bulletproof_security_options_customcode[bps_customcode_one]').scrollTop( $('#scrolltoCCode').val() ); 
});
/* ]]> */
</script>
</td>
    <td width="50%" class="bps-table_cell_help_custom_code" style="padding:0px 0px 0px 10px;">
    <div id="CC-phpini-handler"><pre># TURN OFF YOUR SERVER SIGNATURE<br />ServerSignature Off<br /><br /># ADD A PHP HANDLER<br /># If you are using a PHP Handler add your web hosts PHP Handler below<br /><br /><div style="background-color:#FFFF00;padding:3px;"># CUSTOM CODE TOP PHP/PHP.INI HANDLER/CACHE CODE - Your Custom htaccess code will be created here with AutoMagic</div><br /># DO NOT SHOW DIRECTORY LISTING<br /># If you are getting 500 Errors when activating BPS then comment out Options -Indexes<br /># by adding a # sign in front of it. If there is a typo anywhere in this file you will also see 500 errors.<br />Options -Indexes</pre></div>

<div id="CC-directory-index"><pre># CUSTOM CODE DIRECTORY LISTING/DIRECTORY INDEX - Your Custom htaccess code will be created here with AutoMagic<br /># DO NOT SHOW DIRECTORY LISTING<br /># If you are getting 500 Errors when activating BPS then comment out Options -Indexes<br /># by adding a # sign in front of it. If there is a typo anywhere in this file you will also see 500 errors.<br />Options -Indexes<br /><br /># DIRECTORY INDEX FORCE INDEX.PHP<br /># Use index.php as default directory index file<br /># index.html will be ignored will not load.<br />DirectoryIndex index.php index.html /index.php</pre></div>

<div id="CC-brute-force"><pre># CUSTOM CODE BRUTE FORCE LOGIN PAGE PROTECTION - Your Custom htaccess code will be created here with AutoMagic<br /># BRUTE FORCE LOGIN PAGE PROTECTION<br /># Protects the Login page from SpamBots & Proxies<br /># that use Server Protocol HTTP/1.0 or a blank User Agent<br />RewriteCond %{REQUEST_URI} ^(/wp-login\.php|.*wp-login\.php.*)$<br />RewriteCond %{HTTP_USER_AGENT} ^$ [OR]<br />RewriteCond %{THE_REQUEST} HTTP/1\.0$ [OR]<br />RewriteCond %{SERVER_PROTOCOL} HTTP/1\.0$<br />RewriteRule ^(.*)$ - [F,L]</pre></div>

<div id="CC-error-logging"><pre># CUSTOM CODE ERROR LOGGING AND TRACKING - Your Custom htaccess code will be created here with AutoMagic<br /># BPS PRO ERROR LOGGING AND TRACKING<br /># BPS Pro has premade 403 Forbidden, 400 Bad Request and 404 Not Found files that are used<br />.....<br />.....<br />.....<br /># NOTE: By default WordPress automatically looks in your Theme's folder for a 404.php template file.<br /><br />ErrorDocument 400 <?php echo $bps_plugin_dir; ?>/bulletproof-security/400.php<br />ErrorDocument 401 default<br />ErrorDocument 403 <?php echo $bps_plugin_dir; ?>/bulletproof-security/403.php<br />ErrorDocument 404 /404.php</pre></div>

<div id="CC-wpadmin-includes"><pre># CUSTOM CODE WP-ADMIN/INCLUDES - Your Custom htaccess code will be created here with AutoMagic<br /># WP-ADMIN/INCLUDES<br />RewriteEngine On<br />RewriteBase /<br />RewriteRule ^wp-admin/includes/ - [F,L]<br />RewriteRule !^wp-includes/ - [S=3]<br />RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]<br />RewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F,L]<br />RewriteRule ^wp-includes/theme-compat/ - [F,L]</pre></div>

<div id="CC-start-rewrite"><pre># CUSTOM CODE WP REWRITE LOOP START - Your Custom htaccess code will be created here with AutoMagic<br /># WP REWRITE LOOP START<br />RewriteEngine On<br />RewriteBase /<br />RewriteRule ^index\.php$ - [L]</pre></div>

<div id="CC-request-methods"><pre># CUSTOM CODE REQUEST METHODS FILTERED - Your Custom htaccess code will be created here with AutoMagic<br /># REQUEST METHODS FILTERED<br /># This filter is for blocking junk bots and spam bots from making a HEAD request, but may also block some<br /># HEAD request from bots that you want to allow in certains cases. This is not a security filter and is just<br />.....<br />.....<br />.....<br />RewriteEngine On<br />RewriteCond %{REQUEST_METHOD} ^(HEAD|TRACE|DELETE|TRACK|DEBUG) [NC]<br />RewriteRule ^(.*)$ - [F,L]</pre></div>

<div id="CC-plugins-skip-rules"><pre># PLUGINS/THEMES AND VARIOUS EXPLOIT FILTER SKIP RULES<br /># IMPORTANT!!! If you add or remove a skip rule you must change S= to the new skip number<br /># Example: If RewriteRule S=5 is deleted than change S=6 to S=5, S=7 to S=6, etc.<br /><br /><div style="background-color:#FFFF00;padding:3px;"># CUSTOM CODE PLUGIN SKIP/BYPASS RULES - Your plugins skip/bypass rules .htaccess code will be created here with AutoMagic</div><br /># Adminer MySQL management tool data populate<br />RewriteCond %{REQUEST_URI} ^/<?php echo $bps_plugin_dir; ?>/adminer/ [NC]<br />RewriteRule . - [S=12]</pre></div>

<div id="CC-timthumb"><pre># CUSTOM CODE TIMTHUMB FORBID RFI and MISC FILE SKIP/BYPASS RULE - Your Custom htaccess code will be created here with AutoMagic<br /># TIMTHUMB FORBID RFI and MISC FILE SKIP/BYPASS RULE<br /># Only Allow Internal File Requests From Your Website<br /># To Allow Additional Websites Access to a File Use [OR] as shown below.<br />.....<br />.....<br />.....<br />RewriteCond %{REQUEST_URI} (timthumb\.php|phpthumb\.php|thumb\.php|thumbs\.php) [NC]<br />RewriteCond %{HTTP_REFERER} ^.*<?php echo $bps_get_domain_root; ?>.*<br />RewriteRule . - [S=1]</pre></div>

<div id="CC-BPSQSE"><pre># CUSTOM CODE BPSQSE BPS QUERY STRING EXPLOITS - Your Custom htaccess code will be created here with AutoMagic<br /># BEGIN BPSQSE BPS QUERY STRING EXPLOITS<br /># The libwww-perl User Agent is forbidden - Many bad bots use libwww-perl modules, but some good bots use it too.<br /># Good sites such as W3C use it for their W3C-LinkChecker.<br />.....<br />.....<br />.....<br />RewriteCond %{QUERY_STRING} (;|&lt;|&gt;|'|&quot;|\)|%0A|%0D|%22|%27|%3C|%3E|%00).*(/\*|union|select|insert|drop|delete|update|cast|create|char|convert|alter|declare|order|script|set|md5|benchmark|encode) [NC,OR]<br />RewriteCond %{QUERY_STRING} (sp_executesql) [NC]<br />RewriteRule ^(.*)$ - [F,L]<br /># END BPSQSE BPS QUERY STRING EXPLOITS</pre></div>

<div id="CC-deny-browser-access"><pre># CUSTOM CODE DENY BROWSER ACCESS TO THESE FILES - Your Custom htaccess code will be created here with AutoMagic<br /># DENY BROWSER ACCESS TO THESE FILES<br /># wp-config.php, bb-config.php, php.ini, php5.ini, readme.html<br /># Replace Allow from 88.77.66.55 with your current IP address and remove the<br />.....<br />.....<br />.....<br />&lt;FilesMatch &quot;^(wp-config\.php|php\.ini|php5\.ini|readme\.html|bb-config\.php)&quot;&gt;<br />Order allow,deny<br />Deny from all<br />#Allow from 88.77.66.55<br />&lt;/FilesMatch&gt;</pre></div>

<div id="CC-bottom-spam-block"><pre># CUSTOM CODE BOTTOM HOTLINKING/FORBID COMMENT SPAMMERS/BLOCK BOTS/BLOCK IP/REDIRECT CODE - Your Custom htaccess code will be created here with AutoMagic<br /># BLOCK HOTLINKING TO IMAGES<br />.....<br />.....<br /># FORBID COMMENT SPAMMERS ACCESS TO YOUR wp-comments-post.php FILE<br /># This is a better approach to blocking Comment Spammers so that you do not <br />.....<br />.....<br /># BLOCK MORE BAD BOTS RIPPERS AND OFFLINE BROWSERS<br /># If you would like to block more bad bots you can get a blacklist from<br />.....<br />.....<br /># REDIRECT CODE<br />.....</pre></div>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">&nbsp;</td>
    <td class="bps-table_cell_help">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
</div>
    
    <h3><?php _e('wp-admin htaccess File Custom Code', 'bulletproof-security'); ?></h3>
<div style="margin:0px 0px 0px -25px;">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td colspan="2" class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td width="50%" class="bps-table_cell_help">     

    <form name="bpsCustomCodeFormWPA" action="options.php#bps-tabs-7" method="post">
	<?php settings_fields('bulletproof_security_options_customcode_WPA'); ?>
	<?php $options = get_option('bulletproof_security_options_customcode_WPA'); ?>
<div>
<strong><label for="bps-CCode"><?php _e('CUSTOM CODE BPS WPADMIN DENY ACCESS TO FILES:<br>Add additional wp-admin files that you would like to block here', 'bulletproof-security'); ?> </label></strong><br />
<strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('You MUST copy and paste the entire BPS WPADMIN DENY ACCESS TO FILES section of code from your wp-admin .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes.', 'bulletproof-security').'</font>'; echo $text; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode_WPA[bps_customcode_deny_files_wpa]" tabindex="4"><?php echo $options['bps_customcode_deny_files_wpa']; ?></textarea><br /><br />
<strong><label for="bps-CCode"><?php _e('CUSTOM CODE WPADMIN TOP:<br>Add wp-admin password protection, IP whitelist allow access & miscellaneous custom code here', 'bulletproof-security'); ?> </label></strong><br />
<strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('ONLY add valid htaccess code below or text commented out with a pound sign #', 'bulletproof-security').'</font>'; echo $text; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode_WPA[bps_customcode_one_wpa]" tabindex="4"><?php echo $options['bps_customcode_one_wpa']; ?></textarea><br /><br />
   <strong><label for="bps-CCode"><?php _e('CUSTOM CODE WPADMIN PLUGIN FIXES: Add ONLY WPADMIN personal plugin fixes code here', 'bulletproof-security'); ?> </label></strong><br />
 <strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('ONLY add valid htaccess code below or text commented out with a pound sign #', 'bulletproof-security').'</font>'; echo $text; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode_WPA[bps_customcode_two_wpa]" tabindex="5"><?php echo $options['bps_customcode_two_wpa']; ?></textarea><br /><br />
<strong><label for="bps-CCode"><?php _e('CUSTOM CODE BPSQSE-check BPS QUERY STRING EXPLOITS AND FILTERS:<br>Modify Query String Exploit code here', 'bulletproof-security'); ?> </label></strong><br />
<strong><label for="bps-CCode"><?php $text = '<font color="blue">'.__('You MUST copy and paste the entire BPS QUERY STRING EXPLOITS section of code from your wp-admin .htaccess file into this text box first. You can then edit and modify the code in this text window and save your changes.', 'bulletproof-security').'</font>'; echo $text; ?> </label></strong><br />
    <textarea class="bps-text-area-custom-code" name="bulletproof_security_options_customcode_WPA[bps_customcode_bpsqse_wpa]" tabindex="4"><?php echo $options['bps_customcode_bpsqse_wpa']; ?></textarea>
    <input type="hidden" name="scrolltoCCodeWPA" value="<?php echo $scrolltoCCodeWPA; ?>" />
    <p class="submit">
	<input type="submit" name="bps_customcode_submit_wpa" value="<?php esc_attr_e('Save wp-admin Custom Code', 'bulletproof-security') ?>" class="bps-blue-button" onclick="return confirm('<?php $text = __('Clicking OK will save your wp-admin custom .htaccess code to your database and not actually write that custom htaccess code to your wp-admin htaccess file.', 'bulletproof-security').'\n\n'.$bpsSpacePop.'\n\n'.__('To write your custom htaccess code to your wp-admin htacess file go to the Security Modes page and then Activate BulletProof Mode for your wp-admin folder.', 'bulletproof-security').'\n\n'.$bpsSpacePop.'\n\n'.__('NOTE: The wp-admin htaccess file does not have an AutoMagic button and your Custom Code is written directly to your wp-admin htaccess file when you Activate BulletProof Mode for your wp-admin folder.', 'bulletproof-security').'\n\n'.$bpsSpacePop.'\n\n'.__('Click OK to save your wp-admin Custom Code or click Cancel.', 'bulletproof-security'); echo $text; ?>')" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#bpsCustomCodeFormWPA').submit(function(){ $('#scrolltoCCodeWPA').val( $('#bulletproof_security_options_customcode_WPA[bps_customcode_deny_files_wpa]').scrollTop() ); });
	$('#bulletproof_security_options_customcode_WPA[bps_customcode_deny_files_wpa]').scrollTop( $('#scrolltoCCodeWPA').val() ); 
});
/* ]]> */
</script>
</td>
    <td width="50%" class="bps-table_cell_help_custom_code" style="padding:10px 0px 0px 10px;">
    <div id="CC-wpadmin-deny-files"><pre># BEGIN BPS WPADMIN DENY ACCESS TO FILES<br />&lt;FilesMatch &quot;^(install\.php|example\.php|example2\.php|example3\.php)&quot;&gt;<br />Order allow,deny<br />Deny from all<br />#Allow from 88.77.66.55<br />&lt;/FilesMatch&gt;<br /># END BPS WPADMIN DENY ACCESS TO FILES</pre></div>

    <div id="CC-wpadmin-optional-measures"><pre># BEGIN OPTIONAL WP-ADMIN ADDITIONAL SECURITY MEASURES:<br /><br /># BEGIN CUSTOM CODE WPADMIN TOP: Add miscellaneous custom code here<br /><div style="background-color:#FFFF00;padding:3px;"># CCWTOP - Your custom code will be created here when you activate wp-admin BulletProof Mode</div># END CUSTOM CODE WPADMIN TOP<br /><br /># WP-ADMIN DIRECTORY PASSWORD PROTECTION - .htpasswd<br /># The BPS root .htaccess file already has a security rule that blocks access to all</pre></div>
    
<div id="CC-wpadmin-request-methods"><pre># REQUEST METHODS FILTERED<br />RewriteEngine On<br />RewriteCond %{REQUEST_METHOD} ^(HEAD|TRACE|DELETE|TRACK|DEBUG) [NC]<br />RewriteRule ^(.*)$ - [F,L]<br /><br /># BEGIN CUSTOM CODE WPADMIN PLUGIN FIXES: Add ONLY WPADMIN personal plugin fixes code here<br /><div style="background-color:#FFFF00;padding:3px;"># CCWPF - Your custom code will be created here when you activate wp-admin BulletProof Mode</div># END CUSTOM CODE WPADMIN PLUGIN FIXES<br /><br /># Allow wp-admin files that are called by plugins<br /># Fix for WP Press This</pre></div>   

    <div id="CC-wpadmin-BPSQSE"><pre># BEGIN BPSQSE-check BPS QUERY STRING EXPLOITS AND FILTERS<br /># WORDPRESS WILL BREAK IF ALL THE BPSQSE FILTERS ARE DELETED<br />RewriteCond %{HTTP_USER_AGENT} (%0A|%0D|%27|%3C|%3E|%00) [NC,OR]<br />.....<br />.....<br />.....<br />RewriteCond %{QUERY_STRING} (sp_executesql) [NC]<br />RewriteRule ^(.*)$ - [F,L]<br /># END BPSQSE-check BPS QUERY STRING EXPLOITS AND FILTERS</pre></div>

    </td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">&nbsp;</td>
    <td class="bps-table_cell_help">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
</div>
<?php } ?>
</div>
</div>
</div>

<div id="bps-tabs-8" class="bps-tab-page">
<h2><?php _e('BulletProof Security Maintenance Mode', 'bulletproof-security'); ?></h2>

<?php if ( !current_user_can('manage_options') ) { _e('Permission Denied', 'bulletproof-security'); } else { ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    
    <div id="bps-maintenance_form_table">
<h3><?php _e('Website Maintenance Mode Settings', 'bulletproof-security'); ?></h3>
<h3><?php echo '<font color="red"><strong>'; _e('CAUTION: ', 'bulletproof-security'); echo '</strong></font>'; ?>  <button id="bps-open-modal17" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button></h3>

<div id="bps-modal-content17" title="<?php _e('Website Maintenance Mode', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br><strong>'.__('Your Maintenance Mode Form data is saved to the WordPress Database and will remain permanently until you delete it. When you upgrade BPS your form data will still be saved in your database.','bulletproof-security').'</strong><br><br>'.__('If you are unable to log back into your website because you are also seeing the Maintenance Mode page then you only need to use FTP or use your Web Host Control Panel and delete the .htaccess file that is in your root website folder to be able to log back into your website.', 'bulletproof-security').'<br><br><strong>'.__('Maintenance Mode Activation Steps','bulletproof-security').'</strong><br><br><strong>'.__('Filling In The Maintenance Mode Settings Form','bulletproof-security').'</strong><br><strong>'.__('1. Fill out the Website Maintenance Mode Form','bulletproof-security').'</strong><br> -- '.__('For the Retry-After text field I recommend using 259200. 259200 is 72 hours in seconds. 3600 = 1hr 43200 = 12hrs 86400 = 24hrs. You can copy and paste the example Background Image URL into the Background Image text field if you want to use the background image file that comes with BPS. If you have another background image file that you want to use then just name it with the same name as the example image file and copy it to the /bulletproof-security folder. If you do not want a background image then leave this text field blank. The background color will be white. If you want to customize the Website Under Maintenance template then download this file located in this folder /bulletproof-security/admin/htaccess/bp-maintenance.php.','bulletproof-security').'<br><strong>'.__('2. Click the Save Form Settings button to save your form data to your database.','bulletproof-security').'</strong><br><strong>'.__('3. Click the Create Form button to create your Website Under Maintenance form.','bulletproof-security').'</strong><br><strong>'.__('4. Click the Preview Form button to preview your Website Under Maintenance page.','bulletproof-security').'</strong><br> -- '.__('If you see a 404 or 403 Forbidden message in the popup preview window refresh the popup preview window or just close the popup window and click the Preview button again.','bulletproof-security').'<br> -- '.__('You can use the Preview button at any time to preview how your site will be displayed to everyone else except you when your website is in Maintenance Mode.','bulletproof-security').'<br><br><strong>'.__('Create Your Maintenance Mode .htaccess File','bulletproof-security').'</strong><br>'.__('After you have finished previewing your Website Under Maintenance page, click the Create htaccess File button. This creates your Maintenance Mode .htaccess file for your website. Your current Public IP address and correct RewriteBase and RewriteRule are included when this new Maintenance Mode .htaccess file is created.','bulletproof-security').'<br><br><strong>'.__('Activate Website Under Maintenance Mode','bulletproof-security').'</strong><br>'.__('Select the Maintenance Mode radio button and click the Activate Maintenance Mode button. Your website is now in Maintenance Mode. Everyone else will see your Website Under Maintenance page while you can still view and work on your site as you normally would.','bulletproof-security'); echo $text; ?></p>
</div>

<form name="bps-maintenance-values" action="options.php#bps-tabs-8" method="post">
<?php settings_fields('bulletproof_security_options_maint'); ?>
			<?php $options = get_option('bulletproof_security_options_maint'); ?>
<table class="form-table">
<tr valign="top">
<th scope="row"><label for="bps-site-title"><?php _e('Site Title:', 'bulletproof-security') ?></label></th>
<td><input name="bulletproof_security_options_maint[bps-site-title]" type="text" value="<?php echo $options['bps-site-title']; ?>" class="regular-text" /><span class="description"><?php _e('Add Your Page Title', 'bulletproof-security') ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="bps-message-1"><?php _e('Message 1:', 'bulletproof-security') ?></label></th>
<td><input name="bulletproof_security_options_maint[bps-message-1]" type="text" value="<?php echo $options['bps-message-1']; ?>" class="regular-text" /><span class="description"><?php _e('Add Your Message', 'bulletproof-security') ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="bps-message-2"><?php _e('Message 2:', 'bulletproof-security') ?></label></th>
<td><input name="bulletproof_security_options_maint[bps-message-2]" type="text" value="<?php echo $options['bps-message-2']; ?>" class="regular-text" /><span class="description"><?php _e('Add Another Message or Not', 'bulletproof-security') ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="bps-retry-after"><?php _e('Retry-After:', 'bulletproof-security') ?></label></th>
<td><input name="bulletproof_security_options_maint[bps-retry-after]" type="text" value="<?php echo $options['bps-retry-after']; ?>" class="regular-text" /><span class="description"><?php _e('259200 (Recommended best setting)', 'bulletproof-security') ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="bps-background-image"><?php _e('Background Image', 'bulletproof-security') ?></label></th>
<td><input name="bulletproof_security_options_maint[bps-background-image]" type="text" value="<?php echo $options['bps-background-image']; ?>" class="regular-text" /><span class="description"><?php echo plugins_url('/bulletproof-security/abstract-blue-bg.png'); ?></span></td>
</tr>
</table>
<p class="submit">
<input type="submit" name="bps-maintenance-values_submit" class="bps-blue-button" value="<?php esc_attr_e('Save Form Settings', 'bulletproof-security') ?>" />
</p>
</form>

<form name="bps-maintenance-create-values" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-8" method="post">
<?php wp_nonce_field('bulletproof_security_create_values_form'); ?>
<input type="hidden" name="mmfilename" value="bps-maintenance-create-valuesH" />
<p class="submit">
<input type="submit" name="bps-maintenance-create-values_submit" class="bps-blue-button" value="<?php esc_attr_e('Create Form', 'bulletproof-security') ?>" /></p>
</form>

<?php
// Maintenance Mode Preview - check Referer
if (isset($_POST['maintenance-mode-preview-submit']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_maintenance_preview' );

	$bps_get_IP = $_SERVER['REMOTE_ADDR'];
	$denyall_htaccess_file = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/.htaccess';
	$bps_denyall_content = "order deny,allow\ndeny from all\nallow from $bps_get_IP";
	
	if (is_writable($denyall_htaccess_file)) {
	if (!$handle = fopen($denyall_htaccess_file, 'w+b')) {
		exit;
    }
    if (fwrite($handle, $bps_denyall_content) === FALSE) {
		exit;
    }

    fclose($handle);
	
	} else {
    	
		echo $bps_topDiv;
		$text = '<font color="red"><strong>'.__('The file ', 'bulletproof-security').$denyall_htaccess_file.__(' is not writable or does not exist yet. ', 'bulletproof-security').'</strong></font>';
		echo $text;
		echo $bps_bottomDiv;
	}
}
?>

<form name="MaintenanceModePreview" method="post" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-8" target="" onSubmit="window.open('<?php echo plugins_url('/bulletproof-security/admin/htaccess/bp-maintenance.php'); ?>','','scrollbars=yes,menubar=yes,width=800,height=600,resizable=yes,status=yes,toolbar=yes')">
<?php wp_nonce_field('bulletproof_security_maintenance_preview'); ?>
<p class="submit">
<input type="submit" name="maintenance-mode-preview-submit" class="bps-blue-button" value="<?php esc_attr_e('Preview Form', 'bulletproof-security') ?>" /></p>
</form>

</div>

<h3><?php _e('Activate Website Under Maintenance Mode', 'bulletproof-security'); ?></h3>
<h3><?php echo '<font color="red"><strong>'; _e('CAUTION: ', 'bulletproof-security'); echo '</strong></font>'; ?>  <button id="bps-open-modal18" class="bps-modal-button"><?php _e('Read Me', 'bulletproof-security'); ?></button></h3>

<div id="bps-modal-content18" title="<?php _e('Activate Maintenance Mode', 'bulletproof-security'); ?>">
	<p><?php $text = '<strong>'.__('This Read Me Help window is draggable (top) and resizable (bottom right corner)','bulletproof-security').'</strong><br><br>'.__('If you are unable to log back into your website because you are also seeing the Maintenance Mode page then you only need to use FTP or use your Web Host Control Panel and delete the .htaccess file that is in your root website folder to be able to log back into your website.', 'bulletproof-security').'<br><br><strong>'.__('Activating Maintenance Mode will automatically unlock your Root .htaccess file. Be sure to lock your Root .htaccess file after you have put your site back in BulletProof Mode.','bulletproof-security').'</strong><br><br><strong>'.__('You must click the Create htaccess File button FIRST to create your Maintenance Mode htaccess file before activating Maintenance Mode if you want to be able to continue working on your website while everyone else sees the Website Under Maintenance page','bulletproof-security').'</strong><br>'.__('After you have created your Maintenance Mode .htaccess file - Select the Maintenance Mode radio button and click Activate.','bulletproof-security').'<br><br><strong>'.__('You might see BPS error messages displayed when you put your site in Maintenance Mode. You can disregard these error messages. When you put your site back into BulletProof Mode these error messages will automatically go away.','bulletproof-security').'</strong><br><br><strong>'.__('To switch out of or exit Maintenance Mode just activate BulletProof Security Mode for your Root folder on the Security Modes page.','bulletproof-security').'</strong><br><br>'.__('To view the Maintenance Mode page that your website visitors are seeing click the Preview Form button.','bulletproof-security').'<br><br>'.__('When you activate Maintenance Mode your website will be put in HTTP 503 Service Temporarily Unavailable status and display a Website Under Maintenance page to everyone except you. Your current Public IP address was automatically added to the Maintenance Mode file as well as the correct .htaccess RewriteRule and RewriteBase for your website when you clicked the Create File button.','bulletproof-security').'<br><br>'.__('To manually add additional IP addresses that are allowed to view your website normally use the BPS File Editor to add them. To view your current Public IP address click on the System Info tab menu.','bulletproof-security').'<br><br><strong>'.__('Your current Public IP address is also displayed on the Website Under Maintenance page itself.','bulletproof-security').'</strong><br><br>'.__('Your SERPs (website or web page ranking) will not be affected by putting your website in Maintenance Mode for several days for existing websites. To manually add additional IP addresses that can view your website you would add them using the BPS File Editor.','bulletproof-security').'<br><br>'.__('If you are unable to log back into your WordPress Dashboard and are also seeing the Website Under Maintenance page then you will need to FTP to your website and either delete the .htaccess file in your website root folder or download the .htaccess file - add your correct current Public IP address and upload it back to your website.','bulletproof-security'); echo $text; ?></p>
</div>

<form name="bps-auto-write-maint" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-8" method="post">
<?php wp_nonce_field('bulletproof_security_auto_write_maint'); ?>
<input type="hidden" name="filename" value="bps-auto-write-maint_write" />
<p class="submit">
<input type="submit" name="bps-auto-write-maint" class="bps-blue-button" value="<?php esc_attr_e('Create htaccess File', 'bulletproof-security') ?>" /></p>
</form>

<form name="BulletProof-Maintenance" action="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-8" method="post">
<?php wp_nonce_field('bulletproof_security_maintenance_copy'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection15" type="radio" value="bpmaintenance" class="tog" <?php checked('', $bpmaintenance); ?> />
	<?php _e('Maintenance Mode', 'bulletproof-security'); ?></label></th>
	<td class="url-path"><?php $text = '<font color="green">'.__('Click the Create htaccess File button first to create your Maintenance Mode .htaccess file. To switch out of or exit Maintenance Mode just activate BulletProof Security Mode for your Root Folder.', 'bulletproof-security').'</font><strong>'.__(' Read the ', 'bulletproof-security').'<font color="red">'.__('CAUTION: ', 'bulletproof-security').'</font>'.__('Read Me button for more detailed information.', 'bulletproof-security').'</strong>'; echo $text; ?></td>
   </tr>
</table>
<p class="submit">
<input type="submit" name="submit15" class="bps-blue-button" value="<?php esc_attr_e('Activate Maintenance Mode', 'bulletproof-security') ?>" />
</p>
</form>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>

<div id="bps-tabs-9">
<h2><?php _e('BulletProof Security Help &amp; FAQ', 'bulletproof-security'); ?></h2>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
   <tr>
    <td colspan="2" class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td width="50%" class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/category/bulletproof-security-contributors/" target="_blank"><?php _e('Contributors Page', 'bulletproof-security'); ?></a></td>
    <td width="50%" class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2304/wordpress-tips-tricks-fixes/permalinks-wordpress-custom-permalinks-wordpress-best-wordpress-permalinks-structure/" target="_blank"><?php _e('WP Permalinks - Custom Permalink Structure Help Info', 'bulletproof-security'); ?></a></td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"><a href="http://forum.ait-pro.com/read-me-first/" target="_blank"><?php _e('BulletProof Security Forum', 'bulletproof-security'); ?></a></td>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2239/bulletproof-security-plugin-support/adding-a-custom-403-forbidden-page-htaccess-403-errordocument-directive-examples/" target="_blank"><?php _e('Adding a Custom 403 Forbidden Page For Your Website', 'bulletproof-security'); ?></a></td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2252/bulletproof-security-plugin-support/checking-plugin-compatibility-with-bps-plugin-testing-to-do-list/" target="_blank"><?php _e('Plugin Compatibility Testing - Recent New Permanent Plugin Fixes', 'bulletproof-security'); ?></a></td>
    <td class="bps-table_cell_help"><a href="http://forum.ait-pro.com/video-tutorials/" target="_blank"><?php _e('Custom Code Video Tutorial', 'bulletproof-security'); ?></a></td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">&nbsp;</td>
    <td class="bps-table_cell_help">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
</div>

<div id="bps-tabs-10">
<h2><?php _e('Whats New in ~ ', 'bulletproof-security'); ?><?php echo $bps_version; ?></h2>
<h3><?php _e('The Whats New page will list new changes that were made in each new version release of BulletProof Security', 'bulletproof-security'); ?></h3>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-whats_new_table">
  <tr>
   <td width="1%" class="bps-table_title_no_border">&nbsp;</td>
   <td width="99%" class="bps-table_title_no_border">&nbsp;</td>
  </tr>
   <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr> 
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php $text = '<h3><strong>'.__('Custom Code Code Correction:', 'bulletproof-security').'</strong></h3>'.__('ENT_QUOTES flag added to Custom Code AutoMagic variables to convert Single Quote HTML entities stored in the DB back to characters during AutoMagic File writing.', 'bulletproof-security').'</a></strong>'; echo $text; ?>
    </td>
  </tr> 
   <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr> 
 <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php $text = '<h3><strong>'.__('BPS .49.7', 'bulletproof-security').'</strong></h3>'; echo $text; ?></td>
  </tr>
 <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php $text = '<h3><strong>'.__('Network / Multisite Plugin Network Activation or Single subsite Plugin Activation:', 'bulletproof-security').'</strong></h3>'.__('As of BulletProof Security .49.7, the BPS plugin can be Network Activated or you can allow the BPS plugin to be activated individually on each Network / Multisite subsite or of course you can choose not to Network Activate BPS or allow the BPS plugin on subsites. Super Admins will see BPS Dashboard Alerts and other Status displays on the Primary Site only. Administrators can activate or deactivate BPS on subsites if you allow this on your Network / Multisite website.', 'bulletproof-security').'<br><br><strong>'.__('Primary Network / Multisite Site Menus: ', 'bulletproof-security').'</strong>'.__('The BPS Primary Site Menus will display all BPS menus. All other BulletProof Security features are not available on subsites since Network/Multisite subsites are virtual and do not have separate website files of their own. All of the other standard BulletProof Security features work sitewide and affect all other virtual subsites with the exception of Login Security which works individually for each specific website - Primary or virtual subsites and therefore should only be available to and controlled by the Super Admin with Network Admin capabilities for the Network/Multisite website.', 'bulletproof-security').'<br><br><strong>'.__('NEW BulletProof Security Pro .49.7 Network / Multisite Subsite Menus', 'bulletproof-security').'<br><br>'; echo $text; ?>
    <img src="<?php echo plugins_url('/bulletproof-security/admin/images/BPS-Network-subsites-menu.png'); ?>" /><br /><br /></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>   
    <td class="bps-table_cell_no_border"><strong><?php _e('Login Security and System Info menus / pages are available on Network / Multisite subsites to Super Admins and Administrators: ', 'bulletproof-security'); ?></strong><br /><br /></td>
  </tr>	
  <tr>
	<td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('Login Security has all the same functionality on Network/Multisite subsites with these exceptions: ', 'bulletproof-security'); ?></strong><br /><?php _e('Login Security email alerting is not available for subsites.', 'bulletproof-security'); ?><br /><br /></td>
  </tr>
  <tr>
	<td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('System Info has all the same functionality on Network/Multisite subsites with these exceptions: ', 'bulletproof-security'); ?></strong><br /><?php _e('MySQL Database information is not displayed on subsites.', 'bulletproof-security'); ?><br /><br /></td>
  </tr> 
  <tr>
	<td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('New AutoMagic WP 3.5+ Network / Multisite .htaccess code: ', 'bulletproof-security'); ?></strong><br /><?php _e('BPS  AutoMagic buttons automatically write the correct Network / Multisite root .htaccess code for your site based on your WordPress version.', 'bulletproof-security'); ?><br /><br /></td>
  </tr>  
 <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('Network / Multisite New Feature Notice: BPS can now be Network Activated on Multisite:', 'bulletproof-security'); ?></strong><br /><?php _e('This Network / Multisite New Feature Dismiss Notice displays on Network / Multisite only to alert Network / Multisite site owners about the new Network Activation capability in BPS.', 'bulletproof-security'); ?></td>
  </tr>
   <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr> 
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php $text = '<h3><strong>'.__('CSS Visual Style Changes for WP 3.8+ MP6 & Pre 3.8 WP Versions:', 'bulletproof-security').'</strong></h3>'.__('WordPress 3.8 is using the new MP6 GUI. A BPS 3.8 CSS stylesheet has been created to visually display things correctly in WordPress 3.8. BPS will automatically load the correct CSS stylesheet for your WordPress version. CSS visual enhancements were also created for pre WordPress 3.8 versions.', 'bulletproof-security').'</a></strong>'; echo $text; ?>
    </td>
  </tr> 
   <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr> 
  <tr>
    <td class="bps-table_cell_bottom_no_border">&nbsp;</td>
    <td class="bps-table_cell_bottom_no_border">&nbsp;</td>
  </tr>
</table>
</div>

<div id="bps-tabs-11" class="bps-tab-page">
<h2><?php _e('My Notes', 'bulletproof-security'); ?></h2>
<div id="bpsMyNotesborder" style="border-top:1px solid #999999;">
<h3><?php _e('Save any personal notes or htaccess code to your WordPress Database', 'bulletproof-security'); ?></h3>
</div>

<?php if ( !current_user_can('manage_options') ) { _e('Permission Denied', 'bulletproof-security'); } else { 
	$scrolltoNotes = isset($_REQUEST['scrolltoNotes']) ? (int) $_REQUEST['scrolltoNotes'] : 0;
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">

<form name="myNotes" action="options.php#bps-tabs-11" method="post">
	<?php settings_fields('bulletproof_security_options_mynotes'); ?>
	<?php $options = get_option('bulletproof_security_options_mynotes'); ?>
<div>
    <textarea class="bps-text-area-600x700" name="bulletproof_security_options_mynotes[bps_my_notes]" tabindex="1"><?php echo $options['bps_my_notes']; ?></textarea>
    <input type="hidden" name="scrolltoNotes" value="<?php echo $scrolltoNotes; ?>" />
    <p class="submit">
	<input type="submit" name="myNotes_submit" class="bps-blue-button" value="<?php esc_attr_e('Save My Notes', 'bulletproof-security') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#myNotes').submit(function(){ $('#scrolltoNotes').val( $('#bulletproof_security_options_mynotes[bps_my_notes]').scrollTop() ); });
	$('#bulletproof_security_options_mynotes[bps_my_notes]').scrollTop( $('#scrolltoNotes').val() ); 
});
/* ]]> */
</script>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>

<div id="bps-tabs-12">
<h2><?php _e('BulletProof Security Pro Feature Highlights', 'bulletproof-security'); ?></h2>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td colspan="2" class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td width="62%" valign="top" class="bps-table_cell_help">

<div id="bpsProLogo" style="position:relative; top:0px; left:0px;"><a href="http://affiliates.ait-pro.com/po/" target="_blank" title="Get BulletProof Security Pro">
<img src="<?php echo plugins_url('/bulletproof-security/admin/images/bps-pro-logo.png'); ?>" style="float:left;width:361px;-moz-box-shadow:4px 4px 4px #888888;-webkit-box-shadow:4px 4px 4px #888888;box-shadow:4px 4px 4px #888888;" /></a>
</div>

<div id="bpsProText" style="margin:0px 0px 15px 0px;font-size:22px;color:#000066;font-weight:bold;font-style:italic;line-height:22px;text-align:center;">
<?php echo _e('The Most Effective & Comprehensive<br>WordPress Security Plugin', 'bulletproof-security'); ?>

<div id="bpsProLinks" style="margin:15px 0px 10px 0px;font-size:12px;font-weight:bold;font-style:normal;line-height:12px;">
    <a href="http://forum.ait-pro.com/video-tutorials/" target="_blank" title="Link Opens in New Browser Window"><?php _e('BPS Pro 1 Click Setup Wizard & Demo Video Tutorial', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://affiliates.ait-pro.com/" target="_blank" title="Link Opens in New Browser Window"><?php _e('BPS Pro Affiliate Program', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/bulletproof-security-pro-flash/bulletproof.html" target="_blank" title="Link Opens in New Browser Window"><?php _e('View All BPS Pro Features', 'bulletproof-security'); ?></a>
</div>
</div>

<div id="bpsProFeatures" style="position:relative;top:20px;left:0px;font-size:14px;">

<?php echo '<strong>'; _e('1 Click Setup Wizard: ', 'bulletproof-security'); echo '</strong>'; _e('The Most Effective, Comprehensive & Affordable WordPress Security Plugin now includes a 1 click Setup Wizard. The Setup Wizard Takes 10 seconds to 1 minute to complete the BPS Pro setup.', 'bulletproof-security'); ?><br /><br />

<?php echo '<strong>'; _e('1 Click Upgrades: ', 'bulletproof-security'); echo '</strong>'; _e('BPS Pro Plugin upgrade notifications are displayed in your WordPress Dashboard exactly the same way as all other WordPress plugins. All BPS Pro files are automatically updated during the upgrade process and no additional setup steps are required when upgrading. When new features and options are added to new BPS Pro versions those new features and options are automatically setup during BPS Pro upgrades and do not require any additional setup or configuration by you.', 'bulletproof-security'); ?><br /><br />

<?php echo '<strong>'; _e('AutoRestore / Quarantine Intrusion Detection and Prevention Systems (IDPS): ', 'bulletproof-security'); echo '</strong>'; _e('ARQ is a real-time file monitor that automatically AutoRestores and/or Quarantines files. ARQ utilizes countermeasure website security that has the capability to protect all of your website files, both WordPress and non-WordPress files, even if your Web Host Server is hacked or if your FTP password is cracked or stolen. Quarantine Options: Restore File, Delete File and View File. AutoRestore/Quarantine includes Displayed Alerts, Email Alerts and Logging. AutoRestore/Quarantine works seamlessly with WordPress Automatic Updates. The BPS Pro Security Log logs all WP files that were installed and backed up automatically during WordPress Automatic Update installations.', 'bulletproof-security'); ?><br /><br />

<?php echo '<strong>'; _e('Plugin Firewall: ', 'bulletproof-security'); echo '</strong>'; _e('The Plugin Firewall / Plugins BulletProof Mode prevents/blocks/forbids Remote Access to the plugins folder from external sources (remote script execution, hacker recon, remote scanning, remote accessibility, etc.) and only allows internal access to the plugins folder based on this criteria: Domain name, Server IP Address and Public IP / Your Computer IP Address.', 'bulletproof-security'); ?><br /><br />

<?php echo '<strong>'; _e('Uploads Folder Anti-Exploit Guard: ', 'bulletproof-security'); echo '</strong>'; _e('The Uploads Folder Anti-Exploit Guard / Uploads BulletProof Mode allows ONLY safe image files with valid image file extensions such as jpg, gif, png, etc. to be accessed, opened or viewed from the uploads folder. The Uploads Anti-Exploit Guard prevents/blocks/forbids files by file extension names in the uploads folder from being accessed, opened, viewed, processed or executed.', 'bulletproof-security'); ?><br /><br />

<?php echo '<strong>'; _e('Login Security & Monitoring: ', 'bulletproof-security'); echo '</strong>'; _e('Login Security & Monitoring allows you to choose whether or not to log all user account logins or only log user account lockouts. You can choose to have S-Monitor alerts displayed in your WP Dashboard, BPS Pages only or turn them off based on the Login Security options that you choose. S-Monitor Login Security email alerting options allow you to choose 5 different email alerting options: Choose to have email alerts sent when a User Account is locked out, An Administrator Logs in, An Administrator Logs in and when a User Account is locked out, Any User logs in and when a User Account is locked out or Do Not Send Email Alerts. Disable Password Reset option. Generic error messages displayed option.', 'bulletproof-security'); ?><br /><br />

<?php echo '<strong>'; _e('JTC Anti-Spam / Anti-Hacker: ', 'bulletproof-security'); echo '</strong>'; _e('Hacker Protection ~ Spammer Protection ~ DoS/DDoS Attack Protection ~ Brute Force Login Attack Protection ~ SpamBot Trap. JTC Anti-Spam provides website security protection as well as website Anti-Spam protection. JTC Anti-Spam is user friendly Anti-Spam / Anti-Hacker Protection. You can customize and personalize your JTC ToolTip message and CAPTCHA to match your website concept. JTC Anti-Spam / Anti-Hacker protects these website pages/Forms: Login page/Form, Registration page/Form, Lost Password page/Form, Comment page/Form, BuddyPress Register page/Form and the BuddyPress Sidebar Login Form with a user friendly & customizable jQuery ToolTip CAPTCHA.', 'bulletproof-security'); ?><br /><br />

<?php  echo '<strong>'; _e('Security / HTTP Error Logging/Displayed Alerts/Email Alerts: ', 'bulletproof-security'); echo '</strong>'; _e('BPS Pro Logs HTTP Errors and hacking attempts against your website. IP address, Host name, Request Method, Referering link, the file name or requested resource, the user agent and the query string are logged.', 'bulletproof-security'); ?><br /><br />

<?php  echo '<strong>'; _e('S-Monitor Displayed Alerts, Email Alerting & Log File Options: ', 'bulletproof-security'); echo '</strong>'; _e('S-Monitor displayed alerting options allow you to choose how you want real-time alerts displayed to you: WP Dashboard, BPS Pro pages only or turned off. Choose whether or not to have email alerts sent when Log files log events. Choose to either automatically Zip and Email Log files to you when they reach the maximum size limit option that you choose or just automatically delete log files when they reach the the maximum size limit option that you choose.', 'bulletproof-security'); ?><br /><br />

<?php echo '<strong>'; _e('F-Lock: ', 'bulletproof-security'); echo '</strong>'; _e('Lock and Unlock WordPress Mission Critical files from within your WordPress Dashboard.', 'bulletproof-security'); ?><br /><br />

<?php echo '<strong>'; _e('Custom php.ini / ini_set Options: ', 'bulletproof-security'); echo '</strong>'; _e('Quickly create a custom php.ini file for your website or use ini_set Options to increase security and performance with just a few clicks. Additional P-Security Features: All-purpose File Manager, All-purpose File Editor, Protected PHP Error Log, PHP Error Alerts, Secure phpinfo Viewer...', 'bulletproof-security'); ?><br /><br />

<?php echo '<strong>'; _e('Advanced Real-Time Alerting: ', 'bulletproof-security'); echo '</strong>';  _e('BPS Pro checks and displays error, warning, notifications and alert messages in real time. You can choose how you want these messages displayed to you with S-Monitor Monitoring &amp; Alerting Options - Display in your WP Dashboard, BPS Pro pages only, Turned off, Email Alerts, Logging...', 'bulletproof-security'); ?><br /><br />

<?php echo '<strong>'; _e('Pro-Tools: ', 'bulletproof-security'); echo '</strong>'; _e('Pro-Tools is a set of versatile website tools: Online Base64 Decoder, Offline Base64 Decode/Encode, Mcrypt ~ Decrypt / Encrypt, Crypt Encryption, Scheduled Crons, String Finder, String Replacer / Remover, DB String Finder, DNS Finder, Ping Website, cURL Multi Page Scanner, Website Headers, WP Automatic Update...', 'bulletproof-security'); ?><br /><br />
</div>	

    </td>
    <td width="38%" valign="top" class="bps-table_cell_help">

<div id="bpsProVersions">
<a href="http://forum.ait-pro.com/forums/topic/bulletproof-security-pro-version-release-dates/" target="_blank" title="Link Opens in New Browser Window" style="font-size:22px;"><?php _e('BPS Pro Version Releases', 'bulletproof-security'); ?></a><br /><br /> 
     <a href="http://www.ait-pro.com/aitpro-blog/4926/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-8-0/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 8.0', 'bulletproof-security'); ?></a><br /><br />  
    <a href="http://www.ait-pro.com/aitpro-blog/4916/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-7-9/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 7.9', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/4905/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-7-8/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 7.8', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/4900/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-7-7/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 7.7', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/4895/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-7-6/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 7.6', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/4889/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-7-5/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 7.5', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/4876/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-7-0/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 7.0', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/4845/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-6-5/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 6.5', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/4827/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-6-0/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 6.0', 'bulletproof-security'); ?></a><br /><br />
	<a href="http://www.ait-pro.com/aitpro-blog/4811/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-9/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.9', 'bulletproof-security'); ?></a><br /><br />
	<a href="http://www.ait-pro.com/aitpro-blog/4780/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-8/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.8/5.8.1/5.8.2', 'bulletproof-security'); ?></a><br /><br />
	<a href="http://www.ait-pro.com/aitpro-blog/4744/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-7/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.7/5.7.1/5.7.2', 'bulletproof-security'); ?></a><br /><br />
	<a href="http://www.ait-pro.com/aitpro-blog/4709/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-6/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.6/5.6.1', 'bulletproof-security'); ?></a><br /><br />	
    <a href="http://www.ait-pro.com/aitpro-blog/4683/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-5/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.5', 'bulletproof-security'); ?></a><br /><br />	
    <a href="http://www.ait-pro.com/aitpro-blog/4653/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-4/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.4/5.4.1', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/4628/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-3/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.3/5.3.1/5.3.2/5.3.3', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/4563/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-2/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.2/5.2.1/5.2.2', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/4442/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-1-9/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.1.9', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/4197/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-1-8/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.1.8/5.1.8.1/5.1.8.2/5.1.8.3/5.1.8.4', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/4144/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-1-7/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.1.7', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/4029/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-1-6/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.1.6', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/3845/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-1-5/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.1.5', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/3732/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-1-4/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.1.4', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/3605/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-1-3" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.1.3', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/3529/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-1-2/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.1.2', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/3510/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-1-1/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.1.1', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/3510/bulletproof-security-pro/whats-new-in-bulletproof-security-pro-5-1-1/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.1', 'bulletproof-security'); ?></a><br /><br />
    <a href="http://www.ait-pro.com/aitpro-blog/2835/bulletproof-security-pro/bulletproof-security-pro-features/" target="_blank" title="Link Opens in New Browser Window"><?php _e('Whats New in BPS Pro 5.0', 'bulletproof-security'); ?></a>
 </div>  
    
    </td>
  </tr>
   <tr>
    <td class="bps-table_cell_help">&nbsp;</td>
    <td class="bps-table_cell_help">&nbsp;</td>
  </tr>
   <tr>
    <td colspan="2" class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
</div>

<div id="bps-tabs-13">
<h2><?php _e('Sucuri SiteCheck - Free website malware & blacklist scan', 'bulletproof-security'); ?></h2>
<h3><?php _e('BPS is designed to protect your website from being hacked. If your website was already hacked prior to installing BPS then BPS will not automatically clean it up. Sucuri offers hacked website cleanup services.', 'bulletproof-security'); ?></h3>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    
    <div id="SucuriLogo" style="position:relative; top:0px; left:0px;"><img src="<?php echo plugins_url('/bulletproof-security/admin/images/sucuri-logo.png'); ?>" style="float:left; padding:0px 10px 0px 0px; margin:0px;" /><h3 style="font-size:14px;"><?php echo '<em>'.'"'.'...'; _e('the sheer nature of malware makes it very challenging to give you 100% certainty you will not get infected. The good news though is that we are doing everything in our power to ensure that 1 - you do not get infected, but 2 - if you do, we have the best solution to get you back on your feet.', 'bulletproof-security'); echo '"'.'</em><br> -- '; _e('Tony Perez, CFO Sucuri, LLC', 'bulletproof-security'); ?></h3><a href="http://sitecheck.sucuri.net/" target="_blank" title="Link opens in new browser window">Sucuri SiteCheck Scanner</a>
    </div>    
    
    </td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
</div>
        
<div id="bps-tabs-14">
<h2><?php _e('Website SEO', 'bulletproof-security'); ?></h2>
<h3><?php $text = __('Free, Premium Plugin and Theme testing, rating and review.', 'bulletproof-security').'<br><br><font color="blue"><strong>'.__('SPECIAL OFFER!!! ', 'bulletproof-security').'</strong></font>'.__('Search Engine Optimization (SEO) eBook for Beginners to Experienced Website Owners.', 'bulletproof-security'); echo $text; ?></h3>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    
    <div id="SucuriLogo" style="position:relative; top:0px; left:0px;"><img src="<?php echo plugins_url('/bulletproof-security/admin/images/themes-plugins-logo.png'); ?>" style="float:left; padding:0px 10px 30px 0px; margin:0px;" />
    <h3 style="font-size:14px;"><?php echo '<em>'.'"'.'...'; _e('We Test, Review & Rate Premium, Free and Paid WordPress Themes, Templates & Plugins Daily. 470 themes and 182 plugins have been tested to date....', 'bulletproof-security'); echo '"'.'</em><br> -- '; _e('Reza Shadpay, founder of themesplugins.com', 'bulletproof-security'); ?>
    <br /><br /><a href="http://www.themesplugins.com/" target="_blank" title="Link opens in new browser window">ThemesPlugins.com</a>
    </h3>
	
    <div id="ThemesPlugins" style="position:relative; top:0px; left:0px;">
    <h3 style="font-size:14px;"><?php echo '<em>'.'"'.'...'; _e('SEO explained for Beginners to Experienced website owners. Simple and fully explained WhiteHat SEO techniques and methods that will get your website top Google page ranking positions.', 'bulletproof-security'); echo '"'.'</em><br> -- '; _e('Reza Shadpay, founder of themesplugins.com', 'bulletproof-security'); ?>
    <br /><br /><a href="http://www.themesplugins.com/downloads/seo-ebook-wordpress-book-seo/" target="_blank" title="Link opens in new browser window">SEO eBook</a>
    </h3>
    </div>    
    </div>

    </td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
</div>

<div id="AITpro-link">BulletProof Security <?php echo BULLETPROOF_VERSION; ?> Plugin by <a href="http://www.ait-pro.com/" target="_blank" title="AITpro Website Security">AITpro Website Security</a>
</div>
</div>
</div>