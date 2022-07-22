<?php
/**
*
* @package mChat
* @version $Id: mchat.php
* @copyright (c) 2010 RMcGirr83 ( http://www.rmcgirr83.org/ )
* @copyright (c) djs596 ( http://djs596.com/ ), (c) Stokerpiller ( http://www.phpbb3bbcodes.com/ )
* @copyright (c) By Shapoval Andrey Vladimirovich (AllCity) ~ http://allcity.net.ru/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
**/

/**
* DO NOT CHANGE (IN_PHPBB)!
*/
if(!defined('MCHAT_INCLUDE'))
{
  // Custom Page code from http://www.phpbb.com/kb/article/add-a-new-custom-page-to-phpbb/
  define('IN_PHPBB', true);
  $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
  $phpEx = substr(strrchr(__FILE__, '.'), 1);
  include($phpbb_root_path.'common.'.$phpEx);
  $mchat_include_index = false;
  // Start session management.
  $user->session_begin();
  $auth->acl($user->data);
  $user->setup();
}

// Add lang file
$user->add_lang(array('mods/mchat_lang', 'viewtopic', 'posting'));

//chat enabled
if (!$config['mchat_enable'])
{
	trigger_error($user->lang['MCHAT_ENABLE'], E_USER_NOTICE);
}
// check for mod installed
if (empty($config['mchat_version']))
{
	if($user->data['user_type'] == USER_FOUNDER)
	{
		$installer =  append_sid("{$phpbb_root_path}mchat_install.$phpEx");
		$message = sprintf($user->lang['MCHAT_NOT_INSTALLED'], '<a href="' . $installer . '">', '</a>');
	}
	else
	{
		$message = $user->lang['MCHAT_NOTINSTALLED_USER'];
	}
	trigger_error ($message);
}
//  avatars
if (!function_exists('get_user_avatar'))
{
	include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
}
// Get the config entries.
if (!function_exists('mchat_cache'))
{
	include($phpbb_root_path . 'includes/functions_mchat.' . $phpEx);
}
if (($config_mchat = $cache->get('_mchat_config')) === false)
{
	mchat_cache();
}
$config_mchat = $cache->get('_mchat_config');
// Access rights 
$mchat_allow_bbcode	= ($config['allow_bbcode'] && $auth->acl_get('u_mchat_bbcode')) ? true : false;
$mchat_smilies = ($config['allow_smilies'] && $auth->acl_get('u_mchat_smilies')) ? true : false;
$mchat_urls = ($config['allow_post_links'] && $auth->acl_get('u_mchat_urls')) ? true : false;
$mchat_ip = ($auth->acl_get('u_mchat_ip')) ? true : false;
$mchat_add_mess	= ($auth->acl_get('u_mchat_use')) ? true : false;
$mchat_view	= ($auth->acl_get('u_mchat_view')) ? true : false;
$mchat_no_flood	= ($auth->acl_get('u_mchat_flood_ignore')) ? true : false;
$mchat_read_archive = ($auth->acl_get('u_mchat_archive')) ? true : false;
$mchat_founder = ($user->data['user_type'] == USER_FOUNDER) ? true : false;
$mchat_session_time = !empty($config_mchat['timeout']) ? $config_mchat['timeout'] : 3600;// you can change this number to a greater number for longer chat sessions
$mchat_rules = (!empty($config_mchat['rules']) || isset($user->lang[strtoupper('mchat_rules')])) ? true : false;
$mchat_avatars = (!empty($config_mchat['avatars']) && $user->optionget('viewavatars') && $user->data['user_mchat_avatars']) ? true : false;

// needed variables
// Request options.
$mchat_mode	= request_var('mode', '');
$mchat_read_mode = $mchat_archive_mode = $mchat_custom_page = $mchat_no_message = false;
// set redirect if on index or custom page
$on_page = defined('MCHAT_INCLUDE') ? 'index' : 'mchat';

// Request mode...
switch ($mchat_mode)
{
	// rules popup..
	case 'rules';
		// If the rules are defined in the language file use them, else just use the entry in the database
		if ($mchat_rules || isset($user->lang[strtoupper('mchat_rules')]))
		{
			if(isset($user->lang[strtoupper('mchat_rules')]))
			{
				$template->assign_var('MCHAT_RULES', $user->lang[strtoupper('mchat_rules')]);
			}
			else
			{
				$mchat_rules = $config_mchat['rules'];
				$mchat_rules = explode("\n", $mchat_rules);

				foreach ($mchat_rules as $mchat_rule)
				{
					$mchat_rule = htmlspecialchars($mchat_rule);
					$template->assign_block_vars('rule', array(			
						'MCHAT_RULE' => $mchat_rule,
					));
				}				
			}
			// Output the page
			page_header($user->lang['MCHAT_HELP']);
		
			$template->set_filenames(array(
				'body' => 'mchat_rules.html')
			);		
		
			page_footer();
		}
		else
		{
			// Show no rules
			trigger_error('NO_MCHAT_RULES', E_USER_NOTICE);
		}
		
	break;
	// whois function..
	case 'whois';

		// Must have auths
		if ($mchat_mode == 'whois' && $mchat_ip)
		{	
			// function already exists..
			if (!function_exists('user_ipwhois'))
			{
				include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
			}
			
			$user_ip = request_var('ip', '');
			
			$template->assign_var('WHOIS', user_ipwhois($user_ip));

			// Output the page
			page_header($user->lang['WHO_IS_ONLINE']);

			$template->set_filenames(array(
				'body' => 'viewonline_whois.html')
			);

			page_footer();	
		}
		else
		{
			// Show not authorized
			trigger_error('NO_AUTH_OPERATION', E_USER_NOTICE);
		}
	break;
	// Clean function...
	case 'clean';
			
		// User logged in?
		if(!$user->data['is_registered'] || !$mchat_founder)
		{
			if(!$user->data['is_registered'])
			{
				// Login box...
				login_box('', $user->lang['LOGIN']);
			}
			else if (!$mchat_founder)
			{
				// Show not authorized
				trigger_error('NO_AUTH_OPERATION', E_USER_NOTICE);
			}			
		}
		
		$mchat_redirect = request_var('redirect', '');
		$mchat_redirect = ($mchat_redirect == 'index') ? append_sid("{$phpbb_root_path}index.$phpEx") : append_sid("{$phpbb_root_path}mchat.$phpEx#mChat");	
		
		if(confirm_box(true))
		{
			// Run cleaner
			$sql = 'TRUNCATE TABLE ' . MCHAT_TABLE;
			$db->sql_query($sql);
				
			meta_refresh(3, $mchat_redirect);
			trigger_error($user->lang['MCHAT_CLEANED'].'<br /><br />'.sprintf($user->lang['RETURN_PAGE'], '<a href="'.$mchat_redirect.'">', '</a>'));
		}
		else
		{
			// Display confirm box
			confirm_box(false, $user->lang['MCHAT_DELALLMESS']);
		}
		redirect($mchat_redirect);
	break;

	// Archive function...
	case 'archive';
	
		if (!$mchat_read_archive || !$mchat_view)
		{
			// redirect to correct page
			$mchat_redirect = append_sid("{$phpbb_root_path}index.$phpEx");
			// Redirect to previous page
			meta_refresh(3, $mchat_redirect);
			trigger_error($user->lang['MCHAT_NOACCESS_ARCHIVE'].'<br /><br />'.sprintf($user->lang['RETURN_PAGE'], '<a href="' . $mchat_redirect . '">', '</a>'), E_USER_NOTICE);
		}
		
		if ($config['mchat_enable'] && $mchat_read_archive && $mchat_view)
		{
			// prune the chats if necessary and amount in ACP not empty
			if ($config_mchat['prune_enable'] && $config_mchat['prune_num'] > 0)
			{
				mchat_prune((int) $config_mchat['prune_num']);
			}
					
			// Reguest...
			$mchat_archive_start = request_var('start', 0);
			$sql_where = $user->data['user_mchat_topics'] ? '' : 'WHERE m.forum_id = 0';
			// Message row
			$sql = 'SELECT m.*, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height
				FROM ' . MCHAT_TABLE . ' m
					LEFT JOIN ' . USERS_TABLE . ' u ON m.user_id = u.user_id
				' . $sql_where . '
				ORDER BY m.message_id DESC';
			$result = $db->sql_query_limit($sql, (int) $config_mchat['archive_limit'], $mchat_archive_start);
			$rows = $db->sql_fetchrowset($result);
							
			foreach($rows as $row)
			{
				// auth check
				if ($row['forum_id'] != 0 && !$auth->acl_get('f_read', $row['forum_id']))
				{
					continue;
				}	
				// edit, delete and permission auths
				$mchat_ban = ($auth->acl_get('a_authusers') && $user->data['user_id'] != $row['user_id']) ? true : false;
				$mchat_edit = ($auth->acl_get('u_mchat_edit') && ($auth->acl_get('m_') || $user->data['user_id'] == $row['user_id'])) ? true : false;
				$mchat_del = ($auth->acl_get('u_mchat_delete') && ($auth->acl_get('m_') || $user->data['user_id'] == $row['user_id'])) ? true : false;
				$mchat_avatar = $row['user_avatar'] ? get_user_avatar($row['user_avatar'], $row['user_avatar_type'], ($row['user_avatar_width'] > $row['user_avatar_height']) ? 40 : (40 / $row['user_avatar_height']) * $row['user_avatar_width'], ($row['user_avatar_height'] > $row['user_avatar_width']) ? 40 : (40 / $row['user_avatar_width']) * $row['user_avatar_height']) : '';
				$message_edit = $row['message'];
				decode_message($message_edit, $row['bbcode_uid']);
				$message_edit = str_replace('"', '&quot;', $message_edit); // Edit Fix ;)
				$template->assign_block_vars('mchatrow', array(
					'MCHAT_ALLOW_BAN'		=> $mchat_ban,
					'MCHAT_ALLOW_EDIT'		=> $mchat_edit,
					'MCHAT_ALLOW_DEL'		=> $mchat_del,
					'MCHAT_USER_AVATAR'		=> $mchat_avatar,					
					'U_VIEWPROFILE'			=> ($row['user_id'] != ANONYMOUS) ? append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $row['user_id']) : '',
					'MCHAT_MESSAGE_EDIT'	=> $message_edit,
					'MCHAT_MESSAGE_ID'		=> $row['message_id'],
					'MCHAT_USERNAME_FULL'	=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'], $user->lang['GUEST']),
					'MCHAT_USERNAME'		=> get_username_string('username', $row['user_id'], $row['username'], $row['user_colour'], $user->lang['GUEST']),
					'MCHAT_USERNAME_COLOR'	=> get_username_string('colour', $row['user_id'], $row['username'], $row['user_colour'], $user->lang['GUEST']),
					'MCHAT_USER_IP'			=> $row['user_ip'],
					'MCHAT_U_WHOIS'			=> append_sid("{$phpbb_root_path}mchat.$phpEx", 'mode=whois&amp;ip=' . $row['user_ip']),
					'MCHAT_U_BAN'			=> append_sid("{$phpbb_root_path}adm/index.$phpEx" ,'i=permissions&amp;mode=setting_user_global&amp;user_id[0]=' . $row['user_id'], true, $user->session_id),
					'MCHAT_MESSAGE'			=> generate_text_for_display($row['message'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']),
					'MCHAT_TIME'			=> $user->format_date($row['message_time'], $config_mchat['date']),
					'MCHAT_CLASS'			=> ($row['message_id'] % 2) ? 1 : 2
				));				
			}
			$db->sql_freeresult($result);
			// Write no message
			if (empty($rows))
			{
				$mchat_no_message = true;			
			}
		}

		// Run query again to get the total message rows...
		$sql = 'SELECT COUNT(message_id) AS mess_id FROM ' . MCHAT_TABLE;
		$result = $db->sql_query($sql);
		$mchat_total_message = $db->sql_fetchfield('mess_id');
		$db->sql_freeresult($result);
		// Page list function...
		$template->assign_vars(array(
			'MCHAT_PAGE_NUMBER'		=> on_page($mchat_total_message, (int) $config_mchat['archive_limit'], $mchat_archive_start),
			'MCHAT_TOTAL_MESSAGES'	=> sprintf($user->lang['MCHAT_TOTALMESSAGES'], $mchat_total_message),
			'MCHAT_PAGINATION'		=> generate_pagination(append_sid("{$phpbb_root_path}mchat.$phpEx", 'mode=archive'), $mchat_total_message, (int) $config_mchat['archive_limit'], $mchat_archive_start, true)
		));		
		
		//add to navlinks
		$template->assign_block_vars('navlinks', array(
			'FORUM_NAME'         => $user->lang['MCHAT_ARCHIVE_PAGE'],
			'U_VIEW_FORUM'      => append_sid("{$phpbb_root_path}mchat.$phpEx", 'mode=archive'))
		);			
		// If archive mode request set true
		$mchat_archive_mode = true;
		$old_mode = 'archive';

	break;
 
	// Read function...
	case 'read':

		// If mChat disabled or user can't view the chat
		if (!$config['mchat_enable'] || !$mchat_view)
		{
			// Forbidden (for jQ AJAX request)
			header('HTTP/1.0 403 Forbidden');
			exit_handler();
		}
		// Request
		$mchat_message_last_id = request_var('message_last_id', 0);
		$sql_and = $user->data['user_mchat_topics'] ? '' : 'AND m.forum_id = 0';
		$sql = 'SELECT m.*, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height
				FROM ' . MCHAT_TABLE . ' m, ' . USERS_TABLE . ' u
				WHERE m.user_id = u.user_id
				AND m.message_id > ' . (int) $mchat_message_last_id . '
				' . $sql_and . '
				ORDER BY m.message_id DESC';		
		$result = $db->sql_query_limit($sql, (int) $config_mchat['message_limit']);
		$rows = $db->sql_fetchrowset($result);
		
		// Reverse the array wanting messages appear in reverse
		$rows = array_reverse($rows);
				
		foreach($rows as $row)
		{
			// auth check
			if ($row['forum_id'] != 0 && !$auth->acl_get('f_read', $row['forum_id']))
			{
				continue;
			}	
			// edit auths
			if ($user->data['user_id'] == ANONYMOUS && $user->data['user_id'] == $row['user_id'])
			{
				$chat_auths = $user->data['session_ip'] == $row['user_ip'] ? true : false;
			}
			else
			{
				$chat_auths = $user->data['user_id'] == $row['user_id'] ? true : false;
			}			
			// edit, delete and permission auths
			$mchat_ban = ($auth->acl_get('a_authusers') && $user->data['user_id'] != $row['user_id']) ? true : false;
			$mchat_edit = ($auth->acl_get('u_mchat_edit') && ($auth->acl_get('m_') || $chat_auths)) ? true : false;
			$mchat_del = ($auth->acl_get('u_mchat_delete') && ($auth->acl_get('m_') || $chat_auths)) ? true : false;
			$mchat_avatar = $row['user_avatar'] ? get_user_avatar($row['user_avatar'], $row['user_avatar_type'], ($row['user_avatar_width'] > $row['user_avatar_height']) ? 40 : (40 / $row['user_avatar_height']) * $row['user_avatar_width'], ($row['user_avatar_height'] > $row['user_avatar_width']) ? 40 : (40 / $row['user_avatar_width']) * $row['user_avatar_height']) : '';
			$message_edit = $row['message'];
			decode_message($message_edit, $row['bbcode_uid']);
			$message_edit = str_replace('"', '&quot;', $message_edit);
			$template->assign_block_vars('mchatrow', array(
				'MCHAT_ALLOW_BAN'		=> $mchat_ban,
				'MCHAT_ALLOW_EDIT'		=> $mchat_edit,
				'MCHAT_ALLOW_DEL'		=> $mchat_del,			
				'MCHAT_USER_AVATAR'		=> $mchat_avatar,					
				'U_VIEWPROFILE'			=> ($row['user_id'] != ANONYMOUS) ? append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $row['user_id']) : '',				
				'MCHAT_MESSAGE_EDIT'	=> $message_edit,
				'MCHAT_MESSAGE_ID' 		=> $row['message_id'],
				'MCHAT_USERNAME_FULL'	=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'], $user->lang['GUEST']),
				'MCHAT_USERNAME'		=> get_username_string('username', $row['user_id'], $row['username'], $row['user_colour'], $user->lang['GUEST']),
				'MCHAT_USERNAME_COLOR'	=> get_username_string('colour', $row['user_id'], $row['username'], $row['user_colour'], $user->lang['GUEST']),
				'MCHAT_USER_IP'			=> $row['user_ip'],
				'MCHAT_U_WHOIS'			=> append_sid("{$phpbb_root_path}mchat.$phpEx", 'mode=whois&amp;ip=' . $row['user_ip']),
				'MCHAT_U_BAN'			=> append_sid("{$phpbb_root_path}adm/index.$phpEx" ,'i=permissions&amp;mode=setting_user_global&amp;user_id[0]=' . $row['user_id'], true, $user->session_id),
				'MCHAT_MESSAGE'			=> generate_text_for_display($row['message'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']),
				'MCHAT_TIME'			=> $user->format_date($row['message_time'], $config_mchat['date']),
				'MCHAT_CLASS'			=> ($row['message_id'] % 2) ? 1 : 2
			));			
		}
		$db->sql_freeresult($result);
		
		// Write no message
		if (empty($rows))
		{
			$mchat_no_message = true;
		}

		// If read mode request set true
		$mchat_read_mode = true;

	break;

	// Stats function...
	case 'stats':

		// If mChat disabled or user can't view the chat
		if (!$config['mchat_enable'] || !$mchat_view || !$config_mchat['whois'])
		{
			// Forbidden (for jQ AJAX request)
			header('HTTP/1.0 403 Forbidden');	
			exit_handler();
		}

		$mchat_stats = mchat_users($mchat_session_time);
			
		if(!empty($mchat_stats['online_userlist']))
		{
			$message = '<div class="mChatStats" id="mChatStats"><a href="#" onclick="mChat.toggle(\'UserList\'); return false;">' . $mchat_stats['mchat_users_count'] . '</a>&nbsp;' . $mchat_stats['refresh_message'] . '<br /><span id="mChatUserList" style="display: none; float: left;">' . $mchat_stats['online_userlist'] . '</span></div>';
		}
		else
		{
			$message = '<div class="mChatStats" id="mChatStats">' . $user->lang['MCHAT_NO_CHATTERS'] . '&nbsp;' . $mchat_stats['refresh_message'] . '</div>';
		}

		echo $message;
		exit_handler();
	break;
	
	// Add function...
	case 'add':
	
		// If mChat disabled
		if (!$config['mchat_enable'] || !$mchat_add_mess)
		{
			// Forbidden (for jQ AJAX request)
			header('HTTP/1.0 403 Forbidden');
			exit_handler();
		}
				
		// Reguest...
		$message = utf8_normalize_nfc(request_var('message', '', true));
		
		// must have something other than bbcode in the message		
		if (empty($mchatregex))
		{
			//let's strip all the bbcode
			$mchatregex = '#\[/?[^\[\]]+\]#mi';
		}
		$message_chars = preg_replace($mchatregex, '', $message);
		$message_chars = (utf8_strlen(trim($message_chars)) > 0) ? true : false;
			
		if (!$message || !$message_chars)
		{
			// Not Implemented (for jQ AJAX request)
			header('HTTP/1.0 501 Not Implemented');
			exit_handler();
		}

		// Flood control
		if (!$mchat_no_flood && $config_mchat['flood_time'])
		{
			$mchat_flood_current_time = time();		
			$sql = 'SELECT message_time FROM ' . MCHAT_TABLE . ' 
				WHERE user_id = ' . (int) $user->data['user_id'] . '
				ORDER BY message_time DESC';
			$result = $db->sql_query_limit($sql, 1);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			if($row['message_time'] > 0 && ($mchat_flood_current_time - $row['message_time']) < (int) $config_mchat['flood_time'])
			{
				// Locked (for jQ AJAX request)
				header('HTTP/1.0 400 Bad Request');
				// Stop running code
				exit_handler();
			}
		}

		// we override the $config['min_post_chars'] entry?
		if ($config_mchat['override_min_post_chars'])
		{
			$old_cfg['min_post_chars'] = $config['min_post_chars'];
			$config['min_post_chars'] = 0;
		}
		//we do the same for the max number of smilies?
		if ($config_mchat['override_smilie_limit'])
		{
			$old_cfg['max_post_smilies'] = $config['max_post_smilies'];
			$config['max_post_smilies'] = 0;
		}
		
		// Add function part code from http://wiki.phpbb.com/Parsing_text
		$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
		generate_text_for_storage($message, $uid, $bitfield, $options, $mchat_allow_bbcode, $mchat_urls, $mchat_smilies);
		// Not allowed bbcodes
		if (!$mchat_allow_bbcode || $config_mchat['bbcode_disallowed'])
		{
			if (!$mchat_allow_bbcode)
			{
				$bbcode_remove = '#\[/?[^\[\]]+\]#mi';
				$message = preg_replace($bbcode_remove, '', $message);
			}
			// disallowed bbcodes
			else if ($config_mchat['bbcode_disallowed'])
			{
				if (empty($bbcode_replace))
				{
					$bbcode_replace = array('#\[(' . $config_mchat['bbcode_disallowed'] . ')[^\[\]]+\]#Usi',
										'#\[/(' . $config_mchat['bbcode_disallowed'] . ')[^\[\]]+\]#Usi',
									);
				}		
				$message = preg_replace($bbcode_replace, '', $message);
			}
		}
		
		$sql_ary = array(
			'forum_id' 			=> 0,
			'post_id'			=> 0,		
			'user_id'			=> $user->data['user_id'],
			'user_ip'			=> $user->data['session_ip'],
			'message' 			=> $message,
			'bbcode_bitfield'	=> $bitfield,
			'bbcode_uid'		=> $uid,
			'bbcode_options'	=> $options,
			'message_time'		=> time()
		);
		$sql = 'INSERT INTO ' . MCHAT_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
		$db->sql_query($sql);
		
		// reset the config settings
		if(isset($old_cfg['min_post_chars']))
		{
			$config['min_post_chars'] = $old_cfg['min_post_chars'];
			unset($old_cfg['min_post_chars']);
		}
		if(isset($old_cfg['max_post_smilies']))
		{
			$config['max_post_smilies'] = $old_cfg['max_post_smilies'];
			unset($old_cfg['max_post_smilies']);
		}
		
		// insert user into the mChat sessions table
		mchat_sessions($mchat_session_time, true);
		// Stop run code!
		exit_handler();
	break;

	// Edit function...
	case 'edit':

		// edit and delete auths
		$mchat_edit = $auth->acl_get('u_mchat_edit') ? true : false;
		$mchat_del = $auth->acl_get('u_mchat_delete') ? true : false;	
		// If mChat disabled and not edit
		if (!$config['mchat_enable'] || !$mchat_edit)
		{
			// Forbidden (for jQ AJAX request)
			header('HTTP/1.0 403 Forbidden');
			exit_handler();
		}
		
		// Reguest...
		$message_id = request_var('message_id', 0);
		$message = utf8_normalize_nfc(request_var('message', '', true));
		
		// stop run code
		if (!$message_id )
		{
			// Forbidden (for jQ AJAX request)
			header('HTTP/1.0 403 Forbidden');
			// Stop running code
			exit_handler();
		}
		
		// must have something other than bbcode in the message
		if (empty($mchatregex))
		{
			//let's strip all the bbcode
			$mchatregex = '#\[/?[^\[\]]+\]#mi';
		}
		$message_chars = preg_replace($mchatregex, '', $message);
		$message_chars = (utf8_strlen(trim($message_chars)) > 0) ? true : false;			
		if (!$message || !$message_chars)
		{
			// Not Implemented (for jQ AJAX request)
			header('HTTP/1.0 501 Not Implemented');
			// Stop running code
			exit_handler();
		}

		// Message limit
		$message = ($config_mchat['max_message_lngth'] != 0 && utf8_strlen($message) >= $config_mchat['max_message_lngth'] + 3) ? utf8_substr($message, 0, $config_mchat['max_message_lngth']).'...' : $message;
		
		// we override the $config['min_post_chars'] entry?
		if ($config_mchat['override_min_post_chars'])
		{
			$old_cfg['min_post_chars'] = $config['min_post_chars'];
			$config['min_post_chars'] = 0;
		}
		//we do the same for the max number of smilies?
		if ($config_mchat['override_smilie_limit'])
		{
			$old_cfg['max_post_smilies'] = $config['max_post_smilies'];
			$config['max_post_smilies'] = 0;
		}
		
		// Edit function part code from http://wiki.phpbb.com/Parsing_text
		$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
		generate_text_for_storage($message, $uid, $bitfield, $options, $mchat_allow_bbcode, $mchat_urls, $mchat_smilies);
		
		// Not allowed bbcodes
		if (!$mchat_allow_bbcode || $config_mchat['bbcode_disallowed'])
		{
			if (!$mchat_allow_bbcode)
			{
				$bbcode_remove = '#\[/?[^\[\]]+\]#mi';
				$message = preg_replace($bbcode_remove, '', $message);
			}
			// disallowed bbcodes
			else if ($config_mchat['bbcode_disallowed'])
			{
				if (empty($bbcode_replace))
				{
					$bbcode_replace = array('#\[(' . $config_mchat['bbcode_disallowed'] . ')[^\[\]]+\]#Usi',
										'#\[/(' . $config_mchat['bbcode_disallowed'] . ')[^\[\]]+\]#Usi',
					);
				}		
				$message = preg_replace($bbcode_replace, '', $message);
			}
		}
		
		$sql_ary = array(
			'message'			=> $message,
			'bbcode_bitfield'	=> $bitfield,
			'bbcode_uid'		=> $uid,
			'bbcode_options'	=> $options
		);
		
		$sql = 'UPDATE ' . MCHAT_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary).' 
			WHERE message_id = ' . $message_id;
		$db->sql_query($sql);
		
		// Message edited...now read it
		$sql = 'SELECT m.*, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height
					FROM ' . MCHAT_TABLE . ' m, ' . USERS_TABLE . ' u
						WHERE m.user_id = u.user_id
					AND m.message_id = ' . (int) $message_id . '
				ORDER BY m.message_id DESC';		
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$message_edit = $row['message'];
		
		decode_message($message_edit, $row['bbcode_uid']);
		$message_edit = str_replace('"', '&quot;', $message_edit); // Edit Fix ;)
		$mchat_ban = ($auth->acl_get('a_authusers') && $user->data['user_id'] != $row['user_id']) ? true : false;
 		$mchat_avatar = $row['user_avatar'] ? get_user_avatar($row['user_avatar'], $row['user_avatar_type'], ($row['user_avatar_width'] > $row['user_avatar_height']) ? 40 : (40 / $row['user_avatar_height']) * $row['user_avatar_width'], ($row['user_avatar_height'] > $row['user_avatar_width']) ? 40 : (40 / $row['user_avatar_width']) * $row['user_avatar_height']) : '';   
		$template->assign_block_vars('mchatrow', array(
			'MCHAT_ALLOW_BAN'		=> $mchat_ban,
			'MCHAT_ALLOW_EDIT'		=> $mchat_edit,
			'MCHAT_ALLOW_DEL'		=> $mchat_del,		
			'MCHAT_MESSAGE_EDIT'	=> $message_edit,
			'MCHAT_USER_AVATAR'		=> $mchat_avatar,					
			'U_VIEWPROFILE'			=> ($row['user_id'] != ANONYMOUS) ? append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $row['user_id']) : '',			
			'MCHAT_MESSAGE_ID'		=> $row['message_id'],
			'MCHAT_USERNAME_FULL'	=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'], $user->lang['GUEST']),
			'MCHAT_USERNAME'		=> get_username_string('username', $row['user_id'], $row['username'], $row['user_colour'], $user->lang['GUEST']),
			'MCHAT_USERNAME_COLOR'	=> get_username_string('colour', $row['user_id'], $row['username'], $row['user_colour'], $user->lang['GUEST']),
			'MCHAT_USER_IP'			=> $row['user_ip'],
			'MCHAT_U_WHOIS'			=> append_sid("{$phpbb_root_path}mchat.$phpEx", 'mode=whois&amp;ip=' . $row['user_ip']),
			'MCHAT_U_BAN'			=> append_sid("{$phpbb_root_path}adm/index.$phpEx" ,'i=permissions&amp;mode=setting_user_global&amp;user_id[0]=' . $row['user_id'], true, $user->session_id),
			'MCHAT_MESSAGE'			=> censor_text(generate_text_for_display($row['message'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options'])),
			'MCHAT_TIME'			=> $user->format_date($row['message_time'], $config_mchat['date']),
			'MCHAT_CLASS'			=> ($row['message_id'] % 2) ? 1 : 2
		));
		// reset the config settings
		if(isset($old_cfg['min_post_chars']))
		{
			$config['min_post_chars'] = $old_cfg['min_post_chars'];
			unset($old_cfg['min_post_chars']);
		}
		if(isset($old_cfg['max_post_smilies']))
		{
			$config['max_post_smilies'] = $old_cfg['max_post_smilies'];
			unset($old_cfg['max_post_smilies']);
		}		
		// insert user into the mChat sessions table
		mchat_sessions($mchat_session_time, true);
		// If read mode request set true
		$mchat_read_mode = true;

	break;

	// Delete function...
	case 'delete':
		
		// must have auths to delete
		$mchat_del = ($auth->acl_get('u_mchat_delete')) ? true : false;
		// Reguest...
		$message_id = request_var('message_id', 0);
		// If mChat disabled
		if (!$config['mchat_enable'] || !$mchat_del || !$message_id)
		{
			// Forbidden (for jQ AJAX request)
			header('HTTP/1.0 403 Forbidden');
			exit_handler();
		}
		
		// Run delete!
		$sql = 'DELETE FROM ' . MCHAT_TABLE . ' 
			WHERE message_id = ' . (int) $message_id;
		$db->sql_query($sql);

		// insert user into the mChat sessions table
		mchat_sessions($mchat_session_time, true);
	
		// Stop running code
		exit_handler();
	break;

	// Default function...
	default:

		// If not include in index.php set mchat.php page true
		if (!$mchat_include_index)
		{
			// Yes its custom page...
			$mchat_custom_page = true;

			// If custom page false mchat.php page redirect to index...
			if (!$config_mchat['custom_page'] && $mchat_custom_page)
			{
				$mchat_redirect = append_sid("{$phpbb_root_path}index.$phpEx");			
				// Redirect to previous page
				meta_refresh(3, $mchat_redirect);
				trigger_error($user->lang['MCHAT_NO_CUSTOM_PAGE'].'<br /><br />'.sprintf($user->lang['RETURN_PAGE'], '<a href="' . $mchat_redirect . '">', '</a>'), E_USER_NOTICE);
			}
			
			// user has permissions to view the custom chat?
			if (!$mchat_view && $mchat_custom_page)
			{
				trigger_error($user->lang['NOT_AUTHORISED'], E_USER_NOTICE);				
			}						
			
			// prune the chats if nescessary and amount in ACP not empty
			if ($config_mchat['prune_enable'] && $config_mchat['prune_num'] > 0)
			{
				mchat_prune((int) $config_mchat['prune_num']);
			}

			// if whois true
			if ($config_mchat['whois'])
			{
				// Grab group details for legend display for who is online on the custom page.
				if ($auth->acl_gets('a_group', 'a_groupadd', 'a_groupdel'))
				{
					$sql = 'SELECT group_id, group_name, group_colour, group_type FROM ' . GROUPS_TABLE . ' 
						WHERE group_legend = 1 
							ORDER BY group_name ASC';
				}
				else
				{
					$sql = 'SELECT g.group_id, g.group_name, g.group_colour, g.group_type FROM ' . GROUPS_TABLE . ' g 
						LEFT JOIN ' . USER_GROUP_TABLE . ' ug ON (g.group_id = ug.group_id AND ug.user_id = ' . $user->data['user_id'] . ' AND ug.user_pending = 0) 
							WHERE g.group_legend = 1 
								AND (g.group_type <> ' . GROUP_HIDDEN . ' 
									OR ug.user_id = ' . (int) $user->data['user_id'] . ') 
							ORDER BY g.group_name ASC';
				}
				$result = $db->sql_query($sql);
				$legend = array();
				
				while ($row = $db->sql_fetchrow($result))
				{
					$colour_text = ($row['group_colour']) ? ' style="color:#'.$row['group_colour'].'"' : '';
					$group_name = ($row['group_type'] == GROUP_SPECIAL) ? $user->lang['G_'.$row['group_name']] : $row['group_name'];
					if ($row['group_name'] == 'BOTS' || ($user->data['user_id'] != ANONYMOUS && !$auth->acl_get('u_viewprofile')))
					{
						$legend[] = '<span'.$colour_text.'>'.$group_name.'</span>';
					}
					else
					{
						$legend[] = '<a'.$colour_text.' href="'.append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=group&amp;g='.$row['group_id']).'">'.$group_name.'</a>';
					}
				}
				$db->sql_freeresult($result);
				$legend = implode(', ', $legend);
				
				// Assign index specific vars
				$template->assign_vars(array(
					'LEGEND'	=> $legend,
				));
			}
			$template->assign_block_vars('navlinks', array(
				'FORUM_NAME'         => $user->lang['MCHAT_TITLE'],
				'U_VIEW_FORUM'      => append_sid("{$phpbb_root_path}mchat.$phpEx"))
			);			
		}
		
		// Run code...
		if ($mchat_view)
		{
			$sql_where = $user->data['user_mchat_topics'] ? '' : 'WHERE m.forum_id = 0';
			// Message row
			$sql = 'SELECT m.*, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height
				FROM ' . MCHAT_TABLE . ' m
					LEFT JOIN ' . USERS_TABLE . ' u ON (m.user_id = u.user_id)
				' . $sql_where . '
				ORDER BY message_id DESC';
			$result = $db->sql_query_limit($sql, $config_mchat['message_limit']);
			$rows = $db->sql_fetchrowset($result);

			$rows = array_reverse($rows, true);
			
			foreach($rows as $row)
			{
				// auth check
				if ($row['forum_id'] != 0 && !$auth->acl_get('f_read', $row['forum_id']))
				{
					continue;
				}
				// edit, delete and permission auths
				$mchat_ban = ($auth->acl_get('a_authusers') && $user->data['user_id'] != $row['user_id']) ? true : false;
				// edit auths
				if ($user->data['user_id'] == ANONYMOUS && $user->data['user_id'] == $row['user_id'])
				{
					$chat_auths = $user->data['session_ip'] == $row['user_ip'] ? true : false;
				}
				else
				{
					$chat_auths = $user->data['user_id'] == $row['user_id'] ? true : false;
				}
				$mchat_edit = ($auth->acl_get('u_mchat_edit') && ($auth->acl_get('m_') || $chat_auths)) ? true : false;
				$mchat_del = ($auth->acl_get('u_mchat_delete') && ($auth->acl_get('m_') || $chat_auths)) ? true : false;
				$mchat_avatar = $row['user_avatar'] ? get_user_avatar($row['user_avatar'], $row['user_avatar_type'], ($row['user_avatar_width'] > $row['user_avatar_height']) ? 40 : (40 / $row['user_avatar_height']) * $row['user_avatar_width'], ($row['user_avatar_height'] > $row['user_avatar_width']) ? 40 : (40 / $row['user_avatar_width']) * $row['user_avatar_height']) : '';
				$message_edit = $row['message'];
				decode_message($message_edit, $row['bbcode_uid']);
				$message_edit = str_replace('"', '&quot;', $message_edit); // Edit Fix ;)
				
				$template->assign_block_vars('mchatrow', array(
					'MCHAT_ALLOW_BAN'		=> $mchat_ban,
					'MCHAT_ALLOW_EDIT'		=> $mchat_edit,
					'MCHAT_ALLOW_DEL'		=> $mchat_del,				
					'MCHAT_USER_AVATAR'		=> $mchat_avatar,
					'U_VIEWPROFILE'			=> ($row['user_id'] != ANONYMOUS) ? append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $row['user_id']) : '',					
					'MCHAT_MESSAGE_EDIT'	=> $message_edit,
					'MCHAT_MESSAGE_ID'		=> $row['message_id'],
					'MCHAT_USERNAME_FULL'	=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'], $user->lang['GUEST']),
					'MCHAT_USERNAME'		=> get_username_string('username', $row['user_id'], $row['username'], $row['user_colour'], $user->lang['GUEST']),
					'MCHAT_USERNAME_COLOR'	=> get_username_string('colour', $row['user_id'], $row['username'], $row['user_colour'], $user->lang['GUEST']),
					'MCHAT_USER_IP'			=> $row['user_ip'],
					'MCHAT_U_WHOIS'			=> append_sid("{$phpbb_root_path}mchat.$phpEx", 'mode=whois&amp;ip=' . $row['user_ip']),
					'MCHAT_U_BAN'			=> append_sid("{$phpbb_root_path}adm/index.$phpEx" ,'i=permissions&amp;mode=setting_user_global&amp;user_id[0]=' . $row['user_id'], true, $user->session_id),
					'MCHAT_MESSAGE'			=> generate_text_for_display($row['message'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']),
					'MCHAT_TIME'			=> $user->format_date($row['message_time'], $config_mchat['date']),
					'MCHAT_CLASS'			=> ($row['message_id'] % 2) ? 1 : 2
				));
				
			}
		
			$db->sql_freeresult($result);
			
			// Write no message
			if (empty($rows))
			{
				$mchat_no_message = true;
			}
			// display custom bbcodes
			if($mchat_allow_bbcode && $config['allow_bbcode'])
			{
				display_mchat_bbcodes();
			}
			// Smile row
			if ($mchat_smilies)
			{
				if (!function_exists('generate_smilies'))
				{
					include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
				}
				generate_smilies('inline', 0);
			}
			// If the static message is defined in the language file use it, else just use the entry in the database
			if (isset($user->lang[strtoupper('static_message')]) || !empty($config_mchat['static_message']))
			{
				$config_mchat['static_message'] = $config_mchat['static_message'];
				if(isset($user->lang[strtoupper('static_message')]))
				{
					$config_mchat['static_message'] = $user->lang[strtoupper('static_message')];
				}
			}			
			// a list of users using the chat
			if ($mchat_custom_page)
			{
				$mchat_users = mchat_users($mchat_session_time, true);
			}
			else
			{
				$mchat_users = mchat_users($mchat_session_time);
			}
			$template->assign_vars(array(
				'MCHAT_USERS_COUNT'		=> $mchat_users['mchat_users_count'],
				'MCHAT_USERS_LIST'		=> $mchat_users['online_userlist'],	
			));
		}
	break;
}

// Template function...
$template->assign_vars(array(
	'MCHAT_FILE_NAME'		=> append_sid("{$phpbb_root_path}mchat.$phpEx"),
	'MCHAT_REFRESH_JS'		=> 1000 * $config_mchat['refresh'],
	'MCHAT_ADD_MESSAGE'		=> $mchat_add_mess,
	'MCHAT_READ_MODE'		=> $mchat_read_mode,
	'MCHAT_ARCHIVE_MODE'	=> $mchat_archive_mode,
	'MCHAT_RULES'			=> $mchat_rules,
	'MCHAT_ALLOW_SMILES'	=> $mchat_smilies,
	'MCHAT_ALLOW_IP'		=> $mchat_ip,
	'MCHAT_NOMESSAGE_MODE'	=> $mchat_no_message,
	'MCHAT_ALLOW_BBCODES'	=> ($mchat_allow_bbcode && $config['allow_bbcode']) ? true : false,
	'MCHAT_ENABLE'			=> $config['mchat_enable'],
	'MCHAT_ARCHIVE_URL'		=> append_sid("{$phpbb_root_path}mchat.$phpEx", 'mode=archive'),
	'MCHAT_CUSTOM_PAGE'		=> $mchat_custom_page,
	'MCHAT_INDEX_HEIGHT'	=> $config_mchat['index_height'],
	'MCHAT_CUSTOM_HEIGHT'	=> $config_mchat['custom_height'],
	'MCHAT_READ_ARCHIVE_BUTTON'		=> $mchat_read_archive,
	'MCHAT_FOUNDER'			=> $mchat_founder,
	'MCHAT_CLEAN_URL'		=> append_sid("{$phpbb_root_path}mchat.$phpEx", 'mode=clean&amp;redirect=' . $on_page),
	'MCHAT_STATIC_MESS'		=> !empty($config_mchat['static_message']) ? htmlspecialchars_decode($config_mchat['static_message']) : '',
	'MCHAT_COPYRIGHT'		=> $user->lang['MCHAT_COPYRIGHT'],
	'MCHAT_WHOIS'			=> $config_mchat['whois'],
	'MCHAT_MESSAGE_LNGTH'	=> $config_mchat['max_message_lngth'],
	'MCHAT_MESS_LONG'		=> sprintf($user->lang['MCHAT_MESS_LONG'], $config_mchat['max_message_lngth']),
	'MCHAT_USER_TIMEOUT'	=> $config_mchat['timeout'] ? 1000 * $config_mchat['timeout'] : false,
	'MCHAT_WHOIS_REFRESH'	=> 1000 * $config_mchat['whois_refresh'],
	'MCHAT_PAUSE_ON_INPUT'	=> $config_mchat['pause_on_input'] ? true : false,
	'L_MCHAT_ONLINE_EXPLAIN'	=> mchat_session_time($mchat_session_time),
	'MCHAT_REFRESH_YES'		=> sprintf($user->lang['MCHAT_REFRESH_YES'], $config_mchat['refresh']),
	'L_MCHAT_WHOIS_REFRESH_EXPLAIN'	=> sprintf($user->lang['WHO_IS_REFRESH_EXPLAIN'], $config_mchat['whois_refresh']),
	'S_MCHAT_AVATARS'		=> $mchat_avatars,
	'S_MCHAT_LOCATION'		=> $config_mchat['location'],
	'S_MCHAT_SOUND_YES'		=> $user->data['user_mchat_sound'],
	'S_MCHAT_INDEX_STATS'	=> $user->data['user_mchat_stats_index'],
	'U_MORE_SMILIES'		=> append_sid("{$phpbb_root_path}posting.$phpEx", 'mode=smilies'),
	'U_MCHAT_RULES'			=> append_sid("{$phpbb_root_path}mchat.$phpEx", 'mode=rules'),
));

// Template
if (!$mchat_include_index)
{
	page_header($user->lang['MCHAT_TITLE']);
		$template->set_filenames(array('body' => 'mchat_body.html'));
	page_footer();
}

?>