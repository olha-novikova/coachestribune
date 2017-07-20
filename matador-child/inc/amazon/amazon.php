<?php

function plc_remove_uploaded_files_from_amazon()
{
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'postmeta';
    
    $sql = "SELECT post_id, meta_value FROM ";
    $sql .= '`' . $table_name  . '` ';
    $sql .= "WHERE `meta_key` = 'amazonS3_info' ";
    $sql .= "ORDER BY meta_id DESC ";
    $sql .= "LIMIT 100 ";
    $result = $wpdb->get_results( $sql );
    if($result){
        foreach($result as $value){
            $id = $value->post_id;            
            $meta = wp_get_attachment_metadata($id);   
            $fullsize_path = get_attached_file( $id );
            $filename_only = basename( get_attached_file( $id ) ); 
            if($meta){
                $file = $meta['file'];
                $sizes = $meta['sizes'];
                plc_remove_file($fullsize_path);
                if($sizes){
                    foreach($sizes as $size){
                        $remove = str_replace($filename_only, $size['file'], $fullsize_path);     
                        plc_remove_file($remove);
                    }
                }
            }
        }
    }    
}

function plc_remove_file($file)
{
    if($file && file_exists($file)){
        unlink($file);
    }
}
/*
function plc_daily_remove_file_schedule() {
    if (!wp_next_scheduled( 'plc_daily_remove_file_cron_job' )) {
        wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'plc_daily_remove_file_cron_job' );
    }
}

add_action('init','plc_daily_remove_file_schedule');
add_action('plc_daily_remove_file_cron_job','plc_remove_uploaded_files_from_amazon');
*/