<?php
/**
 * The global settings
 *
 * @package Metaphor Members
 */




add_action( 'admin_menu', 'mtphr_members_settings_page' );
/**
 * Add a menu page to display options
 *
 * @since 1.0.0
 */
function mtphr_members_settings_page() {

	add_submenu_page(
		'edit.php?post_type=mtphr_member',			// The ID of the top-level menu page to which this submenu item belongs
		'Settings',																		// The value used to populate the browser's title bar when the menu page is active
		'Settings',																		// The label of this submenu item displayed in the menu
		'administrator',															// What roles are able to access this submenu item
		'mtphr_members_settings_menu',						// The ID used to represent this submenu item
		'mtphr_members_settings_display'					// The callback function used to render the options for this submenu item
	);
}




add_action( 'admin_init', 'mtphr_members_initialize_settings' );
/**
 * Initializes the options page.
 *
 * @since 1.0.0
 */ 
function mtphr_members_initialize_settings() {
	
	$settings['slug'] = array(
		'title' => __( 'Slug', 'mtphr-members' ),
		'type' => 'text',
		'default' => __( 'members', 'mtphr-members' ),
		'size' => 10,
		'description' => __('Set the slug for the member post type and category.<br/><strong>* You must update permalinks after changing the slug.</strong><br/><strong>* You must not have a page slug with the same name as this slug.</strong>', 'mtphr-members')
	);
	
	$settings['singular_label'] = array(
		'title' => __( 'Singular Label', 'mtphr-members' ),
		'type' => 'text',
		'default' => __( 'Member', 'mtphr-members' ),
		'size' => 20,
		'description' => __('Set the singular label for the member post type and category.', 'mtphr-members')
	);
	
	$settings['plural_label'] = array(
		'title' => __( 'Plural Label', 'mtphr-members' ),
		'type' => 'text',
		'default' => __( 'Members', 'mtphr-members' ),
		'size' => 20,
		'description' => __('Set the plural label for the member post type.', 'mtphr-members')
	);

	if( false == get_option('mtphr_members_settings') ) {	
		add_option( 'mtphr_members_settings' );
	}
	
	/* Register the style options */
	add_settings_section(
		'mtphr_members_settings_section',					// ID used to identify this section and with which to register options
		'',																								// Title to be displayed on the administration page
		'mtphr_members_settings_callback',					// Callback used to render the description of the section
		'mtphr_members_settings'										// Page on which to add this section of options
	);
	
	$settings = apply_filters( 'mtphr_members_settings', $settings );

	if( is_array($settings) ) {
		foreach( $settings as $id => $setting ) {	
			$setting['option'] = 'mtphr_members_settings';
			$setting['option_id'] = $id;
			$setting['id'] = 'mtphr_members_settings['.$id.']';
			add_settings_field( $setting['id'], $setting['title'], 'mtphr_members_field_display', 'mtphr_members_settings', 'mtphr_members_settings_section', $setting);
		}
	}
	
	// Register the fields with WordPress
	register_setting( 'mtphr_members_settings', 'mtphr_members_settings' );
}




/**
 * Renders a simple page to display for the theme menu defined above.
 *
 * @since 1.0.0
 */
function mtphr_members_settings_display() {
	?>
	<div class="wrap">
	
		<div id="icon-mtphr_members" class="icon32"></div>
		<h2><?php _e( 'Member Settings', 'mtphr-members' ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
			settings_fields( 'mtphr_members_settings' );
			do_settings_sections( 'mtphr_members_settings' );
			submit_button();
			?>
		</form>

	</div><!-- /.wrap -->
	<?php
}




/**
 * The callback function for the settings sections.
 *
 * @since 1.0.0
 */ 
function mtphr_members_settings_callback() {
	_e( '<h4>Default settings for the Metaphor Members post type.</h4>', 'mtphr-members' );
}




/**
 * The custom field callback.
 *
 * @since 1.0.0
 */ 
function mtphr_members_field_display( $args ) {

	// First, we read the options collection
	if( isset($args['option']) ) {
		$options = get_option( $args['option'] );
		$value = isset( $options[$args['option_id']] ) ? $options[$args['option_id']] : '';
	} else {
		$value = get_option( $args['id'] );
	}	
	if( $value == '' && isset($args['default']) ) {
		$value = $args['default'];
	}
	if( isset($args['type']) ) {
	
		echo '<div class="mtphr-members-metaboxer-field mtphr-members-metaboxer-'.$args['type'].'">';
		
		// Call the function to display the field
		if ( function_exists('mtphr_members_metaboxer_'.$args['type']) ) {
			call_user_func( 'mtphr_members_metaboxer_'.$args['type'], $args, $value );
		}
		
		echo '<div>';
	}
	
	// Add a descriptions
	if( isset($args['description']) ) {
		echo '<span class="description"><small>'.$args['description'].'</small></span>';
	}
}

