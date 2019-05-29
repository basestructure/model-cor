<?php

add_filter( 'body_class', 'genesis_sample_landing_body_class' );
/**
 * Adds podcast page body class.
 *
 * @since 1.0.0
 *
 * @param array $classes Original body classes.
 * @return array Modified body classes.
 */
function genesis_sample_landing_body_class( $classes ) {

	$classes[] = 'podcast-page';
	return $classes;

}

// Removes Skip Links.
remove_action( 'genesis_before_header', 'genesis_skip_links', 5 );

add_action( 'wp_enqueue_scripts', 'genesis_sample_dequeue_skip_links' );
/**
 * Dequeues Skip Links Script.
 *
 * @since 1.0.0
 */
function genesis_sample_dequeue_skip_links() {

	wp_dequeue_script( 'skip-links' );

}

//* Force sidebar-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content' );

//* Remove the entry title (requires HTML5 theme support)
//remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

//* Remove the entry meta in the entry header (requires HTML5 theme support)
//remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

//* Add adjacent Prev & Next paginations
add_action( 'genesis_after_content', 'genesis_prev_next_post_nav' );

//* Add previous and next pages
add_action( 'genesis_entry_footer', 'wpb_prev_next_post_nav_cpt' );
function wpb_prev_next_post_nav_cpt() {
	if ( ! is_singular( 'podcast' ) ) //add your CPT name
		return;
	genesis_markup( array(
		'html5'   => '<div %s>',
		'xhtml'   => '<div class="navigation">',
		'context' => 'adjacent-entry-pagination',
	) );
	echo '<div class="pagination-next">';
	echo '<span>NEXT | </span>';
	next_post_link();
	echo '</div>';
	echo '<div class="pagination-previous">';
	echo '<span>PREVIOUS | </span>';
	previous_post_link();
	echo '</div>';
}

// Runs the Genesis loop.
genesis();
