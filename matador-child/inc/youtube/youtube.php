<?php

class PlcYoutube
{

    private static $instance = null;
    private static $errors = array();
    private static $links = array();
    private static $data = array();
    private static $yt_data = array();
    private static $yt_ids = array();

    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->set_data('API_KEY', 'AIzaSyACZBcvZblc1zzc4GzRlbj3RW5SuKDnLxA');
        $this->set_data('frontend', false);

        add_action('admin_menu', array($this, 'youtube_menu'));
        add_shortcode('youtube-uploader', array($this, 'youtube_shortcode'));

        add_action('wp_ajax_nopriv_plc_upload_youtube_ajax', array($this, 'upload_youtube_ajax'));
        add_action('wp_ajax_plc_upload_youtube_ajax', array($this, 'upload_youtube_ajax'));

        $this->run_plugin();
    }

    public function youtube_menu()
    {
        add_menu_page('Youtube', 'Youtube', 'administrator', 'youtube_option', array($this, 'youtube_page'), 'dashicons-video-alt3');
    }

    public function youtube_page()
    {
        global $plcCategoryTags;

        $catTag = $plcCategoryTags->get_category_tag_array();
        $categories = $catTag['categories'];
        $tags = $catTag['tags'];

        $this->_proccess_post();

        ob_start();
        require_once( get_stylesheet_directory() . '/inc/youtube/view/index.php' );
        echo ob_get_clean();
    }

    public function youtube_shortcode($atts)
    {
        global $plcCategoryTags;

        wp_register_script('chained-js', get_stylesheet_directory_uri() . '/assets/js/jquery.chained.min.js', array('jquery'), '1.0.9');
        wp_enqueue_script('chained-js');
        
        $catTag = $plcCategoryTags->get_category_tag_array();
        $categories = $catTag['categories'];
        $tags = $catTag['tags'];

        $errors = $this->get_errors();
       
        
        ob_start();
        require_once ( get_stylesheet_directory() . '/inc/youtube/view/shortcode.php' );
        echo ob_get_clean();
    }

    public function upload_youtube_ajax($atts)
    {
        $this->set_required();
        $this->_proccess_post();

        $errors = $this->get_errors();
        $returnErrors = '';
        if ($errors) {
            foreach ($errors as $error) {
                $returnErrors = '<div class="alert alert-danger">' . $error . '</div>';
            }
        }
        $links = $this->get_link(); 
        $returnlinks = '';
        if ($links) {
            foreach ($links as $link => $title) {
                $returnlinks .= '<a class="btn btn-sm btn-link" href="' . $link . '" target="_blank">' . $title . '</a><br/>';
            }
        }

        $response['links'] = $returnlinks;
        $response['errors'] = $returnErrors;
        wp_send_json($response);
    }

    public function youtube_frontend($post_id, $yt_link)
    {
        $this->_proccess_frontend($post_id, $yt_link);
    }

    public function youtube_checklink($yt_link)
    {
        return $this->_youtube_id_from_url($yt_link);
    }

    private function _proccess_frontend($post_id, $yt_link)
    {
        set_time_limit(0);

        $this->set_required();
        $this->_create_folder();

        $this->set_data('frontend', true);
        $this->set_data('post_id', $post_id);
        $this->set_data('yt_link', $yt_link);

        $this->_youtube_id_from_url($yt_link);
        $this->_set_yt_data();
        $this->_proccess_youtube();
    }

    private function _fix_url($url) {
        return (substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://')
            ? $url
            : 'http://'.$url;
    }

    private function _proccess_post()
    {
        $yt_link = isset($_POST['yt_link']) ? trim($_POST['yt_link']) : '';
        $sport = isset($_POST['sport']) ? trim($_POST['sport']) : '';
        $tip = isset($_POST['tip']) ? trim($_POST['tip']) : '';

        if (isset($_POST["about_coach"]) && !empty($_POST["about_coach"])){
            $text = trim($_POST["about_coach"]);

            if (str_word_count($text, 0) > 100) {
                $words = str_word_count($text, 2);
                $pos = array_keys($words);
                $text = substr($text, 0, $pos[100]);
            }

            $about_coach = $text;
        } else  $about_coach = '';

        $website_coach = isset($_POST['website_coach']) ? $this->_fix_url(trim($_POST['website_coach'])) : '';

        if (!$this->_wesite_id_from_url($website_coach)){
            $this->set_error($website_coach . ' is not a valid URL');
        }elseif ($yt_link) {
            set_time_limit(0);

            $this->_create_folder();

            $this->set_data('sport', $sport);
            $this->set_data('tip', $tip);
            $this->set_data('about_coach', $about_coach);
            $this->set_data('website_coach', $website_coach);

            $yt_link = explode("\n", $yt_link);
            $links = array_filter($yt_link, 'trim');

            $i = 1;
            $this->set_data('i', $i);
            foreach ($links as $link) {
                $this->set_data('yt_link', $link);
                $this->_youtube_id_from_url($link);
            }

            $this->_set_yt_data();
            $this->_proccess_youtube();

        }
        else {
            $this->set_error('Insert some youtube links');
        }
    }

    private function _proccess_youtube()
    {
        $youtube = $this->get_yt_data();
        $frontend = $this->get_data('frontend');

        if ($youtube) {
            foreach ($youtube as $yt) {
                $this->set_data('yt', $yt);

                $this->_save_image();
                $this->_save_attachment();
                if ($frontend === TRUE) {
                    $this->_save_post_thumbnails();
                }
                else {
                    $this->_save_post();
                }
            }
        }
    }

    private function _proccess_curl($data)
    {
        if ($data) {
            foreach ($data['items'] as $vid) {
                $title = $vid['snippet']['title'];
                $description = $vid['snippet']['description'];
                $thumbnail = $this->_get_yt_thumbnail($vid['snippet']['thumbnails']);
                $link = 'https://www.youtube.com/watch?v=' . $vid['id'];

                $video = array(
                    'title' => $title,
                    'description' => $description,
                    'thumbnail' => $thumbnail,
                    'link' => $link
                );
                $this->set_yt_data($video);
            }
        }
    }

    private function _save_image()
    {
        $yt = $this->get_data('yt');
        $yt_thumbnail = $yt['thumbnail'];
        $img = $img_url = '';
        if ($yt_thumbnail) {
            $target_dir = $this->get_data('target_dir');
            $target_url = $this->get_data('target_url');

            $yt_img = $this->_curl($yt_thumbnail);
            $filename = md5(microtime());
            $img = $target_dir . $filename . '.jpg';
            $img_url = $target_url . $filename . '.jpg';
            file_put_contents($img, $yt_img);

            if (!file_exists($img)) {
                $img = $img_url = '';
            }
        }
        $this->set_data('img_path', $img);
        $this->set_data('img_url', $img_url);
    }

    private function _save_attachment()
    {
        global $current_user;

        $img = $this->get_data('img_path');
        $img_url = $this->get_data('img_url');
        if ($img && $img_url) {
            $post_mime_type = image_type_to_mime_type(exif_imagetype($img));
            $attachment = array(
                'post_mime_type' => $post_mime_type,
                'post_title' => date('j F Y h:i') . ' ' . $current_user->user_login,
                'post_content' => '',
                'post_status' => 'inherit',
                'post_type' => 'attachment',
                'guid' => $img_url
            );
            $attach_id = wp_insert_attachment($attachment, $img);

            $this->set_data('attachment_id', $attach_id);

            //Generate matadata
            $attach_data = wp_generate_attachment_metadata($attach_id, $img);
            wp_update_attachment_metadata($attach_id, $attach_data);
        }
    }

    private function _save_post()
    {
        global $current_user;

        $attachment_id = $this->get_data('attachment_id');
        $yt = $this->get_data('yt');

        $post_id = '';
        if ($yt && $attachment_id > 0) {

            $sport = $this->get_data('sport');
            $tip = $this->get_data('tip');
            $about_coach = $this->get_data('about_coach');
            $website_coach = $this->get_data('website_coach');

            $title_sport = explode(',', $sport);
            $title_tip = explode(',', $tip);

            $title = $title_sport[1] . ' - ' . $title_tip[1] . ' | ' . $yt['title'];

            $post = array(
                "ID" => "",
                "post_type" => "post",
                "post_status" => "publish",
                "post_title" => $title,
                "post_author" => $current_user->ID,
                "post_name" => sanitize_title($title),
                "post_category" => array(11)
            );

            $post_id = wp_insert_post($post, false);
            
            wp_set_post_terms( $post_id, array($title_tip[1]));
            
            update_post_meta($post_id, '_sport', $sport);
            update_post_meta($post_id, '_tip', $tip);
            update_post_meta($post_id, '_about_coach', $about_coach);
            update_post_meta($post_id, '_website_coach', $website_coach);
            update_post_meta($post_id, '_tip_title', $yt['title']);

            $link = get_permalink($post_id);
            $this->set_link($link, $title);
        }
        $this->set_data('post_id', $post_id);
        $this->_save_post_thumbnails();
    }

    private function _save_post_thumbnails()
    {
        $attachment_id = $this->get_data('attachment_id');
        $post_id = $this->get_data('post_id');
        $yt = $this->get_data('yt');

        if ($yt && $post_id > 0 && $attachment_id > 0) {
            $meta_id_name = '_p_image_1';
            update_post_meta($post_id, $meta_id_name, wp_get_attachment_url($attachment_id));
            update_post_meta($post_id, $meta_id_name . '_id', $attachment_id);
            wp_update_post(
                    array(
                        'ID' => $attachment_id,
                        'post_parent' => $post_id
                    )
            );
            set_post_thumbnail($post_id, $attachment_id);
            update_post_meta($post_id, '_video_link', $yt['link']);
        }
    }

    private function _create_folder()
    {
        global $current_user;

        $wp_upload_dir = wp_upload_dir();
        $target_dir = $wp_upload_dir['basedir'] . "/journeys/" . trim($current_user->user_login) . "/";
        $target_url = $wp_upload_dir['baseurl'] . "/journeys/" . trim($current_user->user_login) . "/";
        $this->set_data('target_dir', $target_dir);
        $this->set_data('target_url', $target_url);

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
    }

    private function _set_yt_data()
    {
        $api_key = $this->get_data('API_KEY');
        $yt_ids = $this->get_yt_ids();
        if ($yt_ids) {
            $ids = is_array($yt_ids) ? implode(',', $yt_ids) : $yt_ids;
            $url = urldecode("https://www.googleapis.com/youtube/v3/videos?id=" . $ids . "&key=" . $api_key . "&fields=items(id,snippet(title,description,thumbnails))&part=snippet");

            $data = $this->_curl($url, true);
            $this->_proccess_curl($data);
        }
    }

    private function _get_yt_thumbnail($thumbs)
    {
        if (isset($thumbs['maxres']['url'])) {
            return $thumbs['maxres']['url'];
        }
        elseif (isset($thumbs['standard']['url'])) {
            return $thumbs['standard']['url'];
        }
        elseif (isset($thumbs['high']['url'])) {
            return $thumbs['high']['url'];
        }
        elseif (isset($thumbs['medium']['url'])) {
            return $thumbs['medium']['url'];
        }
        elseif (isset($thumbs['default']['url'])) {
            return $thumbs['default']['url'];
        }
        else {
            return '';
        }
    }

    private function _wesite_id_from_url($link = null){
        if ($link == null)    return TRUE;

        $url = urldecode(rawurldecode($link));

        if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
            return TRUE;
        }

        return FALSE;
    }

    private function _youtube_id_from_url($link)
    {
        $url = urldecode(rawurldecode($link));

        $result = preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
        if ($result) {
            $yt_id = trim($matches[1]);
            $this->set_yt_ids($yt_id);
            return TRUE;
        }
        $this->set_error($link . ' is not a youtube link');
        return FALSE;
    }

    private function set_yt_data($value)
    {
        $this->yt_data[] = $value;
    }

    private function get_yt_data()
    {
        return $this->yt_data;
    }

    private function set_error($value)
    {
        $this->errors[] = $value;
    }

    private function get_errors()
    {
        return $this->errors;
    }

    private function set_yt_ids($value)
    {
        $this->yt_ids[] = $value;
    }

    private function get_yt_ids()
    {
        return $this->yt_ids;
    }

    private function set_link($key, $value)
    {
        $this->links[$key] = $value;
    }
    private function get_link($key = NULL)
    {
        if ($key) {
            return $this->links[$key];
        }
        else {
            return $this->links;
        }
    }
    private function set_data($key, $value)
    {
        $this->data[$key] = $value;
    }

    private function get_data($key = NULL)
    {
        if ($key) {
            return $this->data[$key];
        }
        else {
            return $this->data;
        }
    }

    private function _curl($url, $decode = FALSE)
    {
        $data = wp_remote_get($url);
        if (is_array($data) && $data['response']['code'] !== 404) {
            if ($decode) {
                $return = json_decode($data['body'], true);
            }
            else {
                $return = $data['body'];
            }
            return $return;
        }
        return false;
    }

    private function set_required()
    {
        require_once( ABSPATH . 'wp-load.php' );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
    }

    private function run_plugin()
    {
        
    }

}

$plcYoutube = PlcYoutube::get_instance();
