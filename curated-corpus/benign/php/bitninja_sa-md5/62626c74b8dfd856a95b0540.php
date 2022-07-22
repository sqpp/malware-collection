<?php
require('../config_session.php');
if(!canMood()){ 
	die();
}
?>
<div class="pad15">
	<div class="boom_form">
		<p class="label"><?php echo $lang['mood']; ?></p>
		<input id="set_mood" maxlength="30" class="full_input" value="<?php echo $data['user_mood']; ?>" autocomplete="off" type="text"/>
	</div>
	<button onclick="saveMood();" class="reg_button theme_btn"><i class="fa fa-save"></i> <?php echo $lang['save']; ?></button>
	<button class="reg_button default_btn cancel_over"><?php echo $lang['cancel']; ?></button>
</div>