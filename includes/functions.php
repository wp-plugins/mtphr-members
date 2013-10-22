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
 * @since 1.0.5
 */
function mtphr_members_localization() {
  load_plugin_textdomain( 'mtphr-members', false, 'mtphr-members/languages/' );
}




/**
 * Set a maximum excerpt length
 *
 * @since 1.0.0
 */
/*
function domino( $length = 200, $more = '&hellip;'  ) {
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
*/




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
 * @since 1.0.4
 */
function mtphr_members_remove_widgets( $params ) {

	if( !is_admin() && get_post_type() == 'mtphr_member' ) {

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
	}

	return $params;
}




/**
 * Get an array of social links
 *
 * @since 1.0.6
 */
function mtphr_members_social_sites_array() {

	$social_sites = array(
		'aim' => 'AIM',
		'amazon' => 'Amazon',
		'android' => 'Android',
		'apple' => 'Apple',
		'bebo' => 'Bebo',
		'behance' => 'Behance',
		'bing' => 'Bing',
		'blogger' => 'Blogger',
		'cargo' => 'Cargo',
		'delicious' => 'Delicious',
		'deviantart' => 'deviantART',
		'digg' => 'Digg',
		'dribble' => 'Dribble',
		'ebay' => 'eBay',
		'ember' => 'Ember',
		'evernote' => 'Evernote',
		'facebook' => 'Facebook',
		'feedburner' => 'Feedburner',
		'flickr' => 'Flickr',
		'googleplus' => 'Google+',
		'googletalk' => 'Google Talk',
		'grooveshark' => 'Grooveshark',
		'lastfm' => 'Last.fm',
		'linkedin' => 'LinkedIn',
		'magnolia' => 'Magnolia',
		'metacafe' => 'Metacafe',
		'mixx' => 'Mixx',
		'mobileme' => 'MobileMe',
		'msn' => 'MSN',
		'myspace' => 'Myspace',
		'netvibes' => 'Netvibes',
		'newsvine' => 'Newsvine',
		'orkut' => 'Orkut',
		'paypal' => 'PayPal',
		'picasa' => 'Picasa',
		'pinterest' => 'Pinterest',
		'plurk' => 'Plurk',
		'posterous' => 'Posterous',
		'reddit' => 'reddit',
		'rss' => 'RSS',
		'skype' => 'Skype',
		'stumbleupon' => 'StumbleUpon',
		'tripadvisor' => 'TripAdvisor',
		'tumblr' => 'Tumblr',
		'twitter' => 'Twitter',
		'vimeo' => 'Vimeo',
		'windows' => 'Windows',
		'wordpress' => 'WordPress',
		'yahoo' => 'Yahoo!',
		'yahoobuzz' => 'Yahoo! Buzz',
		'youtube' => 'YouTube'
	);

	return $social_sites;
}



/* --------------------------------------------------------- */
/* !Display the thumbnail - 1.0.7 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_thumbnail_display') ) {
function mtphr_members_thumbnail_display( $post_id=false, $permalink=false, $disable_permalinks=false ) {
	echo get_mtphr_members_thumbnail_display( $post_id, $permalink, $disable_permalinks );
}
}
if( !function_exists('get_mtphr_members_thumbnail_display') ) {
function get_mtphr_members_thumbnail_display( $post_id=false, $permalink=false, $disable_permalinks=false ) {

	$post_id = $post_id ? $post_id : get_the_id();
	$permalink = $permalink ? $permalink : get_permalink( $post_id );

	if( $thumb_id = get_post_thumbnail_id($post_id) ) {

		$thumb_size = apply_filters( 'mtphr_members_thumbnail_size', 'thumbnail' );
		$thumbnail = get_mtphr_members_thumbnail( $post_id, $thumb_size );
		$thumbnail = $disable_permalinks ? $thumbnail : '<a href="'.$permalink.'">'.$thumbnail.'</a>';
		return apply_filters( 'mtphr_members_thumbnail', $thumbnail, $thumb_size, $permalink, $disable_permalinks );
	}
}
}
if( !function_exists('get_mtphr_members_thumbnail') ) {
function get_mtphr_members_thumbnail( $post_id=false, $thumb_size=false ) {

	$post_id = $post_id ? $post_id : get_the_id();
	$thumb_size = $thumb_size ? $thumb_size : apply_filters( 'mtphr_members_thumbnail_size', 'thumbnail' );

	if( $thumb_id = get_post_thumbnail_id($post_id) ) {
		return get_the_post_thumbnail( $post_id, $thumb_size );
	}
}
}



/* --------------------------------------------------------- */
/* !Display the name - 1.0.7 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_name_display') ) {
function mtphr_members_name_display( $post_id=false, $permalink=false, $disable_permalinks=false ) {
	echo get_mtphr_members_name_display( $post_id, $permalink, $disable_permalinks );
}
}
if( !function_exists('get_mtphr_members_name_display') ) {
function get_mtphr_members_name_display( $post_id=false, $permalink=false, $disable_permalinks=false ) {

	$post_id = $post_id ? $post_id : get_the_id();
	$permalink = $permalink ? $permalink : get_permalink( $post_id );

	$member_name = get_mtphr_members_name( $post_id );
	if( $disable_permalinks ) {
		echo '<h3 class="mtphr-members-name">'.$member_name.'</h3>';
	} else {
		echo '<h3 class="mtphr-members-name"><a href="'.$permalink.'">'.$member_name.'</a></h3>';
	}
}
}
if( !function_exists('get_mtphr_members_name') ) {
function get_mtphr_members_name( $post_id=false ) {

	$post_id = $post_id ? $post_id : get_the_id();

	return apply_filters( 'mtphr_members_archive_name', get_the_title($post_id) );
}
}



/* --------------------------------------------------------- */
/* !Display the title - 1.0.7 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_title_display') ) {
function mtphr_members_title_display( $post_id=false ) {

	echo get_mtphr_members_title_display( $post_id );
}
}
if( !function_exists('get_mtphr_members_title_display') ) {
function get_mtphr_members_title_display( $post_id=false ) {

	$post_id = $post_id ? $post_id : get_the_id();

	$html = '';
	if( $title = get_mtphr_members_title($post_id) ) {
		$html .= '<p class="mtphr-members-title">'.$title.'</p>';
	}
	return $html;
}
}
if( !function_exists('get_mtphr_members_title') ) {
function get_mtphr_members_title( $post_id=false ) {

	$post_id = $post_id ? $post_id : get_the_id();

	return apply_filters( 'mtphr_members_archive_title', get_post_meta($post_id, '_mtphr_members_title', true) );
}
}



/* --------------------------------------------------------- */
/* !Display the contact info - 1.0.7 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_info_display') ) {
function mtphr_members_info_display( $post_id=false ) {

	echo get_mtphr_members_info_display( $post_id );
}
}
if( !function_exists('get_mtphr_members_info_display') ) {
function get_mtphr_members_info_display( $post_id=false ) {

	$post_id = $post_id ? $post_id : get_the_id();

	$html = '';
	$contact_info = get_post_meta( $post_id, '_mtphr_members_contact_info', true );
	if( is_array($contact_info) && count($contact_info) > 0 ) {

		$html .= '<table class="mtphr-members-info">';
		foreach( $contact_info as $i=>$info ) {
			$html .= '<tr>';
			if( $info['title'] != '' ) {
				$html .= '<th class="mtphr-members-info-title">'.apply_filters('the_content', $info['title']).'</th>';
				$html .= '<td>'.apply_filters('the_content', make_clickable($info['description'])).'</td>';
			} else {
				$html .= '<td colspan="2">'.apply_filters('the_content', make_clickable($info['description'])).'</td>';
			}
			$html .= '</tr>';
		}
		$html .= '</table>';
	}
	return $html;
}
}



/* --------------------------------------------------------- */
/* !Display the social links - 1.0.7 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_social_sites_display') ) {
function mtphr_members_social_sites_display( $post_id=false ) {
	echo get_mtphr_members_social_sites_display( $post_id );
}
}
if( !function_exists('get_mtphr_members_social_sites_display') ) {
function get_mtphr_members_social_sites_display( $post_id=false ) {

	$post_id = $post_id ? $post_id : get_the_id();
	$sites = get_post_meta( $post_id, '_mtphr_members_social', true );
	$new_tab = get_post_meta( $post_id, '_mtphr_members_social_new_tab', true );

	$html = '';
	if( isset($sites[0]) ) {
		$t = ( $new_tab ) ? ' target="_blank"' : '';
		$html .= '<div class="mtphr-members-social-links clearfix">';
		foreach( $sites as $site ) {
			$html .= '<a class="mtphr-members-social-site" href="'.esc_url($site['link']).'"'.$t.'><i class="mtphr-socon-'.$site['site'].'"></i></a>';
		}
		$html .= '</div>';
	}
	return $html;
}
}



/* --------------------------------------------------------- */
/* !Display the excerpt - 1.0.7 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_excerpt_display') ) {
function mtphr_members_excerpt_display( $post_id=false, $excerpt_length=140, $excerpt_more='&hellip;' ) {
	echo get_mtphr_members_excerpt_display( $post_id, $excerpt_length, $excerpt_more );
}
}
if( !function_exists('get_mtphr_members_excerpt_display') ) {
function get_mtphr_members_excerpt_display( $post_id=false, $excerpt_length=140, $excerpt_more='&hellip;' ) {

	$post_id = $post_id ? $post_id : get_the_id();

	$html = '';

	$links = array();
	preg_match('/{(.*?)\}/s', $excerpt_more, $links);
	if( isset($links[0]) ) {
		$more_link = '<a href="'.get_permalink($post_id).'">'.$links[1].'</a>';
		$excerpt_more = preg_replace('/{(.*?)\}/s', $more_link, $excerpt_more);
	}
	if( $excerpt_length <= 0 ) {
		if( !$excerpt = get_the_content() ) {
			$post = get_post( $post_id );
			$excerpt = $post->post_content;
		}
	} else {
		if( !$excerpt = get_the_excerpt() ) {
			$post = get_post( $post_id );
			$excerpt = ( $post->post_excerpt != '' ) ? $post->post_excerpt : $post->post_content;
		}
		$excerpt = wp_html_excerpt( $excerpt, intval($excerpt_length) );
	}
	$excerpt .= $excerpt_more;
	if( $excerpt_length <= 0 ) {
		$excerpt = apply_filters( 'the_content', $excerpt );
	}
	$html .= '<p class="mtphr-members-excerpt">'.apply_filters( 'mtphr_members_excerpt', $excerpt, $excerpt_length, $excerpt_more ).'</p>';

	return $html;
}
}



