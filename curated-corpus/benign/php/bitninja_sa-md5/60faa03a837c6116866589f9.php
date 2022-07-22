<?php
if (!empty($_GET['pg']) && isset($wpdb) && $_GET['pg']=='add-locations') { include_once(SL_INCLUDES_PATH."/top-nav.php"); }

if (!isset($wpdb)){ include("../../../../wp-load.php"); }
if (!defined("SL_INCLUDES_PATH")) { include("../sl-define.php"); }
if (!function_exists("sl_initialize_variables")) { include("../sl-functions.php"); }
if (defined('SL_ADDONS_PLATFORM_FILE') && file_exists(SL_ADDONS_PLATFORM_FILE)) { include_once(SL_ADDONS_PLATFORM_FILE); } //check if this inclusion is actually necessary here anymore - 3/19/14

print "<div class='wrap'>";
/*print "<h2>".__("Add Locations", "store-locator")."</h2><br>";*/

global $wpdb;
sl_initialize_variables();

//Inserting addresses by manual input
if (!empty($_POST['sl_store']) && (empty($_GET['mode']) || $_GET['mode']!="pca")) {
	if (!empty($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], "add-location_single")){
		sl_add_location();
		print "<div class='sl_admin_success'>".__("Successful Addition", "store-locator").". $view_link</div> <!--meta http-equiv='refresh' content='0'-->"; 
	} else {
		print "<div class='sl_admin_warning'>".__("Unsucessful addition due to security check failure", "store-locator").". $view_link</div>"; 
	}
}

//Importing addresses from an local or remote database | 2/28/16 - v3.96: or importing from CSV 
if (!empty($_POST['remote']) && trim($_POST['query'])!="" || !empty($_POST['finish_import'])) {
	
	if (!empty($_POST['server']) && preg_match("@.*\..{2,}@", $_POST['server'])) {
		include(SL_ADDONS_PATH."/db-importer/remoteConnect.php");
	} else {
		if (file_exists(SL_ADDONS_PATH."/db-importer/localImport.php")) {
			include(SL_ADDONS_PATH."/db-importer/localImport.php");
		} elseif (file_exists(SL_ADDONS_PATH."/csv-xml-importer-exporter/localImport.php")) {
			include(SL_ADDONS_PATH."/csv-xml-importer-exporter/csv-xml-importer-exporter.php");
			include(SL_ADDONS_PATH."/csv-xml-importer-exporter/localImport.php");
		}
	}
	//for intermediate step match column data to field headers
	if (empty($_POST['finish_import']) || $_POST['finish_import']!="1") {exit();}
}

//Importing CSV file of addresses
$newfile="temp-file.".date("YmdHis").".csv"; 
//$root=plugin_dir_path(__FILE__); //dirname(plugin_basename(__FILE__)); die($root);
$root=SL_ADDONS_PATH;
$target_path="$root/";
//print_r($_FILES);
if (!empty($_FILES['csv_import']['tmp_name']) && move_uploaded_file($_FILES['csv_import']['tmp_name'], "$root/$newfile") && file_exists(SL_ADDONS_PATH."/csv-xml-importer-exporter/csvImport.php")) {
	include(SL_ADDONS_PATH."/csv-xml-importer-exporter/csvImport.php");
}
else{
		//echo "<div style='background-color:salmon; padding:5px'>There was an error uploading the file, please try again. </div>";
}

//If adding via the Point, Click, Add map (accepting AJAX)
if (!empty($_REQUEST['mode']) && $_REQUEST['mode']=="pca") {
	include(SL_ADDONS_PATH."/point-click-add/pcaImport.php");
}

print "
<table cellpadding='' cellspacing='0' style='width:100%' class='manual_add_table'><tr>
<td style='/*border-right:solid silver 1px;*/ padding-top:0px; width:50%' valign='top'>".sl_location_form("add")."</td>
<td style='/*border-right:solid silver 1px;*/ padding-top:0px;' valign='top'>";

function csv_importer(){
	global $sl_uploads_path, $sl_path, $text_domain, $web_domain;
	if (file_exists(SL_ADDONS_PATH."/csv-xml-importer-exporter/csv-import-form.php")) {
		include(SL_ADDONS_PATH."/csv-xml-importer-exporter/csv-import-form.php");
		print "<br>";
	}
}
function db_importer(){
	global $sl_uploads_path, $sl_path, $text_domain, $web_domain;
	if (file_exists(SL_ADDONS_PATH."/db-importer/db-import-form.php")) {
		//include(SL_INCLUDES_PATH."/sl-env.php");
		include(SL_ADDONS_PATH."/db-importer/db-import-form.php");
	}
}
function point_click_add(){
	global $sl_uploads_path, $sl_path, $text_domain, $web_domain;
	if (file_exists(SL_ADDONS_PATH."/point-click-add/point-click-add-form.php")) {
		include(SL_ADDONS_PATH."/point-click-add/point-click-add-form.php");
	}
}
function sl_csv_db_pca_forms(){
  if (file_exists(SL_ADDONS_PATH."/db-importer/db-import-form.php") || file_exists(SL_ADDONS_PATH."/point-click-add/point-click-add-form.php") || file_exists(SL_ADDONS_PATH."/csv-xml-importer-exporter/csv-import-form.php")) {	
	print "<table><tr>";
	if (file_exists(SL_ADDONS_PATH."/csv-xml-importer-exporter/csv-import-form.php") || file_exists(SL_ADDONS_PATH."/db-importer/db-import-form.php")) {
		print "<td style='vertical-align:top; padding-top:0px'>";
		csv_importer();
		db_importer();
		print "</td>";
	}
	if (file_exists(SL_ADDONS_PATH."/point-click-add/point-click-add-form.php")) {
		print "<td style='vertical-align:top; padding-top:0px'>";
		point_click_add();
		print "</td>";
	}	
		print "</tr></table>";
  }
}
if (function_exists("addto_sl_hook")) {
	addto_sl_hook('sl_add_location_forms', 'csv_importer','','','csv-xml-importer-exporter');
	addto_sl_hook('sl_add_location_forms', 'db_importer','','','db-importer');
	addto_sl_hook('sl_add_location_forms', 'point_click_add','','','point-click-add');
} else {
	sl_csv_db_pca_forms();
}

if (function_exists("do_sl_hook")) {do_sl_hook('sl_add_location_forms', 'select-top');}



print "</td>
</tr>
</table>
</div>";

include(SL_INCLUDES_PATH."/sl-footer.php");
?>