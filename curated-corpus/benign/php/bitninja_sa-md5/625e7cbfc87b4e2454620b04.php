<?php
require('../config_session.php');

if(!canTheme()){
	echo 0;
	die();
}
?>
<div class="pad20">
	<div class="setting_element ">
		<p class="label"><?php echo $lang['user_theme']; ?></p>
		<select id="set_user_theme" onchange="setUserTheme(this);">
			<?php echo listTheme($data['user_theme'], 2); ?>
		</select>
	</div>
</div>