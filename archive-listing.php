<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */

$templates = array( 'archive.twig', 'index.twig' );

$context = Timber::get_context();

$context['title'] = 'Archive';
if ( is_day() ) {
	$context['title'] = 'Archive: '.get_the_date( 'D M Y' );
} else if ( is_month() ) {
	$context['title'] = 'Archive: '.get_the_date( 'M Y' );
} else if ( is_year() ) {
	$context['title'] = 'Archive: '.get_the_date( 'Y' );
} else if ( is_tag() ) {
	$context['title'] = single_tag_title( '', false );
} else if ( is_category() ) {
	$context['title'] = single_cat_title( '', false );
	array_unshift( $templates, 'archive-' . get_query_var( 'cat' ) . '.twig' );
} else if ( is_post_type_archive() ) {
	$context['title'] = post_type_archive_title( '', false );
	array_unshift( $templates, 'archive-' . get_post_type() . '.twig' );
}
$context['posts'] = new Timber\PostQuery();
$city = get_query_var( 'city', false );
$date = strtotime(get_query_var( 'date', false ));
$meta_query = array('relationship' => 'AND');
$listings = $posts;
$context['listing_title'] = "Listings";

if ($city || $date) {

}

if ($city) {
  $meta_query['city'] = array(
    'key' => 'city',
    'value' => $city,
    'compare' => 'LIKE'
  );
}

if ($date) {
  $meta_query['date'] = array(
    'key' => 'date',
    'value' =>  date('d/m/Y g:i a', $date),
    // 'value' => $date,
    'type'		=> 'DATETIME',
    'compare' => '<='
  );
}

if($date || $city) {
  $listings = Timber::get_posts(array(
    'post_type' => 'listing',
    'numberposts'	=> -1,
    'order' => 'ASC',
    'orderby' => 'meta_value_num',
    'meta_key' => 'date',
    'meta_query'	=> $meta_query
  ));
  $context['listing_title'] = $context['listing_title'] . " in " . $city;
}

$context['posts'] = $listings;

// echo "<hr>";
// echo date('d/m/Y g:i a', $date);
// echo "<hr>";
// echo $date;
// echo "<hr>";
// print_r($listings);

Timber::render( $templates, $context );
