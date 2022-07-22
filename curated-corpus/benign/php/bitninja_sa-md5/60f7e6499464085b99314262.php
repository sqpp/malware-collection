<?php
/*======================================================================*\
|| #################################################################### ||
|| # ---------------------------------------------------------------- # ||
|| # Copyright ©2007-2009 Jon Dickinson AKA Pandemikk				  # ||
|| # All Rights Reserved. 											  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------------------------------------------------------- # ||
|| # You are not allowed to use this on your server unless the files  # ||
|| # you downloaded were done so with permission.					  # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if (!REGISTRATION::$permissions['caninvite'])
{
	// Can't invite
	print_no_permission();
}

// ######################### REQUIRE BACK-END ############################
require_once(DIR . '/includes/functions_user.php');

// #######################################################################
// ######################## START MAIN SCRIPT ############################
// #######################################################################

// ############################### start send invites ###############################
if ($_POST['action'] == 'send')
{
	if	(!$vbulletin->options['dbtech_registration_invites'] OR $vbulletin->options['dbtech_registration_no_new_invites']
			OR (!$vbulletin->options['allowregistration'] AND !$vbulletin->options['dbtech_registration_invites_override'])
		)
	{
		// new invites aren't allowed
		eval(standard_error(fetch_error('dbtech_registration_invites_off')));
	}

	if (
		$vbulletin->userinfo['permissions']['dbtech_registration_invites'] AND
		$vbulletin->userinfo['permissions']['dbtech_registration_invites'] - (int)$vbulletin->userinfo['dbtech_registration_invites_sent'] <= 0
	)
	{
		// you're done sending invites son
		eval(standard_error(fetch_error('dbtech_registration_invites_off')));
	}

	$vbulletin->input->clean_gpc('r', 'invitee', TYPE_STR);

	// verify email
	REGISTRATION::verify_email($vbulletin->GPC['invitee']);

	// create the hash
	$hash = REGISTRATION::create_hash($vbulletin->GPC['invitee']);

	// insert email
	$db->query_write("
		INSERT INTO " . TABLE_PREFIX . "dbtech_registration_email
			(email, verifyhash)
		VALUES
			(" . $db->sql_prepare($vbulletin->GPC['invitee']) . ", " . $db->sql_prepare($hash) . ")
	");

	// insert invite
	$db->query_write("
		INSERT INTO " . TABLE_PREFIX . "dbtech_registration_invite
			(userid, email, dateline)
		VALUES
			(" . $vbulletin->userinfo['userid'] . ", " . $db->sql_prepare($vbulletin->GPC['invitee']) . ", " . TIMENOW . ")
	");

	$last = $db->insert_id();

	// update userinfo
	$db->query_write("
		UPDATE " . TABLE_PREFIX . "user SET
			dbtech_registration_invites_sent_total = dbtech_registration_invites_sent_total + 1,
			dbtech_registration_invites_sent = dbtech_registration_invites_sent + 1
		WHERE userid = " . $vbulletin->userinfo['userid']
	);

	// update cache
	++REGISTRATION::$cache['total']['invites']['sent'];

	// update datastore
	build_datastore('dbtech_registration_total', serialize(REGISTRATION::$cache['total']), 1);

	// track this
	REGISTRATION::build_log('', 'dbtech_registration_sent_invite', serialize(array('invite' => $last)));

	// set username
	$username = $vbulletin->userinfo['username'];

	// send verification email
	eval(fetch_email_phrases('dbtech_registration_sent_invite_email', $vbulletin->userinfo['languageid']));
	require_once(DIR . '/includes/class_bbcode_alt.php');
	$plaintext_parser = new vB_BbCodeParser_PlainText($vbulletin, fetch_tag_list());
	$plaintext_parser->set_parsing_language($vbulletin->userinfo['languageid']);
	$message = $plaintext_parser->parse($message, 'privatemessage');
	vbmail($vbulletin->GPC['invitee'], $subject, $message, true);

	// fire off "successfully sent invite"
	$vbulletin->url = $vbulletin->options['bburl'] . '/registration.php?' . $vbulletin->session->vars['sessionurl'] . 'do=profile&amp;action=invites';
	if (version_compare($vbulletin->versionnumber, '4.1.7') >= 0)
	{
		eval(print_standard_redirect(array('dbtech_registration_invite_sent_x', htmlspecialchars_uni($vbulletin->GPC['invitee'])), true, true));
	}
	else
	{
		eval(print_standard_redirect('dbtech_registration_invite_sent_x', true, true));
	}
}

// ############################### start display invites ###############################
if ($_REQUEST['action'] == 'invites')
{
	$vbulletin->input->clean_array_gpc('r', array(
		'pagenumber'  	=> TYPE_UINT,
		'perpage'     	=> TYPE_UINT
	));

	// Set the limit on number of stats to fetch
	$limit = (isset($vbulletin->options['dbtech_registration_profile_invite_statistics_limit']) ? $vbulletin->options['dbtech_registration_profile_invite_statistics_limit'] : 25);

	// Ensure there's no errors or out of bounds with the page variables
	if ($vbulletin->GPC['pagenumber'] < 1)
	{
		$vbulletin->GPC['pagenumber'] = 1;
	}
	$pagenumber = $vbulletin->GPC['pagenumber'];
	$perpage	= (!$vbulletin->GPC['perpage'] OR $vbulletin->GPC['perpage'] > $limit) ? $limit : $vbulletin->GPC['perpage'];	

	if	(!$vbulletin->options['dbtech_registration_invites'] OR $vbulletin->options['dbtech_registration_no_new_invites']
			OR (!$vbulletin->options['allowregistration'] AND !$vbulletin->options['dbtech_registration_invites_override'])
		)
	{
		// new invites aren't allowed
		eval(standard_error(fetch_error('dbtech_registration_invites_off')));
	}

	// Navigation bits
	$navbits[''] = $vbphrase['dbtech_registration_invites'];

	// Count number of stats
	$count = REGISTRATION::$db->fetchOne('
		SELECT COUNT(*)
		FROM $dbtech_registration_invite AS invite
		WHERE invite.userid = ' . $vbulletin->userinfo['userid'] . '
	');
		
	// Ensure every result is as it should be
	sanitize_pageresults($count, $pagenumber, $perpage);
	
	// Find out where to start
	$startat = ($pagenumber - 1) * $perpage;
	
	// Constructs the page navigation
	$pagenav = construct_page_nav(
		$pagenumber,
		$perpage,
		$count,
		'registration.php?' . $vbulletin->session->vars['sessionurl'] . 'do=profile&amp;action=invites',
		"&amp;perpage=$perpage"
	);
	
	if (!empty($vbulletin->userinfo['dbtech_registration_invites_sent_total']))
	{
		$invites = REGISTRATION::$db->fetchAll('
			SELECT
				invite.email, invite.dateline,
				verified.verified,
				user.userid, user.username, user.usergroupid, user.infractiongroupid, user.displaygroupid, user.joindate, :dbtech_vbshop
				actionuser_friend.relationid
			FROM $dbtech_registration_invite AS invite
			LEFT JOIN $dbtech_registration_email AS verified ON(invite.email = verified.email) AND verified = \'1\'
			LEFT JOIN $user AS user ON(verified.email = user.email) AND :notme
			LEFT JOIN $userlist AS actionuser_friend ON(actionuser_friend.userid = user.userid)
			WHERE invite.userid = ' . $vbulletin->userinfo['userid'] . '
			GROUP BY invite.email
			ORDER BY invite.dateline DESC
			LIMIT :limitStart, :limitEnd
			', array(
				':dbtech_vbshop'	=> ($vbulletin->products['dbtech_vbshop'] ? 'user.dbtech_vbshop_purchase,' : ''),
				':notme'			=> 'user.userid != ' . $vbulletin->userinfo['userid'],
				':limitStart'	=> $startat,
				':limitEnd'		=> $perpage
		));
	}
	
	$invitebits = '';
	if (!empty($invites))
	{
		foreach ($invites AS $invite)
		{
			if ($invite['userid'])
			{
				// set username markup
				$invite['musername'] = fetch_musername($invite);
			}
			
			// set date
			$invite['dateline'] = vbdate($vbulletin->options['dateformat'], $invite['dateline']);

			// set joined date
			$invite['joindate'] = vbdate($vbulletin->options['dateformat'], $invite['joindate']);
		
			$templater = vB_Template::create('dbtech_registration_invites_bit');
				$templater->register('invite', $invite);
			$invitebits .= $templater->render();	
		}
	}
	
	$codes = REGISTRATION::$db->fetchAll('
		SELECT
			criteria.value,
			(SELECT GROUP_CONCAT(instance_criteria.instanceid) FROM $dbtech_registration_instance_criteria AS instance_criteria WHERE instance_criteria.criteriaid = criteria.criteriaid) AS instance_list
		FROM $dbtech_registration_criteria AS criteria
		WHERE criteria.type = \':type\'
		:limit
		', array(
			':type'			=> 'code',
			':limit'		=> 'LIMIT 10'
	));
	
	$codebits = '';
	if (!empty($codes))
	{
		foreach ($codes AS $code)
		{
			// Sanitize this
			$code['value'] = htmlspecialchars_uni($code['value']);

			$code['instances'] = $vbphrase['n_a'];
			if (!empty($code['instance_list']))
			{
				$code['instance_list'] = explode(',', $code['instance_list']);

				$code['instances'] = '';
				foreach ($code['instance_list'] AS $instanceid)
				{
					if (!REGISTRATION::$cache['instance'][$instanceid])
					{
						continue;
					}
				
					//$code['instances'] .= '<a href="registration.php?' . $vbulletin->session->vars['sessionurl'] . 'do=instance&amp;action=view&amp;instanceid=' . $instanceid . '">' . htmlspecialchars_uni(REGISTRATION::$cache['instance'][$instanceid]['title']) . '</a>';
					$code['instances'] .= htmlspecialchars_uni(REGISTRATION::$cache['instance'][$instanceid]['title']) . ' ';
				}
			}
		
			$templater = vB_Template::create('dbtech_registration_codes_bit');
				$templater->register('code', $code);
			$codebits .= $templater->render();	
		}
	}

	// Include the page template
	$page_templater = vB_Template::create('dbtech_registration_invites');
		$page_templater->register('pagenav', 		$pagenav);
		$page_templater->register('pagenumber', 	$pagenumber);
		$page_templater->register('perpage', 		$perpage);
		$page_templater->register('invitebits',		$invitebits);
		$page_templater->register('codebits',		$codebits);
}

// #######################################################################
if (intval($vbulletin->versionnumber) == 3)
{
	// Create navbits
	$navbits = construct_navbits($navbits);	
	eval('$navbar = "' . fetch_template('navbar') . '";');
}
else
{
	$navbar = render_navbar_template(construct_navbits($navbits));	
}
construct_usercp_nav('dbtech_registration_' . $_REQUEST['action']);

$templater = vB_Template::create('USERCP_SHELL');
	$templater->register_page_templates();
	$templater->register('cpnav', $cpnav);
	if (method_exists($page_templater, 'render'))
	{
		// Only run this if there's anything to render
		$templater->register('HTML', $page_templater->render());
	}
	$templater->register('clientscripts', $clientscripts);
	$templater->register('navbar', $navbar);
	$templater->register('navclass', $navclass);
	$templater->register('onload', $onload);
	$templater->register('pagetitle', $pagetitle);
	$templater->register('template_hook', $template_hook);
print_output($templater->render());

/*=======================================================================*\
|| ##################################################################### ||
|| # Created: 17:29, Sat Dec 27th 2008                                 # ||
|| # SVN: $RCSfile: button.php,v $ - $Revision: $WCREV$ $
|| ##################################################################### ||
\*=======================================================================*/
?>