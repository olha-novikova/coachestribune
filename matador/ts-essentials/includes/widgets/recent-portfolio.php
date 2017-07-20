<?php
/*-------------------------------------------------------
Recent Portfolio widget
-------------------------------------------------------*/
if(!ts_essentials_posttype_supported('portfolio')) return;

add_action('widgets_init', 'ts_recent_portfolio_widget');
function ts_recent_portfolio_widget(){
	register_widget('ts_recent_portfolio_post');
}

class ts_recent_portfolio_post extends WP_Widget{

	/* Widget setup */
	function __construct(){

		/* Widget settings */
		$widget_ops = array( 
			'classname'   => 'recent-portfolio-posts-widget', 
			'description' => __('A widget that shows recent portfolio post thumbnails', 'ThemeStockyard') 
		);

		/* Widget control settings */
		$control_ops = array( 
			'width'   => 250, 
			'height'  => 350, 
			'id_base' => 'portfolio-post-recent' 
		);

		/* Create the widget */
		parent::__construct('portfolio-post-recent', '(TS) '.__('Recent Portfolio Posts', 'ThemeStockyard'), $widget_ops, $control_ops);
	}


	/* Display the widget on the screen */
    function widget ($args, $instance) {

		$title = $instance['title'];
		$num   = $instance['num'];
        
        echo ts_essentials_escape($args['before_widget']);
        
		if ( ! empty( $title ) ) echo ts_essentials_escape($args['before_title'] . apply_filters( 'widget_title', $title ). $args['after_title']);
		
		echo '<ul>';

		$recentPosts = '';
		$temp = $recentPosts;
		$recentPosts = new WP_Query(array('showposts' => $num));
        while ($recentPosts->have_posts()) : $recentPosts->the_post(); 
            $img_url = false;
            $img_id = get_post_thumbnail_id($recentPosts->post->ID);
            $photo = wp_get_attachment_image_src($img_id, 'thumbnail');
            $img_url = (isset($photo[0])) ? $photo[0] : '';
            
            $has_img = ($img_url) ? 'has-img' : '';
            if($img_url) :
        ?>

			<li class="portfolio-post-widget clearfix">
                <div class="portfolio-post-widget-thumbnail">
                    <a href="<?php echo get_permalink($recentPosts->post->ID); ?>" class="thumb-link">
                        <img width="75" src="<?php echo esc_url($img_url);?>" alt="<?php echo esc_attr(get_the_title());?>"/>
                    </a>
                </div><!-- / div.widget-thumbnail -->
			</li><!-- end div.post-widget -->

        <?php 
            endif;
        endwhile; 
        $recentPosts = $temp;
        echo '</ul>';
		echo ts_essentials_escape($args['after_widget']);
  	}


	/* Update the widget settings */
	function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title']);
		$instance['num']   = strip_tags( $new_instance['num']);

		return $instance;
	}


	/* Displays the widget settings controls on the widget panel */
	function form($instance) {

		$defaults = array( 
			'title' => __('Recent Work', 'ThemeStockyard'), 
			'num'   => '5',
		);
		$instance = wp_parse_args((array) $instance, $defaults); 
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'ThemeStockyard'); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('num')); ?>"><?php _e('Show Count', 'ThemeStockyard'); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('num')); ?>" name="<?php echo esc_attr($this->get_field_name('num')); ?>" value="<?php echo esc_attr($instance['num']); ?>" />
		</p>
	<?php
	}

}
