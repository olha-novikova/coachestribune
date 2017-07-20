<?php

header('Content-Type: application/json');
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once($parse_uri[0] . 'wp-load.php');
$ret = array();

try {
    if (!is_user_logged_in()) {
        throw new Exception("You must be logged in!");
    }
    global $current_user, $plcYoutube;
    wp_get_current_user();
    if (current_user_can('edit_post', get_the_ID())) {
        throw new Exception("Do not have permission!");
    }

    if (!isset($_POST['images']) && isset($_POST["video-link"]) && $_POST["video-link"] !== "") {
        $yt_link = trim($_POST['video-link']);
        if (!$plcYoutube->youtube_checklink($yt_link)) {
            $ret = array('status' => 'fail', 'msg' => "You entered the wrong youtube link");

            echo json_encode($ret);
            die();
        }
    }

    if (
            !isset($_POST["post_id"]) ||
            $_POST["post_id"] == "" ||
            !isset($_POST["sport"]) ||
            $_POST["sport"] == "" ||
            !isset($_POST["tip"]) ||
            $_POST["tip"] == "" || 
            !isset($_POST["tip_title"]) || 
            $_POST["tip_title"] == ""
    ) {
        throw new Exception("Wrong post data!");
    }
    $postId = intval($_POST["post_id"]);
    
    $tip_title = trim($_POST["tip_title"]);
    $ps_sport = explode(',', $_POST["sport"]);
    $ps_tip = explode(',', $_POST["tip"]);
    $sport = $ps_sport[1];
    $tip = $ps_tip[1];

    $title = $sport . " - " . $tip . " | " . $tip_title;
    
    $jrrny = array(
        "ID" => $postId,
        "post_type" => "post",
        "post_status" => "publish",
        "post_title" => $title,
        "post_author" => $current_user->ID,
        "post_name" => sanitize_title($title)
    );
    if (isset($_POST["story"]) && $_POST["story"] !== "") {
        $jrrny["post_content"] = trim($_POST["story"]);
    }
    wp_update_post($jrrny);

    //Update tag
    wp_set_post_terms( $postId, array($tip));
    //Update meta
    update_post_meta($postId, '_sport', $_POST["sport"]);
    update_post_meta($postId, '_tip', $_POST["tip"]);
    update_post_meta($postId, '_tip_title', $tip_title);
    update_post_meta($postId, '_tip_source', trim($_POST['tip-source']));
    update_post_meta($postId, '_video_link', trim($_POST['video-link']));

    if (isset($_POST["about-coach"]) && !empty($_POST["about-coach"])){
        $text = trim($_POST["about-coach"]);
        if (str_word_count($text, 0) > 100) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[100]);
        }

        update_post_meta($postId, '_about_coach', $text);
    }

    if (isset($_POST["website-coach"]) && !empty($_POST["website-coach"])){
        $url = trim($_POST["website-coach"]);
        $link = (substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://') ? $url: 'http://'.$url;
        update_post_meta($postId, '_website_coach', $link);
    }

    //Edit other image
    if (isset($_POST['images']) && is_array($_POST['images'])) {
        foreach ($_POST['images'] as $key => $image_id) {
            $meta_id_name = '_p_image_' . ++$key;
            update_post_meta($postId, $meta_id_name, wp_get_attachment_url($image_id));
            update_post_meta($postId, $meta_id_name . '_id', $image_id);
            wp_update_post(
                    array(
                        'ID' => $image_id,
                        'post_parent' => $postId
                    )
            );
        }

        //Add thumbnails
        $mainImageId = reset($_POST['images']); //default first main
        if (isset($_POST['main-image-id']) && $_POST['main-image-id'] !== "") {
            $mainImageId = intval($_POST['main-image-id']);
            if (wp_get_attachment_url(intval($_POST['main-image-id']))) {
                $mainImageId = intval($_POST['main-image-id']);
            }
        }
        set_post_thumbnail($postId, $mainImageId);
    }
    else {
        delete_post_thumbnail($postId);
    }
    if (isset($_POST['video']) && is_array($_POST['video'])) {
        foreach ($_POST['video'] as $key => $video_id) {
            $meta_id_name = '_p_video_' . ++$key;
            update_post_meta($postId, $meta_id_name, wp_get_attachment_url($video_id));
            update_post_meta($postId, $meta_id_name . '_id', $video_id);
            wp_update_post(
                    array(
                        'ID' => $video_id,
                        'post_parent' => $create_jrrny_id
                    )
            );
        }
    }

    if (isset($_POST['imagesh']) && is_array($_POST['imagesh'])) {
        foreach ($_POST['imagesh'] as $key => $image_id) {
            $meta_imageh_id_name = '_hotel_image_' . ++$key;
            update_post_meta($postId, $meta_imageh_id_name, wp_get_attachment_url($image_id));
            update_post_meta($postId, $meta_imageh_id_name . '_id', $image_id);
            wp_update_post(
                    array(
                        'ID' => $image_id,
                        'post_parent' => $postId
                    )
            );
        }
    }
    update_post_meta($postId, '_insider_tip', trim($_POST['insider-tip']));


    if (!isset($_POST['images']) && isset($_POST["video-link"]) && $_POST["video-link"] !== "") {
        $plcYoutube->youtube_frontend($postId, trim($_POST['video-link']));
    }

    $title = get_the_title($postId);
    $link = get_permalink($postId);
    $ret = array(
        'status' => 'ok',
        'post_id' => $postId,
        'permalink' => $link,
        'title' => $title
    );
} catch (Exception $e) {
    $ret['status'] = "fail";
    $ret['msg'] = $e->getMessage();
}
echo json_encode($ret);
die();
