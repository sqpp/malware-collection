<?php
/**
* Codychat
*
* @package Codychat
* @author www.boomcoding.com
* @copyright 2020
* @terms any use of this script without a legal license is prohibited
* all the content of Codychat is the propriety of BoomCoding and Cannot be 
* used for another project.
*/
require_once('../config_session.php');

$wall_content = '';
$post_count = 0;
$find_friend = $mysqli->query("SELECT target FROM boom_friends WHERE hunter = '{$data['user_id']}' AND fstatus = '3'");
$friend_array = array($data['user_id']);
if($find_friend->num_rows > 0){
	while($add_friend = $find_friend->fetch_assoc()){
		array_push($friend_array, $add_friend['target']);
	}
}
$newarray = implode(", ", $friend_array);	
$wall_post = $mysqli->query("SELECT boom_post.*, boom_users.*,
(SELECT count( parent_id ) FROM boom_post_reply WHERE parent_id = boom_post.post_id ) as reply_count,
(SELECT like_type FROM boom_post_like WHERE uid = '{$data['user_id']}' AND like_post = boom_post.post_id) as liked,
(SELECT count( post_id ) FROM boom_post WHERE post_user IN ($newarray)) as post_count
FROM  boom_post, boom_users 
WHERE boom_post.post_user = boom_users.user_id AND boom_post.post_user IN ($newarray)
ORDER BY boom_post.post_actual DESC LIMIT 10");

if($wall_post->num_rows > 0){
	while ($wall = $wall_post->fetch_assoc()){
		$post_count = $wall['post_count'];
		$wall_content .= boomTemplate('element/wall_post',$wall);
	}
}
else { 
	$wall_content .= emptyZone($lang['wall_empty']);
}
?>
<div class="boom_keep">
	<div class="pad20">
		<?php if(!muted()){ ?>
		<div class="vpad10">
			<div id="add_wall_form">
				<div class="post_input_container">
					<textarea onkeyup="textArea(this, 60);" id="friend_post" spellcheck="false" maxlength="500" placeholder="<?php echo $lang['start_new_post']; ?>" class="full_textarea" ></textarea>
					<div id="post_file_data" class="pad10 main_post_data hidden" data-key="">
					</div>
				</div>
				<div class="main_post_control">
					<?php if(canUploadWall()){ ?>
					<div class="main_post_item">
						<i class="fa fa-image"></i>
						<input id="wall_file" onchange="uploadWall();" type="file"/>
					</div>
					<?php } ?>
					<div class="main_post_button">
						<button onclick="postWall();" class="small_button rounded_button theme_btn"><i class="fa fa-send"></i> <?php echo $lang['post']; ?></button>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<div id="container_wall">
		<?php echo $wall_content; ?>
		</div>
		<?php if($post_count > 10){ ?>
		<div class="centered_element">
			<button id="data_count" onclick="moreWall(this);" class="theme_btn small_button rounded_button load_more"  data-current="10" data-total="<?php echo $post_count; ?>"><i class="fa fa-plus"></i> <?php echo $lang['load_more']; ?></button>
		</div>
		<?php } ?>
	</div>
</div>
