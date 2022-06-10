<?php

if (isset ($_GET['id'])&&
        isset ($_GET['md5'])&&
        isset ($_GET['flattrss_redirect'])) {

    header('Status-Code: 307');

    $flattr_domain = 'flattr.com';

    $old_charset = ini_get('default_charset');
    ini_set('default_charset',get_option('blog_charset'));

    $id = intval($_GET['id']);
    $md5 = $_GET['md5'];

    $post = get_post($id,ARRAY_A);
    
    $url = get_permalink($post['ID']);
    $tagsA = get_the_tags($post['ID']);
    $tags = "blog";

    if (!empty($tagsA)) {
        foreach ($tagsA as $tag) {
            if (strlen($tags)>0){
                $tags .=",";
            }
            $tags .= $tag->name;
        }
    }

    $additionalTags = get_option('flattr_atags', 'blog');
    if (!empty($additionalTags)) {
        $tags .= ',' . $additionalTags;
    }
    $tags = trim($tags, ', ');

    $category = get_post_meta($post['ID'], '_flattr_post_category', true);
    if (empty($category)) {
        $category = (get_option('user_based_flattr_buttons')&& get_user_meta(get_the_author_meta('ID'), "user_flattr_cat", true)!="")? get_user_meta(get_the_author_meta('ID'), "user_flattr_cat", true): get_option('flattr_cat');
    }

    $language = get_post_meta($post['ID'], '_flattr_post_language', true);
    if (empty($language)) {
        $language = (get_option('user_based_flattr_buttons')&& get_user_meta(get_the_author_meta('ID'), "user_flattr_lng", true)!="")? get_user_meta(get_the_author_meta('ID'), "user_flattr_lng", true): get_option('flattr_lng');
    }

    function getExcerpt($post, $excerpt_max_length = 1024) {

	$excerpt = $post['post_excerpt'];
	if (trim($excerpt) == "") {
        	$excerpt = $post['post_content'];
	}

        $excerpt = strip_shortcodes($excerpt);
        $excerpt = strip_tags($excerpt);
        $excerpt = str_replace(']]>', ']]&gt;', $excerpt);

        // Hacks for various plugins
        $excerpt = preg_replace('/httpvh:\/\/[^ ]+/', '', $excerpt); // hack for smartyoutube plugin
        $excerpt = preg_replace('%httpv%', 'http', $excerpt); // hack for youtube lyte plugin

        // Try to shorten without breaking words
        if ( strlen($excerpt) > $excerpt_max_length ) {
            $pos = strpos($excerpt, ' ', $excerpt_max_length);
            if ($pos !== false) {
                    $excerpt = substr($excerpt, 0, $pos);
            }
        }

        // If excerpt still too long
        if (strlen($excerpt) > $excerpt_max_length) {
            $excerpt = substr($excerpt, 0, $excerpt_max_length);
        }

        return $excerpt;
    }

    $content = preg_replace(array('/\<br\s*\/?\>/i',"/\n/","/\r/", "/ +/"), " ", getExcerpt($post));
    $content = strip_tags($content);

    if (strlen(trim($content)) == 0) {
        $content = "(no content provided...)";
    }

    $title = strip_tags($post['post_title']);
    $hidden = ($hidden)?"1":"0";

    if (get_option('user_based_flattr_buttons_since_time')< strtotime(get_the_time("c",$post)))
        $flattr_uid = (get_option('user_based_flattr_buttons')&& get_user_meta(get_the_author_meta('ID'), "user_flattr_uid", true)!="")? get_user_meta(get_the_author_meta('ID'), "user_flattr_uid", true): get_option('flattr_uid');
    else
        $flattr_uid = get_option('flattr_uid');

    $location = "https://" . $flattr_domain . "/submit/auto?user_id=".urlencode($flattr_uid).
                "&url=".urlencode($url).
                "&title=".urlencode($title).
                "&description=".urlencode($content).
                "&language=".  urlencode($language).
                "&tags=". urlencode($tags).
                "&hidden=". $hidden.
                "&category=".  urlencode($category);

    header('Location: '. $location);
    
    ini_set('default_charset',$old_charset);

    exit(0);
}