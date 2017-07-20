<?php
global $smof_data, $ts_previous_posts, $ts_page_id, $ts_show_top_ticker, $ts_show_sidebar, $ts_sidebar_position;

$ts_page_object = get_queried_object();
$ts_page_id = (is_single()) ? $post->ID : get_queried_object_id();
$ts_custom_css = get_post_meta($ts_page_id, '_page_css', true);

$ts_show_top_ticker_option = (ts_option_vs_default('show_page_top_ticker', 0) == 1) ? 'yes' : 'no';
$ts_show_top_ticker = ts_postmeta_vs_default($ts_page_id, '_page_top_ticker', $ts_show_top_ticker_option);

$ts_show_sidebar_option = (ts_option_vs_default('show_page_sidebar', 1) != 1) ? 'no' : 'yes';
$ts_show_sidebar = ts_postmeta_vs_default($ts_page_id, '_page_sidebar', $ts_show_sidebar_option);
$ts_show_sidebar_class = ($ts_show_sidebar == 'yes') ? 'has-sidebar' : 'no-sidebar';

$ts_sidebar_position_option = ts_option_vs_default('page_sidebar_position', 'right');
$ts_sidebar_position = ts_postmeta_vs_default($ts_page_id, '_page_sidebar_position', $ts_sidebar_position_option);

$ts_page_comments = (ts_option_vs_default('page_comments', 0) == 1) ? true : false;

$hero_post = get_post_meta($post->ID, 'hero_post', true);
$top_tagline = get_post_meta($post->ID, 'top_tagline', true);
$join_image = get_post_meta($post->ID, 'join_image', true);
$join_image_attr = wp_get_attachment_image_src($join_image, 'full');
$join_image_paralax = get_post_meta($post->ID, 'join_image_paralax', true);
$collections = get_field('collections', $post->ID);

get_header();
get_template_part('top');
get_template_part('title-page');
//get_template_part('slider');

$featured_media_vars = array('media_width' => 1040, 'media_height' => 340, 'is_single' => 1);
$featured_media = ts_get_featured_media($featured_media_vars);
$main_container_wrap_class = '';

if (isset($featured_media) && trim($featured_media)) :
    $ts_sidebar_position = 'content-' . $ts_sidebar_position;
    //$main_container_wrap_class = 'no-top-padding';
endif;
?>
<?php if (!is_page(27) && $top_tagline): ?>
<div class="tag-heading">
    <div class="container">
        <div class="top-tagline">
            <?php echo apply_filters('the_content', $top_tagline);?>
        </div>
    </div>
</div>
    <?php endif ?>
<div id="main-container-wrap"
     class="<?php echo esc_attr(ts_main_container_wrap_class('page')) . ' ' . esc_attr($main_container_wrap_class); ?>">
    <div id="main-container" class="container clearfix">   
        <p class="module-header">The Top 6</p>     
        <?php       
        
        $primaryPost = [
            get_option('primary_1'),
            get_option('primary_2'),
            get_option('primary_3')
        ];
        $atts = [
            'post_type' => 'any',
            'post__in' => $primaryPost,
            'default_query' => false,
            'infinite_scroll' => 'yes_button',
            'orderby' => 'post__in'
        ];
        //$ts_query = new WP_Query($atts);

        ts_blog_loop('3column', $atts);
        ?>
        <?php
        $primaryPost2 = [
            get_option('primary_4'),
            get_option('primary_5'),
            get_option('primary_6')
        ];
        $atts_prim = [
            'post_type' => 'any',
            'post__in' => $primaryPost2,
            'default_query' => false,
            'infinite_scroll' => 'yes_button',
            'orderby' => 'post__in'
        ];
        //$ts_query = new WP_Query($atts_prim);

        ts_blog_loop('3column', $atts_prim);
        
        ?>
        <p class="module-header">Most Recent <a href="/all-sports">...view all</a></p>
        <?php
                
        $atts = [
            'post_type' => 'post',
            'default_query' => false,
            'show_pagination' => false,
            'orderby'       =>  'post_date',
            'order'         =>  'DESC',
            'posts_per_page' => 6,
            'infinite_scroll_limit' => 0,
            'infinite_scroll_button_text' => 'Load more tips'
        ];
        //$ts_query = new WP_Query($atts);

        ts_blog_loop('3column', $atts);
        ?>
        <div class="row">
            <div class="col-xs-12 new-search-wrapper">
                <?php echo ts_top_search(); ?>
            </div>
        </div>
    </div>
<?php 
    if($hero_post){
    //Banner HERO Tag HERO
            $atts = [
            'post_type' => array('post', 'sponsored_post', 'featured_destination'),
            'post__in' => $hero_post,
            'default_query' => false,
            'orderby' => 'post__in',
            'infinite_scroll' => 'no',
            'posts_per_page' => 4,
            'limit' => 4,
            'show_pagination' => false
        ];
        //$ts_query =  new WP_Query($atts);

        ts_blog_loop('2column-banner', $atts);
    }
    if($collections){
    ?>
    <div class="featured_destination_wrapper clearfix">
        <ul id="featured_destination_list" class="no-padding custom-collections">
        <?php

            foreach($collections as $collection)
            {
                $post = $collection['collections_jrrnys'];
                setup_postdata( $post );
                $image_data = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
                if(!$image_data){                    
                    $hero_image = get_post_meta($post->ID, 'hero_image', true);
                    $hero_image_attr = wp_get_attachment_image_src( $hero_image);
                    $image_data = $hero_image_attr[0];
                }
                $backgroundStyle ="background-image: url('". $image_data  ."')";
            ?>
            <li>
                <div style="<?php echo $backgroundStyle;?>" class="fetured-content">
                    <a href="<?php echo the_permalink(); ?>" class="fetured-content-link"></a>
                    <div class="plc-table">
                        <div class="plc-cell">
                            <?php echo the_title(); ?>
                        </div>
                    </div>
                </div>
            </li>

        <?php }    
            wp_reset_postdata();
        ?>
        </ul>
    </div>
    <?php }  ?>
    <div class="main-collection-wrapper">
        <?php
        if (have_posts()) :
        while (have_posts()) : the_post();
            ?>
            <?php the_content(); ?>
            <?php wp_link_pages('before=<div class="page-links">' . __('Pages: ', 'ThemeStockyard') . '<span class="wp-link-pages">&after=</span></div>&link_before=<span>&link_after=</span>'); ?>
        <?php endwhile; ?>
    </div>
<!-- #main-container -->
    <div id="join-container" class="clearfix<?php echo $join_image_paralax ? ' parallax' : ''?>" style="background-image: url('<?php echo $join_image_attr[0];?>');">
        <div class="join-table">
            <div class="join-cell">
                <div class="container">
                    <?php get_template_part('inc/frontend/login/form', 'newsignup'); ?>
                </div>
            </div>
        </div>
    </div>
<?php /*
$topUsersIds = getTopUsersIds();
$countTopUsersIds = count($topUsersIds);
$args = array(
    'include' => $topUsersIds,
    'fields' => 'all',
    'orderby' => 'include',
    'order' => 'ASC'
);
$countTopUsers = 6 - $countTopUsersIds;
$users = '';
if($countTopUsersIds > 0){
    $users = get_users($args); 
}?>
<div id="users-container" class="clearfix">
    <p class="users-title">Here's this week's top contributors!</p>

    <div class="row">
        <?php
        $quota = '';
        if($users){
            foreach ($users as $user) {
                include(locate_template('inc/frontend/topcontributors/user-item.php'));
            }        
        }
        if($countTopUsers > 0){
            $topRandomUsersIds = getTopRandomUsersIds($countTopUsers, $topUsersIds);
            $args = array(
                'include' => $topRandomUsersIds,
                'fields' => 'all',
                'orderby' => 'include',
                'order' => 'ASC'
            );
            $users = get_users($args);
            foreach ($users as $user) {
                include(locate_template('inc/frontend/topcontributors/user-item.php'));
            }
        }
        ?>
    </div>
    <?php
    if ($quota) {
        $q = explode('|', $quota);
        echo '<div id="user-quota-container">';
        echo '<p class="user-quota">&quot;' . $q[0] . '&quot;</p>';
        echo '<p class="user-quota-author">- ' . $q[1] . '</p>';
        echo '</div>';
    }
    ?>
</div>
<?php */ ?>
<?php endif;  ?>
</div><!-- #main-container-wrap -->

<?php get_footer(); ?>