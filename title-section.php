<?php
/**
 * This file creates a new header section inside the site-header
 * then repositions page/entry titles into the new area
 *
 * @package      Genesis Starter
 * @link         https://seothemes.net/genesis-starter
 * @author       Seo Themes
 * @copyright    Copyright © 2017 Seo Themes
 * @license      GPL-2.0+
 */

function starter_setup_title_section() {

	// Remove existing title related functions
	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	remove_action(  'genesis_before_loop', 'genesis_do_posts_page_heading' );
	remove_action(  'genesis_before_loop', 'genesis_do_date_archive_title' );
	remove_action(  'genesis_before_loop', 'genesis_do_blog_template_heading' );
	remove_action(  'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
	remove_action(  'genesis_before_loop', 'genesis_do_author_title_description', 15 );
	remove_action(  'genesis_before_loop', 'genesis_do_cpt_archive_title_description' );

	// Enable excerpts on pages
	add_post_type_support( 'page', 'excerpt' );

	// Wrap header content in a new container to 
	add_action( 'genesis_header', function() { 
		echo '<div class="header-wrap"><div class="wrap">'; 
	}, 6 );
	add_action( 'genesis_header', function() { 
		echo '</div></div>'; 
	}, 13 );
}
starter_setup_title_section();

/**
* Create our main title section function
*
* This function is made up of four parts
* - Start
* - Title
* - Description
* - End 
* 
*/
function starter_title_section() {

	// Check if is front page and not blog
	if ( is_front_page() && !is_home() ) {
		return;
	}
	
	/**
	 * Start title section.
	 *
	 * Checks if post has a featured image set and if so
	 * uses it as a background image for the title section
	 */
	function before_title_section() {
		// Define variables
	    global $post;
	    $thumbnail = get_post_thumbnail_id( $post->ID );
	    $size = 'thumb';
	    $html = '';

	    // Add background image if a featured image is set
	    if( $thumbnail ) {
	    	$class = 'has-thumbnail';
	        $image = wp_get_attachment_image_src( $thumbnail, $size );
	        $html  = 'style="background-image: url(' . $image[0] . ') !important;"';
	    }
	    
	    // Output the html
	    echo '<div class="title-section"' . $html . '><div class="wrap">';
	}
	before_title_section();

	/**
	 * Title section.
	 *
	 * Displays the relevant entry/archive title
	 * depending on the post type
	 */
	function starter_title_section_content() {
		
		//Figure out what post type were on and display the correct title
		if ( is_front_page() ) {
			echo '<h1 class="entry-title" itemprop="headline">Home</h1>';
			add_action( 'genesis_entry_header', 'genesis_do_post_title', 2 );
		}

		elseif ( is_404() ) {
			echo '<h1 class="entry-title" itemprop="headline">Page not found!</h1>';
		}
		
		elseif( is_page_template('page_blog.php') ) {
			genesis_do_blog_template_heading();
			add_action( 'genesis_entry_header', 'genesis_do_post_title', 2 );
		}

		elseif( is_home() || is_archive() ) {
			genesis_do_posts_page_heading();
			add_action( 'genesis_entry_header', 'genesis_do_post_title', 2 );
		}

		elseif( is_date() ) {
			genesis_do_date_archive_title();
		}

		else {
			genesis_do_post_title();
		}


	}
	starter_title_section_content();

	/**
	 * Description/breadcrumbs section.
	 *
	 * Displays the description if one is set or will use
	 * page excerpts or breadcrumbs if no excerpt is set 
	 */
	function starter_title_section_after() {

		if( is_tax() ) {
			genesis_do_taxonomy_title_description();
		}

		elseif( is_author() ) {
			genesis_do_author_title_description();
		}

		elseif( is_post_type_archive() ) {
			genesis_do_cpt_archive_title_description();
		}

		elseif( has_excerpt() ) {
			the_excerpt();
		}

		else {
			genesis_do_breadcrumbs();
		}
	}
	starter_title_section_after();
		
	/**
	 * End title section.
	 *
	 * Adds the closing markup to the title section
	 */
	echo '</div></div>';

}

add_action( 'genesis_header', 'starter_title_section', 14 );