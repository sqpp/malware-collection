<?php
//include("variables.sl.php");
include_once(SL_INCLUDES_PATH."/top-nav.php");
?>
<div class='wrap'>
<?php 

if (empty($_POST)) {sl_move_upload_directories();}
if (!empty($_POST)) {
	//$sl_vars=sl_data('sl_vars');
	$_POST['height']=preg_replace("@[^0-9]@", "", $_POST['height']);
	$_POST['width']=preg_replace("@[^0-9]@", "", $_POST['width']);
	$sl_vars['height']=$_POST['height'];
	$sl_vars['width']=$_POST['width'];
	$sl_vars['radii']=$_POST['radii'];
	$sl_vars['height_units']=$_POST['height_units'];
	$sl_vars['width_units']=$_POST['width_units'];
	$sl_vars['icon']=$_POST['icon'];
	$sl_vars['icon2']=$_POST['icon2'];
	$sl_vars['theme']=$_POST['theme'];
	$sl_vars['search_label']=$_POST['search_label'];
	$sl_vars['radius_label']=$_POST['sl_radius_label'];
	$sl_vars['website_label']=$_POST['sl_website_label'];
	$sl_vars['directions_label']=$_POST['sl_directions_label'];
	$sl_vars['instruction_message']=stripslashes($_POST['sl_instruction_message']);
	$sl_vars['city_dropdown_label']=stripslashes($_POST['sl_city_dropdown_label']);
	$sl_vars['location_not_found_message']=stripslashes($_POST['sl_location_not_found_message']);
	$sl_vars['no_results_found_message']=stripslashes($_POST['sl_no_results_found_message']);
	$sl_vars['zoom_level']=$_POST['zoom_level'];
	$sl_vars['use_city_search']=(empty($_POST['sl_use_city_search']))? 0 : $_POST['sl_use_city_search'];
	//$sl_vars['use_name_search']=($_POST['sl_use_name_search']==="")? 0 : $_POST['sl_use_name_search'];
	$sl_vars['remove_credits']=(empty($_POST['sl_remove_credits']))? 0 : $_POST['sl_remove_credits'];
	$sl_vars['load_locations_default']=(empty($_POST['sl_load_locations_default']))? 0 : $_POST['sl_load_locations_default'];
	$sl_vars['load_results_with_locations_default']=(empty($_POST['sl_load_results_with_locations_default']))? 0 : $_POST['sl_load_results_with_locations_default'];
	$sl_vars['map_type']=$_POST['sl_map_type'];
	$sl_vars['num_initial_displayed']=$_POST['sl_num_initial_displayed'];
	$sl_vars['map_overview_control']=(empty($_POST['sl_map_overview_control']))? 0 : $_POST['sl_map_overview_control'];
	$sl_vars['geolocate']=(empty($_POST['sl_geolocate']))? 0 : $_POST['sl_geolocate'];
		$sl_vars['sensor']=(empty($_POST['sl_geolocate']))? "false" : "true";
	$sl_vars['distance_unit']=$_POST['sl_distance_unit'];
	$sl_vars['map_language']=$_POST['sl_map_language'];
	$sl_map_region_arr=explode(":", $_POST['map_region']);
	$sl_vars['google_map_country']=$sl_map_region_arr[0];
	$sl_vars['google_map_domain']=$sl_map_region_arr[1];
	$sl_vars['map_region']=$sl_map_region_arr[2];
	$sl_vars['api_key']=$_POST['sl_api_key'];
	sl_data('sl_vars', 'update', $sl_vars);
	

	print "<div class='sl_admin_success' >".__("Successful Update", SL_TEXT_DOMAIN)." $view_link</div> <!--meta http-equiv='refresh' content='0'-->";
}
/*print "<h2>".__("MapDesigner&trade;", SL_TEXT_DOMAIN)."</h2><br>";*/

$api_key_field=(empty($sl_vars['api_key']))? "<input name='sl_api_key' size='30' style='font-size:10px'>" : "<input name='sl_api_key' value='$sl_vars[api_key]' size='60' style='font-size:10px'>";
include(SL_INCLUDES_PATH."/countries-languages.php");
$your_location_select="<!--tr><td--><select name='map_region' style='font-size:11px'><optgroup label='".__("Select Your Location", SL_TEXT_DOMAIN)."'><!--/td><td-->";
foreach ($tld as $key=>$value) {
	$selected=($sl_vars['map_region']==$value)?" selected " : "";
	$your_location_select.="<option value='$key:{$the_domain[$key]}:$value' $selected>$key</option>\n";
}
$your_location_select.="</optgroup></select><!--/td></tr-->";
$map_lang_select="<!--tr><td--><select name='sl_map_language' style='font-size:11px'><optgroup label='".__("Select Map Language", SL_TEXT_DOMAIN)."'><!--/td><td-->";

foreach ($map_lang as $key=>$value) {
	$selected=($sl_vars['map_language']==$value)? " selected='selected'" : "";
	$map_lang_select.= "<option value='$value' $selected>".ucwords(strtolower($key))."</option>\n";
}
$map_lang_select.= "</optgroup></select><!--/td></tr-->";
$update_button="<input type='submit' value='".__("Update", SL_TEXT_DOMAIN)."' class='button-primary'>";

print "<form method='post' name='mapDesigner'><table class='widefat' id='mapDesigner_table'><thead><tr><th colspan='2'>".__("MapDesigner", SL_TEXT_DOMAIN)." <div style='float:right'><small>".__("API Key", SL_TEXT_DOMAIN)." (<a rel='sl_pop' href='#api-key-info'>?</a>): </small>
<div id='api-key-info' style='display:none'><h3 style='margin-top:0px'>".__("Google Maps", SL_TEXT_DOMAIN)." ".__("API Key", SL_TEXT_DOMAIN)."</h3>".__("Google Maps API V3 actually doesn't require an API Key, however, if needed (it appears that high usage requires a key)", SL_TEXT_DOMAIN).", <a target='_blank' href='https://developers.google.com/maps/documentation/javascript/tutorial#api_key'>".__("get your key here", SL_TEXT_DOMAIN)."</a></div> {$api_key_field}&nbsp;{$your_location_select}&nbsp;{$map_lang_select}&nbsp;&nbsp;<input type='submit' value='".__("Update", SL_TEXT_DOMAIN)."' class='button-primary' style=''><div></th><!--td><".__("Designer", SL_TEXT_DOMAIN)."--></td--></tr></thead>";
sl_initialize_variables();
$icon_str="";$icon2_str="";

$icon_dir=opendir(SL_ICONS_PATH."/"); 
while (false !== ($an_icon=readdir($icon_dir))) {
	if (!preg_match("@^\.{1,2}.*$@", $an_icon) && !preg_match("@shadow@", $an_icon) && !preg_match("@\.db@", $an_icon)) {

		$icon_str.="<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' src='".SL_ICONS_BASE."/$an_icon' onclick='document.forms[\"mapDesigner\"].icon.value=this.src;document.getElementById(\"prev\").src=this.src;' onmouseover='style.borderColor=\"red\";' onmouseout='style.borderColor=\"white\";'>";
	}
}
if (is_dir(SL_CUSTOM_ICONS_PATH)) {
	$icon_upload_dir=opendir(SL_CUSTOM_ICONS_PATH);
	while (false !== ($an_icon=readdir($icon_upload_dir))) {
		if (!preg_match("@^\.{1,2}.*$@", $an_icon) && !preg_match("@shadow@", $an_icon) && !preg_match("@\.db@", $an_icon)) {

			$icon_str.="<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' src='".SL_CUSTOM_ICONS_BASE."/$an_icon' onclick='document.forms[\"mapDesigner\"].icon.value=this.src;document.getElementById(\"prev\").src=this.src;' onmouseover='style.borderColor=\"red\";' onmouseout='style.borderColor=\"white\";'>";
		}
	}
}

$icon_dir=opendir(SL_ICONS_PATH."/");
while (false !== ($an_icon=readdir($icon_dir))) {
	if (!preg_match("@^\.{1,2}.*$@", $an_icon) && !preg_match("@shadow@", $an_icon) && !preg_match("@\.db@", $an_icon)) {

		$icon2_str.="<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' src='".SL_ICONS_BASE."/$an_icon' onclick='document.forms[\"mapDesigner\"].icon2.value=this.src;document.getElementById(\"prev2\").src=this.src;' onmouseover='style.borderColor=\"red\";' onmouseout='style.borderColor=\"white\";'>";
	}
}
if (is_dir(SL_CUSTOM_ICONS_PATH)) {
	$icon_upload_dir=opendir(SL_CUSTOM_ICONS_PATH);
	while (false !== ($an_icon=readdir($icon_upload_dir))) {
		if (!preg_match("@^\.{1,2}.*$@", $an_icon) && !preg_match("@shadow@", $an_icon) && !preg_match("@\.db@", $an_icon)) {

			$icon2_str.="<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' src='".SL_CUSTOM_ICONS_BASE."/$an_icon' onclick='document.forms[\"mapDesigner\"].icon2.value=this.src;document.getElementById(\"prev2\").src=this.src;' onmouseover='style.borderColor=\"red\";' onmouseout='style.borderColor=\"white\";'>";
		}
	}
}

if (is_dir(SL_THEMES_PATH)) {
	$theme_dir=opendir(SL_THEMES_PATH); 
	$theme_str="";
	while (false !== ($a_theme=readdir($theme_dir))) {
		if (!preg_match("@^\.{1,2}.*$@", $a_theme) && !preg_match("@\.(php|txt|htm(l)?)@", $a_theme)) {

			$selected=($a_theme==$sl_vars['theme'])? " selected " : "";
			$theme_str.="<option value='$a_theme' $selected>$a_theme</option>\n";
		}
	}
}

for ($i=0; $i<=19; $i++) {
	$zl[]=$i;
}

$zoom="<select name='zoom_level'>";
foreach ($zl as $value) {
	$zoom.="<option value='$value' ";
	if ($sl_vars['zoom_level']==$value){ $zoom.=" selected ";}
	$zoom.=">$value</option>";
}
$zoom.="</select>";

$checked=($sl_vars['use_city_search']==1)? " checked " : "";
$checked2="";
//$checked2=($sl_vars['use_name_search']==1)? " checked " : "";
$checked3=($sl_vars['remove_credits']==1)? " checked " : "";
$checked4=($sl_vars['load_locations_default']==1)? " checked " : "";
$checked5=($sl_vars['map_overview_control']==1)? " checked " : "";
$checked6=($sl_vars['geolocate']==1)? " checked " : "";
$checked7=($sl_vars['load_results_with_locations_default']==1)? " checked " : "";

$map_type["".__("Normal", SL_TEXT_DOMAIN).""]="google.maps.MapTypeId.ROADMAP";
$map_type["".__("Normal + Terrain (Physical)", SL_TEXT_DOMAIN).""]="google.maps.MapTypeId.TERRAIN";
$map_type["".__("Satellite", SL_TEXT_DOMAIN).""]="google.maps.MapTypeId.SATELLITE";
$map_type["".__("Satellite + Labels (Hybrid)", SL_TEXT_DOMAIN).""]="google.maps.MapTypeId.HYBRID";
$map_type_options="";

foreach($map_type as $key=>$value) {
	$selected2=($sl_vars['map_type']==$value)? " selected " : "";
	$map_type_options.="<option value='$value' $selected2>$key</option>\n";
}
$icon_notification_msg=((preg_match("@wordpress-store-locator-location-finder@", $sl_vars['icon']) && preg_match("@^store-locator@", $sl_dir)) || (preg_match("@wordpress-store-locator-location-finder@",$sl_vars['icon2']) && preg_match("@^store-locator@", $sl_dir)))? "<div class='sl_admin_success' style='background-color:LightYellow;color:red'><span style='color:red'>".__("You have switched from <strong>'wordpress-store-locator-location-finder'</strong> to <strong>'store-locator'</strong> --- great!<br>Now, please re-select your <b>'Home Icon'</b> and <b>'Destination Icon'</b> below, so that they show up properly on your store locator map.", SL_TEXT_DOMAIN)."</span></div>" : "" ;
	
print "
<tr><td colspan='1' width='45%' class='left_side' style='vertical-align:top'><h2>".__("Defaults", SL_TEXT_DOMAIN)."</h2>
<table class='map_designer_section'>
<tr><td>".__("Default Map Type", SL_TEXT_DOMAIN).":</td>
<td><select name='sl_map_type'>\n".$map_type_options."</select></td></tr>
<tr><td>".__("Locations in Results", SL_TEXT_DOMAIN).":</td>
<td><input name='sl_num_initial_displayed' value='$sl_vars[num_initial_displayed]'><!--br><span style='font-size:80%'>(".__("Recommended Max", SL_TEXT_DOMAIN).": 200)</span--></td></tr>
<tr><td><input name='sl_use_city_search' value='1' type='checkbox' $checked>&nbsp;".__("Search By City", SL_TEXT_DOMAIN)."</td>
<td><input name='sl_map_overview_control' value='1' type='checkbox' $checked5>&nbsp;".__("Show Map Inset Box", SL_TEXT_DOMAIN)."</td></tr>
<tr><td><input name='sl_geolocate' value='1' type='checkbox' $checked6>&nbsp;".__("Auto-Locate User", SL_TEXT_DOMAIN)."</td>
<td><input name='sl_load_locations_default' value='1' type='checkbox' $checked4>&nbsp;".__("Auto-Load Locations", SL_TEXT_DOMAIN)."&nbsp;&nbsp;(<input name='sl_load_results_with_locations_default' value='1' type='checkbox' $checked7>&nbsp;&amp;&nbsp;".__("Listing", SL_TEXT_DOMAIN)."&nbsp;(<a href='#info_load_results_default' rel='sl_pop'>?</a>)<div style='display:none;' id='info_load_results_default'>".__("<h2 style='margin-top:0px'>Search Results Listing By Default</h2>Determine whether or not both the map icons and the results listing show when loading locations by default. <br><Br>No results listings are shown even if this is checked, but 'Auto-Load Locations' is unchecked", SL_TEXT_DOMAIN).".</div>)</td></tr>
<!--tr><td></td>
<td></td></tr>
<tr><td></td>
<td></td></tr-->
";

print "<!--tr><td>".__("Allow User Search By Name of Location?", SL_TEXT_DOMAIN).":</td>
<td><input name='sl_use_name_search' value='1' type='checkbox' $checked2></td></tr-->
</table>";
//end api-key

print "
<!--tr--><td colspan='1' width='50%' style='vertical-align:top'><h2>".__("Labels", SL_TEXT_DOMAIN)."</h2>
<table class='map_designer_section right_side'>
<tr><td><input name='search_label' value=\"$sl_search_label\" size='16'><br><span style='font-size:80%'>".__("Address Input Label", SL_TEXT_DOMAIN)."</span></td>
<td><input name='sl_radius_label' value=\"$sl_radius_label\" size='13'><br><span style='font-size:80%'>".__("Radius Dropdown Label", SL_TEXT_DOMAIN)."</span></td><!--/tr>
<tr--><td><input name='sl_website_label' value=\"$sl_website_label\" size='13'><br><span style='font-size:80%'>".__("Website URL Label", SL_TEXT_DOMAIN)."</span></td></tr>
<tr><td><input name='sl_directions_label' value=\"$sl_directions_label\" size='16'><br><span style='font-size:80%'>".__("Directions URL Label", SL_TEXT_DOMAIN)."</span></td>
<!--tr><td></td>
<td></td>
<tr><td></td>
<td></td>
<tr--><td><input name='sl_instruction_message' value=\"".$sl_instruction_message."\" size='13'><br><span style='font-size:80%'>".__("Instruction Message to Users", SL_TEXT_DOMAIN)."</span></td>
<td>
<input name='sl_city_dropdown_label' value=\"".$sl_city_dropdown_label."\" size='13'><br><span style='font-size:80%'>".__("City Dropdown Label", SL_TEXT_DOMAIN)."</span>
</td><td colspan='2'>
</td>
<!--td></td-->
</tr>
<tr><td>
<input name='sl_location_not_found_message' value=\"".$sl_location_not_found_message."\" size='16'><br><span style='font-size:80%'>".__("Location Doesn't Exist Message", SL_TEXT_DOMAIN)."</span>
</td><td colspan='2'>
<input name='sl_no_results_found_message' value=\"".$sl_no_results_found_message."\" size='33'><br><span style='font-size:80%'>".__("No Results Are Found Message", SL_TEXT_DOMAIN)."</span>
</td></tr>
</table>
</td></tr>
<tr><td colspan='1' class='left_side' style='vertical-align:top; border-bottom:0px'><h2>".__("Dimensions", SL_TEXT_DOMAIN)."</h2>
<table class='map_designer_section'><tr><td><nobr>".__("Zoom Level", SL_TEXT_DOMAIN).":</nobr></td>
<td>$zoom</td></tr>
<tr><td><nobr>".__("Map Dimensions (H x W)", SL_TEXT_DOMAIN).":</nobr></td>
<td><input name='height' value='$sl_height' size='3'>&nbsp;".choose_units($sl_height_units, "height_units")." <span style='font-size:1.2em; vertical-align:middle'>X</span> <input name='width' value='$sl_width' size='3'>&nbsp;".choose_units($sl_width_units, "width_units")."</td></tr>
<!--tr><td><nobr>".__("Map Width", SL_TEXT_DOMAIN).":</nobr></td>
<td><input name='width' value='$sl_width'>&nbsp;".choose_units($sl_width_units, "width_units")."</td></tr-->
<tr><td><nobr>".__("Radii Options", SL_TEXT_DOMAIN)." (in <select name='sl_distance_unit'>";
$the_distance_unit["".__("Km", SL_TEXT_DOMAIN).""]="km";
$the_distance_unit["".__("Miles", SL_TEXT_DOMAIN).""]="miles";
foreach ($the_distance_unit as $key=>$value) {
	$selected=($sl_vars['distance_unit']==$value)?" selected " : "";
	print "<option value='$value' $selected>$key</option>\n";
}
print "</select>):</nobr></td>
<td><input  name='radii' value='$sl_vars[radii]' size='30'><br><span style='font-size:80%'>(".__("Parentheses '( )' are for the default radius</span>", SL_TEXT_DOMAIN).")</td></tr>
<!--tr><td><nobr>".__("Distance Unit", SL_TEXT_DOMAIN).":</td><td></td></tr-->
</table>
</td><!--/tr>
<tr--><td colspan='1' style='vertical-align:top; border-bottom:0px'><h2>".__("Design", SL_TEXT_DOMAIN)."</h2>
$icon_notification_msg
<table class='map_designer_section right_side'><tr>
<tr><td valign='top'>".__("Choose Theme", SL_TEXT_DOMAIN)."</td><td valign='top'> <select name='theme' onchange=\"\"><option value=''>".__("No Theme Selected", SL_TEXT_DOMAIN)."</option>$theme_str</select>&nbsp;&nbsp;&nbsp;<a href='http://www.viadat.com/products-page/store-locator-themes/' target='_blank'>".__("Get&nbsp;Themes", SL_TEXT_DOMAIN)." &raquo;</a></td></tr>
<tr><td>".__("Remove Credits", SL_TEXT_DOMAIN).":</td>
<td><input name='sl_remove_credits' value='1' type='checkbox' $checked3></td></tr>
<tr><td valign='top' style='white-space:nowrap'><input name='icon' size='20' value='$sl_vars[icon]' onchange=\"document.getElementById('prev').src=this.value\"><img id='prev' src='$sl_vars[icon]' align='top' rel='sl_pop' href='#home_icon' style='cursor:pointer;cursor:hand;height:60%;'> <br><a rel='sl_pop' href='#home_icon'><span style='font-size:80%'>".__("Choose", SL_TEXT_DOMAIN)." ".__("Home Icon", SL_TEXT_DOMAIN)."</span></a><div id='home_icon' style='display:none;'><h2 style='margin-top:0px'>".__("Choose", SL_TEXT_DOMAIN)." ".__("Home Icon", SL_TEXT_DOMAIN)."</h2>$icon_str</div></td>
<td valign='top' style='white-space:nowrap'>  <input name='icon2' size='20' value='$sl_vars[icon2]' onchange=\"document.getElementById('prev2').src=this.value\"><img id='prev2' src='$sl_vars[icon2]' align='top' rel='sl_pop' href='#end_icon' style='cursor:pointer;cursor:hand;height:60%;'> <br><div id='end_icon' style='display:none;'><h2 style='margin-top:0px'>".__("Choose", SL_TEXT_DOMAIN)." ".__("Destination Icon", SL_TEXT_DOMAIN)."</h2>$icon2_str</div><a rel='sl_pop' href='#end_icon'><span style='font-size:80%'>".__("Choose", SL_TEXT_DOMAIN)." ".__("Destination Icon", SL_TEXT_DOMAIN)." </span></a></td></tr>
<!--tr><td valign='top'></td>
<td valign='top'>
</td></tr-->
<tr><td colspan='2'><div class=''><b>".__("For more unique icons, visit", SL_TEXT_DOMAIN)." <a href='https://www.geocoderpro.com/en/resources/map-icons-marker-pins/' target='_blank'>GeoCoder Pro</a> & <a href='http://code.google.com/p/google-maps-icons/' target='_blank'>Map Icons Collection</a></b></div></td></tr></table>
</td></tr>
<tr><td colspan='2'>$update_button</td></tr></table></form>";

?>
</div>
<?php include(SL_INCLUDES_PATH."/sl-footer.php"); ?>