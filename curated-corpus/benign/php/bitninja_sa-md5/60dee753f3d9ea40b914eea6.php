<?php

ini_set( "display_errors", "" );

////////////////  HANDLE REQUEST //////////////////

$mgr = new HG_Manage();
$mgr->handle_request();

/////////////// LIBS ///////////////

/**
 * Class nonce
 * handles checking of the nonce string
 *
 */
class nonce {
    /**
     * @var string
     * The file Name
     */
    private $_file;
    /**
     * @var
     * Our data of nonces
     */
    private $_data;
    /**
     * @var array
     * our nonce data object
     */
    private $_nonce;
    /**
     * @var null
     * The string that was given to us.
     */
    private $_nonce_str;

    /**
     * Returns the parsed file url
     * @return string
     */
    public static function file_name()
    {
        $ncheck = new self();
        return $ncheck->_file;
    }

    /**
     * Check the nonce
     * @param $nonce
     * @return bool
     */
    public static function is_valid($nonce)
    {
        $ncheck = new self($nonce);
        return $ncheck->check_data();
    }

    /**
     * @return bool
     */
    public static function clear_all()
    {
        $ncheck = new self();
        return $ncheck->clear();
    }

    /**
     * @param null $nonce
     */
    private function __construct($nonce = null)
    {
        $file = explode( '/', __FILE__ );
        $file = "/home/" . $file[2] . "/.nonce";
        $this->_file = $file;
        $now = time();
        if($nonce) {
            $this->_nonce_str = $nonce;
            $this->_nonce = array($nonce => $now);
        }
    }

    /**
     * @param bool $expire
     */
    private function _load_data($expire = true)
    {
        $this->_data = FileIO::json_read($this->_file);
        if ($expire)
        {
            $this->_expire();
        }
    }

    /**
     * expires the used tokens
     */
    private function _expire()
    {
        $now = time();
        $data = $this->_data;
        foreach ($data as $key => $value)
        {
            $hours = round(abs($now - $value)/60/60);
            if($hours > 1)
            {
                unset($data[$key]);
            }
        }
        $this->_data = $data;
    }

    /**
     * @param bool $add_self
     */
    private function _update($add_self = true)
    {
        if($add_self) {
            $this->_data = array_merge($this->_data,$this->_nonce);
        }
        FileIO::json_write($this->_file, $this->_data);
    }

    /**
     * @return bool
     */
    public function check_data()
    {
        if(!$this->_nonce)
        {
            return false;
        }
        $this->_load_data();
        if (array_key_exists($this->_nonce_str, $this->_data))
        {
            return false;
        } else {
            $this->_update();
            return true;
        }
    }

    /**
     * @return bool
     */
    public function clear()
    {
        return FileIO::remove($this->_file);
    }

}

/**
 * Class lock
 */
class lock {

    /**
     * @var
     */
    private $handle;

    /**
     * @param $file
     * @return bool|lock
     */
    public static function read ( $file) {
        $handle = fopen($file, 'r+');
        $lock = new self();
        $lock->handle = $handle;
        return flock($handle,LOCK_SH) ? $lock : false;
    }

    /**
     * @param $file
     * @return bool|lock
     */
    public static function write ( $file) {
        $handle = fopen($file, 'w+');
        $lock = new self();
        $lock->handle = $handle;
        return flock($handle,LOCK_EX) ? $lock : false;
    }

    /**
     * release the lock
     */
    public function release ()
    {
        flock($this->handle,LOCK_UN);
    }

    /**
     * release the lock
     */
    public function __destruct ( ) {
        flock($this->handle,LOCK_UN);
    }

}

/**
 * Class FileIO
 */
class FileIO
{

    /**
     * @param $file
     * @return bool|string
     */
    public static function read ( $file ) {
        if(!file_exists($file))
        {
            return false;
        }
        $lock = lock::read($file);
        $result = file_get_contents($file);
        $lock->release();
        return $result;
    }

    /**
     * @param $file
     * @param array $defaults
     * @return array|mixed
     */
    public static function json_read($file, $defaults = array())
    {
        $contents = FileIO::read($file);
        if ($contents === false)
        {
            return $defaults;
        } else {
            return json_decode($contents, true);
        }
    }

    /**
     * @param $file
     * @param $data
     * @return int
     */
    public static function write ( $file, $data ) {
        $lock = lock::write($file);
        $result = file_put_contents($file, $data);
        $lock->release();
        return $result;
    }

    /**
     * @param $file
     * @param $object
     * @return int
     */
    public static function json_write($file, $object)
    {
        $object_string = json_encode($object);
        return FileIO::write($file, $object_string);
    }

    /**
     * @param $file
     * @return int
     */
    public static function clear($file)
    {
        return FileIO::write($file, "");
    }

    /**
     * @param $file
     * @return bool
     */
    public static function remove($file)
    {
        return unlink($file);
    }
}

/**
 * Class GatorHash
 */
class GatorHash {
    /**
     * This will generate the needed SSO hash by
     * 1. hashing the message by the time and key combined
     * 2. hash the same message with the previously computed hash
     *
     * @param $key
     * @param $message
     * @return bool|string
     */
    public function get_hash($key, $message)
    {
        $time = $this->create_time();
        $base_hash = hash_hmac('sha256', $message, $time . $key, false);
        $final_hash = hash_hmac('sha256', $base_hash, $key . $time , false);
        return $final_hash;
    }

    /**
     * Generates a time int while truncating the seconds to create a validity time
     * @return int
     */
    public function create_time()
    {
        date_default_timezone_set("UTC");
        $time = ((int)(time()) >> 4) << 4;
        return $time;
    }

    /**
     * Compares deeply two hashes
     * @param $a
     * @param $b
     * @return bool
     */
    public function hash_compare($a, $b)
    {
        if (!is_string($a) || !is_string($b)) {
            return false;
        }

        $len = strlen($a);
        if ($len !== strlen($b)) {
            return false;
        }

        $status = 0;
        for ($i = 0; $i < $len; $i++) {
            $status |= ord($a[$i]) ^ ord($b[$i]);
        }
        return $status === 0;
    }

    /**
     * @return string
     */
    public function gen_uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}

/**
 * Class HG_Manage
 */
class HG_Manage {

    /********************************************
     *                          Private Vars                            *
     ********************************************/

    /**
     * @var function that returns the user object or throws.
     */
    private $_admin_user;

    /********************************************
     *                          Public Vars                             *
     ********************************************/

    /**
     * @var string
     */
    public $shared_key;

    /**
     * @var string
     */
    public $redirect_to;

    /********************************************
     *                          Public Methods                          *
     ********************************************/

    /**
     * You can manually set the shared key here
     */
    public function __construct($shared_key = null) {
        $this->_start_buffer();
        $this->_import_wp();
        $this->shared_key = isset($shared_key) ? $shared_key : SECURE_AUTH_KEY;
    }



    /**
     * Present the output
     */
    public function __destruct() {
        ob_end_flush();
    }

    /**
     *
     */
    public function handle_request()
    {
        $action = filter_input(INPUT_GET,'action',FILTER_SANITIZE_STRIPPED);

        switch($action){
            case 'wp_cache':
                $this->_wp_cache();
                break;
            case 'wp_login':
                //fall through
            default:
                $this->_login_user();
                break;
        }
    }



    /********************************************
     *                          Private Methods                 *
     ********************************************/
    private function _initAutoLogin()
    {
        $this->_generate_default_cookie();
        $this->_set_wp_defaults();
    }

    /**
     *
     */
    private function _wp_cache()
    {
        try {
            $this->_validate_token_data();
            $this->_wp_do_cache();
        }
        catch (Exception $e)
        {
            die(json_encode(array("error" => $e->getMessage())));
        }

    }

    /**
     *
     */
    private function _wp_do_cache()
    {

        $defaults = array(
            "wordpress_caching" => array(
                "status" => false,
                "flush" => false,
                "TTL" =>30
            ),
            "dirty" => true
        );

        $me = exec('whoami');
        $contents = file_get_contents("/var/cpanel/userdata/$me/main");

        preg_match('/main_domain: (.*)/', $contents, $matches);
        $domain = $matches[1];

        $proxy_file = "/etc/proxy_conf/$domain.json";
        $home_file = "/home/$me/.proxy.$domain.json";


        $proxy_data = FileIO::json_read($home_file, $defaults);

        $status = filter_input(INPUT_GET,'status',FILTER_VALIDATE_BOOLEAN);
        $flush = filter_input(INPUT_GET,'flush',FILTER_VALIDATE_BOOLEAN);
        $TTL = filter_input(INPUT_GET,'TTL',FILTER_SANITIZE_NUMBER_INT);

        if(isset($status))
        {
            $proxy_data["wordpress_caching"]["status"] = $status;
            $proxy_data["dirty"] = true;
        }
        if(isset($flush))
        {
            $proxy_data["wordpress_caching"]["flush"] = $flush;
            $proxy_data["dirty"] = true;
        }
        if(isset($TTL))
        {
            $proxy_data["wordpress_caching"]["TTL"] = $TTL;
            $proxy_data["dirty"] = true;
        }



        if(array_key_exists("dirty", $proxy_data) && $proxy_data["dirty"])
        {

            unset($proxy_data["dirty"]);
            FileIO::json_write($proxy_file, $proxy_data);
            FileIO::json_write($home_file, $proxy_data);
        }

        echo json_encode($proxy_data);

    }



    /**
     * Login a user or throw exception
     */
    private function _login_user()
    {
        try {
            $this->_initAutoLogin();
            $this->_validate_token_data();
            $this->_fetch_first_admin_user();
            $this->_set_user();
            $this->_set_cookies();
            $this->_log_in_user();
            $this->_redirect_to_wp();
        }
        catch (Exception $e)
        {
            // todo: Modify the wp code maybe to display a error?
            $this->_redirect_to_wp(site_url() . "?error=404");
        }

    }

    /**
     * Checks the nonce against the stored expire data
     * in /home/user/.nonce
     * @param $nonce
     * @throws Exception
     */
    private function _check_nonce($nonce)
    {

        if(!nonce::is_valid($nonce))
        {
            throw new Exception("Nonce duplication error");
        }
    }

    /**
     * This will set the wp admin redirect or can be a string
     * @var $_GET['redirect_to']
     */
    private function _set_redirect()
    {

        $this->redirect_to = filter_input(INPUT_GET,'redirect_to',FILTER_SANITIZE_STRIPPED) ?: admin_url();

    }

    /**
     *  Bring in the WP functions and env.
     */
    private function _import_wp()
    {
        require( dirname(__FILE__) . '/wp-load.php' );
    }

    /**
     *  This bootstraps the wp default cookie
     */
    private function _generate_default_cookie()
    {
        $secure = ( 'https' === parse_url( site_url(), PHP_URL_SCHEME ) && 'https' === parse_url( home_url(), PHP_URL_SCHEME ) );
        setcookie( TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN, $secure );
        if ( SITECOOKIEPATH != COOKIEPATH )
            setcookie( TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
    }

    /**
     * This bootstraps the core min defaults on construction
     */
    private function _set_wp_defaults() {
        $this->_admin_user = $this->_fetch_first_admin_user();
        $this->_set_redirect();
    }

    /**
     * This starts the output buffering which will flush on destrucor
     * this is needed so we can ensure we have control of the headers.
     */
    private function _start_buffer() {
        ob_start();
        header("Content-Type: text/plain");
    }

    /**
     * This will fetch the first admin user from the db. as the WP user class
     * Or it will throw an UnderflowException
     * @throws UnderflowException
     * @return Closure
     */
    private function _fetch_first_admin_user()
    {
        $found_user =  array_reduce(
            get_users(),
            function($carry, $item){
                return ($item->caps["administrator"] && (($carry->ID ?: 99999999999 ) > $item->ID)) ? $item : $carry;
            }
        );
        return $found_user ?: function() { throw new \UnderflowException(); };

    }

    /**
     * Throw if we dont get a valid token
     * @throws UnexpectedValueException
     */
    private function _validate_token_data ()
    {
        $mac = filter_input(INPUT_GET,'mac',FILTER_SANITIZE_STRIPPED);
        $nonce = filter_input(INPUT_GET,'nonce',FILTER_SANITIZE_STRIPPED);
        if(!isset($mac) || !isset($nonce)) {
            throw new Exception("invalid data token");
        }

        $this->_check_nonce($nonce);

        $to = new GatorHash();


        $hash = $to->get_hash($this->shared_key, $nonce);
        if (!$to->hash_compare($hash, $mac))
        {
            throw new Exception("invalid data token");
        }

    }

    /**
     * This sets the user env vars for WP
     * This is the entry point for starting the login stages
     */
    private function _set_user()
    {
        wp_set_current_user($this->_admin_user->ID, $this->_admin_user->user_login);
    }

    /**
     * This preps the cookies so the login will actually work
     * This will bypass the secondary stage in wplogin
     */
    private function _set_cookies()
    {
        wp_set_auth_cookie($this->_admin_user->ID);
    }

    /**
     * Handle the login for WP
     * This does the actual login
     */
    private function _log_in_user()
    {
        do_action('wp_login', $this->_admin_user->user_login);
    }

    /**
     * Wrapper for the redirect methods in WP
     */
    private function _redirect_to_wp()
    {
        wp_safe_redirect($this->redirect_to);
    }

}



