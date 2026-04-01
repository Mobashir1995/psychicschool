<?php
/**
 * Shortcode handler for stm_testimonials.
 *
 * @param array       $atts    Shortcode attributes.
 * @param string|null $content Shortcode content (unused).
 *
 * @return string
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_testimonials_shortcode( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'stm_testimonials', $atts );

	$pairs = array(
		'testimonials_title'       => '',
		'testimonials_max_num'     => '',
		'testimonials_text_color'  => '#aaaaaa',
		'style'                    => 'style_1',
		'testimonials_slides_per_row' => 2,
	);

	$atts = wp_parse_args( $atts, $pairs );

	return kadence_child_stm_testimonials_render( $atts );
}

