<?php
require_once('../config_session.php');
require(BOOM_PATH . '/system/language/' . $data['user_language'] . '/notification.php');

$find_notify = $mysqli->query("
SELECT boom_notification.*, boom_users.user_name, boom_users.user_tumb, boom_users.user_color
FROM boom_notification
LEFT JOIN boom_users
ON boom_notification.notifier = boom_users.user_id
WHERE boom_notification.notified = '{$data['user_id']}'
ORDER BY boom_notification.notify_date DESC LIMIT 40
");

function renderNotification($notify){
	global $data, $nlang, $lang;
	$ntext = $nlang[$notify['notify_type']];
	$ntext = str_replace(
		array(
			'%rank%',
			'%roomrank%',
			'%delay%',
			'%data%',
			'%data2%'
		),
		array(
			rankTitle($notify['notify_rank']),
			roomRankTitle($notify['notify_rank']),
			boomRenderMinutes($notify['notify_delay']),
			$notify['notify_custom'],
			$notify['notify_custom2']
		),
		$ntext
	);
	return $ntext;
}

$notify_list = '';
if($find_notify->num_rows > 0){
	while($notify = $find_notify->fetch_assoc()){
		$view = '';
		$add_click = '';
		$add_to_date = '';
		$notify_message = '';
		if($notify['notify_view'] == 0){
			$view = '<i class="fa fa-circle theme_color"></i>';
		}
		$notify_message = renderNotification($notify);
		if($notify['notify_source'] == 'post' && $notify['notify_id'] > 0){
			$add_click = 'onclick="showPost(this, \'' . $notify['notify_id'] . '\');"';
		}
		if($notify['notify_type'] == 'like' && !empty($notify['notify_custom'])){
			$add_to_date = likeType($notify['notify_custom'], 'notify_reaction') . ' ';
		}
		$notify_list .= '<div ' . $add_click . ' class="list_element notify_item">
							<div class="notify_avatar">
								<img src="' . myAvatar($notify['user_tumb']) . '"/>
							</div>
							<div class="notify_details">
								<p class="hnotify username ' . myColor($notify) . '">' . $notify['user_name'] . '</p>
								<p class="text_small sub_text notify_text" >' . $notify_message . '</p>
								<p class="text_micro date date_notify">' . $add_to_date . displayDate($notify['notify_date']) . '</p>
							</div>
							<div class="notify_status">
								' . $view . '
							</div>
						</div>';
	}
	$mysqli->query("UPDATE boom_notification SET notify_view = 1 WHERE notified = '{$data['user_id']}'");
}
else {
	$notify_list .= '<div class="pad_box">' . emptyZone($lang['no_notify']) . '</div>';
}
?>
<div id="notify_list">
	<div id="notify_content">
		<?php echo $notify_list; ?>
	</div>
</div>