<?php
/**
 * Shortcode handler for stm_color_separator.
 * Processes attributes and delegates to the render function.
 *
 * @param array       $atts    Shortcode attributes.
 * @param string|null $content Shortcode content (unused).
 *
 * @return string
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_color_separator_shortcode( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'stm_color_separator', $atts );

	$atts['css_class'] = isset( $atts['css'] )
		? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ) )
		: '';

	return kadence_child_stm_color_separator_render( $atts );
}

