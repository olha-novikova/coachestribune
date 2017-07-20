<?php
/*
Template Name: Login
*/
if(is_user_logged_in()){
	global $current_user;
	wp_get_current_user();
	$redirectUrl = home_url() . '/author/' . $current_user->user_login;
	wp_redirect($redirectUrl, '301');
	exit;
}
get_header();
get_template_part('top');
?>

<?php
get_footer();
?>