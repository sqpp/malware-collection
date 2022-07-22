<?php
require('../config_session.php');
?>
<div id="my_profile_top" class="modal_wrap_top modal_top profile_background <?php echo coverClass($data); ?>" <?php echo getCover($data); ?>>
	<div class="brow">
		<div class="bcell">
			<div class="modal_top_menu">
				<div class="bcell_mid">
				</div>
				<?php if(canCover()){ ?>
				<div class="cover_menu">
					<div class="cover_item_wrap lite_olay">
						<div class="cover_item delete_cover" onclick="deleteCover();">
							<i id="cover_button" class="fa fa-times"></i>
						</div>
						<div class="cover_item add_cover">
								<i id="cover_icon" data="fa-camera" class="fa fa-camera"></i>
								<input id="cover_file" class="up_input" onchange="uploadCover();" type="file"/>
						</div>
					</div>
				</div>
				<div class="modal_top_menu_empty">
				</div>
				<?php } ?>
				<div class="cancel_modal modal_top_item cover_text">
					<i class="fa fa-times"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="brow">
		<div class="bcell_bottom profile_top">
			<div class="btable_auto">
				<div id="proav" class="profile_avatar" data="<?php echo $data['user_tumb']; ?>" >
					<div class="avatar_spin">
						<img class="fancybox avatar_profile" <?php echo profileAvatar($data['user_tumb']); ?>/>
					</div>
					<?php if(canAvatar()){ ?>
					<div class="avatar_control olay">
						<div class="avatar_button" onclick="deleteAvatar();" id="delete_avatar">
							<i class="fa fa-times"></i>
						</div>
						<div id="avatarupload" class="avatar_button">
							<i id="avat_icon" data="fa-camera" class="fa fa-camera"></i>
							<input id="avatar_image" class="up_input" onchange="uploadAvatar();" type="file">
						</div>
					</div>
					<?php } ?>
				</div>
				<div class="profile_tinfo">
					<div class="pdetails">
						<div id="pro_name" class="pdetails_text pro_name cover_text">
							<?php echo $data['user_name']; ?>
						</div>
					</div>
					<?php if(canMood()){ ?>
					<div class="pdetails">
						<div id="pro_mood" class="pdetails_text pro_mood cover_text bellips">
							<?php echo getMood($data); ?>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if(!isSecure($data) && isMember($data)){ ?>
<div id="secure_account_warn" onclick="openSecure();" class="profile_info_box ok_btn">
	<i class="fa fa-exclamation-circle"></i> <?php echo $lang['secure_account']; ?>
</div>
<?php } ?>
<?php if(guestCanRegister()){ ?>
<div id="secure_account_warn" onclick="openGuestRegister();" class="profile_info_box ok_btn">
	<i class="fa fa-exclamation-circle"></i> <?php echo $lang['register_guest']; ?>
</div>
<?php } ?>
<?php if(userDelete($data)){ ?>
<div id="delete_warn" class="pad15 warn_btn">
	<p class="text_xsmall">
	<span><?php echo str_replace('%date%', longDate($data['user_delete']), $lang['close_warning']); ?></span> 
	<span onclick="cancelDelete();" class="link_like"><?php echo $lang['cancel_request']; ?></span>
	</p>
</div>
<?php } ?>
<div class="modal_menu">
	<ul>
		<li class="modal_menu_item modal_selected" data="meditprofile" data-z="personal_more"><?php echo $lang['account']; ?></li>
	</ul>
</div>
<div id="meditprofile">
	<div class="modal_zone pad25" id="personal_more">
		<div class="clearbox">
			<?php if(canInfo()){ ?>
			<div onclick="changeInfo();" class="listing_half_element">
				<i class="fa fa-address-book-o listing_icon"></i><?php echo $lang['edit_info']; ?>
			</div>
			<?php } ?>
			<?php if(canAbout()){ ?>
			<div onclick="changeAbout();" class="listing_half_element">
				<i class="fa fa-question-circle listing_icon"></i><?php echo $lang['edit_about']; ?>
			</div>
			<?php } ?>
			<?php if(canName()){ ?>
			<div onclick="changeUsername();" class="listing_half_element">
				<i class="fa fa-edit listing_icon"></i><?php echo $lang['edit_username']; ?>
			</div>
			<?php } ?>
			<?php if(canNameColor()){ ?>
			<div onclick="changeColor();" class="listing_half_element">
				<i class="fa fa-paint-brush listing_icon"></i><?php echo $lang['edit_color']; ?>
			</div>
			<?php } ?>
			<?php if(canMood()){ ?>
			<div onclick="changeMood();" class="listing_half_element">
				<i class="fa fa-pencil listing_icon"></i><?php echo $lang['edit_mood']; ?>
			</div>
			<?php } ?>
			<?php if($data['verified'] == 0 && canVerify()){ ?>
			<div onclick="getVerify();" class="listing_half_element">
				<i class="fa fa-check listing_icon"></i><?php echo $lang['verify_account']; ?>
			</div>
			<?php } ?>
			<?php if(isMember($data)){ ?>
			<div onclick="getFriends();" class="listing_half_element">
				<i class="fa fa-user-plus listing_icon"></i><?php echo $lang['manage_friends']; ?>
			</div>
			<?php } ?>
			<div onclick="getIgnore();" class="listing_half_element">
				<i class="fa fa-ban listing_icon"></i><?php echo $lang['manage_ignores']; ?>
			</div>
			<div onclick="getSoundSetting();" class="listing_half_element">
				<i class="fa fa-volume-up listing_icon"></i><?php echo $lang['sound_settings']; ?>
			</div>
			<?php if(canTheme()){ ?>
			<div onclick="getDisplaySetting();" class="listing_half_element">
				<i class="fa fa-desktop listing_icon"></i><?php echo $lang['theme_settings']; ?>
			</div>
			<?php } ?>
			<div onclick="getPrivateSettings();" class="listing_half_element">
				<i class="fa fa-comments listing_icon"></i><?php echo $lang['private_settings']; ?>
			</div>
			<div onclick="getLocation();" class="listing_half_element">
				<i class="fa fa-globe listing_icon"></i><?php echo $lang['lang_location']; ?>
			</div>
			<?php if(isMember($data) && isSecure($data)){ ?>
			<div onclick="getEmail();" class="listing_half_element">
				<i class="fa fa-envelope listing_icon"></i><?php echo $lang['edit_email']; ?>
			</div>
			<div onclick="getPassword();" class="listing_half_element">
				<i class="fa fa-key listing_icon"></i><?php echo $lang['change_password']; ?>
			</div>
			<?php } ?>
			<?php if(!boomAllow(11) && !userDelete($data) && !isBot($data) && isSecure($data)){ ?>
			<div id="del_account_btn" onclick="getDeleteAccount();" class="listing_half_element">
				<i class="fa fa-trash listing_icon"></i><?php echo $lang['close_account']; ?>
			</div>
			<?php } ?>
		</div>
	</div>
</div>