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
	$section_title         = isset( $atts['experts_title'] ) ? $atts['experts_title'] : '';
	$teachers_count        = isset( $atts['teachers_count'] ) ? (int) $atts['teachers_count'] : 8;
	$expert_slides_per_row = isset( $atts['expert_slides_per_row'] ) ? (int) $atts['expert_slides_per_row'] : 2;
	$autoplay_speed        = isset( $atts['expert_autoplay_speed'] ) ? (int) $atts['expert_autoplay_speed'] : 5000;
	$orderby               = isset( $atts['orderby'] ) ? $atts['orderby'] : 'date';
	$order                 = isset( $atts['order'] ) ? $atts['order'] : 'DESC';
	$css                   = isset( $atts['css'] ) ? $atts['css'] : '';

	$expert_slides_per_row = max( 1, min( 2, $expert_slides_per_row ) );
	if ( $autoplay_speed < 500 ) {
		$autoplay_speed = 500;
	}

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
		<div class="kadence_testimonials_header kadence_experts_header">
			<?php if ( $section_title ) : ?>
				<h2 class="kadence_testimonials_section_title kadence_experts_section_title"><?php echo esc_html( $section_title ); ?></h2>
			<?php endif; ?>
			<div class="kadence_experts_header_right">
				<?php
				$archive_link = get_post_type_archive_link( $post_type );
				if ( $archive_link ) :
					?>
					<a class="kadence_experts_view_all" href="<?php echo esc_url( $archive_link ); ?>">
						<?php esc_html_e( 'View all', 'kadence-child' ); ?>
					</a>
				<?php endif; ?>
				<div class="kadence_testimonials_button_wrapper">
					<div class="kadence_testimonials_button kadence_experts_button_prev"><i class="fa fa-chevron-left"></i></div>
					<div class="kadence_testimonials_button kadence_experts_button_next"><i class="fa fa-chevron-right"></i></div>
				</div>
			</div>
		</div>

		<div class="swiper kadence_experts_swiper" data-per-row="<?php echo esc_attr( $expert_slides_per_row ); ?>" data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>">
			<div class="swiper-wrapper">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				$position  = get_post_meta( get_the_ID(), 'expert_sphere', true );
				?>
				<div class="swiper-slide">
					<div class="kadence_experts_grid_item">
						<div class="expert-card">
							<div class="expert-thumb-wrap">
								<a href="<?php echo esc_url( get_permalink() ); ?>">
									<?php
									if ( has_post_thumbnail() ) {
										the_post_thumbnail( 'thumbnail' );
									}
									?>
								</a>
							</div>
							<div class="expert-body">
								<div class="expert-name">
									<a href="<?php echo esc_url( get_permalink() ); ?>">
										<?php echo esc_html( get_the_title() ); ?>
									</a>
								</div>
								<?php if ( ! empty( $position ) ) : ?>
									<div class="expert-role">
										<?php echo esc_html( $position ); ?>
									</div>
								<?php endif; ?>
								<div class="expert-excerpt">
									<?php the_excerpt(); ?>
								</div>
							</div>
						</div>
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

