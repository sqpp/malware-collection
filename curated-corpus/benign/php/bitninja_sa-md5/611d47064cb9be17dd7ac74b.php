<?php
  // Create menu
  $title = __('.htaccess', 'all_in_one');
  ais_menu($title);


  if(ais_is_demo()) {
    osc_add_flash_error_message(__('This is demo site, you are not authorized to access this section', 'all_in_one'), 'admin');
    header('Location:' . osc_admin_base_url() . '?page=plugins&action=renderplugin&file=all_in_one/admin/global.php');
    exit;
  }


  // GET & UPDATE PARAMETERS
  // $variable = ais_param_update( 'param_name', 'form_name', 'input_type', 'plugin_var_name' );
  // input_type: check, code or value


  // GET & UPDATE PARAMETERS
  $htaccess_file = ais_param_update( 'htaccess_file', 'plugin_action', 'value', 'plugin-ais' );
  $htaccess_custom = ais_param_update( 'htaccess_custom', 'plugin_action', 'code', 'plugin-ais' );


  // Define default content of .htaccess file
  $htaccess_default = 
'<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>';


  // UPDATE .HTACCESS FILE
  $htaccess_default_text = '&lt;IfModule mod_rewrite.c&gt;<br>&nbsp;&nbsp;RewriteEngine On<br>&nbsp;&nbsp;RewriteBase /<br>&nbsp;&nbsp;RewriteRule ^index\.php$ - [L]<br>&nbsp;&nbsp;RewriteCond %{REQUEST_FILENAME} !-f<br>&nbsp;&nbsp;RewriteCond %{REQUEST_FILENAME} !-d<br>&nbsp;&nbsp;RewriteRule . /index.php [L]<br>&lt;/IfModule&gt;';

  if(Params::getParam('plugin_action') == 'done') {
    if($htaccess_file == 0) { 
      if(file_exists(osc_base_path() .  ".htaccess_backup")) {
        $content = file_get_contents(osc_base_path() .  ".htaccess_backup");
      } else {
        $content = $htaccess_default; 
      }

      $htaccess = $content;
    } else {
      if(!file_exists(osc_base_path() .  ".htaccess_backup")) {
        $fp_backup = fopen(osc_base_path() .  ".htaccess_backup", "w+"); 
        fwrite($fp_backup, file_get_contents(osc_base_path() .  ".htaccess"));
        fclose($fp_backup);
        message_ok(__('Backup file .htaccess_backup file was successfully created', 'all_in_one'));
      }

      $content = $htaccess_custom;  
    }

    $fp = fopen(osc_base_path() .  ".htaccess", "w+"); 
    fwrite($fp, $content);
    fclose($fp);
    
    osc_reset_preferences();
    message_ok(__('.htaccess file was successfully updated', 'all_in_one'));

    if(!is_writable(osc_base_path() .  ".htaccess")) {
      message_error(__('It is impossible to write to .htaccess file, please change CHMOD settings on this file.', 'all_in_one'));
    }    
  }


  $rewrite = osc_get_preference('rewriteEnabled', 'osclass');

?>

<div class="mb-body">

  <!-- CHECK IF FRIENDLY URLS ARE ENABLED, IF NOT SHOW ERROR MESSAGE -->
  <?php if($rewrite <> 1) { ?>
    <div class="mb-error-box" style="margin:10px 0 35px 0;">
      <div class="mb-line"><?php _e('You did not activated Friendly URLs. Activate it first or you will not be able to save settings.', 'all_in_one'); ?></div>
      <div class="mb-line"><?php _e('You can activate this on', 'instant_messenger'); ?> <strong><a href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=permalinks" target="_blank"><?php _e('oc-admin > Settings > Permalinks > "Enable friendly urls"', 'all_in_one'); ?></a></strong>.</div>
    </div>
  <?php } ?>


  <form name="promo_form" id="promo_form" action="<?php echo osc_admin_base_url(true); ?>" method="POST" enctype="multipart/form-data" >
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>htaccess.php" />
    <input type="hidden" name="plugin_action" value="done" />
    
    <!-- HTACCESS SETTINGS -->
    <div class="mb-box">
      <div class="mb-head"><i class="fa fa-cog"></i> <?php _e('.htaccess file settings', 'all_in_one'); ?></div>

      <div class="mb-inside">
        <div class="mb-row">
          <label for="htaccess_file" class="h1"><span><?php _e('Select .htaccess file', 'all_in_one'); ?></span></label> 
          <select name="htaccess_file" id="htaccess_file"> 
            <option <?php if($htaccess_file == 1){ ?>selected="selected"<?php } ?> value="1"><?php _e('Custom', 'all_in_one'); ?></option>
            <option <?php if($htaccess_file == 0){ ?>selected="selected"<?php } ?> value="0"><?php _e('Original', 'all_in_one'); ?></option>
          </select>
          
          <div class="mb-explain"><?php _e('Choose which .htaccess file should be use, if your custom, or default one.', 'all_in_one'); ?></div>
        </div>

       <div class="mb-row">
          <label for="htaccess_custom" class="h2"><span><?php _e('Custom .htaccess file content', 'all_in_one'); ?></span></label> 
          <textarea <?php if ($htaccess_file == 0) { ?>readonly<?php } ?> rows="20" type="text" id="htaccess_custom" name="htaccess_custom" class="mb-textarea-super-large"><?php echo htmlentities($htaccess_custom); ?></textarea>
          
          <div class="mb-explain"><?php _e('Define content of custom .htaccess file.', 'all_in_one'); ?></div>
        </div>       

      </div>

      <div class="mb-foot">
        <button <?php if($rewrite <> 1) { ?>disabled<?php } ?> type="submit" class="mb-button"><?php _e('Save', 'all_in_one');?></button>
      </div>
    </div>
    
  </form>

  

  <!-- HELP TOPICS -->
  <div class="mb-box" id="mb-help">
    <div class="mb-head"><i class="fa fa-question-circle"></i> <?php _e('Help', 'all_in_one'); ?></div>

    <div class="mb-inside">
      <div class="mb-row mb-help"><span class="sup">(1)</span> <div class="h1"><?php _e('You can use .htaccess generated by this form (custom) or default .htaccess (original). When original choosen will be choosen, copy from backup file will be used to restore original file.', 'all_in_one'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(2)</span> <div class="h2"><?php _e('When you create .htaccess file using this form, original one will be back up in same folder with name <strong>.htaccess_backup</strong>. This file will be used just in case it does not exist. If it exists, it will not be overwritten.', 'all_in_one'); ?></div></div>
      <div class="mb-row mb-help">
        <div class="mb-line"><?php _e('Do not miss following pages that helps you to understand and correctly generate .htaccess file:', 'all_in_one'); ?></div>
        <div class="mb-line" style="margin:0;"><a href="http://www.htaccessredirect.net/" target="_blank"><?php _e('.htaccess generator', 'all_in_one'); ?></a></div>
        <div class="mb-line" style="margin:0;"><a href="http://httpd.apache.org/docs/2.2/howto/htaccess.html" target="_blank"><?php _e('.htaccess documentation', 'all_in_one'); ?></a></div>
      </div>

      <div class="mb-row mb-help">
        <div class="mb-line"><?php _e('Default .htaccess file generated by osclass has content:', 'all_in_one'); ?></div>
        <div class="mb-line"><div class="mb-code"><?php echo $htaccess_default_text; ?></div></div>
      </div>

      <div class="mb-row mb-help"><div><?php _e('When you get <strong>500 Internal Server Error</strong> on your site, there was some problem in saving .htaccess file or your code is incorrect. In this case just replace content of file with default content listed above.', 'all_in_one'); ?></div></div>
    </div>
  </div>
</div>

<?php echo ais_footer(); ?>
