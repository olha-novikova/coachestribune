<?php
/*
Template Name: Trending
*/

$jrrnys = get_post_meta($post->ID, 'jrrnys', true);

get_header();
get_template_part('top');
get_template_part('title-page');
////////////////
?>

<?php
if (have_posts()) :
    while (have_posts()) : the_post(); ?>
    <div class="tag-heading">
        <div class="container">
            <div class="top-tagline">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
<?php endif; ?>
<div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page')).' '.esc_attr($main_container_wrap_class);?>">
    <div id="main-container" class="container clearfix">
        <?php
        if($jrrnys){
            $atts = [
                'post_type' => array('post', 'sponsored_post', 'featured_destination'),
                'post__in' => $jrrnys,
                'default_query' => false,
                'orderby' => 'post__in',
                'infinite_scroll' => 'no',
                'posts_per_page' => 12,
                'limit' => 12,
                'show_pagination' => false
            ];
            //$ts_query =  new WP_Query($atts);

            ts_blog_loop('3column', $atts);
        }
        ?>
        <?php
            $trendingIds = getTrendingPostsIds();
            $atts = [
                'post__in' => $trendingIds,
                'default_query' => false,
                'orderby' => 'post__in',
                'posts_per_page'=> -1,
                'infinite_scroll' => 'yes'
            ];
            //$ts_query =  new WP_Query($atts);
            
            ts_blog_loop('3column', $atts);
        ?>
	</div>
</div>

<?php get_footer(); ?>