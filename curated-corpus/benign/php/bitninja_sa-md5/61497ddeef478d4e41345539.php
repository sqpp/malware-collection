<?php

/**
 * Fired when the plugin is uninstalled.
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_post_meta_by_key( '_awac_hide_widget' );
unregister_sidebar( 'add-widget-after-content' );
delete_option( 'all_post_types' );
delete_option( 'all_post_formats' );
delete_option( 'all_post_categories' );
delete_option('awac_priority');
delete_option( 'awac_styles' );
//delete_option( 'awac_misc' );
delete_option( 'awac_extensions' );