<?php
/**
 * General functions
 *
 * @package Metaphor Members
 */




/**
 * Add the thumbnail support
 *
 * @since 1.0.0
 */
add_theme_support( 'post-thumbnails', array('mtphr_member') );




/**
 * Add WooSidebars support
 *
 * @since 1.0.0
 */
add_post_type_support( 'mtphr_member', 'woosidebars' );




/**
 * Return a value from the options table if it exists,
 * or return a default value
 *
 * @since 1.0.0
 */
function mtphr_members_settings() {
	
	// Get the options
	$settings = get_option( 'mtphr_members_settings', array() );
	
	$defaults = array(
		'slug' => 'members',
		'singular_label' => __( 'Member', 'mtphr-members' ),
		'plural_label' => __( 'Members', 'mtphr-members' )
	);
	$defaults = apply_filters( 'mtphr_members_default_settings', $defaults );
	
	return wp_parse_args( $settings, $defaults );
}



add_action( 'plugins_loaded', 'mtphr_members_localization' );
/**
 * Setup localization
 *
 * @since 1.0.0
 */
function mtphr_members_localization() {
  load_plugin_textdomain( 'mtphr-members', false, MTPHR_MEMBERS_DIR.'languages/' );
}




/**
 * Set a maximum excerpt length
 *
 * @since 1.0.0
 */
function mtphr_members_excerpt( $length = 200, $more = '&hellip;'  ) {
	echo get_mtphr_members_excerpt( $length, $more );
}
function get_mtphr_members_excerpt( $length = 200, $more = '&hellip;' ) {
	$excerpt = get_the_excerpt();
	$length++;
	
	$output = '';
	if ( mb_strlen( $excerpt ) > $length ) {
		$subex = mb_substr( $excerpt, 0, $length - mb_strlen($more) );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			$output .= mb_substr( $subex, 0, $excut );
		} else {
			$output .= $subex;
		}
		$output .= $more;
	} else {
		$output .= $excerpt;
	}
	return $output;
}




add_filter( 'mtphr_widgets_social_sites', 'mtphr_widgets_members_social_sites', 10, 2 );
/**
 * Override a Metaphor Social Links widget
 *
 * @since 1.0.0
 */
function mtphr_widgets_members_social_sites( $sites, $id ) {
	
	$widgets = get_post_meta( get_the_ID(), '_mtphr_members_social_override', true );
	
	if( is_array($widgets) ) {
		if( array_key_exists($id, $widgets) ) {
			$member_sites = get_post_meta( get_the_ID(), '_mtphr_members_social', true );
			return $member_sites;
		}
	}
	return $sites;
}




add_filter( 'mtphr_widgets_social_new_tab', 'mtphr_widgets_members_social_new_tab', 10, 2 );
/**
 * Override a Metaphor Social Links widget target
 *
 * @since 1.0.0
 */
function mtphr_widgets_members_social_new_tab( $new_tab, $id ) {
	
	$widgets = get_post_meta( get_the_ID(), '_mtphr_members_social_override', true );
	
	if( is_array($widgets) ) {
		if( array_key_exists($id, $widgets) ) {
			$member_new_tab = get_post_meta( get_the_ID(), '_mtphr_members_social_new_tab', true );
			if( $member_new_tab ) {
				return true;
			}
			return false;
		}
	}
	return $new_tab;
}




add_filter( 'mtphr_widgets_contact_info', 'mtphr_widgets_members_contact_info', 10, 2 );
/**
 * Override a Metaphor Contact widget
 *
 * @since 1.0.0
 */
function mtphr_widgets_members_contact_info( $contact_info, $id ) {
	
	$widgets = get_post_meta( get_the_ID(), '_mtphr_members_contact_override', true );
	
	if( is_array($widgets) ) {
		if( array_key_exists($id, $widgets) ) {
			$member_info = get_post_meta( get_the_ID(), '_mtphr_members_contact_info', true );
			return $member_info;
		}
	}
	return $contact_info;
}




add_filter( 'mtphr_widgets_twitter_name', 'mtphr_widgets_members_twitter_name', 10, 2 );
/**
 * Override a Metaphor Twitter widget
 *
 * @since 1.0.0
 */
function mtphr_widgets_members_twitter_name( $twitter_name, $id ) {
	
	$widgets = get_post_meta( get_the_ID(), '_mtphr_members_twitter_override', true );
	
	if( is_array($widgets) ) {
		if( array_key_exists($id, $widgets) ) {
			$member_twitter = get_post_meta( get_the_ID(), '_mtphr_members_twitter', true );
			return $member_twitter;
		}
	}
	return $twitter_name;
}




add_filter( 'dynamic_sidebar_params', 'mtphr_members_remove_widgets' );
/**
 * Remove unused widgets
 *
 * @since 1.0.3
 */
function mtphr_members_remove_widgets( $params ) {
	
	// Create an array to store disabled widgets
	$disabled_widget_ids = array();
	
	// Check for disabled contact widgets
	$widgets = get_post_meta( get_the_ID(), '_mtphr_members_contact_override', true );
	if( is_array($widgets) ) {
		$member_info = get_post_meta( get_the_ID(), '_mtphr_members_contact_info', true );
		if( count($member_info) == 1 && $member_info[0]['title'] == '' && $member_info[0]['description'] == '' ) {
			$disabled_widget_ids = array_merge($disabled_widget_ids, $widgets);
		}
	}
	
	// Check for disabled social widgets
	$widgets = get_post_meta( get_the_ID(), '_mtphr_members_social_override', true );
	if( is_array($widgets) ) {
		$member_sites = get_post_meta( get_the_ID(), '_mtphr_members_social', true );
		if( count($member_sites) == 1 && $member_sites[0]['link'] == '' ) {
			$disabled_widget_ids = array_merge($disabled_widget_ids, $widgets);
		}
	}
	
	// Check for disabled twitter handles
	$widgets = get_post_meta( get_the_ID(), '_mtphr_members_twitter_override', true );
	if( is_array($widgets) ) {
		$member_twitter = get_post_meta( get_the_ID(), '_mtphr_members_twitter', true );
		if( $member_twitter == '' ) {
			$disabled_widget_ids = array_merge($disabled_widget_ids, $widgets);
		}
	}
	
	// Create an array of the disabled widget keys
	$disabled_widgets = array();
	foreach( $params as $i=>$widget ) {
		if( isset($widget['widget_id']) ) {
			if( array_key_exists($widget['widget_id'], $disabled_widget_ids) ) {
				$disabled_widgets[] = $i;
			}
		}
	}
	
	// Remove the unused widgets
	$disabled_widgets = array_reverse($disabled_widgets);
	foreach( $disabled_widgets as $i ) {
		unset( $params[$i] );
	}
	
	return $params;
}




/**
 * Get an array of social links
 *
 * @since 1.0.0
 */
function mtphr_members_social_sites_array() {
	
	$social_sites = array(
		'twitter' => 'Twitter',
		'facebook' => 'Facebook',
		'linkedin' => 'LinkedIn',
		'googleplus' => 'Google+',
		'flickr' => 'Flickr',
		'tridadvisor' => 'TripAdvisor',
		'reddit' => 'reddit',
		'posterous' => 'Posterous',
		'plurk' => 'Plurk',
		'ebay' => 'eBay',
		'netvibes' => 'Netvibes',
		'picasa' => 'Picasa',
		'digg' => 'Digg',
		'newsvine' => 'Newsvine',
		'rss' => 'RSS',
		'stumbleupon' => 'StumbleUpon',
		'aim' => 'AIM',
		'youtube' => 'YouTube',
		'lastfm' => 'Last.fm',
		'myspace' => 'Myspace',
		'msn' => 'MSN',
		'paypal' => 'PayPal',
		'windows' => 'Windows',
		'wordpress' => 'WordPress',
		'yahoo' => 'Yahoo!',
		'dribble' => 'Dribble',
		'apple' => 'Apple',
		'bebo' => 'Bebo',
		'cargo' => 'Cargo',
		'ember' => 'Ember',
		'evernote' => 'Evernote',
		'googletalk' => 'Google Talk',
		'skype' => 'Skype',
		'feedburner' => 'Feedburner',
		'tumblr' => 'Tumblr',
		'android' => 'Android',
		'bing' => 'Bing',
		'metacafe' => 'Metacafe',
		'orkut' => 'Orkut',
		'delicious' => 'Delicious',
		'amazon' => 'Amazon',
		'grooveshark' => 'Grooveshark',
		'deviantart' => 'deviantART',
		'behance' => 'Behance',
		'vimeo' => 'Vimeo',
		'mobileme' => 'MobileMe',
		'magnolia' => 'Magnolia',
		'mixx' => 'Mixx',
		'blogger' => 'Blogger',
		'yahoobuzz' => 'Yahoo! Buzz'
	);
	
	return $social_sites;
}




/**
 * Display the social links
 *
 * @since 1.0.0
 */
function mtphr_members_social_sites( $id ) {

	$sites = get_post_meta( $id, '_mtphr_members_social', true );
	$new_tab = get_post_meta( $id, '_mtphr_members_social_new_tab', true );

	if( isset($sites[0]) ) {
	
		$t = ( $new_tab ) ? ' target="_blank"' : '';
		echo '<div class="mtphr-members-social-links clearfix">'; 
		
		foreach( $sites as $site ) {
			echo '<a class="mtphr-members-social-site mtphr-members-social-'.$site['site'].' mtphr-hover-anim" href="'.esc_url($site['link']).'"'.$t.'>'.$site['site'].'<span class="mtphr-hover-anim-target"></span></a>';
		}
		
		echo '</div>'; 	
	}
}

