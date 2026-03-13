<?php
/**
 * Kadence Child - STM Experts carousel markup.
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
	$section_title        = isset( $atts['experts_title'] ) ? $atts['experts_title'] : '';
	$teachers_count       = isset( $atts['teachers_count'] ) ? (int) $atts['teachers_count'] : 8;
	$expert_slides_per_row = isset( $atts['expert_slides_per_row'] ) ? (int) $atts['expert_slides_per_row'] : 2;
	$orderby              = isset( $atts['orderby'] ) ? $atts['orderby'] : 'date';
	$order                = isset( $atts['order'] ) ? $atts['order'] : 'DESC';
	$css                  = isset( $atts['css'] ) ? $atts['css'] : '';

	$expert_slides_per_row = max( 1, min( 2, $expert_slides_per_row ) );

	// Allow overriding the post type via filter, default to "teachers" CPT (experts).
	$post_type = apply_filters( 'kadence_child_stm_experts_post_type', 'teachers', $atts );

	$args = array(
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'posts_per_page' => $teachers_count > 0 ? $teachers_count : -1,
		'orderby'        => $orderby,
		'order'          => $order,
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		return '';
	}

	$wrapper_classes   = array( 'kadence_experts_carousel_wrapper' );
	if ( ! empty( $css ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
		$wrapper_classes[] = vc_shortcode_custom_css_class( $css );
	}

	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', array_filter( $wrapper_classes ) ) ); ?>">
		<?php if ( $section_title ) : ?>
			<h2 class="kadence_experts_section_title"><?php echo esc_html( $section_title ); ?></h2>
		<?php endif; ?>

		<div class="swiper kadence_experts_swiper" data-per-row="<?php echo esc_attr( $expert_slides_per_row ); ?>">
			<div class="swiper-wrapper">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				$expert_id = get_the_ID();
				$position  = get_post_meta( $expert_id, 'teacher_position', true );
				?>
				<div class="swiper-slide">
					<div class="kadence_experts_grid_item">
						<a href="<?php echo esc_url( get_permalink( $expert_id ) ); ?>" class="expert-card">
							<div class="expert-thumb-wrap">
								<?php
								if ( has_post_thumbnail( $expert_id ) ) {
									echo get_the_post_thumbnail( $expert_id, 'medium', array( 'class' => 'expert-thumb-img' ) );
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
				</div>
				<?php
			endwhile;
			?>
			</div>
		</div>
	</div>
	<?php

	wp_reset_postdata();

	return ob_get_clean();
}

