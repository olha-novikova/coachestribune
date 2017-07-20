<?php
add_action('widgets_init', 'ts_video_widget');
function ts_video_widget() {
	register_widget('ts_video');
}

class ts_video extends WP_Widget {

	/* Widget setup */
	function __construct() {

		/* Widget settings */
		$widget_ops = array( 
			'classname'   => 'video-widget-wrap', 
			'description' => __('Video Widget', 'ThemeStockyard') 
		);

		/* Widget control settings */
		$control_ops = array( 
			'width'   => 250, 
			'height'  => 350, 
			'id_base' => 'video-widget' 
		);

		/* Create the widget */
		parent::__construct('video-widget', '(TS) '.__('Video Widget', 'ThemeStockyard'), $widget_ops, $control_ops);
	}


	/* Display the widget on the screen */
	function widget($args, $instance) {
		
		$title = $instance['title'];
		$video = $instance['video'];
		$url = $instance['url'];

		echo ts_essentials_escape($args['before_widget']);
        
		if ( ! empty( $title ) ) echo ts_essentials_escape($args['before_title'] . apply_filters( 'widget_title', $title ). $args['after_title']);
		
		if(trim($video)) :
            $video = preg_replace("/<(iframe|embed|object|video|audio)/", '<div class="video-widget fluid-width-video-wrapper">\\0', $video);
			$video = preg_replace("/<\/(iframe|embed|object|video|audio)>/", '\\0</div>', $video);
			echo wp_kses_post($video);
		elseif(trim($url)) :
            echo '<p class="ts-wp-oembed fluid-width-video-wrapper">'.wp_oembed_get($url).'</p>';
		endif;

		echo ts_essentials_escape($args['after_widget']);
	}


	/* Update the widget settings */
	function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		if (current_user_can('unfiltered_html')) {
			$instance['video'] =  $new_instance['video'];
		} else {
			$instance['video'] = stripslashes(wp_filter_post_kses( addslashes($new_instance['video'])));
		}
		
		$instance['url'] = esc_url($new_instance['url']);

		return $instance;

	}
	

	/* Displays the widget settings controls on the widget panel */
	function form($instance) {

		$defaults = array( 
			'title' => __('Video', 'ThemeStockyard'),
			'video' => '',
			'url' => ''
		);

		$instance = wp_parse_args((array) $instance, $defaults); 
		$video    = esc_textarea($instance['video']);
		$url    = esc_url($instance['url']);
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo _e('Title:', 'ThemeStockyard'); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		
		<p><label for="<?php echo esc_attr($this->get_field_id('url')); ?>"><?php echo _e('Video URL:', 'ThemeStockyard'); ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('url')); ?>" name="<?php echo esc_attr($this->get_field_name('url')); ?>" value="<?php echo esc_attr($instance['url']); ?>" /></p>
		
		<p><strong><?php _e('-OR-', 'ThemeStockyard');?></strong></p>

		<p><label for="<?php echo esc_attr($this->get_field_id('video')); ?>"><?php echo _e('Video Embed Code:', 'ThemeStockyard'); ?></label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo esc_attr($this->get_field_id('video')); ?>" name="<?php echo esc_attr($this->get_field_name('video')); ?>"><?php echo ts_essentials_escape($video); ?></textarea></p>
	<?php
	}

}