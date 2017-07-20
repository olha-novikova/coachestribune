<?php
global $smof_data, $ts_page_id, $ts_comments_top_padding, $ts_within_blog_loop, $ts_sidebar_position, $ts_show_sidebar, $ts_show_top_ticker;
global $ts_previous_posts;

$ts_page_object = get_queried_object();
$ts_page_id     = (is_single()) ? $post->ID : get_queried_object_id();
$ts_custom_css  = get_post_meta($ts_page_id, '_p_css', true);
$coach_video  = get_post_meta($ts_page_id, '_video_link', true);
$about_coach  = get_post_meta($ts_page_id, '_about_coach', true);
$website_coach  = get_post_meta($ts_page_id, '_website_coach', true);

$ts_previous_posts[] = $ts_page_id;

$ts_show_top_ticker_option = (ts_option_vs_default('show_post_top_ticker', 0) == 1) ? 'yes' : 'no';
$ts_show_top_ticker = ts_postmeta_vs_default($ts_page_id, '_post_top_ticker', $ts_show_top_ticker_option);
$ts_show_sidebar_option = (ts_option_vs_default('show_post_sidebar', 1) != 1) ? 'no' : 'yes';
$ts_show_sidebar = ts_postmeta_vs_default($post->ID, '_p_sidebar', $ts_show_sidebar_option);
$ts_show_sidebar_class = ($ts_show_sidebar == 'yes') ? 'has-sidebar' : 'no-sidebar';
$ts_sidebar_position_option = ts_option_vs_default('post_sidebar_position', 'right');
$ts_sidebar_position = ts_postmeta_vs_default($post->ID, '_p_sidebar_position', $ts_sidebar_position_option);
$ts_direction_links_option = ts_option_vs_default('post_show_direction_links', 'yes');
$ts_direction_links = ts_postmeta_vs_default($post->ID, '_p_show_direction_links', $ts_direction_links_option);
$ts_sharing_position_option = ts_option_vs_default('sharing_options_position_on_post', 'top');
$ts_sharing_position = ts_postmeta_vs_default($post->ID, '_p_sharing_options_position', $ts_sharing_position_option);
$ts_show_featured_media_option = (ts_option_vs_default('show_images_on_post', 1) == 1) ? 'yes' : 'no';
$ts_show_featured_media = ts_postmeta_vs_default($post->ID, '_p_show_featured_image_on_single', $ts_show_featured_media_option);
$crop_images_option = (ts_option_vs_default('crop_images_on_post', 1)) ? 'yes' : 'no';
$crop_images = ts_postmeta_vs_default($post->ID, '_p_crop_images', $crop_images_option);

if($ts_show_sidebar == 'yes' && (in_array($ts_sidebar_position, array('left','right')))) :
    if(in_array($crop_images, array('1','yes','true'))) :
        $crop_width = ts_option_vs_default('cropped_featured_image_width', 740, true);
        $crop_height = ts_option_vs_default('cropped_featured_image_height', 480, true);
    else :
        $crop_width = ts_option_vs_default('cropped_featured_image_width', 740, true);
        $crop_height = 0;
    endif;
else :
    if(in_array($crop_images, array('1','yes','true'))) :
        $crop_width = ts_option_vs_default('cropped_featured_image_width_full', 1100, true);
        $crop_height = ts_option_vs_default('cropped_featured_image_height_full', 540, true);
    else :
        $crop_width = ts_option_vs_default('cropped_featured_image_width_full', 1100, true);
        $crop_height = 0;
    endif;
endif;
if(has_post_thumbnail(get_the_id())) {
    $post_thumbnail_id = (int)get_post_thumbnail_id(get_the_id());
}
/*$hotel_images_ids = [];
$hotelImagesIds = get_post_meta(get_the_ID(), "_hotel_image_id", true);
if(is_array($hotelImagesIds)) {
    foreach($hotelImagesIds as $id) {
        if(wp_get_attachment_url( $id )){
            $hotel_images_ids[] = $id;                                   
        }
    }
}else {
    if(wp_get_attachment_url( $hotelImagesIds )){
        $hotel_images_ids[] = $hotelImagesIds;   
    }                                
}*/

$hotel_images_ids = [];
for ($i = 1; $i <= 2 ; ++$i){
    $id = get_post_meta(get_the_ID(), '_hotel_image_' . $i . '_id', true);
    if(!empty($id)){
        if(wp_get_attachment_url( $id )){
            $hotel_images_ids[$i] = $id;
        }
    }
}

$videoImageIds = [];
for ($i = 1; $i <= 12 ; ++$i){
    $id = get_post_meta(get_the_ID(), '_p_video_' . $i . '_id', true);
    if(!empty($id)){
        if(wp_get_attachment_url( $id )){
            $videoImageIds[$i] = $id;
        }
    }
}
$video_poster = '';
if(isset($post_thumbnail_id)){ 
    $video_poster = wp_get_attachment_url( $post_thumbnail_id );
}
$videoLink = get_post_meta(get_the_ID(), "_video_link", true);
$embed_code ='';
if($videoLink){
    $embed_code = wp_oembed_get($videoLink); 
}
if($videoImageIds || $embed_code){
    $post_thumbnail_id = NULL;
}
$postImageIds = [];
for ($i = 1; $i <= 12 ; ++$i){
    $id = get_post_meta(get_the_ID(), '_p_image_' . $i . '_id', true);
    if(!empty($id) && $id != $post_thumbnail_id){
        if(wp_get_attachment_url( $id )){
            $postImageIds[$i] = $id;
        }
    }
}
if( !is_primary(get_the_ID()) ){                    
   if(!is_user_logged_in()){
        $redirect = get_site_url().'?redirect='.urldecode(get_site_url().$_SERVER['REQUEST_URI']). '#login-form';
        wp_redirect($redirect);
        exit;       
   }
}

get_header();
get_template_part('top');
get_template_part('title-page');
?>
            <div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class());?>">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                
            ?>
                <div id="main-container" class="container clearfix">
                    <div id="main" class="<?php echo esc_attr(ts_main_div_class());?> clearfix">
                        <div class="entry single-entry clearfix">
                            <?php
                            if ($ts_sharing_position == 'top') :
                                get_template_part('single-post-sharing');
                            endif;
                            ?>
                            <?php if($videoImageIds) { 
                                foreach($videoImageIds as $video ){
                                    $videoUrl = wp_get_attachment_url($video);
                                    $attr = array(
                                        'src' => $videoUrl,
                                        'type' => get_post_mime_type($video),
                                        'preload' => 'auto',
                                        //'poster' => $video_poster
                                    );
                                    plc_get_video( $attr );
                                    ?>                                    
                                <?php }                                
                                }?>
                            
                                <?php if($embed_code){ ?>
                                    <div class="video">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <?php echo $embed_code; ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php
                                if( $about_coach ):?>
                                    <div class="author-id">
                                        <h4><?php echo $about_coach; ?></h4>
                                        <?php if( $website_coach ):?>
                                            <p class="hotel-info">
                                                <a href="<?php echo $website_coach; ?>" target="_blank"><?php echo $website_coach;?></a>
                                            </p>
                                        <?php
                                        endif;
                                        ?>
                                    </div>
                               <?php
                                endif;
                                ?>
                            <?php if(isset($post_thumbnail_id)) : ?>
                                <img src='<?= wp_get_attachment_url( $post_thumbnail_id )?>' class="img img-responsive img-block"/>
                            <?php endif; ?>
                                <?php // get_template_part("inc/frontend/map/single-map");?>
                            <div id="ts-post-content-sidebar-wrap" class="clearfix">
                                <div id="ts-post-wrap" class="<?php echo esc_attr(ts_single_post_wrap_class($ts_sharing_position, $featured_media));?>">

                                    <div id="ts-post" <?php post_class('post ts-post-section clearfix'); ?>>

                                        <div id="ts-post-the-content-wrap">
                                            <div id="ts-post-the-content" class="entry-content">
                                                <?php
                                                echo ts_display_link_post_format_url();
                                                echo make_clickable(apply_filters( 'the_content', get_the_content()));
                                                $insider_tip = get_post_meta(get_the_ID(), "_insider_tip", true);
                                                ?>
                                                <?php if(isset($insider_tip) && $insider_tip != '') : ?>
                                                    <h3>Insider Tip</h3>
                                                    <?= make_clickable(apply_filters( 'the_content', $insider_tip)); ?>
                                                <?php endif; ?>
                                                <?php
                                                wp_link_pages('before=<div class="page-links">'.__('Pages: ', 'ThemeStockyard').'<span class="wp-link-pages">&after=</span></div>&link_before=<span>&link_after=</span>');
                                                ?>
                                                <?php
                                                $tags_intro = '<span class="tags-label">'.__('Tags:','ThemeStockyard').' </span>';
                                                $tags_sep = '&bull;';
                                                if(has_tag()) :
                                                ?>
                                                <div class="post-tags mimic-small clearfix"><?php the_tags('', '', '');?></div>
                                                <?php
                                                endif;
                                                ?>

                                            </div>
                                            <div class="single-post-media-attachments">

                                                <?php
                                                if (!$coach_video){
                                                    foreach ($postImageIds as $key => $imgId): ?>
                                                        <img src='<?= wp_get_attachment_url( $imgId )?>' />
                                                    <?php endforeach;
                                                }?>
                                            </div>

                                            
                                            <?php
                                            $tip_source = get_post_meta(get_the_ID(), "_tip_source", true);
                                            if($tip_source){ ?>                                                        
                                                <div class="about-hotel">
                                                    <h3><?php echo __('Tip source', 'ThemeStockyard');?></h3>
                                                    <?php
                                                        if(empty(parse_url($tip_source)["scheme"])){
                                                            $http_tip_source = "http://".$tip_source;
                                                        }
                                                        else{
                                                            $http_tip_source = $tip_source;
                                                        } ?>
                                                    <p class="hotel-info">
                                                        <a href="<?php echo $http_tip_source; ?>" target="_blank"><?php echo $tip_source;?></a>
                                                    </p>  
                                                </div>                                                      
                                            <?php } ?> 
                                            
                                            
                                        <?php if(!plc_is_category(13) && ts_option_vs_default('author_info_on_post', 1) == 1) : ?>
                                        <?php if($post->ID == 5747) : //TEST guayaquil-guayas-ecuador-for-city-living-with-a-great-art-scene?> 
                                            <?php get_template_part('inc/frontend/single/alltherooms-search-bar'); ?>
                                        <?php endif; ?>
                                        <div class="ts-about-author-wrap ts-post-section-inner">
                                            <div class="ts-about-author">
                                                <div class="author-id">
                                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID')));?>" class="avatar-img-link"><?php echo get_avatar(get_the_author_meta('email'), '50'); ?></a>
                                                    <h6 class="smaller"><?php echo __('Posted by', 'ThemeStockyard'); ?>                                                         
                                                    </h6>
                                                    <h4><?php the_author_posts_link(); ?></h4>
                                                </div>
                                                <?php
                                                echo wpautop(do_shortcode(get_the_author_meta('description')));
                                                ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                            <div class="ts-post-section-inner">
                                                <div id="page-share" class="not-pulled small">
                                                    <?php echo ts_social_sharing(); ?>
                                                </div>
                                            </div>
                                        <?php
                                        if(in_array($ts_direction_links, array('yes','yes_similar'))) ts_post_direction_nav_v2($ts_direction_links);
                                        ?>

                                        <?php
                                        $show_related_option = ts_option_vs_default('show_related_blog_posts', 'yes');
                                        $show_related = ts_postmeta_vs_default($post->ID, '_p_related_posts', $show_related_option);
                                        if($show_related == '1' || $show_related == 'yes') :
                                        ?>
                                        <div class="ts-related-posts-on-single ts-post-section-inner">
                                            <h4 class="smaller uppercase"><?php echo ts_option_vs_default('related_blog_posts_title_text', 'Related Posts');?></h4>
                                            <?php
                                            $args = array('include'=>'related','limit'=>3,'show_pagination'=>'no','media_width'=>480,'media_height'=>360, 'title_size'=>4,'allow_videos'=>0,'allow_galleries'=>0);
                                            echo ts_blog('3columns', $args);
                                            ?>
                                        </div>
                                        <?php endif;?>

                                    </div>

                                    <div id="ts-comments-wrap-wrap" class="clearfix <?php echo esc_attr(ts_single_comments_wrap_wrap_class());?>">
                                        <div id="ts-comments-wrap" class="<?php echo esc_attr(ts_single_comments_wrap_class());?>">
                                            <?php
                                            comments_template();
                                            ?>
                                        </div>
                                        <?php ts_get_comments_sidebar(); ?>
                                    </div>
                                </div>
                                <?php ts_get_content_sidebar(); ?>
                            </div>

                        </div>
                    </div>

<?php if(!post_password_required()) ts_get_sidebar(); ?>

                </div><!-- #main-container -->
                
            <?php
                
                endwhile;
                
            else :
            ?>
                <div id="main-container" class="container clearfix">
                    <div id="main" class="<?php echo esc_attr($ts_show_sidebar_class);?> clearfix">
                        <div class="entry single-entry clearfix">
                            <div class="post"><p><?php _e('Sorry, the post you are looking for does not exist.', 'ThemeStockyard');?></p></div>
                        </div>
                    </div>
                </div>
            <?php
            endif;
            ?>
            </div><!-- #main-container-wrap -->
