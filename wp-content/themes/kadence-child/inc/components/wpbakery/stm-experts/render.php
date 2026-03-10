<?php
/**
 * Kadence Child - STM Experts grid markup.
 *
 * Displays a grid of expert posts (teachers CPT by default).
 *
 * @param array $atts Processed shortcode attributes.
 *
 * @return string
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_experts_render( $atts ) {
	$per_page   = isset( $atts['per_page'] ) ? (int) $atts['per_page'] : 8;
	$image_size = ! empty( $atts['image_size'] ) ? $atts['image_size'] : 'medium';
	$pagination = isset( $atts['pagination'] ) ? $atts['pagination'] : 'hide';
	$orderby    = isset( $atts['orderby'] ) ? $atts['orderby'] : 'date';
	$order      = isset( $atts['order'] ) ? $atts['order'] : 'DESC';
	$css        = isset( $atts['css'] ) ? $atts['css'] : '';

	// Allow overriding the post type via filter, default to "teachers" CPT (experts).
	$post_type = apply_filters( 'kadence_child_stm_experts_post_type', 'teachers', $atts );

	$paged = 1;
	if ( 'show' === $pagination ) {
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
		'orderby'        => $orderby,
		'order'          => $order,
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		return '';
	}

	$wrapper_classes   = array( 'kadence_experts_grid_wrapper' );
	$wrapper_classes[] = 'kadence_experts_grid_pagination_' . ( 'show' === $pagination ? 'on' : 'off' );
	if ( ! empty( $css ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
		$wrapper_classes[] = vc_shortcode_custom_css_class( $css );
	}

	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', array_filter( $wrapper_classes ) ) ); ?>">
		<div class="kadence_experts_grid">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				$expert_id = get_the_ID();
				$position  = get_post_meta( $expert_id, 'teacher_position', true );
				?>
				<div class="kadence_experts_grid_item">
					<a href="<?php echo esc_url( get_permalink( $expert_id ) ); ?>" class="expert-card">
						<div class="expert-thumb-wrap">
							<?php
							if ( has_post_thumbnail( $expert_id ) ) {
								echo get_the_post_thumbnail( $expert_id, $image_size, array( 'class' => 'expert-thumb-img' ) );
							}
							?>
						</div>
						<div class="expert-body">
							<div class="expert-name">
								<?php echo esc_html( get_the_title( $expert_id ) ); ?>
							</div>
							<?php if ( ! empty( $position ) ) : ?>
								<div class="expert-role">
									<?php echo esc_html( $position ); ?>
								</div>
							<?php endif; ?>
							<div class="expert-excerpt">
								<?php echo wp_kses_post( wp_trim_words( get_the_excerpt( $expert_id ), 18 ) ); ?>
							</div>
						</div>
					</a>
				</div>
				<?php
			endwhile;
			?>
		</div>
		<?php if ( 'show' === $pagination && $query->max_num_pages > 1 ) : ?>
			<div class="kadence_experts_grid_pagination">
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

