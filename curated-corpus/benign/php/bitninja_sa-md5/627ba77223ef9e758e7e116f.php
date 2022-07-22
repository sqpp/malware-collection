<?php
require("./global.php");
isAdmin();

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="find";

if($action=="add") {

 require("./lib/class_tpl_file.php");
 $tpl = new tpl(0,0,"../");

 if(isset($_POST['send'])) {
  reset($_POST);
  while(list($key,$val)=each($_POST)) {
   if(is_string($val)) $$key=trim($val);
   elseif(is_array($val)) $$key=trim_array($val);
  }

  $username=preg_replace("/\s{2,}/"," ",$username);
  $error="";
  $fieldvalues="";

  $result = $db->query("SELECT profilefieldid, required FROM bb".$n."_profilefields ORDER BY profilefieldid ASC");
  while($row=$db->fetch_array($result)) $fieldvalues.=",'".addslashes(htmlspecialchars($field[$row[profilefieldid]]))."'";

  if(!$username || !$email || !$password) eval ("\$error .= \"".$tpl->get("register_error1")."\";");
  if(!verify_username($username)) eval ("\$error .= \"".$tpl->get("register_error3")."\";");
  if(!verify_email($email)) eval ("\$error .= \"".$tpl->get("register_error4")."\";");
  if($error) eval ("\$error = acp_error_frame(\"".gettemplate("users_add_error")."\");");
  else {
   if($homepage && !preg_match("/[a-zA-Z]:\/\//si", $homepage)) $homepage = "http://".$homepage;
   if($day && $month && $year) $birthday=ifelse(strlen($year)==4,$year,ifelse(strlen($year)==2,"19$year","0000"))."-".ifelse($month<10,"0$month",$month)."-".ifelse($day<10,"0$day",$day);
   else $birthday = "0000-00-00";

   $rankid = $db->query_first("SELECT rankid FROM bb".$n."_ranks WHERE groupid = '$groupid' AND needposts = 0 AND gender IN (0,'$gender') ORDER BY gender DESC");
   $db->query("INSERT INTO bb".$n."_users (userid,username,password,email,groupid,rankid,title,regdate,lastvisit,lastactivity,usertext,signature,icq,aim,yim,msn,homepage,birthday,gender,showemail,admincanemail,usercanemail,invisible,usecookies,styleid,activation,daysprune,timezoneoffset,dateformat,timeformat,emailnotify,receivepm,emailonpm,pmpopup,umaxposts,showsignatures,showavatars,showimages,nosessionhash,avatarid)
    VALUES (NULL,'".addslashes(htmlspecialchars($username))."','".md5($password)."','".addslashes(htmlspecialchars($email))."','$groupid','$rankid[rankid]','".addslashes(htmlspecialchars($title))."','".time()."','".time()."','".time()."','".addslashes(htmlspecialchars($usertext))."','".addslashes($signature)."','".intval($icq)."','".addslashes(htmlspecialchars($aim))."','".addslashes(htmlspecialchars($yim))."','".addslashes(htmlspecialchars($msn))."','".addslashes(htmlspecialchars($homepage))."','".addslashes(htmlspecialchars($birthday))."','".intval($gender)."','".intval($showemail)."','".intval($admincanemail)."','".intval($usercanemail)."','".intval($invisible)."','".intval($usecookies)."','".intval($styleid)."','1','".intval($daysprune)."','".addslashes(htmlspecialchars($default_timezoneoffset))."','".addslashes(htmlspecialchars($dateformat))."','".addslashes(htmlspecialchars($timeformat))."','".intval($emailnotify)."','".intval($receivepm)."','".intval($emailonpm)."','".intval($pmpopup)."','".intval($umaxposts)."','".intval($showsignatures)."','".intval($showavatars)."','".intval($showimages)."','".intval($nosessionhash)."','".intval($avatarid)."')");
   $insertid = $db->insert_id();

   $db->query("INSERT INTO bb".$n."_userfields VALUES (".$insertid.$fieldvalues.")");

   header("Location: users.php?action=find&sid=$session[hash]");
   exit();
  }
 }
 else {
  $invisible=$default_register_invisible;
  $nosessionhash=$default_register_nosessionhash;
  $usecookies=$default_register_usecookies;
  $admincanemail=$default_register_admincanemail;
  $showemail=1-$default_register_showemail;
  $usercanemail=$default_register_usercanemail;
  $emailnotify=$default_register_emailnotify;
  $receivepm=$default_register_receivepm;
  $emailonpm=$default_register_emailonpm;
  $pmpopup=$default_register_pmpopup;
  $showsignatures=$default_register_showsignatures;
  $showavatars=$default_register_showavatars;
  $showimages=$default_register_showimages;
 }

 if(isset($_POST)) {
  reset($_POST);
  while(list($key,$val)=each($_POST)) {
   if(is_string($val)) $$key=htmlspecialchars(trim($val));
   elseif(is_array($val)) $$key=htmlspecialchars_array(trim_array($val));
  }
 }
 $months = explode("|", gettemplate("months"));
 for($i=1;$i<=31;$i++) $day_options.=makeoption($i,$i,$day);
 for($i=1;$i<=12;$i++) $month_options.=makeoption($i,getmonth($i),$month);

 $timezones = explode("\n", $tpl->get("timezones"));
 for($i=0;$i<count($timezones);$i++) {
  $parts = explode("|", trim($timezones[$i]));
  $timezone_options .= makeoption($parts[0],"(GMT".ifelse($parts[1]," ".$parts[1],"").") $parts[2]",$default_timezoneoffset);
 }

 $result = $db->query("SELECT styleid, stylename FROM bb".$n."_styles WHERE default_style = 0 ORDER BY stylename ASC");
 while($row=$db->fetch_array($result)) $style_options.=makeoption($row['styleid'],$row['stylename'],$styleid);

 if(isset($gender)) $sel_gender[$gender]=" selected";
 if(isset($invisible)) $sel_invisible[$invisible]=" selected";
 if(isset($nosessionhash)) $sel_nosessionhash[$nosessionhash]=" selected";
 if(isset($usecookies)) $sel_usecookies[$usecookies]=" selected";
 if(isset($admincanemail)) $sel_admincanemail[$admincanemail]=" selected";
 if(isset($showemail)) $sel_showemail[$showemail]=" selected";
 if(isset($usercanemail)) $sel_usercanemail[$usercanemail]=" selected";
 if(isset($emailnotify)) $sel_emailnotify[$emailnotify]=" selected";
 if(isset($receivepm)) $sel_receivepm[$receivepm]=" selected";
 if(isset($emailonpm)) $sel_emailonpm[$emailonpm]=" selected";
 if(isset($pmpopup)) $sel_pmpopup[$pmpopup]=" selected";
 if(isset($showsignatures)) $sel_showsignatures[$showsignatures]=" selected";
 if(isset($showavatars)) $sel_showavatars[$showavatars]=" selected";
 if(isset($showimages)) $sel_showimages[$showimages]=" selected";
 if(isset($daysprune)) $sel_daysprune[$daysprune]=" selected";
 if(isset($umaxposts)) $sel_umaxposts[$umaxposts]=" selected";

 $result = $db->query("SELECT groupid, title, default_group FROM bb".$n."_groups WHERE default_group <> 1 ORDER BY default_group DESC, title ASC");
 while($row=$db->fetch_array($result)) $group_options.=makeoption($row['groupid'],$row['title'],$groupid);

 $result = $db->query("SELECT * FROM bb".$n."_avatars WHERE userid = 0 AND groupid = 0 AND needposts = 0");
 while($row=$db->fetch_array($result)) $avatar_options.=makeoption($row['avatarid'],$row['avatarname'].".".$row['avatarextension'],$avatarid);

 eval("print(\"".gettemplate("users_add")."\");");
}

if($action=="find") {
 eval("print(\"".gettemplate("users_find")."\");");
}

if($action=="show") {
 if(isset($_REQUEST['offset'])) {
  $offset=intval($_REQUEST['offset']);
  if($offset<1) $offset=1;
 }
 else $offset=1;
 $offset-=1;

 if(isset($_REQUEST['limit'])) {
  $limit=intval($_REQUEST['limit']);
  if($limit<1) $limit=1;
 }
 else $limit=200;

 $where="";
 function add2where($add) {
  global $where;
  if($where) $where.=" AND ".$add;
  else $where=$add;
 }
 if(isset($_REQUEST['activation']) && $_REQUEST['activation']==-1) add2where("activation <> '1'");
 if(isset($_REQUEST['groupid']) && $_REQUEST['groupid']) add2where("groupid = '".intval($_REQUEST['groupid'])."'");
 if(isset($_REQUEST['username']) && $_REQUEST['username']) add2where("username LIKE '%".addslashes(htmlspecialchars($_REQUEST['username']))."%'");
 if(isset($_REQUEST['email']) && $_REQUEST['email']) add2where("email LIKE '%".addslashes(htmlspecialchars($_REQUEST['email']))."%'");


 if(isset($_REQUEST['sortby'])) $sortby=$_REQUEST['sortby'];
 else $sortby="";
 if(isset($_REQUEST['sortorder'])) $sortorder=$_REQUEST['sortorder'];
 else $sortorder="";

 switch($sortorder) {
  case "ASC": break;
  case "DESC": break;
  default: $sortorder="ASC"; break;
 }

 switch($sortby) {
  case "username": break;
  case "email": break;
  case "regdate": break;
  case "lastactivity": break;
  case "userposts": break;
  default: $sortby="username"; break;
 }

 $userbit="";
 $count=0;
 $result=$db->query("SELECT * FROM bb".$n."_users".ifelse($userfields==1," LEFT JOIN bb".$n."_userfields USING (userid)")." ".ifelse($where,"WHERE $where ")."ORDER BY $sortby $sortorder",$limit,$offset);
 if(!$db->num_rows($result)) eval("acp_error(\"".gettemplate("error_noresult")."\");");
 while($row=$db->fetch_array($result)) {
  $rowclass=getone($count++,"firstrow","secondrow");
  $regdate=formatdate($dateformat,$row['regdate']);
  $lastactivity=formatdate($dateformat." ".$timeformat,$row['lastactivity']);
  $username=str_replace("'","\'",$row['username']);
  eval ("\$userbit .= \"".gettemplate("users_showbit")."\";");
 }

 eval("print(\"".gettemplate("users_show")."\");");
}

if($action=="delete") {
 if(isset($_POST['send'])) {
  $userids=trim($_POST['userids']);
  if($userids) {

   $result = $db->query("SELECT avatarid, avatarextension FROM bb".$n."_avatars WHERE userid IN ($userids)");
   while($row=$db->fetch_array($result)) @unlink("../images/avatars/avatar-$row[avatarid].$row[avatarextension]");
   $db->unbuffered_query("DELETE FROM bb".$n."_avatars WHERE userid IN ($userids)",1);

   $db->unbuffered_query("DELETE FROM bb".$n."_events WHERE userid IN ($userids)",1);
   $db->unbuffered_query("DELETE FROM bb".$n."_folders WHERE userid IN ($userids)",1);
   $db->unbuffered_query("DELETE FROM bb".$n."_moderators WHERE userid IN ($userids)",1);
   $db->unbuffered_query("UPDATE bb".$n."_posts SET userid=0 WHERE userid IN ($userids)",1);
   $db->unbuffered_query("DELETE FROM bb".$n."_privatemessage WHERE senderid IN ($userids) OR recipientid IN ($userids)",1);
   $db->unbuffered_query("DELETE FROM bb".$n."_searchs WHERE userid IN ($userids)",1);
   $db->unbuffered_query("DELETE FROM bb".$n."_subscribeboards WHERE userid IN ($userids)",1);
   $db->unbuffered_query("DELETE FROM bb".$n."_subscribethreads WHERE userid IN ($userids)",1);
   $db->unbuffered_query("UPDATE bb".$n."_threads SET starterid=0 WHERE starterid IN ($userids)",1);
   $db->unbuffered_query("UPDATE bb".$n."_threads SET lastposterid=0 WHERE lastposterid IN ($userids)",1);
   $db->unbuffered_query("DELETE FROM bb".$n."_userfields WHERE userid IN ($userids)",1);
   $db->unbuffered_query("DELETE FROM bb".$n."_users WHERE userid IN ($userids)",1);
   $db->unbuffered_query("DELETE FROM bb".$n."_votes WHERE userid IN ($userids)",1);
   $db->unbuffered_query("DELETE FROM bb".$n."_votes WHERE id IN ($userids) AND votemode=3",1);
   $db->unbuffered_query("DELETE FROM bb".$n."_sessions WHERE userid IN ($userids)",1);
  }
  header("Location: users.php?action=find&sid=$session[hash]");
  exit();
 }

 if(isset($_REQUEST['userid']) && is_array($_REQUEST['userid']) && count($_REQUEST['userid'])) $userids=implode(',',$_REQUEST['userid']);
 else eval("acp_error(\"".gettemplate("error_selectnouser")."\");");

 $users="";
 $result=$db->query("SELECT userid, username FROM bb".$n."_users WHERE userid IN ($userids)");
 if(!$db->num_rows($result)) eval("acp_error(\"".gettemplate("error_selectnouser")."\");");
 while($row=$db->fetch_array($result)) {
  if($users) $users.=", ".makehreftag("../profile.php?userid=$row[userid]&sid=$session[hash]",$row['username'],"_blank");
  else $users=makehreftag("../profile.php?userid=$row[userid]&sid=$session[hash]",$row['username'],"_blank");
 }
 eval("print(\"".gettemplate("users_delete")."\");");
}

if($action=="edit") {
 $userid=intval($_REQUEST['userid']);
 $result=$db->query_first("SELECT u.*, uf.*, g.ismod + g.issupermod AS moderator FROM bb".$n."_users u LEFT JOIN bb".$n."_userfields uf USING (userid) LEFT JOIN bb".$n."_groups g ON (u.groupid=g.groupid) WHERE u.userid='$userid'");
 if(!$result['userid']) eval("acp_error(\"".gettemplate("error_selectnouser")."\");");

 require("./lib/class_tpl_file.php");
 $tpl = new tpl(0,0,"../");

 if(isset($_POST['send'])) {
  reset($_POST);
  while(list($key,$val)=each($_POST)) {
   if(is_string($val)) $$key=trim($val);
   elseif(is_array($val)) $$key=trim_array($val);
  }

  $username=preg_replace("/\s{2,}/"," ",$username);
  $error="";
  $fieldvalues="";

  $pfields = $db->query("SELECT profilefieldid, required FROM bb".$n."_profilefields ORDER BY profilefieldid ASC");
  while($row=$db->fetch_array($pfields)) {
   if($fieldvalues) $fieldvalues.=",field$row[profilefieldid]='".addslashes(htmlspecialchars($field[$row[profilefieldid]]))."'";
   else $fieldvalues.="field$row[profilefieldid]='".addslashes(htmlspecialchars($field[$row[profilefieldid]]))."'";
  }

  if(!$username || !$email) eval ("\$error .= \"".$tpl->get("register_error1")."\";");
  if(strtolower(htmlspecialchars($username))!=strtolower($result['username']) && !verify_username($username)) eval ("\$error .= \"".$tpl->get("register_error3")."\";");
  if($email!=$result['email'] && !verify_email($email)) eval ("\$error .= \"".$tpl->get("register_error4")."\";");
  if($error) eval ("\$error = acp_error_frame(\"".gettemplate("users_add_error")."\");");
  else {
   if($homepage && !preg_match("/[a-zA-Z]:\/\//si", $homepage)) $homepage = "http://".$homepage;
   if($day && $month && $year) $birthday=ifelse(strlen($year)==4,$year,ifelse(strlen($year)==2,"19$year","0000"))."-".ifelse($month<10,"0$month",$month)."-".ifelse($day<10,"0$day",$day);
   else $birthday = "0000-00-00";

   $username=htmlspecialchars($username);
   if($username!=$result['username']) {
    $db->unbuffered_query("UPDATE bb".$n."_boards SET lastposter='".addslashes($username)."' WHERE lastposterid='$userid'",1);
    $db->unbuffered_query("UPDATE bb".$n."_posts SET username='".addslashes($username)."' WHERE userid='$userid'",1);
    $db->unbuffered_query("UPDATE bb".$n."_posts SET editor='".addslashes($username)."' WHERE editorid='$userid'",1);
    $db->unbuffered_query("UPDATE bb".$n."_threads SET starter='".addslashes($username)."' WHERE starterid='$userid'",1);
    $db->unbuffered_query("UPDATE bb".$n."_threads SET lastposter='".addslashes($username)."' WHERE lastposterid='$userid'",1);
   }

   $rankid = $db->query_first("SELECT rankid FROM bb".$n."_ranks WHERE groupid IN (0,'$groupid') AND needposts < '$result[userposts]' AND gender IN (0,'$gender') ORDER BY needposts DESC, gender DESC",1);

   if($result['avatarid'] && $result['avatarid']!=$avatarid) {
    $avatar=$db->query_first("SELECT * FROM bb".$n."_avatars WHERE avatarid='$result[avatarid]'");
    if($avatar['userid']==$userid) {
     @unlink("../images/avatars/avatar-$avatar[avatarid].$avatar[avatarextension]");
     $db->unbuffered_query("DELETE FROM bb".$n."_avatars WHERE avatarid='$avatar[avatarid]'",1);
    }
   }

   if($groupid!=$result['groupid'] && $result['moderator']>0) {
    $newgroup=$db->query_first("SELECT ismod+issupermod AS moderator FROM bb".$n."_groups WHERE groupid='$groupid'");
    if($newgroup['moderator']==0) $db->unbuffered_query("DELETE FROM bb".$n."_moderators WHERE userid = '$userid'",1);
   }

   if($blocked==1 && $result['blocked']==0) {
    $admincanemail=0;
    $showemail=0;
    $usercanemail=0;
    $receivepm=0;
    $db->unbuffered_query("DELETE FROM bb".$n."_subscribeboards WHERE userid='$userid'",1);
    $db->unbuffered_query("DELETE FROM bb".$n."_subscribethreads WHERE userid='$userid'",1);
   }

   $db->unbuffered_query("UPDATE bb".$n."_users SET username='".addslashes($username)."',email='".addslashes(htmlspecialchars($email))."',groupid='$groupid',rankid='$rankid[rankid]',title='".addslashes(htmlspecialchars($title))."',usertext='".addslashes(htmlspecialchars($usertext))."',signature='".addslashes($signature)."',icq='".intval($icq)."',aim='".addslashes(htmlspecialchars($aim))."',yim='".addslashes(htmlspecialchars($yim))."',msn='".addslashes(htmlspecialchars($msn))."',homepage='".addslashes(htmlspecialchars($homepage))."',birthday='".addslashes(htmlspecialchars($birthday))."',gender='".intval($gender)."',showemail='".intval($showemail)."',admincanemail='".intval($admincanemail)."',usercanemail='".intval($usercanemail)."',invisible='".intval($invisible)."',usecookies='".intval($usecookies)."',styleid='".intval($styleid)."',daysprune='".intval($daysprune)."',timezoneoffset='".addslashes(htmlspecialchars($default_timezoneoffset))."',dateformat='".addslashes(htmlspecialchars($dateformat))."',timeformat='".addslashes(htmlspecialchars($timeformat))."',emailnotify='".intval($emailnotify)."',receivepm='".intval($receivepm)."',emailonpm='".intval($emailonpm)."',pmpopup='".intval($pmpopup)."',umaxposts='".intval($umaxposts)."',showsignatures='".intval($showsignatures)."',showavatars='".intval($showavatars)."',showimages='".intval($showimages)."',nosessionhash='".intval($nosessionhash)."', blocked='".intval($blocked)."', avatarid = '".intval($avatarid)."' WHERE userid='$userid'",1);
   if($fieldvalues) $db->unbuffered_query("UPDATE bb".$n."_userfields SET $fieldvalues WHERE userid='$userid'",1);

   header("Location: users.php?action=find&sid=$session[hash]");
   exit();
  }
 }
 else {
  while(list($key,$val)=each($result)) {
   if(substr($key,0,5)=="field") $field[intval(substr($key,5))]=$val;
   else $$key=$val;
  }
  $signature=htmlspecialchars($signature);
  $birthday=explode("-",$birthday);
  $day=$birthday[2];
  $month=$birthday[1];
  if($birthday[0]!="0000") $year=$birthday[0];
 }

 if(isset($_POST)) {
  reset($_POST);
  while(list($key,$val)=each($_POST)) {
   if(is_string($val)) $$key=htmlspecialchars(trim($val));
   elseif(is_array($val)) $$key=htmlspecialchars_array(trim_array($val));
  }
 }
 $months = explode("|", gettemplate("months"));
 for($i=1;$i<=31;$i++) $day_options.=makeoption($i,$i,$day);
 for($i=1;$i<=12;$i++) $month_options.=makeoption($i,getmonth($i),$month);

 $timezones = explode("\n", $tpl->get("timezones"));
 for($i=0;$i<count($timezones);$i++) {
  $parts = explode("|", trim($timezones[$i]));
  $timezone_options .= makeoption($parts[0],"(GMT".ifelse($parts[1]," ".$parts[1],"").") $parts[2]",$default_timezoneoffset);
 }

 $result = $db->query("SELECT styleid, stylename FROM bb".$n."_styles WHERE default_style = 0 ORDER BY stylename ASC");
 while($row=$db->fetch_array($result)) $style_options.=makeoption($row['styleid'],$row['stylename'],$styleid);

 if(isset($gender)) $sel_gender[$gender]=" selected";
 if(isset($invisible)) $sel_invisible[$invisible]=" selected";
 if(isset($nosessionhash)) $sel_nosessionhash[$nosessionhash]=" selected";
 if(isset($usecookies)) $sel_usecookies[$usecookies]=" selected";
 if(isset($admincanemail)) $sel_admincanemail[$admincanemail]=" selected";
 if(isset($showemail)) $sel_showemail[$showemail]=" selected";
 if(isset($usercanemail)) $sel_usercanemail[$usercanemail]=" selected";
 if(isset($emailnotify)) $sel_emailnotify[$emailnotify]=" selected";
 if(isset($receivepm)) $sel_receivepm[$receivepm]=" selected";
 if(isset($emailonpm)) $sel_emailonpm[$emailonpm]=" selected";
 if(isset($pmpopup)) $sel_pmpopup[$pmpopup]=" selected";
 if(isset($showsignatures)) $sel_showsignatures[$showsignatures]=" selected";
 if(isset($showavatars)) $sel_showavatars[$showavatars]=" selected";
 if(isset($showimages)) $sel_showimages[$showimages]=" selected";
 if(isset($daysprune)) $sel_daysprune[$daysprune]=" selected";
 if(isset($umaxposts)) $sel_umaxposts[$umaxposts]=" selected";
 if(isset($blocked)) $sel_blocked[$blocked]=" selected";

 $result = $db->query("SELECT groupid, title, default_group FROM bb".$n."_groups WHERE default_group <> 1 ORDER BY default_group DESC, title ASC");
 while($row=$db->fetch_array($result)) $group_options.=makeoption($row['groupid'],$row['title'],$groupid);

 $color="red";
 $result = $db->query("SELECT * FROM bb".$n."_avatars WHERE (userid = 0 AND groupid IN (0,$groupid) AND needposts <= '$userposts') OR userid = '$userid' ORDER BY userid DESC");
 while($row=$db->fetch_array($result)) {
  if($color=="red" && $row['userid']==0) {
   $avatar_options.=makeoption(0,"---------------","",0);
   $color="green";
  }
  $avatar_options.=makeoption($row['avatarid'],$row['avatarname'].".".$row['avatarextension'],$avatarid,1,$color);
 }

 eval("print(\"".gettemplate("users_edit")."\");");
}

if($action=="email") {
 if(isset($_REQUEST['userid']) && is_array($_REQUEST['userid']) && count($_REQUEST['userid'])) $userids=implode(',',$_REQUEST['userid']);
 elseif(isset($_REQUEST['userid']) && $_REQUEST['userid']=="all") $userids="all";
 else eval("acp_error(\"".gettemplate("error_selectnouser")."\");");

 if($userids!="all") {
  $users="";
  $result=$db->query("SELECT userid, username FROM bb".$n."_users WHERE userid IN ($userids)");
  if(!$db->num_rows($result)) eval("acp_error(\"".gettemplate("error_selectnouser")."\");");
  while($row=$db->fetch_array($result)) {
   if($users) $users.=", ".makehreftag("../profile.php?userid=$row[userid]&sid=$session[hash]",$row['username'],"_blank");
   else $users=makehreftag("../profile.php?userid=$row[userid]&sid=$session[hash]",$row['username'],"_blank");
  }
 }
 else eval ("\$users = \"".gettemplate("users_email_all")."\";");
 eval("print(\"".gettemplate("users_email")."\");");
}

if($action=="emailsend") eval("print(\"".gettemplate("users_emailsend")."\");");

if($action=="activate") {
 if(isset($_REQUEST['userid']) && is_array($_REQUEST['userid']) && count($_REQUEST['userid'])) $userids=implode(',',$_REQUEST['userid']);
 else eval("acp_error(\"".gettemplate("error_selectnouser")."\");");

 $result=$db->query("SELECT username, email FROM bb".$n."_users WHERE userid IN ($userids) AND activation<>1");
 if($db->num_rows($result)) {
  require("./lib/class_tpl_file.php");
  $tpl = new tpl(0,0,"../");

  while($row=$db->fetch_array($result)) {
   eval ("\$mail_subject = \"".$tpl->get("ms_activation")."\";");
   eval ("\$mail_text = \"".$tpl->get("mt_activation")."\";");
   mailer($row['email'],$mail_subject,$mail_text);
  }
  $db->unbuffered_query("UPDATE bb".$n."_users SET activation=1 WHERE userid IN ($userids) AND activation<>1",1);
 }
 header("Location: users.php?action=find&sid=$session[hash]");
 exit();
}

if($action=="pw") {
 $userid=$_REQUEST['userid'];
 $user=$db->query_first("SELECT username, email FROM bb".$n."_users WHERE userid = '$userid'");

 if(isset($_POST['send'])) {
  if($_POST['mode']==1) $newpassword=password_generate();
  else $newpassword=$_POST['newpassword'];

  $db->unbuffered_query("UPDATE bb".$n."_users SET password='".md5($newpassword)."' WHERE userid='$userid'",1);

  if($_POST['sendmail']==1) {
   require("./lib/class_tpl_file.php");
   $tpl = new tpl(0,0,"../");

   eval ("\$mail_subject = \"".$tpl->get("ms_newpw")."\";");
   eval ("\$mail_text = \"".$tpl->get("mt_newpw")."\";");
   mailer($user['email'],$mail_subject,$mail_text);
  }
  eval("print(\"".gettemplate("window_close")."\");");
  exit();
 }

 eval("print(\"".gettemplate("users_pw")."\");");
}
?>
