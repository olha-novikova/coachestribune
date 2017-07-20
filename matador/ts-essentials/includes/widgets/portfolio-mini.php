<?php
/*---------------------------------------------------------------------------------*/
/* Portfolio Mini Widget */
/*---------------------------------------------------------------------------------*/
if(!ts_essentials_posttype_supported('portfolio')) return;

class ts_portfolio_mini_widget extends WP_Widget {

    function __construct() {
        $widget_ops = array(
            'classname' => 'portfolio-mini-widget',
            'description' => __('Display portfolio posts as single column.', 'ThemeStockyard') 
        );
        parent::__construct(false, '(TS) '.__('Portfolio Mini', 'ThemeStockyard'),$widget_ops);      
    }
    
    function widget($args, $instance) 
    {  
        
        $title = $instance['title'];        
        $limit   = $instance['limit'];
        $orderby = (isset($instance['orderby'])) ? $instance['orderby'] : '';
        
        
        echo ts_essentials_escape($args['before_widget']);
        
		if ( ! empty( $title ) ) echo ts_essentials_escape($args['before_title'] . apply_filters( 'widget_title', $title ). $args['after_title']);
		
        echo '<div class="portfolio-mini-widget-inner clearfix">';
        echo do_shortcode('[portfolio layout="mini" limit="'.esc_attr($limit).'" called_via="widget" orderby="'.esc_attr($orderby).'"][/portfolio]');
        echo '</div>';
        
        echo ts_essentials_escape($args['after_widget']);
   }

    function update($new_instance, $old_instance) {                
        
        $new_instance = (array) $new_instance;
        
        $instance['title'] = $new_instance['title'];
        $instance['limit']   = $new_instance['limit'];
        $instance['orderby']   = (isset($new_instance['orderby'])) ? $new_instance['orderby'] : '';

        return $instance;
    }


    function form($instance) {

        $defaults = array(            
            'title' => '',
            'limit' => '5',
            'orderby' => ''
        );

        $instance = wp_parse_args($instance, $defaults);
        ?>
        <p>
		   <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'ThemeStockyard'); ?></label>
		   <input type="text" name="<?php echo esc_attr($this->get_field_name('title')); ?>"  value="<?php echo esc_attr($instance['title']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('limit')); ?>"><?php _e('Limit:', 'ThemeStockyard'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('limit')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('limit')); ?>">
				<?php
				for($i = 1; $i <= 10; $i++)
				{
                    echo '<option value="'.$i.'" '.selected($instance['limit'], $i, false).'>'.$i.'</option>'."\n";
				}
				?>      
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php _e('Sort Order...', 'ThemeStockyard'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('orderby')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>">
				<option value="" <?php selected($instance['orderby'], "");?>><?php _e('Default', 'ThemeStockyard'); ?></option>
				<option value="rand" <?php selected($instance['orderby'], "rand");?>><?php _e('Random', 'ThemeStockyard'); ?></option>      
			</select>
		</p>
    <?php 
    }
}

add_action( 'widgets_init', create_function( '', 'register_widget( "ts_portfolio_mini_widget" );' ) );