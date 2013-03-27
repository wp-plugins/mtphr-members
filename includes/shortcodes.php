<?php
/**
 * Shortcodes
 *
 * @package Metaphor Members
 */




add_shortcode( 'mtphr_members_archive', 'mtphr_members_archive_display' );
/**
 * Display the members archive.
 *
 * @since 1.0.2
 */
function mtphr_members_archive_display( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'posts_per_page' => 9,
		'columns' => 3,
		'excerpt_length' => 140,
		'excerpt_more' => '&hellip;',
		'assets' => 'thumbnail,name,social,title,excerpt'
	), $atts ) );
	
	// Set the responsiveness of the grid
	$row = apply_filters( 'mtphr_members_responsive_grid', false );
	$row_class = $row ? 'mtphr-members-row-responsive' : 'mtphr-members-row';
	
	// Filter the container
	$container = apply_filters( 'mtphr_members_container', 'article' );
	
	// Set the span
	$span = intval(12/intval($columns));
	
	// Create an array of the order
	$asset_order = explode( ',', $assets );
	
	$page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args = array(
		'post_type'=> 'mtphr_member',
		'paged' => $page,
		'posts_per_page' => intval($posts_per_page)
	);
	
	// Save the original query & create a new one
	global $wp_query;
	$original_query = $wp_query;
	$wp_query = null;
	$wp_query = new WP_Query();
	$wp_query->query( $args );
	?>
	
	<?php ob_start(); ?>
	
	<div class="mtphr-members-archive">

	<?php if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
		
		<?php
		// Get the count
		$count = ( $wp_query->current_post );
		if( $count%intval($columns) == 0 ) {
			echo '<div class="'.$row_class.'">';
		}
		?>
		
		<div class="mtphr-members-grid<?php echo $span; ?>">
			
			<?php do_action( 'mtphr_members_before' ); ?>
			<<?php echo $container; ?> id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php do_action( 'mtphr_members_top' ); ?>

				<?php
				foreach( $asset_order as $asset ) {
					
					switch( trim($asset) ) {
						
						case 'thumbnail':
							
							// Display the member thumb
							if( $thumb_id = get_post_thumbnail_id() ) {
				
								// Get the thumb image
								$thumb_size = apply_filters( 'mtphr_members_thumbnail_size', 'thumbnail' );
								$thumbnail = get_the_post_thumbnail( get_the_id(), $thumb_size );
								echo '<a href="'.get_permalink().'">'.apply_filters( 'mtphr_members_thumbnail', $thumbnail, $thumb_size ).'</a>';
							}
							break;
							
						case 'name':
							
							// Display the member name
							echo '<h3 class="mtphr-members-name"><a href="'.get_permalink().'">'.apply_filters( 'mtphr_members_archive_name', get_the_title() ).'</a></h3>';
							break;
							
						case 'social':
							
							// If there is at least one site
							$sites = get_post_meta( get_the_ID(), '_mtphr_members_social', true );
							$new_tab = get_post_meta( get_the_ID(), '_mtphr_members_social_new_tab', true );
							$limit = intval(get_post_meta( get_the_ID(), '_mtphr_members_social_limit', true ));
							
							// Trim the number of sites
							if( $limit > 0 ) {
								$sites = array_splice( $sites, 0, $limit );
							}
							
							if( isset($sites[0]) ) {
							
								$t = ( $new_tab ) ? ' target="_blank"' : '';
								echo '<div class="mtphr-members-social-links clearfix">'; 
								
								foreach( $sites as $site ) {
									echo '<a class="mtphr-members-social-site mtphr-members-social-'.$site['site'].' mtphr-hover-anim" href="'.esc_url($site['link']).'"'.$t.'>'.$site['site'].'<span class="mtphr-hover-anim-target"></span></a>';
								}
								
								echo '</div>'; 	
							}
							break;
							
						case 'title':
							// Display the member title
							echo '<p class="mtphr-members-title">'.apply_filters( 'mtphr_members_archive_title', $title ).'</p>';
							break;
							
						case 'excerpt':
						
							// Get the excerpt
							$excerpt = '';
							if( $excerpt_length > 0 ) {
							
								$links = array();
								preg_match('/{(.*?)\}/s', $excerpt_more, $links);
								if( isset($links[0]) ) {
									$more_link = '<a href="'.get_permalink().'">'.$links[1].'</a>';
									$excerpt_more = preg_replace('/{(.*?)\}/s', $more_link, $excerpt_more);
								}
								$excerpt = get_mtphr_members_excerpt( $excerpt_length, $excerpt_more );
							}
							
							// Display the member excerpt
							echo '<p class="mtphr-members-excerpt">'.apply_filters( 'mtphr_members_excerpt', $excerpt, $excerpt_length, $excerpt_more ).'</p>';
							break;
					}
				}
				?>
				
				<?php do_action( 'mtphr_members_bottom' ); ?>
			</<?php echo $container; ?>><!-- #post-<?php the_ID(); ?> -->
			<?php do_action( 'mtphr_members_after' ); ?>
		
		</div>
		
		<?php
		// Get the count
		$count = $count+1;
		if( $count%intval($columns) == 0 || $count == $wp_query->post_count ) {
			echo '</div>';
		}
		?>

	<?php
	endwhile;		
	else :
	endif;
	?>
	
	<?php if ( $wp_query->max_num_pages > 1 ) { ?>
	
		<?php ob_start(); ?>
		<nav class="mtphr-members-content-nav clearfix">
			<?php if( $prev = get_previous_posts_link(__('Newer', 'mtphr-members')) ) { ?>
			<div class="mtphr-members-nav-next"><?php echo $prev; ?></div>
			<?php } ?>
			<?php if( $next = get_next_posts_link(__('Older', 'mtphr-members')) ) { ?>
			<div class="mtphr-members-nav-previous"><?php echo $next; ?></div>
			<?php } ?>
		</nav>
		
		<?php echo apply_filters( 'mtphr_members_archive_navigation', ob_get_clean() ); ?>

	<?php } ?>
	
	<?php
	$wp_query = null;
	$wp_query = $original_query;
	wp_reset_postdata();
	?>
	
	</div><!-- .mtphr-members-archive -->

	<?php
	// Return the output
	return ob_get_clean();
}
