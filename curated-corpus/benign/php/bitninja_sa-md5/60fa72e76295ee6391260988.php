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

if (!REGISTRATION::$permissions['canview'])
{
	// Can't view
	print_no_permission();
}

// #############################################################################

if ($_REQUEST['action'] == 'redirect_log') 
{
	$vbulletin->input->clean_gpc('r', 'redirect_logid', TYPE_UINT);

	if (!$data = REGISTRATION::$db->fetchRow('
		SELECT
			*
		FROM $dbtech_registration_redirect_log
		WHERE redirect_logid = :id
		', array(
			':id' => $vbulletin->GPC['redirect_logid']
	)))
	{
		eval(standard_error(fetch_error('dbtech_registration_invalid_x', $vbphrase['dbtech_registration_redirect_log'], $vbulletin->GPC['redirect_logid'])));
	}

	// set phrase
	$data['type']		= $vbphrase['dbtech_registration_' . $data['type']];

	// set date
	$data['dateline']	= vbdate($vbulletin->options['dateformat'], $data['dateline']) . ' - '
								. vbdate($vbulletin->options['timeformat'], $data['dateline']);
}
else if ($_REQUEST['action'] == 'user') 
{
	$vbulletin->input->clean_gpc('r', 'userid', TYPE_UINT);

	if (!$data = REGISTRATION::$db->fetchRow('
		SELECT
			*
		FROM $user
		WHERE userid = :id
		', array(
			':id' => $vbulletin->GPC['userid']
	)))
	{
		eval(standard_error(fetch_error('dbtech_registration_invalid_x', $vbphrase['userid'], $vbulletin->GPC['userid'])));
	}

	// set date
	$data['dateline']	= vbdate($vbulletin->options['dateformat'], $data['joindate']) . ' - '
						. vbdate($vbulletin->options['timeformat'], $data['joindate']);
}
else if ($_REQUEST['action'] == 'invite') 
{
	$vbulletin->input->clean_gpc('r', 'inviteid', TYPE_UINT);

	if (!$data = REGISTRATION::$db->fetchRow('
		SELECT
			invite.email, invite.dateline,
			:dbtech_vbshop user.userid, user.username, user.usergroupid, user.infractiongroupid, user.displaygroupid,
			email.verified,
			invited.userid AS invited_userid, invited.joindate
		FROM $dbtech_registration_invite AS invite
		LEFT JOIN $user AS user ON(invite.userid = user.userid)
		LEFT JOIN $dbtech_registration_email AS email ON(invite.email = email.email)
		LEFT JOIN $user AS invited ON(invite.email = invited.email)
		WHERE inviteid = :id
		', array(
			':dbtech_vbshop'	=> ($vbulletin->products['dbtech_vbshop'] ? 'user.dbtech_vbshop_purchase,' : ''),
			':id' => $vbulletin->GPC['inviteid']
	)))
	{
		eval(standard_error(fetch_error('dbtech_registration_invalid_x', $vbphrase['dbtech_registration_invite'], $vbulletin->GPC['inviteid'])));
	}

	if (!empty($data['userid']))
	{
		// set username markup
		$data['musername'] = fetch_musername($data);
	}
	
	// set date
	$data['dateline']	= vbdate($vbulletin->options['dateformat'], $data['dateline']) . ' - '
						. vbdate($vbulletin->options['timeformat'], $data['dateline']);
								
	if (!empty($data['joindate']))
	{
		// set date
		$data['joindate']	= vbdate($vbulletin->options['dateformat'], $data['joindate']) . ' - '
							. vbdate($vbulletin->options['timeformat'], $data['joindate']);
	}
}
else
{
	// invalid action
	eval(standard_error(fetch_error('dbtech_registration_error_x', $vbphrase['dbtech_registration_invalid_action'])));
}

// Navigation bits
$navbits['registration.php?' . $vbulletin->session->vars['sessionurl'] . 'do=statistics'] = $vbphrase['dbtech_registration_statistics'];

// Set page titles
$pagetitle = $navbits[] = $vbphrase['details'];

// Begin the page template
$page_templater = vB_Template::create('dbtech_registration_details');
	$page_templater->register('action', $_REQUEST['action']);
	$page_templater->register('data', $data);
$HTML = $page_templater->render();
?>