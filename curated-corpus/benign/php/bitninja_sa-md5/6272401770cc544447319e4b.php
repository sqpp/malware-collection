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
require_once("../config_session.php");

$notify_limit = 50;
function privateNotification($p){
$add_count = '';
if($p['private_count'] > 0){
	$add_count = '<div class="ulist_notify"><span class="pm_notify private_count bnotify">' . $p['private_count'] . '</span></div>';
}
return '<div class="ulist_item" >
			<div class="ulist_avatar">
				<img src="' . myAvatar($p['user_tumb']) . '"/>
			</div>
			<div class="ulist_name gprivate" data="' . $p['user_id'] . '" value="' . $p['user_name'] . '" data-av="' . myAvatar($p['user_tumb']) . '">
				<p class="username ' . myColor($p) . '">' . $p["user_name"] . '</p>
			</div>
			' . $add_count . '
			<div data="' . $p['hunter'] . '" class="ulist_option delete_private">
				<i class="fa fa-times"></i>
			</div>
		</div>';
}

$private = $mysqli->query("
	SELECT count(*) as private_count,  MAX(a.time) AS time, a.hunter, b.user_id, b.user_name, b.user_color, b.user_tumb from boom_private a inner join boom_users b on a.hunter = b.user_id 
	WHERE status = 0 AND target = {$data['user_id']} 
	GROUP BY hunter,user_id,user_name,user_color,user_tumb
	ORDER BY time ASC
");


$check = array();
$new_count = 0;
$private_list = '';
$add_not = '';
$private_list = '';
$priv = 0;
if ($private->num_rows > 0){
	while ($my_private= $private->fetch_assoc()){
		if(!in_array($my_private['user_id'], $check)){
			array_push($check, $my_private['user_id']);
			$new_count++;
			$private_list .=  privateNotification($my_private);
		}
		$priv++;
	}
}
if($new_count < $notify_limit){
	if(!empty($check)){
		$check = implode(", ", $check);
		$check_again = $notify_limit - $new_count;
		$add_not = "AND hunter NOT IN ($check)";
	}
	else {
		$check_again = $notify_limit;
	}
	
	$get_other = $mysqli->query("
		SELECT MAX(a.time) AS time, a.hunter, b.user_id, b.user_name, b.user_color, b.user_tumb from boom_private a inner join boom_users b on a.hunter = b.user_id 
		WHERE target = {$data['user_id']} AND status < 3  AND hunter != '{$data['user_id']}' $add_not
		GROUP BY hunter,user_id,user_name,user_color,user_tumb
		ORDER BY time DESC
	");

	if($get_other->num_rows > 0){
		while ($other_private= $get_other->fetch_assoc()){
			$other_private['private_count'] = 0;
			$private_list .= privateNotification($other_private);
			$priv++;
		}
	}
}
if($private_list == '') {
	$private_list .= emptyZone($lang['no_unread_private']);
}
?>
<div class="modal_top">
	<?php if($priv > 0){ ?>
	<div onclick="clearPrivateList();" class="bcell_mid hpad10 bold private_cleaning">
		<i class="fa fa-trash"></i> <?php echo $lang['clear']; ?>
	</div>
	<?php } ?>
	<div class="modal_top_empty bold">
	</div>
	<div class="modal_top_element close_modal">
		<i class="fa fa-times"></i>
	</div>
</div>
<div class="ulist_container">
	<?php echo $private_list; ?>
</div>