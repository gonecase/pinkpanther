<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});

	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}


if ( ! class_exists( 'ACF' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Advanced Custom Fields Pro not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});

	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});
	return;
}


// helper function for meta values
function get_meta_values( $key = '', $type = 'post', $unique = false, $status = 'publish') {

	global $wpdb;

	if( empty( $key ) )
			return;

	$r = $wpdb->get_col( $wpdb->prepare( "
			SELECT pm.meta_value FROM {$wpdb->postmeta} pm
			LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			WHERE pm.meta_key = '%s' 
			AND p.post_status = '%s' 
			AND p.post_type = '%s'
	", $key, $status, $type ) );

	if ($unique) {
		$r = array_unique($r);
	}

	return $r;
}

function my_acf_init() {
	acf_update_setting('google_api_key', 'AIzaSyAMl2Yot5ehVFYUBOljQTPUnw2myMEVMmo');
}

// store lat and long on separate non serialized fields
function acf_save_lat_lng( $post_id ) {
	// get value of ACF map field
	$value = get_field( 'location' );
	// update lat and lng as separate custom fields
	update_post_meta( $post_id, 'post_lat', $value['lat'] );
	update_post_meta( $post_id, 'post_lng', $value['lng'] );
}

add_action('acf/save_post', 'acf_save_lat_lng', 20);

add_action('acf/init', 'my_acf_init');

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page('Theme Settings');
}

/**
 * Disable the emoji's
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
 }
 add_action( 'init', 'disable_emojis' );

 /**
	* Filter function used to remove the tinymce emoji plugin.
	*
	* @param array $plugins
	* @return array Difference betwen the two arrays
	*/
 function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
	return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
	return array();
	}
 }

 /**
	* Remove emoji CDN hostname from DNS prefetching hints.
	*
	* @param array $urls URLs to print for resource hints.
	* @param string $relation_type The relation type the URLs are printed for.
	* @return array Difference betwen the two arrays.
	*/
 function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
	/** This filter is documented in wp-includes/formatting.php */
	$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

 $urls = array_diff( $urls, array( $emoji_svg_url ) );
	}

 return $urls;
 }


/**
 * Include posts from authors in the search results where
 * either their display name or user login matches the query string
 *
 * @author danielbachhuber
 */
add_filter( 'posts_search', 'db_filter_authors_search' );
function db_filter_authors_search( $posts_search ) {

	// Don't modify the query at all if we're not on the search template
	// or if the LIKE is empty
	if ( !is_search() || empty( $posts_search ) )
		return $posts_search;

	global $wpdb;
	// Get all of the users of the blog and see if the search query matches either
	// the display name or the user login
	add_filter( 'pre_user_query', 'db_filter_user_query' );
	$search = sanitize_text_field( get_query_var( 's' ) );
	$args = array(
		'count_total' => false,
		'search' => sprintf( '*%s*', $search ),
		'search_fields' => array(
			'display_name',
			'user_login',
		),
		'fields' => 'ID',
	);
	$matching_users = get_users( $args );
	remove_filter( 'pre_user_query', 'db_filter_user_query' );
	// Don't modify the query if there aren't any matching users
	if ( empty( $matching_users ) )
		return $posts_search;
	// Take a slightly different approach than core where we want all of the posts from these authors
	$posts_search = str_replace( ')))', ")) OR ( {$wpdb->posts}.post_author IN (" . implode( ',', array_map( 'absint', $matching_users ) ) . ")))", $posts_search );
	return $posts_search;
}
/**
 * Modify get_users() to search display_name instead of user_nicename
 */
function db_filter_user_query( &$user_query ) {

	if ( is_object( $user_query ) )
		$user_query->query_where = str_replace( "user_nicename LIKE", "display_name LIKE", $user_query->query_where );
	return $user_query;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats', array( 'featured', 'gallery' ) );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}

	function register_post_types() {
		//this is where you can register custom post types
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function add_to_context( $context ) {
		$wpp_popular_query = new WPP_Query( array('range' => 'all', 'order_by' => 'views', 'limit' => 10) );
		$preheader_arguments = array(
			// Get post type project
			'post_type' => 'post',
			// Ignore sticky posts
			'post__not_in'	 => get_option( 'sticky_posts' ),
			// Get all posts
			'posts_per_page' => 10,
			// Order by post date
			'orderby' => array(
					'date' => 'DESC'
			),
		);


		$sticky_event_args = array(
			'post_type'           => 'events',
			'post__in'            => get_option( 'sticky_posts' ),
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => 1
		);

    $today = date('Ymd');
		$close_event_args = array(
			'post_type'           => 'events',
			'posts_per_page'      => 2,
			'post__not_in'				=> get_option( 'sticky_posts' ),
			'ignore_sticky_posts' => false,
			'geo_query' => [
					'lat'                =>  $user_location['lat'],                                // Latitude point
					'lng'                =>  $user_location['lon'],                               // Longitude point
					'lat_meta_key'       =>  'post_lat',                         // Meta-key for the latitude data
					'lng_meta_key'       =>  'post_lng',                         // Meta-key for the longitude data
					'radius'             =>  40000,                               // Find locations within a given radius (km)
					'order'              =>  'DESC',                            // Order by distance
					'distance_unit'      =>  111.045,                           // Default distance unit (km per degree). Use 69.0 for statute miles per degree.
					'context'            => '\\Birgir\\Geo\\GeoQueryHaversine', // Default implementation, you can use your own here instead.
			]
    );
    $listing_query = array(
			'post_type'           => 'listing',
			'post__in'            => get_option( 'sticky_posts' ),
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => 1
		);
		$context['preheader'] = new Timber\PostQuery($preheader_arguments);
    $context['sticky_event'] = new Timber\PostQuery($sticky_event_args);
    $context['sticky_listings'] = new Timber\PostQuery($listing_query);
		$context['close_events'] = new Timber\PostQuery( $close_event_args );
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::get_context();';
		$context['menu'] = new TimberMenu();
		$context['site'] = $this;
		$context['sidebar'] = Timber::get_widgets('main_sidebar');
		$context['template'] = $_GET['template'];
    $context['popular'] = $wpp_popular_query->get_posts();
		$context['today'] = $today;
		$context['event_locations'] = get_meta_values( 'city', 'listing', true );
		return $context;
	}

	function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}


	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
		$twig->addFilter(new Twig_SimpleFilter('console_log', function ( $vinp) {
			$vout = ( $vinp );
			return '<script> console.log('.json_encode($vout).')</script>';
		}));
		$twig->addFilter(new Twig_SimpleFilter('timeago', function ($datetime) {

			$time = time() - strtotime($datetime);

			$units = array (
				31536000 => 'year',
				2592000 => 'month',
				604800 => 'week',
				86400 => 'day',
				3600 => 'hour',
				60 => 'minute',
				1 => 'second'
			);

			foreach ($units as $unit => $val) {
				if ($time < $unit) continue;
				$numberOfUnits = floor($time / $unit);
				return ($val == 'second')? 'A few seconds ago' :
						(($numberOfUnits>1) ? $numberOfUnits : 'A')
						.' '.$val.(($numberOfUnits>1) ? 's' : '').' ago';
			}

		}));
		return $twig;
	}

}

add_filter( 'query_vars', 'addnew_query_vars', 10, 1 );
function addnew_query_vars($vars)
{
    $vars[] = 'template';
    $vars[] = 'city';
    $vars[] = 'date';
		$vars[] = 'when';
		$vars[] = 'artist';
    return $vars;
}

require get_template_directory() . '/inc/customizer.php';

if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
function my_jquery_enqueue() {
		wp_deregister_script('jquery');
		// wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js", false, null);
		wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);
		wp_enqueue_script('jquery_sticky_script', get_template_directory_uri() .'/js/sticky.js', array('jquery'), null, true);
		wp_enqueue_script('pinkpanther', get_template_directory_uri() .'/js/pinkpanther.js', array('jquery'), null, true);
}

register_sidebar( array(
	'name' => 'Home left sidebar',
	'id' => 'main_sidebar',
	'before_widget' => '<div>',
	'after_widget' => '</div>',
	'before_title' => '<h2 class="rounded">',
	'after_title' => '</h2>',
) );

new StarterSite();

add_theme_support( 'responsive-embeds' );

add_filter('posts_where', 'jb_filter_acf_meta');
function jb_filter_acf_meta( $where ) {
    $where = str_replace('meta_key = \'artists_$_artist', "meta_key LIKE 'artists_%_artist", $where);
    return $where;
}

// API CUSTOM ENDPOINT
/**
 * Grab all LISTINGS
 * Grab all venues
 * @param array $data Options for the function.
 * @return string|null Post title for the latest,  * or null if none.
 */
function my_awesome_func( WP_REST_Request $request ) {
  $parameters = $request->get_query_params();
  $meta_query = array('relationship' => 'AND');
  $data = $request->get_params();
  // city query
  if ($parameters['city']) {
    $meta_query['city'] = array(
      'key' => 'city',
      'value' => $parameters['city'],
      'compare' => 'LIKE'
    );
  }

  $posts = get_posts( array(
    'post_type' => 'listing',
    'numberposts'	=> -1,
    'meta_query'	=> $meta_query
  ) );

  if ( empty( $posts ) ) {
    return null;
  }

  foreach ($posts as $key => $post) {
    $posts[$key]->acf = get_fields($post->ID);
    $posts[$key]->link = get_permalink($post->ID);
    $posts[$key]->image = get_the_post_thumbnail_url($post->ID);
  }

  return [$parameters, $posts, $data];
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'listing/v1', '/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'my_awesome_func',
  ) );
} );

add_filter('acf/update_value/type=date_time_picker', 'my_update_value_date_time_picker', 10, 3);

function my_update_value_date_time_picker( $value, $post_id, $field ) {

	return strtotime($value);

}

/*
 * Add columns to Listing post list
 */
function add_listing_acf_columns ( $columns ) {
	return array_merge ( $columns, array ( 
		'city' => __ ( 'City' )
	) );
}
add_filter ( 'manage_listing_posts_columns', 'add_listing_acf_columns' );

/*
 * Add columns to listing post list
 */
function listing_custom_column ( $column, $post_id ) {
	switch ( $column ) {
		case 'city':
			echo get_post_meta ( $post_id, 'city', true );
			break;
	}
}
add_action ( 'manage_listing_posts_custom_column', 'listing_custom_column', 10, 2 );