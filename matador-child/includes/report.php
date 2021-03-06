<?php
//Add view to user
function addViewUser($userId){
	global $wpdb;
	$sessionId = session_id();
	$table_name = $wpdb->prefix . 'jrrny_view_user';
	if(!empty($sessionId)){
		$sql = "SELECT id FROM ";
		$sql .= $table_name .' ';
		$sql .= "WHERE user_id= ";
		$sql .= $userId.' ';
		$sql .= "AND session_id= ";
		$sql .= "'".$sessionId."'";
		$sql .= ';';
		$result = $wpdb->get_results( $sql );
		if(count($result) <= 0) {
			$sql = "INSERT INTO ".$table_name." ";
			$sql .= "(user_id, session_id) ";
			$sql .= "VALUES ( ";
			$sql .= $userId . ", ";
			$sql .= "'".$sessionId . "' ";
			$sql .= ");";
			$ret = $wpdb->get_results($sql);
		}
	}	
}
//Add view to post
function addViewPost($postId){
	global $wpdb;
	$sessionId = session_id();
	$table_name = $wpdb->prefix . 'jrrny_view_post';
	if(!empty($sessionId)){
				$sql = "SELECT id FROM ";
		$sql .= $table_name .' ';
		$sql .= "WHERE post_id= ";
		$sql .= $postId.' ';
		$sql .= "AND session_id= ";
		$sql .= "'".$sessionId."'";
		$sql .= ';';
		$result = $wpdb->get_results( $sql );
		if(count($result) <= 0) {
			$sql = "INSERT INTO ".$table_name." ";
			$sql .= "(post_id, session_id) ";
			$sql .= "VALUES ( ";
			$sql .= $postId . ", ";
			$sql .= "'".$sessionId . "' ";
			$sql .= ");";
			$ret = $wpdb->get_results($sql);
		}
	}	
	
}

function reporting_create_db() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$views_user_table = $wpdb->prefix . 'jrrny_view_user';
	$views_post_table = $wpdb->prefix . 'jrrny_view_post';
	$users_table = $wpdb->prefix .'users';
	$posts_table = $wpdb->prefix . 'posts';

	$sql = "CREATE TABLE IF NOT EXISTS $views_user_table ( ";
	$sql .= "id bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY, ";
	$sql .= "user_id bigint(20) unsigned NOT NULL, ";
	$sql .= "session_id VARCHAR(32) NOT NULL, ";
	$sql .= "timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP, ";
	$sql .= "FOREIGN KEY (user_id) REFERENCES $users_table(ID) ";
	$sql .= "ON DELETE CASCADE ";
    $sql .= "ON UPDATE CASCADE ";
	$sql .= ") $charset_collate;";
	$ret = $wpdb->get_results( $sql );

	$sql = "CREATE TABLE IF NOT EXISTS $views_post_table ( ";
	$sql .= "id bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY, ";
	$sql .= "post_id bigint(20) unsigned NOT NULL, ";
	$sql .= "session_id VARCHAR(32) NOT NULL, ";
	$sql .= "timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP, ";
	$sql .= "FOREIGN KEY (post_id) REFERENCES $posts_table(ID) ";
	$sql .= "ON DELETE CASCADE ";
    $sql .= "ON UPDATE CASCADE ";
	$sql .= ") $charset_collate;";

	$ret = $wpdb->get_results( $sql );
}

add_action( 'init', 'reporting_create_db');

function save_view(){
	$hookCount = did_action('wp_footer');
	if($hookCount === 1){
		global $author;
		if(is_author() && !empty($author)){
			addViewUser($author);
		}
		$postId = get_the_ID ();
		if(is_single()  && !empty($postId)){
			addViewPost($postId);
		}
	}

}

add_action( 'wp_footer', 'save_view', PHP_INT_MAX);


function getTopUsersIds(){
	$ret = [];
	global $wpdb;
	$table_post_name = $wpdb->prefix . 'posts';
	$table_view_post_name = $wpdb->prefix . 'jrrny_view_post';
	$sql = "SELECT p.post_author, count(*) as count ";
	$sql .= "FROM " . $table_view_post_name ." as v ";
	$sql .= "LEFT JOIN ".$table_post_name." as p on p.ID=v.post_id ";
        
	$sql .= "WHERE v.timestamp BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY)";
	$sql .= "AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY)"; 
	$sql .= "AND p.post_author NOT IN (1, 13, 74)"; 
        //$sql .= "WHERE v.timestamp > date_sub(now(), interval 7 day)";
	//$sql .= "AND p.post_author != 1 "; //Remove jkueber
	//$sql .= "AND p.post_author != 13 "; //Remove justus
	//$sql .= "AND p.post_author != 74 "; //Remove wanderer
	$sql .= "GROUP BY p.post_author ";
	$sql .= "ORDER BY count DESC ";
	$sql .= "LIMIT 6 ";

	$returns = $wpdb->get_results( $sql );
	foreach($returns as $user){
		$ret[] = $user->post_author;
	}
	return $ret;
}

function getTopRandomUsersIds($limit, $exclude){
	$ret = [];
	global $wpdb;
	$table_post_name = $wpdb->prefix . 'posts';        
        
        $not_in = '';
        foreach($exclude as $value):
            $not_in = $value . ', ';
        endforeach;
        $not_in = rtrim($not_in, ', ');
        
	$sql = "SELECT post_author ";
	$sql .= "FROM " . $table_post_name ." ";        
	$sql .= "WHERE post_status = 'publish' "; 
        if($not_in){
            $sql .= "AND post_author NOT IN (" . $not_in . ") "; 
        }   
	$sql .= "GROUP BY post_author ";
	$sql .= "ORDER BY RAND() ";
	$sql .= "LIMIT " . $limit . " ";
        
	$returns = $wpdb->get_results( $sql );
	foreach($returns as $user){
		$ret[] = $user->post_author;
	}
	return $ret;
}