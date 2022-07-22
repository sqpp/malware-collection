<?php
if (isset($_POST['do_install'])){
$resp = ezbu_install_it();
}
        $scriptpath = dirname(dirname(__FILE__));
		$ppath = plugin_dir_url('').plugin_basename(dirname(dirname(__FILE__))).'/';
		$email = get_bloginfo('admin_email');

$cronfile = $scriptpath.''."/functions/schedule/schedule.jbz";
$key = ezbu_get_key();

if (isset($_POST['crontest'])) {
    // dump current cron jobs from crontab
        $crondump = "crontab -l > $cronfile";
        shell_exec("$crondump");
        $sh3 = $scriptpath.''."/functions/schedule/schedule.jbz";
        chmod("$sh3", 0700);

        if (file_exists($cronfile)) {
		  unlink($cronfile);
          $resp = 'Congratulations! Your Webhost supports running Cron Jobs. If you purchase the Cron Addon you should be all set for automatic backup scheduling.';
        }else{
          $resp = 'SORRY - Your Webhosting Provider does not allow you to run Scheduled Tasks.';
        }
}
$c1 = get_option('EZBUCRON');
$c2 = get_option('EZBUWP');
$c3 = get_option('EZBUATTACH');
?>
        <SCRIPT SRC="<?php echo $ppath; ?>functions/js/boxover.js"></SCRIPT>
<div style="width: 600px;">
<?php if ($resp) { ?>
<p class=ezupdatedstatic><?php echo $resp ?></p>
<?php } ?>
<h2>Help & Additional Information</h2>
<p>Wordpress EZ Backup has been re-written to ensure a much smoother operation with easier to configure options. Here you will find some additional information and details about the plugin itself.</p>
<h2>Installing Purchased Addons</h2>
<p>Addons that you purchased are easy to install. You can deactivate the reactivate the plugin this will verify your purchase and install the addons. You can also manually activate the addons.<br/>
<form method="post">
<input type="submit" name="do_install" value="Activate Purchased Addons">
</form>
</p>
<h2>Settings Page</h2>
<p>Here you can see the plugin was rewritten to minimize the need for advanced settings to be entered. I have done my best to ensure this plugin is the easiest to use not only for system admins but the average wordpress user as well.</p>
<ol>
<li>Currently Archiving - This piece of information shows the location the plugin will copy all files from for the backup. Anything located within the folder at the end of the path will be copied to the backup that is created.</li>
<li>Currently Saving Archives to - This shows you the path and folder the backups will be stored in. Please note this path is not adjustable. The plugin will always attempt to place the backups in a NON Public folder. This is for security reasons and its to prevent unauthorized users from downloading a copy of your files and database</li>
<li>Backup What? - You can choose to backup either your entire Wordpress website (public facing folders) OR you can choose to backup only the wp-content folder. wp-content usually contains the most important files for your site (uploads, themes, plugins etc.)</li>
<li>What Name to give your Backup - You can choose what the backup archive filename is here. The name will automatically have the date it was crated attached to it. Please do not use any special characters or spaces in the name. You may use Underscores if need be.</li>
<li>Enable E-mail Alerts - Enable this option and the plugin will send a simple email alert to you when a backup is created.</li>
<li>Email Address - The address to which the plugin will e-mail the alerts to</li>
<li>Send Backup as E-Mail Attachment - This will attach a copy of the backup to the email alert. I do not recommend emailing very large backups.</li>
</ol>
<h2>Running A Backup</h2>
<p>You will find that the backup page itself lists some useful information as well as allowing you to start the backup process. When a backup is being created you will be shown a log file with status updates during the creation process. The Backup page also has options to view the previous error logs and backup process logs.</p>
<h2>Browsing Backups</h2>
<p>The backup browser allows simple browsing of all current backups as well as the ability to download a copy right from the admin panel. Please note that in order to download a copy the file is temporarily placed in a public folder within your website. Once the download is complete this public folder and copy are removed for your security.</p>
<h2>Restoration</h2>
<p>This plugin does not offer automatic restoration and that is for many good reasons. Due to the possibility of overwriting or restoring files you did not need at the time and potentially causing issues with your site, restorations are best done by hand.</p>
<div style="width: 600px;">
        <h2>Automatic Backups with WP Scheduling</h2>
        <p>Take advantage of the built in Wordpress Scheduling and set your backups to run automatically. The WP Schedule is extremely simple to use. Set your scheduled run time and let Wordpress do the rest.<br/><br/>
<?php if ($c2 == 'no') { ?>		
Purchase the WP Scheduling Addon today via PayPal for $10.00<br/><br/>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="notify_url" value="http://lastnightsdesigns.com/EZBackupRegisters/sbd.php">
<input type="hidden" name="custom" value="<?php echo $key ?>">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<br/><br/>
<?php } ?>
</p>

        <h2>Automatic Backups with Cron</h2>
        <p>Cron is a program designed to handle Automated tasks. These taks are referred to as Cron jobs. Most webhosts fully support cron and allow their customers to setup automated tasks. If your webhost supports Cron you can take advantage of that and use it to schedule your automatic backups. Cron scheduling is way more flexible then WP Scheduling. Cron can schedule to a specific day and hour.<br/><br/>Purchase this addon today via PayPal for $20.00</p>

<h3>Does my Webhost support Cron?</h3><p>Click the button below to do a quick test for your Cron Daemon. If Cron is not found you would be required to use the WP Scheduling addon for automatic backup scheduling.</p><form method="post"><input type="submit" name="crontest" value="Check for Cron Daemon"></form><br/>
<?php if ($c1 == 'no') { ?>	
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="notify_url" value="http://lastnightsdesigns.com/EZBackupRegisters/sbd.php">
<input type="hidden" name="custom" value="<?php echo $key ?>">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<?php } ?>
</p>
</div>
<?php
?>
</div>
