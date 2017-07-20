<?php
/*---------------------------------------------------------------------------------*/
/* Follow RSS Widget */
/*---------------------------------------------------------------------------------*/

class ts_follow_rss_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array(
            'classname' => 'ts-follow-rss-widget',
            'description' => __('Use widget to show an RSS icon and link.', 'ThemeStockyard') 
        );
		parent::__construct(false, '(TS) '.__('Follow RSS', 'ThemeStockyard'),$widget_ops);      
	}

	function widget($args, $instance) { 
		$title          = (trim($instance['title'])) ? $instance['title'] : __('Follow Our RSS Feed', 'ThemeStockyard');
		$label          = $instance['label'];
		$description    = (trim($instance['description'])) ? $instance['description'] : __('Stay up to date with the latest news by following our feed.', 'ThemeStockyard');
		$feedurl        = (trim($instance['feedurl'])) ? $instance['feedurl'] : get_bloginfo('rss2_url');

        echo ts_essentials_escape($args['before_widget']);
        
        echo '<div class="inner">';

		if ( ! empty( $title ) ) echo ts_essentials_escape($args['before_title'] . apply_filters( 'widget_title', $title ). $args['after_title']);
        
		echo '<a href="'.esc_url($feedurl).'">';
		echo '<i class="fa fa-rss rss-bg-color"></i>';
		echo '<h4 class="sp1">'.esc_html($title).'</h4>';
        echo '<span class="sp2 small">'.esc_html($description).'</span>';
        echo '</a>';
		
		echo '</div>';
		
		echo ts_essentials_escape($args['after_widget']);

	}

	function update($new_instance, $old_instance) {                
		$new_instance = (array) $new_instance;

        $instance['label'] = strip_tags( $new_instance['label']);
        $instance['title']   = strip_tags( $new_instance['title']);
        $instance['description'] = strip_tags($new_instance['description']);
        $instance['feedurl'] = strip_tags($new_instance['feedurl']);

        return $instance;
	}

	function form($instance) {        
		
		$defaults = array( 
            'label'         => __('Subscribe', 'ThemeStockyard'),
			'title'         => __('Follow Our RSS Feed', 'ThemeStockyard'), 
			'description'   => __('Stay up to date with the latest news by following our feed.', 'ThemeStockyard'),
			'feedurl'       => get_bloginfo('rss2_url'),
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('label')); ?>"><?php _e('Label (optional):', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('label')); ?>" value="<?php echo esc_attr($instance['label']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('label')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Link Title:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('description')); ?>"><?php _e('Link Description:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('description')); ?>" value="<?php echo esc_attr($instance['description']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('description')); ?>" />
        </p>
		<p>
            <label for="<?php echo esc_attr($this->get_field_id('feedurl')); ?>"><?php _e('RSS feed URL:', 'ThemeStockyard'); ?></label>
            <textarea name="<?php echo esc_attr($this->get_field_name('feedurl')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('feedurl')); ?>"><?php echo esc_url($instance['feedurl']); ?></textarea>
            <span style="font-size:11px;color:#808080;">Will default to WordPress RSS feed if left blank.</span>
        </p>
        <?php
	}
} 

add_action( 'widgets_init', create_function( '', 'register_widget( "ts_follow_rss_widget" );' ) );