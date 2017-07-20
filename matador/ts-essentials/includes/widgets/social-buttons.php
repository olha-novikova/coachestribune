<?php
/*---------------------------------------------------------------------------------*/
/* Social Buttons */
/*---------------------------------------------------------------------------------*/

class ts_social_buttons_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array(
            'classname' => 'ts-social-buttons-widget',
            'description' => __('This widget shows Facebook, Twitter, and Pinterest like/follow buttons.', 'ThemeStockyard') 
        );
		parent::__construct(false, '(TS) '.__('Social Buttons', 'ThemeStockyard'),$widget_ops);      
	}

	function widget($args, $instance) 
	{
		$label          = $instance['label'];
		$facebook       = trim($instance['facebook_username']);
		$twitter        = trim($instance['twitter_username']);	
		$pinterest      = trim($instance['pinterest_username']);
		$gplus          = (isset($instance['gplus_username'])) ? trim($instance['gplus_username']) : '';
		$orientation    = $instance['orientation'];
		$align          = $instance['align'];
		$align          = (in_array($align, array('left', 'center', 'right'))) ? 'text-'.$align : 'text-left';
        $align          = ($orientation == 'horizontal') ? $align : 'text-left';

        echo ts_essentials_escape($args['before_widget']);
        
        echo '<div class="inner '.esc_attr($align).'">';

		if ( ! empty( $title ) ) echo ts_essentials_escape($args['before_title'] . apply_filters( 'widget_title', $title ). $args['after_title']);
        
        echo '<div class="stuff">';
        $orientation_class = ($orientation == 'horizontal') ? 'inline-block' : 'block';
        
        if($facebook) :
            $pre = (is_numeric($facebook)) ? 'https://www.facebook.com/profile.php?id=' : 'https://www.facebook.com/';
            $facebook = (substr($facebook, 0, 4) == 'http') ? $facebook : $pre . $facebook;
            echo '<div class="fb '.esc_attr($orientation_class).'">';
            echo '<iframe src="//www.facebook.com/plugins/like.php?href='.urlencode($facebook);
            echo '&amp;send=false&amp;layout=button_count&amp;width=70&amp;show_faces=false';
            echo '&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21"';
            echo ' style="border:none; overflow:hidden; width:300px; height:35px;"';
            echo '></iframe></div>';
        endif;
        
        if($twitter) :
            echo '<div class="tw '.esc_attr($orientation_class).'">';
            echo '<a href="https://twitter.com/'.esc_attr($twitter).'" class="twitter-follow-button"';
            echo ' data-show-count="false">Follow @'.esc_html($twitter).'</a>';
            echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></div>";
        endif;
        
        if($pinterest) :
            echo '<div class="pn '.esc_attr($orientation_class).'">';
            echo '<a href="http://www.pinterest.com/'.esc_attr($pinterest).'/" target="_blank">';
            echo esc_html($pinterest).'<b></b><i></i></a></div>';
        endif;
        
        echo '</div>';
		
		echo '</div>';
		
		echo ts_essentials_escape($args['after_widget']);

	}

	function update($new_instance, $old_instance) {                
		$new_instance = (array) $new_instance;

        $instance['label']   = strip_tags( $new_instance['label']);
        $instance['facebook_username'] = strip_tags( $new_instance['facebook_username']);
        $instance['twitter_username'] = strip_tags($new_instance['twitter_username']);
		$instance['pinterest_username'] = $new_instance['pinterest_username'];
		$instance['gplus_username'] = (isset($new_instance['gplus_username'])) ? $new_instance['gplus_username'] : '';
		$instance['orientation'] = $new_instance['orientation'];
		$instance['align'] = $new_instance['align'];

        return $instance;
	}

	function form($instance) {        
		
		$defaults = array( 
            'label'         => __("Like & Follow", 'ThemeStockyard'),
			'facebook_username'      => '', 
			'twitter_username'       => '',
			'pinterest_username' => '',
			'gplus_username' => '',
			'orientation' => 'vertical',
			'align' => '',
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('label')); ?>"><?php _e('Label (optional):', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('label')); ?>" value="<?php echo esc_attr($instance['label']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('label')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('facebook_username')); ?>"><?php _e('Facebook Page Username/ID:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('facebook_username')); ?>" value="<?php echo esc_attr($instance['facebook_username']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook_username')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('twitter_username')); ?>"><?php _e('Twitter Username:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('twitter_username')); ?>" value="<?php echo esc_attr($instance['twitter_username']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('twitter_username')); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('pinterest_username')); ?>"><?php _e('Pinterest Username:', 'ThemeStockyard'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('pinterest_username')); ?>" value="<?php echo esc_attr($instance['pinterest_username']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('pinterest_username')); ?>" />
        </p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('orientation')); ?>"><?php _e('Display Orientation:', 'ThemeStockyard'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('orientation')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('orientation')); ?>">
				<option value="horizontal" <?php selected($instance['orientation'], "horizontal");?>><?php _e('Horizontal', 'ThemeStockyard'); ?></option>
				<option value="vertical" <?php selected($instance['orientation'], "vertical");?>><?php _e('Vertical', 'ThemeStockyard'); ?></option>     
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('align')); ?>"><?php _e('Text Alignment:', 'ThemeStockyard'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('align')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('align')); ?>">
				<option value="left" <?php selected($instance['align'], "left");?>><?php _e('Left', 'ThemeStockyard'); ?></option>
				<option value="center" <?php selected($instance['align'], "center");?>><?php _e('Center', 'ThemeStockyard'); ?></option> 
				<option value="right" <?php selected($instance['align'], "right");?>><?php _e('Right', 'ThemeStockyard'); ?></option>      
			</select>
			<small><?php _e('Only works with horizontal orientation', 'ThemeStockyard');?></small>
		</p>
        <?php
	}
} 

add_action( 'widgets_init', create_function( '', 'register_widget( "ts_social_buttons_widget" );' ) );