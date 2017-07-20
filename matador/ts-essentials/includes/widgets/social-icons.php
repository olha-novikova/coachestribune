<?php
/*---------------------------------------------------------------------------------*/
/* Social Icons Widget */
/*---------------------------------------------------------------------------------*/

class ts_social_icons_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array(
            'classname' => 'ts-social-icons-widget',
            'description' => __('This widget shows linked social icons.', 'ThemeStockyard') 
        );
		parent::__construct(false, '(TS) '.__('Social Icons', 'ThemeStockyard'),$widget_ops);      
	}

	function widget($args, $instance) 
	{
		$label          = $instance['label'];
		$facebook       = $instance['facebook'];
		$twitter        = $instance['twitter'];	
		$pinterest      = $instance['pinterest'];
		$google_plus      = $instance['google_plus'];
		$github      = $instance['github'];
		$linkedin      = $instance['linkedin'];
		$instagram      = $instance['instagram'];
		$flickr      = $instance['flickr'];
		$youtube      = $instance['youtube'];
		$vimeo      = $instance['vimeo'];
		$tumblr      = $instance['tumblr'];
		$vk      = (isset($instance['vk'])) ? $instance['vk'] : '';
		$behance      = $instance['behance'];
		$dribbble      = $instance['dribbble'];
		$soundcloud      = $instance['soundcloud'];
		$rss      = $instance['rss'];

        echo ts_essentials_escape($args['before_widget']);
        
        echo '<div class="inner social-icons-widget-style">';

		if ( ! empty( $title ) ) echo ts_essentials_escape($args['before_title'] . apply_filters( 'widget_title', $title ). $args['after_title']);
        
        echo '<div class="social social-fa-icons">';
        
        echo ts_essentials_output_social_icon('facebook', '', $facebook);
        echo ts_essentials_output_social_icon('twitter', '', $twitter);
        echo ts_essentials_output_social_icon('pinterest', '', $pinterest);
        echo ts_essentials_output_social_icon('google_plus', '', $google_plus);
        echo ts_essentials_output_social_icon('github', '', $github);
        echo ts_essentials_output_social_icon('linkedin', '', $linkedin);
        echo ts_essentials_output_social_icon('instagram', '', $instagram);
        echo ts_essentials_output_social_icon('flickr', '', $flickr);
        echo ts_essentials_output_social_icon('youtube', '', $youtube);
        echo ts_essentials_output_social_icon('vimeo', '', $vimeo);
        echo ts_essentials_output_social_icon('vk', '', $vk);
        echo ts_essentials_output_social_icon('tumblr', '', $tumblr);
        echo ts_essentials_output_social_icon('behance', '', $behance);
        echo ts_essentials_output_social_icon('dribbble', '', $dribbble);
        echo ts_essentials_output_social_icon('soundcloud', '', $soundcloud);
        echo ts_essentials_output_social_icon('rss', '', $rss);
        echo '</div>';       
		
		echo '</div>';
		
		echo ts_essentials_escape($args['after_widget']);

	}

	function update($new_instance, $old_instance) {                
		$new_instance = (array) $new_instance;

        $instance['label']   = $new_instance['label'];
        $instance['facebook'] = $new_instance['facebook'];
        $instance['twitter'] = $new_instance['twitter'];
		$instance['pinterest'] = $new_instance['pinterest'];
		$instance['google_plus'] = $new_instance['google_plus'];
		$instance['github'] = $new_instance['github'];
		$instance['linkedin'] = $new_instance['linkedin'];
		$instance['instagram'] = $new_instance['instagram'];
		$instance['flickr'] = $new_instance['flickr'];
		$instance['youtube'] = $new_instance['youtube'];
		$instance['vimeo'] = $new_instance['vimeo'];
		$instance['vk'] = (isset($new_instance['vk'])) ? $new_instance['vk'] : '';
		$instance['tumblr'] = $new_instance['tumblr'];
		$instance['behance'] = $new_instance['behance'];
		$instance['dribbble'] = $new_instance['dribbble'];
		$instance['soundcloud'] = $new_instance['soundcloud'];
		$instance['rss'] = $new_instance['rss'];

        return $instance;
	}

	function form($instance) {        
		
		$defaults = array( 
            'label'         => __("Social Links...", 'ThemeStockyard'),
			'facebook'      => ts_option_vs_default('social_url_facebook', ''), 
			'twitter'       => ts_option_vs_default('social_url_twitter', ''),
			'pinterest' => ts_option_vs_default('social_url_pinterest', ''),
			'google_plus' => ts_option_vs_default('social_url_google_plus', ''),
			'github' => ts_option_vs_default('social_url_github', ''),
			'linkedin' => ts_option_vs_default('social_url_linkedin', ''),
			'instagram' => ts_option_vs_default('social_url_instagram', ''),
			'flickr' => ts_option_vs_default('social_url_flickr', ''),
			'youtube' => ts_option_vs_default('social_url_youtube', ''),
			'vimeo' => ts_option_vs_default('social_url_vimeo', ''),
			'vk' => ts_option_vs_default('social_url_vk', ''),
			'tumblr' => ts_option_vs_default('social_url_tumblr', ''),
			'behance' => ts_option_vs_default('social_url_behance', ''),
			'dribbble' => ts_option_vs_default('social_url_dribbble', ''),
			'soundcloud' => ts_option_vs_default('social_url_soundcloud', ''),
			'rss' => ts_option_vs_default('social_url_rss', ''),
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('label')); ?>"><?php _e('Label (optional):', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('label')); ?>" value="<?php echo esc_attr($instance['label']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('label')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('facebook')); ?>"><?php _e('Facebook URL:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('facebook')); ?>" value="<?php echo esc_attr($instance['facebook']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('twitter')); ?>"><?php _e('Twitter URL:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('twitter')); ?>" value="<?php echo esc_attr($instance['twitter']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('twitter')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('pinterest')); ?>"><?php _e('Pinterest URL:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('pinterest')); ?>" value="<?php echo esc_attr($instance['pinterest']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('pinterest')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('google_plus')); ?>"><?php _e('Google+ URL:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('google_plus')); ?>" value="<?php echo esc_attr($instance['google_plus']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('google_plus')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('github')); ?>"><?php _e('Github URL:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('github')); ?>" value="<?php echo esc_attr($instance['github']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('github')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('linkedin')); ?>"><?php _e('LinkedIn URL:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('linkedin')); ?>" value="<?php echo esc_attr($instance['linkedin']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('linkedin')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('instagram')); ?>"><?php _e('Instagram URL:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('instagram')); ?>" value="<?php echo esc_attr($instance['instagram']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('instagram')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('flickr')); ?>"><?php _e('Flickr URL:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('flickr')); ?>" value="<?php echo esc_attr($instance['flickr']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('flickr')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('youtube')); ?>"><?php _e('Youtube URL:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('youtube')); ?>" value="<?php echo esc_attr($instance['youtube']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('youtube')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('vimeo')); ?>"><?php _e('Vimeo URL:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('vimeo')); ?>" value="<?php echo esc_attr($instance['vimeo']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('vimeo')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('vk')); ?>"><?php _e('VK URL:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('vk')); ?>" value="<?php echo esc_attr($instance['vk']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('vk')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('tumblr')); ?>"><?php _e('Tumblr URL:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('tumblr')); ?>" value="<?php echo esc_attr($instance['tumblr']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('tumblr')); ?>" />
        </p>
        <p>
			<label for="<?php echo esc_attr($this->get_field_id('behance')); ?>"><?php _e('Behance URL:', 'ThemeStockyard'); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('behance')); ?>" name="<?php echo esc_attr($this->get_field_name('behance')); ?>" value="<?php echo esc_attr($instance['behance']); ?>" />
		</p>
	     
	    <p>
	 		<label for="<?php echo esc_attr($this->get_field_id('dribbble')); ?>"><?php  _e('Dribbble URL:', 'ThemeStockyard'); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('dribbble')); ?>" name="<?php echo esc_attr($this->get_field_name('dribbble')); ?>" value="<?php echo esc_attr($instance['dribbble']); ?>" />
	    </p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('soundcloud')); ?>"><?php _e('SoundCloud URL:', 'ThemeStockyard'); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('soundcloud')); ?>" name="<?php echo esc_attr($this->get_field_name('soundcloud')); ?>" value="<?php echo esc_attr($instance['soundcloud']); ?>" />
		</p>
	     
	    <p>
	 		<label for="<?php echo esc_attr($this->get_field_id('rss')); ?>"><?php  _e('RSS URL:', 'ThemeStockyard'); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('rss')); ?>" name="<?php echo esc_attr($this->get_field_name('rss')); ?>" value="<?php echo esc_attr($instance['rss']); ?>" />
	    </p>
	    <p><small><?php _e('(use the <strong>[rss_url]</strong> shortcode for the default RSS url)', 'ThemeStockyard');?></small></p>
        <?php
	}
} 

add_action( 'widgets_init', create_function( '', 'register_widget( "ts_social_icons_widget" );' ) );