<?php
/*
Title: XML Feed Processor Help Page
Program Author URI: http://realestateconnected.com.au/
Description: Program created and written to process Australian REAXML feed for easy import into WordPress.
The program will process the input files that are places in the XML directory from your feed provider and save the results into
three XML output files in the /feedsync/outputs directory. These files contain the results of the input files.

Author: Merv Barrett
Author URI: http://realestateconnected.com.au/

Version: 2.0

*/
	require_once('../../config.php');
	require_once('../functions.php');
	$page_now = 'help';
	do_action('init');

	global $feedsync_db;
	get_header('help');

	$access_key_enabled 	= get_option('feedsync_enable_access_key');
	$access_key_raw		= get_option('feedsync_access_key');
	$access_key 		= '';


	if ( $access_key_enabled == 'on' ) {
		$access_key = '&#38;access_key=' . $access_key_raw;
	}

?>

	<div class="page-header">
		<h1>FeedSync Help</h1>
	</div>

	<h3 style="margin-top:2em;">Check for Updates, License and System Status</h3>

	<p>
		<a href="<?php echo feedsync_nav_link('updates.php') ?>" style="margin: 2em 0;" type="button" class="btn btn-sm btn-warning">Check For Updates</a>
		<a href="<?php echo feedsync_nav_link('license.php') ?>" style="margin: 2em 0;" type="button" class="btn btn-sm btn-warning">Check License Status</a>
		<a href="<?php echo feedsync_nav_link('activate.php') ?>" style="margin: 2em 0;" type="button" class="btn btn-sm btn-warning">Activate Your License</a>
		<a href="<?php echo feedsync_nav_link('info.php') ?>" style="margin: 2em 0;" type="button" class="btn btn-sm btn-warning">System Info</a>
		<a href="<?php echo feedsync_nav_link('tool-geocode-staus.php') ?>" style="margin: 2em 0;" type="button" class="btn btn-sm btn-warning">Check Geocode Status</a>
	</p>


	<h3 style="margin-top:1em;">Documentation</h3>

	<ul>
		<li><a href="http://codex.easypropertylistings.com.au/category/22-feedsync">FeedSync Documentation</a></li>
		<li><a href="http://codex.easypropertylistings.com.au/article/369-how-to-upgrade-to-feedsync-3-0">How to Upgrade to FeedSync 3.0</a></li>
		<li><a href="http://codex.easypropertylistings.com.au/article/84-update-image-urls-using-phpmyadmin-for-old-mydesktop-cdn">How to Upgrade old MyDesktop image URLs</a></li>
		<li><a href="http://codex.easypropertylistings.com.au/article/51-how-to-import-reaxml-into-wordpress-real-estate-website">How to Import REAXML files into Your WordPress Real Estate Website</a></li>
	</ul>

	<h3 style="margin-top:1em;">How to install FeedSync</h3>

	<p>Your first step is to determine where you want to install FeedSync on your server. Download the application from <a href="https://easypropertylistings.com.au/your-account/">your account</a> at <a href="https://easypropertylistings.com.au/">Easy Property Listings.</a></p>

	<p>Uncompress feedsync.zip on your computer and FTP the feedsync directory into the XML/ directory on your server</p>

	<ul>
		<li>cPanel: Recommended folder: <strong>public_html/XML/</strong></li>
		<li>Plesk/Parallels: Recommended folder: <strong>httpdocs/XML/</strong></li>
	</ul>

	<h3 style="margin-top:2em;">Create a database for FeedSync to store listing data</h3>

	<p>FeedSync uses a SQL database to store listing data in. Login to your hosting and create a MySQL database.</p>

	<h4>cPanel Database</h4>
	<ul>
		<li>Click the <strong>MySQL Database Wizard</strong> under the Databases heading.</li>
		<li>Next to <strong>New Database</strong> enter a name for your database and click <strong>Next Step</strong>.</li>
		<li>Next to <strong>Username</strong> enter a username.</li>
		<li>Enter a password next to Password, enter it again for Password (Again) and then click Create User</li>
		<li>On the next page, you'll assign privileges for the user to the database. Check the box next to All Privileges and then click Next Step.</li>
	</ul>

	<h4>Plesk/Parallels Database</h4>
	<ul>
		<li>Login to your hosting and click the <strong>Websites</strong> tab.</li>
		<li>Click the <strong>Databases</strong> under the <strong>Websites & Domains</strong> heading.</li>
		<li>Press <strong>Add New Database</strong> enter <strong>feedsync</strong> for Database name.</li>
		<li>Make a note of the <strong>Database server</strong>.</li>
		<li>Under the <strong>Users</strong> heading enter <strong>feedsync</strong> in the <strong>Database user name</strong> field.</li>

		<li>Enter a strong secure password in the <strong>New password</strong> field, enter it again for <strong>Confirm password</strong> field (Again) and then click <strong>Ok</strong>.</li>
	</ul>

	<p>Congratulations, you have just successfully created a database for FeedSync!</p>

	<p>Make a note of your database name, password and database username.</p>

	<h3 style="margin-top:2em;">Performing the The Initial FeedSync Setup</h3>

	<p>There is only one file that you need to edit in order for your FeedSync to work which is the config.php file located in the feedsync directory.</p>

	<p>Open up your FTP program and navigate to the folder where you installed FeedSync and open the config.php file in a text editor. Edit the following details highlighted in bold below:</p>

	<h4>Base settings</h4>

	<ul>
		<li>define('DB_NAME', '<strong>database_name</strong>' );</li>
		<li>define('DB_USER', '<strong>database_user_name</strong>' );</li>
		<li>define('DB_PASS', '<strong>database_password</strong>' );</li>
		<li>define('DB_HOST', '<strong>localhost</strong>' ); // Plesk/Parallels: replace <strong>localhost</strong> with your Database server details.</li>
		<li>// define('SITE_URL', '<strong>http://YOUR_WEBSITE_DOMAIN_NAME.COM.AU/XML/feedsync/</strong>'); // Optional. Uncomment if the site URL is not automatically detected or you move the location of FeedSync.</li>
	</ul>

	<p>Save the config file and visit your feedsync directory from your browser and you should see FeedSync in all its glory :)</p>

	<h3 style="margin-top:2em;">Automatically generate latitude and longitude coordinates</h3>

	<p>Some providers supply latitude and longitude coordinates for listings and some do not. Once you receive files from your provider open one of the XML files and look for a &#60;Geocode&#62; node which contains the Lat and Long values. If &#60;Geocode&#62; is not present set the following option to ON. Default is OFF.</p>

	<p>Once your provider has delivered the REAXML files and they are ready for processing by FeedSync for the first time it is best to leave the setting OFF, process the files then manually generate your coordinates from the Process Page. Once the coordinates are generated for all the listings, set the following setting to ON. </p>

	<ul>
		<li>Visit the FeedSync <a href="<?php echo feedsync_nav_link('../settings.php') ?>">Settings page</a> to enable this.</li>
	</ul>

	<h3 style="margin-top:2em;">Force re-generating coordinates</h3>

	<p>In some instances your coordinates may not be correct so you can set the Force Geocode setting to ON which will re-generate all coordinates for your listings. Once updated, set this setting to OFF.</p>

	<ul>
		<li>Visit the FeedSync <a href="<?php echo feedsync_nav_link('../settings.php') ?>">Settings page</a> to enable this.</li>
	</ul>

	<h3 style="margin-top:2em;">Create a unique FTP account for your REAXML provider</h3>

	<p>We recommend that you to create an unique FTP account for the feed provider which will only give them access to the <strong>feedsync/input</strong> folder. <em>They don't need access to anything else on your server.</em> This lets you move FeedSync later and all you have to do is edit the providers FTP Account Directory on your hosting.</p>

	<p>Log into your hosting account and press the FTP Accounts button. Create a unique FTP Account using a unique username and strong secure password.</p>

	<p><strong>Important:</strong> In the <strong>Directory</strong> field specify the FeedSync input folder. With cPanel its usually:</p>

	<ul>
		<li>cPanel: Recommended: <strong>public_html/XML/feedsync/input</strong></li>
		<li>Plesk/Parallels: Recommended: <strong>httpdocs/XML/feedsync/input</strong></li>
	</ul>

	<p>Supply your REAXML feed provider with their unique FTP details:</p>

	<ul>
		<li><strong>FTP Account:</strong> ftp.myawesomewebsite.com.au</li>
		<li><strong>User name:</strong> reaxml@myawesomewebsite.com.au</li>
		<li><strong>Password:</strong> Secure password e.g. rn7vHgU <em>Note: Some providers have difficulty with long passwords.</em></li>
		<li><strong>Folder:</strong> Leave this blank as you have only given them access to the feedsync/input folder.</li>
	</ul>

	<p>Once your provider has configured their end they will start delivering REAXML files to the input folder. When files are in the feedsync/input folder they will be listed on the FeedSync home page as ready for processing.</p>

	<h3 style="margin-top:2em;">Processing for the first time</h3>

	<p>Once you have received REAXML files you can perform the import process manually to check that everything is working correctly with your configuration.</p>

	<h3 style="margin-top:2em;">Upgrading From FeedSync 2.0+</h3>

	<p>Upgrading FeedSync to 2.1 from 3.0+ is a simple process. Download the latest version of FeedSync from <a href="https://easypropertylistings.com.au/your-account/">your account</a>. Unzip the file to your computer and all you need to do is replace the /core folder and its contents in your installation of FeedSync on your server. Also update the index.php file in the feedsync directory.</p>

	<p>Once you have upgraded the core files you need to perform a one time <strong>Database Upgrade</strong> from the <a href="<?php echo feedsync_nav_link('process.php') ?>">Process page</a> which will allow you to filter listings by office id and address values. Once the database is upgraded you can update your agent database with the <strong>Process Listing Agents</strong> button.</p>

	<p>Instructions to upgrade from FeedSync 1.3 or lower <a href="https://easypropertylistings.com.au/docs/how-to-upgrade-to-feedsync-2-0/">can be found here.</a></p>

	<h3 id="cron" style="margin-top:2em;">How To Setup Your Cron Job</h3>

	<p>Once your feed is working correctly and you have successfully processed your initial feed manually you can create a cron job on your server to process the files automatically.</p>

	<p>Login to your server cPanel account (usually just add /cpanel to the end of your domain name. Once you have logged in look for the <strong>Cron Jobs</strong> button. On the Cron Jobs page under the Add New Cron Job under common settings select Once every 30 minutes and insert the following into the Command box and press Add New Cron Job.</p>

	<?php
	$feedsync_path = getcwd();
	chdir('../');
	$feedsync_path = getcwd();

	if ( $feedsync_path ) { ?>

	<h5>Option 1 is the most reliable for servers.</h5>

	<h6>Cron Option 1</h6>
	<div class="alert alert-success">wget -q -O /dev/null "<?php echo SITE_URL; ?>core/cron.php"</div>

	<h6>Cron Option 2</h6>
	<div class="alert alert-success">/usr/bin/php -q <?php echo $feedsync_path; ?>/cron.php</div>

	<h6>Cron Option 3</h6>
	<div class="alert alert-success">GET <?php echo SITE_URL; ?>core/cron.php</div>

	<h6>Cron Option 4 (EAC Format Only)</h6>
	<p>When using the EAC import format, two cron jobs must be set. The Trigger script can be set to once per hour and the Process to once every 2 minutes for best results.</p>

	<div class="alert alert-success">
		<div class="row">
			<div class="col-md-12">
				<strong>Trigger Import</strong>
				<p>wget -q -O /dev/null "<?php echo SITE_URL; ?>core/cron.php?action=trigger"</p>
			</div>
			<div class="col-md-12">
				<strong>Process import </strong>
				<p>wget -q -O /dev/null "<?php echo SITE_URL; ?>core/cron.php?action=process"</p>
			</div>
		</div>
	</div>

	<?php } else { ?>

	<h6>Cron Option 1</h6>
	<p><strong>/usr/bin/php -q /home/YOUR_ACCOUNT/public_html/XML/feedsync/core/cron.php</strong></p>

	<h6>Cron Option 2</h6>
	<p><strong>GET http://YOUR_URL.COM.AU/XML/feedsync/core/cron.php</strong></p>

	<?php } ?>

	<p>Now your FeedSync will check for new files and run will process them at your scheduled cron times. <a href="http://codex.easypropertylistings.com.au/article/265-alternative-wget-cron-command">Alternative to wget cron command</a> for some servers.</p>




	<?php
		/**
		 * Export Help
		 *
		 * @since 1.0
		 * @return void
		 */


		$feed_type = get_option('feedtype');

	?>

	<h3 style="margin-top:2em;">Exporting Listings for Import. </h3>

	<h5><em>Current format is: <strong><?php echo $feed_type; ?></strong></em></h5>

	<p>FeedSync has an export tab for exporting xml files manually.</p>

	<p>You can also use the dynamic action to and you can also directly access the export via a URL for importing into your website. Using <a href="https://easypropertylistings.com.au/">Easy Property Listings</a> with <a href="http://www.wpallimport.com/">WP All Import Pro</a> and the <a href="https://wordpress.org/plugins/easy-property-listings-xml-csv-import/">Importer Add-On</a> allows you to import REAXML listing data into your WordPress website.</p>

	<div class="alert alert-success"><?php echo SITE_URL; ?>?action=do_output<?php echo $access_key; ?>&#38;type=residential</div>

	<p>Note: This will only output listings that are current, leased, sold. If you want to include withdrawn and off market listing you can use &status=all</p>

	<p>You can specify the file output using the following variables based on the REAXML specifications.</p>

	<ol>
		<li>&#63;action=do_output : this is required.</li>
		<li>&#38;access_key=<?php echo $access_key_raw; ?> : Access Key is (<?php echo $access_key_enabled; ?>) this is required when on/enabled.</li>
		<li>&#38;type=all or a singular value of ( all, residential, rental, land, rural, commercial, business, commercial_land).</li>
		<li>&#38;status=current or a singular value of (current, sold, leased, withdrawn, offmarket, all).</li>
	</ol>

	<p>Adjust the type=residential to the post type you want to export. Available types for <strong><?php echo $feed_type; ?></strong> are:</p>

	<?php
	/**
	 * Export Types REAXML
	 *
	 * @since 1.0
	 */

	if ( $feed_type == 'reaxml' ) { ?>

		<ul>
			<li>residential</li>
			<li>rental</li>
			<li>land</li>
			<li>rural</li>
			<li>business</li>
			<li>commercial</li>
			<li>commercialland</li>
		</ul>
	<?php } ?>

	<?php
	/**
	 * Export Types Rockend Rest
	 *
	 * @since 1.0
	 */

	if ( $feed_type == 'rockend' ) { ?>

		<ul>
			<li>rental</li>
		</ul>
	<?php } ?>

	<?php
	/**
	 * Export Types EAC
	 *
	 * @since 3.2
	 */

	if ( $feed_type == 'eac' ) { ?>
		<ul>
			<li>residential</li>
			<li>rental</li>
			<li>land</li>
			<li>rural</li>
			<li>business</li>
			<li>commercial</li>
			<li>commercialland</li>
		</ul>
	<?php } ?>

	<?php
	/**
	 * Export Types JUPIX
	 *
	 * @since 3.2
	 */

	if ( $feed_type == 'jupix' ) { ?>
		<ul>
			<li>property</li>
			<li>rental</li>
			<li>commercial</li>
		</ul>
	<?php } ?>

	<?php
	/**
	 * Export Types Expert Agent
	 *
	 * @since 3.2
	 */

	if ( $feed_type == 'expert_agent' ) { ?>
		<ul>
			<li>property</li>
			<li>rental</li>
			<li>commercial</li>
		</ul>
	<?php } ?>

	<?php
	/**
	 * Export Types BLM
	 *
	 * @since 3.2
	 */

	if ( $feed_type == 'blm' ) { ?>
		<ul>
			<li>residential</li>
			<li>rental</li>
		</ul>
	<?php } ?>



	<h3 style="margin-top:2em;">Additional Listing Export Commands</h3>

	<p>You can also output your listings by address fields and agent/office id.</p>

	<div class="alert alert-success"><?php echo SITE_URL; ?>?action=do_output<?php echo $access_key; ?>&#38;type=residential&#38;agent_id=123456</div>

	<p>You can output listings by additional options indicated below.</p>

	<ol>
		<li>&#63;action=do_output : this is required.</li>
		<li>&#38;access_key=<?php echo $access_key_raw; ?> : Access Key is (<?php echo $access_key_enabled; ?>) this is required when on/enabled.</li>
		<li>&#38;agent_id=123456 : output listings by office id.</li>
		<li>&#38;date=today : output listings by the current date.</li>
		<li>&#38;days_back=10 : output listings for the past number of specified days.</li>
		<li>&#38;minutes_back=10 : output listings for the past number of minutes.</li>
		<li>&#38;street=Smith Street : output listings by street name.</li>
		<li>&#38;suburb=Sydney : output listings by suburb.</li>
		<li>&#38;state=WA : output listings by state.</li>
		<li>&#38;postcode=6000 output listings by postcode.</li>
		<li>&#38;country= output listings by country.</li>
		<li>&#38;listing_agent= output listings listing agent.</li>
	</ol>

	<h3 style="margin-top:2em;">Exporting Agents</h3>

	<p>You can export all agents present in your database for import.</p>

	<div class="alert alert-success"><?php echo SITE_URL; ?>?action=do_output<?php echo $access_key; ?>&#38;type=agents</div>


<?php echo get_footer(); ?>