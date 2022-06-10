<?php
if (isset($_POST['info_update'])) 
{
	$updateOption = false;

	// Save Dev Key
	if(architect_save_option('architect_devkey')) $updateOption = true;
	// Save the show blog description
	if(architect_save_option('architect_show_blogdesc')) $updateOption = true;
	// Save the show blog title
	if(architect_save_option('architect_show_blogtitle')) $updateOption = true;
	// Save the mobile blog title
	if(architect_save_option('architect_mobile_blogtitle', array('stripTags' => true))) $updateOption = true;
	// Save the mobile blog description
	if(architect_save_option('architect_mobile_blogdesc', array('stripTags' => true))) $updateOption = true;
	// Save use header image
	if(architect_save_option('architect_use_headerimage')) $updateOption = true;
	// Save actual header image
	if(isset($_FILES['architect_headerimage']))
	{
		$pathToHeader = WP_UPLOAD_DIR.DIRECTORY_SEPARATOR.get_wapl_plugin_base().DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'header'.DIRECTORY_SEPARATOR;
		architect_check_path($pathToHeader);
		if(move_uploaded_file($_FILES['architect_headerimage']['tmp_name'], $pathToHeader.$_FILES['architect_headerimage']['name']))
		{
			update_option('architect_headerimage', $_FILES['architect_headerimage']['name']);
			$updateOption = true;
		}
	}
	// Save header image BG transparency col
	if(architect_save_option('architect_headerimage_transparency_colour')) $updateOption = true;
	// Is the header image a link back to the homepage
	if(architect_save_option('architect_headerimagelinktohome')) $updateOption = true;
	// Save use footer image
	if(architect_save_option('architect_use_footerimage')) $updateOption = true;
	// Save actual footer image
	if(isset($_FILES['architect_footerimage']))
	{
		$pathToFooter = WP_UPLOAD_DIR.DIRECTORY_SEPARATOR.get_wapl_plugin_base().DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'footer'.DIRECTORY_SEPARATOR;
		architect_check_path($pathToFooter);
		if(move_uploaded_file($_FILES['architect_footerimage']['tmp_name'], $pathToFooter.$_FILES['architect_footerimage']['name']))
		{
			update_option('architect_footerimage', $_FILES['architect_footerimage']['name']);
			$updateOption = true;
		}
	}
	// Save footer image BG transparency col
	if(architect_save_option('architect_footerimage_transparency_colour')) $updateOption = true;
	// Show footer text
	if(architect_save_option('architect_showfootertext')) $updateOption = true;
	// What is the footer text
	if(architect_save_option('architect_footertext', array('stripTags' => true))) $updateOption = true;
	// Save show menu at top
	if(architect_save_option('architect_showmenuattop')) $updateOption = true;
	// Save show menu at bottom
	if(architect_save_option('architect_showmenuatbottom')) $updateOption = true;
	// Save show author
	if(architect_save_option('architect_showauthor')) $updateOption = true;
}

if(isset($updateOption) AND $updateOption == true)
{
	echo "<div class='updated fade'><p><strong>Settings saved</strong></p></div>";
}
	
if(!isset($oldVersion) OR $oldVersion == false)
{
	echo '<div class="wrap">';
	echo '<form method="post" action="admin.php?page=architect-basic" enctype="multipart/form-data">';
	echo architect_admin_header('2', 'Wapple Architect Basic Settings');	
} else
{
	echo '<h3>Basic Settings</h3>';
}

echo architect_table_start();

// Dev Key
echo architect_admin_option('input', array('label' => 'Wapple Architect Dev Key', 'name' => 'architect_devkey', 'value' => get_option('architect_devkey'), 'description' => 'Enter your newly acquired Dev Key from Wapple'));

// Mobile Blog Title
echo architect_admin_option('input', array('label' => 'Mobile Blog Title', 'name' => 'architect_mobile_blogtitle', 'value' => stripslashes(get_option('architect_mobile_blogtitle')), 'description' => 'Enter your Mobile Blog Title (leave blank if you want your normal one)'));

// Mobile Blog Title
echo architect_admin_option('input', array('label' => 'Mobile Blog Description', 'name' => 'architect_mobile_blogdesc', 'value' => stripslashes(get_option('architect_mobile_blogdesc')), 'description' => 'Enter your Mobile Blog Description (leave blank if you want your normal one)'));

// Use a mobile header image
echo architect_admin_option('select', array('label' => 'Show blog title', 'name' => 'architect_show_blogtitle', 'options' => array('1' => 'Yes', '0' => 'No'), 'value' => get_option('architect_show_blogtitle'), 'description' => 'Show the blog title in the header'));

// Show the blog description
echo architect_admin_option('select', array('label' => 'Show blog description', 'name' => 'architect_show_blogdesc', 'options' => array('1' => 'Yes', '0' => 'No'), 'value' => get_option('architect_show_blogdesc'), 'description' => 'Show blog description at the top of your page'));

// Use a mobile header image
echo architect_admin_option('select', array('label' => 'Use mobile header image', 'name' => 'architect_use_headerimage', 'options' => array('1' => 'Yes', '0' => 'No'), 'value' => get_option('architect_use_headerimage'), 'description' => 'Use an image on your site to replace header text'));


// Upload a mobile header image	
echo architect_admin_option('file', array('label' => 'Mobile site header image', 'name' => 'architect_headerimage', 'description' => 'Browse your local computer for a header image<br />Make your header image as big as possible as it gets dynamically resized on the fly to fit the handset. 500px x 100px is good, 1000px x 200px is even better!'));

// Transparency colour for header image
echo architect_admin_option('input', array('label' => 'Header BG transparency colour', 'size' => 6, 'name' => 'architect_headerimage_transparency_colour', 'value' => get_option('architect_headerimage_transparency_colour'), 'before' => '#', 'description' => 'What background colour do you want your header image to have?'));

// Show mobile header image preview
if(get_option('architect_headerimage') AND get_option('architect_use_headerimage'))
{
	if(file_exists(WP_UPLOAD_DIR.DIRECTORY_SEPARATOR.get_wapl_plugin_base().DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'header'.DIRECTORY_SEPARATOR.get_option('architect_headerimage')))
	{
		$src = WP_CONTENT_URL.'/uploads/'.get_wapl_plugin_base().'/images/header/'.get_option('architect_headerimage');
	} else if(file_exists(WP_PLUGIN_DIR.DIRECTORY_SEPARATOR.get_wapl_plugin_base().DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'header'.DIRECTORY_SEPARATOR.get_option('architect_headerimage')))
	{
		$src = WP_CONTENT_URL.'/plugins/'.get_wapl_plugin_base().'/theme/images/header/'.get_option('architect_headerimage');
	} else
	{
		$src = '';
	}
	echo architect_admin_option('image', array('label' => 'Mobile site header image preview', 'alt' => 'Mobile Header Image', 'src' => $src));
}

// Header image as link -> homepage
echo architect_admin_option('select', array('label' => 'Header links back to home', 'name' => 'architect_headerimagelinktohome', 'options' => array('1' => 'Yes', '0' => 'No'), 'value' => get_option('architect_headerimagelinktohome'), 'description' => 'Set your header as a link back to the home page'));

// Use a mobile footer image
echo architect_admin_option('select', array('label' => 'Use mobile footer image', 'name' => 'architect_use_footerimage', 'options' => array('1' => 'Yes', '0' => 'No'), 'value' => get_option('architect_use_footerimage'), 'description' => 'Use an image on your site to replace footer text'));

// Upload a mobile footer image	
echo architect_admin_option('file', array('label' => 'Mobile site footer image', 'name' => 'architect_footerimage', 'description' => 'Browse your local computer for a footer image<br />Make your footer image as big as possible as it gets dynamically resized on the fly to fit the handset. 500px x 100px is good, 1000px x 200px is even better!'));

// Transparency colour for footer image
echo architect_admin_option('input', array('label' => 'Footer BG transparency colour', 'size' => 6, 'name' => 'architect_footerimage_transparency_colour', 'value' => get_option('architect_footerimage_transparency_colour'), 'before' => '#', 'description' => 'What background colour do you want your footer image to have?'));

// Show mobile footer image preview
if(get_option('architect_footerimage') AND get_option('architect_use_footerimage'))
{
	if(file_exists(WP_UPLOAD_DIR.DIRECTORY_SEPARATOR.get_wapl_plugin_base().DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'footer'.DIRECTORY_SEPARATOR.get_option('architect_footerimage')))
	{
		$src = WP_CONTENT_URL.'/uploads/'.get_wapl_plugin_base().'/images/footer/'.get_option('architect_footerimage');
	} else if(file_exists(WP_PLUGIN_DIR.DIRECTORY_SEPARATOR.get_wapl_plugin_base().DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'footer'.DIRECTORY_SEPARATOR.get_option('architect_footerimage')))
	{
		$src = WP_CONTENT_URL.'/plugins/'.get_wapl_plugin_base().'/theme/images/footer/'.get_option('architect_footerimage');
	} else
	{
		$src = '';
	}
	echo architect_admin_option('image', array('label' => 'Mobile site footer image preview', 'alt' => 'Mobile Footer Image', 'src' => $src));
}

// Show footer text
echo architect_admin_option('select', array('label' => 'Show footer text', 'name' => 'architect_showfootertext', 'options' => array('1' => 'Yes', '0' => 'No'), 'value' => get_option('architect_showfootertext'), 'description' => 'Show footer text at the bottom of your site'));

// Footer text
echo architect_admin_option('input', array('label' => 'Footer Text', 'name' => 'architect_footertext', 'value' => stripslashes(get_option('architect_footertext')), 'description' => 'Define footer text to appear at the bottom of your site'));

// Show menu at top
echo architect_admin_option('select', array('label' => 'Show menu at top', 'name' => 'architect_showmenuattop', 'options' => array('1' => 'Yes', '0' => 'No'), 'value' => get_option('architect_showmenuattop'), 'description' => 'Show your menu at the top'));

// Show menu at bottom
echo architect_admin_option('select', array('label' => 'Show menu at bottom', 'name' => 'architect_showmenuatbottom', 'options' => array('1' => 'Yes', '0' => 'No'), 'value' => get_option('architect_showmenuatbottom'), 'description' => 'Show your menu at the bottom'));

// Show author on home page and posts page
echo architect_admin_option('select', array('label' => 'Show post author name', 'name' => 'architect_showauthor', 'options' => array('1' => 'Yes', '0' => 'No'), 'value' => get_option('architect_showauthor'), 'description' => 'Show author name on home page and post'));

echo '</table>';

if(!isset($oldVersion) OR $oldVersion == false)
{
	echo '<table class="form-table" cellspacing="2" cellpadding="5" width="100%"><tr><td><p class="submit"><input class="button-primary" type="submit" name="info_update" value="Save Changes" /></p></td></tr></table>';
	echo '</form></div>';
}
?>