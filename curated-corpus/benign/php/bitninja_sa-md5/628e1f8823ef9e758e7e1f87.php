<?php

global $arffield, $arfform, $MdlDb, $wpdb, $arfieldhelper, $arfsettings;

$wp_upload_dir = wp_upload_dir();
$upload_dir = $wp_upload_dir['basedir'] . '/arforms/css/';
$main_css_dir = $wp_upload_dir['basedir'] . '/arforms/maincss/';

$field_data = file_get_contents(VIEWS_PATH . '/arf_editor_data.json');

$field_data_obj = json_decode($field_data,true);

$field_data_obj = $field_data_obj['field_data'];

if( isset($arf_update_templates) && $arf_update_templates == true ){
    $values['id'] = 1;
}
$values['name'] = 'Subscription Form';
$values['description'] = 'Gather user information';
$values['options']['custom_style'] = 1;
$values['is_template'] = '1';
$values['status'] = 'published';
$values['form_key'] = 'Subscription';
$values['options']['display_title_form'] = "1";

$new_values = array(
    'arfmainformwidth' => '550',
    'form_width_unit' => 'px',
    'edit_msg' => 'Your submission was successfully saved.',
    'update_value' => 'Update',
    'arfeditoroff' => false,
    'arfmaintemplatepath' => '',
    'csv_format' => 'UTF-8',
    'date_format' => 'MMM D, YYYY',
    'cal_date_format' => 'MMM D, YYYY',
    'arfcalthemecss' => 'default_theme',
    'arfcalthemename' => 'default_theme',
    'theme_nicename' => 'default_theme',
    'permalinks' => false,
    'form_align' => 'left',
    'fieldset' => '2',
    'arfmainfieldsetcolor' => 'd9d9d9',
    'arfmainfieldsetpadding' => '30px 45px 30px 45px',
    'arfmainfieldsetradius' => '6',
    'font' => 'Helvetica',
    'font_other' => '',
    'font_size' => '16',
    'label_color' => '706d70',
    'weight' => 'normal',
    'position' => 'top',
    'hide_labels' => false,
    'align' => 'left',
    'width' => 90,
    'width_unit' => 'px',
    'arfdescfontsetting' => '"Lucida Grande","Lucida Sans Unicode",Tahoma,sans-serif',
    'arfdescfontsizesetting' => 14,
    'arfdesccolorsetting' => '666666',
    'arfdescweightsetting' => 'normal',
    'description_style' => 'normal',
    'arfdescalighsetting' => 'right',
    'field_font_size' => '14',
    'field_width' => '100',
    'field_width_unit' => '%',
    'auto_width' => false,
    'arffieldpaddingsetting' => '2',
    'arffieldmarginssetting' => 20,
    'arffieldanimationdurationsetting' => '0',
    'arfpbfieldanimationdurationsetting' => '0',
    'arffieldanimationdelaysetting' => '0',
    'arfpbfieldanimationdelaysetting' => '0',
    'bg_color' => 'ffffff',
    'text_color' => '17181c',
    'border_color' => 'b0b0b5',
    'arffieldborderwidthsetting' => '2',
    'arffieldborderstylesetting' => 'solid',
    'arfbgactivecolorsetting' => '#fafafa',
    'arfborderactivecolorsetting' => '#20bfe3',
    'arferrorbgcolorsetting' => 'ffffff',
    'arferrorbordercolorsetting' => 'ed4040',
    'arferrorborderwidthsetting' => '1',
    'arferrorborderstylesetting' => 'solid',
    'arfradioalignsetting' => 'inline',
    'arfcheckboxalignsetting' => 'block',
    'check_font' => 'Helvetica',
    'check_font_other' => '',
    'arfcheckboxfontsizesetting' => '12px',
    'arfcheckboxlabelcolorsetting' => '444444',
    'check_weight' => 'normal',
    'arfsubmitbuttonstylesetting' => false,
    'arfsubmitbuttonfontsizesetting' => '18',
    'arfsubmitbuttonwidthsetting' => '150',
    'arfsubmitbuttonheightsetting' => '42',
    'submit_bg_color' => '#20bfe3',
    'arfsubmitbuttonbgcolorhoversetting' => '#19adcf',
    'arfsubmitbgcolor2setting' => '',
    'arfsubmitbordercolorsetting' => '#e1e1e3',
    'arfsubmitborderwidthsetting' => '0',
    'arfsubmitboxxoffsetsetting' => '1', 
    'arfsubmitboxyoffsetsetting' => '2',
    'arfsubmitboxblursetting' => '3',
    'arfsubmitboxshadowsetting' => '0',
    'arfsubmittextcolorsetting' => 'ffffff',
    'arfsubmitweightsetting' => 'bold',
    'arfsubmitborderradiussetting' => '3',
    'submit_bg_img' => '',
    'submit_hover_bg_img' => '',
    'arfsubmitbuttonmarginsetting' => '10px 10px 0px 0px',
    'arfsubmitbuttonpaddingsetting' => '8',
    'arfsubmitshadowcolorsetting' => '#f0f0f0',
    'border_radius' => '3',
    'arferroriconsetting' => 'e1.png',
    'arferrorbgsetting' => 'F3CAC7',
    'arferrorbordersetting' => 'FA8B83',
    'arferrortextsetting' => '501411',
    'arffontsizesetting' => '14',
    'arfsucessiconsetting' => 's1.png',
    'success_bg' => '',
    'success_border' => '',
    'success_text' => '',
    'arfsucessfontsizesetting' => '14',
    'arftextareafontsizesetting' => '13px',
    'arftextareawidthsetting' => '400',
    'arftextareawidthunitsetting' => 'px',
    'arftextareapaddingsetting' => '2',
    'arftextareamarginsetting' => '20',
    'arftextareabgcolorsetting' => 'ffffff',
    'arftextareacolorsetting' => '444444',
    'arftextareabordercolorsetting' => 'dddddd',
    'arftextareaborderwidthsetting' => '1',
    'arftextareaborderstylesetting' => 'solid',
    'text_direction' => '1',
    'arffieldheightsetting' => '24',
    'arfmainformtitlecolorsetting' => '#696969',
    'form_title_font_size' => 26,
    'error_font' => 'Lucida Sans Unicode',
    'error_font_other' => '',
    'arfactivebgcolorsetting' => 'FFFF00',
    'arfmainformbgcolorsetting' => 'ffffff',
    'arfmainformtitleweightsetting' => 'normal',
    'arfmainformtitlepaddingsetting' => '0px 0px 20px 0px',
    'arfmainformbordershadowcolorsetting' => '#d4d2d4',
    'form_border_shadow' => 'shadow',
    'arfsubmitalignsetting' => 'center',
    'checkbox_radio_style' => '1',
    'bg_color_pg_break' => '087ee2',
    'bg_inavtive_color_pg_break' => '7ec3fc',
    'text_color_pg_break' => 'ffffff',
    'text_color_pg_break_style3' => '087ee2',
    'arfmainform_bg_img' => '',
    'arfmainform_opacity' => '1',
    'arfmainfield_opacity' => '0',
    'arfsubmitfontfamily' => 'Helvetica',
    'arfmainfieldsetpadding_1' => '30',
    'arfmainfieldsetpadding_2' => '45',
    'arfmainfieldsetpadding_3' => '30',
    'arfmainfieldsetpadding_4' => '45',
    'arfmainformtitlepaddingsetting_1' => '0',
    'arfmainformtitlepaddingsetting_2' => '0',
    'arfmainformtitlepaddingsetting_3' => 25,
    'arfmainformtitlepaddingsetting_4' => '0',
    'arffieldinnermarginssetting_1' => '10',
    'arffieldinnermarginssetting_2' => '10',
    'arffieldinnermarginssetting_3' => '10',
    'arffieldinnermarginssetting_4' => '10',
    'arfsubmitbuttonmarginsetting_1' => '10',
    'arfsubmitbuttonmarginsetting_2' => '10',
    'arfsubmitbuttonmarginsetting_3' => '0',
    'arfsubmitbuttonmarginsetting_4' => '0',
    'arfcheckradiostyle' => 'flat',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfcheckradiocolor' => 'blue',
    'arf_checked_checkbox_icon' => '',
    'enable_arf_checkbox' => '0',
    'arf_checked_radio_icon' => '',
    'enable_arf_radio' => '0',
    'checked_checkbox_icon_color' => '#23b7e5',
    'checked_radio_icon_color' => '#23b7e5',
    'arfformtitlealign' => 'center',
    'arferrorstyle' => 'normal',
    'arferrorstylecolor' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstylecolor2' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstyleposition' => 'bottom',
    'arfsubmitautowidth' => '100',
    'arftitlefontfamily' => 'Helvetica',
    'bar_color_survey' => '#007ee4',
    'bg_color_survey' => '#dadde2',
    'text_color_survey' => '#333333',
    'prefix_suffix_bg_color' => '#e7e8ec',
    'prefix_suffix_icon_color' => '#808080',
    'arfsectionpaddingsetting_1' => '20',
    'arfsectionpaddingsetting_2' => '0',
    'arfsectionpaddingsetting_3' => '20',
    'arfsectionpaddingsetting_4' => '20',
    'arfsectionpaddingsetting' => "20px 0px 20px 20px",
    'arffieldinnermarginssetting' => '10px 10px 10px 10px',
    'arfsucessbgcolorsetting' => '#E0FDE2',
    'arfsucessbordercolorsetting' => '#BFE0C1',
    'arfsucesstextcolorsetting' => '#4C4D4E',
    'arfformerrorbgcolorsetting' => '#FDECED',
    'arfformerrorbordercolorsetting' => '#F9CFD1',
    'arfformerrortextcolorsetting' => '#ED4040',
    'check_weight_form_title' => 'bold',
    "arfsubmitbuttonstyle"=>"border",
    'arfinputstyle' => 'standard',
    'arfcheckradiostyle' => 'default',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfmainform_color_skin' => 'cyan',
    'arf_tooltip_bg_color' => '#000000',
    'arf_tooltip_font_color' => '#ffffff',
    "arfcommonfont"=>"Helvetica",
    "arfmainfieldcommonsize"=>"3",
    "arfvalidationbgcolorsetting"=>"#ed4040",
    "arfvalidationtextcolorsetting"=>"#ffffff",
    "arfdatepickerbgcolorsetting"=>"#007ee4",
    "arfdatepickertextcolorsetting"=>"#000000",
    "arfsectiontitlefamily"=>"Helvetica",
    "arfsectiontitlefontsizesetting"=>"16",
    "arfsectiontitleweightsetting"=>"bold",
    "arfsubmitbuttontext"=>"Submit",
    "arfuploadbtntxtcolorsetting"=>"#ffffff",
    "arfuploadbtnbgcolorsetting" =>"#23b7e5",
    "arf_req_indicator"=>"0",
    "arf_section_inherit_bg" => "1",
    "arfformsectionbackgroundcolor"=>"#ffffff",
    "arfmainbasecolor" => "#23b7e5",
    "arflikebtncolor"=>"#4786ff",
    "arfdislikebtncolor"=>"#ec3838",
    "arfstarratingcolor"=>"#FCBB1D",
    "arfsliderselectioncolor"=>"#d1dee5",
    "arfslidertrackcolor"=>"#bcc7cd",
    "arfplaceholder_opacity" => "0.5",
    "arf_bg_position_x" => "left",
    "arf_bg_position_input_x" => "",
    "arf_bg_position_y" => "top",
    "arf_bg_position_input_y" => "",
);

$new_values1 = maybe_serialize($new_values);
$values['form_css'] = $new_values1;
$form_id = $arfform->create($values);
if (!empty($new_values)) {

    $use_saved = true;

    $arfssl = (is_ssl()) ? 1 : 0;

    $filename = FORMPATH . '/core/css_create_main.php';

    $wp_upload_dir = wp_upload_dir();

    $target_path = $wp_upload_dir['basedir'] . '/arforms/maincss';

    $css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";


    $css .= "\n";


    ob_start();


    include $filename;


    $css .= ob_get_contents();


    ob_end_clean();


    $css .= "\n " . $warn;

    $css_file = $target_path . '/maincss_' . $form_id . '.css';

    WP_Filesystem();
    global $wp_filesystem;
    $css = str_replace('##', '#', $css);
    $wp_filesystem->put_contents($css_file, $css, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename1 = FORMPATH . '/core/css_create_materialize.php';
    $css1 = $warn1 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css1 .= "\n";
    ob_start();
    include $filename1;
    $css1 .= ob_get_contents();
    ob_end_clean();
    $css1 .= "\n " . $warn1;
    $css_file1 = $target_path . '/maincss_materialize_' . $form_id . '.css';
    WP_Filesystem();
    $css1 = str_replace('##', '#', $css1);
    $wp_filesystem->put_contents($css_file1, $css1, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename2 = FORMPATH . '/core/css_create_materialize_outline.php';
    $css2 = $warn2 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css2 .= "\n";
    ob_start();
    include $filename2;
    $css2 .= ob_get_contents();
    ob_end_clean();
    $css2 .= "\n" . $warn2;
    $css_file2 = $target_path . '/maincss_materialize_outlined_' . $form_id . '.css';
    WP_Filesystem();
    $css2 = str_replace( '##', '#', $css2 );
    $wp_filesystem->put_contents( $css_file2, $css2, 0777 );

} else {
    $query_results = true;
}

$field_order = array();
$inner_field_order = array();

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'First Name';
$field_values['field_options']['name'] = 'First Name';
$field_values['field_options']['placeholdertext'] = 'First Name';
$field_values['field_options']['description'] = '';
$field_values['type'] = 'text';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter first name', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('First Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 1;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Last Name';
$field_values['field_options']['name'] = 'Last Name';
$field_values['field_options']['placeholdertext'] = 'Last Name';
$field_values['type'] = 'text';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter last name', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Last Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 2;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['email'];
$field_values['name'] = 'Email';
$field_values['type'] = 'email';
$field_values['field_options']['name'] = 'Email';
$field_values['field_options']['placeholdertext'] = 'Email Address';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter email address', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Email Address', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Please enter a valid email address', 'ARForms'));
$field_values['field_options']['confirm_email_label'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['field_options']['invalid_confirm_email'] = addslashes(esc_html__('Confirm email address does not match with email', 'ARForms'));
$field_values['field_options']['confirm_email_placeholder'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 3;
unset($field_values);
unset($field_id);
unset($values);

$field_options = $wpdb->get_results($wpdb->prepare("SELECT `options` FROM `" . $MdlDb->forms . "` WHERE `id` = %d", $form_id));

$form_opt = maybe_unserialize($field_options[0]->options);

$form_opt['arf_field_order'] = json_encode($field_order);
$form_opt['arf_inner_field_order'] = json_encode( $inner_field_order );

$form_options = maybe_serialize($form_opt);

$wpdb->update($MdlDb->forms, array('options' => $form_options), array('id' => $form_id));

unset($field_order);
unset($inner_field_order);

if( isset($arf_update_templates) && $arf_update_templates == true ){
    $values['id'] = 2;
}

$values['name'] = 'Registration form';
$values['description'] = 'Gather User information';
$values['options']['custom_style'] = 1;
$values['is_template'] = '1';
$values['status'] = 'published';
$values['form_key'] = 'Registration';

$new_values = array(
    'arfmainformwidth' => '800',
    'form_width_unit' => 'px',
    'edit_msg' => 'Your submission was successfully saved.',
    'update_value' => 'Update',
    'arfeditoroff' => false,
    'arfmaintemplatepath' => '',
    'csv_format' => 'UTF-8',
    'date_format' => 'MMM D, YYYY',
    'cal_date_format' => 'MMM D, YYYY',
    'arfcalthemecss' => 'default_theme',
    'arfcalthemename' => 'default_theme',
    'theme_nicename' => 'default_theme',
    'permalinks' => false,
    'form_align' => 'left',
    'fieldset' => '2',
    'arfmainfieldsetcolor' => 'd9d9d9',
    'arfmainfieldsetpadding' => '30px 45px 30px 45px',
    'arfmainfieldsetradius' => '6',
    'font' => 'Helvetica',
    'font_other' => '',
    'font_size' => '16',
    'label_color' => '706d70',
    'weight' => 'normal',
    'position' => 'top',
    'hide_labels' => false,
    'align' => 'left',
    'width' => '130',
    'width_unit' => 'px',
    'arfdescfontsetting' => '"Lucida Grande","Lucida Sans Unicode",Tahoma,sans-serif',
    'arfdescfontsizesetting' => '12',
    'arfdesccolorsetting' => '666666',
    'arfdescweightsetting' => 'normal',
    'description_style' => 'normal',
    'arfdescalighsetting' => 'right',
    'field_font_size' => '14',
    'field_width' => '100',
    'field_width_unit' => '%',
    'auto_width' => false,
    'arffieldpaddingsetting' => '2',
    'arffieldmarginssetting' => '23',
    'arffieldanimationdurationsetting' => '0',
    'arfpbfieldanimationdurationsetting' => '0',
    'arffieldanimationdelaysetting' => '0',
    'arfpbfieldanimationdelaysetting' => '0',
    'bg_color' => 'ffffff',
    'text_color' => '17181c',
    'border_color' => 'b0b0b5',
    'arffieldborderwidthsetting' => '1',
    'arffieldborderstylesetting' => 'solid',
    'arfbgactivecolorsetting' => 'ffffff',
    'arfborderactivecolorsetting' => '087ee2',
    'arferrorbgcolorsetting' => 'ffffff',
    'arferrorbordercolorsetting' => 'ed4040',
    'arferrorborderwidthsetting' => '1',
    'arferrorborderstylesetting' => 'solid',
    'arfradioalignsetting' => 'inline',
    'arfcheckboxalignsetting' => 'block',
    'check_font' => 'Helvetica',
    'check_font_other' => '',
    'arfcheckboxfontsizesetting' => '12px',
    'arfcheckboxlabelcolorsetting' => '444444',
    'check_weight' => 'normal',
    'arfsubmitbuttonstylesetting' => false,
    'arfsubmitbuttonfontsizesetting' => '18',
    'arfsubmitbuttonwidthsetting' => '',
    'arfsubmitbuttonheightsetting' => '38',
    'submit_bg_color' => '077BDD',
    'arfsubmitbuttonbgcolorhoversetting' => '0b68b7',
    'arfsubmitbgcolor2setting' => '',
    'arfsubmitbordercolorsetting' => 'f6f6f8',
    'arfsubmitborderwidthsetting' => '0',
    'arfsubmitboxxoffsetsetting' => '1', 
    'arfsubmitboxyoffsetsetting' => '2',
    'arfsubmitboxblursetting' => '3',
    'arfsubmitboxshadowsetting' => '0',
    'arfsubmittextcolorsetting' => 'ffffff',
    'arfsubmitweightsetting' => 'bold',
    'arfsubmitborderradiussetting' => '3',
    'submit_bg_img' => '',
    'submit_hover_bg_img' => '',
    'arfsubmitbuttonmarginsetting' => '10px 10px 0px 0px',
    'arfsubmitbuttonpaddingsetting' => '8',
    'arfsubmitshadowcolorsetting' => 'c6c8cc',
    'border_radius' => '3',
    'arferroriconsetting' => 'e1.png',
    'arferrorbgsetting' => 'F3CAC7',
    'arferrorbordersetting' => 'FA8B83',
    'arferrortextsetting' => '501411',
    'arffontsizesetting' => '14',
    'arfsucessiconsetting' => 's1.png',
    'success_bg' => NULL,
    'success_border' => NULL,
    'success_text' => NULL,
    'arfsucessfontsizesetting' => '14',
    'arftextareafontsizesetting' => '13px',
    'arftextareawidthsetting' => '400',
    'arftextareawidthunitsetting' => 'px',
    'arftextareapaddingsetting' => '2',
    'arftextareamarginsetting' => '20',
    'arftextareabgcolorsetting' => 'ffffff',
    'arftextareacolorsetting' => '444444',
    'arftextareabordercolorsetting' => 'dddddd',
    'arftextareaborderwidthsetting' => '1',
    'arftextareaborderstylesetting' => 'solid',
    'text_direction' => '1',
    'arffieldheightsetting' => '24',
    'arfmainformtitlecolorsetting' => '4a494a',
    'form_title_font_size' => '28',
    'error_font' => 'Lucida Sans Unicode',
    'error_font_other' => '',
    'arfactivebgcolorsetting' => 'FFFF00',
    'arfmainformbgcolorsetting' => 'ffffff',
    'arfmainformtitleweightsetting' => 'normal',
    'arfmainformtitlepaddingsetting' => '0px 0px 20px 0px',
    'arfmainformbordershadowcolorsetting' => 'f2f2f2',
    'form_border_shadow' => 'flat',
    'arfsubmitalignsetting' => 'left',
    'checkbox_radio_style' => '1',
    'bg_color_pg_break' => '087ee2',
    'bg_inavtive_color_pg_break' => '7ec3fc',
    'text_color_pg_break' => 'ffffff',
    'text_color_pg_break_style3' => '087ee2',
    'arfmainform_bg_img' => '',
    'arfmainform_opacity' => '1',
    'arfmainfield_opacity' => '0',
    'arfsubmitfontfamily' => 'Helvetica',
    'arfmainfieldsetpadding_1' => '30',
    'arfmainfieldsetpadding_2' => '45',
    'arfmainfieldsetpadding_3' => '30',
    'arfmainfieldsetpadding_4' => '45',
    'arfmainformtitlepaddingsetting_1' => '0',
    'arfmainformtitlepaddingsetting_2' => '0',
    'arfmainformtitlepaddingsetting_3' => '20',
    'arfmainformtitlepaddingsetting_4' => '0',
    'arffieldinnermarginssetting_1' => '8',
    'arffieldinnermarginssetting_2' => '10',
    'arffieldinnermarginssetting_3' => '8',
    'arffieldinnermarginssetting_4' => '10',
    'arfsubmitbuttonmarginsetting_1' => '10',
    'arfsubmitbuttonmarginsetting_2' => '10',
    'arfsubmitbuttonmarginsetting_3' => '0',
    'arfsubmitbuttonmarginsetting_4' => '0',
    'arfcheckradiostyle' => 'flat',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfcheckradiocolor' => 'blue',
    'arf_checked_checkbox_icon' => '',
    'enable_arf_checkbox' => '0',
    'arf_checked_radio_icon' => '',
    'enable_arf_radio' => '0',
    'checked_checkbox_icon_color' => '#0C7CD5',
    'checked_radio_icon_color' => '#0C7CD5',
    'arfformtitlealign' => 'left',
    'arferrorstyle' => 'advance',
    'arferrorstylecolor' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstylecolor2' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstyleposition' => 'bottom',
    'arfsubmitautowidth' => '100',
    'arftitlefontfamily' => 'Helvetica',
    'bar_color_survey' => '#007ee4',
    'bg_color_survey' => '#dadde2',
    'text_color_survey' => '#333333',
    'prefix_suffix_bg_color' => '#e7e8ec',
    'prefix_suffix_icon_color' => '#808080',
    'arfsectionpaddingsetting_1' => '20',
    'arfsectionpaddingsetting_2' => '0',
    'arfsectionpaddingsetting_3' => '20',
    'arfsectionpaddingsetting_4' => '20',
    'arfsectionpaddingsetting' => "20px 0px 20px 20px",
    'arffieldinnermarginssetting' => '8px 10px 8px 10px',
    'arfsucessbgcolorsetting' => '#E0FDE2',
    'arfsucessbordercolorsetting' => '#BFE0C1',
    'arfsucesstextcolorsetting' => '#4C4D4E',
    'arfformerrorbgcolorsetting' => '#FDECED',
    'arfformerrorbordercolorsetting' => '#F9CFD1',
    'arfformerrortextcolorsetting' => '#ED4040',
    "arfsubmitbuttonstyle"=>"border",
    'arfinputstyle' => 'standard',
    'arfcheckradiostyle' => 'default',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfmainform_color_skin' => 'blue',
    'arf_tooltip_bg_color' => '#000000',
    'arf_tooltip_font_color' => '#ffffff',
    "arfcommonfont"=>"Helvetica",
    "arfmainfieldcommonsize"=>"3",
    "arfvalidationbgcolorsetting"=>"#ed4040",
    "arfvalidationtextcolorsetting"=>"#ffffff",
    "arfdatepickerbgcolorsetting"=>"#007ee4",
    "arfdatepickertextcolorsetting"=>"#000000",
    "arfsectiontitlefamily"=>"Helvetica",
    "arfsectiontitlefontsizesetting"=>"16",
    "arfsectiontitleweightsetting"=>"bold",
    "arfsubmitbuttontext"=>"Submit",
    "arfuploadbtntxtcolorsetting"=>"#FFFFFF",
    "arfuploadbtnbgcolorsetting" =>"#0C7CD5",
    "arf_req_indicator"=>"0",
    "arf_section_inherit_bg" => "1",
    "arfformsectionbackgroundcolor"=>"#ffffff",
    "arfmainbasecolor" => "#0c7cd5",
    "arflikebtncolor"=>"#4786ff",
    "arfdislikebtncolor"=>"#ec3838",
    "arfstarratingcolor"=>"#FCBB1D",
    "arfsliderselectioncolor"=>"#d1dee5",
    "arfslidertrackcolor"=>"#bcc7cd",
    "arfplaceholder_opacity" => "0.5",
    "arf_bg_position_x" => "left",
    "arf_bg_position_input_x" => "",
    "arf_bg_position_y" => "top",
    "arf_bg_position_input_y" => "",
);

$new_values1 = maybe_serialize($new_values);
$values['form_css'] = $new_values1;
$form_id = $arfform->create($values);

if (!empty($new_values)) {

    $use_saved = true;

    $arfssl = (is_ssl()) ? 1 : 0;

    $filename = FORMPATH . '/core/css_create_main.php';

    $wp_upload_dir = wp_upload_dir();

    $target_path = $wp_upload_dir['basedir'] . '/arforms/maincss';

    $css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";

    $css .= "\n";

    ob_start();

    include $filename;

    $css .= ob_get_contents();

    ob_end_clean();

    $css .= "\n " . $warn;

    $css_file = $target_path . '/maincss_' . $form_id . '.css';

    WP_Filesystem();
    global $wp_filesystem;
    $css = str_replace('##', '#', $css);
    $wp_filesystem->put_contents($css_file, $css, 0777);

    wp_cache_delete($form_id, 'arfform');

    $filename1 = FORMPATH . '/core/css_create_materialize.php';
    $css1 = $warn1 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css1 .= "\n";
    ob_start();
    include $filename1;
    $css1 .= ob_get_contents();
    ob_end_clean();
    $css1 .= "\n " . $warn1;
    $css_file1 = $target_path . '/maincss_materialize_' . $form_id . '.css';
    WP_Filesystem();
    $css1 = str_replace('##', '#', $css1);
    $wp_filesystem->put_contents($css_file1, $css1, 0777);
    wp_cache_delete($form_id, 'arfform');


    $filename2 = FORMPATH . '/core/css_create_materialize_outline.php';
    $css2 = $warn2 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css2 .= "\n";
    ob_start();
    include $filename2;
    $css2 .= ob_get_contents();
    ob_end_clean();
    $css2 .= "\n" . $warn2;
    $css_file2 = $target_path . '/maincss_materialize_outlined_' . $form_id . '.css';
    WP_Filesystem();
    $css2 = str_replace( '##', '#', $css2 );
    $wp_filesystem->put_contents( $css_file2, $css2, 0777 );
} else {

    $query_results = true;
}


$field_order = array();
$inner_field_order = array();
$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'First Name';
$field_values['field_options']['name'] = 'First Name';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'text';
$field_values['field_options']['description'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter first name', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('First Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 1;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Last Name';
$field_values['field_options']['name'] = 'Last Name';
$field_values['type'] = 'text';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter last name', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Last Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['label'] = 'hidden';
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 2;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['email'];
$field_values['name'] = 'Email';
$field_values['field_options']['name'] = 'Email';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'email';
$field_values['field_options']['description'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter email address', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Email Address', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Please enter a valid email address', 'ARForms'));
$field_values['field_options']['confirm_email_label'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['field_options']['invalid_confirm_email'] = addslashes(esc_html__('Confirm email address does not match with email', 'ARForms'));
$field_values['field_options']['confirm_email_placeholder'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 3;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['url'];
$field_values['name'] = 'Website';
$field_values['field_options']['name'] = 'Website';
$field_values['type'] = 'url';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your website URL', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Website', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Please enter a valid website', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 4;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Address';
$field_values['type'] = 'text';
$field_values['field_options']['name'] = 'Address';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your address', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Address', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 5;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Address Line 2';
$field_values['type'] = 'text';
$field_values['field_options']['name'] = 'Address Line 2';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your address', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Address Line 2', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 6;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'City';
$field_values['type'] = 'text';
$field_values['field_options']['name'] = 'City';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your city', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('City', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 7;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['select'];
$field_values['name'] = 'State';
$field_values['type'] = 'select';
$field_values['field_options']['name'] = 'State';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please select your state', 'ARForms'));
$field_values['options'] = json_encode(array('', 'AL', 'AK', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 8;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Postal Code';
$field_values['type'] = 'text';
$field_values['field_options']['name'] = 'Postal Code';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your postal code', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Postal Code', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 9;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['select'];
$field_values['name'] = 'Country';
$field_values['type'] = 'select';
$field_values['field_options']['name'] = 'Country';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please select your country', 'ARForms'));
$field_values['options'] = json_encode(array('', 'Afghanistan', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo', 'Cook Islands', 'Costa Rica', 'Croatia (Hrvatska)', 'Cuba', 'Cyprus', 'Czech Republic', 'Congo (DRC)', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Islas Malvinas)', 'Faroe Islands', 'Fiji Islands', 'Finland', 'France', 'French Guiana', 'French Polynesia', 'French Southern and Antarctic Lands', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hong Kong SAR', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macao SAR', 'Macedonia, Former Yugoslav Republic of', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia and Montenegro', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'));
$field_values['field_options']['size'] = 1;
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 10;
unset($field_values);
unset($field_id);

unset($values);

$field_options = $wpdb->get_results($wpdb->prepare("SELECT `options` FROM `" . $MdlDb->forms . "` WHERE `id` = %d", $form_id));

$form_opt = maybe_unserialize($field_options[0]->options);

$form_opt['arf_field_order'] = json_encode($field_order);
$form_opt['arf_inner_field_order'] = json_encode( $inner_field_order );

$form_options = maybe_serialize($form_opt);

$wpdb->update($MdlDb->forms, array('options' => $form_options), array('id' => $form_id));

unset($field_order);
unset($inner_field_order);

if( isset($arf_update_templates) && $arf_update_templates == true ){
    $values['id'] = 3;
}
$values['name'] = addslashes(esc_html__('Contact Us', 'ARForms'));
$values['description'] = addslashes(esc_html__('We would like to hear from you. Please send us a message by filling out the form below and we will get back with you shortly.', 'ARForms'));
$values['options']['custom_style'] = 1;
$values['is_template'] = '1';
$values['status'] = 'published';
$values['form_key'] = 'ContactUs';
$values['options']['display_title_form'] = "1";

$new_values = array(
    'arfmainformwidth' => '800',
    'form_width_unit' => 'px',
    'edit_msg' => 'Your submission was successfully saved.',
    'update_value' => 'Update',
    'arfeditoroff' => false,
    'arfmaintemplatepath' => '',
    'csv_format' => 'UTF-8',
    'date_format' => 'MMM D, YYYY',
    'cal_date_format' => 'MMM D, YYYY',
    'arfcalthemecss' => 'default_theme',
    'arfcalthemename' => 'default_theme',
    'theme_nicename' => 'default_theme',
    'permalinks' => false,
    'form_align' => 'left',
    'fieldset' => '2',
    'arfmainfieldsetcolor' => 'd9d9d9',
    'arfmainfieldsetpadding' => '30px 45px 30px 45px',
    'arfmainfieldsetradius' => '6',
    'font' => 'Helvetica',
    'font_other' => '',
    'font_size' => '16',
    'label_color' => '706d70',
    'weight' => 'normal',
    'position' => 'top',
    'hide_labels' => false,
    'align' => 'left',
    'width' => '130',
    'width_unit' => 'px',
    'arfdescfontsetting' => '"Lucida Grande","Lucida Sans Unicode",Tahoma,sans-serif',
    'arfdescfontsizesetting' => '12',
    'arfdesccolorsetting' => '666666',
    'arfdescweightsetting' => 'normal',
    'description_style' => 'normal',
    'arfdescalighsetting' => 'right',
    'field_font_size' => '14',
    'field_width' => '100',
    'field_width_unit' => '%',
    'auto_width' => false,
    'arffieldpaddingsetting' => '2',
    'arffieldmarginssetting' => 18,
    'arffieldanimationdurationsetting' => '0',
    'arfpbfieldanimationdurationsetting' => '0',
    'arffieldanimationdelaysetting' => '0',
    'arfpbfieldanimationdelaysetting' => '0',
    'bg_color' => 'ffffff',
    'text_color' => '17181c',
    'border_color' => 'b0b0b5',
    'arffieldborderwidthsetting' => '1',
    'arffieldborderstylesetting' => 'solid',
    'arfbgactivecolorsetting' => 'ffffff',
    'arfborderactivecolorsetting' => '087ee2',
    'arferrorbgcolorsetting' => 'ffffff',
    'arferrorbordercolorsetting' => 'ed4040',
    'arferrorborderwidthsetting' => '1',
    'arferrorborderstylesetting' => 'solid',
    'arfradioalignsetting' => 'inline',
    'arfcheckboxalignsetting' => 'block',
    'check_font' => 'Helvetica',
    'check_font_other' => '',
    'arfcheckboxfontsizesetting' => '12px',
    'arfcheckboxlabelcolorsetting' => '444444',
    'check_weight' => 'normal',
    'arfsubmitbuttonstylesetting' => false,
    'arfsubmitbuttonfontsizesetting' => '18',
    'arfsubmitbuttonwidthsetting' => 120,
    'arfsubmitbuttonheightsetting' => 40,
    'submit_bg_color' => '077BDD',
    'arfsubmitbuttonbgcolorhoversetting' => '0b68b7',
    'arfsubmitbgcolor2setting' => '',
    'arfsubmitbordercolorsetting' => 'f6f6f8',
    'arfsubmitborderwidthsetting' => '0',
    'arfsubmitboxxoffsetsetting' => '1', 
    'arfsubmitboxyoffsetsetting' => '2',
    'arfsubmitboxblursetting' => '3',
    'arfsubmitboxshadowsetting' => '0',
    'arfsubmittextcolorsetting' => 'ffffff',
    'arfsubmitweightsetting' => 'bold',
    'arfsubmitborderradiussetting' => '3',
    'submit_bg_img' => '',
    'submit_hover_bg_img' => '',
    'arfsubmitbuttonmarginsetting' => '10px 10px 0px 0px',
    'arfsubmitbuttonpaddingsetting' => '8',
    'arfsubmitshadowcolorsetting' => 'c6c8cc',
    'border_radius' => 2,
    'arferroriconsetting' => 'e1.png',
    'arferrorbgsetting' => 'F3CAC7',
    'arferrorbordersetting' => 'FA8B83',
    'arferrortextsetting' => '501411',
    'arffontsizesetting' => '14',
    'arfsucessiconsetting' => 's1.png',
    'success_bg' => NULL,
    'success_border' => NULL,
    'success_text' => NULL,
    'arfsucessfontsizesetting' => '14',
    'arftextareafontsizesetting' => '13px',
    'arftextareawidthsetting' => '400',
    'arftextareawidthunitsetting' => 'px',
    'arftextareapaddingsetting' => '2',
    'arftextareamarginsetting' => '20',
    'arftextareabgcolorsetting' => 'ffffff',
    'arftextareacolorsetting' => '444444',
    'arftextareabordercolorsetting' => 'dddddd',
    'arftextareaborderwidthsetting' => '1',
    'arftextareaborderstylesetting' => 'solid',
    'text_direction' => '1',
    'arffieldheightsetting' => '24',
    'arfmainformtitlecolorsetting' => '#0d0e12',
    'form_title_font_size' => '28',
    'error_font' => 'Lucida Sans Unicode',
    'error_font_other' => '',
    'arfactivebgcolorsetting' => 'FFFF00',
    'arfmainformbgcolorsetting' => 'ffffff',
    'arfmainformtitleweightsetting' => 'normal',
    'arfmainformtitlepaddingsetting' => '0px 0px 20px 0px',
    'arfmainformbordershadowcolorsetting' => 'f2f2f2',
    'form_border_shadow' => 'flat',
    'arfsubmitalignsetting' => 'left',
    'checkbox_radio_style' => '1',
    'bg_color_pg_break' => '087ee2',
    'bg_inavtive_color_pg_break' => '7ec3fc',
    'text_color_pg_break' => 'ffffff',
    'text_color_pg_break_style3' => '087ee2',
    'arfmainform_bg_img' => '',
    'arfmainform_opacity' => '1',
    'arfmainfield_opacity' => '0',
    'arfsubmitfontfamily' => 'Helvetica',
    'arfmainfieldsetpadding_1' => '30',
    'arfmainfieldsetpadding_2' => '45',
    'arfmainfieldsetpadding_3' => '30',
    'arfmainfieldsetpadding_4' => '45',
    'arfmainformtitlepaddingsetting_1' => '0',
    'arfmainformtitlepaddingsetting_2' => '0',
    'arfmainformtitlepaddingsetting_3' => 30,
    'arfmainformtitlepaddingsetting_4' => '0',
    'arffieldinnermarginssetting_1' => 10,
    'arffieldinnermarginssetting_2' => '10',
    'arffieldinnermarginssetting_3' => 10,
    'arffieldinnermarginssetting_4' => '10',
    'arfsubmitbuttonmarginsetting_1' => '10',
    'arfsubmitbuttonmarginsetting_2' => '10',
    'arfsubmitbuttonmarginsetting_3' => '0',
    'arfsubmitbuttonmarginsetting_4' => '0',
    'arfcheckradiostyle' => 'flat',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfcheckradiocolor' => 'blue',
    'arf_checked_checkbox_icon' => '',
    'enable_arf_checkbox' => '0',
    'arf_checked_radio_icon' => '',
    'enable_arf_radio' => '0',
    'checked_checkbox_icon_color' => '#0C7CD5',
    'checked_radio_icon_color' => '#0C7CD5',
    'arfformtitlealign' => 'left',
    'arferrorstyle' => 'advance',
    'arferrorstylecolor' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstylecolor2' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstyleposition' => 'bottom',
    'arfsubmitautowidth' => '100',
    'arftitlefontfamily' => 'Helvetica',
    'bar_color_survey' => '#007ee4',
    'bg_color_survey' => '#dadde2',
    'text_color_survey' => '#333333',
    'prefix_suffix_bg_color' => '#e7e8ec',
    'prefix_suffix_icon_color' => '#808080',
    'arfsectionpaddingsetting_1' => '20',
    'arfsectionpaddingsetting_2' => '0',
    'arfsectionpaddingsetting_3' => '20',
    'arfsectionpaddingsetting_4' => '20',
    'arfsectionpaddingsetting' => "20px 0px 20px 20px",
    'arffieldinnermarginssetting' => '8px 10px 8px 10px',
    'arfsucessbgcolorsetting' => '#E0FDE2',
    'arfsucessbordercolorsetting' => '#BFE0C1',
    'arfsucesstextcolorsetting' => '#4C4D4E',
    'arfformerrorbgcolorsetting' => '#FDECED',
    'arfformerrorbordercolorsetting' => '#F9CFD1',
    'arfformerrortextcolorsetting' => '#ED4040',
    "arfsubmitbuttonstyle"=>"border",
    'arfinputstyle' => 'standard',
    'arfcheckradiostyle' => 'default',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfmainform_color_skin' => 'blue',
    'arf_tooltip_bg_color' => '#000000',
    'arf_tooltip_font_color' => '#ffffff',
    "arfcommonfont"=>"Helvetica",
    "arfmainfieldcommonsize"=>"3",
    "arfvalidationbgcolorsetting"=>"#ed4040",
    "arfvalidationtextcolorsetting"=>"#ffffff",
    "arfdatepickerbgcolorsetting"=>"#007ee4",
    "arfdatepickertextcolorsetting"=>"#000000",
    "arfsectiontitlefamily"=>"Helvetica",
    "arfsectiontitlefontsizesetting"=>"16",
    "arfsectiontitleweightsetting"=>"bold",
    "arfsubmitbuttontext"=>"Submit",
    "arfuploadbtntxtcolorsetting"=>"#FFFFFF",
    "arfuploadbtnbgcolorsetting" =>"#0C7CD5",
    "arf_req_indicator"=>"0",
    "arf_section_inherit_bg" => "1",
    "arfformsectionbackgroundcolor"=>"#ffffff",
    "arfmainbasecolor" => "#0c7cd5",
    "arflikebtncolor"=>"#4786ff",
    "arfdislikebtncolor"=>"#ec3838",
    "arfstarratingcolor"=>"#FCBB1D",
    "arfsliderselectioncolor"=>"#d1dee5",
    "arfslidertrackcolor"=>"#bcc7cd",
    "arfplaceholder_opacity" => "0.5",
    "arf_bg_position_x" => "left",
    "arf_bg_position_input_x" => "",
    "arf_bg_position_y" => "top",
    "arf_bg_position_input_y" => "",
);

$new_values1 = maybe_serialize($new_values);
$values['form_css'] = $new_values1;
$form_id = $arfform->create($values);
if (!empty($new_values)) {

    $use_saved = true;

    $arfssl = (is_ssl()) ? 1 : 0;

    $filename = FORMPATH . '/core/css_create_main.php';

    $wp_upload_dir = wp_upload_dir();

    $target_path = $wp_upload_dir['basedir'] . '/arforms/maincss';

    $css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";


    $css .= "\n";


    ob_start();


    include $filename;


    $css .= ob_get_contents();


    ob_end_clean();


    $css .= "\n " . $warn;

    $css_file = $target_path . '/maincss_' . $form_id . '.css';

    WP_Filesystem();
    global $wp_filesystem;
    $css = str_replace('##', '#', $css);
    $wp_filesystem->put_contents($css_file, $css, 0777);

    wp_cache_delete($form_id, 'arfform');

    $filename1 = FORMPATH . '/core/css_create_materialize.php';
    $css1 = $warn1 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css1 .= "\n";
    ob_start();
    include $filename1;
    $css1 .= ob_get_contents();
    ob_end_clean();
    $css1 .= "\n " . $warn1;
    $css_file1 = $target_path . '/maincss_materialize_' . $form_id . '.css';
    WP_Filesystem();
    $css1 = str_replace('##', '#', $css1);
    $wp_filesystem->put_contents($css_file1, $css1, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename2 = FORMPATH . '/core/css_create_materialize_outline.php';
    $css2 = $warn2 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css2 .= "\n";
    ob_start();
    include $filename2;
    $css2 .= ob_get_contents();
    ob_end_clean();
    $css2 .= "\n" . $warn2;
    $css_file2 = $target_path . '/maincss_materialize_outlined_' . $form_id . '.css';
    WP_Filesystem();
    $css2 = str_replace( '##', '#', $css2 );
    $wp_filesystem->put_contents( $css_file2, $css2, 0777 );
} else {

    $query_results = true;
}

$field_order = array();
$inner_field_order = array();
$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'First Name';
$field_values['field_options']['name'] = 'First Name';
$field_values['type'] = 'text';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter first name', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('First Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));

$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 1;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Last Name';
$field_values['type'] = 'text';
$field_values['field_options']['name'] = 'Last Name';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter last name', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Last Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['label'] = 'hidden';
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 2;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['email'];
$field_values['name'] = addslashes(esc_html__('Email', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Email', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'email';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter email address', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Email Address', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Please enter a valid email address', 'ARForms'));
$field_values['field_options']['confirm_email_label'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['field_options']['invalid_confirm_email'] = addslashes(esc_html__('Confirm email address does not match with email', 'ARForms'));
$field_values['field_options']['confirm_email_placeholder'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 3;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['url'];
$field_values['name'] = addslashes(esc_html__('Website', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Website', 'ARForms'));
$field_values['type'] = 'url';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your website URL', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Website', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Please enter a valid website', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 4;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = addslashes(esc_html__('Subject', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Subject', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'text';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter subject', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Subject', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 5;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['textarea'];
$field_values['name'] = addslashes(esc_html__('Message', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Message', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'textarea';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your message', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Message', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 6;
unset($field_values);
unset($field_id);

unset($values);

$field_options = $wpdb->get_results($wpdb->prepare("SELECT `options` FROM `" . $MdlDb->forms . "` WHERE `id` = %d", $form_id));

$form_opt = maybe_unserialize($field_options[0]->options);

$form_opt['arf_field_order'] = json_encode($field_order);
$form_opt['arf_inner_field_order'] = json_encode( $inner_field_order );

$form_options = maybe_serialize($form_opt);

$wpdb->update($MdlDb->forms, array('options' => $form_options), array('id' => $form_id));

unset($field_order);
unset($inner_field_order);


if( isset($arf_update_templates) && $arf_update_templates == true ){
    $values['id'] = 4;
}
$values['name'] = 'Survey Form';
$values['description'] = 'Gather User information';
$values['options']['custom_style'] = 1;
$values['is_template'] = '1';
$values['status'] = 'published';
$values['form_key'] = 'Survey';
$values['options']['display_title_form'] = "1";
$values['options']['arf_form_title'] = "border-bottom:1px solid #4a494a;padding-bottom:5px;";

$new_values = array(
    'arfmainformwidth' => '800',
    'form_width_unit' => 'px',
    'edit_msg' => 'Your submission was successfully saved.',
    'update_value' => 'Update',
    'arfeditoroff' => false,
    'arfmaintemplatepath' => '',
    'csv_format' => 'UTF-8',
    'date_format' => 'MMM D, YYYY',
    'cal_date_format' => 'MMM D, YYYY',
    'arfcalthemecss' => 'default_theme',
    'arfcalthemename' => 'default_theme',
    'theme_nicename' => 'default_theme',
    'permalinks' => false,
    'form_align' => 'left',
    'fieldset' => '0',
    'arfmainfieldsetcolor' => 'd9d9d9',
    'arfmainfieldsetpadding' => '30px 45px 30px 45px',
    'arfmainfieldsetradius' => '6',
    'font' => 'Helvetica',
    'font_other' => '',
    'font_size' => '16',
    'label_color' => '706d70',
    'weight' => 'normal',
    'position' => 'top',
    'hide_labels' => false,
    'align' => 'left',
    'width' => '130',
    'width_unit' => 'px',
    'arfdescfontsetting' => '"Lucida Grande","Lucida Sans Unicode",Tahoma,sans-serif',
    'arfdescfontsizesetting' => '12',
    'arfdesccolorsetting' => '666666',
    'arfdescweightsetting' => 'normal',
    'description_style' => 'normal',
    'arfdescalighsetting' => 'right',
    'field_font_size' => '14',
    'field_width' => '100',
    'field_width_unit' => '%',
    'auto_width' => false,
    'arffieldpaddingsetting' => '2',
    'arffieldmarginssetting' => '23',
    'arffieldanimationdurationsetting' => '0',
    'arfpbfieldanimationdurationsetting' => '0',
    'arffieldanimationdelaysetting' => '0',
    'arfpbfieldanimationdelaysetting' => '0',
    'bg_color' => 'ffffff',
    'text_color' => '17181c',
    'border_color' => 'b0b0b5',
    'arffieldborderwidthsetting' => '1',
    'arffieldborderstylesetting' => 'solid',
    'arfbgactivecolorsetting' => 'ffffff',
    'arfborderactivecolorsetting' => '087ee2',
    'arferrorbgcolorsetting' => 'ffffff',
    'arferrorbordercolorsetting' => 'ed4040',
    'arferrorborderwidthsetting' => '1',
    'arferrorborderstylesetting' => 'solid',
    'arfradioalignsetting' => 'inline',
    'arfcheckboxalignsetting' => 'block',
    'check_font' => 'Helvetica',
    'check_font_other' => '',
    'arfcheckboxfontsizesetting' => '12px',
    'arfcheckboxlabelcolorsetting' => '444444',
    'check_weight' => 'normal',
    'arfsubmitbuttonstylesetting' => false,
    'arfsubmitbuttonfontsizesetting' => '18',
    'arfsubmitbuttonwidthsetting' => '',
    'arfsubmitbuttonheightsetting' => '38',
    'submit_bg_color' => '077BDD',
    'arfsubmitbuttonbgcolorhoversetting' => '0b68b7',
    'arfsubmitbgcolor2setting' => '',
    'arfsubmitbordercolorsetting' => 'f6f6f8',
    'arfsubmitborderwidthsetting' => '0',
    'arfsubmitboxxoffsetsetting' => '1', 
    'arfsubmitboxyoffsetsetting' => '2',
    'arfsubmitboxblursetting' => '3',
    'arfsubmitboxshadowsetting' => '0',
    'arfsubmittextcolorsetting' => 'ffffff',
    'arfsubmitweightsetting' => 'bold',
    'arfsubmitborderradiussetting' => '3',
    'submit_bg_img' => '',
    'submit_hover_bg_img' => '',
    'arfsubmitbuttonmarginsetting' => '10px 10px 0px 0px',
    'arfsubmitbuttonpaddingsetting' => '8',
    'arfsubmitshadowcolorsetting' => 'c6c8cc',
    'border_radius' => '3',
    'arferroriconsetting' => 'e1.png',
    'arferrorbgsetting' => 'F3CAC7',
    'arferrorbordersetting' => 'FA8B83',
    'arferrortextsetting' => '501411',
    'arffontsizesetting' => '14',
    'arfsucessiconsetting' => 's1.png',
    'success_bg' => NULL,
    'success_border' => NULL,
    'success_text' => NULL,
    'arfsucessfontsizesetting' => '14',
    'arftextareafontsizesetting' => '13px',
    'arftextareawidthsetting' => '400',
    'arftextareawidthunitsetting' => 'px',
    'arftextareapaddingsetting' => '2',
    'arftextareamarginsetting' => '20',
    'arftextareabgcolorsetting' => 'ffffff',
    'arftextareacolorsetting' => '444444',
    'arftextareabordercolorsetting' => 'dddddd',
    'arftextareaborderwidthsetting' => '1',
    'arftextareaborderstylesetting' => 'solid',
    'text_direction' => '1',
    'arffieldheightsetting' => '24',
    'arfmainformtitlecolorsetting' => '4a494a',
    'form_title_font_size' => '32',
    'error_font' => 'Lucida Sans Unicode',
    'error_font_other' => '',
    'arfactivebgcolorsetting' => 'FFFF00',
    'arfmainformbgcolorsetting' => 'ffffff',
    'arfmainformtitleweightsetting' => 'normal',
    'arfmainformtitlepaddingsetting' => '0px 0px 20px 0px',
    'arfmainformbordershadowcolorsetting' => 'f2f2f2',
    'form_border_shadow' => 'flat',
    'arfsubmitalignsetting' => 'left',
    'checkbox_radio_style' => '1',
    'bg_color_pg_break' => '087ee2',
    'bg_inavtive_color_pg_break' => '7ec3fc',
    'text_color_pg_break' => 'ffffff',
    'text_color_pg_break_style3' => '087ee2',
    'arfmainform_bg_img' => '',
    'arfmainform_opacity' => '1',
    'arfmainfield_opacity' => '0',
    'arfsubmitfontfamily' => 'Helvetica',
    'arfmainfieldsetpadding_1' => '30',
    'arfmainfieldsetpadding_2' => '45',
    'arfmainfieldsetpadding_3' => '30',
    'arfmainfieldsetpadding_4' => '45',
    'arfmainformtitlepaddingsetting_1' => '0',
    'arfmainformtitlepaddingsetting_2' => '0',
    'arfmainformtitlepaddingsetting_3' => '20',
    'arfmainformtitlepaddingsetting_4' => '0',
    'arffieldinnermarginssetting_1' => '8',
    'arffieldinnermarginssetting_2' => '10',
    'arffieldinnermarginssetting_3' => '8',
    'arffieldinnermarginssetting_4' => '10',
    'arfsubmitbuttonmarginsetting_1' => '10',
    'arfsubmitbuttonmarginsetting_2' => '10',
    'arfsubmitbuttonmarginsetting_3' => '0',
    'arfsubmitbuttonmarginsetting_4' => '0',
    'arfcheckradiostyle' => 'flat',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfcheckradiocolor' => 'blue',
    'arf_checked_checkbox_icon' => '',
    'enable_arf_checkbox' => '0',
    'arf_checked_radio_icon' => '',
    'enable_arf_radio' => '0',
    'checked_checkbox_icon_color' => '#0C7CD5',
    'checked_radio_icon_color' => '#0C7CD5',
    'arfformtitlealign' => 'center',
    'arferrorstyle' => 'advance',
    'arferrorstylecolor' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstylecolor2' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstyleposition' => 'bottom',
    'arfsubmitautowidth' => '100',
    'arftitlefontfamily' => 'Helvetica',
    'bar_color_survey' => '#007ee4',
    'bg_color_survey' => '#dadde2',
    'text_color_survey' => '#333333',
    'prefix_suffix_bg_color' => '#e7e8ec',
    'prefix_suffix_icon_color' => '#808080',
    'arfsectionpaddingsetting_1' => '20',
    'arfsectionpaddingsetting_2' => '0',
    'arfsectionpaddingsetting_3' => '20',
    'arfsectionpaddingsetting_4' => '20',
    'arfsectionpaddingsetting' => "20px 0px 20px 20px",
    'arffieldinnermarginssetting' => '8px 10px 8px 10px',
    'arfsucessbgcolorsetting' => '#E0FDE2',
    'arfsucessbordercolorsetting' => '#BFE0C1',
    'arfsucesstextcolorsetting' => '#4C4D4E',
    'arfformerrorbgcolorsetting' => '#FDECED',
    'arfformerrorbordercolorsetting' => '#F9CFD1',
    'arfformerrortextcolorsetting' => '#ED4040',
    'check_weight_form_title' => 'bold',
    "arfsubmitbuttonstyle"=>"border",
    'arfinputstyle' => 'standard',
    'arfcheckradiostyle' => 'default',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfmainform_color_skin' => 'blue',
    'arf_tooltip_bg_color' => '#000000',
    'arf_tooltip_font_color' => '#ffffff',
    "arfcommonfont"=>"Helvetica",
    "arfmainfieldcommonsize"=>"3",
    "arfvalidationbgcolorsetting"=>"#ed4040",
    "arfvalidationtextcolorsetting"=>"#ffffff",
    "arfdatepickerbgcolorsetting"=>"#007ee4",
    "arfdatepickertextcolorsetting"=>"#000000",
    "arfsectiontitlefamily"=>"Helvetica",
    "arfsectiontitlefontsizesetting"=>"16",
    "arfsectiontitleweightsetting"=>"bold",
    "arfsubmitbuttontext"=>"Submit",
    "arfuploadbtntxtcolorsetting"=>"#FFFFFF",
    "arfuploadbtnbgcolorsetting" =>"#0C7CD5",
    "arf_req_indicator"=>"0",
    "arf_section_inherit_bg" => "1",
    "arfformsectionbackgroundcolor"=>"#ffffff",
    "arfmainbasecolor" => "#0c7cd5",
    "arflikebtncolor"=>"#4786ff",
    "arfdislikebtncolor"=>"#ec3838",
    "arfstarratingcolor"=>"#FCBB1D",
    "arfsliderselectioncolor"=>"#d1dee5",
    "arfslidertrackcolor"=>"#bcc7cd",
    "arfplaceholder_opacity" => "0.5",
    "arf_bg_position_x" => "left",
    "arf_bg_position_input_x" => "",
    "arf_bg_position_y" => "top",
    "arf_bg_position_input_y" => "",
);

$new_values1 = maybe_serialize($new_values);
$values['form_css'] = $new_values1;
$form_id = $arfform->create($values);
if (!empty($new_values)) {
    
    $use_saved = true;

    $arfssl = (is_ssl()) ? 1 : 0;

    $filename = FORMPATH . '/core/css_create_main.php';

    $wp_upload_dir = wp_upload_dir();

    $target_path = $wp_upload_dir['basedir'] . '/arforms/maincss';

    $css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";


    $css .= "\n";


    ob_start();


    include $filename;


    $css .= ob_get_contents();


    ob_end_clean();


    $css .= "\n " . $warn;

    $css_file = $target_path . '/maincss_' . $form_id . '.css';

    WP_Filesystem();
    global $wp_filesystem;
    $css = str_replace('##', '#', $css);
    $wp_filesystem->put_contents($css_file, $css, 0777);

    wp_cache_delete($form_id, 'arfform');

    $filename1 = FORMPATH . '/core/css_create_materialize.php';
    $css1 = $warn1 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css1 .= "\n";
    ob_start();
    include $filename1;
    $css1 .= ob_get_contents();
    ob_end_clean();
    $css1 .= "\n " . $warn1;
    $css_file1 = $target_path . '/maincss_materialize_' . $form_id . '.css';
    WP_Filesystem();
    $css1 = str_replace('##', '#', $css1);
    $wp_filesystem->put_contents($css_file1, $css1, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename2 = FORMPATH . '/core/css_create_materialize_outline.php';
    $css2 = $warn2 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css2 .= "\n";
    ob_start();
    include $filename2;
    $css2 .= ob_get_contents();
    ob_end_clean();
    $css2 .= "\n" . $warn2;
    $css_file2 = $target_path . '/maincss_materialize_outlined_' . $form_id . '.css';
    WP_Filesystem();
    $css2 = str_replace( '##', '#', $css2 );
    $wp_filesystem->put_contents( $css_file2, $css2, 0777 );
} else {

    $query_results = true;
}

$field_order = array();
$inner_field_order = array();
$field_values = array();
$field_values['field_options'] = $field_data_obj['radio'];
$field_values['field_key'] = '1';
$field_values['name'] = '1. When you visit ARForms, do you see it as... (choose one)';
$field_values['field_options']['name'] = '1. When you visit ARForms, do you see it as... (choose one)';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'radio';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['options'] = json_encode(array('Problem solvers', 'An inspiration', 'Ideas generator', 'Solution'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 1;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['checkbox'];
$field_values['field_key'] = '2';
$field_values['name'] = '2. Which words best describe ARForms? (choose as many that apply)';
$field_values['field_options']['name'] = '2. Which words best describe ARForms? (choose as many that apply)';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'checkbox';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['field_options']['separate_value'] = "false";
$field_values['options'] = json_encode(array('Unhelpful', 'Difficult to use', 'Supportive', 'Solutions focused', 'Good value', 'Global', 'Community based', 'Friendly', 'Creative', 'Inspiring', 'Developer world'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 2;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['radio'];
$field_values['field_key'] = '3';
$field_values['name'] = '3. Which best describes your relationship with ARForms?';
$field_values['field_options']['name'] = '3. Which best describes your relationship with ARForms?';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'radio';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['separate_value'] = "false";
$field_values['options'] = json_encode(array('I am aware of it', 'Rarely use it', 'Use it sometimes', 'Frequent user', 'Do not know it'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 3;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['radio'];
$field_values['field_key'] = '4';
$field_values['name'] = '4. When I visit ARForms for something I need to work on, I feel...(choose one)';
$field_values['field_options']['name'] = '4. When I visit ARForms for something I need to work on, I feel...(choose one)';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'radio';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['separate_value'] = "false";
$field_values['options'] = json_encode(array('Concerned I won\'t be able to find what I am looking for', 'Inspired', 'Reluctant', 'Indifferent', 'Excited to be starting a project', 'Know I will end up browsing lots of things'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 4;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['radio'];
$field_values['field_key'] = '5';
$field_values['name'] = '5. Which of the following best describes your area of work?';
$field_values['field_options']['name'] = '5. Which of the following best describes your area of work?';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'radio';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['separate_value'] = "false";
$field_values['options'] = json_encode(array('Administrative', 'Computing', 'Web Design', 'Creative', 'Web Development', 'Marketing', 'Technical'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 5;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['radio'];
$field_values['name'] = '6. How often do you use ARForms?';
$field_values['field_options']['name'] = '6. How often do you use ARForms?';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'radio';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['separate_value'] = "false";
$field_values['options'] = json_encode(array('It is my first time', 'Weekly', 'Monthly', 'Quarterly', 'Annually', 'Occasionally'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 6;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['textarea'];
$field_values['name'] = 'Other Comments About ARForms';
$field_values['field_options']['name'] = 'Other Comments About ARForms';
$field_values['type'] = 'textarea';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your comments', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Comments', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 7;
unset($field_values);
unset($field_id);
unset($values);

$field_options = $wpdb->get_results($wpdb->prepare("SELECT `options` FROM `" . $MdlDb->forms . "` WHERE `id` = %d", $form_id));


$form_opt = maybe_unserialize($field_options[0]->options);

$form_opt['arf_field_order'] = json_encode($field_order);
$form_opt['arf_inner_field_order'] = json_encode( $inner_field_order );

$form_opt['arf_form_other_css'] = '.arf_form_outer_wrapper.ar_main_div_'.$form_id.' .arf_fieldset .formtitle_style{border-bottom:1px solid #4a494a !important;}.arf_form_outer_wrapper.ar_main_div_'.$form_id.' .arf_fieldset .arfformfield.arf_field_type_radio .controls{ padding-top:10px !important;padding-left:20px !important; }.arf_form_outer_wrapper.ar_main_div_'.$form_id.' .arf_fieldset .arfformfield.arf_field_type_checkbox .controls{ padding-top:10px !important;padding-left:20px !important; }';


$form_options = maybe_serialize($form_opt);

$wpdb->update($MdlDb->forms, array('options' => $form_options), array('id' => $form_id));

unset($field_order);
unset($inner_field_order);

$field_order = array();
$inner_field_order = array();
if( isset($arf_update_templates) && $arf_update_templates == true ){
    $values['id'] = 5;
}
$values['name'] = 'Feedback Form';
$values['description'] = 'Gather User information';
$values['options']['custom_style'] = 1;
$values['is_template'] = '1';
$values['status'] = 'published';
$values['form_key'] = 'Feedback';

$new_values = array(
    'arfmainformwidth' => '800',
    'form_width_unit' => 'px',
    'edit_msg' => 'Your submission was successfully saved.',
    'update_value' => 'Update',
    'arfeditoroff' => false,
    'arfmaintemplatepath' => '',
    'csv_format' => 'UTF-8',
    'date_format' => 'MMM D, YYYY',
    'cal_date_format' => 'MMM D, YYYY',
    'arfcalthemecss' => 'default_theme',
    'arfcalthemename' => 'default_theme',
    'theme_nicename' => 'default_theme',
    'permalinks' => false,
    'form_align' => 'left',
    'fieldset' => '2',
    'arfmainfieldsetcolor' => 'd9d9d9',
    'arfmainfieldsetpadding' => '30px 45px 30px 45px',
    'arfmainfieldsetradius' => '6',
    'font' => 'Helvetica',
    'font_other' => '',
    'font_size' => '16',
    'label_color' => '706d70',
    'weight' => 'normal',
    'position' => 'top',
    'hide_labels' => false,
    'align' => 'left',
    'width' => '130',
    'width_unit' => 'px',
    'arfdescfontsetting' => '"Lucida Grande","Lucida Sans Unicode",Tahoma,sans-serif',
    'arfdescfontsizesetting' => '12',
    'arfdesccolorsetting' => '666666',
    'arfdescweightsetting' => 'normal',
    'description_style' => 'normal',
    'arfdescalighsetting' => 'right',
    'field_font_size' => '14',
    'field_width' => '100',
    'field_width_unit' => '%',
    'auto_width' => false,
    'arffieldpaddingsetting' => '2',
    'arffieldmarginssetting' => '23',
    'arffieldanimationdurationsetting' => '0',
    'arfpbfieldanimationdurationsetting' => '0',
    'arffieldanimationdelaysetting' => '0',
    'arfpbfieldanimationdelaysetting' => '0',
    'bg_color' => 'ffffff',
    'text_color' => '17181c',
    'border_color' => 'b0b0b5',
    'arffieldborderwidthsetting' => '1',
    'arffieldborderstylesetting' => 'solid',
    'arfbgactivecolorsetting' => 'ffffff',
    'arfborderactivecolorsetting' => '087ee2',
    'arferrorbgcolorsetting' => 'ffffff',
    'arferrorbordercolorsetting' => 'ed4040',
    'arferrorborderwidthsetting' => '1',
    'arferrorborderstylesetting' => 'solid',
    'arfradioalignsetting' => 'inline',
    'arfcheckboxalignsetting' => 'block',
    'check_font' => 'Helvetica',
    'check_font_other' => '',
    'arfcheckboxfontsizesetting' => '12px',
    'arfcheckboxlabelcolorsetting' => '444444',
    'check_weight' => 'normal',
    'arfsubmitbuttonstylesetting' => false,
    'arfsubmitbuttonfontsizesetting' => '18',
    'arfsubmitbuttonwidthsetting' => '',
    'arfsubmitbuttonheightsetting' => '38',
    'submit_bg_color' => '077BDD',
    'arfsubmitbuttonbgcolorhoversetting' => '0b68b7',
    'arfsubmitbgcolor2setting' => '',
    'arfsubmitbordercolorsetting' => 'f6f6f8',
    'arfsubmitborderwidthsetting' => '0',
    'arfsubmitboxxoffsetsetting' => '1', 
    'arfsubmitboxyoffsetsetting' => '2',
    'arfsubmitboxblursetting' => '3',
    'arfsubmitboxshadowsetting' => '0',
    'arfsubmittextcolorsetting' => 'ffffff',
    'arfsubmitweightsetting' => 'bold',
    'arfsubmitborderradiussetting' => '3',
    'submit_bg_img' => '',
    'submit_hover_bg_img' => '',
    'arfsubmitbuttonmarginsetting' => '10px 10px 0px 0px',
    'arfsubmitbuttonpaddingsetting' => '8',
    'arfsubmitshadowcolorsetting' => 'c6c8cc',
    'border_radius' => '3',
    'arferroriconsetting' => 'e1.png',
    'arferrorbgsetting' => 'F3CAC7',
    'arferrorbordersetting' => 'FA8B83',
    'arferrortextsetting' => '501411',
    'arffontsizesetting' => '14',
    'arfsucessiconsetting' => 's1.png',
    'success_bg' => NULL,
    'success_border' => NULL,
    'success_text' => NULL,
    'arfsucessfontsizesetting' => '14',
    'arftextareafontsizesetting' => '13px',
    'arftextareawidthsetting' => '400',
    'arftextareawidthunitsetting' => 'px',
    'arftextareapaddingsetting' => '2',
    'arftextareamarginsetting' => '20',
    'arftextareabgcolorsetting' => 'ffffff',
    'arftextareacolorsetting' => '444444',
    'arftextareabordercolorsetting' => 'dddddd',
    'arftextareaborderwidthsetting' => '1',
    'arftextareaborderstylesetting' => 'solid',
    'text_direction' => '1',
    'arffieldheightsetting' => '24',
    'arfmainformtitlecolorsetting' => '4a494a',
    'form_title_font_size' => '28',
    'error_font' => 'Lucida Sans Unicode',
    'error_font_other' => '',
    'arfactivebgcolorsetting' => 'FFFF00',
    'arfmainformbgcolorsetting' => 'ffffff',
    'arfmainformtitleweightsetting' => 'normal',
    'arfmainformtitlepaddingsetting' => '0px 0px 20px 0px',
    'arfmainformbordershadowcolorsetting' => 'f2f2f2',
    'form_border_shadow' => 'flat',
    'arfsubmitalignsetting' => 'left',
    'checkbox_radio_style' => '1',
    'bg_color_pg_break' => '087ee2',
    'bg_inavtive_color_pg_break' => '7ec3fc',
    'text_color_pg_break' => 'ffffff',
    'text_color_pg_break_style3' => '087ee2',
    'arfmainform_bg_img' => '',
    'arfmainform_opacity' => '1',
    'arfmainfield_opacity' => '0',
    'arfsubmitfontfamily' => 'Helvetica',
    'arfmainfieldsetpadding_1' => '30',
    'arfmainfieldsetpadding_2' => '45',
    'arfmainfieldsetpadding_3' => '30',
    'arfmainfieldsetpadding_4' => '45',
    'arfmainformtitlepaddingsetting_1' => '0',
    'arfmainformtitlepaddingsetting_2' => '0',
    'arfmainformtitlepaddingsetting_3' => '20',
    'arfmainformtitlepaddingsetting_4' => '0',
    'arffieldinnermarginssetting_1' => '8',
    'arffieldinnermarginssetting_2' => '10',
    'arffieldinnermarginssetting_3' => '8',
    'arffieldinnermarginssetting_4' => '10',
    'arfsubmitbuttonmarginsetting_1' => '10',
    'arfsubmitbuttonmarginsetting_2' => '10',
    'arfsubmitbuttonmarginsetting_3' => '0',
    'arfsubmitbuttonmarginsetting_4' => '0',
    'arfcheckradiostyle' => 'flat',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfcheckradiocolor' => 'blue',
    'arf_checked_checkbox_icon' => '',
    'enable_arf_checkbox' => '0',
    'arf_checked_radio_icon' => '',
    'enable_arf_radio' => '0',
    'checked_checkbox_icon_color' => '#0C7CD5',
    'checked_radio_icon_color' => '#0C7CD5',
    'arfformtitlealign' => 'left',
    'arferrorstyle' => 'advance',
    'arferrorstylecolor' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstylecolor2' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstyleposition' => 'bottom',
    'arfsubmitautowidth' => '100',
    'arftitlefontfamily' => 'Helvetica',
    'bar_color_survey' => '#007ee4',
    'bg_color_survey' => '#dadde2',
    'text_color_survey' => '#333333',
    'prefix_suffix_bg_color' => '#e7e8ec',
    'prefix_suffix_icon_color' => '#808080',
    'arfsectionpaddingsetting_1' => '20',
    'arfsectionpaddingsetting_2' => '0',
    'arfsectionpaddingsetting_3' => '20',
    'arfsectionpaddingsetting_4' => '20',
    'arfsectionpaddingsetting' => "20px 0px 20px 20px",
    'arffieldinnermarginssetting' => '8px 10px 8px 10px',
    'arfsucessbgcolorsetting' => '#E0FDE2',
    'arfsucessbordercolorsetting' => '#BFE0C1',
    'arfsucesstextcolorsetting' => '#4C4D4E',
    'arfformerrorbgcolorsetting' => '#FDECED',
    'arfformerrorbordercolorsetting' => '#F9CFD1',
    'arfformerrortextcolorsetting' => '#ED4040',
    'arfinputstyle' => 'standard',
    'arfcheckradiostyle' => 'default',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfmainform_color_skin' => 'blue',
    'arf_tooltip_bg_color' => '#000000',
    'arf_tooltip_font_color' => '#ffffff',
    "arfcommonfont"=>"Helvetica",
    "arfmainfieldcommonsize"=>"3",
    "arfvalidationbgcolorsetting"=>"#ed4040",
    "arfvalidationtextcolorsetting"=>"#ffffff",
    "arfdatepickerbgcolorsetting"=>"#007ee4",
    "arfdatepickertextcolorsetting"=>"#000000",
    "arfsectiontitlefamily"=>"Helvetica",
    "arfsectiontitlefontsizesetting"=>"16",
    "arfsectiontitleweightsetting"=>"bold",
    "arfsubmitbuttontext"=>"Submit",
    "arfuploadbtntxtcolorsetting"=>"#FFFFFF",
    "arfuploadbtnbgcolorsetting" =>"#0C7CD5",
    "arf_req_indicator"=>"0",
    "arf_section_inherit_bg" => "1",
    "arfformsectionbackgroundcolor"=>"#ffffff",
    "arfmainbasecolor" => "#0c7cd5",
    "arflikebtncolor"=>"#4786ff",
    "arfdislikebtncolor"=>"#ec3838",
    "arfstarratingcolor"=>"#FCBB1D",
    "arfsliderselectioncolor"=>"#d1dee5",
    "arfslidertrackcolor"=>"#bcc7cd",
    "arfplaceholder_opacity" => "0.5",
    "arf_bg_position_x" => "left",
    "arf_bg_position_input_x" => "",
    "arf_bg_position_y" => "top",
    "arf_bg_position_input_y" => "",
);

$new_values1 = maybe_serialize($new_values);
$values['form_css'] = $new_values1;
$form_id = $arfform->create($values);
if (!empty($new_values)) {

    $use_saved = true;

    $arfssl = (is_ssl()) ? 1 : 0;

    $filename = FORMPATH . '/core/css_create_main.php';

    $wp_upload_dir = wp_upload_dir();

    $target_path = $wp_upload_dir['basedir'] . '/arforms/maincss';

    $css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";


    $css .= "\n";


    ob_start();


    include $filename;


    $css .= ob_get_contents();


    ob_end_clean();


    $css .= "\n " . $warn;

    $css_file = $target_path . '/maincss_' . $form_id . '.css';

    WP_Filesystem();
    global $wp_filesystem;
    $css = str_replace('##', '#', $css);
    $wp_filesystem->put_contents($css_file, $css, 0777);

    wp_cache_delete($form_id, 'arfform');

    $filename1 = FORMPATH . '/core/css_create_materialize.php';
    $css1 = $warn1 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css1 .= "\n";
    ob_start();
    include $filename1;
    $css1 .= ob_get_contents();
    ob_end_clean();
    $css1 .= "\n " . $warn1;
    $css_file1 = $target_path . '/maincss_materialize_' . $form_id . '.css';
    WP_Filesystem();
    $css1 = str_replace('##', '#', $css1);
    $wp_filesystem->put_contents($css_file1, $css1, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename2 = FORMPATH . '/core/css_create_materialize_outline.php';
    $css2 = $warn2 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css2 .= "\n";
    ob_start();
    include $filename2;
    $css2 .= ob_get_contents();
    ob_end_clean();
    $css2 .= "\n" . $warn2;
    $css_file2 = $target_path . '/maincss_materialize_outlined_' . $form_id . '.css';
    WP_Filesystem();
    $css2 = str_replace( '##', '#', $css2 );
    $wp_filesystem->put_contents( $css_file2, $css2, 0777 );
} else {

    $query_results = true;
}


$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'First Name';
$field_values['field_options']['name'] = 'First Name';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'text';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter first name', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('First Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 1;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Last Name';
$field_values['field_options']['name'] = 'Last Name';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'text';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter last name', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Last Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 2;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['email'];
$field_values['name'] = 'E-mail Address';
$field_values['field_options']['name'] = 'E-mail Address';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'email';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter email address', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Email Address', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Please enter a valid email address', 'ARForms'));
$field_values['field_options']['confirm_email_label'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['field_options']['invalid_confirm_email'] = addslashes(esc_html__('Confirm email address does not match with email', 'ARForms'));
$field_values['field_options']['confirm_email_placeholder'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 3;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Company Name';
$field_values['field_options']['name'] = 'Company Name';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'text';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your comapany name', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Company Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 4;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['url'];
$field_values['name'] = 'Website';
$field_values['field_options']['name'] = 'Website';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'url';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your website URL', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Website', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Please enter a valid website', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 5;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Subject';
$field_values['field_options']['name'] = 'Subject';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'text';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter subject', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Subject', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 6;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['radio'];
$field_values['name'] = 'How did you find us?';
$field_values['field_options']['name'] = 'How did you find us?';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'radio';
$field_values['field_options']['invalid'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['field_options']['separate_value'] = "false";
$field_values['options'] = json_encode(array('Search Engine', 'Link From Another Site', 'News Article', 'Televistion Ad', 'Word of Mouth'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 7;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['radio'];
$field_values['name'] = 'How often do you visit our site?';
$field_values['field_options']['name'] = 'How often do you visit our site?';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'radio';
$field_values['field_options']['invalid'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['field_options']['separate_value'] = "false";
$field_values['options'] = json_encode(array('Daily', 'Weekly', 'Monthly', 'Yearly'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 8;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['select'];
$field_values['name'] = 'Please rate the quality of our content. (10=Best 1=Worst)';
$field_values['field_options']['name'] = 'Please rate the quality of our content. (10=Best 1=Worst)';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'select';
$field_values['field_options']['invalid'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['field_options']['separate_value'] = "false";
$field_values['options'] = json_encode(array('10', '9', '8', '7', '6', '5', '4', '3', '2', '1'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 9;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['select'];
$field_values['name'] = 'Please rate the quality of our site design. (10=Best 1=Worst)';
$field_values['field_options']['name'] = 'Please rate the quality of our site design. (10=Best 1=Worst)';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'select';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['invalid'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['separate_value'] = "false";
$field_values['options'] = json_encode(array('10', '9', '8', '7', '6', '5', '4', '3', '2', '1'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 10;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['checkbox'];
$field_values['name'] = 'Suitable word for ARForms';
$field_values['field_options']['name'] = 'Suitable word for ARForms';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'checkbox';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['invalid'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['separate_value'] = "false";
$field_values['field_options']['options'] = json_encode(array('Good', 'Best', 'Difficult', 'Creative', 'Helpful', 'Unhelpful'));
$field_values['options'] = json_encode(array('Good', 'Best', 'Difficult', 'Creative', 'Helpful', 'Unhelpful'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 11;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['textarea'];
$field_values['name'] = 'What was your favorite part of the ARForms?';
$field_values['field_options']['name'] = 'What was your favorite part of the ARForms?';
$field_values['field_options']['required'] = 0;
$field_values['type'] = 'textarea';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('What was your favorite part of the ARForms?', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 12;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['textarea'];
$field_values['name'] = 'Did you experience any problems or have any suggestions?';
$field_values['field_options']['name'] = 'Did you experience any problems or have any suggestions?';
$field_values['field_options']['required'] = 0;
$field_values['type'] = 'textarea';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Did you experience any problems or have any suggestions?', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 13;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['textarea'];
$field_values['name'] = 'Other Comment';
$field_values['field_options']['name'] = 'Other Comment';
$field_values['type'] = 'textarea';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Other Comment', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(addslashes(esc_html__('Invalid minimum characters length', 'ARForms')));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 14;
unset($field_values);
unset($field_id);
unset($values);

$field_options = $wpdb->get_results($wpdb->prepare("SELECT `options` FROM `" . $MdlDb->forms . "` WHERE `id` = %d", $form_id));

$form_opt = maybe_unserialize($field_options[0]->options);

$form_opt['arf_field_order'] = json_encode($field_order);
$form_opt['arf_inner_field_order'] = json_encode( $inner_field_order );

$form_options = maybe_serialize($form_opt);

$wpdb->update($MdlDb->forms, array('options' => $form_options), array('id' => $form_id));

unset($field_order);
unset($inner_field_order);

if( isset($arf_update_templates) && $arf_update_templates == true ){
    $values['id'] = 6;
}
$values['name'] = 'RSVP Form';
$values['description'] = 'Gather User information';
$values['options']['custom_style'] = 1;
$values['is_template'] = '1';
$values['status'] = 'published';
$values['form_key'] = 'RSVP';
$values['options']['display_title_form'] = "1";
$values['options']['arf_form_title'] = "background-color:rgb(147, 217, 226);padding: 10px;border-radius:5px;-webkit-border-radius:5px;-o-border-radius:5px;-moz-border-radius:5px;";


$new_values = array(
    'arfmainformwidth' => '800',
    'form_width_unit' => 'px',
    'edit_msg' => 'Your submission was successfully saved.',
    'update_value' => 'Update',
    'arfeditoroff' => false,
    'arfmaintemplatepath' => '',
    'csv_format' => 'UTF-8',
    'date_format' => 'MMM D, YYYY',
    'cal_date_format' => 'MMM D, YYYY',
    'arfcalthemecss' => 'default_theme',
    'arfcalthemename' => 'default_theme',
    'theme_nicename' => 'default_theme',
    'permalinks' => false,
    'form_align' => 'left',
    'fieldset' => '2',
    'arfmainfieldsetcolor' => '#c9c7c9',
    'arfmainfieldsetpadding' => '30px 45px 30px 45px',
    'arfmainfieldsetradius' => '6',
    'font' => 'Helvetica',
    'font_other' => '',
    'font_size' => '16',
    'label_color' => '706d70',
    'weight' => 'normal',
    'position' => 'top',
    'hide_labels' => false,
    'align' => 'left',
    'width' => '130',
    'width_unit' => 'px',
    'arfdescfontsetting' => '"Lucida Grande","Lucida Sans Unicode",Tahoma,sans-serif',
    'arfdescfontsizesetting' => '12',
    'arfdesccolorsetting' => '666666',
    'arfdescweightsetting' => 'normal',
    'description_style' => 'normal',
    'arfdescalighsetting' => 'right',
    'field_font_size' => '14',
    'field_width' => '100',
    'field_width_unit' => '%',
    'auto_width' => false,
    'arffieldpaddingsetting' => '2',
    'arffieldmarginssetting' => '23',
    'arffieldanimationdurationsetting' => '0',
    'arfpbfieldanimationdurationsetting' => '0',
    'arffieldanimationdelaysetting' => '0',
    'arfpbfieldanimationdelaysetting' => '0',
    'bg_color' => 'ffffff',
    'text_color' => '#384647',
    'border_color' => 'b0b0b5',
    'arffieldborderwidthsetting' => '1',
    'arffieldborderstylesetting' => 'solid',
    'arfbgactivecolorsetting' => 'ffffff',
    'arfborderactivecolorsetting' => '#6fdeed',
    'arferrorbgcolorsetting' => 'ffffff',
    'arferrorbordercolorsetting' => '#f28888',
    'arferrorborderwidthsetting' => '1',
    'arferrorborderstylesetting' => 'solid',
    'arfradioalignsetting' => 'inline',
    'arfcheckboxalignsetting' => 'block',
    'check_font' => 'sans-serif',
    'check_font_other' => '',
    'arfcheckboxfontsizesetting' => '12px',
    'arfcheckboxlabelcolorsetting' => '444444',
    'check_weight' => 'normal',
    'arfsubmitbuttonstylesetting' => false,
    'arfsubmitbuttonfontsizesetting' => '19',
    'arfsubmitbuttonwidthsetting' => '140',
    'arfsubmitbuttonheightsetting' => '44',
    'submit_bg_color' => '#84d1db',
    'arfsubmitbuttonbgcolorhoversetting' => '#6ac7d4',
    'arfsubmitbgcolor2setting' => '',
    'arfsubmitbordercolorsetting' => 'f6f6f8',
    'arfsubmitborderwidthsetting' => '0',
    'arfsubmitboxxoffsetsetting' => '1', 
    'arfsubmitboxyoffsetsetting' => '2',
    'arfsubmitboxblursetting' => '3',
    'arfsubmitboxshadowsetting' => '0',
    'arfsubmittextcolorsetting' => 'ffffff',
    'arfsubmitweightsetting' => 'bold',
    'arfsubmitborderradiussetting' => '3',
    'submit_bg_img' => '',
    'submit_hover_bg_img' => '',
    'arfsubmitbuttonmarginsetting' => '15px 10px 0px 0px',
    'arfsubmitbuttonpaddingsetting' => '8',
    'arfsubmitshadowcolorsetting' => '#f0f0f0',
    'border_radius' => '3',
    'arferroriconsetting' => 'e1.png',
    'arferrorbgsetting' => 'F3CAC7',
    'arferrorbordersetting' => 'FA8B83',
    'arferrortextsetting' => '501411',
    'arffontsizesetting' => '14',
    'arfsucessiconsetting' => 's1.png',
    'success_bg' => NULL,
    'success_border' => NULL,
    'success_text' => NULL,
    'arfsucessfontsizesetting' => '14',
    'arftextareafontsizesetting' => '13px',
    'arftextareawidthsetting' => '400',
    'arftextareawidthunitsetting' => 'px',
    'arftextareapaddingsetting' => '2',
    'arftextareamarginsetting' => '20',
    'arftextareabgcolorsetting' => 'ffffff',
    'arftextareacolorsetting' => '444444',
    'arftextareabordercolorsetting' => 'dddddd',
    'arftextareaborderwidthsetting' => '1',
    'arftextareaborderstylesetting' => 'solid',
    'text_direction' => '1',
    'arffieldheightsetting' => '24',
    'arfmainformtitlecolorsetting' => '#ffffff',
    'form_title_font_size' => 28,
    'error_font' => 'Lucida Sans Unicode',
    'error_font_other' => '',
    'arfactivebgcolorsetting' => 'FFFF00',
    'arfmainformbgcolorsetting' => 'ffffff',
    'arfmainformtitleweightsetting' => 'normal',
    'arfmainformtitlepaddingsetting' => '0px 0px 20px 0px',
    'arfmainformbordershadowcolorsetting' => '#ebebeb',
    'form_border_shadow' => 'flat',
    'arfsubmitalignsetting' => 'left',
    'checkbox_radio_style' => '1',
    'bg_color_pg_break' => '087ee2',
    'bg_inavtive_color_pg_break' => '7ec3fc',
    'text_color_pg_break' => 'ffffff',
    'text_color_pg_break_style3' => '087ee2',
    'arfmainform_bg_img' => '',
    'arfmainform_opacity' => '1',
    'arfmainfield_opacity' => '0',
    'arfsubmitfontfamily' => 'Verdana',
    'arfmainfieldsetpadding_1' => '30',
    'arfmainfieldsetpadding_2' => '45',
    'arfmainfieldsetpadding_3' => '30',
    'arfmainfieldsetpadding_4' => '45',
    'arfmainformtitlepaddingsetting_1' => '0',
    'arfmainformtitlepaddingsetting_2' => '0',
    'arfmainformtitlepaddingsetting_3' => 30,
    'arfmainformtitlepaddingsetting_4' => '0',
    'arffieldinnermarginssetting_1' => '8',
    'arffieldinnermarginssetting_2' => '10',
    'arffieldinnermarginssetting_3' => '8',
    'arffieldinnermarginssetting_4' => '10',
    'arfsubmitbuttonmarginsetting_1' => '15',
    'arfsubmitbuttonmarginsetting_2' => '10',
    'arfsubmitbuttonmarginsetting_3' => '0',
    'arfsubmitbuttonmarginsetting_4' => '0',
    'arfcheckradiostyle' => 'flat',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfcheckradiocolor' => 'aero',
    'arf_checked_checkbox_icon' => '',
    'enable_arf_checkbox' => '0',
    'arf_checked_radio_icon' => '',
    'enable_arf_radio' => '0',
    'checked_checkbox_icon_color' => '#666666',
    'checked_radio_icon_color' => '#666666',
    'arfformtitlealign' => 'center',
    'arferrorstyle' => 'advance',
    'arferrorstylecolor' => '#F2DEDE|#A94442|#508b27',
    'arferrorstylecolor2' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstyleposition' => 'bottom',
    'arfsubmitautowidth' => '100',
    'arftitlefontfamily' => 'Courier',
    'bar_color_survey' => '#007ee4',
    'bg_color_survey' => '#dadde2',
    'text_color_survey' => '#333333',
    'prefix_suffix_bg_color' => '#e7e8ec',
    'prefix_suffix_icon_color' => '#808080',
    'arfsectionpaddingsetting_1' => '20',
    'arfsectionpaddingsetting_2' => '0',
    'arfsectionpaddingsetting_3' => '20',
    'arfsectionpaddingsetting_4' => '20',
    'arfsectionpaddingsetting' => "20px 0px 20px 20px",
    'arffieldinnermarginssetting' => '8px 10px 8px 10px',
    'arfsucessbgcolorsetting' => '#E0FDE2',
    'arfsucessbordercolorsetting' => '#BFE0C1',
    'arfsucesstextcolorsetting' => '#4C4D4E',
    'arfformerrorbgcolorsetting' => '#FDECED',
    'arfformerrorbordercolorsetting' => '#F9CFD1',
    'arfformerrortextcolorsetting' => '#ED4040',
    'check_weight_form_title' => 'bold',
    'arfinputstyle' => 'standard',
    "arfsubmitbuttonstyle"=>"border",
    'arfcheckradiostyle' => 'default',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfmainform_color_skin' => 'green',
    'arf_tooltip_bg_color' => '#000000',
    'arf_tooltip_font_color' => '#ffffff',
    "arfcommonfont"=>"Helvetica",
    "arfmainfieldcommonsize"=>"3",
    "arfvalidationbgcolorsetting"=>"#F2DEDE",
    "arfvalidationtextcolorsetting"=>"#A94442",
    "arfdatepickerbgcolorsetting"=>"#007ee4",
    "arfdatepickertextcolorsetting"=>"#000000",
    "arfsectiontitlefamily"=>"Helvetica",
    "arfsectiontitlefontsizesetting"=>"16",
    "arfsectiontitleweightsetting"=>"bold",
    "arfsubmitbuttontext"=>"Submit",
    "arfuploadbtntxtcolorsetting"=>"#FFFFFF",
    "arfuploadbtnbgcolorsetting" =>"#00C9B6",
    "arf_req_indicator"=>"0",
    "arf_section_inherit_bg" => "1",
    "arfformsectionbackgroundcolor"=>"#ffffff",
    "arfmainbasecolor" => "#00C9B6",
    "arflikebtncolor"=>"#4786ff",
    "arfdislikebtncolor"=>"#ec3838",
    "arfstarratingcolor"=>"#FCBB1D",
    "arfsliderselectioncolor"=>"#d1dee5",
    "arfslidertrackcolor"=>"#bcc7cd",
    "arfplaceholder_opacity" => "0.5",
    "arf_bg_position_x" => "left",
    "arf_bg_position_input_x" => "",
    "arf_bg_position_y" => "top",
    "arf_bg_position_input_y" => "",
);

$new_values1 = maybe_serialize($new_values);
$values['form_css'] = $new_values1;
$form_id = $arfform->create($values);
if (!empty($new_values)) {

    $use_saved = true;

    $arfssl = (is_ssl()) ? 1 : 0;

    $filename = FORMPATH . '/core/css_create_main.php';

    $wp_upload_dir = wp_upload_dir();

    $target_path = $wp_upload_dir['basedir'] . '/arforms/maincss';

    $css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";


    $css .= "\n";


    ob_start();


    include $filename;


    $css .= ob_get_contents();


    ob_end_clean();


    $css .= "\n " . $warn;

    $css_file = $target_path . '/maincss_' . $form_id . '.css';

    WP_Filesystem();
    global $wp_filesystem;
    $css = str_replace('##', '#', $css);
    $wp_filesystem->put_contents($css_file, $css, 0777);

    wp_cache_delete($form_id, 'arfform');

    $filename1 = FORMPATH . '/core/css_create_materialize.php';
    $css1 = $warn1 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css1 .= "\n";
    ob_start();
    include $filename1;
    $css1 .= ob_get_contents();
    ob_end_clean();
    $css1 .= "\n " . $warn1;
    $css_file1 = $target_path . '/maincss_materialize_' . $form_id . '.css';
    WP_Filesystem();
    $css1 = str_replace('##', '#', $css1);
    $wp_filesystem->put_contents($css_file1, $css1, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename2 = FORMPATH . '/core/css_create_materialize_outline.php';
    $css2 = $warn2 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css2 .= "\n";
    ob_start();
    include $filename2;
    $css2 .= ob_get_contents();
    ob_end_clean();
    $css2 .= "\n" . $warn2;
    $css_file2 = $target_path . '/maincss_materialize_outlined_' . $form_id . '.css';
    WP_Filesystem();
    $css2 = str_replace( '##', '#', $css2 );
    $wp_filesystem->put_contents( $css_file2, $css2, 0777 );
} else {

    $query_results = true;
}

$field_order = array();
$inner_field_order = array();
$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Full Name';
$field_values['field_options']['name'] = 'Full Name';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'text';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your full name', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Full Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 1;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['email'];
$field_values['name'] = 'Email';
$field_values['field_options']['name'] = 'Email';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'email';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter email address', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Email Address', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Please enter a valid email address', 'ARForms'));
$field_values['field_options']['confirm_email_label'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['field_options']['invalid_confirm_email'] = addslashes(esc_html__('Confirm email address does not match with email', 'ARForms'));
$field_values['field_options']['confirm_email_placeholder'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 2;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['phone'];
$field_values['name'] = 'Phone';
$field_values['field_options']['name'] = 'Phone';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'phone';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['phone_validation'] = 'international';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your phone number', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Phone', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 3;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['textarea'];
$field_values['name'] = 'Address';
$field_values['field_options']['name'] = 'Address';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'textarea';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your address', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Address', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 4;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'City';
$field_values['field_options']['name'] = 'City';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'text';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter city', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('City', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 5;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['select'];
$field_values['name'] = 'Your Meal Selection';
$field_values['field_options']['name'] = 'Your Meal Selection';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'select';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['options'] = json_encode(array('Chicken', 'Steak', 'Vegetarian'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 6;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['select'];
$field_values['name'] = 'Are you bringing a guest?';
$field_values['field_options']['name'] = 'Are you bringing a guest?';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'select';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['options'] = json_encode(array('Yes', 'No'));
$field_values['form_id'] = $form_id;
$bringing_guest_field_id = $field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 7;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['select'];
$field_values['name'] = 'How many guests will be there?';
$field_values['field_options']['name'] = 'How many guests will be there?';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'select';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['options'] = json_encode(array('One', 'Two', 'Three', 'Four'));
$field_values['form_id'] = $form_id;
$conditional_rule = array(
    '1' => array(
        'id' => 1,
        'field_id' => $bringing_guest_field_id,
        'field_type' => 'select',
        'operator' => 'equals',
        'value' => 'Yes',
    ),
);
$conditional_logic_exp = array(
    'enable' => 1,
    'display' => 'show',
    'if_cond' => 'all',
    'rules' => $conditional_rule,
);
$field_values['conditional_logic'] = maybe_serialize($conditional_logic_exp);

$how_guests_id = $arffield->create($field_values);
$field_order[$how_guests_id] = 8;
unset($field_values);
unset($field_id);
$field_values = array();
$field_values['field_options'] = $field_data_obj['time'];
$field_values['name'] = 'Which is your suitable time?';
$field_values['field_options']['name'] = 'Which is your suitable time?';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'time';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 9;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['radio'];
$field_values['name'] = 'How much interested in our ARForms?';
$field_values['field_options']['name'] = 'How much interested in our ARForms?';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'radio';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['options'] = json_encode(array('Extremely', 'Very', 'Moderately', 'Slightly', 'Not Excited'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 10;
unset($field_values);
unset($field_id);
unset($values);

$field_options = $wpdb->get_results($wpdb->prepare("SELECT `options` FROM `" . $MdlDb->forms . "` WHERE `id` = %d", $form_id));

$form_opt = maybe_unserialize($field_options[0]->options);

$form_opt['arf_field_order'] = json_encode($field_order);
$form_opt['arf_inner_field_order'] = json_encode( $inner_field_order );
$form_opt['arf_form_other_css'] = '.arf_form_outer_wrapper.ar_main_div_'.$form_id.' .arf_fieldset .formtitle_style{background-color:rgb(147, 217, 226);padding: 10px;border-radius:5px;-webkit-border-radius:5px;-o-border-radius:5px;-moz-border-radius:5px;height:auto;}';
$form_options = maybe_serialize($form_opt);

$wpdb->update($MdlDb->forms, array('options' => $form_options), array('id' => $form_id));

unset($field_order);
unset($inner_field_order);


$record = $arfform->getOne($form_id);
$conditional_logic = array();
$conditional_logic = array(
    '0' => array(
        'id' => 0,
        'logical_operator' => 'and',
        'condition' => array
            (
            '0' => array
                (
                'condition_id' => 0,
                'field_id' => $bringing_guest_field_id,
                'field_type' => 'select',
                'operator' => 'equals',
                'value' => 'Yes',
            )
        ),
        'result' => Array
            (
            '0' => Array
                (
                'result_id' => 0,
                'action' => 'show',
                'field_id' => $how_guests_id,
                'field_type' => 'select',
                'value' => '',
            )
        )
    )
);
$record_old['options'] = $record->options;

$record_old['options']['arf_conditional_logic_rules'] = $conditional_logic;
$update = $wpdb->query($wpdb->prepare("update " . $MdlDb->forms . " set options = '%s' where id = '%d'", maybe_serialize($record_old['options']), $form_id));

if( isset($arf_update_templates) && $arf_update_templates == true ){
    $values['id'] = 7;
}
$values['name'] = addslashes(esc_html__('Job Application Form', 'ARForms'));
$values['description'] = '';
$values['options']['custom_style'] = 1;
$values['is_template'] = '1';
$values['status'] = 'published';
$values['form_key'] = 'JobApplication';
$values['options']['display_title_form'] = "1";
$values['options']['arf_form_description'] = "margin:0px !important;";

$new_values = array(
    'arfmainformwidth' => '800',
    'form_width_unit' => 'px',
    'edit_msg' => 'Your submission was successfully saved.',
    'update_value' => 'Update',
    'arfeditoroff' => false,
    'arfmaintemplatepath' => '',
    'csv_format' => 'UTF-8',
    'date_format' => 'MMM D, YYYY',
    'cal_date_format' => 'MMM D, YYYY',
    'arfcalthemecss' => 'default_theme',
    'arfcalthemename' => 'default_theme',
    'theme_nicename' => 'default_theme',
    'permalinks' => false,
    'form_align' => 'left',
    'fieldset' => '1',
    'arfmainfieldsetcolor' => '#e0e0de',
    'arfmainfieldsetpadding' => '30px 45px 30px 45px',
    'arfmainfieldsetradius' => '6',
    'font' => 'Helvetica',
    'font_other' => '',
    'font_size' => '14',
    'label_color' => '#787778',
    'weight' => 'bold',
    'position' => 'top',
    'hide_labels' => false,
    'align' => 'left',
    'width' => '130',
    'width_unit' => 'px',
    'arfdescfontsetting' => '"Lucida Grande","Lucida Sans Unicode",Tahoma,sans-serif',
    'arfdescfontsizesetting' => '12',
    'arfdesccolorsetting' => '666666',
    'arfdescweightsetting' => 'normal',
    'description_style' => 'normal',
    'arfdescalighsetting' => 'right',
    'field_font_size' => '14',
    'field_width' => '100',
    'field_width_unit' => '%',
    'auto_width' => false,
    'arffieldpaddingsetting' => '2',
    'arffieldmarginssetting' => '18',
    'arffieldanimationdurationsetting' => '0',
    'arfpbfieldanimationdurationsetting' => '0',
    'arffieldanimationdelaysetting' => '0',
    'arfpbfieldanimationdelaysetting' => '0',
    'bg_color' => '#fffcff',
    'text_color' => '#565657',
    'border_color' => '#b0b0b5',
    'arffieldborderwidthsetting' => '1',
    'arffieldborderstylesetting' => 'solid',
    'arfbgactivecolorsetting' => '#f5f9fc',
    'arfborderactivecolorsetting' => '#a969e0',
    'arferrorbgcolorsetting' => 'ffffff',
    'arferrorbordercolorsetting' => '#ebc173',
    'arferrorborderwidthsetting' => '1',
    'arferrorborderstylesetting' => 'solid',
    'arfradioalignsetting' => 'inline',
    'arfcheckboxalignsetting' => 'block',
    'check_font' => 'Helvetica',
    'check_font_other' => '',
    'arfcheckboxfontsizesetting' => '12px',
    'arfcheckboxlabelcolorsetting' => '444444',
    'check_weight' => 'normal',
    'arfsubmitbuttonstylesetting' => false,
    'arfsubmitbuttonfontsizesetting' => '18',
    'arfsubmitbuttonwidthsetting' => '100',
    'arfsubmitbuttonheightsetting' => '45',
    'submit_bg_color' => '#a969e0',
    'arfsubmitbuttonbgcolorhoversetting' => '#9249d1',
    'arfsubmitbgcolor2setting' => '',
    'arfsubmitbordercolorsetting' => 'f6f6f8',
    'arfsubmitborderwidthsetting' => '0',
    'arfsubmitboxxoffsetsetting' => '1', 
    'arfsubmitboxyoffsetsetting' => '2',
    'arfsubmitboxblursetting' => '3',
    'arfsubmitboxshadowsetting' => '0',
    'arfsubmittextcolorsetting' => 'ffffff',
    'arfsubmitweightsetting' => 'bold',
    'arfsubmitborderradiussetting' => '3',
    'submit_bg_img' => '',
    'submit_hover_bg_img' => '',
    'arfsubmitbuttonmarginsetting' => '0px 10px 0px 0px',
    'arfsubmitbuttonpaddingsetting' => '8',
    'arfsubmitshadowcolorsetting' => 'c6c8cc',
    'border_radius' => '2',
    'arferroriconsetting' => 'e1.png',
    'arferrorbgsetting' => 'F3CAC7',
    'arferrorbordersetting' => 'FA8B83',
    'arferrortextsetting' => '501411',
    'arffontsizesetting' => '11',
    'arfsucessiconsetting' => 's1.png',
    'success_bg' => NULL,
    'success_border' => NULL,
    'success_text' => NULL,
    'arfsucessfontsizesetting' => '14',
    'arftextareafontsizesetting' => '13px',
    'arftextareawidthsetting' => '400',
    'arftextareawidthunitsetting' => 'px',
    'arftextareapaddingsetting' => '2',
    'arftextareamarginsetting' => '20',
    'arftextareabgcolorsetting' => 'ffffff',
    'arftextareacolorsetting' => '444444',
    'arftextareabordercolorsetting' => 'dddddd',
    'arftextareaborderwidthsetting' => '1',
    'arftextareaborderstylesetting' => 'solid',
    'text_direction' => '1',
    'arffieldheightsetting' => '24',
    'arfmainformtitlecolorsetting' => '#767a74',
    'form_title_font_size' => '28',
    'error_font' => 'Verdana',
    'error_font_other' => '',
    'arfactivebgcolorsetting' => 'FFFF00',
    'arfmainformbgcolorsetting' => '#fcfcfc',
    'arfmainformtitleweightsetting' => 'normal',
    'arfmainformtitlepaddingsetting' => '0px 0px 20px 0px',
    'arfmainformbordershadowcolorsetting' => '#dedede',
    'form_border_shadow' => 'shadow',
    'arfsubmitalignsetting' => 'center',
    'checkbox_radio_style' => '1',
    'bg_color_pg_break' => '087ee2',
    'bg_inavtive_color_pg_break' => '7ec3fc',
    'text_color_pg_break' => 'ffffff',
    'text_color_pg_break_style3' => '087ee2',
    'arfmainform_bg_img' => '',
    'arfmainform_opacity' => '1',
    'arfmainfield_opacity' => '0',
    'arfsubmitfontfamily' => 'Helvetica',
    'arfmainfieldsetpadding_1' => '20',
    'arfmainfieldsetpadding_2' => '30',
    'arfmainfieldsetpadding_3' => '30',
    'arfmainfieldsetpadding_4' => '30',
    'arfmainformtitlepaddingsetting_1' => '0',
    'arfmainformtitlepaddingsetting_2' => '0',
    'arfmainformtitlepaddingsetting_3' => '30',
    'arfmainformtitlepaddingsetting_4' => '0',
    'arffieldinnermarginssetting_1' => '8',
    'arffieldinnermarginssetting_2' => '10',
    'arffieldinnermarginssetting_3' => '8',
    'arffieldinnermarginssetting_4' => '10',
    'arfsubmitbuttonmarginsetting_1' => '0',
    'arfsubmitbuttonmarginsetting_2' => '10',
    'arfsubmitbuttonmarginsetting_3' => '0',
    'arfsubmitbuttonmarginsetting_4' => '0',
    'arfcheckradiostyle' => 'square',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfcheckradiocolor' => 'yellow',
    'arf_checked_checkbox_icon' => '',
    'enable_arf_checkbox' => '0',
    'arf_checked_radio_icon' => '',
    'enable_arf_radio' => '0',
    'checked_checkbox_icon_color' => '#666666',
    'checked_radio_icon_color' => '#666666',
    'arfformtitlealign' => 'center',
    'arferrorstyle' => 'advance',
    'arferrorstylecolor' => '#FAEBCC|#8A6D3B|#af7a0c',
    'arferrorstylecolor2' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstyleposition' => 'right',
    'arfsubmitautowidth' => '100',
    'arftitlefontfamily' => 'Helvetica',
    'bar_color_survey' => '#007ee4',
    'bg_color_survey' => '#dadde2',
    'text_color_survey' => '#333333',
    'prefix_suffix_bg_color' => '#e7e8ec',
    'prefix_suffix_icon_color' => '#808080',
    'arfsectionpaddingsetting_1' => '20',
    'arfsectionpaddingsetting_2' => '0',
    'arfsectionpaddingsetting_3' => '20',
    'arfsectionpaddingsetting_4' => '20',
    'arfsectionpaddingsetting' => "20px 0px 20px 20px",
    'arffieldinnermarginssetting' => '8px 10px 8px 10px',
    'arfsucessbgcolorsetting' => '#E0FDE2',
    'arfsucessbordercolorsetting' => '#BFE0C1',
    'arfsucesstextcolorsetting' => '#4C4D4E',
    'arfformerrorbgcolorsetting' => '#FDECED',
    'arfformerrorbordercolorsetting' => '#F9CFD1',
    'arfformerrortextcolorsetting' => '#ED4040',
    'check_weight_form_title' => 'bold',
    'arfsubmitbuttontext' => 'Apply Now',
    'arfinputstyle' => 'standard',
    "arfsubmitbuttonstyle"=>"border",
    'arfcheckradiostyle' => 'default',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfmainform_color_skin' => 'cyan',
    'arf_tooltip_bg_color' => '#000000',
    'arf_tooltip_font_color' => '#ffffff',
    "arfcommonfont"=>"Helvetica",
    "arfmainfieldcommonsize"=>"3",
    "arfvalidationbgcolorsetting"=>"#FAEBCC",
    "arfvalidationtextcolorsetting"=>"#8A6D3B",
    "arfdatepickerbgcolorsetting"=>"#007ee4",
    "arfdatepickertextcolorsetting"=>"#000000",
    "arfsectiontitlefamily"=>"Helvetica",
    "arfsectiontitlefontsizesetting"=>"16",
    "arfsectiontitleweightsetting"=>"bold",
    "arfsubmitbuttontext"=>"Submit",
    "arfuploadbtntxtcolorsetting"=>"#FFFFFF",
    "arfuploadbtnbgcolorsetting" =>"#23b7e5",
    "arf_req_indicator"=>"0",
    "arf_section_inherit_bg" => "1",
    "arfformsectionbackgroundcolor"=>"#ffffff",
    "arfmainbasecolor" => "#23b7e5",
    "arflikebtncolor"=>"#4786ff",
    "arfdislikebtncolor"=>"#ec3838",
    "arfstarratingcolor"=>"#FCBB1D",
    "arfsliderselectioncolor"=>"#d1dee5",
    "arfslidertrackcolor"=>"#bcc7cd",
    "arfplaceholder_opacity" => "0.5",
    "arf_bg_position_x" => "left",
    "arf_bg_position_input_x" => "",
    "arf_bg_position_y" => "top",
    "arf_bg_position_input_y" => "",
);

$new_values1 = maybe_serialize($new_values);
$values['form_css'] = $new_values1;
$form_id = $arfform->create($values);

if (!empty($new_values)) {

    $use_saved = true;

    $arfssl = (is_ssl()) ? 1 : 0;

    $filename = FORMPATH . '/core/css_create_main.php';

    $wp_upload_dir = wp_upload_dir();

    $target_path = $wp_upload_dir['basedir'] . '/arforms/maincss';

    $css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";


    $css .= "\n";


    ob_start();


    include $filename;


    $css .= ob_get_contents();


    ob_end_clean();


    $css .= "\n " . $warn;

    $css_file = $target_path . '/maincss_' . $form_id . '.css';

    WP_Filesystem();
    global $wp_filesystem;
    $css = str_replace('##', '#', $css);
    $wp_filesystem->put_contents($css_file, $css, 0777);

    wp_cache_delete($form_id, 'arfform');

    $filename1 = FORMPATH . '/core/css_create_materialize.php';
    $css1 = $warn1 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css1 .= "\n";
    ob_start();
    include $filename1;
    $css1 .= ob_get_contents();
    ob_end_clean();
    $css1 .= "\n " . $warn1;
    $css_file1 = $target_path . '/maincss_materialize_' . $form_id . '.css';
    WP_Filesystem();
    $css1 = str_replace('##', '#', $css1);
    $wp_filesystem->put_contents($css_file1, $css1, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename2 = FORMPATH . '/core/css_create_materialize_outline.php';
    $css2 = $warn2 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css2 .= "\n";
    ob_start();
    include $filename2;
    $css2 .= ob_get_contents();
    ob_end_clean();
    $css2 .= "\n" . $warn2;
    $css_file2 = $target_path . '/maincss_materialize_outlined_' . $form_id . '.css';
    WP_Filesystem();
    $css2 = str_replace( '##', '#', $css2 );
    $wp_filesystem->put_contents( $css_file2, $css2, 0777 );
} else {

    $query_results = true;
}

$field_order = array();
$inner_field_order = array();

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = addslashes(esc_html__('First Name', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('First Name', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'text';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter first name', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('First Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['field_options']['placeholdertext'] = 'First Name';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 1;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = addslashes(esc_html__('Last name', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Last name', 'ARForms'));
$field_values['field_options']['placeholdertext'] = 'Last Name';
$field_values['type'] = 'text';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter last name', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Last Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 2;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['email'];
$field_values['name'] = addslashes(esc_html__('Email', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Email', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['field_options']['placeholdertext'] = 'Email';
$field_values['type'] = 'email';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter email address', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Email Address', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Please enter a valid email address', 'ARForms'));
$field_values['field_options']['confirm_email_label'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['field_options']['invalid_confirm_email'] = addslashes(esc_html__('Confirm email address does not match with email', 'ARForms'));
$field_values['field_options']['confirm_email_placeholder'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 3;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['phone'];
$field_values['name'] = addslashes(esc_html__('Contact No', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Contact No', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'phone';
$field_values['field_options']['placeholdertext'] = 'Contact No';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['field_options']['phone_validation'] = 'international';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your contact no', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Contact No', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Enter contact no is invalid', 'ARForms'));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 4;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['textarea'];
$field_values['name'] = addslashes(esc_html__('Address', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Address', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'textarea';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your address', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Address', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['max'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 5;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['select'];
$field_values['name'] = addslashes(esc_html__('Position apply for?', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Position apply for?', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'select';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please select position', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['options'] = json_encode(array('', addslashes(esc_html__('Developer', 'ARForms')), addslashes(esc_html__('Manager', 'ARForms')), addslashes(esc_html__('Clerk', 'ARForms')), addslashes(esc_html__('Representative', 'ARForms'))));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 6;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['select'];
$field_values['name'] = addslashes(esc_html__('Are you applying for?', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Are you applying for?', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'select';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please select applying for', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['options'] = json_encode(array('', addslashes(esc_html__('Full Time', 'ARForms')), addslashes(esc_html__('Part Time', 'ARForms'))));
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 7;
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['section'];
$field_values['name'] = addslashes(esc_html__('Education and Experience Details', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Education and Experience Details', 'ARForms'));
$field_values['type'] = 'section';
$field_values['field_options']['css_label'] = 'padding-top:20px;margin-bottom:20px;';
$field_values['field_options']['arf_section_font'] = 'Arial';
$field_values['field_options']['arf_section_font_size'] = '18';
$field_values['field_options']['arf_section_bg_color'] = '#fcfcfc';
$field_values['field_options']['classes'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 8;
$section_field_id = $field_id;
$inner_field_order[$section_field_id] = array();
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = addslashes(esc_html__('Diploma / Degree Name', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Diploma / Degree Name', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'text';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter Diploma / Degree', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Diploma / Degree Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id.'|1';
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = addslashes(esc_html__('College / University Name', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('College / University Name', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'text';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your College / University', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('College / University Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id.'|2';
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['number'];
$field_values['name'] = addslashes(esc_html__('Graduation Year', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Graduation Year', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'number';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter graduation year', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Graduation Year', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Number is out of range', 'ARForms'));
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id.'|3';
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = addslashes(esc_html__('Percentage', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Percentage', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'text';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter your  percentage', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Percentage', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id.'|4';
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['textarea'];
$field_values['name'] = addslashes(esc_html__('Skills & Qualification', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Skills & Qualification', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'textarea';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter skills & qualification', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Skills & Qualification', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['field_options']['max'] = '';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] =$field_id.'|5';
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['number'];
$field_values['name'] = addslashes(esc_html__('Desired Salary', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Desired Salary', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'number';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter desired salary', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Desired Salary', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['invaid'] = addslashes(esc_html__('Number is out of range', 'ARForms'));
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id.'|6';
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['radio'];
$field_values['name'] = addslashes(esc_html__('Fresher / Experienced', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Fresher / Experienced', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'radio';
$field_values['field_options']['align'] = 'inline';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please select Fresher / Experienced', 'ARForms'));
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_values['options'] = json_encode(array(addslashes(esc_html__('Fresher', 'ARForms')), addslashes(esc_html__('Experienced', 'ARForms'))));


$frsh_exp_id = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $frsh_exp_id.'|7';
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = addslashes(esc_html__('Experience', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Experience', 'ARForms'));
$field_values['field_options']['description'] = addslashes(esc_html__('(e.g. 3 months, 2 years etc)', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'text';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please Enter Experience', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Experience', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id.'|8';
unset($field_values);
unset($field_id);

$field_values = array();
$field_values['field_options'] = $field_data_obj['number'];
$field_values['name'] = addslashes(esc_html__('Current Salary', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Current Salary', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'number';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please Enter Current Salary', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Experience', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Number is out of range', 'ARForms'));
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$current_salary = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $current_salary.'|9';
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['textarea'];
$field_values['name'] = addslashes(esc_html__('Current Company Detail', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Current Company Detail', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'textarea';

$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter current company detail', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Current Company Detail', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$textarea_id = $arffield->create($field_values);
$inner_field_order[$section_field_id][] = $textarea_id.'|10';
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['file'];
$field_values['name'] = addslashes(esc_html__('Upload Resume', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Upload Resume', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'file';
$field_values['field_options']['restrict'] = 1;
$field_values['field_options']['ftypes'] = array('doc' => 'application/msword', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'pdf' => 'application/pdf', 'txt|asc|c|cc|h' => 'text/plain', 'rtf' => 'application/rtf');
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please Select Resume', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Upload Resume', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('File is invalid', 'ARForms'));
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id.'|11';
unset($field_values);
unset($values);

$field_options = $wpdb->get_results($wpdb->prepare("SELECT `options` FROM `" . $MdlDb->forms . "` WHERE `id` = %d", $form_id));

$form_opt = maybe_unserialize($field_options[0]->options);
$inner_field_order[$section_field_id][] = 'arf_2col|12';
$form_opt['arf_field_order'] = json_encode($field_order);
$updated_inner_field_order = json_encode( $inner_field_order );
$form_opt['arf_inner_field_order'] = $updated_inner_field_order;
$form_opt['arf_form_other_css'] = '.arf_form_outer_wrapper.ar_main_div_'.$form_id.' .arf_fieldset .arfformfield.edit_field_type_section label.arf_main_label{padding-top:10px !important; padding-left:20px !important;}';

$form_options = maybe_serialize($form_opt);

$wpdb->update($MdlDb->forms, array('options' => $form_options), array('id' => $form_id));

unset($field_order);
unset($inner_field_order);

$record = $arfform->getOne($form_id);
$conditional_logic = array();
$conditional_logic = array(
    '0' => array(
        'id' => 0,
        'logical_operator' => 'and',
        'condition' => array
            (
            '0' => array
                (
                'condition_id' => 0,
                'field_id' => $frsh_exp_id,
                'field_type' => 'radio',
                'operator' => 'equals',
                'value' => 'Experienced',
            )
        ),
        'result' => Array
            (
            '0' => Array
                (
                'result_id' => 0,
                'action' => 'show',
                'field_id' => $textarea_id,
                'field_type' => 'textarea',
                'value' => '',
            ),
            '1' => Array
                (
                'result_id' => 1,
                'action' => 'show',
                'field_id' => $current_salary,
                'field_type' => 'number',
                'value' => '',
            ),
        )
    ),
);
$record_old['options'] = $record->options;

$record_old['options']['arf_conditional_logic_rules'] = $conditional_logic;
$update = $wpdb->query($wpdb->prepare("update " . $MdlDb->forms . " set options = '%s' where id = '%d'", maybe_serialize($record_old['options']), $form_id));


if( isset($arf_update_templates) && $arf_update_templates == true ){
    $values['id'] = 8;
}
$values['name'] = 'Donation Form';
$values['description'] = '';
$values['options']['custom_style'] = 1;
$values['is_template'] = '1';
$values['status'] = 'published';
$values['form_key'] = 'Donation';
$values['options']['display_title_form'] = "1";

$new_values = array(
    "display_title_form" => '1',
    "arfmainformwidth" => '600',
    "form_width_unit" => 'px',
    "text_direction" => '1',
    "form_align" => 'left',
    "arfmainfieldsetpadding" => '30px 45px 30px 45px',
    "form_border_shadow" => 'flat',
    "fieldset" => '1',
    "arfmainfieldsetradius" => '6',
    "arfmainfieldsetcolor" => '#d9d9d9',
    "arfmainformbordershadowcolorsetting" => '#f2f2f2',
    "arfmainformtitlecolorsetting" => '#0d0e12',
    "check_weight_form_title" => 'normal',
    "form_title_font_size" => '28',
    "arfmainformtitlepaddingsetting" => '0px 0px 20px 0px',
    "arfmainformbgcolorsetting" => '#ffffff',
    "font" => 'Khand',
    "label_color" => '#706d70',
    "weight" => 'normal',
    "font_size" => '16',
    "align" => 'left',
    "position" => 'top',
    "width" => '130',
    "width_unit" => 'px',
    "arfdescfontsizesetting" => '12',
    "arfdescalighsetting" => 'right',
    "hide_labels" => '',
    "check_font" => 'Khand',
    "check_weight" => 'normal',
    "field_font_size" => '14',
    "text_color" => '#17181c',
    "border_radius" => '0',
    "border_color" => '#b0b0b5',
    "arffieldborderwidthsetting" => '1',
    "arffieldborderstylesetting" => 'solid',
    "arfsubmitbuttonstyle" => 'border',
    "field_width" => '100',
    "field_width_unit" => '%',
    "arffieldmarginssetting" => '23',
    "arffieldinnermarginssetting" => '0px 0px 0px 0px',
    "bg_color" => '#ffffff',
    "arfbgactivecolorsetting" => '#ffffff',
    "arfborderactivecolorsetting" => '#0C7CD5',
    "arferrorbgcolorsetting" => '#ffffff',
    "arferrorbordercolorsetting" => '#ed4040',
    "arfradioalignsetting" => '',
    "arfcheckboxalignsetting" => '',
    "auto_width" => '',
    "arfcalthemename" => '',
    "arfcalthemecss" => '',
    "date_format" => 'MMM D, YYYY',
    "arfsubmitbuttontext" => 'Donate Now',
    "arfsubmitweightsetting" => 'normal',
    "arfsubmitbuttonfontsizesetting" => '18',
    "arfsubmitbuttonwidthsetting" => '',
    "arfsubmitbuttonheightsetting" => '38',
    "submit_bg_color" => '#0C7CD5',
    "arfsubmitbuttonbgcolorhoversetting" => '#0264b5',
    "arfsubmitbgcolor2setting" => '',
    "arfsubmittextcolorsetting" => '#ffffff',
    "arfsubmitbordercolorsetting" => '#f6f6f8',
    "arfsubmitborderwidthsetting" => '0',
    'arfsubmitboxxoffsetsetting' => '1', 
    'arfsubmitboxyoffsetsetting' => '2',
    'arfsubmitboxblursetting' => '3',
    'arfsubmitboxshadowsetting' => '0',
    "arfsubmitborderradiussetting" => '3',
    "arfsubmitshadowcolorsetting" => '#c6c8cc',
    "arfsubmitbuttonmarginsetting" => '10px 0px 0px 0px',
    "submit_bg_img" => '',
    "submit_hover_bg_img" => '',
    "error_font" => 'Khand',
    "error_font_other" => '',
    "arffontsizesetting" => '14',
    "arferrorbgsetting" => '#F3CAC7',
    "arferrortextsetting" => '#501411',
    "arferrorbordersetting" => '#FA8B83',
    'arfsucessbgcolorsetting' => '#FFFFFF',
    'arfsucessbordercolorsetting' => '#D7D8D8',
    'arfsucesstextcolorsetting' => '#24DC67',
    'arfformerrorbgcolorsetting' => '#FFFFFF',
    'arfformerrorbordercolorsetting' => '#D7D8D8',
    'arfformerrortextcolorsetting' => '#F71F4F',
    "arfsubmitalignsetting" => 'center',
    "checkbox_radio_style" => '',
    "bg_color_pg_break" => '#0C7CD5',
    "bg_inavtive_color_pg_break" => '#7ac1fa',
    "text_color_pg_break" => '#ffffff',
    "arfmainform_bg_img" => '',
    "arfmainform_color_skin" => 'blue',
    "arfinputstyle" => 'material',
    "arfsubmitfontfamily" => 'Khand',
    "arfmainfieldcommonsize" => '3',
    "arfdatepickerbgcolorsetting" => '#0C7CD5',
    "arfdatepickertextcolorsetting" => '#000000',
    "arfmainfieldsetpadding_1" => '30',
    "arfmainfieldsetpadding_2" => '45',
    "arfmainfieldsetpadding_3" => '30',
    "arfmainfieldsetpadding_4" => '45',
    "arfmainformtitlepaddingsetting_1" => '0',
    "arfmainformtitlepaddingsetting_2" => '0',
    "arfmainformtitlepaddingsetting_3" => '20',
    "arfmainformtitlepaddingsetting_4" => '0',
    "arffieldinnermarginssetting_1" => '0',
    "arffieldinnermarginssetting_2" => '0',
    "arffieldinnermarginssetting_3" => '0',
    "arffieldinnermarginssetting_4" => '0',
    "arfsubmitbuttonmarginsetting_1" => '10',
    "arfsubmitbuttonmarginsetting_2" => '0',
    "arfsubmitbuttonmarginsetting_3" => '0',
    "arfsubmitbuttonmarginsetting_4" => '0',
    'arfsectionpaddingsetting_1' => '20',
    'arfsectionpaddingsetting_2' => '0',
    'arfsectionpaddingsetting_3' => '20',
    'arfsectionpaddingsetting_4' => '20',
    'arfsectionpaddingsetting' => "20px 0px 20px 20px",
    "arfcheckradiostyle" => 'material_tick',
    "arfcheckradiocolor" => '',
    "arf_checked_checkbox_icon" => 'fa-check',
    "enable_arf_checkbox" => '',
    "arf_checked_radio_icon" => 'fa-circle',
    "enable_arf_radio" => '',
    "checked_checkbox_icon_color" => '#0C7CD5',
    "checked_radio_icon_color" => '#0C7CD5',
    "arferrorstyle" => 'normal',
    'arferrorstylecolor' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstylecolor2' => '#ed4040|#FFFFFF|#ed4040',
    "arferrorstyleposition" => 'bottom',
    "arfvalidationbgcolorsetting" => '#ed4040',
    "arfvalidationtextcolorsetting" => '#ed4040',
    "arfformtitlealign" => 'center',
    "arfsubmitautowidth" => '125',
    "arftitlefontfamily" => 'Khand',
    "bar_color_survey" => '#0C7CD5',
    "bg_color_survey" => '#dadde2',
    "text_color_survey" => '#333333',
    "arfmainform_opacity" => '1',
    "arfmainfield_opacity" => '1',
    "prefix_suffix_bg_color" => '#e7e8ec',
    "prefix_suffix_icon_color" => '#808080',
    "arf_tooltip_bg_color" => '#000000',
    "arf_tooltip_font_color" => '#ffffff',
    "arf_tooltip_width" => '',
    "arf_tooltip_position" => '',
    "arftippos" => '',
    "arfcommonfont" => 'Khand',
    "arfsectiontitlefamily" => 'Khand',
    "arfsectiontitlefontsizesetting" => '16',
    "arfsectiontitleweightsetting" => 'bold',
    "arfsubmitbuttontext"=>"Submit",
    "arfuploadbtntxtcolorsetting"=>"#FFFFFF",
    "arfuploadbtnbgcolorsetting" =>"#0C7CD5",
    "arf_req_indicator"=>"0",
    "arf_section_inherit_bg" => "1",
    "arfformsectionbackgroundcolor"=>"#ffffff",
    "arfmainbasecolor" => "#0c7cd5",
    "arflikebtncolor"=>"#4786ff",
    "arfdislikebtncolor"=>"#ec3838",
    "arfstarratingcolor"=>"#FCBB1D",
    "arfsliderselectioncolor"=>"#d1dee5",
    "arfslidertrackcolor"=>"#bcc7cd",
    "arfplaceholder_opacity" => "0.5",
    "arf_bg_position_x" => "left",
    "arf_bg_position_input_x" => "",
    "arf_bg_position_y" => "top",
    "arf_bg_position_input_y" => "",
);

$new_values1 = maybe_serialize($new_values);
$values['form_css'] = $new_values1;
$form_id = $arfform->create($values);
if (!empty($new_values)) {

    $use_saved = true;

    $arfssl = (is_ssl()) ? 1 : 0;

    $filename = FORMPATH . '/core/css_create_main.php';

    $wp_upload_dir = wp_upload_dir();

    $target_path = $wp_upload_dir['basedir'] . '/arforms/maincss';

    $css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";


    $css .= "\n";


    ob_start();


    include $filename;


    $css .= ob_get_contents();


    ob_end_clean();


    $css .= "\n " . $warn;

    $css_file = $target_path . '/maincss_' . $form_id . '.css';

    WP_Filesystem();
    global $wp_filesystem;
    $css = str_replace('##', '#', $css);
    $wp_filesystem->put_contents($css_file, $css, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename1 = FORMPATH . '/core/css_create_materialize.php';
    $css1 = $warn1 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css1 .= "\n";
    ob_start();
    include $filename1;
    $css1 .= ob_get_contents();
    ob_end_clean();
    $css1 .= "\n " . $warn1;
    $css_file1 = $target_path . '/maincss_materialize_' . $form_id . '.css';
    WP_Filesystem();
    $css1 = str_replace('##', '#', $css1);
    $wp_filesystem->put_contents($css_file1, $css1, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename2 = FORMPATH . '/core/css_create_materialize_outline.php';
    $css2 = $warn2 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css2 .= "\n";
    ob_start();
    include $filename2;
    $css2 .= ob_get_contents();
    ob_end_clean();
    $css2 .= "\n" . $warn2;
    $css_file2 = $target_path . '/maincss_materialize_outlined_' . $form_id . '.css';
    WP_Filesystem();
    $css2 = str_replace( '##', '#', $css2 );
    $wp_filesystem->put_contents( $css_file2, $css2, 0777 );
} else {
    $query_results = true;
}

$field_order = array();
$inner_field_order = array();

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'First Name';
$field_values['field_options']['name'] = 'Full Name';
$field_values['field_options']['description'] = '';
$field_values['type'] = 'text';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter full name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 1;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['radio'];
$field_values['field_key'] = '1';
$field_values['name'] = 'Donation Amount';
$field_values['field_options']['name'] = 'Donation Amount';
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'radio';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));

$std1 = new stdClass();
$std1->value = '10';
$std1->label = '$10';

$std2 = new stdClass();
$std2->value = '20';
$std2->label = '$20';

$std3 = new stdClass();
$std3->value = '30';
$std3->label = '$30';

$std4 = new stdClass();
$std4->value = '50';
$std4->label = '$50';

$std5 = new stdClass();
$std5->value = 'other';
$std5->label = 'other';

$field_values['field_options']['separate_value'] = 1;
$field_values['options'] = json_encode(array($std1, $std2, $std3, $std4, $std5));
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id_amt = $arffield->create($field_values, true);
$field_order[$field_id_amt] = 2;
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['number'];
$field_values['name'] = 'Enter Donation Amount';
$field_values['field_options']['name'] = 'Enter Donation Amount';
$field_values['type'] = 'number';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please Enter Donation Amount', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['invaid'] = addslashes(esc_html__('Number is out of range', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id_gift_amt = $arffield->create($field_values, true);
$field_order[$field_id_gift_amt] = 3;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['radio'];
$field_values['field_key'] = '1';
$field_values['name'] = 'Select Payment Method';
$field_values['field_options']['name'] = 'Select Payment Method';
$field_values['field_options']['required'] = 0;
$field_values['type'] = 'radio';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));

$std1 = new stdClass();
$std1->label = 'PayPal';
$std1->value = 'paypal';

$std2 = new stdClass();
$std2->label = 'Stripe';
$std2->value = 'stripe';

$std3 = new stdClass();
$std3->label = 'Authorize.Net';
$std3->value = 'authorizenet';
$field_values['field_options']['separate_value'] = 1;
$field_values['options'] = json_encode(array($std1, $std2, $std3));
$field_values['field_options']['placeholdertext'] = "paypal";
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id_pay = $arffield->create($field_values, true);
$field_order[$field_id_pay] = 4;
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['number'];
$field_values['name'] = 'Credit Card Number';
$field_values['field_options']['name'] = 'Credit Card Number';
$field_values['type'] = 'number';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter Credit Card Number', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['invaid'] = addslashes(esc_html__('Number is out of range', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['form_id'] = $form_id;
$field_id_card = $arffield->create($field_values, true);
$field_order[$field_id_card] = 5;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['number'];
$field_values['name'] = 'Expiry Month';
$field_values['field_options']['name'] = 'Expiry Month';
$field_values['type'] = 'number';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter Expiry Month', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['invaid'] = addslashes(esc_html__('Number is out of range', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['form_id'] = $form_id;
$field_id_exp_mnth = $arffield->create($field_values, true);
$field_order[$field_id_exp_mnth] = 6;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['number'];
$field_values['name'] = 'Expiry Year';
$field_values['field_options']['name'] = 'Expiry Year';
$field_values['type'] = 'number';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter Expiry Year', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['invaid'] = addslashes(esc_html__('Number is out of range', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['form_id'] = $form_id;
$field_id_exp_yr = $arffield->create($field_values, true);
$field_order[$field_id_exp_yr] = 7;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['number'];
$field_values['name'] = 'CVC';
$field_values['field_options']['name'] = 'CVC';
$field_values['type'] = 'number';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter CVC', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['invaid'] = addslashes(esc_html__('Number is out of range', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['form_id'] = $form_id;
$field_id_cvv = $arffield->create($field_values, true);
$field_order[$field_id_cvv] = 8;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['textarea'];
$field_values['name'] = addslashes(esc_html__('Leave your message? (optional)', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Leave your message? (optional)', 'ARForms'));
$field_values['field_options']['required'] = 0;
$field_values['type'] = 'textarea';
$field_values['field_options']['blank'] = addslashes(esc_html__('Please Leave your message', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));

$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 9;
unset($field_values);
unset($field_id);



$field_options = $wpdb->get_results($wpdb->prepare("SELECT `options` FROM `" . $MdlDb->forms . "` WHERE `id` = %d", $form_id));

$form_opt = maybe_unserialize($field_options[0]->options);
$form_opt['arf_field_order'] = json_encode($field_order);
$form_opt['arf_inner_field_order'] = json_encode( $inner_field_order );

$form_options = maybe_serialize($form_opt);

$wpdb->update($MdlDb->forms, array('options' => $form_options), array('id' => $form_id));

unset($field_order);
unset($inner_field_order);

$record = $arfform->getOne($form_id);
$conditional_logic = array();
$conditional_logic = array(
    '0' => array(
        'id' => 0,
        'logical_operator' => 'and',
        'condition' => array
            (
            '0' => array
                (
                'condition_id' => 0,
                'field_id' => $field_id_amt,
                'field_type' => 'radio',
                'operator' => 'equals',
                'value' => 'other',
            )
        ),
        'result' => Array
            (
            '0' => Array
                (
                'result_id' => 0,
                'action' => 'show',
                'field_id' => $field_id_gift_amt,
                'field_type' => 'text',
                'value' => '',
            )
        )
    ),

    '1' => array(
        'id' => 1,
        'logical_operator' => 'or',
        'condition' => array
            (
            '0' => array
                (
                'condition_id' => 1,
                'field_id' => $field_id_pay,
                'field_type' => 'radio',
                'operator' => 'equals',
                'value' => 'stripe',
            ),
            '1' => array
                (
                'condition_id' => 1,
                'field_id' => $field_id_pay,
                'field_type' => 'radio',
                'operator' => 'equals',
                'value' => 'authorizenet',
            )
        ),
        'result' => Array
            (
            '0' => Array
                (
                'result_id' => 1,
                'action' => 'show',
                'field_id' => $field_id_card,
                'field_type' => 'number',
                'value' => '',
            ),
            '1' => Array
                (
                'result_id' => 1,
                'action' => 'show',
                'field_id' => $field_id_exp_mnth,
                'field_type' => 'number',
                'value' => '',
            ),
            '2' => Array
                (
                'result_id' => 1,
                'action' => 'show',
                'field_id' => $field_id_exp_yr,
                'field_type' => 'number',
                'value' => '',
            ),
            '3' => Array
                (
                'result_id' => 1,
                'action' => 'show',
                'field_id' => $field_id_cvv,
                'field_type' => 'number',
                'value' => '',
            )
        )
    )


);
$record_old['options'] = $record->options;

$record_old['options']['arf_conditional_logic_rules'] = $conditional_logic;
$update = $wpdb->query($wpdb->prepare("update " . $MdlDb->forms . " set options = '%s' where id = '%d'", maybe_serialize($record_old['options']), $form_id));



if( isset($arf_update_templates) && $arf_update_templates == true ){
    $values['id'] = 9;
}
$values['name'] = 'Request a Quote';
$values['description'] = '';
$values['options']['custom_style'] = 1;
$values['is_template'] = '1';
$values['status'] = 'published';
$values['form_key'] = 'request-a-quote';
$values['options']['display_title_form'] = "1";
$values['temp_fields'] = array();
$new_values = array(

    "display_title_form" => '1',
    "arfmainformwidth" => '700',
    "form_width_unit" => 'px',
    "text_direction" => '1',
    "form_align" => 'left',
    "arfmainfieldsetpadding" => '30px 45px 30px 45px',
    "form_border_shadow" => 'flat',
    "fieldset" => '1',
    "arfmainfieldsetradius" => '6',
    "arfmainfieldsetcolor" => '#d9d9d9',
    "arfmainformbordershadowcolorsetting" => '#f2f2f2',
    "arfmainformtitlecolorsetting" => '#0d0e12',
    "check_weight_form_title" => 'normal',
    "form_title_font_size" => '28',
    "arfmainformtitlepaddingsetting" => '0px 0px 30px 0px',
    "arfmainformbgcolorsetting" => '#ffffff',
    "font" => 'Vidaloka',
    "label_color" => '#706d70',
    "weight" => 'normal',
    "font_size" => '16',
    "align" => 'left',
    "position" => 'top',
    "width" => '130',
    "width_unit" => 'px',
    "arfdescfontsizesetting" => '12',
    "arfdescalighsetting" => 'right',
    "hide_labels" => '',
    "check_font" => 'Vidaloka',
    "check_weight" => 'normal',
    "field_font_size" => '14',
    "text_color" => '#17181c',
    "border_radius" => '3',
    "border_color" => '#b0b0b5',
    "arffieldborderwidthsetting" => '1',
    "arffieldborderstylesetting" => 'solid',
    "arfsubmitbuttonstyle" => 'border',
    "field_width" => '100',
    "field_width_unit" => '%',
    "arffieldmarginssetting" => '23',
    "arffieldinnermarginssetting" => '10px 10px 10px 10px',
    "bg_color" => '#ffffff',
    "arfbgactivecolorsetting" => '#ffffff',
    "arfborderactivecolorsetting" => '#0C7CD5',
    "arferrorbgcolorsetting" => '#ffffff',
    "arferrorbordercolorsetting" => '#ed4040',
    "arfradioalignsetting" => '',
    "arfcheckboxalignsetting" => '' ,
    "auto_width" => '',
    "arfcalthemename" => '',
    "arfcalthemecss" => '',
    "date_format" => 'MMM D, YYYY',
    "arfsubmitbuttontext" => 'Submit',
    "arfsubmitweightsetting" => 'normal',
    "arfsubmitbuttonfontsizesetting" => '18',
    "arfsubmitbuttonwidthsetting" => '',
    "arfsubmitbuttonheightsetting" => '38',
    "submit_bg_color" => '#0C7CD5',
    "arfsubmitbuttonbgcolorhoversetting" => '#0264b5',
    "arfsubmitbgcolor2setting" => '',
    "arfsubmittextcolorsetting" => '#ffffff',
    "arfsubmitbordercolorsetting" => '#f6f6f8',
    "arfsubmitborderwidthsetting" => '0',
    'arfsubmitboxxoffsetsetting' => '1', 
    'arfsubmitboxyoffsetsetting' => '2',
    'arfsubmitboxblursetting' => '3',
    'arfsubmitboxshadowsetting' => '0',
    "arfsubmitborderradiussetting" => '3',
    "arfsubmitshadowcolorsetting" => '#c6c8cc',
    "arfsubmitbuttonmarginsetting" => '0px 0px 0px 0px',
    "submit_bg_img" => '',
    "submit_hover_bg_img" => '',
    "error_font" => 'Vidaloka',
    "error_font_other" => '',
    "arffontsizesetting" => '14',
    "arferrorbgsetting" => '#F3CAC7',
    "arferrortextsetting" => '#501411',
    "arferrorbordersetting" => '#FA8B83',
    'arfsucessbgcolorsetting' => '#E0FDE2',
    'arfsucessbordercolorsetting' => '#BFE0C1',
    'arfsucesstextcolorsetting' => '#4C4D4E',
    'arfformerrorbgcolorsetting' => '#FDECED',
    'arfformerrorbordercolorsetting' => '#F9CFD1',
    'arfformerrortextcolorsetting' => '#ED4040',
    "arfsubmitalignsetting" => 'left',
    "checkbox_radio_style" => '',
    "bg_color_pg_break" => '#0C7CD5',
    "bg_inavtive_color_pg_break" => '#7ac1fa',
    "text_color_pg_break" => '#ffffff',
    "arfmainform_bg_img" => '',
    "arfmainform_color_skin" => 'blue',
    "arfinputstyle" => 'standard',
    "arfsubmitfontfamily" => 'Vidaloka',
    "arfmainfieldcommonsize" => '3',
    "arfdatepickerbgcolorsetting" => '#0C7CD5',
    "arfdatepickertextcolorsetting" => '#000000',
    "arfmainfieldsetpadding_1" => '30',
    "arfmainfieldsetpadding_2" => '45',
    "arfmainfieldsetpadding_3" => '30',
    "arfmainfieldsetpadding_4" => '45',
    "arfmainformtitlepaddingsetting_1" => '0',
    "arfmainformtitlepaddingsetting_2" => '0',
    "arfmainformtitlepaddingsetting_3" => '30',
    "arfmainformtitlepaddingsetting_4" => '0',
    "arffieldinnermarginssetting_1" => '10',
    "arffieldinnermarginssetting_2" => '10',
    "arffieldinnermarginssetting_3" => '0',
    "arffieldinnermarginssetting_4" => '0',
    "arfsubmitbuttonmarginsetting_1" => '0',
    "arfsubmitbuttonmarginsetting_2" => '0',
    "arfsubmitbuttonmarginsetting_3" => '0',
    "arfsubmitbuttonmarginsetting_4" => '0',
    'arfsectionpaddingsetting_1' => '20',
    'arfsectionpaddingsetting_2' => '0',
    'arfsectionpaddingsetting_3' => '20',
    'arfsectionpaddingsetting_4' => '20',
    'arfsectionpaddingsetting' => "20px 0px 20px 20px",
    "arfcheckradiostyle" => 'custom',
    "arfcheckradiocolor" => '',
    "arf_checked_checkbox_icon" => 'fa-check',
    "enable_arf_checkbox" => '',
    "arf_checked_radio_icon" => 'fa-check',
    "enable_arf_radio" => '1',
    "checked_checkbox_icon_color" => '#0C7CD5',
    "checked_radio_icon_color" => '#0C7CD5',
    "arferrorstyle" => 'advance',
    'arferrorstylecolor' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstylecolor2' => '#ed4040|#FFFFFF|#ed4040',
    "arferrorstyleposition" => 'bottom',
    "arfvalidationbgcolorsetting" => '#ed4040',
    "arfvalidationtextcolorsetting" => '#ffffff',
    "arfformtitlealign" => 'center',
    "arfsubmitautowidth" => '125',
    "arftitlefontfamily" => 'Vidaloka',
    "bar_color_survey" => '#0C7CD5',
    "bg_color_survey" => '#dadde2',
    "text_color_survey" => '#333333',    
    "arfmainform_opacity" => '1',
    "arfmainfield_opacity" => '',
    "prefix_suffix_bg_color" => '#e7e8ec',
    "prefix_suffix_icon_color" => '#808080',
    "arf_tooltip_bg_color" => '#000000',
    "arf_tooltip_font_color" => '#ffffff',
    "arf_tooltip_width" => '',
    "arf_tooltip_position" =>'' ,
    "arftippos" => '',
    "arfcommonfont" => 'Vidaloka',
    "arfsectiontitlefamily" => 'Vidaloka',
    "arfsectiontitlefontsizesetting" => '16',
    "arfsectiontitleweightsetting" => 'bold',
    "arfsubmitbuttontext"=>"Submit",
    "arfuploadbtntxtcolorsetting"=>"#FFFFFF",
    "arfuploadbtnbgcolorsetting" =>"#0C7CD5",
    "arf_req_indicator"=>"0",
    "arf_section_inherit_bg" => "1",
    "arfformsectionbackgroundcolor"=>"#ffffff",
    "arfmainbasecolor" => "#0c7cd5",
    "arflikebtncolor"=>"#4786ff",
    "arfdislikebtncolor"=>"#ec3838",
    "arfstarratingcolor"=>"#FCBB1D",
    "arfsliderselectioncolor"=>"#d1dee5",
    "arfslidertrackcolor"=>"#bcc7cd",
    "arfplaceholder_opacity" => "0.5",
    "arf_bg_position_x" => "left",
    "arf_bg_position_input_x" => "",
    "arf_bg_position_y" => "top",
    "arf_bg_position_input_y" => "",

);

$new_values1 = maybe_serialize($new_values);
$values['form_css'] = $new_values1;
$form_id = $arfform->create($values);
if (!empty($new_values)) {

    $use_saved = true;

    $arfssl = (is_ssl()) ? 1 : 0;

    $filename = FORMPATH . '/core/css_create_main.php';

    $wp_upload_dir = wp_upload_dir();

    $target_path = $wp_upload_dir['basedir'] . '/arforms/maincss';

    $css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";


    $css .= "\n";


    ob_start();


    include $filename;


    $css .= ob_get_contents();


    ob_end_clean();


    $css .= "\n " . $warn;

    $css_file = $target_path . '/maincss_' . $form_id . '.css';

    WP_Filesystem();
    global $wp_filesystem;
    $css = str_replace('##', '#', $css);
    $wp_filesystem->put_contents($css_file, $css, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename1 = FORMPATH . '/core/css_create_materialize.php';
    $css1 = $warn1 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css1 .= "\n";
    ob_start();
    include $filename1;
    $css1 .= ob_get_contents();
    ob_end_clean();
    $css1 .= "\n " . $warn1;
    $css_file1 = $target_path . '/maincss_materialize_' . $form_id . '.css';
    WP_Filesystem();
    $css1 = str_replace('##', '#', $css1);
    $wp_filesystem->put_contents($css_file1, $css1, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename2 = FORMPATH . '/core/css_create_materialize_outline.php';
    $css2 = $warn2 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css2 .= "\n";
    ob_start();
    include $filename2;
    $css2 .= ob_get_contents();
    ob_end_clean();
    $css2 .= "\n" . $warn2;
    $css_file2 = $target_path . '/maincss_materialize_outlined_' . $form_id . '.css';
    WP_Filesystem();
    $css2 = str_replace( '##', '#', $css2 );
    $wp_filesystem->put_contents( $css_file2, $css2, 0777 );
} else {
    $query_results = true;
}

$field_order = array();
$inner_field_order = array();

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'First Name';
$field_values['field_options']['name'] = 'First Name';
$field_values['field_options']['description'] = '';
$field_values['type'] = 'text';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter first name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 1;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Last Name';
$field_values['field_options']['name'] = 'Last Name';
$field_values['type'] = 'text';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter last name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 2;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['email'];
$field_values['name'] = 'Email ID';
$field_values['type'] = 'email';
$field_values['field_options']['name'] = 'Email';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter email ID', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Please enter a valid email address', 'ARForms'));
$field_values['field_options']['confirm_email'] = 0;
$field_values['field_options']['confirm_email_label'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['field_options']['invalid_confirm_email'] = addslashes(esc_html__('Confirm email address does not match with email', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 3;
unset($field_values);
unset($field_id);
unset($values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Company / Organization Name';
$field_values['field_options']['name'] = 'Company / Organization Name';
$field_values['type'] = 'text';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 0;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter Company / Organization Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 4;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['radio'];
$field_values['field_key'] = '1';
$field_values['name'] = 'Preffered Method for Contact';
$field_values['field_options']['name'] = 'Preffered Method for Contact';
$field_values['field_options']['required'] = 0;
$field_values['type'] = 'radio';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['separate_value'] = 0;
$field_values['options'] = json_encode(array('Email','Phone','Skype'));
$field_values['field_options']['placeholdertext'] = "Email";
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id_pref_meth = $arffield->create($field_values, true);
$field_order[$field_id_pref_meth] = 5;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['phone'];
$field_values['name'] = 'Phone';
$field_values['field_options']['name'] = 'Phone';
$field_values['type'] = 'phone';
$field_values['field_options']['description'] = 'please include country code eg. +11234567890';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter Phone', 'ARForms'));
$field_values['field_options']['phone_validation'] = 'international';
$field_values['field_options']['invalid'] = 'Phone is invalid';

$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id_phone = $arffield->create($field_values, true);
$field_order[$field_id_phone] = 6;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Skype Name';
$field_values['field_options']['name'] = 'Skype Name';
$field_values['type'] = 'text';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter Skype Name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id_skype = $arffield->create($field_values, true);
$field_order[$field_id_skype] = 7;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['textarea'];
$field_values['name'] = addslashes(esc_html__('Describe your request', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Describe your request', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'textarea';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 8;
unset($field_values);
unset($field_id);

$field_options = $wpdb->get_results($wpdb->prepare("SELECT `options` FROM `" . $MdlDb->forms . "` WHERE `id` = %d", $form_id));

$form_opt = maybe_unserialize($field_options[0]->options);
$form_opt['arf_field_order'] = json_encode($field_order);
$form_opt['arf_inner_field_order'] = json_encode( $inner_field_order );

$form_options = maybe_serialize($form_opt);

$wpdb->update($MdlDb->forms, array('options' => $form_options), array('id' => $form_id));

unset($field_order);
unset($inner_field_order);

$record = $arfform->getOne($form_id);
$conditional_logic = array();
$conditional_logic = array(
    '0' => array(
        'id' => 0,
        'logical_operator' => 'and',
        'condition' => array
            (
            '0' => array
                (
                'condition_id' => 0,
                'field_id' => $field_id_pref_meth,
                'field_type' => 'radio',
                'operator' => 'equals',
                'value' => 'Phone',
            )
        ),
        'result' => Array
            (
            '0' => Array
                (
                'result_id' => 0,
                'action' => 'show',
                'field_id' => $field_id_phone,
                'field_type' => 'phone',
                'value' => '',
            )
        )
    ),
    '1' => array(
        'id' => 1,
        'logical_operator' => 'and',
        'condition' => array
            (
            '0' => array
                (
                'condition_id' => 1,
                'field_id' => $field_id_pref_meth,
                'field_type' => 'radio',
                'operator' => 'equals',
                'value' => 'Skype',
            )
        ),
        'result' => Array
            (
            '0' => Array
                (
                'result_id' => 1,
                'action' => 'show',
                'field_id' => $field_id_skype,
                'field_type' => 'text',
                'value' => '',
            )
        )
    )

  

);
$record_old['options'] = $record->options;

$record_old['options']['arf_conditional_logic_rules'] = $conditional_logic;
$update = $wpdb->query($wpdb->prepare("update " . $MdlDb->forms . " set options = '%s' where id = '%d'", maybe_serialize($record_old['options']), $form_id));


if( isset($arf_update_templates) && $arf_update_templates == true ){
    $values['id'] = 10;
}

$values['name'] = 'Member Login';
$values['description'] = '';
$values['options']['custom_style'] = 1;
$values['is_template'] = '1';
$values['status'] = 'published';
$values['form_key'] = 'memberlogin';
$values['options']['display_title_form'] = "1";
$values['temp_fields'] = array();
$new_values = array (
      'display_title_form' => '1',
      'arfmainformwidth' => '500',
      'form_width_unit' => 'px',
      'text_direction' => '1',
      'form_align' => 'left',
      'arfmainfieldsetpadding' => '30px 45px 30px 45px',
      'form_border_shadow' => 'flat',
      'fieldset' => '1',
      'arfmainfieldsetradius' => '0',
      'arfmainfieldsetcolor' => '#d9d9d9',
      'arfmainformbordershadowcolorsetting' => '#f2f2f2',
      'arfmainformtitlecolorsetting' => '#0d0e12',
      'check_weight_form_title' => 'bold',
      'form_title_font_size' => '28',
      'arfmainformtitlepaddingsetting' => '150px 0px 20px 0px',
      'arfmainformbgcolorsetting' => '#ffffff',
      'font' => 'Capriola',
      'label_color' => '#706d70',
      'weight' => 'normal',
      'font_size' => '16',
      'align' => 'left',
      'position' => 'top',
      'width' => '130',
      'width_unit' => 'px',
      'arfdescfontsizesetting' => '12',
      'arfdescalighsetting' => 'right',
      'hide_labels' => '1',
      'check_font' => 'Capriola',
      'check_weight' => 'normal',
      'field_font_size' => '14',
      'text_color' => '#17181c',
      'border_radius' => '4',
      'border_color' => '#b0b0b5',
      'arffieldborderwidthsetting' => '1',
      'arffieldborderstylesetting' => 'solid',
      'arfsubmitbuttonstyle' => 'flat',
      'field_width' => '100',
      'field_width_unit' => '%',
      'arffieldmarginssetting' => '20',
      'arffieldanimationdurationsetting' => '0',
      'arfpbfieldanimationdurationsetting' => '0',
      'arffieldanimationdelaysetting' => '0',
      'arfpbfieldanimationdelaysetting' => '0',
      'arffieldinnermarginssetting' => '10px 10px 10px 10px',
      'bg_color' => '#ffffff',
      'arfbgactivecolorsetting' => '#ffffff',
      'arfborderactivecolorsetting' => '#23b7e5',
      'arferrorbgcolorsetting' => '#ffffff',
      'arferrorbordercolorsetting' => '#ed4040',
      'arfradioalignsetting' => '',
      'arfcheckboxalignsetting' => '',
      'auto_width' => '',
      'arfcalthemename' => '',
      'arfcalthemecss' => '',
      'date_format' => 'MMM D, YYYY',
      'arfsubmitbuttontext' => 'Sign In',
      'arfsubmitweightsetting' => 'normal',
      'arfsubmitbuttonfontsizesetting' => '18',
      'arfsubmitbuttonwidthsetting' => '80',
      'arfsubmitbuttonheightsetting' => '40',
      'submit_bg_color' => '#23b7e5',
      'arfsubmitbuttonbgcolorhoversetting' => '#1d9dc4',
      'arfsubmitbgcolor2setting' => '',
      'arfsubmittextcolorsetting' => '#ffffff',
      'arfsubmitbordercolorsetting' => '#f6f6f8',
      'arfsubmitborderwidthsetting' => '0',
      'arfsubmitboxxoffsetsetting' => '1', 
      'arfsubmitboxyoffsetsetting' => '2',
      'arfsubmitboxblursetting' => '3',
      'arfsubmitboxshadowsetting' => '0',
      'arfsubmitborderradiussetting' => '0',
      'arfsubmitshadowcolorsetting' => '#c6c8cc',
      'arfsubmitbuttonmarginsetting' => '10px 10px 0px 0px',
      'submit_bg_img' => '',
      'submit_hover_bg_img' => '',
      'error_font' => 'Capriola',
      'error_font_other' => '',
      'arffontsizesetting' => '14',
      'arferrorbgsetting' => '#F3CAC7',
      'arferrortextsetting' => '#501411',
      'arferrorbordersetting' => '#FA8B83',
      'arfsucessbgcolorsetting' => '#E0FDE2',
      'arfsucessbordercolorsetting' => '#BFE0C1',
      'arfsucesstextcolorsetting' => '#4C4D4E',
      'arfformerrorbgcolorsetting' => '#FDECED',
      'arfformerrorbordercolorsetting' => '#F9CFD1',
      'arfformerrortextcolorsetting' => '#ED4040',
      'arfsubmitalignsetting' => 'center',
      'checkbox_radio_style' => '',
      'bg_color_pg_break' => '#23b7e5',
      'bg_inavtive_color_pg_break' => '#66d7fa',
      'text_color_pg_break' => '#ffffff',
      'text_color_pg_break_style3' => '#23b7e5',
      'arfmainform_bg_img' => '',
      'arfmainform_color_skin' => 'cyan',
      'arfinputstyle' => 'standard',
      'arfsubmitfontfamily' => 'Capriola',
      'arfmainfieldcommonsize' => '3',
      'arfdatepickerbgcolorsetting' => '#23b7e5',
      'arfdatepickertextcolorsetting' => '#000000',
      'arfmainfieldsetpadding_1' => '30',
      'arfmainfieldsetpadding_2' => '45',
      'arfmainfieldsetpadding_3' => '30',
      'arfmainfieldsetpadding_4' => '45',
      'arfmainformtitlepaddingsetting_1' => '150',
      'arfmainformtitlepaddingsetting_2' => '0',
      'arfmainformtitlepaddingsetting_3' => '20',
      'arfmainformtitlepaddingsetting_4' => '0',
      'arffieldinnermarginssetting_1' => '10',
      'arffieldinnermarginssetting_2' => '10',
      'arffieldinnermarginssetting_3' => '0',
      'arffieldinnermarginssetting_4' => '0',
      'arfsubmitbuttonmarginsetting_1' => '10',
      'arfsubmitbuttonmarginsetting_2' => '10',
      'arfsubmitbuttonmarginsetting_3' => '0',
      'arfsubmitbuttonmarginsetting_4' => '0',
      'arfsectionpaddingsetting_1' => '20',
      'arfsectionpaddingsetting_2' => '0',
      'arfsectionpaddingsetting_3' => '20',
      'arfsectionpaddingsetting_4' => '20',
      'arfsectionpaddingsetting' => "20px 0px 20px 20px",
      'arfcheckradiostyle' => 'default',
      'arffieldanimationstyle' => 'no animation',
      'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
      'arfcheckradiocolor' => '',
      'arf_checked_checkbox_icon' => 'fa-check',
      'enable_arf_checkbox' => '',
      'arf_checked_radio_icon' => 'fa-circle',
      'enable_arf_radio' => '',
      'checked_checkbox_icon_color' => '#23b7e5',
      'checked_radio_icon_color' => '#23b7e5',
      'arferrorstyle' => 'advance',
      'arferrorstylecolor' => '#ed4040|#FFFFFF|#ed4040',
      'arferrorstylecolor2' => '#ed4040|#FFFFFF|#ed4040',
      'arferrorstyleposition' => 'bottom',
      'arfvalidationbgcolorsetting' => '#ed4040',
      'arfvalidationtextcolorsetting' => '#ffffff',
      'arfformtitlealign' => 'center',
      'arfsubmitautowidth' => '125',
      'arftitlefontfamily' => 'Capriola',
      'bar_color_survey' => '#23b7e5',
      'bg_color_survey' => '#dadde2',
      'text_color_survey' => '#333333',
      'arfmainform_opacity' => '1',
      'arfmainfield_opacity' => '',
      'prefix_suffix_bg_color' => '#e7e8ec',
      'prefix_suffix_icon_color' => '#808080',
      'arf_tooltip_bg_color' => '#000000',
      'arf_tooltip_font_color' => '#ffffff',
      'arf_tooltip_width' => '',
      'arf_tooltip_position' => '',
      "arftippos" => '',
      'arfcommonfont' => 'Capriola',
      'arfsectiontitlefamily' => 'Capriola',
      'arfsectiontitlefontsizesetting' => '16',
      'arfsectiontitleweightsetting' => 'bold',
      "arfuploadbtntxtcolorsetting"=>"#FFFFFF",
      "arfuploadbtnbgcolorsetting" =>"#23b7e5",
      "arf_req_indicator"=>"0",
      "arf_section_inherit_bg" => "1",
    "arfformsectionbackgroundcolor"=>"#ffffff",
    "arfmainbasecolor" => "#23b7e5",
    "arflikebtncolor"=>"#4786ff",
    "arfdislikebtncolor"=>"#ec3838",
    "arfstarratingcolor"=>"#FCBB1D",
    "arfsliderselectioncolor"=>"#d1dee5",
    "arfslidertrackcolor"=>"#bcc7cd",
    "arfplaceholder_opacity" => "0.5",
    "arf_bg_position_x" => "left",
    "arf_bg_position_input_x" => "",
    "arf_bg_position_y" => "top",
    "arf_bg_position_input_y" => "",
);

$new_values1 = maybe_serialize($new_values);
$values['form_css'] = $new_values1;
$form_id = $arfform->create($values);
if (!empty($new_values)) {

    $use_saved = true;

    $arfssl = (is_ssl()) ? 1 : 0;

    $filename = FORMPATH . '/core/css_create_main.php';

    $wp_upload_dir = wp_upload_dir();

    $target_path = $wp_upload_dir['basedir'] . '/arforms/maincss';

    $css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";


    $css .= "\n";


    ob_start();


    include $filename;


    $css .= ob_get_contents();


    ob_end_clean();


    $css .= "\n " . $warn;

    $css_file = $target_path . '/maincss_' . $form_id . '.css';

    WP_Filesystem();
    global $wp_filesystem;
    $css = str_replace('##', '#', $css);
    $wp_filesystem->put_contents($css_file, $css, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename1 = FORMPATH . '/core/css_create_materialize.php';
    $css1 = $warn1 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css1 .= "\n";
    ob_start();
    include $filename1;
    $css1 .= ob_get_contents();
    ob_end_clean();
    $css1 .= "\n " . $warn1;
    $css_file1 = $target_path . '/maincss_materialize_' . $form_id . '.css';
    WP_Filesystem();
    $css1 = str_replace('##', '#', $css1);
    $wp_filesystem->put_contents($css_file1, $css1, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename2 = FORMPATH . '/core/css_create_materialize_outline.php';
    $css2 = $warn2 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css2 .= "\n";
    ob_start();
    include $filename2;
    $css2 .= ob_get_contents();
    ob_end_clean();
    $css2 .= "\n" . $warn2;
    $css_file2 = $target_path . '/maincss_materialize_outlined_' . $form_id . '.css';
    WP_Filesystem();
    $css2 = str_replace( '##', '#', $css2 );
    $wp_filesystem->put_contents( $css_file2, $css2, 0777 );
} else {
    $query_results = true;
}

$field_order = array();
$inner_field_order = array();

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Username / Email';
$field_values['field_options']['name'] = 'Username / Email';
$field_values['field_options']['placeholdertext'] = 'Username / Email';
$field_values['field_options']['description'] = '';
$field_values['type'] = 'text';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please Enter Username / Email', 'ARForms'));
$field_values['field_options']['placeholdertext'] =  addslashes(esc_html__('Username / Email', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['enable_arf_prefix'] = "1";
$field_values['field_options']['arf_prefix_icon'] = "fa fa-user";
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 1;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['password'];
$field_values['name'] = 'Password';
$field_values['field_options']['name'] = 'Password';
$field_values['field_options']['placeholdertext'] = 'Password';
$field_values['type'] = 'password';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please Enter Password', 'ARForms'));
$field_values['field_options']['placeholdertext'] = addslashes(esc_html__('Enter Password', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['enable_arf_prefix'] = "1";
$field_values['field_options']['arf_prefix_icon'] = "fa fa-key";
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 2;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['checkbox'];
$field_values['name'] = 'Remembar me';
$field_values['field_options']['name'] = 'Remember me';
$field_values['field_options']['required'] = 0;
$field_values['type'] = 'checkbox';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['classes'] = '';
$field_values['field_options']['separate_value'] = "false";
$field_values['options'] = json_encode(array('Remember Me'));
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 3;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['imagecontrol'];
$field_values['name'] =  "";
$field_values['type'] = 'imagecontrol';
$field_values['field_options']['name'] = "";;
$field_values['field_options']['required'] = 0;
$field_values['field_options']['image_position_from'] = 'top_left';
$field_values['field_options']['image_url'] = ARFURL.'/images/user_avatar.png';
$field_values['field_options']['image_center'] = "Yes";
$field_values['field_options']['image_left'] = "";
$field_values['field_options']['image_top'] = "40px";
$field_values['field_options']['image_width'] = "100px";
$field_values['field_options']['image_height'] = "100px";
$field_values['field_options']['editor_image_top'] = "40px";
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values,true);
$field_order[$field_id] = 4;
unset($field_values);

unset($field_id);

$field_options = $wpdb->get_results($wpdb->prepare("SELECT `options` FROM `" . $MdlDb->forms . "` WHERE `id` = %d", $form_id));

$form_opt = maybe_unserialize($field_options[0]->options);
$form_opt['arf_field_order'] = json_encode($field_order);
$form_opt['arf_inner_field_order'] = json_encode( $inner_field_order );

$form_options = maybe_serialize($form_opt);

$wpdb->update($MdlDb->forms, array('options' => $form_options), array('id' => $form_id));

unset($field_order);
unset($inner_field_order);

if( isset($arf_update_templates) && $arf_update_templates == true ){
    $values['id'] = 11;
}

$values['name'] = 'Order Form';
$values['description'] = '';
$values['options']['custom_style'] = 1;
$values['is_template'] = '1';
$values['status'] = 'published';
$values['form_key'] = 'order-form';
$values['options']['display_title_form'] = "1";
$values['temp_fields'] = array();

$new_values = array( 
    'display_title_form' => '1',
    'arfmainformwidth' => '800',
    'form_width_unit' => 'px',
    'text_direction' => '1',
    'form_align' => 'left',
    'arfmainfieldsetpadding' => '30px 45px 30px 45px',
    'form_border_shadow' => 'flat',
    'fieldset' => '1',
    'arfmainfieldsetradius' => '6',
    'arfmainfieldsetcolor' => '#D9D9D9',
    'arfmainformbordershadowcolorsetting' => '#F2F2F2',
    'arfmainformtitlecolorsetting' => '#0D0E12',
    'check_weight_form_title' => 'normal',
    'form_title_font_size' => '28',
    'arfmainformtitlepaddingsetting' => '0px 0px 20px 0px',
    'arfmainformbgcolorsetting' => '#FFFFFF',
    'font' => 'Helvetica',
    'label_color' => '#706D70',
    'weight' => 'normal',
    'font_size' => '16',
    'align' => 'left',
    'position' => 'top',
    'width' => '130',
    'width_unit' => 'px',
    'arfdescfontsizesetting' => '12',
    'arfdescalighsetting' => 'right',
    'hide_labels' => '',
    'check_font' => 'Helvetica',
    'check_weight' => 'normal',
    'field_font_size' => '14',
    'text_color' => '#17181C',
    'border_radius' => '0',
    'border_color' => '#B0B0B5',
    'arffieldborderwidthsetting' => '1',
    'arffieldborderstylesetting' => 'solid',
    'arfsubmitbuttonstyle' => 'border',
    'field_width' => '100',
    'field_width_unit' => '%',
    'arffieldmarginssetting' => '23',
    'arffieldanimationdurationsetting' => '0',
    'arfpbfieldanimationdurationsetting' => '0',
    'arffieldanimationdelaysetting' => '0',
    'arfpbfieldanimationdelaysetting' => '0',
    'arffieldinnermarginssetting' => '0px 0px 0px 0px',
    'bg_color' => '#FFFFFF',
    'arfbgactivecolorsetting' => '#FFFFFF',
    'arfborderactivecolorsetting' => '#6164C1',
    'arferrorbgcolorsetting' => '#FFFFFF',
    'arferrorbordercolorsetting' => '#ED4040',
    'arfradioalignsetting' => '',
    'arfcheckboxalignsetting' => '',
    'auto_width' => '',
    'arfcalthemename' => '',
    'arfcalthemecss' => '',
    'date_format' => 'MMM D, YYYY',
    'arfsubmitbuttontext' => 'Submit Order',
    'arfsubmitweightsetting' => 'normal',
    'arfsubmitbuttonfontsizesetting' => '18',
    'arfsubmitbuttonwidthsetting' => '',
    'arfsubmitbuttonheightsetting' => '38',
    'submit_bg_color' => '#6164C1',
    'arfsubmitbuttonbgcolorhoversetting' => '#5053A3',
    'arfsubmitbgcolor2setting' => '#',
    'arfsubmittextcolorsetting' => '#FFFFFF',
    'arfsubmitbordercolorsetting' => '#F6F6F8',
    'arfsubmitborderwidthsetting' => '2',
    'arfsubmitboxxoffsetsetting' => '1', 
    'arfsubmitboxyoffsetsetting' => '2',
    'arfsubmitboxblursetting' => '3',
    'arfsubmitboxshadowsetting' => '0',
    'arfsubmitborderradiussetting' => '3',
    'arfsubmitshadowcolorsetting' => '#C6C8CC',
    'arfsubmitbuttonmarginsetting' => '10px 10px 0px 0px',
    'submit_bg_img' => '',
    'submit_hover_bg_img' => '',
    'error_font' => 'Lucida Sans Unicode',
    'error_font_other' => '',
    'arffontsizesetting' => '14',
    'arferrorbgsetting' => '#F3CAC7',
    'arferrortextsetting' => '#501411',
    'arferrorbordersetting' => '#FA8B83',
    'arfsucessbgcolorsetting' => '#FFFFFF',
    'arfsucessbordercolorsetting' => '#D7D8D8',
    'arfsucesstextcolorsetting' => '#24DC67',
    'arfformerrorbgcolorsetting' => '#FFFFFF',
    'arfformerrorbordercolorsetting' => '#D7D8D8',
    'arfformerrortextcolorsetting' => '#F71F4F',
    'arfsubmitalignsetting' => 'left',
    'checkbox_radio_style' => '',
    'bg_color_pg_break' => '#6164C1',
    'bg_inavtive_color_pg_break' => '#9295F7',
    'text_color_pg_break' => '#FFFFFF',
    'text_color_pg_break_style3' => '#6164C1',
    'arfmainform_bg_img' => '',
    'arfmainform_color_skin' => 'purple',
    'arfinputstyle' => 'material',
    'arfsubmitfontfamily' => 'Helvetica',
    'arfmainfieldcommonsize' => '3',
    'arfdatepickerbgcolorsetting' => '#6164C1',
    'arfdatepickertextcolorsetting' => '#000000',
    'arfmainfieldsetpadding_1' => '30',
    'arfmainfieldsetpadding_2' => '45',
    'arfmainfieldsetpadding_3' => '30',
    'arfmainfieldsetpadding_4' => '45',
    'arfmainformtitlepaddingsetting_1' => '0',
    'arfmainformtitlepaddingsetting_2' => '0',
    'arfmainformtitlepaddingsetting_3' => '20',
    'arfmainformtitlepaddingsetting_4' => '0',
    'arffieldinnermarginssetting_1' => '0',
    'arffieldinnermarginssetting_2' => '0',
    'arffieldinnermarginssetting_3' => 0,
    'arffieldinnermarginssetting_4' => 0,
    'arfsubmitbuttonmarginsetting_1' => '10',
    'arfsubmitbuttonmarginsetting_2' => '10',
    'arfsubmitbuttonmarginsetting_3' => '0',
    'arfsubmitbuttonmarginsetting_4' => '0',
    'arfsectionpaddingsetting_1' => '20',
    'arfsectionpaddingsetting_2' => '0',
    'arfsectionpaddingsetting_3' => '20',
    'arfsectionpaddingsetting_4' => '20',
    'arfsectionpaddingsetting' => "20px 0px 20px 20px",
    'arfcheckradiostyle' => 'material',
    'arffieldanimationstyle' => 'no animation',
    'arfpbfieldanimationstyle' => 'slideInLeft',
    "arfpagebreakinheritanimation" => "0",
    'arfcheckradiocolor' => '',
    'arf_checked_checkbox_icon' => 'fa-check',
    'enable_arf_checkbox' => '',
    'arf_checked_radio_icon' => 'fa-circle',
    'enable_arf_radio' => '',
    'checked_checkbox_icon_color' => '#6164C1',
    'checked_radio_icon_color' => '#6164C1',
    'arferrorstyle' => 'advance',
    'arferrorstylecolor' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstylecolor2' => '#ed4040|#FFFFFF|#ed4040',
    'arferrorstyleposition' => 'bottom',
    'arfvalidationbgcolorsetting' => '#ED4040',
    'arfvalidationtextcolorsetting' => '#FFFFFF',
    'arfformtitlealign' => 'left',
    'arfsubmitautowidth' => '125',
    'arftitlefontfamily' => 'Helvetica',
    'bar_color_survey' => '#6164C1',
    'bg_color_survey' => '#DADDE2',
    'text_color_survey' => '#333333',
    'arfmainform_opacity' => '1',
    'arfmainfield_opacity' => 1,
    'prefix_suffix_bg_color' => '#E7E8EC',
    'prefix_suffix_icon_color' => '#808080',
    'arf_tooltip_bg_color' => '#000000',
    'arf_tooltip_font_color' => '#FFFFFF',
    'arf_tooltip_width' => '',
    'arf_tooltip_position' => '',
    "arftippos" => '',
    'arfcommonfont' => 'Helvetica',
    'arfsectiontitlefamily' => 'Helvetica',
    'arfsectiontitlefontsizesetting' => '24',
    'arfsectiontitleweightsetting' => 'bold',
    "arfsubmitbuttontext"=>"Pay Now",
    "arfuploadbtntxtcolorsetting"=>"#FFFFFF",
    "arfuploadbtnbgcolorsetting" =>"#23b7e5",
    "arf_req_indicator"=>"0",
    "arf_section_inherit_bg" => "1",
    "arfformsectionbackgroundcolor"=>"#ffffff",
    "arfmainbasecolor" => "#23b7e5",
    "arflikebtncolor"=>"#4786ff",
    "arfdislikebtncolor"=>"#ec3838",
    "arfstarratingcolor"=>"#FCBB1D",
    "arfsliderselectioncolor"=>"#d1dee5",
    "arfslidertrackcolor"=>"#bcc7cd",
    "arfplaceholder_opacity" => "0.5",
    "arf_bg_position_x" => "left",
    "arf_bg_position_input_x" => "",
    "arf_bg_position_y" => "top",
    "arf_bg_position_input_y" => "",
);

$new_values1 = maybe_serialize($new_values);
$values['form_css'] = $new_values1;
$form_id = $arfform->create($values);
if (!empty($new_values)) {

    $use_saved = true;

    $arfssl = (is_ssl()) ? 1 : 0;

    $filename = FORMPATH . '/core/css_create_main.php';

    $wp_upload_dir = wp_upload_dir();

    $target_path = $wp_upload_dir['basedir'] . '/arforms/maincss';

    $css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";


    $css .= "\n";


    ob_start();


    include $filename;


    $css .= ob_get_contents();


    ob_end_clean();


    $css .= "\n " . $warn;

    $css_file = $target_path . '/maincss_' . $form_id . '.css';

    WP_Filesystem();
    global $wp_filesystem;
    $css = str_replace('##', '#', $css);
    $wp_filesystem->put_contents($css_file, $css, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename1 = FORMPATH . '/core/css_create_materialize.php';
    $css1 = $warn1 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css1 .= "\n";
    ob_start();
    include $filename1;
    $css1 .= ob_get_contents();
    ob_end_clean();
    $css1 .= "\n " . $warn1;
    $css_file1 = $target_path . '/maincss_materialize_' . $form_id . '.css';
    WP_Filesystem();
    $css1 = str_replace('##', '#', $css1);
    $wp_filesystem->put_contents($css_file1, $css1, 0777);
    wp_cache_delete($form_id, 'arfform');

    $filename2 = FORMPATH . '/core/css_create_materialize_outline.php';
    $css2 = $warn2 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
    $css2 .= "\n";
    ob_start();
    include $filename2;
    $css2 .= ob_get_contents();
    ob_end_clean();
    $css2 .= "\n" . $warn2;
    $css_file2 = $target_path . '/maincss_materialize_outlined_' . $form_id . '.css';
    WP_Filesystem();
    $css2 = str_replace( '##', '#', $css2 );
    $wp_filesystem->put_contents( $css_file2, $css2, 0777 );
} else {
    $query_results = true;
}

$field_order = array();
$inner_field_order = array();

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'First Name';
$field_values['field_options']['name'] = 'First Name';
$field_values['field_options']['description'] = '';
$field_values['type'] = 'text';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter first name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 1;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'Last Name';
$field_values['field_options']['name'] = 'Last Name';
$field_values['type'] = 'text';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter last name', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 2;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['email'];
$field_values['name'] = 'Email ID';
$field_values['type'] = 'email';
$field_values['field_options']['name'] = 'Email';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter email ID', 'ARForms'));
$field_values['field_options']['invalid'] = addslashes(esc_html__('Please enter a valid email address', 'ARForms'));
$field_values['field_options']['confirm_email'] = 0;
$field_values['field_options']['confirm_email_label'] = addslashes(esc_html__('Confirm Email Address', 'ARForms'));
$field_values['field_options']['invalid_confirm_email'] = addslashes(esc_html__('Confirm email address does not match with email', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 3;
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['phone'];
$field_values['name'] = 'Phone';
$field_values['field_options']['name'] = 'Phone';
$field_values['type'] = 'phone';
$field_values['field_options']['description'] = 'please include country code eg. +11234567890';
$field_values['field_options']['required'] = 1;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter Phone', 'ARForms'));
$field_values['field_options']['phone_validation'] = 'international';
$field_values['field_options']['invalid'] = 'Phone is invalid';
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id_phone = $arffield->create($field_values, true);
$field_order[$field_id_phone] = 4;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['break'];
$field_values['type'] = 'break';
$field_values['name'] = '';
$field_values['field_options']['first_page_label'] = 'Customer Details';
$field_values['field_options']['second_page_label'] = 'Select Product';
$field_values['field_options']['pre_page_title'] = 'Previous';
$field_values['field_options']['next_page_title'] = "Next";
$field_values['field_options']['page_break_type'] = "wizard";
$field_values['field_options']['page_break_type_possition'] = "top";
$field_values['field_options']['pagebreaktabsbar'] = 0;
$field_values['field_options']['pagebreakclickable'] = 0;
$field_values['field_options']['page_break_wizard_theme'] = 'style1';
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 5;
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'Prd1';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = '<b> Product 1 ( $10 ) </b>';
$field_values['field_options']['name'] = "Prd1";
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['form_id'] = $form_id;
$field_id_p1 = $arffield->create($field_values, true);
$field_order[$field_id_p1] = 6;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['number'];
$field_values['name'] = 'Quantity';
$field_values['field_options']['name'] = 'Quantity';
$field_values['type'] = 'number';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 0;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter Quantity', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['invaid'] = addslashes(esc_html__('Number is out of range', 'ARForms'));
$field_values['field_options']['max'] = '';
$field_values['field_options']['minlength'] = '';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['form_id'] = $form_id;
$field_id_q1 = $arffield->create($field_values, true);
$field_order[$field_id_q1] = 7;
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'Prd2';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = '<b> Product 2 ( $20 ) </b>';
$field_values['field_options']['name'] = "Prd2";
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['form_id'] = $form_id;
$field_id_p2 = $arffield->create($field_values, true);
$field_order[$field_id_p2] = 8;
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['number'];
$field_values['name'] = 'Quantity';
$field_values['field_options']['name'] = 'Quantity';
$field_values['type'] = 'number';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 0;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter Quantity', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['invaid'] = addslashes(esc_html__('Number is out of range', 'ARForms'));
$field_values['field_options']['max'] = '';
$field_values['field_options']['minlength'] = '';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['form_id'] = $form_id;
$field_id_q2 = $arffield->create($field_values, true);
$field_order[$field_id_q2] = 9;
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'Prd3';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = '<b> Product 3 ( $30 ) </b>';
$field_values['field_options']['name'] = "Prd3";
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['form_id'] = $form_id;
$field_id_p3 = $arffield->create($field_values, true);
$field_order[$field_id_p3] = 10;
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['number'];
$field_values['name'] = 'Quantity';
$field_values['field_options']['name'] = 'Quantity';
$field_values['type'] = 'number';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 0;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter Quantity', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['invaid'] = addslashes(esc_html__('Number is out of range', 'ARForms'));
$field_values['field_options']['max'] = '';
$field_values['field_options']['minlength'] = '';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['form_id'] = $form_id;
$field_id_q3 = $arffield->create($field_values, true);
$field_order[$field_id_q3] = 11;
unset($field_values);



$field_values = array();
$field_values['field_options'] = $field_data_obj['break'];
$field_values['type'] = 'break';
$field_values['name'] = '';
$field_values['field_options']['first_page_label'] = 'Step1';
$field_values['field_options']['second_page_label'] = 'Confirm Order';
$field_values['field_options']['pre_page_title'] = 'Previous';
$field_values['field_options']['next_page_title'] = "Place Order";
$field_values['field_options']['page_break_type'] = "wizard";
$field_values['field_options']['page_break_type_possition'] = "top";
$field_values['field_options']['pagebreaktabsbar'] = 0;
$field_values['field_options']['pagebreakclickable'] = 0;
$field_values['field_options']['page_break_wizard_theme'] = 'style1';

$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 12;
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['textarea'];
$field_values['name'] = addslashes(esc_html__('Delivery Address', 'ARForms'));
$field_values['field_options']['name'] = addslashes(esc_html__('Delivery Address', 'ARForms'));
$field_values['field_options']['required'] = 1;
$field_values['type'] = 'textarea';
$field_values['field_options']['blank'] = addslashes(esc_html__('This field cannot be blank.', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_1';
$field_values['field_options']['inner_class'] = 'arf_1col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 13;
unset($field_values);
unset($field_id);


$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'City';
$field_values['field_options']['name'] = 'City';
$field_values['type'] = 'text';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 0;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter City', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 14;
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['text'];
$field_values['name'] = 'State';
$field_values['field_options']['name'] = 'State';
$field_values['type'] = 'text';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 0;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter State', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 15;
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['number'];
$field_values['name'] = 'Postal Code';
$field_values['field_options']['name'] = 'Postal Code';
$field_values['type'] = 'number';
$field_values['field_options']['description'] = '';
$field_values['field_options']['required'] = 0;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please enter Postal Code', 'ARForms'));
$field_values['field_options']['minlength_message'] = addslashes(esc_html__('Invalid minimum characters length', 'ARForms'));
$field_values['field_options']['arf_regular_expression_msg'] = addslashes(esc_html__('Entered value is invalid', 'ARForms'));
$field_values['field_options']['invaid'] = addslashes(esc_html__('Number is out of range', 'ARForms'));
$field_values['field_options']['max'] = '6';
$field_values['field_options']['minlength'] = '5';
$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf21colclass';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 16;
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['select'];
$field_values['name'] = 'Country';
$field_values['type'] = 'select';
$field_values['field_options']['name'] = 'Country';
$field_values['field_options']['required'] = 0;
$field_values['field_options']['blank'] = addslashes(esc_html__('Please select your state', 'ARForms'));
$field_values['options'] = json_encode(array("","Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Antigua and Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Colombia","Comoros","Congo","Costa Rica","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Fiji","Finland","France","French Guiana","French Polynesia","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","North Korea","South Korea","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","Norway","Northern Mariana Islands","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico","Qatar","Romania","Russia","Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia and Montenegro","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","Spain","Sri Lanka","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Togo","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands, British","Virgin Islands, U.S.","Yemen","Zambia","Zimbabwe"));

$field_values['field_options']['classes'] = 'arf_2';
$field_values['field_options']['inner_class'] = 'arf_2col';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$field_order[$field_id] = 17;
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['section'];
$field_values['type'] = 'section';
$field_values['name'] = 'Billing Info';
$field_values['field_options']['description'] = '';
$field_values['field_options']['name'] = "Billing Information";
$field_values['field_options']['arf_section_bg_color'] = "#ffffff";
$field_values['field_options']['classes'] = "arf_1";
$field_values['field_options']['inner_class'] = "arf_1col";
$field_values['form_id'] = $form_id;
$section_field_id = $arffield->create($field_values, true);
$field_order[$section_field_id] = 18;
$inner_field_order[$section_field_id] = array();
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'P1';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = '<b> Product 1 </b>';
$field_values['field_options']['name'] = "P1";
$field_values['field_options']['classes'] = "arf_3";
$field_values['field_options']['inner_class'] = "arf31colclass";
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id.'|1';
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'HTML';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = "<div style=\"text-align:center\">10 x <arftotal>[Quantity:".$field_id_q1."]</arftotal></div>";
$field_values['field_options']['name'] = "HTML";
$field_values['field_options']['enable_total'] = '1';
$field_values['field_options']['classes'] = "arf_3";
$field_values['field_options']['inner_class'] = "arf_23col";
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id_det_1 = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id_det_1.'|2';
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'HTML';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = "<div style=\"text-align:right\"><arftotal>[Quantity:".$field_id_q1."]*10</arftotal></div>";
$field_values['field_options']['name'] = "HTML";
$field_values['field_options']['enable_total'] = '1';
$field_values['field_options']['classes'] = "arf_3";
$field_values['field_options']['inner_class'] = "arf_3col";
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id_sum_1 = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id_sum_1.'|3';
unset($field_values);


$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'P2';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = '<b> Product 2 </b>';
$field_values['field_options']['name'] = "P2";
$field_values['field_options']['classes'] = "arf_3";
$field_values['field_options']['inner_class'] = "arf31colclass";
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id.'|4';
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'HTML';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = "<div style=\"text-align:center\">20 x <arftotal>[Quantity:".$field_id_q2."]</arftotal></div>";
$field_values['field_options']['name'] = "HTML";
$field_values['field_options']['enable_total'] = '1';
$field_values['field_options']['classes'] = "arf_3";
$field_values['field_options']['inner_class'] = "arf_23col";
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id_det_2 = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id_det_2.'|5';
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'HTML';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = "<div style=\"text-align:right\"><arftotal>[Quantity:".$field_id_q2."]*20</arftotal></div>";
$field_values['field_options']['name'] = "HTML";
$field_values['field_options']['enable_total'] = '1';
$field_values['field_options']['classes'] = "arf_3";
$field_values['field_options']['inner_class'] = "arf_3col";
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id_sum_2 = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id_sum_2.'|6';
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'P3';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = '<b> Product 3 </b>';
$field_values['field_options']['name'] = "P3";
$field_values['field_options']['classes'] = "arf_3";
$field_values['field_options']['inner_class'] = "arf31colclass";
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id.'|7';
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'HTML';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = "<div style=\"text-align:center\">30 x <arftotal>[Quantity:".$field_id_q3."]</arftotal></div>";
$field_values['field_options']['name'] = "HTML";
$field_values['field_options']['enable_total'] = '1';
$field_values['field_options']['classes'] = "arf_3";
$field_values['field_options']['inner_class'] = "arf_23col";
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id_det_3 = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id_det_3.'|8';
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'HTML';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = "<div style=\"text-align:right\"><arftotal>[Quantity:".$field_id_q3."]*30</arftotal></div>";
$field_values['field_options']['name'] = "HTML";
$field_values['field_options']['enable_total'] = '1';
$field_values['field_options']['classes'] = "arf_3";
$field_values['field_options']['inner_class'] = "arf_3col";
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id_sum_3 = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id_sum_3.'|9';
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'HTML';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = "<hr/><div style=\"text-align:center;font-weight:bold\">GRAND TOTAL</div>";
$field_values['field_options']['name'] = "HTML";
$field_values['field_options']['classes'] = "arf_3";
$field_values['field_options']['inner_class'] = "arf_23col";
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id_sum_3 = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = 'arf31colclass|10';
$inner_field_order[$section_field_id][] = $field_id_sum_3.'|11';
unset($field_values);

$field_values = array();
$field_values['field_options'] = $field_data_obj['html'];
$field_values['name'] = 'HTML';
$field_values['type'] = 'html';
$field_values['field_options']['description'] = "<hr/><div style=\"text-align:right\"><arftotal>(([Quantity:".$field_id_q1."]*10)+([Quantity:".$field_id_q2."]*20)+([Quantity:".$field_id_q3."]*30))</arftotal></div>";
$field_values['field_options']['name'] = "HTML";
$field_values['field_options']['enable_total'] = '1';
$field_values['field_options']['classes'] = "arf_3";
$field_values['field_options']['inner_class'] = "arf_3col";
$field_values['field_options']['has_parent'] = true;
$field_values['field_options']['parent_field'] = $section_field_id;
$field_values['field_options']['parent_field_type'] = 'section';
$field_values['form_id'] = $form_id;
$field_id_total = $arffield->create($field_values, true);
$inner_field_order[$section_field_id][] = $field_id_total.'|12';

unset($field_values);

unset($field_id);

$field_options = $wpdb->get_results($wpdb->prepare("SELECT `options` FROM `" . $MdlDb->forms . "` WHERE `id` = %d", $form_id));

$form_opt = maybe_unserialize($field_options[0]->options);
$form_opt['arf_field_order'] = json_encode($field_order);
$updated_inner_field_order = json_encode( $inner_field_order );
$form_opt['arf_inner_field_order'] = $updated_inner_field_order;

$form_options = maybe_serialize($form_opt);

$wpdb->update($MdlDb->forms, array('options' => $form_options), array('id' => $form_id));

$wpdb->update($MdlDb->fields,array('enable_running_total' => $field_id_det_1.",".$field_id_sum_1.",".$field_id_total), array('id' => $field_id_q1));
$wpdb->update($MdlDb->fields,array('enable_running_total' => $field_id_det_2.",".$field_id_sum_2.",".$field_id_total), array('id' => $field_id_q2));
$wpdb->update($MdlDb->fields,array('enable_running_total' => $field_id_det_3.",".$field_id_sum_3.",".$field_id_total), array('id' => $field_id_q3));
unset($field_order);
unset($inner_field_order);