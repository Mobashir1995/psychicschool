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

	$color = isset( $atts['color'] ) ? $atts['color'] : '';
	$color = sanitize_hex_color( $color );
	if ( empty( $color ) ) {
		$color = '#fdc735';
	}

	$css = isset( $atts['css'] ) ? $atts['css'] : '';
	$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
	if ( empty( $css_class ) ) {
		$css_class = 'kadence_color_separator_';
	}

	$unique = 'stm_color_separator_' . substr( md5( wp_json_encode( $atts ) . microtime( true ) ), 0, 10 );

	$atts['color']         = $color;
	$atts['css_class']     = $css_class;
	$atts['unique']        = $unique;
	$atts['inline_styles'] = "
		.{$unique} .kadence_triangled_colored_separator {
			background-color: {$color} !important;
		}
		.{$unique} .kadence_triangled_colored_separator .triangle {
			border-bottom-color: {$color} !important;
		}
	";

	return kadence_child_stm_color_separator_render( $atts );
}

