<?php
/**
 * Create the meta boxes
 *
 * @package Metaphor Members
 */




add_action( 'admin_init', 'mtphr_members_metaboxes', 20 );
/**
 * Add custom fields
 *
 * @since 1.0.0
 */
function mtphr_members_metaboxes() {
	
	// Create the link structure	
	$link_structure = array(
		'site' => array(
			'header' => __('Website', 'mtphr-members'),
			'width' => '10%',
			'type' => 'select',
			'options' => mtphr_members_social_sites_array()
		),
		'link' => array(
			'header' => __('Link', 'mtphr-members'),
			'type' => 'text'
		)
	);
	
	// Create the contact structure	
	$contact_structure = array(
		'title' => array(
			'header' => __('Title', 'mtphr-members'),
			'width' => '30%',
			'type' => 'text'
		),
		'description' => array(
			'header' => __('Description', 'mtphr-members'),
			'type' => 'textarea',
			'rows' => 1
		)
	);
	
	$member_info = array(
		'id' => '_mtphr_members_info',
		'title' => __( 'Member Info', 'mtphr-members' ),
		'page' => array( 'mtphr_member' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'id' => '_mtphr_members_title',
				'type' => 'text',
				'name' => __( 'Member Title', 'mtphr-members' ),
				'description' => __( 'Add a job title.', 'mtphr-members' )
			),
			array(
				'id' => '_mtphr_members_contact_info',
				'type' => 'list',
				'name' => __( 'Contact Info', 'mtphr-members' ),
				'structure' =>  $contact_structure,
				'description' => __( 'Add contact info.', 'mtphr-members' ),
				'default' => array(
					array(
						'title' => __('Email', 'mtphr-members'),
						'description' => '',
					),
					array(
						'title' => __('Tel', 'mtphr-members'),
						'description' => '',
					),
					array(
						'title' => __('Fax', 'mtphr-members'),
						'description' => '',
					)
				)
			),
			array(
				'id' => '_mtphr_members_social',
				'type' => 'list',
				'name' => __( 'Social Links', 'mtphr-members' ),
				'structure' =>  $link_structure,
				'description' => __( 'Add links to social sites.', 'mtphr-members' )
			),
			array(
				'id' => '_mtphr_members_social_new_tab',
				'type' => 'checkbox',
				'name' => __( 'Social Links Target', 'mtphr-members' ),
				'label' => __('Open links in a new window/tab', 'mtphr-members'),
				'description' => __( 'Select to open links in a new window/tab.', 'mtphr-members' )
			),
			array(
				'id' => '_mtphr_members_twitter',
				'type' => 'text',
				'name' => __( 'Twitter Handle', 'mtphr-members' ),
				'description' => __( 'Add a twitter handle.', 'mtphr-members' )
			),
		)
	);
	new MTPHR_MEMBERS_MetaBoxer( $member_info );
	
	
	
	if( is_plugin_active('mtphr-widgets/mtphr-widgets.php') ) {
	
		$sidebars = get_option('sidebars_widgets');
		$contact_widgets = array();
		$social_widgets = array();
		$twitter_widgets = array();
		foreach( $sidebars as $sidebar ) {
			if( is_array($sidebar) ) {
				foreach( $sidebar as $widget ) {
					if( strstr($widget,'mtphr-contact') ) {
						$contact_widgets[$widget] = $widget;
					}
					if( strstr($widget,'mtphr-social') ) {
						$social_widgets[$widget] = $widget;
					}
					if( strstr($widget,'mtphr-twitter') ) {
						$twitter_widgets[$widget] = $widget;
					}
				}	
			}
		}
		
		$fields = array(
			array(
				'id' => '_mtphr_members_contact_override',
				'type' => 'checkbox',
				'name' => __( 'Contact Widget Override', 'mtphr-members' ),
				'options' => $contact_widgets,
				'description' => __('Override the following contact widgets if they are active.', 'mtphr-members')
			),
			array(
				'id' => '_mtphr_members_social_override',
				'type' => 'checkbox',
				'name' => __( 'Social Widget Override', 'mtphr-members' ),
				'options' => $social_widgets,
				'description' => __('Override the following social widgets if they are active.', 'mtphr-members')
			),
			array(
				'id' => '_mtphr_members_twitter_override',
				'type' => 'checkbox',
				'name' => __( 'Twitter Widget Override', 'mtphr-members' ),
				'options' => $twitter_widgets,
				'description' => __('Override the following twitter widgets if they are active.', 'mtphr-members')
			)
		);
		
	} else {
		$fields = array(
			array(
				'id' => '_mtphr_members_contact_override_alert',
				'type' => 'html',
				'default' => '<p><strong>'.__('Install and activate <a href="http://wordpress.org/extend/plugins/mtphr-widgets/" target="_blank">Metaphor Widgets</a> to enhance your Metaphor Members sidebars!', 'mtphr-members').'</strong><br/>'.__('You\'ll be able to add the following widgets to a single sidebar and easily override them with each member\'s custom settings.', 'mtphr-members').'</p><ul style="list-style:disc; margin-left:30px;"><li>'.__('Metaphor Contact Widget', 'mtphr-members').'</li><li>'.__('Metaphor Social Widget', 'mtphr-members').'</li><li>'.__('Metaphor Twitter Widget', 'mtphr-members').'</li></ul>'
			)
		);
	}
	
	$member_overrides = array(
		'id' => '_mtphr_members_widget_overrides',
		'title' => __( 'Metaphor Widgets Overrides', 'mtphr-members' ),
		'page' => array( 'mtphr_member' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => $fields
	);
	new MTPHR_MEMBERS_MetaBoxer( $member_overrides );
}



