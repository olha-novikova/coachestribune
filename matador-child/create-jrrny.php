<?php
header('Content-Type: application/json');
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once($parse_uri[0] . 'wp-load.php');
$ret = array();

if (is_user_logged_in()) {
    global $current_user, $plcYoutube;
    wp_get_current_user();

    
    if (!isset($_POST['images']) && isset($_POST["video-link"]) && $_POST["video-link"] !== "") {
        $yt_link = trim($_POST['video-link']);
        if(!$plcYoutube->youtube_checklink($yt_link)){
            $ret = array('status' => 'fail', 'msg' => "You entered the wrong youtube link");

            echo json_encode($ret);
            die();
        }
    }
        
    if (isset($_POST["sport"])
        && $_POST["sport"] !== ""
        && isset($_POST["tip"])
        && $_POST["tip"] !== ""
        && isset($_POST["tip_title"])
        && $_POST["tip_title"] !== ""
    ) { 
        $tip_title = trim($_POST["tip_title"]);
        $ps_sport = explode(',', $_POST["sport"]);
        $ps_tip = explode(',', $_POST["tip"]);
        $sport = $ps_sport[1];
        $tip = $ps_tip[1];
        
        $rules = isset($_POST['rules']) ? true : false;
        
        $title = $sport . " - " . $tip . " | " . $tip_title;

        $post_category =  array(11);
        if(is_user_in_role( $current_user->ID,  'blogger'  )){
            $post_category = array(16);
        }else if(is_user_in_role( $current_user->ID,  'celebrity'  )){
            $post_category = array(17);
        }
        $jrrny = array(
            "ID" => "",
            "post_type" => "post",
            "post_status" => "publish",
            "post_title" => $title,
            "post_author" => $current_user->ID,
            "post_name" => sanitize_title($title),
            "post_category" => $post_category
        );
        if (isset($_POST["story"]) && $_POST["story"] !== "") {
            $content = trim($_POST["story"]);
            $jrrny["post_content"] = $content;
        }
        if ($create_jrrny_id = wp_insert_post($jrrny, false)) {
            //Add tag
            wp_set_post_terms( $create_jrrny_id, array($tip));
            //Add meta
            add_post_meta($create_jrrny_id, '_sport', $_POST["sport"]);
            add_post_meta($create_jrrny_id, '_tip', $_POST["tip"]);
            add_post_meta($create_jrrny_id, '_tip_title', $tip_title);
            add_post_meta($create_jrrny_id, '_rules', $rules);


            if (isset($_POST["about-coach"]) && !empty($_POST["about-coach"])){
                $text = trim($_POST["about-coach"]);
                if (str_word_count($text, 0) > 100) {
                    $words = str_word_count($text, 2);
                    $pos = array_keys($words);
                    $text = substr($text, 0, $pos[100]);
                }

                add_post_meta($create_jrrny_id, '_about_coach', $text);
            }

            if (isset($_POST["website-coach"]) && !empty($_POST["website-coach"])){
                $url = trim($_POST["website-coach"]);
                $link = (substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://') ? $url: 'http://'.$url;
                add_post_meta($create_jrrny_id, '_website_coach', $link);
            }

            //Add jrrny to contest
            if (isset($_POST["contest_id"]) && $_POST["contest_id"] > 0) {
                add_post_meta($create_jrrny_id, '_attend_to_contest', $_POST["contest_id"]);                
            }
          
            //add_post_meta($create_jrrny_id, "_hotel_name", trim($_POST['hotel-name']));
            if (isset($_POST["tip-source"]) && $_POST["tip-source"] !== "") {
                add_post_meta($create_jrrny_id, '_tip_source', trim($_POST['tip-source']));
            }
            //Add othe image
            if (isset($_POST['images']) && is_array($_POST['images'])) {
                foreach ($_POST['images'] as $key => $image_id) {
                    $meta_id_name = '_p_image_' . ++$key;
                    add_post_meta($create_jrrny_id, $meta_id_name, wp_get_attachment_url($image_id));
                    add_post_meta($create_jrrny_id, $meta_id_name . '_id', $image_id);
                    wp_update_post(
                        array(
                            'ID' => $image_id,
                            'post_parent' => $create_jrrny_id
                        )
                    );
                }
                        //Add thumbnails
                $mainImageId = reset($_POST['images']); //default first main
                if (isset($_POST['main-image-id']) && $_POST['main-image-id'] !== "") {
                    $mainImageId = intval($_POST['main-image-id']);
                    if(wp_get_attachment_url(intval($_POST['main-image-id']))){
                        $mainImageId = intval($_POST['main-image-id']);
                    }
                }
                set_post_thumbnail($create_jrrny_id, $mainImageId);
            }else{
                delete_post_thumbnail($create_jrrny_id);
            }
            
            //Add video
            if (isset($_POST['video']) && is_array($_POST['video'])) {
                foreach ($_POST['video'] as $key => $video_id) {
                    $meta_id_name = '_p_video_' . ++$key;
                    add_post_meta($create_jrrny_id, $meta_id_name, wp_get_attachment_url($video_id));
                    add_post_meta($create_jrrny_id, $meta_id_name . '_id', $video_id);
                    wp_update_post(
                        array(
                            'ID' => $video_id,
                            'post_parent' => $create_jrrny_id
                        )
                    );
                }
            }
            
            add_post_meta($create_jrrny_id, '_insider_tip', trim($_POST['insider-tip']));

            if (isset($_POST["video-link"]) && $_POST["video-link"] !== "") {
                add_post_meta($create_jrrny_id, '_video_link', trim($_POST['video-link']));
            }
            if (!isset($_POST['images']) && isset($_POST["video-link"]) && $_POST["video-link"] !== "") {
                $plcYoutube->youtube_frontend($create_jrrny_id, trim($_POST['video-link']));
            }
            //Create return
            $title = get_the_title($create_jrrny_id);
            $link = get_permalink($create_jrrny_id);
            $ret = array(
                'status' => 'ok',
                'post_id' => $create_jrrny_id,
                'permalink' => $link
            );

            $social_urls = array(
                'facebook' =>       esc_url($link),
                'twitter' =>        esc_url('https://twitter.com/home?status=' . urlencode($title . ' ' . $link)),
                'google_plus' =>    esc_url('https://plus.google.com/share?url=' . urlencode($link) . '&amp;title=' . urlencode($title)),
            );
            $ret["social"] = $social_urls;

        } 
    }
} else {
    $ret = array('status' => 'fail', 'msg' => "You must login!");
}

echo json_encode($ret);
die();
//'https://www.facebook.com/dialog/share?app_id=145886185939751&display=popup&href='.$link.'&redirect_uri="'.$link