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

// Remove the standard pagination, so we don't get two sets
remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );

//remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
//remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

// Include PHP file for AJAX
include_once( 'singles-video-ajax.php' );

// Main Class
class SWPVideoTemplate {
	
	// DISPLAY VIDEO
	public function swp_display_video_func() {

		?><section id="section-video"><?php

		// YouTube
		$display_video = get_post_meta( get_the_ID(), "youtube_link", TRUE );
		if( $display_video ) {

			?><div id="item-video" style="width:600px"><?php
			echo do_shortcode( "[su_youtube url='".$display_video."' width='600' height='400' responsive='yes' autoplay='no' mute='no' class='']" );
			?></div><?php

		}

		// Vimeo
		$display_video = get_post_meta( get_the_ID(), "vimeo_link", TRUE );
		if( $display_video ) {

			?><div id="item-video" style="width:600px"><?php
			echo do_shortcode( "[su_vimeo url='".$display_video."' width='600' height='400' responsive='yes' autoplay='no' dnt='no' class='']" );
			?></div><?php

		}

		// Video Embed
		// video_embed
		// [su_dailymotion url="" width="600" height="400" responsive="yes" autoplay="no" background="#FFC300" foreground="#F7FFFD" highlight="#171D1B" logo="yes" quality="380" related="yes" info="yes" class=""]

	}

	// DISPLAY LIST-ICONINFO
	public function swp_display_listiconinfo_func() {
		
		// call video template file for navigator
		$swp_navi = new SWPVideoTemplateAJAX();
		?><ul id="list-iconinfo" class="list-iconinfo"><?php echo $swp_navi->swpajaxtemplate( get_the_ID() ); ?></ul><?php

		?></section><?php

	}

	// DISPLAY NATIVE WP CONTENT AREA
	public function swp_display_native_content_func() {

		echo '<div>'.do_shortcode( the_content() ).'</div>';

	}

	// DISPLAY PREVIOUS & NEXT ARTICLES
	/*public function swp_display_post_navs_func() {

		// PREVIOUS
		$prev_post = get_previous_post();
		echo "Previous: <a href='".get_permalink( $prev_post->ID )."'>".get_the_title( $prev_post->ID )."</a>";
		echo "<br />";

		// NEXT
		$next_post = get_next_post();
		echo "Next: <a href='".get_permalink( $next_post->ID )."'>".get_the_title( $next_post->ID )."</a>";

	}*/

	// CONSTRUCT
	public function __construct() {

		if( !is_admin() ) {

			// DISPLAY VIDEO
			add_action( 'genesis_before_content_sidebar_wrap', array( $this, 'swp_display_video_func' ) );

			// DISPLAY LIST-ICONINFO
			add_action( 'genesis_before_sidebar_widget_area', array( $this, 'swp_display_listiconinfo_func' ) );

			// DISPLAY VIDEO
			add_action( 'genesis_entry_content', array( $this, 'swp_display_native_content_func' ) );

			// DISPLAY PREVIOUS & NEXT POST ENTRY
			//add_action( 'genesis_after_entry', array( $this, 'swp_display_post_navs_func' ) );

		}

	}
}

$q = new SWPVideoTemplate();

genesis();