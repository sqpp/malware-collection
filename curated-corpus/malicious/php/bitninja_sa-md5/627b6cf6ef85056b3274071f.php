<?php
 global $wpdb; extract( $_REQUEST ); if( isset( $import_settings_submit ) && !( isset( $_GET['action'] ) && 'import' == $_GET['action'] && isset( $_GET['wpnonce'] ) && wp_verify_nonce( $_GET['wpnonce'], 'import_settings' ) ) ) { if ( isset( $_FILES['import_file']['size'] ) && '' != $_FILES['import_file']['size'] ) { $size = round( $_FILES['import_file']['size'] / 1024 ); $target_path = $this->get_upload_dir( 'wpclient/' ); $orig_name = $_FILES['import_file']['name']; $new_name = basename( 'import_settings.xml' ); $target_path = $target_path . $new_name; if( file_exists( $target_path ) ) { unlink( $target_path ); } if( move_uploaded_file( $_FILES['import_file']['tmp_name'], $target_path ) ) { $nonce = wp_create_nonce( 'import_settings' ); do_action( 'wp_client_redirect', add_query_arg( array( 'page' => 'wpclients_settings', 'tab' => 'import_export', 'action' => 'import', 'wpnonce'=>$nonce ), 'admin.php' ) ); exit; } } } elseif( isset( $import_settings ) && isset( $import_settings_submit ) && isset( $_POST['key'] ) && 'import' == $_POST['key'] ) { $target_path = $this->get_upload_dir( 'wpclient/' ); $new_name = basename( 'import_settings.xml' ); $target_path = $target_path . $new_name; if( file_exists( $target_path ) ) { $xml_content = file_get_contents( $target_path ); } if( new SimpleXMLElement( $xml_content ) ) { $xml = json_decode( json_encode( ( array ) simplexml_load_string( $xml_content ) ), 1 ); $import_settings = array_keys( $import_settings ); foreach( $xml as $key=>$value ) { if( in_array( $key, $import_settings ) ) { if( 'custom_redirects' == $key ) { $wpdb->query("DELETE FROM {$wpdb->prefix}wpc_client_login_redirects"); foreach( $value as $custom_redirect ) { if( is_array( $custom_redirect ) && 0 < count( $custom_redirect ) ) { foreach( $custom_redirect as $subkey=>$subvalue ) { if( is_array( $subvalue ) && 0 == count( $subvalue ) ) { $custom_redirect[$subkey] = ''; } } } $wpdb->insert( "{$wpdb->prefix}wpc_client_login_redirects", $custom_redirect ); } } else { if( isset( $value ) && !empty( $value ) ) { if( is_array( $value ) && 0 < count( $value ) ) { foreach( $value as $subkey=>$subvalue ) { if( is_array( $subvalue ) && 0 == count( $subvalue ) ) { $value[$subkey] = ''; } elseif( is_array( $subvalue ) && 0 < count( $subvalue ) ) { foreach( $subvalue as $subsubkey=>$subsubvalue ) { if( is_array( $subsubvalue ) && 0 == count( $subsubvalue ) ) { $value[$subkey][$subsubkey] = ''; } } } } if( 'gateways' == $key && isset( $value['allowed'] ) && is_string( $value['allowed'] ) ) { $value['allowed'] = explode( '#|#', $value['allowed'] ); } if( 'paid_registration' == $key && isset( $value['gateways'] ) && is_string( $value['gateways'] ) ) { $value['gateways'] = explode( '#|#', $value['gateways'] ); } } update_option( 'wpc_' . $key, $value ); } } } } if( file_exists( $target_path ) ) { unlink( $target_path ); } do_action( 'wp_client_redirect', add_query_arg( array( 'page' => 'wpclients_settings', 'tab' => 'import_export', 'msg'=>'t' ), 'admin.php' ) ); exit; } else { do_action( 'wp_client_redirect', add_query_arg( array( 'page' => 'wpclients_settings', 'tab' => 'import_export', 'msg'=>'f' ), 'admin.php' ) ); exit; } } if( isset( $_GET['action'] ) && 'import_cancel' == $_GET['action'] && isset( $_GET['wpnonce'] ) && wp_verify_nonce( $_GET['wpnonce'], 'import_cancel' ) ) { $target_path = $this->get_upload_dir( 'wpclient/' ); $new_name = basename( 'import_settings.xml' ); $target_path = $target_path . $new_name; if( file_exists( $target_path ) ) { unlink( $target_path ); } do_action( 'wp_client_redirect', add_query_arg( array( 'page' => 'wpclients_settings', 'tab' => 'import_export' ), 'admin.php' ) ); exit; } ?>

<style type="text/css">
    .wrap input[type=text] {
        width:400px;
    }

    .wrap input[type=password] {
        width:400px;
    }
</style>

<script type="text/javascript">
    jQuery( document ).ready( function() {
        var admin_url = '<?php echo admin_url();?>';

        jQuery('#export_select_all').click(function(){
            if(jQuery('#export_select_all').attr('checked') == 'checked') {
                jQuery('input[name*="export_settings"]').attr('checked','checked');
                jQuery('#export_deselect_all').removeAttr('checked');
            }
        });

        jQuery('#import_select_all').click(function(){
            if(jQuery('#import_select_all').attr('checked') == 'checked') {
                jQuery('input[name*="import_settings"]').attr('checked','checked');
                jQuery('#import_deselect_all').removeAttr('checked');
            }
        });

        jQuery('#export_deselect_all').click(function(){
            if(jQuery('#export_deselect_all').attr('checked') == 'checked') {
                jQuery('input[name*="export_settings"]').removeAttr('checked');
                jQuery('#export_select_all').removeAttr('checked');
            }
        });

        jQuery('#import_deselect_all').click(function(){
            if(jQuery('#import_deselect_all').attr('checked') == 'checked') {
                jQuery('input[name*="import_settings"]').removeAttr('checked');
                jQuery('#import_select_all').removeAttr('checked');
            }
        });

        jQuery('input[name*="export_settings"]').click(function(){

            if( jQuery(this).attr('checked') != 'checked' && jQuery('#export_select_all').attr('checked') == 'checked' ) {
                jQuery('#export_select_all').removeAttr('checked');
            } else if( jQuery('input[name*="export_settings"]:checked').length == jQuery('input[name*="export_settings"]').length - 1 && jQuery('#export_select_all').attr('checked') != 'checked' ) {
                jQuery('#export_select_all').attr('checked','checked');
            }
            if( jQuery('input[name*="export_settings"]:checked').length > 0 && jQuery('#export_deselect_all').attr('checked') == 'checked' ) {
                jQuery('#export_deselect_all').removeAttr('checked');
            } else if( jQuery('input[name*="export_settings"]:checked').length == 0 && jQuery('#export_deselect_all').attr('checked') != 'checked' ) {
                jQuery('#export_deselect_all').attr('checked','checked');
            }
        });

        jQuery('input[name*="import_settings"]').click(function(){
            if( jQuery(this).attr('checked') != 'checked' && jQuery('#import_select_all').attr('checked') == 'checked' ) {
                jQuery('#import_select_all').removeAttr('checked');
            } else if( jQuery('input[name*="import_settings"]:checked').length == jQuery('input[name*="import_settings"]').length - 2 && jQuery('#import_select_all').attr('checked') != 'checked' ) {
                jQuery('#import_select_all').attr('checked','checked');
            }
            if( jQuery('input[name*="import_settings"]:checked').length > 0 && jQuery('#import_deselect_all').attr('checked') == 'checked' ) {
                jQuery('#import_deselect_all').removeAttr('checked');
            } else if( jQuery('input[name*="import_settings"]:checked').length == 0 && jQuery('#import_deselect_all').attr('checked') != 'checked' ) {
                jQuery('#import_deselect_all').attr('checked','checked');
            }
        });

        jQuery('input[name="export_settings_submit"]').click(function(){
            if( jQuery('input[name*="export_settings"]:checked').length > 0 ) {
                return true;
            } else {
                jQuery('#wrong_export').css('display', 'block').delay(2000).slideUp('slow');
                return false;
            }
        });

        jQuery('input[name="import_settings_submit"]').click(function(){
            if( jQuery('input[name*="import_settings"]:checked').length > 0 ) {
                return true;
            } else if ( jQuery('input[name*="import_settings"]').length == 1 ) {
                return true;
            } else {
                jQuery('#wrong_import').css('display', 'block').delay(2000).slideUp('slow');
                return false;
            }
        });

        jQuery('input[name="import_settings_cancel"]').click(function(){
            window.location = '<?php echo add_query_arg( array( 'action'=>'import_cancel', 'wpnonce'=>wp_create_nonce( 'import_cancel' ) ), admin_url( 'admin.php?page=wpclients_settings&tab=import_export' ) ); ?>';
        });
    });
</script>

<?php
 $tabs = array_flip( $tabs ); if( in_array( 'about', $tabs ) ) { unset( $tabs[array_search( 'about', $tabs )] ); } if( in_array( 'import_export', $tabs ) ) { unset( $tabs[array_search( 'import_export', $tabs )] ); } $tabs = array_flip( $tabs ); ?>

<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="key" value="import" />
    <div class="postbox">
        <h3 class='hndle'><span><?php _e( 'Import Settings', WPC_CLIENT_TEXT_DOMAIN ) ?></span></h3>
        <div class="inside">
            <div id="wrong_import" style="display: none;"><p><?php _e( 'Please select checkboxes and try again!', WPC_CLIENT_TEXT_DOMAIN ); ?></p></div>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="import_file"><?php _e( 'XML settings-file', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label>
                    </th>
                    <td>
                        <input id="import_file" <?php echo ( isset( $_GET['action'] ) && 'import' == $_GET['action'] && isset( $_GET['wpnonce'] ) && wp_verify_nonce( $_GET['wpnonce'], 'import_settings' ) ) ? ' readonly="readonly" disabled="disabled" ' : ''; ?> name="import_file" type="file" accept=".xml" />
                    </td>
                </tr>
            </table>
            <?php if( isset( $_GET['action'] ) && 'import' == $_GET['action'] && isset( $_GET['wpnonce'] ) && wp_verify_nonce( $_GET['wpnonce'], 'import_settings' ) ) { $target_path = $this->get_upload_dir( 'wpclient/' ); $new_name = basename( 'import_settings.xml' ); $target_path = $target_path . $new_name; if( file_exists( $target_path ) ) { $xml_content = file_get_contents( $target_path ); } if( new SimpleXMLElement( $xml_content ) ) { $xml = json_decode( json_encode( ( array ) simplexml_load_string( $xml_content ) ), 1 ); ?>
                        <table class="form-table wpc-import-export-table">
                            <tr valign="top">
                                <td><label><?php _e( 'Select All', WPC_CLIENT_TEXT_DOMAIN ) ?>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="import_select_all" checked="checked" /></label>
                            </td>
                                <td><label><?php _e( 'Deselect All', WPC_CLIENT_TEXT_DOMAIN ) ?>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="import_deselect_all" /></label>
                            </td>
                            </tr>
                        </table>

                        <table class="form-table wpc-import-export-table-paramethers">
                        <?php
 $count = 0; $import_tabs = $tabs; $import_tabs['custom_redirects'] = __( 'Login/Logout Redirects Users', WPC_CLIENT_TEXT_DOMAIN ); foreach( $import_tabs as $tab=>$title ) { if( 0 === $count%2 ) { ?>
                                <tr valign="top">
                            <?php } ?>
                                    <td>
                                        <label for="import_settings_<?php echo $tab ?>"><?php echo $title ?>:</label>
                                    </td>
                                    <td width="15%">
                                        <?php if( isset( $xml[$tab] ) && !empty( $xml[$tab] ) ) { ?>
                                            <input type="checkbox" id="import_settings_<?php echo $tab ?>" name="import_settings[<?php echo $tab ?>]" checked="checked" />
                                        <?php } else { ?>
                                            <input type="checkbox" id="import_settings_<?php echo $tab ?>" readonly="readonly" disabled="disabled" />
                                        <?php } ?>
                                    </td>
                            <?php if( 1 === $count%2 ) { ?>
                                </tr>
                            <?php } $count++; } ?>
                        </table>
                        <input type='submit' name='import_settings_submit' class='button-primary' value='<?php _e( 'Import', WPC_CLIENT_TEXT_DOMAIN ) ?>' />
                        <input type='button' name='import_settings_cancel' class='button' value='<?php _e( 'Cancel', WPC_CLIENT_TEXT_DOMAIN ) ?>' />
                        <?php
 } else { ?>
                        <p>Error!</p>
                        <input type='button' name='import_settings_cancel' class='button' value='<?php _e( 'Cancel', WPC_CLIENT_TEXT_DOMAIN ) ?>' />
                        <?php
 } } else { ?>
                <input type='submit' name='import_settings_submit' class='button-primary' value='<?php _e( 'Import', WPC_CLIENT_TEXT_DOMAIN ) ?>' />
            <?php } ?>
        </div>
    </div>
</form>

<form action="" method="post">
    <input type="hidden" name="key" value="export" />
    <div class="postbox">
        <h3 class='hndle'><span><?php _e( 'Export Settings', WPC_CLIENT_TEXT_DOMAIN ) ?></span></h3>
        <div class="inside">

            <div id="wrong_export" style="display: none;">
                <p><?php _e( 'Please select checkboxes and try again!', WPC_CLIENT_TEXT_DOMAIN ); ?></p>
            </div>

            <table class="form-table wpc-import-export-table">
                <tr valign="top">
                    <td><label><?php _e( 'Select All', WPC_CLIENT_TEXT_DOMAIN ) ?>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="export_select_all" /></label>
                </td>
                    <td><label><?php _e( 'Deselect All', WPC_CLIENT_TEXT_DOMAIN ) ?>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="export_deselect_all" checked="checked" /></label>
                </td>
                </tr>
            </table>
            <table class="form-table wpc-import-export-table-paramethers">
            <?php
 $count = 0; $tabs['custom_redirects'] = __( 'Login/Logout Redirects Users', WPC_CLIENT_TEXT_DOMAIN ); if( is_array( $tabs ) && count( $tabs ) ) { foreach( $tabs as $tab => $title ) { if( 0 === $count%2 ) { ?>
                            <tr valign="top">
                        <?php } ?>
                                <td>
                                    <label for="export_settings_<?php echo $tab ?>"><?php echo $title ?>:</label>
                                </td>
                                <td width="15%">
                                    <input type="checkbox" id="export_settings_<?php echo $tab ?>" name="export_settings[<?php echo $tab ?>]"/>
                                </td>
                        <?php if( 1 === $count%2 ) { ?>
                            </tr>
                        <?php } $count++; } } unset( $count ); ?>
            </table>
            <input type='submit' name='export_settings_submit' class='button-primary' value='<?php _e( 'Export', WPC_CLIENT_TEXT_DOMAIN ) ?>' />
        </div>
    </div>
</form>