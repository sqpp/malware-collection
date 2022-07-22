<?php
require_once('../config_session.php');
if(!isset($_POST['target'])){
	echo 0;
	die();
}
if(!canDeletePrivate()){
	echo 0;
	die();
}
$id = escape($_POST['target']);
?>
<div class="pad25">
	<div class="tpad10 bpad20">
		<p class="centered_element"><?php echo $lang['delete_private']; ?></p>
	</div>
	<div class="centered_element">
		<button onclick="clearPrivate(<?php echo $id; ?>);" class="reg_button theme_btn"><?php echo $lang['yes']; ?></button>
		<button class="reg_button cancel_over default_btn"><?php echo $lang['cancel']; ?></button>
	</div>
</div>