<?php
/**
 * Your Inspiration Themes
 * 
 * @package WordPress
 * @subpackage Your Inspiration Themes
 * @author Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
 
/*
Template Name: Shop
*/

// get the style
global $woocommerce, $woocommerce_loop, $blog_id;
$cookie_shop_view = 'yit_' . get_template() . ( is_multisite() ? '_' . $blog_id : '' ) . '_shop_view';
$woocommerce_loop['view'] = isset( $_COOKIE[ $cookie_shop_view ] ) ? $_COOKIE[ $cookie_shop_view ] : yit_get_option( 'shop-view', 'grid' );

if ( function_exists( 'yit_shop_page_meta' ) ) add_action( 'yit_loop_page', 'yit_shop_page_meta', 1 );
yit_add_body_class('archive');
yit_add_body_class('woocommerce');
yit_add_body_class('woocommerce-page');

add_action( 'wp_footer', create_function( '', "echo '<script>
/* <![CDATA[ */
var yit_shop_view_cookie = \'$cookie_shop_view;\';
/* ]]> */
</script>';
" ) );

get_template_part( 'page' );