<?php
/**
 * Create the Members post type
 *
 * @package Metaphor Members
 */
 
 
 
 
add_action( 'init','mtphr_members_posttype' );
/**
 * Add the members post type
 *
 * @since 1.0.0
 */
function mtphr_members_posttype() {
	
	// Set the slug
	$settings = mtphr_members_settings('mtphr_members_settings');
	$slug = $settings['slug'];
	$singular = $settings['singular_label'];
	$plural = $settings['plural_label'];
	
	// Set the labels
	$labels = array(
		'name' => sprintf( __( '%1$s', 'mtphr-members' ), $plural ),
		'singular_name' => sprintf( __( '%1$s', 'mtphr-members' ), $singular ),
		'add_new' => __( 'Add New', 'mtphr-members' ),
		'add_new_item' => sprintf( __( 'Add New %1$s', 'mtphr-members' ), $singular ),
		'edit_item' => sprintf( __( 'Edit %1$s', 'mtphr-members' ), $singular ),
		'new_item' => sprintf( __( 'New %1$s', 'mtphr-members' ), $singular ),
		'view_item' => sprintf( __( 'View %1$s', 'mtphr-members' ), $singular ),
		'search_items' => sprintf( __( 'Search %1$s', 'mtphr-members' ), $plural ),
		'not_found' => sprintf( __( 'No %1$s Found', 'mtphr-members' ), $plural ),
		'not_found_in_trash' => sprintf( __( 'No %1$s Found in Trash', 'mtphr-members' ), $plural ), 
		'parent_item_colon' => '',
		'menu_name' => sprintf( __( '%1$s', 'mtphr-members' ), $plural )
	);
	
	// Create the arguments
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'show_in_nav_menus' => true,
		'rewrite' => array( 'slug' => $slug ),
		'has_archive' => true
	);
	
	// Register post type
	register_post_type( 'mtphr_member', $args );	
}