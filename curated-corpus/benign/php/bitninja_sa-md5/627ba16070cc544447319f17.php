<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (WP Edition)                                          |
 |                                                                     |
 | (c) NinTechNet - https://nintechnet.com/                            |
 +---------------------------------------------------------------------+
 | This program is free software: you can redistribute it and/or       |
 | modify it under the terms of the GNU General Public License as      |
 | published by the Free Software Foundation, either version 3 of      |
 | the License, or (at your option) any later version.                 |
 |                                                                     |
 | This program is distributed in the hope that it will be useful,     |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of      |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       |
 | GNU General Public License for more details.                        |
 +---------------------------------------------------------------------+ i18n+ / sa / 2
*/

if (! defined( 'NFW_ENGINE_VERSION' ) ) { die( 'Forbidden' ); }

nf_not_allowed( 'block', __LINE__ );

add_thickbox();

if ( defined( 'NFW_WPWAF' ) ) {
	nfw_upgrade_fullwaf();

} else {
	nfw_configure_fullwaf();
}

if (! empty( $errlog_content ) ) {
	nfw_show_errorlog( $errlog_content );
}

return;

// ---------------------------------------------------------------------

function nfw_show_errorlog( $errlog_content ) {

	?>
	<div id="nfw-errorlog-thickbox-content" style="display:none;">
		<h2><?php _e('NinjaFirewall error log', 'ninjafirewall') ?></h2>
		<div id="nfwaf-step1" style="height:80%">
			<p style="height:100%">
				<textarea class="large-text code" style="color:green;height:100%" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" wrap="off"><?php
				foreach( $errlog_content as $line ) {
					echo htmlspecialchars( $line );
				}
				?></textarea>
			</p>
			<form method="post" onSubmit="return nfwjs_del_errorlog()">
				<input type="button" class="button-primary" name="close_log" value="<?php _e('Close Log', 'ninjafirewall') ?>" onclick="tb_remove()" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" class="button-secondary" name="delete-error-log" value="<?php _e('Delete Log', 'ninjafirewall') ?>" />
				<?php wp_nonce_field('delete_error_log', 'nfwnonce_errorlog', 0); ?>
			</form>
		</div>
	</div>
	<?php
}
// ---------------------------------------------------------------------

function nfw_upgrade_fullwaf() {

	if (! function_exists( 'get_home_path' ) ) {
		include_once ABSPATH .'wp-admin/includes/file.php';
 	}
 	$NFW_ABSPATH = get_home_path();

?>
<div id="nfw-activate-thickbox-content" style="display:none">

	<h2><?php _e('Activate Full WAF mode', 'ninjafirewall') ?></h2>

	<div id="nfwaf-step1">
		<p>
		<?php
			esc_html_e('In Full WAF mode, all scripts located inside the blog installation directories and sub-directories are protected by NinjaFirewall. It gives you the highest possible level of protection: security without compromise.', 'ninjafirewall');
			echo '&nbsp;';
			printf( esc_html__('It works on most websites right out of the box, or may require %ssome very little tweaks%s. But in a few cases, mostly because of some shared hosting plans restrictions, it may simply not work at all.','ninjafirewall'), '<a href="https://blog.nintechnet.com/troubleshoot-ninjafirewall-installation-problems/" title="Troubleshoot NinjaFirewall installation problems." target="_blank">', '</a>');
			echo '&nbsp;';
			esc_html_e('If this happened to you, don\'t worry: you could still run it in WordPress WAF mode. Despite being less powerful than the Full WAF mode, it offers a level of protection and performance much higher than other security plugins.', 'ninjafirewall');
		?>
		</p>
		<?php
		// Fetch the HTTP server and PHP SAPI
		$s1 = ''; $s2 = ''; $s3 = ''; $s4 = ''; $s5 = ''; $s6 = ''; $s7 = ''; $s8 = ''; $type = '';
		$recommended = ' ' . __('(recommended)', 'ninjafirewall');
		$display_none = ' style="display:none"';
		$tr_ini_userini = '';
		$tr_ini_phpini = $display_none;
		$tr_htaccess_modphp = $display_none;
		$tr_htaccess_apachelsapi = $display_none;
		$tr_htaccess_litespeed = $display_none;
		$tr_htaccess_openlitespeed = $display_none;
		$tr_htaccess_suphp = $display_none;
		$diy_div_style = '';
		$div_nfwaf_step2 = $display_none;

		// Mod_php
		if ( preg_match('/apache/i', PHP_SAPI) ) {
			$http_server = 'apachemod';
			$s1 = $recommended ;
			$type = 'htaccess';
			$tr_htaccess_modphp = '';
			$tr_ini_userini = $display_none;

		// Litespeed / Openlitespeed / Apache + LSAPI
		} elseif ( preg_match( '/litespeed/i', PHP_SAPI ) ) {

			if ( isset( $_SERVER['LSWS_EDITION'] ) && stripos( $_SERVER['LSWS_EDITION'], 'Openlitespeed') === 0 ) {
				$http_server = 'openlitespeed';
				$s6 = $recommended ;
				$type = 'htaccess';
				$tr_htaccess_openlitespeed = '';
				$tr_ini_userini = $display_none;
				$diy_div_style = $display_none;
				$div_nfwaf_step2 = '';

			} elseif ( isset( $_SERVER['SERVER_SOFTWARE'] ) && $_SERVER['SERVER_SOFTWARE'] == 'LiteSpeed' ) {
				$http_server = 'litespeed';
				$s5 = $recommended ;
				$type = 'htaccess';
				$tr_htaccess_litespeed = '';
				$tr_ini_userini = $display_none;

			} else {
				$http_server = 'apachelsapi';
				$s8 = $recommended ;
				$type = 'htaccess';
				$tr_htaccess_apachelsapi = '';
				$tr_ini_userini = $display_none;
			}

		} else {
			$type = 'ini';
			// Apache FCGI
			if ( preg_match('/apache/i', $_SERVER['SERVER_SOFTWARE']) ) {
				$http_server = 'apachecgi';
				$s2 = $recommended ;

			// NGINX
			} elseif ( preg_match('/nginx/i', $_SERVER['SERVER_SOFTWARE']) ) {
				$http_server = 'nginx';
				$s4 = $recommended;

			// Other webserver with FCGI
			} else {
				$http_server = 'othercgi';
				$s7 = $recommended ;
			}
		}
		?>
		<table class="form-table nfw-table">
			<tr>
				<th scope="row" class="row-med"><?php _e('Select your HTTP server and your PHP server API', 'ninjafirewall') ?> (<code>SAPI</code>)</th>
				<td>
					<?php /* HTTP value must be changed in JS and main script as well */ ?>
					<select class="input" name="http_server" onchange="nfwjs_httpserver(this.value)">
						<option value="1"<?php selected($http_server, 'apachemod') ?>>Apache + PHP<?php echo PHP_MAJOR_VERSION ?> module<?php echo $s1 ?></option>
						<option value="2"<?php selected($http_server, 'apachecgi') ?>>Apache + CGI/FastCGI or PHP-FPM<?php echo $s2 ?></option>
						<option value="3"<?php selected($http_server, 'apachesuphp') ?>>Apache + suPHP</option>
						<option value="8"<?php selected($http_server, 'apachelsapi') ?>>Apache + LSAPI/Cloudlinux<?php echo $s8 ?></option>
						<option value="4"<?php selected($http_server, 'nginx') ?>>Nginx + CGI/FastCGI or PHP-FPM<?php echo $s4 ?></option>
						<option value="5"<?php selected($http_server, 'litespeed') ?>>Litespeed<?php echo $s5 ?></option>
						<option value="6"<?php selected($http_server, 'openlitespeed') ?>>Openlitespeed<?php echo $s6 ?></option>
						<option value="7"<?php selected($http_server, 'othercgi') ?>><?php _e('Other webserver + CGI/FastCGI or PHP-FPM', 'ninjafirewall') ?><?php echo $s6 ?></option>
					</select>
					<p class="description"><a class="links" href="<?php echo wp_nonce_url( '?page=NinjaFirewall&nfw_act=99', 'show_phpinfo', 'nfwnonce' ); ?>" target="_blank"><?php _e('View PHPINFO', 'ninjafirewall') ?></a></p>
				</td>
			</tr>
			<?php
			$f1 = ''; $f2 = '';
			if ( file_exists( $NFW_ABSPATH .'.user.ini' ) ) {
				$ini_type = 1;
				$f1 = $recommended;
				$tr_ini_phpini = $display_none;
				$tr_ini_userini = '';
			} elseif ( file_exists( $NFW_ABSPATH .'php.ini' ) ) {
				$ini_type = 2;
				$f2 = $recommended;
				$tr_ini_phpini = '';
				$tr_ini_userini = $display_none;
			} else {
				// fall back to .user.ini
				$ini_type = 1;
				$f1 = $recommended;
				$tr_ini_phpini = $display_none;
				$tr_ini_userini = '';
			}
			// Hide all ini input if no ini required
			if ( $type == 'ini' ) {
				$ini_style = '';
			} else {
				$ini_style = ' style="display:none"';
				$tr_ini_phpini = $display_none;
				$tr_ini_userini = $display_none;
			}
			?>
			<tr id="tr-select-ini"<?php echo $ini_style ?>>
				<th scope="row" class="row-med"><?php _e('Select the PHP initialization file supported by your server', 'ninjafirewall') ?></th>
				<td>
					<p><label><input type="radio" id="ini-type-user" onClick="nfwjs_radio_ini(1)" name="ini_type" value="1"<?php checked( $ini_type, 1 ) ?>><code>.user.ini</code><?php echo $f1 ?></label></p>
					<p><label><input type="radio" id="ini-type-php" onClick="nfwjs_radio_ini(2)" name="ini_type" value="2"<?php checked( $ini_type, 2 ) ?>><code>php.ini</code><?php echo $f2 ?></label></p>
				</td>
			</tr>
		</table>
	</div>

	<table class="form-table nfw-table">
		<tr>
			<th scope="row" class="row-med"><?php esc_html_e('Folders protected by NinjaFirewall', 'ninjafirewall') ?></th>
			<td>
				<?php esc_html_e('WordPress root directory:', 'ninjafirewall') ?> <code><?php echo htmlentities( ABSPATH ) ?></code>
				<?php
				$list = nfw_display_directories();
				?>
				<br />
				<p><?php esc_html_e('The following folders will be protected by NinjaFirewall. If you want to exclude some of them, uncheck them in the list below:', 'ninjafirewall') ?></p>
				<div class="nfw-sub">
					<table class="form-table" style="margin-top:0">
						<?php echo $list ?>
					</table>
				</div>
				<span class="description" style="color:#646970;"><?php esc_html_e('After setting up the Full WAF mode, you could come back to this page to re-configure it whenever you want.', 'ninjafirewall') ?></span>
			</td>
		</tr>
	</table>

	<br />

	<div class="font-15px" id="diy-div"<?php echo $diy_div_style ?>>
		<p><label><input onClick="nfwjs_diy_chg(this.value)" id="diynfw" type="radio" name="diy-choice" value="nfw" checked /> <?php _e('Let NinjaFirewall make the necessary changes (recommended).', 'ninjafirewall') ?></label>
		<br />
		<label><input onClick="nfwjs_diy_chg(this.value)" type="radio" name="diy-choice" value="usr" /> <?php _e('I want to make the changes myself.', 'ninjafirewall') ?></label></p>
		<div id="diy-msg" style="display:none;background:#FFFFFF;border-left:4px solid #fff;-webkit-box-shadow:0 1px 1px 0 rgba(0,0,0,.1);box-shadow:0 1px 1px 0 rgba(0,0,0,.1);margin:5px 0 15px;padding:1px 12px;border-left-color:orange;">
			<p><?php _e('Please make the changes below, then click on the "Finish Installation" button.', 'ninjafirewall') ?></p>
		</div>
	</div>
	<?php
	require_once __DIR__ .'/install.php';
	nfw_get_constants();

	$file_missing = __('The %s file must be created, and the following lines of code added to it:', 'ninjafirewall');
	$file_exist = __('The following lines of code must be added to your existing %s file:', 'ninjafirewall');
	?>

	<div id="nfwaf-step2"<?php echo $div_nfwaf_step2 ?>>

		<table class="form-table">
			<tr id="tr-ini-userini"<?php echo $tr_ini_userini ?>>
				<td>
					<?php
					if ( file_exists( $NFW_ABSPATH .'.user.ini' ) ) {
						$text = sprintf( $file_exist, '<code>'. htmlspecialchars( $NFW_ABSPATH ) .'<b>.user.ini</b>' .'</code>');
					} else {
						$text = sprintf( $file_missing, '<code>'. htmlspecialchars( $NFW_ABSPATH ) .'<b>.user.ini</b>' .'</code>');
					}
					echo $text;
					?>
					<br /><textarea name="txtlog" class="large-text code" rows="6" style="color:green;font-size:13px" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" wrap="off"><?php echo NFW_PHPINI_BEGIN ."\n" . NFW_PHPINI_DATA ."\n". NFW_PHPINI_END ."\n"; ?></textarea>
				</td>
			</tr>
			<tr id="tr-ini-phpini"<?php echo $tr_ini_phpini ?>>
				<td>
					<?php
					if ( file_exists( $NFW_ABSPATH .'php.ini' ) ) {
						$text = sprintf( $file_exist, '<code>'. htmlspecialchars( $NFW_ABSPATH ) .'<b>php.ini</b>' .'</code>');
					} else {
						$text = sprintf( $file_missing, '<code>'. htmlspecialchars( $NFW_ABSPATH ) .'<b>php.ini</b>' .'</code>');
					}
					echo $text;
					?>
					<br /><textarea name="txtlog" class="large-text code" rows="6" style="color:green;font-size:13px" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" wrap="off"><?php echo NFW_PHPINI_BEGIN ."\n" . NFW_PHPINI_DATA ."\n". NFW_PHPINI_END ."\n"; ?></textarea>
				</td>
			</tr>


			<?php
			if ( file_exists( $NFW_ABSPATH .'.htaccess' ) ) {
				$text = sprintf( $file_exist, '<code>'. htmlspecialchars( $NFW_ABSPATH ) .'<b>.htaccess</b>' .'</code>');
			} else {
				$text = sprintf( $file_missing, '<code>'. htmlspecialchars( $NFW_ABSPATH ) .'<b>.htaccess</b>' .'</code>');
			}
			?>
			<tr id="tr-htaccess-apachelsapi"<?php echo $tr_htaccess_apachelsapi ?>>
				<td>
					<?php
					echo $text;
					?>
					<br /><textarea name="txtlog" class="large-text code" rows="6" style="color:green;font-size:13px" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" wrap="off"><?php echo NFW_HTACCESS_BEGIN ."\n" . NFW_APACHELSAPI_DATA ."\n". NFW_HTACCESS_END ."\n"; ?></textarea>
				</td>
			</tr>
			<tr id="tr-htaccess-modphp"<?php echo $tr_htaccess_modphp ?>>
				<td>
					<?php
					echo $text;
					?>
					<br /><textarea name="txtlog" class="large-text code" rows="6" style="color:green;font-size:13px" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" wrap="off"><?php echo NFW_HTACCESS_BEGIN ."\n" . NFW_HTACCESS_DATA ."\n". NFW_HTACCESS_END ."\n"; ?></textarea>
				</td>
			</tr>
			<tr id="tr-htaccess-litespeed"<?php echo $tr_htaccess_litespeed ?>>
				<td>
					<?php
					echo $text;
					?>
					<br /><textarea name="txtlog" class="large-text code" rows="6" style="color:green;font-size:13px" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" wrap="off"><?php echo NFW_HTACCESS_BEGIN ."\n" . NFW_LITESPEED_DATA ."\n". NFW_HTACCESS_END ."\n"; ?></textarea>
				</td>
			</tr>
			<tr id="tr-htaccess-openlitespeed"<?php echo $tr_htaccess_openlitespeed ?>>
				<td>
					<?php
					esc_html_e('Log in to your Openlitespeed admin dashboard, click on "Virtual Host", select your domain, add the following instructions to the "php.ini Override" section in the "General" tab, and restart Openlitespeed:', 'ninjafirewall' );
					?>
					<br /><textarea name="txtlog" class="large-text code" rows="4" style="color:green;font-size:13px" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" wrap="off"><?php echo NFW_OPENLITESPEED_DATA ."\n"; ?></textarea>
					<br />
					<br />
					<div style="background:#FFFFFF;border-left:4px solid #fff;-webkit-box-shadow:0 1px 1px 0 rgba(0,0,0,.1);box-shadow:0 1px 1px 0 rgba(0,0,0,.1);margin:5px 0 15px;padding:1px 12px;border-left-color:orange;">
						<br>
						<?php _e('Important: if one day you wanted to uninstall NinjaFirewall, do not forget to remove these instructions from your Openlitespeed admin dashboard <strong>before</strong> uninstalling NinjaFirewall because this installer could not do it for you.', 'ninjafirewall') ?>
						<br>&nbsp;
					</div>
				</td>
			</tr>
			<tr id="tr-htaccess-suphp"<?php echo $tr_htaccess_suphp ?>>
				<td>
					<?php
					echo $text;
					?>
					<br /><textarea name="txtlog" class="large-text code" rows="6" style="color:green;font-size:13px" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" wrap="off"><?php echo NFW_HTACCESS_BEGIN ."\n" . NFW_SUPHP_DATA ."\n". NFW_HTACCESS_END ."\n"; ?></textarea>
				</td>
			</tr>
		</table>
	</div>
	<div>
		<p id="enable-sandbox"><label><input type="checkbox" checked="checked" name="enable_sandbox" /> <?php _e('Enable the sandbox.', 'ninjafirewall'); ?></label><br /><span class="description" style="color: #646970;"><?php _e('If there were a problem during the installation, NinjaFirewall would undo those changes automatically for you.', 'ninjafirewall') ?></span></p>
		<input id="btn-waf-next" type="button" class="button-primary" name="step" value="<?php _e('Finish Installation', 'ninjafirewall') ?> &#187;" onclick="nfwjs_fullwafsubmit()" />
		<?php wp_nonce_field('events_save', 'nfwnonce_fullwaf', 0); ?>
	</div>
	<br />
	<br />
</div>

<?php
}

// ---------------------------------------------------------------------
// Configure Full WAF mode or downgrade to WP WAF mode.

function nfw_configure_fullwaf() {

?>
<div id="nfw-configure-thickbox-content" style="display:none">

	<h2><?php esc_html_e('Configuration', 'ninjafirewall') ?></h2>

	<table class="form-table nfw-table">
		<tr>
			<th scope="row" class="row-med"><?php esc_html_e('Full WAF mode', 'ninjafirewall') ?></th>
			<td>
				<?php esc_html_e('WordPress root directory:', 'ninjafirewall') ?> <code><?php echo htmlentities( ABSPATH ) ?></code>
				<?php
				$list = nfw_display_directories();
				?>
				<br />
				<p><?php esc_html_e('The following folders will be protected by NinjaFirewall. If you want to exclude some of them, uncheck them in the list below:', 'ninjafirewall') ?></p>
				<div class="nfw-sub">
					<table class="form-table" style="margin-top:0">
						<?php echo $list ?>
					</table>
				</div>
				<br />
				<input id="btn-waf-next" type="button" class="button-secondary" name="fullwaf-configure" value="<?php esc_attr_e('Save Changes', 'ninjafirewall') ?>" onclick="nfwjs_fullwafconfig(1)" />
			</td>
		</tr>
	</table>

	<br />

	<?php wp_nonce_field('events_save', 'nfwnonce_fullwaf', 0); ?>

	<table class="form-table nfw-table">
		<tr>
			<th scope="row" class="row-med"><?php esc_html_e('WordPress WAF mode', 'ninjafirewall') ?></th>
			<td>
				<?php
				// Look for OpenLitespeed:
				if ( preg_match( '/litespeed/i', PHP_SAPI ) && isset( $_SERVER['LSWS_EDITION'] ) &&
					stripos( $_SERVER['LSWS_EDITION'], 'Openlitespeed') === 0 ) {

					esc_html_e('If you want to downgrade to WordPress WAF mode, log in to your Openlitespeed admin dashboard, click on "Virtual Host", select your domain and remove the "auto_prepend_file" directive from the "php.ini Override" section in the "General" tab, and restart Openlitespeed.', 'ninjafirewall' ); ?>
					<p><input type="button" class="button-secondary" value="<?php esc_attr_e('Downgrade to WordPress WAF mode', 'ninjafirewall') ?>" disabled /></p>
					<br />
				<?php
				} else {
					esc_html_e('If you want to downgrade to WordPress WAF mode, click the button below.', 'ninjafirewall') ?>
					<br />
					<br />
					<input id="btn-waf-next" type="button" class="button-secondary" name="fullwaf-downgrade" value="<?php esc_attr_e('Downgrade to WordPress WAF mode', 'ninjafirewall') ?>" onclick="nfwjs_fullwafconfig(2)" />
					<br />
					<span class="description" style="color: #646970;"><?php esc_html_e('You may have to wait five minutes for the changes to take effect.', 'ninjafirewall') ?></span>
				<?php
				}
				?>
			</td>
		</tr>
	</table>
	<p><input type="button" class="button-secondary" value="<?php esc_attr_e('Cancel and Close', 'ninjafirewall') ?>" onclick="tb_remove();" /></p>
</div>

<?php
}

// ---------------------------------------------------------------------
// Display directories browser.

function nfw_display_directories() {

	$nfw_options = nfw_get_option( 'nfw_options' );
	$nfw_exclude_waf_list = array();
	if (! empty( $nfw_options['exclude_waf_list'] ) ) {
		$nfw_exclude_waf_list = json_decode( $nfw_options['exclude_waf_list'], true );
	}
	$absfiles = scandir( ABSPATH );
	$list = '';
	$row = 0;
	foreach( $absfiles as $item ) {
		$checked = '';
		if ( $item != '.' &&  $item != '..' && is_dir( ABSPATH. $item ) ) {
			if (! in_array( $item, $nfw_exclude_waf_list ) ) {
				$checked = ' checked';
			}
			++$row;
			if ( $row % 2 == 0 ) {
				$r_color = 'f-white';
			} else {
				$r_color = 'f-grey';
			}
			$list .= '<tr class="'. $r_color .'"><td class="dir-list"><label><input type="checkbox" name="nfw_exclude_waf_list[]" value="'. htmlspecialchars( $item ) .'"'. $checked .' /> '. htmlspecialchars( $item ) .'/</label></td></tr>';
		}
	}
	return $list;
}
// ---------------------------------------------------------------------
// EOF
