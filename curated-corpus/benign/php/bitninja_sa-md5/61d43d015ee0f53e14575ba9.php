<div class="wrap">
<h2>Dynamic Headers - Settings</h2>

<?php
	include("header.php");
	if($_GET['updated'] == 'true'){
		echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade"><p>Settings Updates</p></div>';
	}
?>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<?php settings_fields( 'dhnd_options' ); ?> 

<table class="form-table">

<tr valign="top">
<th scope="row">Display Footer Link</th>
<td><input type="checkbox" name="dhnd_footer_link" value="Yes" <?php  if(get_option('dhnd_footer_link') == 'Yes') echo 'Checked' ?> />
	<span class="setting-description">Check this box to display a link to us in your footer.<br />This is entirely optional, but we do provide this plugin free of charge and appreciate any link-backs.<br />If this breaks your page layout in any way, please disable this option.</span></td>
</tr>

<th scope="row">Max File Size</th>
<td><input type="text" name="dhnd_max_size" value="<?php echo get_option('dhnd_max_size'); ?>" />
	<span class="setting-description">File size in kb<br />This is the max size that your file uploads will be limited to. 200-500k is usually a safe upload size.</span></td>
</tr>

<tr valign="top">
<th scope="row">Default Header Media</th>
<td>	<select name="dhnd_default" id="dhnd_default">
			<option value="None" <?php if(get_option('dhnd_default') == 'None') echo 'selected'  ?>>- No Default Header -</option>
			<option value="Random" <?php if(get_option('dhnd_default') == 'Random') echo 'selected'  ?>>- Use Random Media -</option>
			<?php
			//Print out the media file list
			if($media_dir = opendir($dhnd_image_dir)){
				while ($m_file = readdir($media_dir)) {
					if($m_file != "." && $m_file != ".."){
						if(get_option('dhnd_default') == $m_file){
							echo '<option value="'.$m_file.'" selected>'.$m_file.'</option>';
						} else {
							echo '<option value="'.$m_file.'">'.$m_file.'</option>';
						}
					}
				}
			}
			?>
		</select>
	<span class="setting-description"><br />
	If you do not set a default header, none will be shown on pages where one is not set.<br />Selecting Random Media will randomly display one of your media files on pages that do not have a header associated with them.</span></td>
</tr>

<tr valign="top">
<th scope="row">Blog Page Header</th>
<td>	<select name="dhnd_homepage" id="dhnd_homepage">
			<option value="None" <?php if(get_option('dhnd_homepage') == 'None') echo 'selected'  ?>>- No Default Header -</option>
			<option value="Random" <?php if(get_option('dhnd_homepage') == 'Random') echo 'selected'  ?>>- Use Random Media -</option>
			<?php
			//Print out the media file list
			if($media_dir = opendir($dhnd_image_dir)){
				while ($m_file = readdir($media_dir)) {
					if($m_file != "." && $m_file != ".."){
						if(get_option('dhnd_homepage') == $m_file){
							echo '<option value="'.$m_file.'" selected>'.$m_file.'</option>';
						} else {
							echo '<option value="'.$m_file.'">'.$m_file.'</option>';
						}
					}
				}
			}
			?>
		</select>
	<span class="setting-description"><br />
	The blog page is the default homepage for WordPress. It is the page that shows your latest posts. <br />The blog page header has to be handled separately. Select the media to use on the blog page with this drop down.</span></td>
</tr>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="dhnd_max_size,dhnd_default,dhnd_homepage,dhnd_footer_link" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>