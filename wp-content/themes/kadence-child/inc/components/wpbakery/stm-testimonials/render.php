<?php
/**
 * Kadence Child - Testimonials markup (Swiper carousel like product categories).
 *
 * @param array $atts Processed shortcode attributes.
 *
 * @return string
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_testimonials_render( $atts ) {
	$title                   = isset( $atts['testimonials_title'] ) ? $atts['testimonials_title'] : '';
	$max_num                 = isset( $atts['testimonials_max_num'] ) ? (int) $atts['testimonials_max_num'] : 0;
	$text_color              = isset( $atts['testimonials_text_color'] ) ? $atts['testimonials_text_color'] : '#aaaaaa';
	$style                   = isset( $atts['style'] ) ? $atts['style'] : 'style_1';
	$slides_per_row          = isset( $atts['testimonials_slides_per_row'] ) ? (int) $atts['testimonials_slides_per_row'] : 2;

	$args = array(
		'post_type'      => 'testimonial',
		'post_status'    => 'publish',
		'posts_per_page' => $max_num > 0 ? $max_num : -1,
	);

	$query = new WP_Query( $args );
	if ( ! $query->have_posts() ) {
		return '';
	}

	$slides_per_row = max( 1, min( 4, $slides_per_row ) );

	ob_start();
	?>
	<div class="kadence_testimonials_wrapper kadence_testimonials_<?php echo esc_attr( $style ); ?>">
		<div class="kadence_testimonials_header">
			<?php if ( $title ) : ?>
				<h2 class="kadence_testimonials_section_title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>
			<div class="kadence_testimonials_button_wrapper">
				<div class="kadence_testimonials_button kadence_testimonials_button_prev"><i class="fa fa-chevron-left"></i></div>
				<div class="kadence_testimonials_button kadence_testimonials_button_next"><i class="fa fa-chevron-right"></i></div>
			</div>
		</div>

		<div class="swiper kadence_testimonials_swiper"
			 data-per-row="<?php echo esc_attr( $slides_per_row ); ?>">
			<div class="swiper-wrapper testimonials-carousel-init">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					$profession = get_post_meta( get_the_ID(), 'testimonial_profession', true );
					?>
					<div class="swiper-slide">
						<div class="kadence_testimonial_item" style="color:<?php echo esc_attr( $text_color ); ?>;">
							<div class="kadence_testimonial_meta">
								<h4><?php the_title(); ?></h4>
								<?php if ( $profession ) : ?>
									<div class="kadence_testimonial_profession"><?php echo esc_html( $profession ); ?></div>
								<?php endif; ?>
							</div>
							<div class="kadence_testimonial_content">
								<?php the_excerpt(); ?>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
	<?php
	wp_reset_postdata();

	return ob_get_clean();
}

