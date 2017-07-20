<?php
add_action('widgets_init', 'ts_tab_widget');
function ts_tab_widget(){
	register_widget('ts_tab_widget');
}

class ts_tab_widget extends WP_Widget{

	/* Widget setup */
	function __construct(){

		/* Widget settings */
		$widget_ops = array( 
			'classname'   => 'tabs-widget-wrapper', 
			'description' => __('A tabbed widget that displays popular posts, recent posts and comments.', 'ThemeStockyard') 
		);

		/* Widget control settings */
		$control_ops = array( 
			'width'   => 250, 
			'height'  => 350, 
			'id_base' => 'tab-widget' 
		);

		/* Create the widget */
		parent::__construct( 'tab-widget', '(TS) '.__('Tabbed Widget', 'ThemeStockyard'), $widget_ops, $control_ops );
	}
	


	/* How to display the widget on the screen */
	function widget( $args, $instance ) {
		global $wpdb, $post;

		$tab1  = $instance['tab1'];
		$tab2  = $instance['tab2'];
		$tab3  = $instance['tab3'];
		$crop_thumbs   = (isset($instance['crop_thumbs'])) ? $instance['crop_thumbs'] : 1;
		$count = (ctype_digit($instance['count']) && ts_essentials_number_within_range($instance['count'], 1, 20)) ? $instance['count'] : 5;
	
		echo ts_essentials_escape($args['before_widget']);
		
		echo '<div class="ts-tabs-widget tabs-widget shortcode-tabs simple-tabs horizontal-tabs">';

		$tab = array();
		?>	
		<div class="tab-widget">

			<ul class="tab-header clearfix">
				<li class="active"><?php echo esc_html($tab1); ?></li>
				<li><?php echo esc_html($tab2); ?></li>
				<li class="last"><?php echo esc_html($tab3); ?></li>
			</ul>

            <div class="tab-contents">
			<div  class="tab-context visible">
                <ul>
				<?php
				$popular_posts = '';
                $temp = $popular_posts;
                $popular_posts = new WP_Query(array('showposts' => intval($count), 'orderby' => 'comment_count'));
                while ($popular_posts->have_posts()) : $popular_posts->the_post(); 
	            ?>
				
                    <li class="post-widget clearfix">
                        <?php                        
                        $img_url = false;
                        $size = 'thumbnail';
                        $img_id = get_post_thumbnail_id($popular_posts->post->ID);
                        $photo = wp_get_attachment_image_src($img_id, $size);
                        $img_url = (isset($photo[0])) ? $photo[0] : '';
                        
                        $has_img = ($img_url) ? 'has-img' : '';
                        if($img_url) :
                        ?>
                        <div class="widget-thumbnail">
                            <a href="<?php echo get_permalink($popular_posts->post->ID); ?>" class="thumb-link">
                                <img width="60" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"/>
                            </a>
                        </div><!-- / div.widget-thumbnail -->
                        <?php
                        endif;
                        ?>

                        <div class="widget-context <?php echo esc_attr($has_img);?>">
                            <h4><a href="<?php echo get_permalink($popular_posts->post->ID); ?>"><?php the_title() ?></a></h4>
                            <small><?php the_time('F j, Y'); ?></small>
                        </div><!-- / div.widget-context -->

                    </li><!-- / div.post-widget -->

                <?php 
                    endwhile; 
                $popular_posts = $temp;	
                echo '</ul></div>';


                echo '<div  class="tab-context"><ul>';	            
                    
                $recentPosts = '';
                $temp = $recentPosts;
                $recentPosts = new WP_Query(array('showposts' => intval($count)));
                while ($recentPosts->have_posts()) : $recentPosts->the_post();
                ?>
                    <li class="post-widget clearfix">
                        <?php                          
                        $img_url = false;
                        $size = 'thumbnail';
                        $img_id = get_post_thumbnail_id($recentPosts->post->ID);
                        $photo = wp_get_attachment_image_src($img_id, $size);
                        $img_url = (isset($photo[0])) ? $photo[0] : '';
                        
                        $has_img = ($img_url) ? 'has-img' : '';
                        if($img_url) :
                        ?>
                        <div class="widget-thumbnail">
                            <a href="<?php echo get_permalink($recentPosts->post->ID); ?>" class="thumb-link">
                                <img width="60" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"/>
                            </a>
                        </div><!-- / div.widget-thumbnail -->
                        <?php
                        endif;
                        ?>

                        <div class="widget-context <?php echo esc_attr($has_img);?>">
                            <h4><a href="<?php echo get_permalink($recentPosts->post->ID); ?>"><?php the_title() ?></a></h4>
                            <small><?php the_time('F j, Y'); ?></small>
                        </div><!-- / div.widget-context -->

                    </li><!-- / div.post-widget -->

                <?php 
                endwhile; 
                $recentPosts = $temp;		
                echo '</ul></div>';


                echo '<div class="tab-context"><ul>';
                $sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved, comment_type, comment_author_url, SUBSTRING(comment_content,1,70) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT ".intval($count);
                $comments = $wpdb->get_results($sql);
                foreach ($comments as $comment) 
                { 
                ?>

					<li class="post-widget post-widget-comment clearfix">
						<div class="widget-thumbnail">
							<a href="<?php echo get_permalink($comment->ID); ?>#comment-<?php echo esc_attr($comment->comment_ID); ?>" title="<?php echo esc_attr(strip_tags($comment->comment_author)); ?> <?php _e('on ', 'ThemeStockyard'); ?><?php echo esc_attr($comment->post_title); ?>" class="thumb-link"><?php echo get_avatar( $comment, '60' ); ?></a>
						</div>
						
						<div class="widget-context">
							<a href="<?php echo get_permalink($comment->ID); ?>#comment-<?php echo esc_attr($comment->comment_ID); ?>" class="comm_link">
                                <h4><?php echo esc_html(strip_tags($comment->comment_author)); ?></h4>
                                <p>&#8220;<?php echo ts_essentials_trim_text($comment->com_excerpt, 50); ?>&#8221;</p>
                            </a>
                            <p class="small"><?php _e('on', 'ThemeStockyard');?> <a href="<?php echo get_permalink($comment->comment_post_ID); ?>" class="post-link"><?php echo ts_essentials_trim_text(get_the_title($comment->comment_post_ID), 36);?></a></p>
						</div>
					</li>
				<?php 
                }				
            echo '</ul></div>';

            wp_reset_postdata();
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
            echo ts_essentials_escape($args['after_widget']);
	}
	

	/* Update the widget settings. */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['tab1']  = $new_instance['tab1'];
		$instance['tab2']  = $new_instance['tab2'];
		$instance['tab3']  = $new_instance['tab3'];
		$instance['crop_thumbs']   = strip_tags( $new_instance['crop_thumbs']);
		$instance['count'] = $new_instance['count'];
		
		return $instance;
	}

	
	/* Displays the widget settings controls on the widget panel. */
	function form( $instance ) {
	
		$defaults = array(
			'tab1'  => 'Popular',
			'tab2'  => 'Recent',
			'tab3'  => 'Comments',
			'crop_thumbs' => 1,
			'count' => 5
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'tab1' )); ?>"><?php _e('Popular Post Title:', 'ThemeStockyard') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'tab1' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'tab1' )); ?>" value="<?php echo esc_attr($instance['tab1']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'link1' )); ?>"><?php _e('Recent Post Title:', 'ThemeStockyard') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'tab2' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'tab2' )); ?>" value="<?php echo esc_attr($instance['tab2']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'tab2' )); ?>"><?php _e('Comments Title:', 'ThemeStockyard') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'tab3' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'tab3' )); ?>" value="<?php echo esc_attr($instance['tab3']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'count' )); ?>"><?php _e('Number of Items:', 'ThemeStockyard') ?> (1 - 20)</label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'tab3' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'count' )); ?>" value="<?php echo esc_attr($instance['count']); ?>" />
		</p>		
	
	<?php
	}
}