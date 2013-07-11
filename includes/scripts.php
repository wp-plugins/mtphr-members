<?php
/**
 * Load CSS & jQuery Scripts
 *
 * @package Metaphor Members
 */




add_action( 'admin_enqueue_scripts', 'mtphr_members_admin_scripts' );
/**
 * Load the admin scripts
 *
 * @since 1.0.0
 */
function mtphr_members_admin_scripts( $hook ) {

	global $typenow;
	if ( $typenow == 'mtphr_member' && in_array($hook, array('post-new.php', 'post.php', 'mtphr_member_page_mtphr_members_settings_menu')) ) {

		// Load the style sheet
		wp_register_style( 'mtphr-members-metaboxer', MTPHR_MEMBERS_URL.'/includes/metaboxer/metaboxer.css', false, MTPHR_MEMBERS_VERSION );
		wp_enqueue_style( 'mtphr-members-metaboxer' );

		// Load scipts for the media uploader
		if(function_exists( 'wp_enqueue_media' )){
	    wp_enqueue_media();
		} else {
	    wp_enqueue_style('thickbox');
	    wp_enqueue_script('media-upload');
	    wp_enqueue_script('thickbox');
		}

		// Load the jQuery
		wp_register_script( 'mtphr-members-metaboxer', MTPHR_MEMBERS_URL.'/includes/metaboxer/metaboxer.js', array('jquery'), MTPHR_MEMBERS_VERSION, true );
		wp_enqueue_script( 'mtphr-members-metaboxer' );

	}

	// Load the style sheet
	wp_register_style( 'mtphr-members-admin', MTPHR_MEMBERS_URL.'/assets/css/style-admin.css', false, MTPHR_MEMBERS_VERSION );
	wp_enqueue_style( 'mtphr-members-admin' );
}




add_action( 'wp_enqueue_scripts', 'mtphr_members_scripts' );
/**
 * Load the front end scripts
 *
 * @since 1.0.5
 */
function mtphr_members_scripts() {

	// Load the social stylesheet
	wp_register_style( 'socialfont', MTPHR_MEMBERS_URL.'/assets/css/socialfont.css', false, MTPHR_MEMBERS_VERSION );
  wp_enqueue_style( 'socialfont' );

	// Load the style sheet
	wp_register_style( 'mtphr-members', MTPHR_MEMBERS_URL.'/assets/css/style.css', false, MTPHR_MEMBERS_VERSION );
	wp_enqueue_style( 'mtphr-members' );

	wp_register_script( 'respond', MTPHR_MEMBERS_URL.'/assets/js/respond.min.js', array('jquery'), MTPHR_MEMBERS_VERSION, true );
	wp_enqueue_script( 'respond' );
}



