<?php
/*---------------------------------------------------------------------------------*/
/* Blog Mini Widget */
/*---------------------------------------------------------------------------------*/
class ts_blog_mini_widget extends WP_Widget {

    function __construct() {
        $widget_ops = array(
            'classname' => 'blog-mini-widget',
            'description' => __('Display blog posts in widget form.', 'ThemeStockyard') 
        );
        parent::__construct(false, '(TS) '.__('Blog Mini', 'ThemeStockyard'),$widget_ops);      
    }
    
    function widget($args, $instance) 
    {          
        $title = $instance['title'];
        $title_link = (isset($instance['title_link'])) ? $instance['title_link'] : '';
        
        $widget_layout = $instance['widget_layout'];
        $limit   = $instance['limit'];
        $category_name = (isset($instance['category_name'])) ? $instance['category_name'] : '';
        $category_name = (is_array($category_name)) ? implode(',', $category_name) : $category_name;
        $cat = (isset($instance['cat'])) ? $instance['cat'] : '';
        $cat = (is_array($cat)) ? implode(',', $cat) : $cat;
        $exclude_previous_posts   = $instance['exclude_previous_posts'];
        $exclude_these_later   = $instance['exclude_these_later'];
        $show_excerpt   = $instance['show_excerpt'];
        $show_meta   = $instance['show_meta'];
        $show_media   = $instance['show_media'];
        $allow_videos   = $instance['allow_videos'];
        $allow_galleries   = $instance['allow_galleries'];
        $orderby = (isset($instance['orderby'])) ? $instance['orderby'] : '';
        
        
        echo ts_essentials_escape($args['before_widget']);
        
		if ( ! empty( $title ) ) :
            echo ts_essentials_escape($args['before_title']); 
            echo (trim($title_link)) ? '<a href="'.esc_attr($title_link).'">' : '';
            echo apply_filters( 'widget_title', $title );
            echo (trim($title_link)) ? '<i class="fa fa-angle-right"></i></a>' : ''; 
            echo ts_essentials_escape($args['after_title']);
        endif;
		
        echo '<div class="blog-mini-widget-inner clearfix">';
        if(isset($instance['cat'])) : // backwords compatibility
            echo do_shortcode('[blog_widget widget_layout="'.esc_attr($widget_layout).'" limit="'.esc_attr($limit).'" cat="'.esc_attr($cat).'" override_widget_heading="no" exclude_previous_posts="'.esc_attr($exclude_previous_posts).'" exclude_these_later="'.esc_attr($exclude_these_later).'" show_excerpt="'.esc_attr($show_excerpt).'" show_meta="'.esc_attr($show_meta).'" show_media="'.esc_attr($show_media).'" allow_videos="'.esc_attr($allow_videos).'" allow_galleries="'.esc_attr($allow_galleries).'" called_via="widget" orderby="'.esc_attr($orderby).'"][/blog_widget]');
        else :
            echo do_shortcode('[blog_widget widget_layout="'.esc_attr($widget_layout).'" limit="'.esc_attr($limit).'" category_name="'.esc_attr($category_name).'" override_widget_heading="no" exclude_previous_posts="'.esc_attr($exclude_previous_posts).'" exclude_these_later="'.esc_attr($exclude_these_later).'" show_excerpt="'.esc_attr($show_excerpt).'" show_meta="'.esc_attr($show_meta).'" show_media="'.esc_attr($show_media).'" allow_videos="'.esc_attr($allow_videos).'" allow_galleries="'.esc_attr($allow_galleries).'" called_via="widget" orderby="'.esc_attr($orderby).'"][/blog_widget]');
        endif;
        echo '</div>';
        
        echo ts_essentials_escape($args['after_widget']);
   }

    function update($new_instance, $old_instance) {                
        
        $new_instance = (array) $new_instance;
        
        $instance['title'] = $new_instance['title'];
        $instance['title_link']   = (isset($new_instance['title_link'])) ? $new_instance['title_link'] : '';
        $instance['widget_layout'] = $new_instance['widget_layout'];
        $instance['limit']   = $new_instance['limit'];
        $instance['cat']   = (isset($new_instance['cat'])) ? $new_instance['cat'] : '';
        $instance['exclude_previous_posts']   = $new_instance['exclude_previous_posts'];
        $instance['exclude_these_later']   = $new_instance['exclude_these_later'];
        $instance['show_excerpt']   = $new_instance['show_excerpt'];
        $instance['show_meta']   = $new_instance['show_meta'];
        $instance['show_media']   = $new_instance['show_media'];
        $instance['allow_videos']   = $new_instance['allow_videos'];
        $instance['allow_galleries']   = $new_instance['allow_galleries'];
        $instance['orderby']   = (isset($new_instance['orderby'])) ? $new_instance['orderby'] : '';

        return $instance;
    }


    function form($instance) {

        $defaults = array(            
            'title' => '',
            'title_link' => '',
            'widget_layout' => '',
            'limit' => '5',
            'cat' => '',
            'exclude_previous_posts' => 'no',
            'exclude_these_later' => 'no',
            'show_excerpt' => 'no',
            'show_meta' => 'yes',
            'show_media' => 'yes',
            'allow_videos' => 'yes',
            'allow_galleries' => 'no',
            'orderby' => ''
        );

        $instance = wp_parse_args($instance, $defaults);

        $category_namess = get_terms('category');
        foreach ($category_namess as $cat) {
            $category_names[$cat->term_id] = $cat->name;
        }
        ?>
        <p>
		   <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'ThemeStockyard'); ?></label>
		   <input type="text" name="<?php echo esc_attr($this->get_field_name('title')); ?>"  value="<?php echo esc_attr($instance['title']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" />
		</p>
        <p>
		   <label for="<?php echo esc_attr($this->get_field_id('title_link')); ?>"><?php _e('Title Link (URL):', 'ThemeStockyard'); ?></label>
		   <input type="text" name="<?php echo esc_attr($this->get_field_name('title_link')); ?>"  value="<?php echo esc_attr($instance['title_link']); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('title_link')); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('widget_layout')); ?>"><?php _e('Layout:', 'ThemeStockyard'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('widget_layout')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('widget_layout')); ?>">
				<option value="vertical" <?php selected($instance['widget_layout'], "vertical");?>><?php _e('Vertical', 'ThemeStockyard'); ?></option>
				<option value="horizontal" <?php selected($instance['widget_layout'], "horizontal");?>><?php _e('Horizontal', 'ThemeStockyard'); ?></option>           
			</select>
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
			<label for="<?php echo esc_attr($this->get_field_id('cat')); ?>"><?php _e('Category:', 'ThemeStockyard'); ?></label>
			<small><?php _e('(leave blank to show all)', 'ThemeStockyard');?></small>
			<select name="<?php echo esc_attr($this->get_field_name('cat')); ?>[]" class="widefat" id="<?php echo esc_attr($this->get_field_id('cat')); ?>" multiple>
				<?php
				foreach($category_names AS $key => $category_name)
				{
                    if(is_array($instance['cat']))
                        echo '<option value="'.$key.'" '.(in_array($key, $instance['cat']) ? 'selected' : '').'>'.$category_name.'</option>'."\n";
                    else
                        echo '<option value="'.$key.'" '.selected($instance['cat'], $key, false).'>'.$category_name.'</option>'."\n";
				}
				?>      
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('exclude_previous_posts')); ?>"><?php _e('Exclude Previous Posts?', 'ThemeStockyard'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('exclude_previous_posts')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('exclude_previous_posts')); ?>">
				<option value="no" <?php selected($instance['exclude_previous_posts'], "no");?>><?php _e('No', 'ThemeStockyard'); ?></option>
				<option value="yes" <?php selected($instance['exclude_previous_posts'], "yes");?>><?php _e('Yes', 'ThemeStockyard'); ?></option>      
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('exclude_these_later')); ?>"><?php _e('Exclude These Posts Later?', 'ThemeStockyard'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('exclude_these_later')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('exclude_these_later')); ?>">
				<option value="no" <?php selected($instance['exclude_these_later'], "no");?>><?php _e('No', 'ThemeStockyard'); ?></option>
				<option value="yes" <?php selected($instance['exclude_these_later'], "yes");?>><?php _e('Yes', 'ThemeStockyard'); ?></option>      
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>"><?php _e('Show Excerpt?', 'ThemeStockyard'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('show_excerpt')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>">
				<option value="no" <?php selected($instance['show_excerpt'], "no");?>><?php _e('No', 'ThemeStockyard'); ?></option>
				<option value="yes" <?php selected($instance['show_excerpt'], "yes");?>><?php _e('Yes', 'ThemeStockyard'); ?></option>      
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_meta')); ?>"><?php _e('Show Meta?', 'ThemeStockyard'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('show_meta')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('show_meta')); ?>">
				<option value="yes" <?php selected($instance['show_meta'], "yes");?>><?php _e('Yes', 'ThemeStockyard'); ?></option> 
				<option value="no" <?php selected($instance['show_meta'], "no");?>><?php _e('No', 'ThemeStockyard'); ?></option>     
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_media')); ?>"><?php _e('Show Media (images, videos, etc)?', 'ThemeStockyard'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('show_media')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('show_media')); ?>">
				<option value="yes" <?php selected($instance['show_media'], "yes");?>><?php _e('Yes', 'ThemeStockyard'); ?></option> 
				<option value="first" <?php selected($instance['show_media'], "first");?>><?php _e('Only for the first post', 'ThemeStockyard'); ?></option> 
				<option value="no" <?php selected($instance['show_media'], "no");?>><?php _e('No', 'ThemeStockyard'); ?></option>     
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('allow_videos')); ?>"><?php _e('Allow videos?', 'ThemeStockyard'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('allow_videos')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('allow_videos')); ?>">
				<option value="yes" <?php selected($instance['allow_videos'], "yes");?>><?php _e('Yes', 'ThemeStockyard'); ?></option>
				<option value="no" <?php selected($instance['allow_videos'], "no");?>><?php _e('No', 'ThemeStockyard'); ?></option>      
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('allow_galleries')); ?>"><?php _e('Allow Image Galleries?', 'ThemeStockyard'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('allow_galleries')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('allow_galleries')); ?>">
				<option value="no" <?php selected($instance['allow_galleries'], "no");?>><?php _e('No', 'ThemeStockyard'); ?></option>
				<option value="yes" <?php selected($instance['allow_galleries'], "yes");?>><?php _e('Yes', 'ThemeStockyard'); ?></option>      
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

add_action( 'widgets_init', create_function( '', 'register_widget( "ts_blog_mini_widget" );' ) );