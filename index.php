<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
	//check ip from share internet
	$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
//to check ip is pass from proxy
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$ip = $_SERVER['REMOTE_ADDR'];
}

if ($ip == "::1") {
	$ip = "99.231.18.122";
}

$ip_api_response = wp_remote_get( 'http://ip-api.com/json/'.$ip );
$user_location = json_decode(wp_remote_retrieve_body( $ip_api_response ));
$user_location     = get_object_vars($user_location);

$context = Timber::get_context();
$context['posts'] = new Timber\PostQuery();
$context['foo'] = 'bar';
$context['is_home'] = is_home();
$context['is_archive'] = is_archive();
$context['is_single'] = is_single();
$context['the_title'] = get_the_title();
$context['is_category'] = is_category();
$context['user_location'] = $user_location;

$templates = array( 'index.twig' );
if ( is_home() ) {
	array_unshift( $templates, 'home.twig' );
}
Timber::render( $templates, $context );
