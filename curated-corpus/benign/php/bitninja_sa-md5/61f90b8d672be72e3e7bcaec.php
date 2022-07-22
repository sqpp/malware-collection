<?php
require_once('../config_session.php');

if(!isset($_POST['post_id'], $_POST['show_this_post'])){
	echo 0;
	die();
}
$postid = escape($_POST["post_id"]);
if(!canPostAction($postid)){
	echo 0;
	die();
}
?>
<div class="pad20">
	<?php echo showPost($postid); ?>
</div>