<?php
/**
 * Custom edit columns
 *
 * @package Metaphor Members
 */




add_filter( 'manage_edit-mtphr_members_columns', 'mtphr_members_set_columns' );
/**
 * Set custom edit screen columns
 *
 * @since 1.0.0
 */
function mtphr_members_set_columns( $columns ){

	$new_columns = array();
	$i = 0;
	foreach( $columns as $key => $value ) {
		if( $i == 2 ) {
			$new_columns['member_title'] = __( 'Job Title', 'mtphr-members' );
		}
		$new_columns[$key] = $value;
		$i++;
	}
	return $new_columns;
}




add_action( 'manage_mtphr_members_posts_custom_column',  'mtphr_members_display_columns', 10, 2 );
/**
 * Display the custom edit screen columns
 *
 * @since 1.0.0
 */
function mtphr_members_display_columns( $column, $post_id ){
	
	$title = get_the_title( $post_id );
	
	switch ( $column ) {
			
		case 'member_title':
			echo sanitize_text_field( get_post_meta(get_the_ID(), '_mtphr_members_title', true) );
			break;
	}
}
