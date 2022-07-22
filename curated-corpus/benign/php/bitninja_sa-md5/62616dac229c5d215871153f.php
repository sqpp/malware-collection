<?php
if (!empty($_GET['pg']) && isset($wpdb) && $_GET['pg']=='add-store') { include_once(SSF_WP_INCLUDES_PATH."/top-nav.php"); print "<script>jQuery('.locations').removeAttr('id');</script>"; } 

if (!isset($wpdb)){ include("../../../../wp-load.php"); }
if (!defined("SSF_WP_INCLUDES_PATH")) { include("../ssf-wp-define.php"); }
if (!function_exists("ssf_wp_initialize_variables")) { include("../ssf-wp-functions.php"); }
if (defined('SSF_WP_ADDONS_PLATFORM_FILE') && file_exists(SSF_WP_ADDONS_PLATFORM_FILE)) { include_once(SSF_WP_ADDONS_PLATFORM_FILE); } //check if this inclusion is actually necessary here anymore - 3/19/14

print "<div class='wrap'>";
/*print "<h2>".__("Add Locations", SSF_WP_TEXT_DOMAIN)."</h2><br>";*/

global $wpdb, $ssf_wp_vars;
ssf_wp_initialize_variables();
//Inserting addresses by manual input
if (!empty($_POST['ssf_wp_store']) && (empty($_GET['mode']) || $_GET['mode']!="pca")) {
	if (!empty($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], "add-location_single")){
		ssf_wp_add_location();
		print "<div class='ssf_wp_admin_success'>".__(" Store successfully added",SSF_WP_TEXT_DOMAIN).". $view_link</div> <!--meta http-equiv='refresh' content='0'-->"; 
	} else {
		print "<div class='ssf-wp-menu-alert'>".__(" Store failed to be added to the database.",SSF_WP_TEXT_DOMAIN)."</div>"; 
	}
}

//Importing addresses from an local or remote database
if (!empty($_POST['remote']) && trim($_POST['query'])!="" || !empty($_POST['finish_import'])) {
	
	if (!empty($_POST['server']) && preg_match("@.*\..{2,}@", $_POST['server'])) {
		include(SSF_WP_ADDONS_PATH."/db-importer/remoteConnect.php");
	} else {
		if (file_exists(SSF_WP_ADDONS_PATH."/db-importer/localImport.php")) {
			include(SSF_WP_ADDONS_PATH."/db-importer/localImport.php");
		} elseif (file_exists(SSF_WP_ADDONS_PATH."/csv-xml-importer-exporter/localImport.php")) {
			include(SSF_WP_ADDONS_PATH."/csv-xml-importer-exporter/csv-xml-importer-exporter.php");
			include(SSF_WP_ADDONS_PATH."/csv-xml-importer-exporter/localImport.php");
		}
	}
	//for intermediate step match column data to field headers
	if (empty($_POST['finish_import']) || $_POST['finish_import']!="1") {exit();}
}

//Importing CSV file of addresses
$newfile="temp-file.csv"; 
//$root=plugin_dir_path(__FILE__); //dirname(plugin_basename(__FILE__)); die($root);
$root=SSF_WP_ADDONS_PATH;
$target_path="$root/";
//print_r($_FILES);
if (!empty($_FILES['csv_import']['tmp_name']) && move_uploaded_file($_FILES['csv_import']['tmp_name'], "$root/$newfile") && file_exists(SSF_WP_ADDONS_PATH."/csv-xml-importer-exporter/csvImport.php")) {
	include(SSF_WP_ADDONS_PATH."/csv-xml-importer-exporter/csvImport.php");
}
else{
		//echo "<div style='background-color: rgb(255, 124, 86); padding:5px'>There was an error uploading the file, please try again. </div>";
}

//If adding via the Point, Click, Add map (accepting AJAX)
if (!empty($_REQUEST['mode']) && $_REQUEST['mode']=="pca") {
	include(SSF_WP_ADDONS_PATH."/point-click-add/pcaImport.php");
}

print ssf_wp_location_form("add");

function csv_importer(){
	global $ssf_wp_uploads_path, $ssf_wp_path, $text_domain, $web_domain;
	if (file_exists(SSF_WP_ADDONS_PATH."/csv-xml-importer-exporter/csv-import-form.php")) {
		include(SSF_WP_ADDONS_PATH."/csv-xml-importer-exporter/csv-import-form.php");
		print "<br>";
	}
}
function db_importer(){
	global $ssf_wp_uploads_path, $ssf_wp_path, $text_domain, $web_domain;
	if (file_exists(SSF_WP_ADDONS_PATH."/db-importer/db-import-form.php")) {
		//include(SSF_WP_INCLUDES_PATH."/ssf-wp-env.php");
		include(SSF_WP_ADDONS_PATH."/db-importer/db-import-form.php");
	}
}
function point_click_add(){
	global $ssf_wp_uploads_path, $ssf_wp_path, $text_domain, $web_domain;
	if (file_exists(SSF_WP_ADDONS_PATH."/point-click-add/point-click-add-form.php")) {
		include(SSF_WP_ADDONS_PATH."/point-click-add/point-click-add-form.php");
	}
}
function ssf_wp_csv_db_pca_forms(){
  if (file_exists(SSF_WP_ADDONS_PATH."/db-importer/db-import-form.php") || file_exists(SSF_WP_ADDONS_PATH."/point-click-add/point-click-add-form.php") || file_exists(SSF_WP_ADDONS_PATH."/csv-xml-importer-exporter/csv-import-form.php")) {	
	print "<table><tr>";
	if (file_exists(SSF_WP_ADDONS_PATH."/csv-xml-importer-exporter/csv-import-form.php") || file_exists(SSF_WP_ADDONS_PATH."/db-importer/db-import-form.php")) {
		print "<td style='vertical-align:top; padding-top:0px'>";
		csv_importer();
		db_importer();
		print "</td>";
	}
	if (file_exists(SSF_WP_ADDONS_PATH."/point-click-add/point-click-add-form.php")) {
		print "<td style='vertical-align:top; padding-top:0px'>";
		point_click_add();
		print "</td>";
	}	
		print "</tr></table>";
  }
}
if (function_exists("addto_ssf_wp_hook")) {
	addto_ssf_wp_hook('ssf_wp_add_location_forms', 'csv_importer','','','csv-xml-importer-exporter');
	addto_ssf_wp_hook('ssf_wp_add_location_forms', 'db_importer','','','db-importer');
	addto_ssf_wp_hook('ssf_wp_add_location_forms', 'point_click_add','','','point-click-add');
} else {
	ssf_wp_csv_db_pca_forms();
}

if (function_exists("do_ssf_wp_hook")) {do_ssf_wp_hook('ssf_wp_add_location_forms', 'select-top');}



print "
</div>";

include(SSF_WP_INCLUDES_PATH."/ssf-wp-footer.php");
?>
