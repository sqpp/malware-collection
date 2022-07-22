<?php
require('../config_session.php');

if(!isset($_POST['get_profile'], $_POST['cp'])){
	die();
}
$id = escape($_POST['get_profile']);
$curpage = escape($_POST['cp']);
$user = boomUserInfo($id);
if(empty($user)){
	echo 2;
	die();
}
$user['page'] = $curpage;
$pro_menu = boomTemplate('element/pro_menu', $user);
$room = roomInfo($user['user_roomid']);
?>
<div class="modal_wrap_top modal_top profile_background <?php echo coverClass($user); ?>" <?php echo getCover($user); ?>>
	<div class="brow">
		<div class="bcell">
			<div class="modal_top_menu">
				<div class="bcell_mid hpad15">
				</div>
				<?php if(canEditUser($user, 8)){ ?>
				<div onclick="editUser(<?php echo $user['user_id']; ?>);" class="cover_text modal_top_item">
					<i class="fa fa-edit"></i>
				</div>
				<?php } ?>
				<?php if(canEditUser($user, 8, 1)){ ?>
				<div onclick="getActions(<?php echo $user['user_id']; ?>);" class="cover_text modal_top_item">
					<i class="fa fa-flash"></i>
				</div>
				<?php } ?>
				<?php if(!mySelf($user['user_id']) && !empty($pro_menu)){ ?>
				<div id="promenu" onclick="loadProMenu(<?php echo $user['user_id']; ?>, 'pro_menu');" class="cover_text modal_top_item">
					<i class="fa fa-bars"></i>
					<div id="pro_menu" class="add_shadow fmenu">
						<?php echo $pro_menu; ?>
					</div>
				</div>
				<?php } ?>
				<div class="modal_top_menu_empty">
				</div>
				<div class="cancel_modal cover_text modal_top_item">
					<i class="fa fa-times"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="brow">
		<div class="bcell_bottom profile_top">
			<div class="btable_auto">
				<div id="proav" class="profile_avatar" data="<?php echo $user['user_tumb']; ?>" >
					<div class="avatar_spin">
						<img class="fancybox avatar_profile" <?php echo profileAvatar($user['user_tumb']); ?>/>
					</div>
					<?php echo userActive($user, 'state_profile'); ?>
				</div>
				<div class="profile_tinfo cover_text">
					<div class="pdetails">
						<div class="pdetails_text pro_rank">
							<?php echo proRanking($user, 'pro_ranking'); ?>
						</div>
					</div>
					<div class="pdetails">
						<div class="pdetails_text pro_name">
							<?php echo $user['user_name']; ?>
						</div>
					</div>
					<div class="pdetails">
						<?php if(!empty($user['user_mood'])){ ?>
						<div class="pdetails_text pro_mood bellips">
							<?php echo $user['user_mood']; ?>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if(isRegmute($user) && !isMuted($user) && !isBanned($user)){ ?>
<div class="im_muted profile_info_box theme_btn">
	<i class="fa fa-exclamation-circle"></i> <?php echo $lang['user_regmuted']; ?>
</div>
<?php } ?>
<?php if(isMuted($user) && !isBanned($user)){ ?>
<div class="im_muted profile_info_box warn_btn">
	<i class="fa fa-exclamation-circle"></i> <?php echo $lang['user_muted']; ?>
</div>
<?php } ?>
<?php if(isBanned($user)){ ?>
<div class="im_banned profile_info_box delete_btn">
	<i class="fa fa-exclamation-circle"></i> <?php echo $lang['user_banned']; ?>
</div>
<?php } ?>
<div class="modal_menu">
	<ul>
		<li class="modal_menu_item modal_selected" data="mprofilemenu" data-z="profile_info"><?php echo $lang['about_me']; ?></li>
		<?php if(!isGuest($user) && !isBot($user)){ ?>
		<li class="modal_menu_item" data="mprofilemenu" onclick="lazyBoom('profile_friends');" data-z="profile_friends"><?php echo $lang['friends']; ?></li>
		<?php } ?>
		<?php if(!isBot($user)){ ?> 
		<li class="modal_menu_item" data="mprofilemenu" data-z="prodetails"><?php echo $lang['main_info']; ?></li>
		<?php } ?>
	</ul>
</div>
<div id="mprofilemenu">
	<div class="modal_zone pad25 tpad15" id="profile_info">
		<div class="clearbox">
			<?php if(boomAge($user['user_age'])){ ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['age']; ?></div>
				<div class="listing_text"><?php echo getUserAge($user['user_age']); ?></div>
			</div>
			<?php } ?>
			<?php if(boomSex($user['user_sex'])){ ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['gender']; ?></div>
				<div class="listing_text"><?php echo getGender($user['user_sex']); ?></div>
			</div>
			<?php } ?>
			<?php if(verified($user)){ ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['account_status']; ?></div>
				<div class="listing_text"><?php echo $lang['verified']; ?></div>
			</div>
			<?php } ?>
			<?php if(usercountry($user['country'])){ ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['country']; ?></div>
				<div class="listing_text"><?php echo countryName($user['country']); ?></div>
			</div>
			<?php } ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['join_chat']; ?></div>
				<div class="listing_text"><?php echo longDate($user['user_join']); ?></div>
			</div>
			<?php if(userInRoom($user) && !empty($room)){ ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['user_room']; ?></div>
				<div class="listing_text"><?php echo $room['room_name']; ?></div>
			</div>
			<?php } ?>
		</div>
		<?php if($user['user_about'] != ''){ ?>
		<div>
			<div class="listing_element info_pro">
				<div class="listing_title"><?php echo $lang['about_me']; ?></div>
				<div class="listing_text"><?php echo boomFormat($user['user_about']); ?></div>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php if(!isBot($user)){ ?>
	<div class="hide_zone  pad25 tpad15 modal_zone" id="prodetails">
		<div class="clearbox">
			<?php if(isVisible($user)){ ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['last_seen']; ?></div>
				<div class="listing_text"><?php echo longDateTime($user['last_action']); ?></div>
			</div>
			<?php } ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['language']; ?></div>
				<div class="listing_text"><?php echo $user['user_language']; ?></div>
			</div>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['user_theme']; ?></div>
				<div class="listing_text"><?php echo boomUserTheme($user); ?></div>
			</div>
			<?php if(canViewTimezone($user)){ ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['user_timezone']; ?></div>
				<div class="listing_text"><?php echo userTime($user); ?></div>
			</div>
			<?php } ?>
			<?php if(canViewEmail($user)){ ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['email']; ?></div>
				<div class="listing_text"><?php echo $user['user_email']; ?></div>
			</div>
			<?php } ?>
			<?php if(canViewIp($user)){ ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['ip']; ?></div>
				<div class="listing_text"><?php echo $user['user_ip']; ?></div>
			</div>
			<?php } ?>
			<?php if(canViewId($user)){ ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['user_id']; ?></div>
				<div class="listing_text"><?php echo $user['user_id']; ?></div>
			</div>
			<?php } ?>
			<?php if(canEditUser($user, 8, 1)){ ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['other_account']; ?></div>
				<div class="listing_text"><?php echo sameAccount($user); ?></div>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>
	<?php if(!isGuest($user) && !isBot($user)){ ?>
	<div class="hide_zone pad20 modal_zone" id="profile_friends">
		<?php echo findFriend($user); ?>
		<div class="clear"></div>
	</div>
	<?php } ?>
</div>