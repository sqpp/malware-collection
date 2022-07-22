<?php if (!defined ('ABSPATH')) die('No direct access allowed');

// Checking safe mode is on/off and set time limit
if( ini_get('safe_mode') ){
   @ini_set('max_execution_time', WPBACKITUP__SCRIPT_TIMEOUT_SECONDS);
}else{
   @set_time_limit(WPBACKITUP__SCRIPT_TIMEOUT_SECONDS);
}

/**
 * WP BackItUp  - Backup Job
 *
 * @package WP BackItUp
 * @author  Chris Simmons <chris.simmons@wpbackitup.com>
 * @link    http://www.wpbackitup.com
 *
 */

/*** Includes ***/

if( !class_exists( 'WPBackItUp_Utility' ) ) {
 	include_once 'class-utility.php';
}


if( !class_exists( 'WPBackItUp_SQL' ) ) {
	include_once 'class-sql.php';
}

if( !class_exists( 'WPBackItUp_Backup' ) ) {
	include_once 'class-backup.php';
}

 if( !class_exists( 'WPBackItUp_Zip' ) ) {
 	include_once 'class-zip.php';
 }


if( !class_exists( 'WPBackItUp_Filesystem' ) ) {
	include_once 'class-filesystem.php';
}

if( !class_exists( 'WPBackItUp_DataAccess' ) ) {
	include_once 'class-database.php';
}

//if( !class_exists( 'WPBackItUp_Encryption' ) ) {
//	include_once 'class-encryption.php';
//}

/*** Globals ***/
global $WPBackitup;

global $inactive,$active,$complete,$failure,$warning,$success;
$inactive=0;
$active=1;
$complete=2;
$failure=-1;
$warning=-2;
$success=99;

//setup the status array
global $status_array;
$status_array = array(
	'preparing' =>$inactive,
	'create_inventory' =>$inactive,
	'exportdb' =>$inactive,
	'backupdb' =>$inactive ,
	'backup_themes'=>$inactive,
	'backup_plugins'=>$inactive,
	'backup_uploads'=>$inactive,
	'backup_other'=>$inactive,
	'validate_backup'=>$inactive,
	'encrypt' =>$inactive,
	'finalize_backup'=>$inactive,
 );

//*****************//
//*** MAIN CODE ***//
//*****************//

if (! is_object ($current_job)){
	WPBackItUp_Logger::log_error($events_logname,$process_id,'Current job not object:');
	WPBackItUp_Logger::log_error($events_logname,$process_id,var_export($current_job));
	return false;
}

if (! is_object ($current_task)){
	WPBackItUp_Logger::log_error($events_logname,$process_id,'Current task not object');
	WPBackItUp_Logger::log_error($events_logname,$process_id,var_export($current_task));
	return false;
}

WPBackItUp_Logger::log_info($events_logname,$process_id ,'Run task:' .$current_task->getTaskName());

//*************************//
//*** MAIN BACKUP CODE  ***//
//*************************//
global $backup_logname;
//Get the backup ID
$backup_name =  $current_job->getJobName();
$backup_logname =  sprintf('JobLog_%s',$current_job->getJobName());

$log_function='job_backup::'.$current_task->getTaskName();

global $wp_backup;
$wp_backup = new WPBackItUp_Backup($backup_logname,$backup_name,$WPBackitup->backup_type);


//*************************//
//***   BACKUP TASKS    ***//
//*************************//

WPBackItUp_Logger::log_info($backup_logname,$log_function ,'Run task:' .$current_task->getTaskName());

//An error has occurred on the previous tasks
if (WPBackItUp_Job::ERROR==$current_task->getStatus()){
	WPBackItUp_Logger::log_error($backup_logname,$log_function,'Fatal error on previous task:'. $current_task->getTaskName());

	//Fetch last wordpress error(might not be related to timeout)
	//error type constants: http://php.net/manual/en/errorfunc.constants.php
	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Last Error: ' .var_export(error_get_last(),true));

	//Check for error type
	switch ($current_task->getTaskName()) {
		case "task_preparing":
			set_status('preparing',$active,true);
			write_fatal_error_status('2101');
			end_backup(2101, false);
			break;

		case "task_inventory_database":
		case "task_inventory_plugins":
		case "task_inventory_themes":
		case "task_inventory_uploads":
		case "task_inventory_others":
			set_status('create_inventory',$active,true);
			write_fatal_error_status('2127');
			end_backup(2127, false);
	case "task_backup_siteinfo":
			set_status( 'create_inventory', $active, true );
			write_fatal_error_status( '2105' );
			end_backup( 2105, false );
			break;

		case "task_export_db":
			set_status( 'exportdb', $active, true );
			write_fatal_error_status( '2104' );
			end_backup( 2104, false );
			break;

		case "task_backup_db":
			set_status( 'backupdb', $active, true );
			write_fatal_error_status( '2135' );
			end_backup( 2135, false );
			break;

		case "task_backup_themes":
			set_status( 'backup_themes', $active, true );
			write_fatal_error_status( '2120' );
			end_backup( 2120, false );
			break;

		case "task_backup_plugins":
			set_status( 'backup_plugins', $active, true );
			write_fatal_error_status( '2121' );
			end_backup( 2121, false );
			break;

		case "task_backup_uploads":
			set_status( 'backup_uploads', $active, true );
			write_fatal_error_status( '2122' );
			end_backup( 2122, false );
			break;

		case "task_backup_other":
			set_status( 'backup_other', $active, true );
			write_fatal_error_status( '2123' );
			end_backup( 2123, false );
			break;

		case "task_validate_backup":
			set_status( 'validate_backup', $active, true );
			write_fatal_error_status( '2126' );
			end_backup( 2126, false );
			break;

		case "task_encrypt_files":
			set_status( 'encrypt', $active, true );
			write_fatal_error_status( '2130' );
			end_backup( 2130, false );
			break;

		case "task_finalize_backup":
			set_status( 'finalize_backup', $active, true );
			write_fatal_error_status( '2109' );
			end_backup( 2109, false );
			break;

		default:
			write_warning_status( '2999' );
			end_backup( 2999, false );
			break;
	}

	return false;

}

if ('task_preparing'==$current_task->getTaskName()) {

	//Init
	WPBackItUp_Logger::log($backup_logname,'***BEGIN BACKUP***');
	WPBackItUp_Logger::log_sysinfo($backup_logname);
	WPBackItUp_Logger::log_info($backup_logname,$log_function,'BACKUP TYPE:' .$wp_backup->backup_type);
	WPBackItUp_Logger::log_info($backup_logname,$log_function,'BACKUP ID:' .$current_job->getJobId());

	$WPBackitup->increment_backup_count();
	//End Init

	WPBackItUp_Logger::log($backup_logname,'**BEGIN CLEANUP**');

	//Cleanup & Validate the backup folded is ready
	write_response_processing("preparing for backup");
	set_status('preparing',$active,true);

	write_response_processing("Cleanup before backup");

	//*** Check Dependencies ***
	if ( ! WPBackItUp_Zip::zip_utility_exists()) {
		WPBackItUp_Logger::log_error($backup_logname,$log_function,'Zip Util does not exist.' );
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,125);
		write_fatal_error_status( '125' );
		end_backup( 125, false );
		return false;
	}

	//*** END Check Dependencies ***


	//Make sure wpbackitup_backups exists
	if (! $wp_backup->backup_root_folder_exists() ){
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,101);

	    write_fatal_error_status('101');
	    end_backup(101, false);
		return false;
	}

	//Create the root folder for the current backup
	if (! $wp_backup->create_current_backup_folder()){
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,101);

	    write_fatal_error_status('101');
	    end_backup(101, false);
		return false;
	}

	//Check to see if the directory exists and is writeable
	if (! $wp_backup->backup_folder_exists()){
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,102);

	    write_fatal_error_status('102');
	    end_backup(102,false);
		return false;
	}

	set_status('preparing',$complete,false);
	$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);

	WPBackItUp_Logger::log($backup_logname,'**END CLEANUP**');
	return;
}

if ('task_inventory_database'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log( $backup_logname, '**INVENTORY DATABASE**' );

	write_response_processing( "Create inventory" );
	set_status( 'create_inventory', $active, true );

	// Exclude wp backitup job tables from backup
	$wpb_job_tables = WPBackItUp_DataAccess::get_excluded_jobs_tables();

	//get excludes from settings
	$backup_dbtables_filter_list = array_map('trim', explode(',', WPBackitup_Admin::backup_dbtables_filter_list()));
	$tables_exclude = array_merge(
		$backup_dbtables_filter_list,
		$wpb_job_tables
	);

	//get batch size from user settings
	$batch_size = $WPBackitup->backup_dbtables_batch_size();

	if ( ! $wp_backup->save_database_inventory($current_job->getJobId(), WPBackItUp_Job_Item::DATABASE, $batch_size, $tables_exclude ) ) {
		WPBackItUp_Logger::log_error( $backup_logname, $log_function, 'Database Inventory Error.' );
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,127);

		write_fatal_error_status( '127' );
		end_backup( 127, false );

		return false;
	};


	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Database Inventory complete.');
	$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);

	WPBackItUp_Logger::log($backup_logname,'**INVENTORY DATABASE**');
	return;

}


if ('task_inventory_plugins'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log( $backup_logname, '**INVENTORY PLUGINS**' );

	write_response_processing( "Create inventory" );
	set_status( 'create_inventory', $active, true );

	$global_exclude = explode(',', WPBACKITUP__BACKUP_GLOBAL_IGNORE_LIST);
	// Getting user filter
	$backup_plugins_filter = explode(',', WPBackitup_Admin::backup_plugins_filter());

	// WARNING
	// Remember these are wildcard searches(FOLDERS ONLY) so any folders containing these values will be excluded from inventory
	// ie. backup and backups will be excluded because "backup" was contained in the array
	// This is a deep filter as well so ANY occurrence of these will be excluded no matter how deep in the tree
	$plugin_exclude = array_merge(
		$global_exclude,
		$backup_plugins_filter,
		array(
			"wp-backitup",
            "wp-backitup-premium",
            "wp-backitup-safe"
		)
	);

	if ( ! $wp_backup->save_folder_inventory( WPBACKITUP__SQL_BULK_INSERT_SIZE, $current_job->getJobId(), WPBackItUp_Job_Item::PLUGINS, WPBACKITUP__PLUGINS_ROOT_PATH, $plugin_exclude ) ) {
		WPBackItUp_Logger::log_error( $backup_logname, $log_function, 'Plugins Inventory Error.' );
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,127);

		write_fatal_error_status( '127' );
		end_backup( 127, false );

		return false;
	};


	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Plugin Inventory complete.');

	//set_status('inventory',$complete,false);
	$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);

	WPBackItUp_Logger::log($backup_logname,'**INVENTORY PLUGINS**');
	return;

}



if ('task_inventory_themes'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log( $backup_logname, '**INVENTORY THEMES**' );

	$global_exclude = explode(',', WPBACKITUP__BACKUP_GLOBAL_IGNORE_LIST);

	// Getting user filter
	$backup_themes_filter = explode(',', WPBackitup_Admin::backup_themes_filter());

	// WARNING
	// Remember these are wildcard searches(FOLDERS ONLY) so any folders containing these values will be excluded from inventory
	// ie. backup and backups will be excluded because "backup" was contained in the array
	// This is a deep filter as well so ANY occurrence of these will be excluded no matter how deep in the tree
	$theme_exclude = array_merge(
		$global_exclude,
		$backup_themes_filter
	);

	if (! $wp_backup->save_folder_inventory(WPBACKITUP__SQL_BULK_INSERT_SIZE,$current_job->getJobId(),WPBackItUp_Job_Item::THEMES,WPBACKITUP__THEMES_ROOT_PATH,$theme_exclude)){
		WPBackItUp_Logger::log_error($backup_logname,$log_function,'Themes Inventory Error.');
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,127);

		write_fatal_error_status('127');
		end_backup(127,false);
		return false;
	};

	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Theme Inventory complete.');

	$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);

	WPBackItUp_Logger::log($backup_logname,'**INVENTORY THEMES**');
	return;

}

if ('task_inventory_uploads'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log( $backup_logname, '**INVENTORY UPLOADS**' );

	$global_exclude = explode(',', WPBACKITUP__BACKUP_GLOBAL_IGNORE_LIST);
	// Getting user filter
	$backup_uploads_filter = explode(',', WPBackitup_Admin::backup_uploads_filter());

	// WARNING
	// Remember these are wildcard searches(FOLDERS ONLY) so any folders containing these values will be excluded from inventory
	// ie. backup and backups will be excluded because "backup" was contained in the array
	// This is a deep filter as well so ANY occurrence of these will be excluded no matter how deep in the tree

	$upload_exclude = array_merge (
		$global_exclude,
		$backup_uploads_filter,
		array(
			"backup",
			"backwpup",
			"updraft",
			"wp-clone",
			"backwpup",
			"backupwordpress",
			"cache",
			"backupcreator",
			"backupbuddy"
		));

	$upload_array = wp_upload_dir();
	$uploads_root_path = $upload_array['basedir'];
	if (! $wp_backup->save_folder_inventory(WPBACKITUP__SQL_BULK_INSERT_SIZE,$current_job->getJobId(),WPBackItUp_Job_Item::UPLOADS,$uploads_root_path,$upload_exclude)){
		WPBackItUp_Logger::log_error($backup_logname,$log_function,'Uploads Inventory Error.');
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,127);

		write_fatal_error_status('127');
		end_backup(127,false);
		return false;
	};

	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Uploads Inventory complete.');
	$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);

	WPBackItUp_Logger::log($backup_logname,'**INVENTORY UPLOADS**');
	return;

}

if ('task_inventory_others'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log( $backup_logname, '**INVENTORY OTHERS**' );

	$global_exclude = explode(',', WPBACKITUP__BACKUP_GLOBAL_IGNORE_LIST);
	// Getting user filter
	$backup_others_filter = explode(',', WPBackitup_Admin::backup_others_filter());

	// WARNING
	// Remember these are wildcard searches(FOLDERS ONLY) so any folders containing these values will be excluded from inventory
	// ie. backup and backups will be excluded because "backup" was contained in the array
	// This is a deep filter as well so ANY occurrence of these will be excluded no matter how deep in the tree

	$other_exclude = array_merge (
		$global_exclude,
		$backup_others_filter,
		array(
			"debug.log",
			"backup",
			"uploads",
			"wpbackitup_backups",
			"wpbackitup_restore",
			"backup",
			"w3tc-config",
			"updraft",
			"wp-clone",
			"backwpup",
			"backupwordpress",
			"cache",
			"backupcreator",
			"backupbuddy",
			"wptouch-data",
			"ai1wm-backups",
			"sedlex",
		));

	if (! $wp_backup->save_folder_inventory(WPBACKITUP__SQL_BULK_INSERT_SIZE,$current_job->getJobId(),WPBackItUp_Job_Item::OTHERS,WPBACKITUP__CONTENT_PATH,$other_exclude,true)){
		WPBackItUp_Logger::log_error($backup_logname,$log_function,'Other Inventory Error.');
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,127);

		write_fatal_error_status('127');
		end_backup(127,false);
		return false;
	};

	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Others Inventory complete.');

	//when others is done then update the task as completed
	//set_status('create_inventory',$complete,false);
	$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);

	WPBackItUp_Logger::log($backup_logname,'**INVENTORY OTHERS**');
	return;

}

//Create the site info file
if ('task_backup_siteinfo'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log($backup_logname,'**SITE INFO**' );

	if ( $wp_backup->create_siteinfo_file($current_job->getJobId())) {

		$source_site_data_root = $wp_backup->getBackupProjectPath();
		$target_site_data_root = 'site-data';

		$file_system = new WPBackItUp_FileSystem($backup_logname);

		// adding wp-config.php to main zip
		$wpconfig_path =  ABSPATH.'wp-config.php';
		copy($wpconfig_path, $source_site_data_root.'wp-config.config');

		//when task is done then update the task as completed
		set_status( 'create_inventory', $complete, false );
		$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);

		WPBackItUp_Logger::log($backup_logname,'**END SITE INFO**' );

	} else {
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,105);

		write_fatal_error_status( '105' );
		end_backup( 105, false );
		return false;
	}

	return;
}


//Backup the database
if ('task_export_db'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log($backup_logname,'**BEGIN DATABASE EXPORT**');
	write_response_processing( "Create database export" );
	set_status( 'exportdb', $active, true );

	$db_mysqldump_path = $current_task->getTaskMetaValue('db_mysqldump_path',false);

	//Default db export type to user setting
	$first_attempt=false;

	//get batch size from user settings
	$batch_size = $WPBackitup->backup_dbtables_batch_size();

	$tables_remaining_count = $wp_backup->export_database_wpbackitup($current_job,WPBackItUp_Job_Item::DATABASE,$batch_size);

	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Database Export Items remaining:' .var_export($tables_remaining_count,true));
	if (false===$tables_remaining_count) {
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,104);
		write_fatal_error_status( '104' );
		end_backup( 104, false );
		return false;
	}else{
		$current_task->setTaskMetaValue('db_mysqldump_path',$db_mysqldump_path);
		if ($tables_remaining_count>0){
			//CONTINUE
			WPBackItUp_Logger::log_info($backup_logname,$log_function,'Continue backing up tables.');
			$current_task->setStatus(WPBackItUp_Job_Task::QUEUED);
		}else{
			//COMPLETE
			WPBackItUp_Logger::log_info($backup_logname,$log_function,'Complete - All tables backed up.');

			//set_status( 'exportdb', $complete, false );
			$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);
			WPBackItUp_Logger::log($backup_logname,'**END DATABASE EXPORT**');
		}
	}

	return;

}

//merge SQL files  into single file
//File for ref only so dont stop on error
if ('task_merge_sql'==$current_task->getTaskName()) {

	WPBackItUp_Logger::log($backup_logname,'**BEGIN SQL MERGE**');

	if (true===$WPBackitup->single_file_db()){
        write_response_processing( "Create single SQL File" );

        // merged sql files to path.
        $toFile_path = sprintf( '%s/%s.db',$wp_backup->getBackupProjectPath(),$backup_name);
        $search_path = $wp_backup->getBackupProjectPath();
        $task = $current_task;
        // Getting SQL merge batch size and convert to integer
        $batch_size = (int) $WPBackitup->backup_sql_merge_batch_size();

        $sql_files_remaining_count = $wp_backup->merge_sql_files_to_path($task, $batch_size, $search_path, $toFile_path, 'sql');

        WPBackItUp_Logger::log_info($backup_logname,$log_function,'SQL Merge Items remaining:' .var_export($sql_files_remaining_count,true));
        if(false===$sql_files_remaining_count){
            $WPBackitup->set_single_file_db(false);//turn off on failure
            WPBackItUp_Logger::log_warning($backup_logname,$log_function, 'Could not merge SQL files');
        }else{
            if($sql_files_remaining_count>0){
                // CONTINUE
                WPBackItUp_Logger::log_info($backup_logname,$log_function,'Continue merging SQL files');
                $current_task->setStatus(WPBackItUp_Job_Task::QUEUED);
            }else{
                //COMPLETE
                WPBackItUp_Logger::log_info($backup_logname,$log_function,'Complete - Merged all SQL files');

                set_status( 'exportdb', $complete, false );
                $current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);
                WPBackItUp_Logger::log($backup_logname,'**END SQL MERGE**');
            }
        }
	}else{
		WPBackItUp_Logger::log_info($backup_logname,$log_function,'SQL Merge option off.');

		// Need set task as complete if merge option off.
        set_status( 'exportdb', $complete, false );
        $current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);
        WPBackItUp_Logger::log($backup_logname,'**END SQL MERGE**');
	}

	return;

}

//TODO: change the db task names to export DB and zip or backup database

//Extract the site info
if ('task_backup_db'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log($backup_logname,'**BACKUP DATABASE**' );
	write_response_processing( "Backup Database" );
	set_status( 'backupdb', $active, true );

		$file_system = new WPBackItUp_FileSystem($backup_logname);

        //Add site Info and SQL data to main zip
        $suffix='main';
        $source_site_data_root = $wp_backup->getBackupProjectPath();
        $target_site_data_root = 'site-data';
        $batch_size = $WPBackitup->backup_sql_batch_size();

        // getting site data files from task meta
		$site_data_files = $current_task->getTaskMetaValue('site_data_files', array());

		// getting db file backup status from task meta.
        $is_merged_db_file_backup_queued = $current_task->getTaskMetaValue('is_merged_db_file_backup_queued', false);

		// getting batch ID from task meta.
        // As we only need one zip file to created for main zip.
        $batch_id = $current_task->getTaskMetaValue('task_backup_db_batch_id', null);

        if(is_null($batch_id)){
            $batch_id = current_time( 'timestamp' );
            $current_task->setTaskMetaValue('task_backup_db_batch_id', $batch_id);
        }

		if(empty($site_data_files)){
            $site_data_files = $file_system->get_fileonly_list($wp_backup->getBackupProjectPath(), 'txt|sql|config|crypt');
            WPBackItUp_Logger::log_info($backup_logname,__METHOD__, 'Main Files Found:' . var_export($site_data_files,true));
        }

        // passing $site_data_files as a reference value
        // counting site data remaining file to backup
		$site_data_remaining_count = $wp_backup->backup_file_list( $source_site_data_root, $target_site_data_root, $suffix, $site_data_files, $batch_size, $batch_id);
        WPBackItUp_Logger::log_info($backup_logname,$log_function,'Site data Items remaining:' .var_export($site_data_remaining_count,true));

		if ( $site_data_remaining_count === false ) {
			$current_task->setStatus(WPBackItUp_Job_Task::ERROR, 135);
			write_fatal_error_status( '135' );
			end_backup( 135, false );
			return false;
		} else {
			if ($site_data_remaining_count>0){
                // CONTINUE
                $current_task->setTaskMetaValue('site_data_files',$site_data_files);

                WPBackItUp_Logger::log_info($backup_logname,$log_function,'Continue backing up site data');
                $current_task->setStatus(WPBackItUp_Job_Task::QUEUED);

			}elseif( false === $is_merged_db_file_backup_queued  && true===$WPBackitup->single_file_db() ){
                // set task meta so that db file backup queued.
                $current_task->setTaskMetaValue('is_merged_db_file_backup_queued', true);

                // Before complete Try to backup .db file or files
                $db_file = $file_system->get_fileonly_list($wp_backup->getBackupProjectPath(), 'db');
                WPBackItUp_Logger::log_info($backup_logname, $log_function, 'Found .db files: '. var_export($db_file, true));

                // Run a pass to backup the .db file or files
                WPBackItUp_Logger::log_info($backup_logname, $log_function, 'Trying to backup db files');
                $current_task->setTaskMetaValue('site_data_files', $db_file);
                $current_task->setStatus(WPBackItUp_Job_Task::QUEUED);

            }else{
			    // COMPLETE

                WPBackItUp_Logger::log_info($backup_logname,$log_function,'Complete - backup all site data files');
				//get rid of the SQL and sitedata file - will check again at end in cleanup
				$wp_backup->cleanup_current_backup_async('txt|sql|db|config|crypt');

				set_status( 'backupdb', $complete, false );
				$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);

				WPBackItUp_Logger::log($backup_logname,'**END SITE INFO**' );
			}
		}

	return;
}


//Backup the themes
if ('task_backup_themes'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log($backup_logname,'**BACKUP THEMES TASK**' );
	write_response_processing( "Backup themes " );
	set_status( 'backup_themes', $active, true );

	$themes_remaining_files_count = $wp_backup->backup_files($current_job->getJobId(),WPBACKITUP__THEMES_ROOT_PATH,WPBackItUp_Job_Item::THEMES);
	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Themes remaining:' .$themes_remaining_files_count);
	if ($themes_remaining_files_count===false) {
		//ERROR
		WPBackItUp_Logger::log_error($backup_logname,$log_function,'Error backing up themes.');
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,120);
		write_fatal_error_status( '120' );
		end_backup( 120, false );
		return false;
	}else{
		if ($themes_remaining_files_count>0){
			//CONTINUE
			WPBackItUp_Logger::log_info($backup_logname,$log_function,'Continue backing up themes.');
			$current_task->setStatus(WPBackItUp_Job_Task::QUEUED);
		}else{
			//COMPLETE
			WPBackItUp_Logger::log_info($backup_logname,$log_function,'Complete - All themes backed up.');

			set_status( 'backup_themes', $complete, false );
			$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);
			WPBackItUp_Logger::log($backup_logname,'**END BACKUP THEMES TASK**');
		}
	}

	return;
}


//Backup the plugins
if ('task_backup_plugins'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log($backup_logname,'**BACKUP PLUGINS TASK**' );
	write_response_processing( "Backup plugins " );
	set_status( 'backup_plugins', $active, true );

	$plugins_remaining_files_count = $wp_backup->backup_files($current_job->getJobId(),WPBACKITUP__PLUGINS_ROOT_PATH,WPBackItUp_Job_Item::PLUGINS);
	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Plugins remaining:' .$plugins_remaining_files_count);
	if ($plugins_remaining_files_count===false) {
		//ERROR
		WPBackItUp_Logger::log_error($backup_logname,$log_function,'Error backing up plugins.');

		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,121);
		write_fatal_error_status( '121' );
		end_backup( 121, false );
		return false;
	} else {
		if ($plugins_remaining_files_count>0){
			//CONTINUE
			WPBackItUp_Logger::log_info($backup_logname,$log_function,'Continue backing up plugins.');
			$current_task->setStatus(WPBackItUp_Job_Task::QUEUED);
		} else{
			//COMPLETE
			WPBackItUp_Logger::log_info($backup_logname,$log_function,'Complete - All plugins backed up.');
			set_status( 'backup_plugins', $complete, false );
			$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);
			WPBackItUp_Logger::log($backup_logname,'**END BACKUP PLUGINS TASK**');
		}
	}

	return;
}

//Backup the uploads
if ('task_backup_uploads'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log($backup_logname,'**BACKUP UPLOADS TASK**' );
	write_response_processing( "Backup uploads " );
	set_status( 'backup_uploads', $active, true );

	$upload_array        = wp_upload_dir();
	$source_uploads_root = $upload_array['basedir'];

	//exclude zip files from backup
	$uploads_remaining_files_count = $wp_backup->backup_files($current_job->getJobId(),$source_uploads_root,WPBackItUp_Job_Item::UPLOADS);
	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Uploads remaining:' .$uploads_remaining_files_count);
	if ( $uploads_remaining_files_count ===false) {
		//ERROR
		WPBackItUp_Logger::log_error($backup_logname,$log_function,'Error backing up uploads.' );
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,122);

		write_fatal_error_status( '122' );
		end_backup( 122, false );
		return false;
	} else {
		if ( $uploads_remaining_files_count > 0 ) {
			//CONTINUE
			WPBackItUp_Logger::log_info($backup_logname,$log_function,'Continue backing up uploads.' );
			$current_task->setStatus(WPBackItUp_Job_Task::QUEUED);

		} else {
			//COMPLETE
			WPBackItUp_Logger::log_info($backup_logname,$log_function,'All uploads backed up.' );
			set_status( 'backup_uploads', $complete, false );
			$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);
			WPBackItUp_Logger::log($backup_logname,'**END BACKUP UPLOADS TASK**' );
		}
	}

	return;
}

//Backup all the other content in the wp-content root
if ('task_backup_other'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log($backup_logname,'**BACKUP OTHER TASK**' );
	write_response_processing( "Backup other files " );
	set_status( 'backup_other', $active, true );

	$others_remaining_files_count = $wp_backup->backup_files($current_job->getJobId(),WPBACKITUP__CONTENT_PATH,WPBackItUp_Job_Item::OTHERS);
	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Others remaining:' .$others_remaining_files_count);
	if ( $others_remaining_files_count ===false) {
		//ERROR
		WPBackItUp_Logger::log_error($backup_logname,$log_function,'Error backing up others.' );
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,123);

		write_fatal_error_status( '123' );
		end_backup( 123, false );
		return false;
	} else {
		if ( $others_remaining_files_count > 0 ) {
			//CONTINUE
			WPBackItUp_Logger::log_info($backup_logname,$log_function,'Continue backing up others.' );
			$current_task->setStatus(WPBackItUp_Job_Task::QUEUED);
		} else {
			//COMPLETE
			WPBackItUp_Logger::log_info($backup_logname,$log_function,'All others backed up.' );

			set_status( 'backup_other', $complete, false );
			$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);
			WPBackItUp_Logger::log($backup_logname,'**END BACKUP OTHER TASK**' );
		}

	}

	return;
}


//Validate the backup IF logging is turned on - reporting only
if ('task_validate_backup'==$current_task->getTaskName()) {
	//Validate the content if logging is on
	WPBackItUp_Logger::log($backup_logname,'**VALIDATE CONTENT**');

	write_response_processing( "Validating Backup " );
	set_status( 'validate_backup', $active, true );

	$set_validate_backup_error = false;
	$set_validate_backup_job_queue = true;
	$db = new WPBackItUp_DataAccess();

	$plugin_validation_meta = $current_task->getTaskMetaValue('task_multistep_validate_plugins');
	WPBackItUp_Logger::log_info( $backup_logname, $log_function, sprintf('plugin validation meta: %s',var_export( $plugin_validation_meta, true )));

	$theme_validation_meta = $current_task->getTaskMetaValue('task_multistep_validate_themes');
	WPBackItUp_Logger::log_info( $backup_logname, $log_function, sprintf('theme validation meta: %s',var_export( $theme_validation_meta, true )));

	$upload_validation_meta = $current_task->getTaskMetaValue('task_multistep_validate_uploads');
	WPBackItUp_Logger::log_info( $backup_logname, $log_function, sprintf('upload validation meta: %s',var_export( $upload_validation_meta, true )));

	$other_validation_meta = $current_task->getTaskMetaValue('task_multistep_validate_others');
	WPBackItUp_Logger::log_info( $backup_logname, $log_function, sprintf('other validation meta: %s',var_export( $other_validation_meta, true )));


	$validation_meta=false;
	$validation_task=false;
	if( $plugin_validation_meta != WPBackItUp_Job_Task::COMPLETE ) {
		$validation_task=WPBackItUp_Job_Item::PLUGINS;
		$validation_meta=$plugin_validation_meta;
	} elseif( $theme_validation_meta != WPBackItUp_Job_Task::COMPLETE ) {
		$validation_task=WPBackItUp_Job_Item::THEMES;
		$validation_meta=$theme_validation_meta;
	} elseif( $upload_validation_meta != WPBackItUp_Job_Task::COMPLETE )  {
		$validation_task=WPBackItUp_Job_Item::UPLOADS;
		$validation_meta=$upload_validation_meta;
	} elseif( $other_validation_meta != WPBackItUp_Job_Task::COMPLETE )  {
		$validation_task=WPBackItUp_Job_Item::OTHERS;
		$validation_meta=$other_validation_meta;
	} else {
		$set_validate_backup_job_queue = false;
	}


    WPBackItUp_Logger::log_info( $backup_logname, $log_function, sprintf('%s validation meta: %s',$validation_task,var_export( $validation_meta, true )));
    if( $validation_meta !==false ) {
		$meta_task = sprintf( 'task_multistep_validate_%s', $validation_task );
		$batch_ids = $db->get_item_batch_ids( $current_job->getJobId(), $validation_task );

		if(!empty($batch_ids)){
			WPBackItUp_Logger::log_info( $backup_logname, $log_function, sprintf('%s Batch Ids: %s',$validation_task,var_export( $batch_ids, true )));

			$array_index = 0;
			if ( is_numeric( $validation_meta ) ) {
				$array_index = intval( $validation_meta );
				$array_index ++;
			}


			if ( array_key_exists( $array_index, $batch_ids ) ) {
                WPBackItUp_Logger::log_info( $backup_logname, $log_function, "Array index: " .var_export($array_index, true));
                $batch_id        = $batch_ids[ $array_index ];//get batch ID
				$validate_result = $wp_backup->validate_backup_files_by_batch_id( $current_job->getJobId(), $validation_task, $batch_id );
				if ( $validate_result === false ) {
					$set_validate_backup_error = true;
				} else {
					$updated_meta = $current_task->setTaskMetaValue( $meta_task, $array_index );

                    WPBackItUp_Logger::log_info( $backup_logname, $log_function, sprintf('Meta %s', $meta_task));
                    if($updated_meta === false){
                        WPBackItUp_Logger::log_error( $backup_logname, $log_function, sprintf('Meta %s update failed', $meta_task));
                    }
					WPBackItUp_Logger::log_info( $backup_logname, $log_function, sprintf('%s Content, Batch ID: %s Validated Successfully!',$validation_task,$batch_id ));

				}
			} else {
				//task is done
				$current_task->setTaskMetaValue( $meta_task, WPBackItUp_Job_Task::COMPLETE );
				WPBackItUp_Logger::log_info( $backup_logname, $log_function, sprintf('%s Content Validated Successfully!',$validation_task));
			}
		} else{
			//task is done
			WPBackItUp_Logger::log_info( $backup_logname, $log_function, sprintf('Task Done! Folder is empty'));
			$current_task->setTaskMetaValue( $meta_task, WPBackItUp_Job_Task::COMPLETE );
			WPBackItUp_Logger::log_info( $backup_logname, $log_function, sprintf('%s Content Validated Successfully!',$validation_task));
		}
	}

	//if error set error message
	if($set_validate_backup_error) {
		//ERROR
		WPBackItUp_Logger::log_error($backup_logname,$log_function,'Content Validation ERROR.' );
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,126);

		write_fatal_error_status( '126' );
		end_backup( 123, false );
		return false;
	} elseif($set_validate_backup_job_queue === false) {

		//set backup status to success
		set_status( 'validate_backup', $complete, false );
		$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);

		WPBackItUp_Logger::log_info($backup_logname,$log_function,'All Content Validated Successfully!' );
		WPBackItUp_Logger::log($backup_logname,'**END VALIDATE CONTENT**' );

	} elseif ($set_validate_backup_job_queue === true) {
		$current_task->setStatus(WPBackItUp_Job_Task::QUEUED);
	}

	return;
}


//Create the manifest and add to main zip - part of validation step
if ('task_create_manifest'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log( $backup_logname, '**CREATE BACKUP MANIFEST**' );

	write_response_processing( "Create Backup Manifest" );
	set_status( 'validate_backup', $active, true );

	WPBackItUp_Logger::log_info( $backup_logname, $log_function, 'Create Backup Set? ' . var_export( $WPBackitup->single_file_backupset(), true ) );

	//Generate manifest
	if ( ! $wp_backup->create_backup_manifest() ) {
		$current_task->setStatus( WPBackItUp_Job_Task::ERROR, 109 );

		write_fatal_error_status( '109' );
		end_backup( 109, false );

		return false;
	}

	set_status( 'validate_backup', $complete, false );
	$current_task->setStatus( WPBackItUp_Job_Task::COMPLETE );

	return;

}


//ENCRYPT FILES HERE
if ('task_encrypt_files'==$current_task->getTaskName()) {

	WPBackItUp_Logger::log($backup_logname,'**ENCRYPT SENSITIVE FILES**');
	write_response_processing( "Encrypt Sensitive Files" );
	set_status( 'encrypt', $active, true );

	if (true===$WPBackitup->encrypt_files()){

		$file_list= glob($wp_backup->getBackupProjectPath() .'*-main-[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]*.zip' );

		if (count($file_list) != 1){
			WPBackItUp_Logger::log_error($backup_logname,$log_function, 'More than 1 main File found:' . var_export( $file_list,true));

			$current_task->setStatus(WPBackItUp_Job_Task::ERROR,130);

			write_fatal_error_status( '130' );
			end_backup( 130, false );
			return false;
		}

		$main_file = current($file_list); //get the main file
		try {
			//$passkey    = $WPBackitup->license_key() . $current_job->getJobId();
			$encryption = new WPBackItUp_Encryption( $passkey );

			//Encrypt file
			$encrypted= $encryption->encrypt_file($main_file);

		} catch (Exception $e){
			WPBackItUp_Logger::log_error($backup_logname,$log_function, 'Encryption Error:' .  $e);
			$encrypted=false;
		}

		if ( false === $encrypted){
			WPBackItUp_Logger::log_error($backup_logname,$log_function, 'Could not encrypt file:' . var_export( $main_file,true));

			$current_task->setStatus(WPBackItUp_Job_Task::ERROR,130);

			write_fatal_error_status( '130' );
			end_backup( 130, false );
			return false;

		};

	} else{
		set_status( 'finalize_backup', $active, true );  //start the spinner
		WPBackItUp_Logger::log_info($backup_logname,$log_function,'Encryption option off.');
	}

	set_status( 'encrypt', $complete, false );
	$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);
	WPBackItUp_Logger::log($backup_logname,'**END ENCRYPT SENSITIVE FILES**');

	return;
}


//Everything below this line is part of finalizing backup


//Create the backup set
if ('task_create_backupset'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log($backup_logname,'**CREATE BACKUP SET**' );

	write_response_processing( "Create Backup Set" );
	set_status( 'finalize_backup', $active, true );

	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Create Backup Set? '.var_export($WPBackitup->single_file_backupset(),true));
	if (false===$WPBackitup->single_file_backupset()){
		$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);
		return;
	}

	//Has the backup set file name been generated
	$backup_set_zip = $current_task->getTaskMetaValue('backup_set_zip');
	$backup_zip_files = $current_task->getTaskMetaValue('backup_zip_files');

	//If null then this is the first pass so save the job/task meta info
	if (empty($backup_set_zip)){

		//Generate backup set name & path
		$backup_set_zip = sprintf('%s%s-%s-%s.zip',$wp_backup->getBackupProjectPath(),$current_job->getJobName(),'backupset',current_time( 'timestamp' ));

		//save the backup set name to the task meta
		$current_task->setTaskMetaValue('backup_set_zip',$backup_set_zip);

		//Take an inventory of the zip files created
		$file_system = new WPBackItUp_FileSystem($backup_logname);
		$backup_zip_files = $file_system->get_fileonly_list_with_filesize($wp_backup->getBackupProjectPath(), 'zip|crypt');

		//save them to the current task
		$current_task->setTaskMetaValue('backup_zip_files',$backup_zip_files);
	}

	//Log backup set info
	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Backup Set Name:'.$backup_set_zip );
	WPBackItUp_Logger::log_info($backup_logname,$log_function,'Backup Files:'.var_export($backup_zip_files,true));

	//when is empty then all the files are in the backup set
	if (! empty($backup_zip_files) && is_array($backup_zip_files)) {

		//Get zip from top of list
		$zip_file = key($backup_zip_files);
		WPBackItUp_Logger::log_info($backup_logname,$log_function,'Add File to backup set:'.$zip_file);

		//add file to backupset zip
		if (true===$wp_backup->backup_file_to_zip( $wp_backup->getBackupProjectPath(), 'backups', $zip_file,$backup_set_zip )){
			//remove file from list
			unset($backup_zip_files[$zip_file]);
			WPBackItUp_Logger::log_info($backup_logname,$log_function,'File added to backup set successfully.' );

		} else{

			//error occurred so stop trying and move on to next step
			//This is not a fatal error BUT will result in no backup set
			WPBackItUp_Logger::log_error($backup_logname,$log_function,'Unable to create backup set.');
			$backup_zip_files=null;

			//remove the backup set if exists
			$file_system = new WPBackItUp_FileSystem($backup_logname);
			$file_system->delete_files(array($backup_set_zip));

			//turn off backup set setting - should we use a counter?
			$WPBackitup->set_single_file_backupset(false);
		}

		//update task meta
		$current_task->setTaskMetaValue('backup_zip_files',$backup_zip_files);

	}

	//are there any files left?
	if (is_array($backup_zip_files) && count($backup_zip_files)>0){
		//CONTINUE
		WPBackItUp_Logger::log_info($backup_logname,$log_function,'Continue adding backups to set' );
		$current_task->setStatus(WPBackItUp_Job_Task::QUEUED);
	}else{
		//COMPLETE
		WPBackItUp_Logger::log_info($backup_logname,$log_function,'All others backed up.' );

		$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);
		WPBackItUp_Logger::log($backup_logname,'**END CREATE BACKUP SET**' );

	}

	return;
}



//Zip up the backup folder
if ('task_finalize_backup'==$current_task->getTaskName()) {
	WPBackItUp_Logger::log($backup_logname,'**FINALIZE BACKUP**' );

	write_response_processing( "Compress Backup " );
	set_status( 'finalize_backup', $active, true );	

	//Rename backup folder
	if ( ! $wp_backup->rename_backup_folder()) {
		$current_task->setStatus(WPBackItUp_Job_Task::ERROR,109);

		//cleanup the manifest and sql files from root
		if ( ! $wp_backup->cleanup_current_backup_async('txt|sql|db|config')) {
			//Warning - no need to error job
			write_warning_status( '106' );
			WPBackItUp_Logger::log_warning($backup_logname,$log_function,'Cleanup could not be dispatched.');
		}

		write_fatal_error_status( '109' );
		end_backup( 109, false );
		return false;
	}

	//Take an inventory of the zip files created

	$file_system = new WPBackItUp_FileSystem($backup_logname);
	$zip_files = $file_system->get_fileonly_list_with_filesize($wp_backup->getBackupProjectPath(), 'zip|crypt');

	$wp_backup->save_file_list_inventory(WPBACKITUP__SQL_BULK_INSERT_SIZE,$current_job->getJobId(),WPBackItUp_Job_Item::BACKUPS,$wp_backup->getBackupProjectPath(),$zip_files);

	$current_job->setJobMetaValue('backup_zip_files' , $zip_files ); //list of zip files

	set_status( 'finalize_backup', $complete, false );
	$current_task->setStatus(WPBackItUp_Job_Task::COMPLETE);

	WPBackItUp_Logger::log($backup_logname,'**END FINALIZE BACKUP**' );

	//If we get this far we have a finalized backup so change the path

	$WPBackitup->increment_successful_backup_count();

	//cleanup the manifest and sql files from root
	if (  $wp_backup->cleanup_current_backup_async('txt|sql|db|config')  ) {
		//Warning - no need to error job
		write_warning_status( '106' );
		WPBackItUp_Logger::log_warning($backup_logname,$log_function,'Cleanup could not be dispatched.');
	}

	//If complete then no post backup tasks
	if ($current_job->is_job_complete()) {
		//SUCCESS- End Job!
		//write response file first to make sure it is there
		write_response_file_success();
		set_status_success();


		//Remove supporting zip files if backup set successful
		$remove_supporting_zip_files = $WPBackitup->is_remove_supporting_zip_files();
		if ( true === $remove_supporting_zip_files){
			WPBackItUp_Logger::log_info($backup_logname,$log_function,'Cleanup supporting zips.');
			
			//do not fail on error.
			if(false===$wp_backup->remove_supporting_zips_async($zip_files)){
				WPBackItUp_Logger::log_warning($backup_logname,$log_function,'Supporting zip files NOT deleted.');
           }

		}

		//Send the files over to safe sync to be uploaded.
		if (false===$wp_backup->queue_safe_job_sync($current_job->getJobId(),$WPBackitup->single_file_backupset())){
			WPBackItUp_Logger::log_warning($backup_logname,$log_function,'Error with queue safe sync job.');
		}

		end_backup( null, true );
		return true;
	}else{
		return;
	}
}

//RUN POST BACKUP TASKS
//error_log('TASK:'.$current_task->getTaskName());

$wp_backup->set_final_backup_path($current_job->getBackupFolderPath());

//If not complete then we must have some post backup tasks
$post_backup_task = sprintf("%s_%s",WPBACKITUP__NAMESPACE,$current_task->getTaskName());
//error_log('Run Task:'.$post_backup_task);

//task will be updated in call
do_action($post_backup_task,$current_job);


if ($current_job->is_job_complete()) {
	//SUCCESS- End Job!

	//write response file first to make sure it is there
	write_response_file_success();
	set_status_success();

	end_backup( null, true );
	return true;
}else{
	//If not complete then post backup tasks available
	return;
}


exit();


/******************/
/*** Functions ***/
/******************/

function end_backup($err=null, $success=null){
    global $WPBackitup,$wp_backup,$backup_logname,$current_job;
	WPBackItUp_Logger::log_info($backup_logname,__METHOD__,'Begin');


	//Cleanup TMP folder on error - will dispatch before changing path below
	if (! $success) {
		WPBackItUp_Admin_Bar::backup_error();
		WPBackItUp_Logger::log_warning($backup_logname,__METHOD__,'Cleanup on backup error.');
		//cleanup the manifest and sql files from root
		if (  $wp_backup->cleanup_current_backup_async('txt|sql|db|config')  ) {
			//Warning - no need to error job
			WPBackItUp_Logger::log_warning($backup_logname,__METHOD__,'Cleanup on backup error could not be dispatched.');
		}
	}else {
		WPBackItUp_Admin_Bar::backup_success();
	}

	$wp_backup->set_final_backup_path($current_job->getBackupFolderPath());

	WPBackItUp_Logger::log_info($backup_logname,__METHOD__,'Zip up all the logs.');
	//Zip up all the logs in the log folder
	$logs_path = WPBACKITUP__PLUGIN_PATH .'logs';
	$zip_file_path = $wp_backup->getBackupProjectPath() .'logs_' .$current_job->getJobId() . '.zip';

	//copy WP debug file
	$wpdebug_file_path = WPBACKITUP__CONTENT_PATH . '/debug.log';
	WPBackItUp_Logger::log_info($backup_logname,__METHOD__,'Save WP Debug: ' .$wpdebug_file_path);
	if (file_exists($wpdebug_file_path)) {
		$debug_log = sprintf('%s/wpdebug_%s.log',$logs_path,$current_job->getJobId());
		copy( $wpdebug_file_path, $debug_log );
		WPBackItUp_Logger::log_info($backup_logname,__METHOD__,'WP Debug file saved: ' .$debug_log);
	}else{
		WPBackItUp_Logger::log_info($backup_logname,__METHOD__,'NO WP Debug file: ' .$wpdebug_file_path);
	}

	$zip = new WPBackItUp_Zip($backup_logname,$zip_file_path);
	$zip->zip_log_files_in_folder($logs_path,$current_job->getJobId());
	$zip->close();

	WPBackItUp_Backup::end(); //Release the lock
	$current_datetime = current_time( 'timestamp' );
	// updating lastrun_date
    $updated = $WPBackitup->set_option('backup_lastrun_date', $current_datetime);
    $debug_set_options_logname='set_options_debug';
    WPBackItUp_Logger::log_info($debug_set_options_logname,__METHOD__, 'Option backup_lastrun_date'. ' update status: ' . $updated );


    $util = new WPBackItUp_Utility($backup_logname);
    $seconds = $util->timestamp_diff_seconds($current_job->getJobStartTimeTimeStamp(),$current_job->getJobEndTimeTimeStamp());

    $processing_minutes = round($seconds / 60);
    $processing_seconds = $seconds % 60;

	WPBackItUp_Logger::log_info($backup_logname,__METHOD__,'Script Processing Time:' .$processing_minutes .' Minutes ' .$processing_seconds .' Seconds');

    if (true===$success) WPBackItUp_Logger::log_info($backup_logname,__METHOD__,'Backup completed: SUCCESS');
	if (false===$success) WPBackItUp_Logger::log_info($backup_logname,__METHOD__,'Backup completed: ERROR');

	WPBackItUp_Logger::log($backup_logname,'*** END BACKUP ***');

	//Send Notification email
	WPBackItUp_Logger::log_info($backup_logname,__METHOD__,'Send Email notification');
	$logs_attachment = array( $zip_file_path  );
	send_backup_notification_email($err, $success,$logs_attachment);

    $logFileName = WPBackItUp_Logger::getLogFileName($backup_logname);
    $logFilePath = WPBackItUp_Logger::getLogFilePath($backup_logname);

    //COPY the log if it exists
    $newlogFilePath = $wp_backup->getBackupProjectPath() .$logFileName;
    if (null!=$success && file_exists($logFilePath)){
	    copy($logFilePath,$newlogFilePath);
    }

	WPBackItUp_Logger::close($backup_logname);
    echo('Backup has completed');
}

function send_backup_notification_email($err, $success,$logs=array()) {
	global $WPBackitup, $wp_backup, $backup_logname,$status_array,$current_job;
	WPBackItUp_Logger::log_info($backup_logname,__METHOD__,'Begin');

	$start_timestamp = $current_job->getJobStartTimeTimeStamp();
	$end_timestamp = $current_job->getJobEndTimeTimeStamp();

    $utility = new WPBackItUp_Utility($backup_logname);
    $seconds = $utility->timestamp_diff_seconds($start_timestamp,$end_timestamp);

    $processing_minutes = round($seconds / 60);
    $processing_seconds = $seconds % 60;

	$site_id = WPBackitup_Settings::get_site_id();
	$footer = sprintf('<img src="https://www.google-analytics.com/collect?v=1&t=event&tid=UA-41130863-2&cid=%s',$site_id);
	$footer .='&ec=email&ea=open&el=backup_notification&dp=%2Femail%2Fplugin&dt=backup%20notification" />' ;

    $message="";
	$message.="<p><img alt='WPBackItUp Logo' src='http://cdn.wpbackitup.com/images/wpbackitup_logo.png' />&nbsp; &nbsp;<strong style='color:#005d8b; font-family:cambria,georgia,serif; font-size:14px; font-style:normal; font-weight:bold'>The Simplest Way to Backup Your WordPress Site</strong></p><br><br>";
	$domain = parse_url( get_home_url(), PHP_URL_HOST );

	if($success)
	{
		//Don't send logs on success unless debug is on.
		if (WPBACKITUP__DEBUG!==true){
			$logs=array();
		}

        $subject = sprintf(__('%s - Backup completed successfully.', 'wp-backitup'), $domain);
        $message .= '<b>' . __('Your backup completed successfully.', 'wp-backitup') . '</b><br/><br/>';

    } else  {
        $subject = sprintf(__('%s - Backup did not complete successfully.', 'wp-backitup'),$domain);
        $message .= '<b>' . __('Your backup did not complete successfully.', 'wp-backitup') . '</b><br/><br/>';
    }


	$local_start_datetime = get_date_from_gmt(date( 'Y-m-d H:i:s',$start_timestamp));
	$local_end_datetime = get_date_from_gmt(date( 'Y-m-d H:i:s',$end_timestamp));

	$message .= sprintf(__('WordPress Site: <a href="%s" target="_blank"> %s </a><br/>', 'wp-backitup'), home_url(), $domain);
    $message .= __('Backup date:', 'wp-backitup') . ' ' . $local_start_datetime . '<br/>';
	$message .= __('Number of backups completed with WPBackItUp:', 'wp-backitup') . ' ' . $WPBackitup->backup_count() . '<br/>';

	$message .= __('Completion Code:', 'wp-backitup') . ' ' . $current_job->getJobId() .'-'. $processing_minutes .'-' .$processing_seconds .'<br/>';
	$message .= __('WPBackItUp Version:', 'wp-backitup') . ' '  . WPBACKITUP__VERSION . '<br/>';
    $message .= '<br/>';

	//Add the completed steps on success
	if(! $success) {
		//Error occurred
        $message .= '<br/>';
        $message .= 'Errors:<br/>' . get_error_message($err);
	}

    $term='success';
    if(!$success)$term='error';
      $message .='<br/><br/>' . sprintf(__('Checkout %s for info about WPBackItUp and our other products.', 'wp-backitup'), WPBackItUp_Utility::get_anchor_with_utm('www.wpbackitup.com', '', 'notification+email', $term) ) . '<br/>';


	$message.=$footer;

	$notification_email = $WPBackitup->get_option('notification_email');
	if($notification_email)
		$utility->send_email($notification_email,$subject,$message,$logs);

	WPBackItUp_Logger::log_info($backup_logname,__METHOD__,'End');
}

function write_fatal_error_status($status_code) {
	global $status_array,$active,$failure;
	
	//Find the active status and set to failure
	foreach ($status_array as $key => $value) {
		if ($value==$active){
			$status_array[$key]=$failure;	
		}
	}

	write_status();
	write_response_file_error($status_code);
}

function write_warning_status($status_code) {
	global $status_array,$active,$warning;

	//Find the active status and set to failure
	foreach ($status_array as $key => $value) {
		if ($value==$active){
			$status_array[$key]=$warning;
		}
	}

	write_status();
}

//function write_warning_status($status_code) {
//	global $status_array,$warning;
//
//	//Add warning to array
//	$status_array[$status_code]=$warning;
//	write_status();
//}

function write_status() {
	global $status_array;
	$fh=getStatusLog();

	foreach ($status_array as $key => $value) {
		fwrite($fh, '<div class="' . $key . '">' . $value .'</div>');		
	}

	fclose($fh);
}

function set_status($process,$status,$flush){
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

	if ($flush) write_status(); 
}

function set_status_success(){
	global $status_array,$complete,$success;

	//Mark all the others complete and flush
	foreach ($status_array as $key => $value) {
		$status_array[$key]=$complete;
	}

	$status_array['finalinfo']=$success;
	write_status();
}

//Get Status Log
function getStatusLog(){
	global $backup_logname;

	$status_file_path = WPBACKITUP__PLUGIN_PATH .'/logs/backup_status.log';
	$filesystem = new WPBackItUp_FileSystem($backup_logname);
	return $filesystem->get_file_handle($status_file_path);

}

//write Response Log
function write_response_processing($message) {

    $jsonResponse = new stdClass();
	$jsonResponse->backupStatus = 'processing';
    $jsonResponse->backupMessage = $message;

	write_response_file($jsonResponse);
}


//write Response Log
function write_response_file_error($error) {

	$jsonResponse = new stdClass();
	$jsonResponse->backupStatus = 'error';
	$jsonResponse->backupMessage = get_error_message($error);

	write_response_file($jsonResponse);
}

//write Response Log
function write_response_file_success() {
    global $wp_backup,$backup_logname, $current_job;

    $jsonResponse = new stdClass();
	$jsonResponse->backupStatus = 'success';
    $jsonResponse->backupMessage = 'success';
    $jsonResponse->backupName = $wp_backup->backup_name;
    $jsonResponse->backupRetained = $wp_backup->backup_retained_number;
    $jsonResponse->backupRuntype = $current_job->getJobRunType();
    $jsonResponse->backupDate = $current_job->getJobDate();
    $jsonResponse->backupDuration = $current_job->getJobDurationFormatted();

	$jsonResponse->logFileExists = file_exists(WPBackItUp_Logger::getLogFilePath($backup_logname));

	write_response_file($jsonResponse);
}

//write Response Log
function write_response_file($JSON_Response) {
	global $backup_logname;

	$json_response = json_encode($JSON_Response);
	WPBackItUp_Logger::log_info($backup_logname,__METHOD__,'Write response file:' . $json_response);

	$fh=get_response_file();
	fwrite($fh, $json_response);
	fclose($fh);
}

//Get Response Log
function get_response_file() {
    global $backup_logname;

    $response_file_path = WPBACKITUP__PLUGIN_PATH .'logs/backup_response.log';
    $filesytem = new WPBackItUp_FileSystem($backup_logname);
    return $filesytem->get_file_handle($response_file_path,false);
}


/**
 * Get error message
 *
 * @param $error_code
 *
 * @return string
 */
function get_error_message($error_code){

	$error_message_array = array(
		'101' => __('(101) Unable to create a new directory for backup. Please check your CHMOD settings of your wp-backitup backup directory', 'wp-backitup'),
		'102'=> __('(102) Cannot create backup directory. Please check the CHMOD settings of your wp-backitup plugin directory', 'wp-backitup'),
		'103'=> __('(103) Unable to backup your files. Please try again', 'wp-backitup'),
		'104'=> __('(104) Unable to export your database. Please try again', 'wp-backitup'),
		'105'=> __('(105) Unable to export site information file. Please try again', 'wp-backitup'),
		'106'=> __('(106) Unable to cleanup your backup directory', 'wp-backitup'),
		'107'=> __('(107) Unable to compress(zip) your backup. Please try again', 'wp-backitup'),
		'108'=> __('(108) Unable to backup your site data files. Please try again', 'wp-backitup'),
		'109'=> __('(109) Unable to finalize backup. Please try again', 'wp-backitup'),
		'110'=> __('(110) Unable to create backup manifest. Please try again', 'wp-backitup'),
		'114'=> __('(114) Your database was accessible but an export could not be created. Please contact support by clicking the get support link on the right. Please let us know who your host is when you submit the request', 'wp-backitup'),
		'120'=> __('(120) Unable to backup your themes. Please try again', 'wp-backitup'),
		'121'=> __('(121) Unable to backup your plugins. Please try again', 'wp-backitup'),
		'122'=> __('(122) Unable to backup your uploads. Please try again', 'wp-backitup'),
		'123'=> __('(123) Unable to backup your miscellaneous files. Please try again', 'wp-backitup'),
		'125'=> __('(125) Unable to compress your backup because there is no zip utility available.  Please contact support', 'wp-backitup'),
		'126'=> __('(126) Unable to validate your backup. Please try again', 'wp-backitup'),
		'127'=> __('(127) Unable to create inventory of files to backup. Please try again', 'wp-backitup'),
		'128'=> __('(128) Unable to create job control record. Please try again', 'wp-backitup'),
		'130'=> __('(130) Unable to encrypt sensitive files. Please try again and contact support if this issue continues.', 'wp-backitup'),
		'135'=> __('(135) Unable to backup your database. Please try again', 'wp-backitup'),

		'2101' => __('(2101) Unable to create a new directory for backup. Please check your CHMOD settings of your wp-backitup backup directory', 'wp-backitup'),
		'2102'=> __('(2102) Cannot create backup directory. Please check the CHMOD settings of your wp-backitup plugin directory', 'wp-backitup'),
		'2103'=> __('(2103) Unable to backup your files. Please try again', 'wp-backitup'),
		'2104'=> __('(2104) Unable to export your database. Please try again', 'wp-backitup'),
		'2105'=> __('(2105) Unable to export site information file. Please try again', 'wp-backitup'),
		'2106'=> __('(2106) Unable to cleanup your backup directory', 'wp-backitup'),
		'2107'=> __('(2107) Unable to compress(zip) your backup. Please try again', 'wp-backitup'),
		'2108'=> __('(2108) Unable to backup your site data files. Please try again', 'wp-backitup'),
		'2109'=> __('(2109) Unable to finalize backup. Please try again', 'wp-backitup'),
		'2110'=> __('(2110) Unable to create backup manifest. Please try again', 'wp-backitup'),
		'2114'=> __('(2114) Your database was accessible but an export could not be created. Please contact support by clicking the get support link on the right. Please let us know who your host is when you submit the request', 'wp-backitup'),
		'2120'=> __('(2120) Unable to backup your themes. Please try again', 'wp-backitup'),
		'2121'=> __('(2121) Unable to backup your plugins. Please try again', 'wp-backitup'),
		'2122'=> __('(2122) Unable to backup your uploads. Please try again', 'wp-backitup'),
		'2123'=> __('(2123) Unable to backup your miscellaneous files. Please try again', 'wp-backitup'),
		'2125'=> __('(2125) Unable to compress your backup because there is no zip utility available.  Please contact support', 'wp-backitup'),
		'2126'=> __('(2126) Unable to validate your backup. Please try again', 'wp-backitup'),
		'2127'=> __('(2127) Unable to create inventory of files to backup. Please try again', 'wp-backitup'),
		'2128'=> __('(2128) Unable to create job control record. Please try again', 'wp-backitup'),
		'2130'=> __('(2130) Unable to encrypt sensitive files. Please try again and contact support if this issue continues.', 'wp-backitup'),
		'2135'=> __('(2135) Unable to backup your database. Please try again', 'wp-backitup'),
	);

	$error_message = __('(999) Unexpected error', 'wp-backitup');
	if (array_key_exists($error_code,$error_message_array)) {
		$error_message = $error_message_array[ $error_code ];
	}

	return $error_message;
}
