<?php

class PlcCategoryTags
{

    private static $instance = null;
    private static $validation = array();
    private static $data = array();

    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('init', array($this, 'create_db'));
        add_action('admin_enqueue_scripts', array($this, 'set_admin_assets'));
        add_action('admin_menu', array($this, 'categoryTags_menu'));

        //add_action('wp_ajax_nopriv_plc_upload_youtube_ajax', array($this, 'upload_youtube_ajax'));
        //add_action('wp_ajax_plc_upload_youtube_ajax', array($this, 'upload_youtube_ajax'));
        $this->run_plugin();
    }

    public function create_db()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . 'plc_category_tags';

        $sql = "CREATE TABLE IF NOT EXISTS $table ( ";
        $sql .= "category text NULL, ";
        $sql .= "tags text NULL ";
        $sql .= ") $charset_collate;";

        $wpdb->get_results($sql);
    }

    public function set_admin_assets()
    {
        wp_enqueue_script('categoryTags_js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery'), '4.0.3', true);

        wp_register_style('categoryTags_style', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css', false, '4.0.3', 'all');
        wp_enqueue_style('categoryTags_style');

        wp_register_script('chained-js', get_stylesheet_directory_uri() . '/assets/js/jquery.chained.min.js', array('jquery'), '1.0.9');
        wp_enqueue_script('chained-js');
    }

    public function categoryTags_menu()
    {
        add_menu_page('Category Tags', 'Category Tags', 'administrator', 'categoryTags_option', array($this, 'categoryTags_page'));
    }

    public function categoryTags_page()
    {
        $this->_proccess_post();

        $categories = get_categories(
                array(
                    'hide_empty' => 0,
                    'orderby' => 'name',
                    'order' => 'ASC'
                )
        );
        $tags = get_tags(
                array(
                    'hide_empty' => 0,
                    'orderby' => 'name',
                    'order' => 'ASC'
                )
        );
        $categories_tags = $this->get_categoryTags();
        $validation = $this->get_validation();

        ob_start();
        require_once( get_stylesheet_directory() . '/inc/categoryTags/view/index.php' );
        echo ob_get_clean();
    }

    private function _proccess_post()
    {
        $category = isset($_POST['category']) ? $_POST['category'] : '';
        $tags = isset($_POST['tags']) ? $_POST['tags'] : '';

        if ($category && $tags) {
            $this->data['category'] = serialize(explode(',', $category));
            $newtags = array();
            foreach ($tags as $tag) {
                $newtags[] = explode(',', $tag);
            }
            $this->data['tags'] = serialize($newtags);
            $this->_proccess_save();
        }
        elseif ($category && !$tags) {
            $this->data['category'] = serialize(explode(',', $category));
            $this->_delete();
        }
    }

    private function _proccess_save()
    {
        if ($this->_is_exist() > 0) {
            $this->_update();
        }
        else {
            $this->_insert();
        }
    }

    private function _is_exist()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'plc_category_tags';

        $sql = "SELECT COUNT(*) FROM " . $table;
        $sql .= " WHERE category LIKE '%" . $this->data['category'] . "%'";

        return $wpdb->get_var($sql);
    }

    private function _insert()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'plc_category_tags';

        $sql = "INSERT INTO ";
        $sql .= "`" . $table . "` ";
        $sql .= "(`category`, `tags`) VALUES (";
        $sql .= "'" . $this->data['category'] . "', '" . $this->data['tags'] . "');";

        $wpdb->get_results($sql);
        $this->set_validation('success', unserialize($this->data['category'])[1] . ' added to our list');
    }

    private function _update()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'plc_category_tags';

        $sql = "UPDATE " . $table . " SET ";
        $sql .= "tags = '" . $this->data['tags'] . "' ";
        $sql .= "WHERE category = '" . $this->data['category'] . "' ";

        $wpdb->get_results($sql);
        $this->set_validation('success', unserialize($this->data['category'])[1] . ' updated');
    }

    private function _delete()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'plc_category_tags';

        $sql = "DELETE FROM " . $table . " ";
        $sql .= "WHERE category = '" . $this->data['category'] . "' ";

        $wpdb->get_results($sql);
        $this->set_validation('success', unserialize($this->data['category'])[1] . ' deleted');
    }

    public function get_categoryTags()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'plc_category_tags';

        $sql = "SELECT * FROM " . $table;

        return $wpdb->get_results($sql);
    }

    private function set_validation($type, $msg)
    {
        $this->validation[] = '<div class="notice notice-' . $type . ' is-dismissible"><p>' . $msg . '</p></div>';
    }

    private function get_validation()
    {
        return $this->validation;
    }

    public function get_category_tag_array()
    {
        $categoryTags = $this->get_categoryTags();

        $categories_array = array();
        $tags_array = array();

        foreach ($categoryTags as $row) {
            $category = unserialize($row->category);
            $tags = unserialize($row->tags);
            $cat_id = $category[0];
            $cat_title = $category[1];
            $cat_value = $cat_id . ',' . $cat_title;
            $categories_array[$cat_id] = array(
                'id' => $cat_id,
                'title' => $cat_title,
                'value' => $cat_value
            );
            foreach ($tags as $tag) {
                $tag_id = $tag[0];
                $tag_title = $tag[1];
                $tag_value = $tag_id . ',' . $tag_title;
                $tags_array[$tag_id]['id'] = $tag_id;
                $tags_array[$tag_id]['title'] = $tag_title;
                $tags_array[$tag_id]['value'] = $tag_value;
                $tags_array[$tag_id]['class'][] = $cat_value;
                $categories_array[$cat_id]['tags'][$tag_id]['id'] = $tag_id;
                $categories_array[$cat_id]['tags'][$tag_id]['title'] = $tag_title;
                $categories_array[$cat_id]['tags'][$tag_id]['value'] = $tag_value;
                $categories_array[$cat_id]['tags'][$tag_id]['class'] = $cat_value;
            }
        }
        $array = array(
            'categories' => array_sort($categories_array, 'title', SORT_ASC),
            'tags' => $tags_array
        );

        return $array;
    }

    private function run_plugin()
    {
        
    }

}

$plcCategoryTags = PlcCategoryTags::get_instance();
