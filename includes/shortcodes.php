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
 * @since 1.0.9
 */
function mtphr_members_archive_display( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'posts_per_page' => 9,
		'columns' => 3,
		'order' => 'DESC',
		'orderby' => 'menu_order',
		'categories' => false,
		'excerpt_length' => 140,
		'excerpt_more' => '&hellip;',
		'assets' => 'thumbnail,name,social,title,excerpt',
		'disable_permalinks' => false
	), $atts ) );
	
	// Override permalinks based on public attribute
	$mtphr_member = get_post_type_object('mtphr_member');
	if( !$mtphr_member->public ) {
		$disable_permalinks = true;
	}

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
		'order' => sanitize_text_field( $order ),
		'orderby' => sanitize_text_field( $orderby ),
		'paged' => $page,
		'posts_per_page' => intval($posts_per_page)
	);
	if( $categories ) {
		$category_array = explode(',', $categories);
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'mtphr_member_category',
				'field' => 'slug',
				'terms' => $category_array
			)
		);
	}

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
			<<?php echo $container; ?> id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
				<?php do_action( 'mtphr_members_top' ); ?>

				<?php
				$permalink = ( $categories ) ? add_query_arg( array('taxonomy' => 'mtphr_member_category', 'terms' => $categories), get_permalink() ) : remove_query_arg( array('taxonomy', 'terms'), get_permalink() );
				foreach( $asset_order as $asset ) {

					switch( trim($asset) ) {

						case 'thumbnail':
							mtphr_members_thumbnail_display( get_the_id(), $permalink, $disable_permalinks );
							break;

						case 'name':
							mtphr_members_name_display( get_the_id(), $permalink, $disable_permalinks );
							break;

						case 'social':
							mtphr_members_social_sites_display( get_the_id() );
							break;

						case 'title':
							mtphr_members_title_display( get_the_id() );
							break;

						case 'info':
							mtphr_members_info_display( get_the_id() );
							break;

						case 'excerpt':
							mtphr_members_excerpt_display( get_the_id(), $excerpt_length, $excerpt_more, $disable_permalinks );
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
