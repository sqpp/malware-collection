<?php if (!defined ('ABSPATH')) die('No direct access allowed (restore)');
@set_time_limit(WPBACKITUP__SCRIPT_TIMEOUT_SECONDS);

/**
 * WP BackItUp  - Restore Job
 *
 * @package WP BackItUp
 * @author  Chris Simmons <chris.simmons@wpbackitup.com>
 * @link    http://www.wpbackitup.com
 *
 */

/*** Includes ***/

if( !class_exists( 'WPBackItUp_Restore' ) ) {
    include_once 'class-restore.php';
}

if( !class_exists( 'WPBackItUp_Filesystem' ) ) {
    include_once 'class-filesystem.php';
}

if( !class_exists( 'WPBackItUp_Zip' ) ) {
	include_once 'class-zip.php';
}

if( !class_exists( 'WPBackItUp_Utility' ) ) {
	include_once 'class-utility.php';
}

if( !class_exists( 'WPBackItUp_SQL' ) ) {
    include_once 'class-sql.php';
}

if( !class_exists( 'WPBackItUp_Task' ) ) {
	include_once 'class-task.php';
}

/*** Globals ***/
global $WPBackitup;
global $table_prefix; //this is from wp-config

global $backup_name; //name of the backup file
global $RestorePoint_SQL; //path to restore point
global $logger;

global $status_array,$inactive,$active,$complete,$failure,$warning,$success;
$inactive=0;
$active=1;
$complete=2;
$failure=-1;
$warning=-2;
$success=99;

//setup the status array
global $status_array;
$status_array = array(
	'preparing' =>$inactive ,
	'unzipping' =>$inactive ,
	'validation'=>$inactive,
	'deactivate_plugins' =>$inactive ,
	'restore_point'=>$inactive,
	'stage_wpcontent'=>$inactive,
	'restore_wpcontent'=>$inactive,
	'restore_database'=>$inactive,
	'update_user'=>$inactive,
	'update_site_info'=>$inactive,
	'update_permalinks'=>$inactive,
 );

//**************************//
//   SINGLE THREAD RESTORE  //
//**************************//

$logger_tasks = new WPBackItUp_Logger(false,null,'debug_restore_tasks');

//**************************//
//     Task Handling        //
//**************************//
$current_task= null;

$restore_error=false;
$logger_tasks->log_info(__METHOD__.'(' .$process_id .')','Check for available job');
if ($restore_job){

	//Get the next task in the stack
	$current_task = $restore_job->get_next_task();
	$logger_tasks->log_info(__METHOD__.'(' .$process_id .')','TASK Info:'.var_export($current_task,true));
	if (null!= $current_task && false!==$current_task){
		$restore_id=$restore_job->get_job_id();
		$current_task->increment_retry_count();

		//Was there an error on the previous task
		if (WPBackItUp_Job_v2::ERROR==$current_task->getStatus()){
			$logger_tasks->log_info(__METHOD__.'(' .$process_id .')','Restore Error Found:' .$current_task->getId());
			$restore_error=true;
		}

		$logger_tasks->log_info(__METHOD__.'(' .$process_id .')','Available Task Found:' . $current_task->getId());

	}else{
		$logger_tasks->log_info(__METHOD__.'(' .$process_id .')','No available tasks found.');
		//WPBackItUp_Backup::end(); //release lock
		return;
	}
}else {
	$logger_tasks->log_info(__METHOD__.'(' .$process_id .')','No job available.');

	//wp_clear_scheduled_hook( 'wpbackitup_run_restore_tasks');
	//WPBackItUp_Backup::end(); //release lock
	return;
}

//Should only get here when there is a task to run
$logger_tasks->log_info(__METHOD__.'(' .$process_id .')','Run Restore task:' .$current_task->getId());


//*****************//
//*** MAIN CODE ***//
//*****************//

//Get the job name
$job_log_name =  get_job_log_name($restore_job->get_job_id());

global $logger;
$logger = new WPBackItUp_Logger(false,null,$job_log_name,true);

$backup_name = $restore_job->get_job_meta('backup_name');
if( empty($backup_name)) {
	$logger->log_error(__METHOD__,'Backup name not found in job meta.');
	write_fatal_error_status('error201');
	end_restore();
}

//Get user ID
$user_id = $restore_job->get_job_meta('user_id');
if( empty($user_id)) {
	$logger->log_error(__METHOD__,'User Id not found in job meta.');
	write_fatal_error_status('error201');
	end_restore();
}

global $wp_restore; //Eventually everything will be migrated to this class
$wp_restore = new WPBackItUp_Restore($logger,$backup_name,$restore_job->get_job_id());

//*************************//
//***   RESTORE TASKS   ***//
//*************************//
//An error has occurred on the previous tasks
if ($restore_error) {

	//Check for error type
	switch ( $current_task->getId() ) {
		case "task_preparing":
			fatal_error( 'preparing', '2001', 'Task ended in error:'.$current_task->getId() );
			break;

		case "task_unzip_backup_set":
			fatal_error( 'unzipping', '2002', 'Task ended in error:'.$current_task->getId());
			break;

		case "task_validate_backup":
			fatal_error( 'validation', '2003', 'Task ended in error:'.$current_task->getId() );
			break;

		case "task_create_checkpoint":
			fatal_error( 'restore_point', '2004', 'Task ended in error:'.$current_task->getId() );
			break;

		case "task_stage_wpcontent":
			fatal_error( 'stage_wpcontent', '2005', 'Task ended in error:'.$current_task->getId() );
			break;

		case "task_restore_wpcontent":
			fatal_error( 'restore_wpcontent', '2006', 'Task ended in error:'.$current_task->getId() );
			break;

		case "task_restore_database":
			fatal_error( 'restore_database', '2007', 'Task ended in error:'.$current_task->getId() );
			break;

		default:
			fatal_error( 'unknown', '2999', 'Task ended in error:'.$current_task->getId() );
			break;
	}
}

//Cleanup Task
if ('task_preparing'==$current_task->getId()) {
	$logger->log('***BEGIN RESTORE***');
	$logger->log_sysinfo();

	$task = 'preparing';
	start_status($task);

	$logger->log('**PREPARING FOR RESTORE**');

	//ONLY check license here and prevent restore from starting. If
	//IF license check fails in later steps could be because DB was restored and no license on backup
	//which is a valid condition.
	if (! $this->license_active()){
		fatal_error($task,'225','Restore is not available because license is not active.');
	}

	//PREPARE TASK
	if (! class_exists('ZipArchive')){
		fatal_error($task,'235','Zip Archive Class is not available.');
	}

	$logger->log('*DELETE RESTORE FOLDER*');
	if ( ! $wp_restore->delete_restore_folder()){
		fatal_error($task,'222','Restore folder could not be deleted.');
	}
	$logger->log('*END DELETE RESTORE FOLDER*');

	$logger->log('*CREATE ROOT RESTORE FOLDER*');
	if ( ! $wp_restore->create_restore_root_folder()){
		fatal_error($task,'222','Root Restore folder could not be created.');
	}
	$logger->log('*END CREATE RESTORE FOLDER*');

	$logger->log('*DELETE STAGED FOLDER*');
	if ( ! $wp_restore->delete_staged_folders()){
		fatal_error($task, '222','Staged folders could not be deleted.');
	}
	$logger->log('*END DELETE STAGED FOLDER*');

	$logger->log('*UPDATE ZIP JOB META*');
	//Get the zip list
	$backup_path_pattern = $wp_restore->get_backup_folder_path() . '/'  .$wp_restore->get_backup_name() . '*.zip' ;
	$logger->log_info(__METHOD__,'Fetch backups pattern:' .$backup_path_pattern);
	$backup_set = glob( $backup_path_pattern);
	if ( is_array($backup_set) && count($backup_set)>0){
		$restore_job->update_job_meta('backup_set',$backup_set);
		$restore_job->update_job_meta('backup_set_remaining',$backup_set);
	}else{
		fatal_error($task,'222','No zip files found (pattern):' . $backup_path_pattern);
	}
	$logger->log('*END UPDATE ZIP JOB META*');


	$logger->log( '*UPDATE SITE VALUES META*' );
	$siteurl    = $wp_restore->get_siteurl();
	if (false===$siteurl){
		fatal_error($task,'207','Unable to fetch site url.');
	}else {
		$restore_job->update_job_meta('current_siteurl',$siteurl);
	}

	$homeurl    = $wp_restore->get_homeurl();
	if (false===$homeurl){
		fatal_error($task, '208','Unable to fetch home url.');
	}else{
		$restore_job->update_job_meta('current_homeurl',$homeurl);
	}

	$user_login = $wp_restore->get_user_login( $user_id );
	if (false===$user_login) {
		fatal_error($task,'209','Unable to fetch user login.');
	}else{
		$restore_job->update_job_meta('current_user_login',$user_login);
	}

	$user_pass  = $wp_restore->get_user_pass( $user_id );
	if (false===$user_pass){
		fatal_error($task,'210','Unable to fetch user password.');
	}else{
		$restore_job->update_job_meta('current_user_pass_hash',$user_pass);
	}

	$user_email = $wp_restore->get_user_email( $user_id );
	if (false===$user_email){
		fatal_error($task,'211','Unable to fetch user email.');
	} else{
		$restore_job->update_job_meta('current_user_email',$user_email);
	}

	$logger->log( '*END UPDATE SITE VALUES META*' );

	end_status($task);
	$restore_job->set_task_complete();
	$logger->log('**END PREPARING FOR RESTORE**');

	return;
}

if ('task_unzip_backup_set'==$current_task->getId()) {

	$logger->log( '**UNZIP BACKUP**' );

	$task = 'unzipping';
	start_status($task );

	//get the list of plugins zips in folder
	$backup_set_list=$restore_job->get_job_meta('backup_set_remaining');
	$logger->log_info(__METHOD__,'Begin -  Backup set list:');
	$logger->log($backup_set_list);
	if ( ! $wp_restore->unzip_archive_file( $backup_set_list) ) {
		fatal_error($task,'203','Unable to unzip archive.');
	} else {

		array_shift( $backup_set_list ); //remove from list
		$restore_job->update_job_meta('backup_set_remaining',$backup_set_list);

		if (is_array($backup_set_list) && count($backup_set_list)>0){
			//CONTINUE
			$logger->log('Continue unzipping backup set.');
			$restore_job->set_task_queued();
		} else{
			//COMPLETE
			$logger->log('Complete - All archives restored.');
			end_status( $task);
			$restore_job->set_task_complete();
			$logger->log( '**END UNZIP BACKUP**' );
		}

	}

	return;

}

//Validate the backup folder
if ('task_validate_backup'==$current_task->getId()) {
	$logger->log_info(__METHOD__, '**VALIDATE BACKUP**' );

	$task =  'validation';
	start_status($task);

	//Validate the restore folder

	if ( ! $wp_restore->validate_restore_folder( )){
		fatal_error($task,'204','Restore directory INVALID.');
	}

	$logger->log( '*VALIDATE MANIFEST*' );
	$backup_set_list=$restore_job->get_job_meta('backup_set');
	if ( $wp_restore->validate_manifest_file($backup_set_list,$error_code)===false){
		if ($error_code==1){
			fatal_error($task,'251','Empty manifest.');
		}

		if ($error_code==2){
			fatal_error($task,'252','Missing zip file.');
		}

		if ($error_code==3){
			fatal_error($task,'253','Zip file not in manifest.');
		}

		//shouldnt get here
		fatal_error($task,'999','Unexpected error code:' . $error_code);

	}
	$logger->log( '*END VALIDATE MANIFEST*' );

	$logger->log( '*VALIDATE SITEDATA FILE*' );
	//validate the site data file
	$site_info = $wp_restore->validate_siteinfo_file();
	if ( $site_info===false){
		fatal_error($task,'204','Site Data file INVALID.');
	}else{
		//save restore info to meta
		$restore_job->update_job_meta('restore_site_info',$site_info);
	}

	//Check table prefix values FATAL - need to add link to article
	if ( $table_prefix != $site_info['restore_table_prefix'] ) {
		fatal_error($task,'221','Table prefix different from restore.');
	}

	//Check wordpress version
	$site_wordpress_version =  get_bloginfo('version');
	$backup_wordpress_version = $site_info['restore_wp_version'];
	$logger->log_info(__METHOD__, 'Site Wordpress Version:' . $site_wordpress_version);
	$logger->log_info(__METHOD__, 'Backup Wordpress Version:' . $backup_wordpress_version);
	if ( ! WPBackItUp_Utility::version_compare($site_wordpress_version, $backup_wordpress_version )) {
		$logger->log( '*VALIDATE SITEDATA FILE*' );
		fatal_error($task,'226','Backup was created using different version of wordpress');
	}


	$restore_wpbackitup_version = $site_info['restore_wpbackitup_version'];
	$current_wpbackitup_version = WPBACKITUP__VERSION;
	$logger->log_info(__METHOD__, 'WP BackItUp current Version:' . $current_wpbackitup_version);
	$logger->log_info(__METHOD__, 'WP BackItUp backup  Version:' . $restore_wpbackitup_version);
	if (! WPBackItUp_Utility::version_compare($restore_wpbackitup_version, $current_wpbackitup_version )){
		fatal_error($task,'227','Backup was created using different version of WP BackItUp');
	}
	$logger->log( '*END VALIDATE SITEDATA FILE*' );


	$logger->log( '*VALIDATE SQL FILE EXISTS*' );
	if ( ! $wp_restore->validate_SQL_exists( )){
		fatal_error($task,'216','NO Database backups in backup.');
	}
	$logger->log( '*END VALIDATE SQL FILE EXISTS*' );
	end_status($task);

	$logger->log('*DEACTIVATE ACTIVE PLUGINS*');
	$task='deactivate_plugins';
	start_status($task);
	$wp_restore->deactivate_plugins();
	end_status($task);
	$logger->log('*END DEACTIVATE ACTIVE PLUGINS*');

	$restore_job->set_task_complete();
	$logger->log( '**END VALIDATE BACKUP**' );

	return;
}


//Create the DB restore point
if ('task_create_checkpoint'==$current_task->getId()) {

	$logger->log('**CREATE RESTORE POINT**');
	$task = 'restore_point';
	start_status($task);

	if ( ! $wp_restore->export_database()){
		fatal_error($task,'205','Cant backup database.');
	}

	$restore_job->set_task_complete();
	end_status($task);
	$logger->log('**END CREATE RESTORE POINT**');

	return;
}


//Stage WP content folders
if ('task_stage_wpcontent'==$current_task->getId()) {

	$logger->log('*STAGE WP-CONTENT*');
	$task = 'stage_wpcontent';

	start_status($task);

	$folder_stage_suffix = $wp_restore->get_restore_staging_suffix();

	//Stage all but plugins

	$logger->log('*STAGE THEMES*');
	$from_folder_name = $wp_restore->get_restore_root_folder_path() .'/' .WPBackItUp_Restore::THEMESPATH;
	$to_folder_name = WPBACKITUP__THEMES_ROOT_PATH . $folder_stage_suffix;
	if (! $wp_restore->rename_folder($from_folder_name,$to_folder_name)){
		fatal_error($task,'219','Cant stage themes.',false);
		$wp_restore->delete_staged_folders();
		end_restore();
	}
	$logger->log('*END STAGE THEMES*');

	$logger->log('*STAGE UPLOADS*');
	$from_folder_name = $wp_restore->get_restore_root_folder_path() .'/' .WPBackItUp_Restore::UPLOADPATH;
	$upload_array = wp_upload_dir();
	$uploads_root_path = $upload_array['basedir'];
	$to_folder_name = $uploads_root_path . $folder_stage_suffix;
	if (! $wp_restore->rename_folder($from_folder_name,$to_folder_name)){
		fatal_error($task,'219','Cant stage uploads.',false);
		$wp_restore->delete_staged_folders();
		end_restore();
	}
	$logger->log('*END STAGE UPLOADS*');

	$logger->log('*STAGE OTHER FOLDERS*');
	$other_list = glob($wp_restore->get_restore_root_folder_path() .'/' .WPBackItUp_Restore::OTHERPATH .'/*',GLOB_ONLYDIR|GLOB_NOSORT);
	foreach ( $other_list as $from_folder_name ) {
		$to_folder_name = WPBACKITUP__CONTENT_PATH .'/' .basename($from_folder_name) . $folder_stage_suffix;
		if (! $wp_restore->rename_folder($from_folder_name,$to_folder_name)) {
			fatal_error($task,'219','Cant stage other.',false);
			$wp_restore->delete_staged_folders();
			end_restore();
		}
	}
	$logger->log('*END STAGE OTHER FOLDERS*');
	end_status($task);

	$restore_job->set_task_complete();

	$logger->log('**END STAGE WP-CONTENT**');

	return;
}


//Rename the staged folders to current
if ('task_restore_wpcontent'==$current_task->getId()) {

	$logger->log('**RESTORE WPCONTENT**');
	$task ='restore_wpcontent';
	start_status($task);

	$logger->log('*RESTORE MAIN WPCONTENT*');
	$wpcontent_restore =$wp_restore->restore_wpcontent();
	if (! $wpcontent_restore===true) {
		//array with failed list returned
		//If any of them fail call it done.
		warning('300','Cant restore all WP content.');
	}
	$logger->log('*END RESTORE MAIN WPCONTENT*');

	$logger->log('*RESTORE PLUGINS*');
	$plugin_restore = $wp_restore->restore_plugins();
	if (! $plugin_restore ===true) {
		//array with fail list returned
		warning('305', 'Couldnt restore all plugins.');
	}

	$logger->log('*END RESTORE PLUGINS*');

	$restore_job->set_task_complete();
	end_status($task);
	$logger->log('**END RESTORE WPCONTENT**');

	return;
}

//restore the DB
if ('task_restore_database'==$current_task->getId()) {

	$logger->log('**RESTORE DATABASE**');
	$task ='restore_database';
	start_status($task);

	//grab the license before the database is restored
	$license_key = $this->license_key();

	$current_siteurl= $restore_job->get_job_meta('current_siteurl');
	$current_homeurl= $restore_job->get_job_meta('current_homeurl');

	$current_user_id=$restore_job->get_job_meta('user_id');
	$current_user_login=$restore_job->get_job_meta('current_user_login');
	$current_user_pass_hash= $restore_job->get_job_meta('current_user_pass_hash');
	$current_user_email=$restore_job->get_job_meta('current_user_email');

	$user_full_name='';
	$current_user = get_user_by('id',$current_user_id);
	if (false!==$current_user){
		$user_full_name = $current_user->first_name . ' ' .$current_user->last_name;
	}

	//Not going to use the restore Point SQL because IF the import failed then DB may be intact
	if ( ! $wp_restore->restore_database()) {
		fatal_error($task,'212','Database NOT restored.');
		//Do we want to recover the DB ?
	}

	end_status($task);
	$logger->log('**END RESTORE DATABASE**');

	$logger->log('*UPDATE DATABASE VALUES*');

	//update the session cookie
	wp_set_auth_cookie( $user_id, true);

	//Cancel any jobs that were in the restored DB
	WPBackItUp_Job_v2::cancel_all_jobs('backup');
	WPBackItUp_Job_v2::cancel_all_jobs('cleanup');

	start_status('update_user');
	//Restored DB so current user may not be there.
	//If current user id doesnt exist then create it
	//if exists update to current properties
	if ( ! $wp_restore->update_credentials($user_id, $user_full_name, $current_user_login, $current_user_pass_hash, $current_user_email,$table_prefix)){
			warning('215', 'Cant update user credentials.');
	}
	end_status('update_user');

	start_status('update_site_info');
	if ( ! $wp_restore->update_siteurl($table_prefix,$current_siteurl)){
		warning('213', 'Cant update site url.');
	}

	if ( ! $wp_restore->update_homeurl($table_prefix, $current_homeurl)){
		warning('214', 'Cant update home url.');
	}
	end_status('update_site_info');


	//Update the license information in the DB just in case it wasn't there on DB restore
	//Dont need to call activation, will happen on its own
	$wp_restore->update_license_key($table_prefix, $license_key);


	//DONT NEED TO UPDATE TASKS - DB RESTORED
	//DONT need to activate plugins, they will be active in restored DB

	start_status('update_permalinks');
	if (! $wp_restore->update_permalinks()){
		//dont do anything
	}
	end_status('update_permalinks');
	$logger->log('*END UPDATE DATABASE VALUES*');
}

//**************************************************************
//  After the database is restored all the job data will be gone
//
//      NO MORE TASKS OR JOB DATA AFTER THIS POINT
//
//**************************************************************


	//schedule a cleanup? with job id? for staged folder
	set_status_success();
	$logger->log('Restore completed successfully');
	$logger->log('***END RESTORE***');

	end_restore(null,true);

/******************/
/*** Functions ***/
/******************/
function fatal_error($process,$error_code,$error_message, $end=true){
	global $restore_job, $failure, $logger;

	$logger->log_error(__METHOD__,$error_message);
	$restore_job->set_task_error($error_code);
	write_response_file_error($error_code,$error_message);

	set_status($process,$failure,true);
	write_fatal_error_status('error' .$error_code);

	if ($end) {
		end_restore();
	}
}

function warning($error_code,$warning_message) {
	global $logger, $status_array,$warning;

	$logger->log_warning(__METHOD__, $warning_message);

	//Add warning to array
	$status_array['warning' .$error_code]=$warning;
	write_restore_status();
}

function set_status($process,$status,$flush){
	global $wp_restore,$logger;
	global $status_array,$complete;

	$status_array[$process]=$status;

	//Mark all the others complete and flush
	foreach ($status_array as $key => $value) {
		if ($process==$key) {
			break;
		}else{
			$status_array[$key]=$complete;
		}
	}

	if ($flush) write_restore_status();

}

//write Response Log
function write_response_file_error($error_code,$error_message) {

	$jsonResponse = new stdClass();
	$jsonResponse->restoreStatus = 'error';
	$jsonResponse->errorCode = $error_code;
	$jsonResponse->restoreMessage = $error_message;

	write_response_file($jsonResponse);
}

//write Response Log
function write_response_file_success() {

	$jsonResponse = new stdClass();
	$jsonResponse->backupStatus = 'success';
	$jsonResponse->backupMessage = 'success';

	write_response_file($jsonResponse);
}


//write Response Log
function write_response_file($JSON_Response) {
	global $logger;

	$json_response = json_encode($JSON_Response);
	$logger->log('Write response file:' . $json_response);

	$fh=get_response_file();
	fwrite($fh, $json_response);
	fclose($fh);
}

//Get Response Log
function get_response_file() {
	global $logger;
	$response_file_path = WPBACKITUP__PLUGIN_PATH .'logs/restore_response.log';
	$filesytem = new WPBackItUp_FileSystem($logger);
	return $filesytem->get_file_handle($response_file_path,false);
}

function get_job_log_name($timestamp){

	$url = home_url();
	$url = str_replace('http://','',$url);
	$url = str_replace('https://','',$url);
	$url = str_replace('/','-',$url);
	$fileUTCDateTime=$timestamp;//current_time( 'timestamp' );
	$localDateTime = date_i18n('Y-m-d-His',$fileUTCDateTime);
	$job_log_name = 'Restore_' . $url .'_' .$localDateTime;

	return $job_log_name;
}

//Get Status Log
function get_restore_Log() {
	global $logger;

	$status_file_path = WPBACKITUP__PLUGIN_PATH .'/logs/restore_status.log';
	$filesystem = new WPBackItUp_FileSystem($logger);
	return $filesystem->get_file_handle($status_file_path);

}

function write_fatal_error_status($status_code) {
	global $status_array,$inactive,$active,$complete,$failure,$warning,$success;
	
	//Find the active status and set to failure
	foreach ($status_array as $key => $value) {
		if ($value==$active){
			$status_array[$key]=$failure;	
		}
	}

	//Add failure to array
	$status_array[$status_code]=$failure;
	write_restore_status();
}



function write_restore_status() {
	global $status_array;
	$fh=get_restore_Log();
	
	foreach ($status_array as $key => $value) {
		fwrite($fh, '<div class="' . $key . '">' . $value .'</div>');		
	}
	fclose($fh);
}

function start_status($process){
	global $wp_restore,$active;

	set_status($process,$active,true);
	$wp_restore->save_process_status($process,'started');
}

function end_status($process){
	global $wp_restore,$complete;

	set_status($process,$complete,false);
	$wp_restore->save_process_status($process,'completed');
}


function set_status_success(){
	global $status_array,$inactive,$active,$complete,$failure,$warning,$success;
	global $active;

	$status_array['finalinfo']=$success;
	write_restore_status();
}

function end_restore($err=null, $success=null){
	global $restore_job, $logger;

	if (true===$success) $logger->log("Restore completed: SUCCESS");
	if (false===$success) $logger->log("Restore completed: ERROR");

	$logger->log("*** END RESTORE ***");


	//Close the logger
	$logger->close_file();
	$restore_job->release_lock();

	//response back the status file since this method will end processing
	$log = WPBACKITUP__PLUGIN_PATH .'/logs/restore_status.log';
	if(file_exists($log) ) {
		readfile($log);
	}

	exit(0);
}
