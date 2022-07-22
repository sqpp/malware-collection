#!/usr/bin/php -q
<?php

error_reporting(0);

$_SERVER['SERVER_PROTOCOL'] = '';
$_SERVER['REQUEST_METHOD'] = '';
$_SERVER['SERVER_NAME'] = '';
require_once '../../../wp-blog-header.php';
require_once AIOSC_DIR.'/inc/email-parser/plancake-email-parser.php';

global $aiosc_settings, $aiosc_capabilities, $aiosc_user;

//Check if e-mail piping is enabled first
if(!$aiosc_settings->get('email_piping_enable')) {
    die();
}

/**
 * E-mail TO structure: USER_ID-TICKET_ID-TICKET_HASH@aiosc.domain
 * Then check if USER_ID exists and if FROM e-mail is the e-mail user used when registered his account on WordPress
 * Then check if USER_ID is AUTHOR_ID or OPERATOR_ID of TICKET_ID
 * Then check if TICKET_ID and TICKET_HASH combination matches
 * Next, check if ticket's status is not CLOSED
 * If everything is okay, create new reply
 */
// read from stdin
$fd = fopen("php://stdin", "r"); //fopen("email_demo.txt", "r"); //debug
$email = "";
while (!feof($fd))
{
    $email .= fread($fd, 1024);
}
fclose($fd);

$emailParser = new PlancakeEmailParser($email);


//var_dump($emailParser->rawFields);


/** PARSING */
$hfrom = $emailParser->getHeader('from');
$hto = $emailParser->getHeader('to');
$email_from = @$hfrom['email'];
$email_to = @$hto['email']; //"2-1-da780cc1ca4f6738a771ec13c8e79fd99e08b8aa@aiosc.diwave-coders.com";


if(empty($email_to)) aiosc_pipe_error('Email-To not found at all');
if(empty($email_from) || !aiosc_is_email($email_from)) aiosc_pipe_error('Email-From not found at all');



/**
 * @CREATE-TICKET
 * Check if Mail-To is @email_piping_support_addr address. If so, then try to create ticket by
 * getting user from sender's e-mail.
 *
 * @filter aiosc_support_email
 */
$support_email = apply_filters('aiosc_support_email', $aiosc_settings->get('email_piping_support_addr'));
if(aiosc_is_email($support_email) && $email_to == $support_email) {

    $user = get_user_by('email', $email_from);
    if($user == false) aiosc_pipe_error(sprintf('[Creation] [Invalid User] User with %s email not found.', $email_from));
    $dep = new aiosc_Department($aiosc_settings->get('email_piping_creation_department'));
    if(!aiosc_is_department($dep) || !$dep->is_active) aiosc_pipe_error(sprintf('[Creation] [Invalid Department] You did not set any active department in preferences (%d)',$dep->ID));

    $priority = new aiosc_Priority($aiosc_settings->get('email_piping_creation_priority'));
    if(!aiosc_is_priority($priority) || !$priority->is_active) aiosc_pipe_error(sprintf('[Creation] [Invalid Priority] You did not set any active priority in preferences. (%d)', $priority->ID));

    $user = new aiosc_User($user->ID);
    if(!aiosc_is_user($user)) aiosc_pipe_error(sprintf('[Creation] [Invalid User] User with ID %d is not valid AIOSC user.', $user->ID));

    $aiosc_user = $user;

    $subject = $emailParser->getSubject();
    $content = $emailParser->getHTMLBody();
    $content = quoted_printable_decode($content);
    if(!$emailParser->has_html) $content = str_replace(array('\n\n','\r'),'<br />',$content);
    $content = aiosc_clean_content($content);

    $subject = quoted_printable_decode($subject);
    $subject = strip_tags(aiosc_preclean_content(str_replace(array('\n','\r'),'', $subject)));

    if(empty($subject)) $subject = __('Untitled', 'aiosc');

    $o_min_subject_len = $aiosc_settings->get('min_subject_len');
    $aiosc_settings->set('min_subject_len', 1);

    $result = aiosc_TicketManager::update_ticket(0, $user->ID, $subject, $content, $dep->ID, 0, $priority->ID);
    $aiosc_settings->set('min_subject_len', $o_min_subject_len);

    if(aiosc_is_error($result)) {
        aiosc_pipe_error('[Creation] [Fatal] '.$result);
    }

}

/**
 * @POST-REPLY
 * If Mail-To is aiosc generated e-mail, try to post reply
 */
else {
    $email_to = substr($email_to,0,strpos($email_to,'@')); //get everything before @
    $email_to = explode('-',$email_to);
    if(!is_array($email_to) || count($email_to) !== 3) aiosc_pipe_error('[Invalid Email-To] '.$email_to);

    $user_id = $email_to[0];
    if(!is_numeric($user_id) || $user_id < 1) aiosc_pipe_error('[Invalid User ID] '.$user_id);

    $ticket_id = $email_to[1];
    if(!is_numeric($ticket_id) || $ticket_id < 1) aiosc_pipe_error('[Invalid Ticket ID] '.$ticket_id);

    $ticket_hash = $email_to[2];
    if(strlen($ticket_hash) != 40) aiosc_pipe_error('[Invalid Hash] '.$ticket_hash);

//get objects to work with
    $user = new aiosc_User($user_id);
    if(!aiosc_is_user($user) || $email_from !== $user->user_email) {
        if(!aiosc_is_user($user))  aiosc_pipe_error('[Invalid aiosc_User] Could not create user from ID: '.$user_id);
        else  aiosc_pipe_error('[Invalid aiosc_User] Could not create user from ID: '.$user_id.', e-mail address does not match user\'s email address.');
    }

    $ticket = new aiosc_Ticket($ticket_id);
    if(!aiosc_is_ticket($ticket) || $ticket->hash_code !== $ticket_hash || $ticket->status == 'closed')
        aiosc_pipe_error('[Invalid aiosc_Ticket] Could not create ticket from ID: '.$ticket_id);

    if(!$user->can('reply_ticket',array('ticket_id'=>$ticket)))
        aiosc_pipe_error('[User Permission Error] User ('.$user_id.') cannot reply to this ticket ('.$ticket_id.')');

//file_put_contents(getcwd()."/mail_".time().".txt",$email);
    $content = $emailParser->getHTMLBody();
    $content = quoted_printable_decode($content);
    if(!$emailParser->has_html) $content = str_replace(array('\n\n','\r'),'<br />',$content);
    $content = aiosc_clean_content($content);
    if(aiosc_is_error(aiosc_ReplyManager::update_reply(0,$ticket->ID,$content,false,false,true, $user))) {
        aiosc_pipe_error('[ReplyManager::update_reply] Could not update reply for some reason.');
    }
}
function aiosc_pipe_error($message='') {
    if(empty($message)) $message = '[AIOSC Piper] Unknown error.';
    else $message = '[AIOSC Piper] '.$message;
    aiosc_log($message);
    //die($message); //send email back to user
    die();
}
die();
?>
