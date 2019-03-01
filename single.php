<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */
$today = date('Ymd');
$artist_events = array(
	'post_type'           => 'events',
	'posts_per_page'      => 10,
	'meta_query' => array(
    array(
        'key' => 'date',
        'value' => $today,
        'compare' => '<'
    )
	)
);

$artist_articles = array(
	'post_type'           => 'events',
	'posts_per_page'      => 10,
	'meta_query' => array(
    array(
        'key' => 'date',
        'value' => $today,
        'compare' => '<'
    )
	)
);

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;
$context['is_single'] = true;
$context['is_home'] = false;
$context['artist_events'] = new Timber\PostQuery( $artist_events );
$context['artist_articles'] = new Timber\PostQuery( $artist_articles );
// $context['author_bio'] = get_the_author_meta( 'user_description', $post->author );
$context['author_bio'] = get_the_author_meta( 'description' );


if ( post_password_required( $post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $post->ID . '.twig', 'single-' . $post->post_type . '.twig', 'single.twig' ), $context );
}
