<?php
require('../config_session.php');
?>
<div class="pad15">
	<div class="setting_element ">
		<p class="label"><?php echo $lang['private_mode']; ?></p>
		<select id="set_private_mode">
			<option <?php echo selCurrent($data['user_private'], 1); ?> value="1"><?php echo $lang['on']; ?></option>
			<?php if(boomAllow(1)){ ?>
			<option <?php echo selCurrent($data['user_private'], 3); ?> value="3"><?php echo $lang['user']; ?></option>
			<option <?php echo selCurrent($data['user_private'], 2); ?> value="2"><?php echo $lang['friend_only']; ?></option>
			<?php } ?>
			<option <?php echo selCurrent($data['user_private'], 0); ?> value="0"><?php echo $lang['off']; ?></option>
		</select>
	</div>
	<div class="tpad15">
		<button onclick="savePrivateSettings();" class="reg_button theme_btn"><i class="fa fa-save"></i> <?php echo $lang['save']; ?></button>
		<button class="cancel_over reg_button default_btn"><?php echo $lang['cancel']; ?></button>
	</div>
</div>