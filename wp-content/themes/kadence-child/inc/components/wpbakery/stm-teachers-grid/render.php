<?php
/**
 * Kadence Child - Teachers Grid markup.
 *
 * Displays a grid of "teachers" posts similar in spirit to MasterStudy Teachers Grid.
 *
 * @param array $atts Processed shortcode attributes.
 *
 * @return string
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_teachers_grid_render( $atts ) {
	$per_page   = isset( $atts['per_page'] ) ? (int) $atts['per_page'] : 8;
	$image_size = ! empty( $atts['image_size'] ) ? $atts['image_size'] : 'medium';
	$pagination = isset( $atts['pagination'] ) ? $atts['pagination'] : 'show';
	$css        = isset( $atts['css'] ) ? $atts['css'] : '';

	// Allow overriding the post type via filter, default to "teachers" CPT.
	$post_type = apply_filters( 'kadence_child_stm_teachers_grid_post_type', 'teachers', $atts );

	$paged = 1;
	if ( 'show' === $pagination ) {
		// Support pagination on static pages and archives.
		$paged = max(
			1,
			get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' )
		);
	}

	$args = array(
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'posts_per_page' => $per_page > 0 ? $per_page : -1,
		'paged'          => $paged,
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		return '';
	}

	// Build wrapper classes, including any VC css class.
	$wrapper_classes   = array( 'kadence_teachers_grid_wrapper' );
	$wrapper_classes[] = 'kadence_teachers_grid_pagination_' . ( 'show' === $pagination ? 'on' : 'off' );
	if ( ! empty( $css ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
		$wrapper_classes[] = vc_shortcode_custom_css_class( $css );
	}

	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', array_filter( $wrapper_classes ) ) ); ?>">
		<div class="kadence_teachers_grid">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				$teacher_id = get_the_ID();
				$position   = get_post_meta(get_the_id(), 'expert_sphere', true);
				?>
				<div class="kadence_teachers_grid_item">
					<a href="<?php echo esc_url( get_permalink( $teacher_id ) ); ?>" class="teacher-item">
						<div class="teacher-thumbnail">
							<?php
							if ( has_post_thumbnail( $teacher_id ) ) {
								echo get_the_post_thumbnail( $teacher_id, $image_size, array( 'class' => 'teacher-thumb-img' ) );
							}
							?>
						</div>
					</a>
					<div class="teacher-info">
						<div class="teacher-name">
							<a href="<?php echo esc_url( get_permalink( $teacher_id ) ); ?>" title="<?php echo esc_html( get_the_title( $teacher_id ) ); ?>"><?php echo esc_html( get_the_title( $teacher_id ) ); ?></a>
						</div>
						<?php if ( ! empty( $position ) ) : ?>
							<div class="teacher-role">
								<?php echo esc_html( $position ); ?>
							</div>
						<?php endif; ?>
					</div>
					<div class="teacher-content">
						<?php the_excerpt(); ?>
					</div>
				</div>
				<?php
			endwhile;
			?>
		</div>
		<?php if ( 'show' === $pagination && $query->max_num_pages > 1 ) : ?>
			<div class="kadence_teachers_grid_pagination">
				<?php
				echo wp_kses_post(
					paginate_links(
						array(
							'total'   => $query->max_num_pages,
							'current' => $paged,
							'type'    => 'list',
						)
					)
				);
				?>
			</div>
		<?php endif; ?>
	</div>
	<?php

	wp_reset_postdata();

	return ob_get_clean();
}

