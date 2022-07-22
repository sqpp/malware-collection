<?php
require('../config_session.php');
if(!canInfo()){ 
	die();
}
?>
<div class="pad20">
	<div class="boom_form">
		<div class="form_split">
			<div class="form_left">
				<div class="setting_element ">
					<p class="label"><?php echo $lang['age']; ?></p>
					<select id="set_profile_age">
						<?php echo listAge($data['user_age'], 2); ?>
					</select>
				</div>
			</div>
			<div class="form_right">
				<div class="setting_element ">
					<p class="label"><?php echo $lang['gender']; ?></p>
					<select id="set_profile_gender">
						<?php echo listGender($data['user_sex']); ?>
					</select>
				</div>
			</div>
			<div class="clear">
			</div>
		</div>
	</div>
	<button type="button" onclick="saveInfo();" class="reg_button theme_btn"><i class="fa fa-floppy-o"></i> <?php echo $lang['save']; ?></button>
</div>