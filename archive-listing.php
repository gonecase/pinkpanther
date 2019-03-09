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
$when = get_query_var( 'when', false );
$artist = get_query_var( 'when', false );
$date = strtotime('today midnight');
$end_date = strtotime('+1 year', $date);
$date_compare = '>=';
// $date_compare = '<=';
if ($when == 'Today') {
  $end_date = strtotime('+1 day');
}
if ($when == 'Tomorrow') {
  $date = strtotime('tomorrow midnight');
  $end_date = strtotime('+2 day');
  // echo "<h1>FFFFFFF".$date.$end_date."</h1>";
}
if ($when == 'Week') {
  $end_date = strtotime('+1 week');
}
if ($when == 'Month') {
  $end_date = strtotime('+1 month');
}
if ($when == 'All') {
  // $date = false;
}
$meta_query = array(
  'relationship' => 'AND',
  'end_date' => array(
    'key' => 'date',
    // 'value' =>  $date.' 00:00:00',
    'value' => $end_date,
    // 'type'		=> 'DATETIME',
    'compare' => "<="
  )
);
$listings = $posts;
$context['listing_title'] = "Stand-up Comedy Listings";

if ($city || $date) {

}

if ($city) {
  $meta_query['city'] = array(
    'key' => 'city',
    'value' => $city,
    'compare' => 'LIKE'
  );
}

if ($artist) {
  $meta_query['city'] = array(
    'key' => 'city',
    'value' => $city,
    'compare' => 'LIKE'
  );
}

if ($date) {
  $meta_query['startdate'] = array(
    'key' => 'date',
    // 'value' =>  $date.' 00:00:00',
    'value' => $date,
    // 'type'		=> 'DATETIME',
    'compare' => $date_compare
  );
}

if($date || $city) {
  $listings = Timber::get_posts(array(
    'post_type' => 'listing',
    'numberposts'	=> -1,
    'meta_key' => 'date',
    'orderby'	=> 'meta_value_num',
    'order' => 'ASC',
    'meta_query'	=> $meta_query
  ));
  $context['listing_title'] = $context['listing_title'] . " in " . $city;
}

$context['city_query'] = get_query_var( 'city', false );
$context['date_query'] = get_query_var( 'when', false );
$context['posts'] = $listings;

?>
<script>
  console.log(<?php echo json_encode($date); ?>);
  console.log(<?php echo json_encode($listings); ?>);
  console.log(<?php echo json_encode($all_listings); ?>);
  console.log(<?php echo json_encode($meta_query); ?>);
</script>
<?php
// echo "<hr>";
// echo date('d/m/Y g:i a', $date);
// echo "<hr>";
// echo $date;
// echo "<hr>";
// print_r($listings);

Timber::render( $templates, $context );
