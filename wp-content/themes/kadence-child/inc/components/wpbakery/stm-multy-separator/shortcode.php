<?php
/**
 * Shortcode handler for stm_multy_separator.
 *
 * @param array       $atts    Shortcode attributes.
 * @param string|null $content Shortcode content (unused).
 *
 * @return string
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_multy_separator_shortcode( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'stm_multy_separator', $atts );

	$defaults = array(
		'primary_color'   => '#196EC0',
		'secondary_color' => '#0AED80',
		'height'          => '3',
		'width'           => '15',
		'align'           => 'center',
		'css'             => '',
	);

	$atts = wp_parse_args( $atts, $defaults );

	return kadence_child_stm_multy_separator_render( $atts );
}

