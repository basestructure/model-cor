<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Include WPQuery file
//include_once( 'swp_wp_query.php' );

// Main Class
class SWPVideoTemplateAJAX {

	public function swpajaxtemplate( $current_post_id ) {

		$args = array(
			'post_type' 		=> 'video',
			'post_status'    	=> 'publish',
			'posts_per_page' 	=> 5,
			//'paged' 			=> $paged,
			//'meta_key'			=> 'NULL',
			'orderby'			=> 'date',
			'order'				=> 'DESC',
			'post__not_in' 		=> array( $current_post_id ),
		);

		$the_query = new WP_Query( $args );
		
		//$query = new SWPWPQueryPosts();
		// swp_query_archive_posts( $post_type, $num_of_posts, $tax_name, $tax_term, $paged, $orderbymeta, $orderby, $order )
		//$the_query = $query->swp_query_archive_posts( , '3', NULL, NULL, NULL, NULL, 'date', 'DESC' );
		
		// The Loop
		if ( $the_query->have_posts() ) {
            
			while ( $the_query->have_posts() ) {

				$the_query->the_post();
				
				// choose how and where you want to pull the media from
				$this_field = 'icon';
				$swp_attachment = wp_get_attachment_image( get_post_meta( get_the_ID(), $this_field, TRUE ), 'icon-ratio32' );
				//$swp_attachment = '<img style="width:80px; height:auto;" src="'.wp_get_attachment_url( get_post_meta( get_the_ID(), $this_field, TRUE ) ).'" />';

				// custom fields available
				/*
					get_post_meta( get_the_ID(), 'cta', TRUE )
					get_post_meta( get_the_ID(), 'alt_title', TRUE )
					get_post_meta( get_the_ID(), 'alt_content', TRUE )
					get_post_meta( get_the_ID(), 'alt_summary', TRUE )
				*/

				?>
				<li class="list-entry">
					<div class="item-icon">
						<a href="<?php echo get_permalink( get_the_ID() ); ?>">
							<?php echo $swp_attachment; ?>
						</a>
					</div>
					<div class="item-info">
						<a href="<?php echo get_permalink( get_the_ID() ); ?>">
							<?php echo get_the_title(); ?>
						</a>
					</div>
				</ul>
				<?php
				
			}

			//Restore original Post Data
			wp_reset_postdata();
		
		}

	}

	// CONSTRUCT
	/*public function __construct() {
		echo 'jakers';
	}*/

}



			/* PAGINATION
			 * ---------------------------------------------------------------------------- */
				/* With previous and next pages
				 * -------------- */
				//previous_posts_link(); next_posts_link();

				/* Without previous and next pages
				 * -------------- */
				//the_posts_pagination( array( 'mid_size'  => 2 ) );

				/* Pagination with Alternative Prev/Next Text
				 * -------------- */
				/*echo get_the_posts_pagination( array(
				    'mid_size' => 2,
				    'prev_text' => __( '<<', 'textdomain' ),
				    'next_text' => __( '>>', 'textdomain' ),
				) );*/
			/* PAGINATION END
			 * ---------------------------------------------------------------------------- */