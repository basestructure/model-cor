<?php
/**
 * Template Name: COR Video Page
 * Description: Video template for COR 
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

//* Force sidebar-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar' );

//* Remove post info
add_action ( 'genesis_before_loop', 'themeprefix_remove_post_info' );
function themeprefix_remove_post_info() {
	if ( 'post' !== get_post_type() ) {//add in your CPT name
		remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	}
}

// Main Class
class SWPVideoTemplate {
	
	// DISPLAY VIDEO
	public function swp_display_video_func() {

		?><section id="section-video"><?php

		// YouTube
		$display_video = get_post_meta( get_the_ID(), "youtube_link", TRUE );
		if( $display_video ) {

			?><div id="item-video" style="width:100%"><?php
			echo do_shortcode( "[su_youtube url='".$display_video."' width='600' height='400' responsive='yes' autoplay='no' mute='no' class='']" );
			?></div><?php

		}

		// Vimeo
		$display_video = get_post_meta( get_the_ID(), "vimeo_link", TRUE );
		if( $display_video ) {

			?><div id="item-video" style="width:100%"><?php
			echo do_shortcode( "[su_vimeo url='".$display_video."' width='600' height='400' responsive='yes' autoplay='no' dnt='no' class='']" );
			?></div><?php

		}

		// Video Embed
		// video_embed
		// [su_dailymotion url="" width="600" height="400" responsive="yes" autoplay="no" background="#FFC300" foreground="#F7FFFD" highlight="#171D1B" logo="yes" quality="380" related="yes" info="yes" class=""]

		?></section><?php

	}

	/*
		// native fields
		the_title()
		the_content() // in this template, the_content() is already displayed. see function "swp_display_native_content_func"
		the_post_thumbnail( 'put_size_here' )
		the_tags()
		the_category()
		the_permalink() or get_permalink() // depends on how you want to use it
		the_author()
		get_the_date()

		// custom fields available
		get_post_meta( get_the_ID(), 'cta', TRUE )
		get_post_meta( get_the_ID(), 'alt_title', TRUE )
		get_post_meta( get_the_ID(), 'alt_content', TRUE )
		get_post_meta( get_the_ID(), 'alt_summary', TRUE )
	*/

	// TEMPLATE FOR PREVIOUS AND NEXT - AVOID DUPLICATE CODES
	private function swp_display_navi_temp( $text, $pid, $this_field, $image_size ) {

		// choose how and where you want to pull the media from
		$swp_attachment = wp_get_attachment_image( get_post_meta( $pid, $this_field, TRUE ), $image_size );
		//$swp_attachment = '<img style="width:80px; height:auto;" src="'.wp_get_attachment_url( get_post_meta( get_the_ID(), $this_field, TRUE ) ).'" />';

		// hide text container if $text is empty
		if( $text ) {
			$text_out = '<div id="item-header"><strong>'.$text.'</strong></div>';
		}

		?>
		<div class="module-videobacknext module-videofeaturelist">
		<div class="module-header"><?php echo $text_out; ?></div>
		<div class="module-inner iconinfo">
			<div class="item-icon"><a href="<?php echo get_permalink( $pid ); ?>"><?php echo $swp_attachment; ?></a></div>
			<div class="item-info"><a class="item-textlink" href="<?php echo get_permalink( $pid ); ?>"><?php echo get_the_title( $pid ); ?></a></div>
		</div>
		</div>
		<?php

	}

	// NAVIGATION
	public function swpvideotemplatenavigation_prevnext( $echo_this ) {
		
		$current_post_id = get_the_ID();

		$args = array(
			'post_type' 		=> 'video',
			'post_status'    	=> 'publish',
			'posts_per_page' 	=> -1,
			//'paged' 			=> $paged,
			//'meta_key'			=> 'NULL',
			'orderby'			=> 'date',
			'order'				=> 'DESC',
			//'post__not_in' 		=> array( $current_post_id ),
		);

		$the_query = new WP_Query( $args );
		
		$h = 1; // set counter

		// The Loop
		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) {

				$out_prev = ''; $out_next = '';

				$the_query->the_post();
				
				$this_field = 'icon';
				$this_image_size = 'icon-ratio32'; // 'icon-ratio32'

				// catch current post and display previous post details
				if( $current_post_id == get_the_ID() ) {

					// show Previous pane if NOT the first article
					if( $h > 1 ) {

						// show pane if called
						if( $echo_this == 'previous' || $echo_this == 'all' ) {
							$out_prev = $this->swp_display_navi_temp( 'Previous', $previous_id, $this_field, $this_image_size );
						}

					}

					// mark the NEXT article based on the loop ($h)
					$the_next_post = $h + 1;

				}

				// catch previous entry
				$previous_id = get_the_ID();

				// get next entry | can be found in loop $h + 1
				if( $h == $the_next_post ) {

					// surprisingly this pane doesn't show if the current post is the last one - hooray!

					if( $echo_this == 'next' || $echo_this == 'all' ) {
						$out_next = $this->swp_display_navi_temp( 'Next', get_the_ID(), $this_field, $this_image_size );
					}

					// no need to continue with the loop
					break;
				}
				
				// increment
				$h++;

			}

			//Restore original Post Data
			wp_reset_postdata();
		
		}

		if( $echo_this == 'all' ) {
			return $out_prev.$out_next;
		} else {
			if( $echo_this == 'previous' ) {
				return $out_prev;
			}

			if( $echo_this == 'next' ) {
				return $out_next;
			}
		}
	}

	// DISPLAY NAVIGATION: PREVIOUS
	public function swp_vid_nav_prev() {
		$this->swpvideotemplatenavigation_prevnext( 'previous' );
	}

	// DISPLAY NAVIGATION: NEXT
	public function swp_vid_nav_next() {
		$this->swpvideotemplatenavigation_prevnext( 'next' );
	}

	// DISPLAY NAVIGATION: PREVIOUS & NEXT (all)
	public function swp_vid_nav_all() {
		$this->swpvideotemplatenavigation_prevnext( 'all' );
	}

	// TEMPLATE FOR LIST - AVOID DUPLICATE CODES
	private function swp_list_navi_temp( $text, $pid, $this_field, $image_size ) {

		// choose how and where you want to pull the media from
		$swp_attachment = wp_get_attachment_image( get_post_meta( $pid, $this_field, TRUE ), $image_size );
		//$swp_attachment = '<img style="width:80px; height:auto;" src="'.wp_get_attachment_url( get_post_meta( get_the_ID(), $this_field, TRUE ) ).'" />';

		// hide text container if $text is empty
		if( $text ) {
			$text_out = '<div id="item-header"><strong>'.$text.'</strong></div>';
		}

		?>
		<div class="module-videofeaturelist">
		<div class="module-header"><?php echo $text_out; ?></div>
		<div class="module-inner iconinfo">
			<div class="item-icon"><a href="<?php echo get_permalink( $pid ); ?>"><?php echo $swp_attachment; ?></a></div>
			<div class="item-info"><a class="item-textlink" href="<?php echo get_permalink( $pid ); ?>"><?php echo get_the_title( $pid ); ?></a></div>
		</div>
		</div>
		<?php

	}

	// DISPLAY NAVIGATION: LIST
	public function swpvideotemplatenavigation_list() {

		$args = array(
			'post_type' 		=> 'video',
			'post_status'    	=> 'publish',
			'posts_per_page' 	=> 10,
			//'paged' 			=> $paged,
			//'meta_key'			=> 'NULL',
			'orderby'			=> 'date',
			'order'				=> 'DESC',
			'post__not_in' 		=> array( get_the_ID() ),
		);

		$the_query = new WP_Query( $args );

		// The Loop
		if ( $the_query->have_posts() ) {
            
			while ( $the_query->have_posts() ) {

				$the_query->the_post();
				
				$this_field = 'icon';
				$this_image_size = 'icon-ratio32';

				$this->swp_list_navi_temp( '', get_the_ID(), $this_field, $this_image_size );

			}

			//Restore original Post Data
			wp_reset_postdata();
		
		}

	}

	// DISPLAY NATIVE WP CONTENT AREA
	public function swp_display_native_content_func() {

		//echo '<div>'.do_shortcode( the_content() ).'</div>';
		//echo '<div class="fontsize-med">Title: '.do_shortcode( get_the_title( get_the_ID() ) ).'</div>';
		//echo '<div class="fontsize-med">Author: '.do_shortcode( get_the_author() ).'</div>';
		//echo '<div class="fontsize-med">Category: '.do_shortcode( get_the_category_list( ',', '', get_the_ID() ) ).'</div>';

	}

	// DISPLAY VIDEO SPECS
	public function swp_display_video_specs() {
		echo '<div class="fontsize-xxxsml">Author: '.do_shortcode( get_the_author() ).'</div>';
		echo '<div class="fontsize-xsml space-top">'.do_shortcode( get_the_excerpt() ).'</div>';
	}

	// CONSTRUCT
	public function __construct() {

		if( !is_admin() ) {

			// DISPLAY VIDEO
			add_action( 'genesis_entry_header', array( $this, 'swp_display_video_func' ), 8 );

			// DISPLAY SPECS
			add_action( 'genesis_entry_content', array( $this, 'swp_display_video_specs' ) );

			// DISPLAY WP CONTENT
			//add_action( 'genesis_entry_content', array( $this, 'swp_display_native_content_func' ) );

			// DISPLAY PREVIOUS
			//add_action( 'genesis_before_sidebar_widget_area', array( $this, 'swp_vid_nav_prev' ) );

			// DISPLAY NEXT
			add_action( 'genesis_before_sidebar_widget_area', array( $this, 'swp_vid_nav_next' ) );

			// DISPLAY ALL
			//add_action( 'genesis_entry_content', array( $this, 'swp_vid_nav_all' ) );

			// DISPLAY LIST
			add_action( 'genesis_after_sidebar_widget_area', array( $this, 'swpvideotemplatenavigation_list' ) );

		}

	}

}

$q = new SWPVideoTemplate();

genesis();